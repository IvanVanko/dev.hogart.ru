<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 01/08/16
 * Time: 16:09
 */

namespace Hogart\Lk\Entity;

use Bitrix\Catalog\ProductTable;
use Bitrix\Main\Entity\DatetimeField;
use Bitrix\Main\Entity\Event;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\ReferenceField;
use Bitrix\Main\Entity\StringField;
use Bitrix\Main\Entity\DateField;
use Bitrix\Main\Entity\EnumField;
use Bitrix\Main\Entity\BooleanField;
use Hogart\Lk\Field\GuidField;
use Hogart\Lk\Helper\Template\OrderEventNote;

class RTUTable extends AbstractEntity implements IOrderEventNote
{
    /**
     * {@inheritDoc}
     */
    public static function getTableName()
    {
        return "h_rtu";
    }

    /**
     * {@inheritDoc}
     */
    public static function getMap()
    {
        return [
            new IntegerField("id", [
                'primary' => true,
                "autocomplete" => true
            ]),
            new GuidField("guid_id"),

            new IntegerField("order_id"),
            new ReferenceField("order", __NAMESPACE__ . "\\OrderTable", ["=this.order_id" => "ref.id"]),

            new StringField("number"),
            new DatetimeField("rtu_date"),
            new StringField("currency_code"),
            new ReferenceField("currency", "Bitrix\\Currency\\CurrencyTable", ["=this.currency_code" => "ref.CURRENCY"]),
            new BooleanField("is_active")
        ];
    }

    /**
     * {@inheritDoc}
     */
    protected static function getIndexes()
    {
        return [
            new Index('idx_guid_id', ['guid_id' => 36]),
            new Index('idx_rtu_entity_most', ['order_id']),
            new Index('idx_is_active', ['is_active']),
        ];
    }

    public static function getByOrder($order_id)
    {
        return array_map(function ($item) {
            $item['items'] = RTUItemTable::getByRtu($item['id']);
            return $item;
        }, self::getList([
            'filter' => [
                '=order_id' => $order_id,
                '=is_active' => true
            ]
        ])->fetchAll());
    }

    static function getOrderEventNote($entity_id, $event)
    {
        $rtu = self::getRowById($entity_id);
        $note = new OrderEventNote(
            "Отгрузка №" . $rtu['number'],
            $rtu['rtu_date']
        );
        $__items = RTUItemTable::getList([
            'filter' => [
                '=rtu_id' => $entity_id,
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

            $note->setTemplateData(['items' => $__items, 'measures' => $measures]);
        }

        $note
            ->setTemplateFile($event["event"] . ".php")
            ->setBadgeIcon('<i class="fa fa-truck" aria-hidden="true"></i>')
            ->setBadgeClass('primary')
        ;

        return $note;

    }

    public static function onAfterAdd(Event $event)
    {
        $id = $event->getParameter('id');
        $fields = $event->getParameter('fields');
        OrderEventTable::add([
            'entity_id' => $id,
            'event' => OrderEventTable::ORDER_EVENT_RTU_SUCCESS,
            'order_id' => $fields['order_id'],
        ]);
    }
}
