<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 29/07/16
 * Time: 16:38
 */

namespace Hogart\Lk\Entity;


use Bitrix\Main\DB\SqlExpression;
use Bitrix\Main\Entity\BooleanField;
use Bitrix\Main\Entity\EnumField;
use Bitrix\Main\Entity\Event;
use Bitrix\Main\Entity\EventResult;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\ReferenceField;
use Bitrix\Main\Entity\ScalarField;
use Bitrix\Main\Entity\StringField;
use Hogart\Lk\Exchange\RabbitMQ\Exchange\AddressExchange;
use Hogart\Lk\Exchange\SOAP\Request\Address;
use Hogart\Lk\Field\GuidField;
use Ramsey\Uuid\Uuid;

/**
 * Таблица Адресса
 * @package Hogart\Lk\Entity
 */
class AddressTable extends AbstractEntityRelation implements IExchangeable
{
    /**
     * {@inheritDoc}
     */
    public static function getTableName()
    {
        return "h_address";
    }

    /**
     * {@inheritDoc}
     */
    public static function getMap()
    {
        return array_merge([
            new GuidField("guid_id", [
                'primary' => true
            ]),
            new IntegerField("type_id"),
            new ReferenceField("type", __NAMESPACE__ . "\\AddressTypeTable", ["=this.type_id" => "ref.id"]),
            new StringField("value"),
            new StringField("postal_code"),
            new StringField("region"),
            new StringField("city"),
            new StringField("street"),
            new StringField("house"),
            new StringField("building"),
            new StringField("flat"),
            new GuidField("fias_code"),
            new StringField("kladr_code"),
            new BooleanField("is_active")
        ], parent::getMap());
    }

    /**
     * @param $owner_id
     * @param $owner_type
     * @param array $filter
     * @param array $select
     * @return array
     */
    public static function getByOwner($owner_id, $owner_type, $filter = [], $select = ['*'])
    {
        return array_reduce(
            parent::getByOwner($owner_id, $owner_type, $filter, array_merge($select, ['t_' => 'type'])),
            function ($result, $item) { $result[$item['t_code']][] = $item; return $result; },
            []
        );
    }

    public static function getValue($address, $prefix = '')
    {
        return $address[$prefix . 'value'] ? : (
            join(" ", [
                $address[$prefix . 'postal_code'],
                $address[$prefix . 'region'],
                $address[$prefix . 'city'],
                $address[$prefix . 'street'],
                $address[$prefix . 'house'],
                $address[$prefix . 'building'],
                $address[$prefix . 'flat']
            ])
        );
    }
    /**
     * {@inheritDoc}
     */
    protected static function getIndexes()
    {
        return [
            new Index("idx_address_entity_most", ['owner_id', 'type_id']),
            new Index('idx_is_active', ['is_active'])
        ];
    }

    public static function getUUID($owner_id, $owner_type, $type_id, $fias_code)
    {
        return Uuid::uuid5(Uuid::NAMESPACE_OID, implode('|', [
            $owner_id, $owner_type, $type_id, $fias_code,
        ]))->toString();
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

        $type = AddressTypeTable::getRowById($fields['type_id']);
        if ($type['code'] == AddressTypeTable::TYPE_DELIVERY && $fields['is_active'] != false) {

            $fields['type_id'] = null;

            $rows = self::getByOwner($fields['owner_id'], $fields['owner_type'], [
                '=is_active' => true
            ]);

            $rows = $rows[AddressTypeTable::TYPE_DELIVERY];
            $typeIds = [];
            foreach ($rows as $row) {
                $typeIds[] = $row['type_id'];
            }

            $typeIds = array_unique($typeIds);

            $types = AddressTypeTable::getList([
                'filter' => [
                    '=code' => AddressTypeTable::TYPE_DELIVERY
                ]
            ])->fetchAll();

            foreach ($types as $type) {
                if (in_array($type['id'], $typeIds)) continue;

                $fields['type_id'] = $type['id'];
                break;
            }
        }

        $result->modifyFields([
            'type_id' => $fields['type_id'],
            'guid_id' => self::getUUID(
                $fields['owner_id'],
                $fields['owner_type'],
                $fields['type_id'],
                $fields['fias_code'])
        ]);

        return $result;
    }

    static function putTo1c($primary)
    {
        $address = self::getRowById($primary);
        self::publishToRabbit(new AddressExchange(), new Address([$address]));
    }

    public static function onAfterAdd(Event $event)
    {
        self::putTo1c($event->getParameter('primary'));
    }
}