<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @global CDatabase $DB */
/** @global CUser $USER */
/** @global CMain $APPLICATION */


if(!isset($arParams["CACHE_TIME"]))
	$arParams["CACHE_TIME"] = 36000000;

$arParams["ID"] = intval($arParams["ID"]);
$arParams["IBLOCK_ID"] = intval($arParams["IBLOCK_ID"]);

$arParams["DEPTH_LEVEL"] = intval($arParams["DEPTH_LEVEL"]);
if($arParams["DEPTH_LEVEL"]<=0)
	$arParams["DEPTH_LEVEL"]=1;

$arResult["SECTIONS"] = array();
$arResult["ELEMENT_LINKS"] = array();

if($this->StartResultCache())
{
	if(!CModule::IncludeModule("iblock"))
	{
		$this->AbortResultCache();
	}
	else
	{
		$arFilter = array(
			"IBLOCK_ID"=>$arParams["IBLOCK_ID"],
			"GLOBAL_ACTIVE"=>"Y",
			"IBLOCK_ACTIVE"=>"Y",
			"<="."DEPTH_LEVEL" => $arParams["DEPTH_LEVEL"],
		);
		$arOrder = array(
			"left_margin"=>"asc",
		);

		$rsSections = CIBlockSection::GetList($arOrder, $arFilter, false, array(
			"ID",
			"IBLOCK_SECTION_ID",
			"DEPTH_LEVEL",
			"NAME",
			"CODE",
			"SECTION_PAGE_URL",
			
		));
		if($arParams["IS_SEF"] !== "Y")
			$rsSections->SetUrlTemplates("", $arParams["SECTION_URL"]);
		else
			$rsSections->SetUrlTemplates("", $arParams["SEF_BASE_URL"].$arParams["SECTION_PAGE_URL"]);
		while($arSection = $rsSections->GetNext())
		{
			#DebugMessage($arSection);	
			$arResult["SECTIONS"][] = array(
				"ID" => $arSection["ID"],
				"DEPTH_LEVEL" => $arSection["DEPTH_LEVEL"],
				"~NAME" => $arSection["~NAME"],
				"~SECTION_PAGE_URL" => $arSection["~SECTION_PAGE_URL"],
				"CODE" => $arSection["CODE"],
				"IBLOCK_SECTION_ID" => $arSection["IBLOCK_SECTION_ID"],
			);
			$arResult["ELEMENT_LINKS"][$arSection["ID"]] = array();
		}
		$this->EndResultCache();
	}
}

//In "SEF" mode we'll try to parse URL and get ELEMENT_ID from it
if($arParams["IS_SEF"] === "Y")
{
	$engine = new CComponentEngine($this);
	if (CModule::IncludeModule('iblock'))
	{
		$engine->addGreedyPart("#SECTION_CODE_PATH#");
		$engine->setResolveCallback(array("CIBlockFindTools", "resolveComponentEngine"));
	}
	$componentPage = $engine->guessComponentPath(
		$arParams["SEF_BASE_URL"],
		array(
			"section" => $arParams["SECTION_PAGE_URL"],
			"detail" => $arParams["DETAIL_PAGE_URL"],
		),
		$arVariables
	);
	if($componentPage === "detail")
	{
		CComponentEngine::InitComponentVariables(
			$componentPage,
			array("SECTION_ID", "ELEMENT_ID"),
			array(
				"section" => array("SECTION_ID" => "SECTION_ID"),
				"detail" => array("SECTION_ID" => "SECTION_ID", "ELEMENT_ID" => "ELEMENT_ID"),
			),
			$arVariables
		);
		$arParams["ID"] = intval($arVariables["ELEMENT_ID"]);
	}
}

if(($arParams["ID"] > 0) && (intval($arVariables["SECTION_ID"]) <= 0) && CModule::IncludeModule("iblock"))
{
	$arSelect = array("ID", "IBLOCK_ID", "DETAIL_PAGE_URL", "IBLOCK_SECTION_ID");
	$arFilter = array(
		"ID" => $arParams["ID"],
		"ACTIVE" => "Y",
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
	);
	$rsElements = CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect);
	if(($arParams["IS_SEF"] === "Y") && (strlen($arParams["DETAIL_PAGE_URL"]) > 0))
		$rsElements->SetUrlTemplates($arParams["SEF_BASE_URL"].$arParams["DETAIL_PAGE_URL"]);
	while($arElement = $rsElements->GetNext())
	{
		$arResult["ELEMENT_LINKS"][$arElement["IBLOCK_SECTION_ID"]][] = $arElement["~DETAIL_PAGE_URL"];
	}
}

$aMenuLinksNew = array();
$menuIndex = 0;
$previousDepthLevel = 1;
foreach($arResult["SECTIONS"] as $arSection)
{
	if ($menuIndex > 0)
		$aMenuLinksNew[$menuIndex - 1][3]["IS_PARENT"] = $arSection["DEPTH_LEVEL"] > $previousDepthLevel;
	$previousDepthLevel = $arSection["DEPTH_LEVEL"];

	$arResult["ELEMENT_LINKS"][$arSection["ID"]][] = urldecode($arSection["~SECTION_PAGE_URL"]);
	$aMenuLinksNew[$menuIndex++] = array(
		htmlspecialcharsbx($arSection["~NAME"]),
		$arSection["~SECTION_PAGE_URL"],
		$arResult["ELEMENT_LINKS"][$arSection["ID"]],
		array(
			"FROM_IBLOCK" => true,
			"IS_PARENT" => false,
			"DEPTH_LEVEL" => $arSection["DEPTH_LEVEL"],
			"CODE" => $arSection["CODE"],
			"SECTION_ID" => $arSection["ID"],
			"PARENT_SECTION_ID" => $arSection["IBLOCK_SECTION_ID"],
		),
	);
}

if (count($_SESSION["MENU_CATALOG_CUR"]) > 0 && array($_SESSION["MENU_CATALOG_CUR"]))
{	
	$_arNewMenu = Array();
	foreach ($aMenuLinksNew as $key=>$arMenu)
	{
		if ($arMenu[3]["SECTION_ID"] == $_SESSION["MENU_CATALOG_CUR"]["SECTION_ID"])
		{
			$_arNewMenu[] = $aMenuLinksNew[$key];
			if (IntVal($arMenu[3]["PARENT_SECTION_ID"]) > 0)
			{
				foreach ($aMenuLinksNew as $key1=>$arMenu1)
				{
					if ($arMenu[3]["PARENT_SECTION_ID"] == $arMenu1[3]["SECTION_ID"])
					{
						$_arNewMenu[] = $aMenuLinksNew[$key1];
						break;
					}	
				}
			}
		}
	}
	#
	# Ничего дебильнее не делал 5 лет!!!
	# пздц это, а не ТЗ!
	#
	#DebugMessage($_arNewMenu);
	if (count($_arNewMenu) > 0)
	{
		$depth_max = 1;
		foreach ($_arNewMenu as $k=>$arMnu)
		{

			if ($k == 0)
			{	
				$_arNewMenu[$k][1] = $_SESSION["MENU_CATALOG_CUR"]["SECTION_CODE_PATH"];
				$_arNewMenu[$k][2][0] = $_SESSION["MENU_CATALOG_CUR"]["SECTION_CODE_PATH"];
				if ($_arNewMenu[$k][3]["DEPTH_LEVEL"] > $depth_max)
					$depth_max = $_arNewMenu[$k][3]["DEPTH_LEVEL"];
			}
			if ($k == 1)	
			{	
				$_arNewMenu[$k][1] = str_replace($_SESSION["MENU_CATALOG_CUR"]["SECTION_CODE"], "", $_SESSION["MENU_CATALOG_CUR"]["SECTION_CODE_PATH"]);
				$_arNewMenu[$k][2][0] = str_replace($_SESSION["MENU_CATALOG_CUR"]["SECTION_CODE"], "", $_SESSION["MENU_CATALOG_CUR"]["SECTION_CODE_PATH"]);
				if ($_arNewMenu[$k][3]["DEPTH_LEVEL"] > $depth_max)
					$depth_max = $_arNewMenu[$k][3]["DEPTH_LEVEL"];
			}	
		}	
		#DebugMessage($depth_max , "depth");
		if ($depth_max < 3)
			arsort($_arNewMenu);
		else
			sort($_arNewMenu);
	}
}

return $_arNewMenu;
?>
