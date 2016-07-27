<?php
/**
 * Created by PhpStorm.
 * User: Ivan Koretskiy aka gillbeits[at]gmail.com
 * Date: 27/07/16
 * Time: 22:44
 */

namespace Hogart\Lk\Entity;

use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\ReferenceField;
use Hogart\Lk\Field\GuidField;

class ClientCompanyTable extends AbstractEntity
{
    /**
     * @inheritDoc
     */
    public static function getTableName()
    {
        return 'h_client_company';
    }

    /**
     * @inheritDoc
     */
    public static function getMap()
    {
        return [
            new IntegerField("id", [
                'primary' => true,
                "autocomplete" => true
            ]),
            new IntegerField("user_id"),
            new ReferenceField("user", "Bitrix\\Main\\UserTable", ["=this.user_id" => "ref.ID"])
        ];
    }

}