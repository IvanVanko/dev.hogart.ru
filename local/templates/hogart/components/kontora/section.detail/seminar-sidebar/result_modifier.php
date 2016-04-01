<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();


//$arFilter = Array("IBLOCK_ID" => $arParams['IBLOCK_ID'], "ACTIVE" => "Y");
$arFilter = Array("IBLOCK_ID" => 9, "ACTIVE" => "Y", 'ID' => $arResult['PROPERTIES']['org']['VALUE']);
//$res = CIBlockElement::GetList(Array('PROPERTY_lecturer.NAME' => 'ASC'), $arFilter, array('PROPERTY_lecturer'), false, array());
$res = CIBlockElement::GetList(Array('PROPERTY_lecturer.NAME' => 'ASC', 'PROPERTY_lecturer.status' => 'ASC'), $arFilter, false, false, array());

?>


<? while ($ob = $res->GetNextElement()): ?>

    <?$arFields = $ob->GetFields();
    $arFields['props'] = $ob->GetProperties();
    $arResult['ORGS'] = $arFields;
    ?>
<?
//    echo '<pre>';
//    var_dump($arFields);
//    echo '</pre>';
    /*?>

    <li class="<?=($arFields['PROPERTY_M_CITY_VALUE']==0)?" active":""?>">
        <a href="#city_<?=$arFields['PROPERTY_M_CITY_VALUE'];?>" data-toggle="tab"><?=$arFields['PROPERTY_M_CITY_NAME'];?></a>
    </li>

<?*/
endwhile;?>
