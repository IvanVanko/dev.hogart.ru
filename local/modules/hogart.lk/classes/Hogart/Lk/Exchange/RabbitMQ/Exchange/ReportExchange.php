<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 18/01/2017
 * Time: 17:49
 */

namespace Hogart\Lk\Exchange\RabbitMQ\Exchange;


use Hogart\Lk\Entity\ReportTable;

class ReportExchange extends AbstractExchange
{
    /**
     * @inheritDoc
     */
    function getQueueName()
    {
        return "reports";
    }

    /**
     * @inheritDoc
     */
    function runEnvelope(\AMQPEnvelope $envelope)
    {
        switch ($key = $this->getRoutingKey($envelope)) {
            case 'request':
                $request = unserialize($envelope->getBody());
                ReportTable::generateReport($request);
                break;
        }
    }

}