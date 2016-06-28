<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>

<div class="row">
	<!-- блок категорий -->
	<div class="col-md-9 sections">
		<?
		$APPLICATION->IncludeComponent(
			"bitrix:catalog.section.list",
			"",
			array(
				"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
				"IBLOCK_ID" => $arParams["IBLOCK_ID"],
				"CACHE_TYPE" => $arParams["CACHE_TYPE"],
				"CACHE_TIME" => $arParams["CACHE_TIME"],
				"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
				"COUNT_ELEMENTS" => $arParams["SECTION_COUNT_ELEMENTS"],
				"TOP_DEPTH" => $arParams["SECTION_TOP_DEPTH"],
				"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
				"SECTION_USER_FIELDS" => ["UF_*"],
				"VIEW_MODE" => $arParams["SECTIONS_VIEW_MODE"],
				"SHOW_PARENT_NAME" => $arParams["SECTIONS_SHOW_PARENT_NAME"],
				"HIDE_SECTION_NAME" => (isset($arParams["SECTIONS_HIDE_SECTION_NAME"]) ? $arParams["SECTIONS_HIDE_SECTION_NAME"] : "N"),
				"ADD_SECTIONS_CHAIN" => (isset($arParams["ADD_SECTIONS_CHAIN"]) ? $arParams["ADD_SECTIONS_CHAIN"] : '')
			),
			$component,
			array("HIDE_ICONS" => "Y")
		);
		?>
	</div>
	<!-- блок новостей -->
	<div class="col-md-3" style="padding-right: 0; overflow-x: hidden">
		<div class="aside-blocks fixed-block" data-rel-fixed-block="#header-block">
			<?
			$newsIBlockId = (LANGUAGE_ID == 'en' ? '28' : '3');
			$propertyTagValuesRes = CIBlockProperty::GetPropertyEnum(CIBlockProperty::GetPropertyArray('tag', $newsIBlockId)['ORIG_ID']);
			$propertyTagValues = [];
			while (($propertyTagValue = $propertyTagValuesRes->GetNext())) {
				$propertyTagValues[$propertyTagValue["XML_ID"]] = $propertyTagValue;
			}
			$date = new DateTime();
			date_sub($date, date_interval_create_from_date_string('2 month'));
			$APPLICATION->IncludeComponent("kontora:element.list", "sections_news", array(
				"BLOCK_TITLE" => GetMessage("Новости"),
				"LINK" => SITE_DIR . "company/news/?tag[" . $propertyTagValues['450e18f7257ca2e9d1202d8f58eb6ae8']["ID"] . "]=" . $propertyTagValues['450e18f7257ca2e9d1202d8f58eb6ae8']["VALUE"],
				'IBLOCK_ID' => $newsIBlockId,
				'FILTER' => array(
					"PROPERTY_tag" => array($propertyTagValues['450e18f7257ca2e9d1202d8f58eb6ae8']["ID"]),
					">=DATE_ACTIVE_FROM" => date_format($date, 'd-m-Y')." 00:00:00"
				),
				"CHECK_PERMISSIONS" => "Y",
				"CACHE_TYPE" => "A",
				"CACHE_TIME" => "0",
				"PROPS" => "Y",
				'ORDER' => array('property_priority' => 'asc,nulls', 'active_from' => 'desc'),
				'ELEMENT_COUNT' => 3,
			));

			$APPLICATION->IncludeComponent("kontora:element.list", "sections_news", array(
				"BLOCK_TITLE" => GetMessage("Акции"),
				"LINK" => SITE_DIR . "company/news/?tag[" . $propertyTagValues['19b9ef6f18390872303b696b849ee374']["ID"] . "]=" . $propertyTagValues['19b9ef6f18390872303b696b849ee374']["VALUE"],
				'IBLOCK_ID' => $newsIBlockId,
				'FILTER' => array(
					"PROPERTY_tag" => array($propertyTagValues['19b9ef6f18390872303b696b849ee374']["ID"]),
					">=DATE_ACTIVE_FROM" => date_format($date, 'd-m-Y')." 00:00:00"
				),
				"CHECK_PERMISSIONS" => "Y",
				"CACHE_TYPE" => "A",
				"CACHE_TIME" => "0",
				"PROPS" => "Y",
				'ORDER' => array('property_priority' => 'asc,nulls', 'active_from' => 'desc'),
				'ELEMENT_COUNT' => 3,
			));
			?>
		</div>
	</div>
</div>