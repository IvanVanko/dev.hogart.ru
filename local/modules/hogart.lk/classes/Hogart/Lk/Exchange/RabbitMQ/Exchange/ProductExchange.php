<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 07/11/2016
 * Time: 18:26
 */

namespace Hogart\Lk\Exchange\RabbitMQ\Exchange;

use Hogart\Lk\Search\CartSuggest;

require_once ($_SERVER["DOCUMENT_ROOT"]."/k_1c_upload/ParsingModel.php");

/**
 * Задачи RabbitMQ - Товары
 *
 * @rabbitmq.exchange
 * | *__Код задачи__*          | *__Тело сообщения__* | *__Описание__*                                         |
 * |:----------:               |:----------:          |--------------                                          |
 * | __product.get__           |                      | _Задача получения Товаров из КИС_                      |
 * | __product.index__         |                      | _Задача индексирования Товаров в ElasticSearch_        |
 * | __product.price__         |                      | _Задача получения Цен из КИС_                          |
 * | __product.tehdoc__        |                      | _Задача получения Документации из КИС_                 |
 * | __product.brands__        |                      | _Задача получения Брендов из КИС_                      |
 * | __product.branch__        |                      | _Задача получения Направлений из КИС_                  |
 * | __product.category__      |                      | _Задача получения Категорий из КИС_                    |
 * | __product.stock__         |                      | _Задача получения Остатков из КИС_                     |
 *
 * @package Hogart\Lk\Exchange\RabbitMQ\Exchange
 */
class ProductExchange extends AbstractExchange
{
    protected static $parse;

    protected function getParsingModel()
    {
        if (null === self::$parse) {
            self::$parse = new \ParsingModel(false);
        }
        return self::$parse;
    }

    /**
     * @inheritDoc
     */
    function getQueueName()
    {
        return "product";
    }

    /**
     * @inheritDoc
     */
    function runEnvelope(\AMQPEnvelope $envelope)
    {
        ob_start();
        switch ($key = $this->getRoutingKey($envelope)) {
            case 'price':
                $parse = $this->getParsingModel();
                $parse->answer = true;
                $parse->initPrice();
                break;
            case 'tehdoc':
                $parse = $this->getParsingModel();
                $parse->answer = true;
                $parse->initTehDoc();
                break;
            case 'brands':
                $parse = $this->getParsingModel();
                $parse->answer = true;
                $parse->initBrands();
                break;
            case 'branch':
                $parse = $this->getParsingModel();
                $parse->answer = true;
                $parse->initBranch();
                break;
            case 'category':
                $parse = $this->getParsingModel();
                $parse->answer = true;
                $parse->initCategory();
                break;
            case 'stock':
                $parse = $this->getParsingModel();
                $parse->initWarehouse();
                break;
            case 'index':
                CartSuggest::getInstance()
                    ->createIndex()
                    ->indexAll();
                break;
            case 'get':
                $parse = $this->getParsingModel();
                $parse->answer = true;
                $refresh = $parse->initProduct();
                if ($refresh) {
                    $this
                        ->publish("", $key);
                }
            break;
        }
        ob_end_flush();
    }

}