<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?
$arIDS = Array();
foreach ($arResult["SEARCH"] as $k=>$arItem):
	$arIDS[] = $arItem["ITEM_ID"];
endforeach;
array_unique($arIDS);
if (count($arIDS) <= 0)
	return;
$arOrder = Array("NAME"=>"ASC");
$arNavParams = Array("nTopCount"=>count($arIDS));
$arFilter = Array(
	"ID" => $arIDS,
	"IBLOCK_ID" => $arParams["IBLOCK_ID"],
	"INCLUDE_SUBSECTIONS" => "Y",
	"ACTIVE" => "Y",
	"SECTION_ACTIVE" => "Y",
	"SECTION_GLOBAL_ACTIVE" => "Y",
);
$arSelect = Array(
	"ID",
	"IBLOCK_ID",
	"IBLOCK_SECTION_ID",
	"CODE",
	"NAME",
	"PREVIEW_PICTURE",
	"CATALOG_GROUP_1",
	"DETAIL_PAGE_URL",
);
$res = CIBlockElement::GetList($arOrder, $arFilter, false, $arNavParams, $arSelect);
$arData = Array();
$i=0;
while($arRes = $res->Fetch())
{
	$arData[$i] = $arRes;
	$arData[$i]["IMG"] = CFile::ResizeImageGet(
		$arRes["PREVIEW_PICTURE"],
		array("width" => 71, "height" => 108),
		BX_RESIZE_IMAGE_PROPORTIONAL,
		true
	);
	# Нада переделать запрос в запросе.. чисто для скорости сдачи!!!
	$nav = CIBlockSection::GetNavChain($arParams["IBLOCK_ID"], $arRes["IBLOCK_SECTION_ID"], Array("ID", "CODE"));
	while($_arSect = $nav->Fetch()):
		$arSects[$_arSect["ID"]] = $_arSect["CODE"];
  	endwhile;
  	$str = "";
  	foreach ($arSects as $k1=>$arV1)
  	{
  		$str .= $arV1."/";
  	}
  	 $str = substr($str, 0, -1);
  	 $arData[$i]["URL"] = str_replace(Array("#SITE_DIR#", "#SECTION_CODE_PATH#", "#ELEMENT_CODE#"), Array("", $str, $arData[$i]["ID"]), $arData[$i]["DETAIL_PAGE_URL"]);
	$i++;
}
$arResult["SEARCH_GOODS"] = $arData;
BXHelper::addCachedKeys($this->__component, array('SEARCH_GOODS'), $arResult);
?>