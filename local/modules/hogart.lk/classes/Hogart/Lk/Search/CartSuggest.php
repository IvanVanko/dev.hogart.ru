<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 14/10/2016
 * Time: 22:02
 */

namespace Hogart\Lk\Search;


use Bitrix\Iblock\ElementTable;
use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use Hogart\Lk\Creational\Singleton;
use Hogart\Lk\Debug\Timer;

class CartSuggest
{
    use Singleton;

    const INDEX = 'cart';
    const TYPE = 'item';

    /** @var  Client */
    protected $client;
    /** @var  string */
    protected $indexName;

    protected function create()
    {
        $this->client = ClientBuilder::create()->build();
        $this->indexName = $this->getIndexName();
    }

    protected function getIndexName()
    {
        global $DB;
        if (null == $this->indexName) {
            $this->indexName = vsprintf("%s-%s", [self::INDEX, md5(serialize($DB))]);
        }
        return $this->indexName;
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @return $this
     */
    public function createIndex()
    {
        if (!$this->client->indices()->exists([
            'index' => $this->indexName
        ])) {
            $this->client->indices()->create([
                'index' => $this->indexName,
                'body' => [
                    'settings' => [
                        'analysis' => [
                            'filter' => [
                                'nGram_filter' => [
                                    'type' => 'nGram',
                                    'min_gram' => 1,
                                    'max_gram' => 20,
                                    'token_chars' => [
                                        'letter',
                                        'digit',
                                        'punctuation',
                                        'symbol'
                                    ]
                                ]
                            ],
                            'analyzer' => [
                                'sku_analyzer' => [
                                    'type' => 'custom',
                                    'tokenizer' => 'whitespace',
                                    'filter' => [
                                        'lowercase',
                                        'asciifolding',
                                        'nGram_filter'
                                    ]
                                ],
                                'content_analyzer' => [
                                    'type' => 'custom',
                                    'tokenizer' => 'whitespace',
                                    'filter' => [
                                        'lowercase',
                                        'asciifolding',
                                        'nGram_filter'
                                    ]
                                ],
                                'whitespace_analyzer' => [
                                    'type' => 'custom',
                                    'tokenizer' => 'whitespace',
                                    'filter' => [
                                        'lowercase',
                                        'asciifolding'
                                    ]
                                ]
                            ]
                        ]
                    ],
                    'mappings' => [
                        self::TYPE => [
                            'properties' => [
                                'sku' => [
                                    'type' => 'string',
                                    'analyzer' => 'sku_analyzer',
                                    'search_analyzer' => 'whitespace_analyzer',
                                    'copy_to' => 'content',
                                ],
                                'title' => [
                                    'type' => 'string',
                                    'analyzer' => 'russian',
                                    'copy_to' => 'content',
                                ],
                                'xml_id' => [
                                    'type' => 'string',
                                    'index' => 'not_analyzed'
                                ],
                                'content' => [
                                    'type' => 'string',
                                    'analyzer' => 'content_analyzer',
                                    'search_analyzer' => 'whitespace_analyzer'
                                ]
                            ]
                        ]
                    ]
                ]
            ]);
        }
        return $this;
    }

    /**
     * @return array
     */
    public function deleteIndex()
    {
        if ($this->client->indices()->exists([
            'index' => $this->indexName
        ])) {
            return $this->client->indices()->delete([
                'index' => $this->indexName
            ]);
        }
    }

    /**
     * @return array
     */
    public function indexAll()
    {
        return $this->index(ElementTable::getList([
            'filter' => ['=IBLOCK_ID' => CATALOG_IBLOCK_ID],
            'select' => ['NAME', 'ID', 'XML_ID'],
        ])->fetchAll());
    }

    /**
     * @param $items
     * @return array
     */
    public function index($items)
    {
        $responses = [];
        $props = array_reduce($items, function ($result, $item) {
            $result[$item['ID']] = $item;
            return $result;
        }, []);
        \CIBlockElement::GetPropertyValuesArray($props, CATALOG_IBLOCK_ID, ['ID' => array_keys($props)], ['CODE' => ['sku']]);
        $rows = [];
        $i = 1;
        foreach ($items as $item) {
            $rows = array_merge($rows, $this->indexItem($item, $props));
            if ($i % 1000 == 0) {
                $responses = array_merge($responses, $this->client->bulk(['body' => $rows]));
                $rows = [];
            }
            $i++;
        }

        if (!empty($rows)) {
            $responses = array_merge($responses, $this->client->bulk(['body' => $rows]));
        }

        return $responses;
    }

    public function indexItem($element, $props = [])
    {
        $properties = $props[$element['ID']];
        $params[] = [
            'index' => [
                '_index' => $this->indexName,
                '_type' => self::TYPE,
                '_id' => $element['ID'],
            ]
        ];
        $params[] = [
            'title' => $element['NAME'],
            'sku' => $properties['sku']['VALUE'],
            'xml_id' => $element['XML_ID']
        ];
        return $params;
    }

    public function search($term, $size = 20)
    {
        return $this->client->search([
            'index' => $this->indexName,
            'type' => self::TYPE,
            'body' => [
                'size' => $size,
                'query' => [
                    'match' => [
                        'content' => [
                            'query' => $term,
                            'operator' => 'and'
                        ],
                    ],
                ]
            ]
        ]);
    }
}