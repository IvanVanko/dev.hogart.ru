<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Обучение");
?>

<?
$APPLICATION->IncludeComponent(
	"bitrix:news.detail",
	"hogart_seminar_detail",
	Array(
		"DISPLAY_DATE" => "Y",
		"DISPLAY_NAME" => "Y",
		"DISPLAY_PICTURE" => "Y",
		"DISPLAY_PREVIEW_TEXT" => "Y",
		"IBLOCK_TYPE" => "training",
		"IBLOCK_ID" => "8",
		//result_modifier.php
		"ORDER" => array($arParams["SORT_BY1"] => $arParams["SORT_ORDER1"], $arParams["SORT_BY2"] => $arParams["SORT_ORDER2"]),
		"SEF_FOLDER" => "/learning/",//$arParams['SEF_FOLDER'],
		"ELEMENT_CODE" => $_REQUEST['ELEMENT_CODE'],
		"PROPERTY_CODE" => array(
			"sem_start_date"
		)
	),
	false
);
?>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>