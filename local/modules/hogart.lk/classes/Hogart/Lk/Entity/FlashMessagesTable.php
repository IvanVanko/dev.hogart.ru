<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 10/10/2016
 * Time: 22:01
 */

namespace Hogart\Lk\Entity;


use Bitrix\Main\Entity\DatetimeField;
use Bitrix\Main\Entity\Event;
use Bitrix\Main\Entity\EventResult;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\ReferenceField;
use Bitrix\Main\Entity\StringField;
use Bitrix\Main\Type\DateTime;
use Hogart\Lk\Field\GuidField;
use Hogart\Lk\Helper\Template\Message;
use Hogart\Lk\Helper\Template\MessageFactory;
use Ramsey\Uuid\Uuid;

class FlashMessagesTable extends AbstractEntity
{
    /**
     * @inheritDoc
     */
    public static function getTableName()
    {
        return "h_flash_messages";
    }

    /**
     * @inheritDoc
     */
    public static function getMap()
    {
        return [
            new GuidField("guid_id", [
                'primary' => true
            ]),
            new DatetimeField("created_at", [
                'default_value' => new DateTime()
            ]),
            new IntegerField("account_id"),
            new ReferenceField("account", __NAMESPACE__ . "\\AccountTable", ["=this.account_id" => "ref.id"]),
            new StringField("severity"),
            new StringField("message"),
            new StringField("url"),
            new StringField("icon"),
        ];
    }

    public static function addNewMessage($account_id, Message $message)
    {
        return self::add([
            'account_id' => $account_id,
            'severity' => $message->getSeverity(),
            'message' => $message->getMessage(),
            'url' => (string)$message->getUrl(),
            'icon' => (string)$message->getIcon()
        ]);
    }

    public static function getMessages($account_id)
    {
        return array_reduce(self::getList([
            'filter' => [
                '=account_id' => $account_id
            ],
            'order' => [
                'created_at' => 'ASC'
            ]
        ])->fetchAll(), function ($result, $message) {
            $m = new Message($message['message'], $message['severity']);
            $m
                ->setIcon($message['icon'])
                ->setUrl($message['url'])
            ;
            $result[] = MessageFactory::getInstance()->addMessage($m);
            self::delete($message['guid_id']);
            return $result;
        }, []);
    }

    public static function onBeforeAdd(Event $event)
    {
        $result = new EventResult();
        $result->modifyFields([
            'guid_id' => Uuid::uuid4()->toString()
        ]);
        return $result;
    }
}