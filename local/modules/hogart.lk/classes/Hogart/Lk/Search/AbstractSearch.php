<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 11/11/2016
 * Time: 16:48
 */

namespace Hogart\Lk\Search;


use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use Hogart\Lk\Creational\Singleton;

abstract class AbstractSearch
{
    use Singleton;

    /** @var  Client */
    protected $client;
    /** @var  string */
    protected $indexName;

    protected function create()
    {
        $this->client = ClientBuilder::create()->build();
        $this->indexName = $this->getIndexName();
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    public function getIndexName()
    {
        if (null == $this->indexName) {
            $this->indexName = vsprintf("%s-%s", [$this->getIndex(), substr(md5(serialize($_SERVER["DOCUMENT_ROOT"])), -6)]);
        }
        return $this->indexName;
    }

    /**
     * @return array|null
     */
    public function deleteIndex()
    {
        if ($this->client->indices()->exists([
            'index' => $this->getIndexName()
        ])) {
            return $this->client->indices()->delete([
                'index' => $this->getIndexName()
            ]);
        }
        return null;
    }

    public function search($term, $size = 20, $search = [])
    {
        if (!is_array($term)) {
            $data = array_merge_recursive([
                'index' => $this->getIndexName(),
                'type' => $this->getType(),
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
            ], $search);
        } else {
            $data = $term;
        }
        return $this->client->search($data);
    }

    abstract function getIndex();
    abstract function getType();
}
