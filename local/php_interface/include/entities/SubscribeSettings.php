<?php

namespace CUSTOM\Entity;

use Bitrix\Main\Entity;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Type as FieldType;

class SubscribeSettingsTable extends Entity\DataManager
{
    public static function getFilePath()
    {
        return __FILE__;
    }


    public static function getTableName()
    {
        return 'subscribe_settings';
    }

    /**
     * @return mixed
     */
    public static function GetBaseEntity() {
        return \CHLEntity::GetEntityByName('SubscribeSettings');
    }

    /**
     * @return mixed
     */
    public static function GetBaseEntityId() {
        return \CHLEntity::GetEntityIdByName('SubscribeSettings');
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
        static $arMap = null;
        if($arMap === null) {
            $arMap = array();
            $obBaseEntityContractors = static::GetBaseEntity();
            if($obBaseEntityContractors) {
                $sEntityClassContractors = $obBaseEntityContractors->GetDataClass();

                $arMap = $sEntityClassContractors::GetMap();

                // опишем дополнительные связи
                //$arAddRefMap = self::GetAddRefMap();
                //$arMap = array_merge($arMap, $arAddRefMap);
            }
        }
        
        return $arMap;
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