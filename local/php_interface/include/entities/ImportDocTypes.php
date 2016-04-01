<?php

namespace CUSTOM\Entity;

use Bitrix\Main\Entity;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Type as FieldType;

class ImportDocTypesTable extends Entity\DataManager
{
    public static function getFilePath()
    {
        return __FILE__;
    }


    public static function getTableName()
    {
        return 'b_iblock_property_enum';
    }

    /**
     * @return mixed
     */
    public static function GetBaseEntity() {
        return \CHLEntity::GetEntityByName('ImportDocTypes');
    }

    /**
     * @return mixed
     */
    public static function GetBaseEntityId() {
        return \CHLEntity::GetEntityIdByName('ImportDocTypes');
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
                'title' => 'ID варианта свойства',
            ),
            'PROPERTY_ID' => array(
                'data_type' => 'integer',
                'title' => 'ID свойства',
            ),
            'SORT' => array(
                'data_type' => 'integer',
                'title' => 'Сортировка',
            ),
            'VALUE' => array(
                'data_type' => 'string',
                'title' => 'Лейбл свойства'
            ),
            'XML_ID' => array(
                'data_type' => 'string',
                'title' => 'Внешний код'
            ),
        );
    }

    public static function GetTypesForImport() {
        $ob_result = static::GetList(array('select' => array('ID','XML_ID'), 'filter' => array('>SORT' => 2)));
        $return = array();
        while ($next = $ob_result->fetch()) {
            $return[$next['XML_ID']] = $next['ID'];
        }

        return $return;
    }

    public static function GetImageTypes() {
        $ob_result = static::GetList(array('select' => array('XML_ID','SORT'), 'filter' => array('SORT' => array(1,2))));
        $return = array();
        while ($next = $ob_result->fetch()) {
            $return[$next['XML_ID']] = $next['SORT'];
        }

        return $return;
    }

//    public static function GetImageTypes() {
//        $ob_result = static::GetList(array('select' => array('XML_ID')));
//        $return = array();
//        while ($next = $ob_result->fetch()) {
//            $return[] = $next['XML_ID'];
//        }
//    }

    public static function getPropertyId() {
        $props = \BXHelper::getProperties(array(), array('IBLOCK_ID' => '10', 'CODE' => 'type'), array('ID'));
        $prop = current($props['RESULT']);
        return $prop['ID'];
    }

    public static function update($id, $fields) {
        $fields['PROPERTY_ID'] = static::getPropertyId();
        return parent::update($id, $fields);
    }

    public static function add($fields) {
        $fields['PROPERTY_ID'] = static::getPropertyId();
        $fields['SORT'] = 500;
        return parent::add($fields);
    }

    public static function GetList($arParams = array()){
        $arParams['filter']['PROPERTY_ID'] = static::getPropertyId();
        $obResult = parent::GetList($arParams);
        return $obResult;
    }
    public static function delete($id) {
        $result = parent::delete($id);
        return $result;
    }
}