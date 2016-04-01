<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)	die();?>
<?global $DB;?>

<?$strSql = "SELECT COUNT(ID) as CNT FROM b_iblock_element WHERE IBLOCK_ID = ".COLLECTION_IBLOCK_ID.";"?>
<?$result['collection'] = $DB->Query($strSql)->GetNext();?>

<?$strSql = "SELECT COUNT(ID) as CNT FROM b_iblock_element WHERE IBLOCK_ID = ".BRAND_IBLOCK_ID.";"?>
<?$result['brand'] = $DB->Query($strSql)->GetNext();?>


<?$property_threshold_id = 300; //граница ID свойств, которые были добавлены вручную. Все свойства, ID которых > 300 - импортированы из 1С. (за исключением collection, которая тоже исключена из импорта)?>
<?/*запрос ниже деллает выборку по значениям свойств, которые*/?>
<?
// ID > 300
// CODE != 'collection'
// не дополнительное свойства типа Число для диапазонов, которое создается на основе свойства приходящего из импорта. Само свойство считаем, а дополнительные нет. (CODE NOT LIKE '%min%' AND bip.CODE NOT LIKE '%max%')
// IBLOCK_ID = 1 - каталог
?>
<?$strSql = "SELECT COUNT(biep.IBLOCK_PROPERTY_ID) as CNT FROM b_iblock_element_property biep JOIN b_iblock_property bip ON bip.ID = biep.IBLOCK_PROPERTY_ID WHERE bip.IBLOCK_ID = ".CATALOG_IBLOCK_ID." AND bip.ID > ".$property_threshold_id." AND bip.CODE <> 'collection' AND bip.CODE NOT LIKE '%min%' AND bip.CODE NOT LIKE '%max%';"?>
<?$result['property_values'] = $DB->Query($strSql)->GetNext();?>

<div>
    <ul>
        <li>
            Количество коллекций: <b><?=$result['collection']['CNT']?></b>
        </li>
        <li>
            Количество брендов: <b><?=$result['brand']['CNT']?></b>
        </li>
        <li>
            Количество значений характеристик: <b><?=$result['property_values']['CNT']?></b>
        </li>
    </ul>
</div>
