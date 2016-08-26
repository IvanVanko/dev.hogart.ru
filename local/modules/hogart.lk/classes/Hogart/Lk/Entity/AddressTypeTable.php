<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 01/08/16
 * Time: 14:56
 */

namespace Hogart\Lk\Entity;


use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\StringField;
use Hogart\Lk\Field\GuidField;

/**
 * Таблица Типов Адресов
 * @package Hogart\Lk\Entity
 */
class AddressTypeTable extends AbstractEntity
{
    const TYPE_RESIDENTIAL = 0; // адрес проживание
    const TYPE_ACTUAL = 1; // фактический адрес
    const TYPE_LEGAL = 2; // юридический адрес
    const TYPE_DELIVERY = 3; // адрес доставки
    /**
     * {@inheritDoc}
     */
    public static function getTableName()
    {
        return "h_address_type";
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
            new StringField("name"),
            new IntegerField("code")
        ];
    }

    /**
     * {@inheritDoc}
     */
    protected static function getIndexes()
    {
        return [
            new Index("idx_guid_id", ["guid_id" => 36])
        ];
    }
}
