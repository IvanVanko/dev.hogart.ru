<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
global $DB;
$dbResult = $DB->Query("SELECT bip.ID , COUNT(distinct biep.ID) as VALUE_COUNT,bip.NAME, bis.ID as SECTION_ID, bis.NAME as SECTION_NAME FROM b_iblock_property bip JOIN b_iblock_property_enum bipe ON bip.ID = bipe.PROPERTY_ID JOIN b_iblock_element_property biep ON biep.IBLOCK_PROPERTY_ID = bip.ID JOIN b_iblock_section_property bisp ON bip.ID = bisp.PROPERTY_ID JOIN b_iblock_section bis ON bisp.SECTION_ID = bis.ID WHERE bipe.VALUE LIKE '%;%' AND bip.IBLOCK_ID = 1 GROUP BY bip.ID");
while ($next = $dbResult->GetNext()) {
    pr($next);
}

?>