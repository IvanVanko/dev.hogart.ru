<?php
namespace CUSTOM\Entity;

use Bitrix\Main\Entity;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Type as FieldType;

class ProductItemMeasureTable extends Entity\DataManager
{
    public static function getFilePath()
    {
        return __FILE__;
    }


    public static function getTableName()
    {
        return 'product_item_measure';
    }

    /**
     * @return mixed
     */
    public static function GetBaseEntity() {
        return \CHLEntity::GetEntityByName('ProductItemMeasure');
    }

    /**
     * @return mixed
     */
    public static function GetBaseEntityId() {
        return \CHLEntity::GetEntityIdByName('ProductItemMeasure');
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

    /** Получить Элементы едениц измерения в упаковке по item_id и messure_id */
    public static function GetListByItem($itemID, $messure_id = null){
        $params = array(
            'select' => array('*'),
            'filter' => array('UF_ITEM_ID' => $itemID)
        );
        if($messure_id !== null)
            $params['filter']['UF_MESSURE_ID'] = $messure_id;
        return self::GetList($params);
    }

    // Обновить один элемент едениц измерения $measure_data - массив данных для добавления в self hL-block
    public static function UpdateMeasures(array $measure_data=array()){
        var_dump($measure_data);
        $existedResult = self::GetListByItem($measure_data['UF_ITEM_ID'], $measure_data['UF_MESSURE_ID']);

        if($existedResult->getSelectedRowsCount() > 0){
            // уже есть в БД - обновляем
            $mItem = $existedResult->fetch();
            return self::update($mItem['ID'], $measure_data);
        }else{
            // нет в БД - добавляем
            return self::add($measure_data);
        }

    }

    public static function delete($id) {
        $result = parent::delete($id);
        return $result;
    }
}