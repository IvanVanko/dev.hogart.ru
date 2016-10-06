<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 12/09/16
 * Time: 16:36
 */

namespace Hogart\Lk\Entity;


use Bitrix\Iblock\ElementTable;
use Bitrix\Main\DB\SqlExpression;
use Bitrix\Main\Entity\AddResult;
use Bitrix\Main\Entity\DatetimeField;
use Bitrix\Main\Entity\DeleteResult;
use Bitrix\Main\Entity\Event;
use Bitrix\Main\Entity\EventResult;
use Bitrix\Main\Entity\ExpressionField;
use Bitrix\Main\Entity\FloatField;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\ReferenceField;
use Bitrix\Main\Entity\ScalarField;
use Bitrix\Main\Entity\StringField;
use Bitrix\Main\Type\DateTime;
use Hogart\Lk\Field\GuidField;
use Hogart\Lk\Helper\Template\FlashError;
use Ramsey\Uuid\Uuid;

class CartItemTable extends AbstractEntity
{
    public static $DEFAULT_PRICE_TTL = 600;
    public static $PRICE_GROUP_NAME;

    /**
     * Returns DB table name for entity
     *
     * @return string
     */
    public static function getTableName()
    {
        return "h_cart_item";
    }

    /**
     * Returns entity map definition
     */
    public static function getMap()
    {
        return [
            new GuidField("guid_id", ["primary" => true]),
            new GuidField("cart_id"),
            new ReferenceField("cart", __NAMESPACE__ . "\\CartTable", ["=this.cart_id" => "ref.guid_id"]),
            new IntegerField("item_id"),
            new ReferenceField("item", "Bitrix\\Iblock\\ElementTable", ["=this.item_id" => "ref.ID", "=ref.IBLOCK_ID" => new SqlExpression('?i', CATALOG_IBLOCK_ID)]),
            new IntegerField("count"),
            new StringField("item_group", [
                "default_value" => ""
            ]),
            new IntegerField("item_group_position", [
                "default_value" => 1
            ]),
            new DatetimeField("created_at"),
            new DatetimeField("updated_at"),
            new FloatField("price"),
            new IntegerField("price_ttl", [
                'default_value' => self::$DEFAULT_PRICE_TTL
            ])
        ];
    }

    public static function getAccountCartCount($account_id)
    {
        return (int)self::getList([
            'filter' => [
                '=cart.account.id' => $account_id,
            ],
            'select' => ['CNT'],
            'runtime' => [
                new ExpressionField('CNT', 'SUM(%s)', ['count'])
            ]
        ])->fetch()['CNT'];
    }

    /**
     * @param $cart_id
     * @return array|DeleteResult[]
     */
    public static function deleteByCart($cart_id)
    {
        $filter = [
            '=cart_id' => $cart_id
        ];
        $items = self::getList([
            'filter' => $filter,
            'select' => ['guid_id']
        ])->fetchAll();
        $result = [];
        foreach ($items as $item) {
            $result[] = self::delete($item['guid_id']);
        }
        return $result;
    }

    /**
     * @param $cart_id
     * @param string $item_group
     * @return array
     */
    public static function getByCartId($cart_id, $item_group = null)
    {
        $filter = [
            '=cart_id' => $cart_id
        ];
        if (null !== $item_group) {
            $filter['=item_group'] = $item_group;
        }
        return self::getList([
            'filter' => $filter,
            'order' => [
                'item_group' => 'ASC',
                'item_group_position' => 'ASC'
            ]
        ])->fetchAll();
    }

    /**
     * @param int $account_id ID Аккаунта
     * @param int $item_id ID Номенклатуры
     * @param int $count Кол-во Номенлатуры
     * @param string $item_group Группировка разрезе одной корзины
     * @param int $contract_id ID Договора
     * @param string $store_guid GUID Склада
     * @return bool|\Bitrix\Main\Entity\AddResult|\Bitrix\Main\Entity\UpdateResult
     */
    public static function addItem($account_id, $item_id, $count = 1, $item_group = '', $contract_id = 0, $store_guid = '')
    {
        if (!$contract_id) {
            $contracts = ContractTable::getByAccountId($account_id);
            if (count($contracts) == 1) {
                $contract_id = reset($contracts)['id'];
            }
        }

        if (!$store_guid) {
            $stores = AccountStoreRelationTable::getByAccountId($account_id);;
            if (count($stores) == 1) {
                $store_guid = reset($stores)['XML_ID'];
            }
        }

        /**
         * @todo Сделать проверку добавляемой позиции на тип, исходя из этого делается тип корзины
         * @todo На данный момент по умолчанию тип "товар"
         */
        $cart = CartTable::createOrUpdateByField([
            'account_id' => $account_id,
            'contract_id' => $contract_id,
            'store_guid' => $store_guid
        ], "guid_id");

        $cart_id = $cart->getId();

        $cnt = 0;
        $position = 1;

        $row = self::getRow([
            'filter' => [
                '=cart_id' => $cart_id,
                '=item_id' => $item_id,
                '=item_group' => $item_group
            ],
            'select' => [
                'count' => 'count',
                'item_group_position' => 'item_group_position'
            ]
        ]);

        if (empty($row)) {
            $group_pos = self::getRow([
                'filter' => [
                    '=cart_id' => $cart_id,
                    '=item_group' => $item_group
                ],
                'order' => [
                    'item_group_position' => 'DESC'
                ]
            ])['item_group_position'];
            $position += $group_pos;
        } else {
            $cnt = $row['count'];
            $position = $row['item_group_position'];
        }


        $props = array_reduce([$element = ElementTable::getById($item_id)->fetch()], function ($result, $item) { $result[$item['ID']] = $item; return $result; }, []);
        \CIBlockElement::GetPropertyValuesArray($props, CATALOG_IBLOCK_ID, ['ID' => array_keys($props)], ['CODE' => ['default_count', 'sku']]);
        if (!empty($props[$item_id]['default_count'])) {
            $default_count = intval($props[$item_id]['default_count']['VALUE']) ? : 1;
            $count = ceil($count / $default_count) * $default_count;
        }
        $cnt += $count;

        $group_id = \CCatalogGroup::GetList([], ['BASE' => 'Y'])->Fetch()['ID'];
        $price = GetCatalogProductPrice($item_id, $group_id);

        if (null === $price['PRICE']) {
            new FlashError(vsprintf("В каталоге не указана цена на позицию: <strong><u>%s</u></strong>", [$element['NAME']]));
            return false;
        }

        $result = self::createOrUpdateByField([
            'cart_id' => $cart_id,
            'item_id' => $item_id,
            'item_group' => $item_group,
            'count' => $cnt,
            'price' => $price['PRICE'],
            'item_group_position' => $position
        ], 'guid_id');
        $data = $result->getData();
        $data['element'] = $element;
        $result->setData($data);
        return $result;
    }

    public static function onBeforeAdd(Event $event)
    {
        $result = new EventResult();
        $fields = $event->getParameter('fields');
        // set fields with default values
        foreach (static::getEntity()->getFields() as $field)
        {
            if ($field instanceof ScalarField && !array_key_exists($field->getName(), $fields))
            {
                $defaultValue = $field->getDefaultValue();

                if ($defaultValue !== null)
                {
                    $fields[$field->getName()] = $field->getDefaultValue();
                }
            }
        }
        $result->modifyFields([
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
            'guid_id' => Uuid::uuid5(Uuid::NAMESPACE_OID, implode('|', [
                $fields['cart_id'],
                $fields['item_id'],
                $fields['item_group'],

            ]))->toString()
        ]);

        return $result;
    }

    public static function onAfterAdd(Event $event)
    {
        $fields = $event->getParameter("fields");
        CartTable::update($fields['cart_id'], [
            'updated_at' => $fields['created_at']
        ]);
    }


    public static function onBeforeUpdate(Event $event)
    {
        $result = new EventResult();
        $result->modifyFields([
            'updated_at' => new DateTime()
        ]);

        return $result;
    }

    public static function onAfterUpdate(Event $event)
    {
        $fields = $event->getParameter("fields");
        CartTable::update($fields['cart_id'], [
            'updated_at' => $fields['updated_at']
        ]);
    }

    public static function onBeforeDelete(Event $event)
    {
        $id = $event->getParameter('id');
        $row = self::getById($id)->fetch();
        CartTable::update($row['cart_id'], [
            'updated_at' => new DateTime()
        ]);
    }
}
