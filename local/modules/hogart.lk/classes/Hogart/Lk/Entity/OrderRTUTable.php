<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 02/10/2016
 * Time: 00:44
 */

namespace Hogart\Lk\Entity;


use Bitrix\Catalog\ProductTable;
use Bitrix\Main\Entity\BooleanField;
use Bitrix\Main\Entity\DateField;
use Bitrix\Main\Entity\DatetimeField;
use Bitrix\Main\Entity\EnumField;
use Bitrix\Main\Entity\Event;
use Bitrix\Main\Entity\EventResult;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\ReferenceField;
use Bitrix\Main\Entity\StringField;
use Bitrix\Main\Entity\TextField;
use Bitrix\Main\Type\Date;
use Bitrix\Main\Type\DateTime;
use Hogart\Lk\Exchange\RabbitMQ\Exchange\OrderRTUExchange;
use Hogart\Lk\Exchange\SOAP\Request\OrderRTU;
use Hogart\Lk\Field\GuidField;
use Hogart\Lk\Helper\Template\OrderEventNote;

class OrderRTUTable extends AbstractEntity implements IOrderEventNote, IExchangeable
{
    const DELIVERY_OUR = 1;
    const DELIVERY_SELF = 2;

    const DATE_INTERVAL_09_15 = 1;
    const DATE_INTERVAL_15_21 = 2;

    const STATUS_ACTIVE = 0;
    const STATUS_CANCEL = 1;


    /**
     * Returns DB table name for entity
     *
     * @return string
     */
    public static function getTableName()
    {
        return 'h_order_rtu';
    }

    /**
     * Returns entity map definition
     */
    public static function getMap()
    {
        return [
            new IntegerField("id", [
                'primary' => true,
                "autocomplete" => true
            ]),
            new GuidField("guid_id"),
            new IntegerField("account_id"),
            new StringField("number"),
            new EnumField("status", [
                'values' => [
                    self::STATUS_ACTIVE,
                    self::STATUS_CANCEL,
                ],
                'default_value' => self::STATUS_ACTIVE,
            ]),
            new StringField("refuse_reason"),
            new DatetimeField("rtu_date"),
            new EnumField("delivery_type", [
                'values' => [
                    self::DELIVERY_OUR,
                    self::DELIVERY_SELF
                ]
            ]),
            new GuidField("store_guid"),
            new GuidField("address_guid"),
            new ReferenceField("address", __NAMESPACE__ . "\\AddressTable", ["=this.address_guid" => "ref.guid_id"]),
            new DateField("plan_date"),
            new StringField("plan_time"),
            new IntegerField("contact_id"),
            new ReferenceField("contact",  __NAMESPACE__ . "\\ContactTable", ["=this.contact_id" => "ref.id"]),
            new StringField("email"),
            new StringField("phone"),
            new BooleanField("is_sms_notify"),
            new BooleanField("is_email_notify"),
            new BooleanField("is_tk", [
                'default_value' => false
            ]),
            new StringField("tk_name"),
            new GuidField("tk_address"),
            new ReferenceField("tk_address_ref", __NAMESPACE__ . "\\AddressTable", ["=this.tk_address" => "ref.guid_id"]),
            new StringField("driver_name"),
            new StringField("driver_phone"),
            new TextField("note"),
            new BooleanField("is_active"),
        ];
    }

    /**
     * {@inheritDoc}
     */
    protected static function getIndexes()
    {
        return [
            new Index('idx_guid_id', ['guid_id' => 36]),
            new Index('idx_is_active', ['is_active']),
        ];
    }

    public static function getDeliveryTypeText($delivery)
    {
        return [
            self::DELIVERY_OUR => "Доставка",
            self::DELIVERY_SELF => "Самовывоз",
        ][$delivery];
    }

    public static function getDateIntevalText($interval)
    {
        return [
            self::DATE_INTERVAL_09_15 => "с 09:00 до 15:00",
            self::DATE_INTERVAL_15_21 => "с 15:00 до 21:00",
        ][$interval];
    }

    public static function getIntervals()
    {
        return [
            self::DATE_INTERVAL_09_15,
            self::DATE_INTERVAL_15_21,
        ];
    }

    public static function getRTUOrder($id)
    {
        $order_rtu = self::getRow([
            'filter' => [
                '=id' => $id
            ],
            'select' => [
                '*',
                'address_' => 'address',
                'tk_address_' => 'tk_address_ref',
                'c_' => 'contact'
            ]
        ]);
        $__items = OrderRTUItemTable::getList([
            'filter' => [
                '=order_rtu_id' => $id
            ],
            'select' => [
                '*',
                '' => 'item',
                'ELEMENT_CODE' => 'item.CODE',
                'url' => 'item.IBLOCK.DETAIL_PAGE_URL'
            ],
        ])->fetchAll();

	    if (!empty($__items)) {
		    $items = array_reduce($__items, function ($result, $item) { $result[$item['ID']] = $item; return $result; }, []);
		    \CIBlockElement::GetPropertyValuesArray($items, CATALOG_IBLOCK_ID, ['ID' => array_keys($items)], ['CODE' => ['sku']]);

		    $products = ProductTable::getList([
			    'filter' => [
				    '@ID' => array_keys($items)
			    ]
		    ])->fetchAll();

		    $products = array_reduce($products, function ($result, $item) { $result[$item['ID']] = $item; return $result; }, []);

		    $measures = [];
		    foreach ($__items as &$item) {
			    $item['url'] = \CIBlock::ReplaceDetailUrl($item['url'], $item, false, 'E');
			    $item['props'] = $items[$item['item_id']];
			    $item['product'] = $products[$item['item_id']];
			    $measures[] = $item['product']['MEASURE'];
		    }

		    $__items = array_reduce($__items, function ($result, $item) {
			    $result[$item['item_group']][] = $item;
			    return $result;
		    }, []);

		    $measuresRes = \CCatalogMeasure::getList(
			    array(),
			    array('@ID' => array_unique($measures)),
			    false,
			    false,
			    array('ID', 'SYMBOL_RUS')
		    );
		    $measures = [];
		    while ($measure = $measuresRes->GetNext()) {
			    $measures[$measure['ID']] = $measure['SYMBOL_RUS'];
		    }

		    $order_rtu['measures'] = $measures;
		    $order_rtu['items'] = $__items;
	    }

        return $order_rtu;
    }

    public static function showName($rtu_order = [], $prefix = '')
    {
        $name = "Заявка на отгрузку №" . ($rtu_order[$prefix . 'number'] ? : "<sup>получение</sup>");
        $date = $rtu_order[$prefix . 'rtu_date'];
        if (!empty($date) && $date instanceof DateTime) {
            $name .= " от " . $date->format("d-m-Y");
        }

        return $name;
    }

    public static function getOrderEventNote($entity_id, $event)
    {
        $order_rtu = self::getRTUOrder($entity_id);

        if (!$order_rtu['is_active']) return null;

        $note = new OrderEventNote(
            self::showName($order_rtu)
        );

        $note
            ->setTemplateFile($event["event"] . ".php")
            ->setTemplateData(['order_rtu' => $order_rtu, 'items' => $order_rtu['items'], 'measures' => $order_rtu['measures']])
            ->setBadgeIcon('<i class="fa fa-truck" aria-hidden="true"></i>')
            ->setBadgeClass('primary')
            ->setDate($order_rtu['rtu_date'])
        ;

        return $note;
    }

    public static function onBeforeAdd(Event $event)
    {
        $fields = $event->getParameter('fields');
        $result = new EventResult();
        $result->modifyFields([
            'account_id' => intval($fields['account_id']),
            'rtu_date' => new DateTime(),
            'driver_name' => (string)$fields['driver_name'],
            'driver_phone' => (string)$fields['driver_phone'],
            'note' => (string)$fields['note']
        ]);

        return $result;
    }

    static function putTo1c($primary)
    {
        self::publishToRabbit(new OrderRTUExchange(), new OrderRTU([self::getRTUOrder($primary)]));
    }
}
