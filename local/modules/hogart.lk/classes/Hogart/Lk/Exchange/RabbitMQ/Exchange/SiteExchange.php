<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 11/11/2016
 * Time: 22:21
 */

namespace Hogart\Lk\Exchange\RabbitMQ\Exchange;


use Hogart\Lk\Search\MainSearchSuggest;

class SiteExchange extends AbstractExchange
{
    const INDEX_ITEM = "index_item";
    const INDEX_ALL = "index_all";
    const INDEX_DELETE_ITEM = "index_delete_item";

    /**
     * @inheritDoc
     */
    function getQueueName()
    {
        return "site";
    }

    /**
     * @inheritDoc
     */
    function runEnvelope(\AMQPEnvelope $envelope)
    {
        switch ($key = $this->getRoutingKey($envelope)) {
            case self::INDEX_ITEM:
                MainSearchSuggest::getInstance()
                    ->createIndex()
                    ->indexById($envelope->getBody())
                ;
                break;
            case self::INDEX_DELETE_ITEM:
                MainSearchSuggest::getInstance()
                    ->deleteItemFromIndex($envelope->getBody())
                ;
                break;
            case self::INDEX_ALL:
                MainSearchSuggest::getInstance()
                    ->createIndex()
                    ->indexAll()
                ;
                break;
        }
    }
}