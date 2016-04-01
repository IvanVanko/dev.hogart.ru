<?php
namespace CUSTOM\Entity;

use Bitrix\Main\Entity;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Type as FieldType;

class HogartSubscriptionTable extends Entity\DataManager
{
    public static function getFilePath()
    {
        return __FILE__;
    }


    public static function getTableName()
    {
        return 'b_subscription';
    }

    /**
     * @return mixed
     */
    public static function GetBaseEntity() {
        return \CHLEntity::GetEntityByName('HogartSubscription');
    }

    /**
     * @return mixed
     */
    public static function GetBaseEntityId() {
        return \CHLEntity::GetEntityIdByName('HogartSubscription');
    }


    /**
     * GetAddRefMap
     * @return array
     */
    public static function GetAddRefMap()
    {

    }
    public static function GetMap() {

    /**
     * getMap
     * @return array|void
     */
        return array(
         'ID' => array(
                'data_type' => 'integer',
                'primary' => true,
                'autocomplete' => true,
                'title' => 'ID склада',
            ),
            'EMAIL' => array(
                'data_type' => 'string',
                'title' => 'E-mail'
            ),
        );
  
    }

    public static function GetList($arParams = array()){
        $obResult = parent::GetList($arParams);
        return $obResult;
    }
    
    public static function delete($id) {
        $result = parent::delete($id);
        return $result;
    }
}