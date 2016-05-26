<?php

namespace CUSTOM\Entity;

use Bitrix\Main\Entity;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Type as FieldType;

class SectionPropertySortTable extends Entity\DataManager
{
    public static function getFilePath()
    {
        return __FILE__;
    }


    public static function getTableName()
    {
        return 'section_property_sort';
    }

    /**
     * @return mixed
     */
    public static function GetBaseEntity() {
        return \CHLEntity::GetEntityByName('SectionPropertySort');
    }

    /**
     * @return mixed
     */
    public static function GetBaseEntityId() {
        return \CHLEntity::GetEntityIdByName('SectionPropertySort');
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
                $sEntityClassContractors = $obBaseEntityContractors->getDataClass();
                $arMap = $sEntityClassContractors::getMap();
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