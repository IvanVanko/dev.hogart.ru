<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 05/11/2016
 * Time: 01:28
 */

namespace Hogart\Lk\Entity;


use Bitrix\Main\Entity\Event;
use Bitrix\Main\Entity\EventResult;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\ReferenceField;
use Hogart\Lk\Field\GuidField;
use Ramsey\Uuid\Uuid;

class StoreAmountTable extends AbstractEntity
{
    /**
     * @inheritDoc
     */
    public static function getTableName()
    {
        return "h_store_amount";
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
            new IntegerField("item_id"),
            new GuidField("item_guid"),
            new GuidField("store_guid"),
            new ReferenceField("store", __NAMESPACE__ . "\\StoreTable", ["=this.store_guid" => "ref.XML_ID"]),
            new IntegerField("stock"),
            new IntegerField("in_reserve"),
            new IntegerField("in_transit"),
        ];
    }

    /**
     * @inheritDoc
     */
    public static function getIndexes()
    {
        return [
            new Index('idx_item_store', ['item_guid', 'store_guid']),
            new Index('idx_item_id', ['item_id']),
        ];
    }

    public static function getStoreAmountByItemsId($items_id, $stores_id = null)
    {
        $filter = [
            '=item_id' => $items_id
        ];

        if (null !== $stores_id) {
            $filter['=store.ID'] = $stores_id;
        }
        return array_reduce(StoreAmountTable::getList([
            'filter' => $filter,
            'select' => [
                '*',
                's_' => 'store'
            ]
        ])->fetchAll(), function ($result, $item) {
            $item['is_visible'] = (bool)($item['stock'] + $item['in_reserve'] + $item['in_transit']);
            $result[$item['item_id']][$item['s_ID']] = $item;
            $result[$item['item_id']]['__stock'] += $item['stock'];
            $result[$item['item_id']]['__in_reserve'] += $item['in_reserve'];
            $result[$item['item_id']]['__in_transit'] += $item['in_transit'];
            return $result;
        }, []);
    }

    public static function getUUID($item_guid, $store_guid)
    {
        return Uuid::uuid5(Uuid::NAMESPACE_OID, implode('|', [
            $item_guid,
            $store_guid,
        ]))->toString();
    }

    public static function onBeforeAdd(Event $event)
    {
        $result = new EventResult();
        $fields = $event->getParameter('fields');
        $result->modifyFields([
            'guid_id' => self::getUUID(
                $fields['item_guid'],
                $fields['store_guid'])
        ]);
        return $result;
    }

    public static function onAfterAdd(Event $event)
    {
        $catalog_product = new \CCatalogStoreProduct();
        $fields = $event->getParameter('fields');
        $store = StoreTable::getByXmlId($fields['store_guid'])[0];
        $catalog_product->Add([
            "PRODUCT_ID" => $fields['item_id'],
            "STORE_ID" => $store['ID'],
            "AMOUNT" => $fields['stock'],
        ]);
    }

    public static function onAfterUpdate(Event $event)
    {
        $catalog_product = new \CCatalogStoreProduct();
        $fields = $event->getParameter('fields');
        $store = StoreTable::getByXmlId($fields['store_guid'])[0];
        $catalog_product->Update($fields['item_id'], [
            "PRODUCT_ID" => $fields['item_id'],
            "STORE_ID" => $store['ID'],
            "AMOUNT" => $fields['stock'],
        ]);
    }
}
