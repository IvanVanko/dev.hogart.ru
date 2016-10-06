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
use Bitrix\Main\Type\Date;
use Bitrix\Main\Type\DateTime;
use Hogart\Lk\Field\GuidField;
use Hogart\Lk\Helper\Template\OrderEventNote;

class OrderRTUTable extends AbstractEntity implements IOrderEventNote
{
    const DELIVERY_OUR = 1;
    const DELIVERY_SELF = 2;

    const DATE_INTERVAL_09_15 = 1;
    const DATE_INTERVAL_15_21 = 2;

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
            new DatetimeField("rtu_date"),
            new EnumField("delivery_type", [
                'values' => [
                    self::DELIVERY_OUR,
                    self::DELIVERY_SELF
                ]
            ]),
            new GuidField("store_guid"),
            new GuidField("address_guid"),
            new ReferenceField("address", __NAMESPACE__ . "\\AddressTable", ["=this.address_guid" => "ref.gui_id"]),
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
            new StringField("driver_name"),
            new StringField("driver_phone"),
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

    public static function getDateIntevalText($interval)
    {
        return [
            self::DATE_INTERVAL_09_15 => "c 09:00 до 15:00",
            self::DATE_INTERVAL_15_21 => "c 15:00 до 21:00",
        ][$interval];
    }

    public static function getIntervals()
    {
        return [
            self::DATE_INTERVAL_09_15,
            self::DATE_INTERVAL_15_21,
        ];
    }

    public static function getOrderEventNote($entity_id, $event)
    {
        $order_rtu = self::getRowById($entity_id);
        $note = new OrderEventNote(
            "Создание заказа на отгрузку №{$entity_id}"
        );

        $__items = OrderRTUItemTable::getList([
            'filter' => [
                '=order_rtu_id' => $entity_id,
                '=order_id' => $event['order_id']
            ],
            'select' => [
                '*',
                '' => 'item',
                'ELEMENT_CODE' => 'item.CODE',
                'url' => 'item.IBLOCK.DETAIL_PAGE_URL'
            ],
        ])->fetchAll();

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

        $note
            ->setTemplateFile($event["event"] . ".php")
            ->setTemplateData(['items' => $__items, 'measures' => $measures])
            ->setBadgeIcon('<i class="fa fa-truck" aria-hidden="true"></i>')
            ->setBadgeClass('primary')
            ->setDate($order_rtu['rtu_date'])
        ;

        return $note;
    }

    public static function onBeforeAdd(Event $event)
    {
        $result = new EventResult();
        $result->modifyFields([
            'rtu_date' => new DateTime()
        ]);

        return $result;
    }
}
