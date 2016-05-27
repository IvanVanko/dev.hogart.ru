<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<aside class="sidebar js-fh js-fixed-block js-paralax-height" data-fixed="top">
    <div class="inner js-paralax-item">
		<? if (LANGUAGE_ID != 'en'): ?>
			<div class="side_href">
				<a href="/documentation/?brands[]=<?=$arParams['FILTER']['PROPERTY_BRAND']?>&fbrand=y" class="icon_doc">Перейти к документации</a>
			</div>
			<?if (count($arResult["ITEMS"]) > 0):?>
				<div class="padding">
					<h2>Документация</h2>
					<ul class="price-list">
						<?foreach ($arResult["ITEMS"] as $arItem):
							$arFile = CFile::GetFileArray($arItem['PROPERTIES']['file']['VALUE']);
							$info = new SplFileInfo($arFile['FILE_NAME']);
							$extention = $info->getExtension();?>
							<li>
								<a target="_blank" href="<?=$arFile['SRC']?>">
									<?=$arItem['NAME']?>
								</a>
								<span>— .<?=$extention?>, <?=round($arFile['FILE_SIZE']/1048576, 2)?> mb</span>
							</li>
						<?endforeach;?>
					</ul>
				</div>
			<?endif; ?>
		<?endif; ?>
		<div class="company-side-cnt padding text-center">
			<h2><?= GetMessage("Основные направления деятельности") ?></h2>
			<?$GetCurDir = explode("/", $APPLICATION->GetCurDir());

			$GetCurDir = array_filter(
				$GetCurDir,
				function($el){ return !empty($el);}
			);
			$GLOBALS['myFilter'] = array("PROPERTY_show_where"=> $GetCurDir);

			$APPLICATION->IncludeComponent(
				"bitrix:news.list",
				"advantages",
				Array(
					"COMPONENT_TEMPLATE" => ".default",
					"IBLOCK_TYPE" => "advantages",
					"IBLOCK_ID" => (LANGUAGE_ID == 'en' ? 31 : 19),
					"NEWS_COUNT" => "3",
					"SORT_BY1" => "SORT",
					"SORT_ORDER1" => "ASC",
					"SORT_BY2" => "ACTIVE_FROM",
					"SORT_ORDER2" => "DESC",
					"FILTER_NAME" => "myFilter",
					"FIELD_CODE" => array("",""),
					"PROPERTY_CODE" => array("link"),
					"CHECK_DATES" => "Y",
					"DETAIL_URL" => "",
					"AJAX_MODE" => "N",
					"AJAX_OPTION_JUMP" => "N",
					"AJAX_OPTION_STYLE" => "Y",
					"AJAX_OPTION_HISTORY" => "N",
					"CACHE_TYPE" => "A",
					"CACHE_TIME" => "36000000",
					"CACHE_FILTER" => "N",
					"CACHE_GROUPS" => "Y",
					"PREVIEW_TRUNCATE_LEN" => "",
					"ACTIVE_DATE_FORMAT" => "d.m.Y",
					"SET_TITLE" => "N",
					"SET_BROWSER_TITLE" => "N",
					"SET_META_KEYWORDS" => "N",
					"SET_META_DESCRIPTION" => "N",
					"SET_STATUS_404" => "N",
					"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
					"ADD_SECTIONS_CHAIN" => "N",
					"HIDE_LINK_WHEN_NO_DETAIL" => "N",
					"PARENT_SECTION" => "",
					"PARENT_SECTION_CODE" => "",
					"INCLUDE_SUBSECTIONS" => "Y",
					"DISPLAY_DATE" => "N",
					"DISPLAY_NAME" => "Y",
					"DISPLAY_PICTURE" => "Y",
					"DISPLAY_PREVIEW_TEXT" => "N",
					"PAGER_TEMPLATE" => ".default",
					"DISPLAY_TOP_PAGER" => "N",
					"DISPLAY_BOTTOM_PAGER" => "N",
					"PAGER_TITLE" => "Новости",
					"PAGER_SHOW_ALWAYS" => "N",
					"PAGER_DESC_NUMBERING" => "N",
					"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
					"PAGER_SHOW_ALL" => "N"
				)
			);
			?>
		</div>
    </div>
</aside>