<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 11/11/2016
 * Time: 16:47
 */

namespace Hogart\Lk\Search;


use Bitrix\Iblock\ElementTable;
use Bitrix\Main\DB\Result;

class MainSearchSuggest extends AbstractSearch
{
    function getIndex()
    {
        return "main";
    }

    function getType()
    {
        return "site";
    }

    public function indexById($ids = null)
    {
        if (null === $ids) return false;

        if (!is_array($ids)) $ids = [$ids];

        return $this->indexAll([
            '=ID' => $ids
        ]);
    }

    public function deleteItemFromIndex($id)
    {
        return $this->client->delete([
            'id' => $id,
            'index' => $this->getIndexName(),
            'type' => $this->getType()
        ]);
    }

    /**
     * @param array $filter
     * @return array
     */
    public function indexAll($filter = [])
    {
        $items = ElementTable::getList([
            'filter' => array_merge([
                '=IBLOCK.ACTIVE' => true,
                '=ACTIVE' => true,
            ], $filter),
            'select' => [
                'SITE' => 'IBLOCK.Bitrix\Iblock\IblockSiteTable:IBLOCK.SITE_ID',
                'NAME',
                'ID',
                'IBLOCK_SECTION_ID',
                'IBLOCK_ID',
                'BLOCK_NAME' => 'IBLOCK.NAME',
                'XML_ID',
                'CODE',
                'PREVIEW_TEXT',
                'DETAIL_TEXT',
                'DETAIL_PAGE_URL' => 'IBLOCK.DETAIL_PAGE_URL'
            ]
        ])->fetchAll();

        return $this->index($items);
    }

    /**
     * @param array|Result $items
     * @return array
     */
    public function index($items)
    {
        $responses = [];

        if ($items instanceof Result) {
            $items = $items->fetchAll();
        }

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

    protected function indexItem($element, $props = [])
    {
        $params[] = [
            'index' => [
                '_index' => $this->getIndexName(),
                '_type' => $this->getType(),
                '_id' => $element['ID'],
            ]
        ];
        $item = [
            'url' => \CIBlock::ReplaceDetailUrl($element['DETAIL_PAGE_URL'], $element, false, 'E'),
            'site' => $element['SITE'],
            'title' => $element['NAME'],
            'xml_id' => $element['XML_ID'],
            'block_id' => $element['IBLOCK_ID'],
            'block' => $element['BLOCK_NAME'],
            'preview_text' => strip_tags($element['PREVIEW_TEXT']),
            'detail_text' => strip_tags($element['DETAIL_TEXT']),
        ];
        if ($element['IBLOCK_ID'] == CATALOG_IBLOCK_ID) {
            foreach ($props[$element['ID']] as $propName => $prop) {
                $item[$propName] = $prop['VALUE'];
            }
        }

        $params[] = $item;

        return $params;
    }

    public function search($term, $site, $size = 20)
    {
        $data = [
            'sort' => 'block_id:asc,_score:desc',
            'index' => $this->getIndexName(),
            'type' => $this->getType(),
            'body' => [
                'size' => $size,
                'query' => [
                    'bool' => [
                        'must' => [
                            [
                                'match' => [
                                    'content' => [
                                        'query' => $term,
                                        'operator' => 'and'
                                    ],
                                ],
                            ],
                            [
                                'match' => [
                                    'site' => [
                                        'query' => $site,
                                        'operator' => 'and'
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];
        return parent::search($data);
    }


    /**
     * @return $this
     */
    public function createIndex()
    {
        if (!$this->client->indices()->exists([
            'index' => $this->getIndexName()
        ])) {
            $this->client->indices()->create([
                'index' => $this->getIndexName(),
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
                        $this->getType() => [
                            'properties' => [
                                'sku' => [
                                    'type' => 'string',
                                    'analyzer' => 'sku_analyzer',
                                    'search_analyzer' => 'whitespace_analyzer',
                                    'copy_to' => 'content',
                                ],
                                'block' => [
                                    'type' => 'string',
                                    'analyzer' => 'russian',
                                    'copy_to' => 'content',
                                ],
                                'title' => [
                                    'type' => 'string',
                                    'analyzer' => 'russian',
                                    'copy_to' => 'content',
                                ],
                                'preview_text' => [
                                    'type' => 'string',
                                    'analyzer' => 'russian',
                                    'copy_to' => 'content',
                                ],
                                'detail_text' => [
                                    'type' => 'string',
                                    'analyzer' => 'russian',
                                    'copy_to' => 'content',
                                ],
                                'xml_id' => [
                                    'type' => 'string',
                                    'index' => 'not_analyzed'
                                ],
                                'url' => [
                                    'type' => 'string',
                                    'index' => 'not_analyzed'
                                ],
                                'block_id' => [
                                    'type' => 'integer',
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
}