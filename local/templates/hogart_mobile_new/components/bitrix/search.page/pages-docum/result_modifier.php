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
	#"INCLUDE_SUBSECTIONS" => "Y",
	"ACTIVE" => "Y",
	#"SECTION_ACTIVE" => "Y",
	#"SECTION_GLOBAL_ACTIVE" => "Y",
);
$arSelect = Array(
	"ID",
	"IBLOCK_ID",
	"NAME",
	"PROPERTY_file",
	"PROPERTY_direction",
);
$res = CIBlockElement::GetList($arOrder, $arFilter, false, $arNavParams, $arSelect);
$arData = Array();
$i=0;
while($arRes = $res->Fetch())
{
	
	$arData[$i] = $arRes;
	$arData[$i]["FILE"] = CFile::GetPath($arRes["PROPERTY_FILE_VALUE"]);
	if (count($arRes["PROPERTY_DIRECTION_VALUE"]) > 0)
	{
		$rs_items = CIBlockSection::GetList(Array("NAME"=>"ASC"), Array("IBLOCK_ID" => 1, "ID"=>$arRes["PROPERTY_DIRECTION_VALUE"], "ACTIVE"=>"Y", "INCLUDE_SUBSECTIONS" => "Y","SECTION_ACTIVE" => "Y","SECTION_GLOBAL_ACTIVE" => "Y"),  false, Array("ID","NAME","IBLOCK_ID"));
		while($arDir = $rs_items->Fetch())
   		{
			$arData[$i]["DIRECTION"][] = "<span>".$arDir["NAME"]."</span>";
		}
		
	}
	$i++;
}
#DebugMessage($arData);
$arResult["SEARCH_DOCUM"] = $arData;
BXHelper::addCachedKeys($this->__component, array('SEARCH_DOCUM'), $arResult);
?>