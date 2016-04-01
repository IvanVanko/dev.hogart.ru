<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)   die();?>
<?
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
<?if (count($arResult["ITEMS"]) > 0 && is_array($arResult["ITEMS"])):?>
<!--
<div class="main-filter">
	<div class="btn show-filter-btn open-next"></div>
	<form action="" class="main-filter-form hidden_block open-block">
		<div class="filter-block">
			<a href="<?=$APPLICATION->GetCurDir();?>" class="input-btn gray-btn">сбросить</a>
			<label for="select1">Сортировка</label>
			<select name="select1" id="select1">
				<option value="1">По артикулю</option>
				<option value="2">По артикулю</option>
			</select>
		</div>
		<div class="filter-block">
			<p class="block-title">Стоимость, руб</p>
			<div class="values_wrap clearfix">
				<input type="text" class="min" value="0" readonly>
				<input type="text" class="max" value="200000" readonly>	
			</div>

			<div class="slider" data-min="0" data-max="200000" data-start-value="0" data-end-value="200000" data-step="1"></div>
		</div>
		<div class="filter-block">
			<p class="block-title">Бренд</p>
			<div class="checkbox_wrap open-block">
				<input type="checkbox" id="checkbox_1" name="checkbox_1" class="custom_checkbox">
				<label for="checkbox_1">Buderus</label>
				<input type="checkbox" id="checkbox_2" name="checkbox_2" class="custom_checkbox">
				<label for="checkbox_2">Kiturami</label>
				<input type="checkbox" id="checkbox_3" name="checkbox_3" class="custom_checkbox">
				<label for="checkbox_3">Protherm</label>
				<input type="checkbox" id="checkbox_4" name="checkbox_4" class="custom_checkbox">
				<label for="checkbox_4">Unical</label>
				<input type="checkbox" id="checkbox_5" name="checkbox_5" class="custom_checkbox">
				<label for="checkbox_5">Saturn</label>
				<input type="checkbox" id="checkbox_6" name="checkbox_6" class="custom_checkbox">
				<label for="checkbox_6">Olympia</label>	

				<input type="checkbox" id="checkbox_6" name="checkbox_6" class="custom_checkbox">
				<label for="checkbox_6">Olympia</label>	
				<input type="checkbox" id="checkbox_6" name="checkbox_6" class="custom_checkbox">
				<label for="checkbox_6">Olympia</label>	
				<input type="checkbox" id="checkbox_6" name="checkbox_6" class="custom_checkbox">
				<label for="checkbox_6">Olympia</label>	
				<input type="checkbox" id="checkbox_6" name="checkbox_6" class="custom_checkbox">
				<label for="checkbox_6">Olympia</label>							
			</div>
			<a href="#" class="input-btn gray-btn all open-next">показать все</a>
		</div>
		<div class="filter-block">
			<p class="block-title">Серия</p>
			<div class="checkbox_wrap">
				<input type="checkbox" id="checkbox_1" name="checkbox_1" class="custom_checkbox">
				<label for="checkbox_1">Logano G125</label>
				<input type="checkbox" id="checkbox_2" name="checkbox_2" class="custom_checkbox">
				<label for="checkbox_2">Logano E400</label>
				<input type="checkbox" id="checkbox_3" name="checkbox_3" class="custom_checkbox">
				<label for="checkbox_3">Logano G225</label>
				<input type="checkbox" id="checkbox_4" name="checkbox_4" class="custom_checkbox">
				<label for="checkbox_4">Logano F12</label>						
			</div>
		</div>
		<div class="filter-block">
			<p class="block-title">Мощность, кВт</p>
			<div class="values_wrap clearfix">
				<input type="text" class="min" value="50" readonly>
				<input type="text" class="max" value="400" readonly>	
			</div>
			<div class="slider" data-min="0" data-max="600" data-start-value="50" data-end-value="400" data-step="1"></div>
		</div>

		<div class="filter-block">
			<p class="block-title">Отапливаемая площадь, м</p>
			<div class="values_wrap clearfix">
				<input type="text" class="min" value="50" readonly>
				<input type="text" class="max" value="400" readonly>	
			</div>
			<div class="slider" data-min="0" data-max="600" data-start-value="50" data-end-value="400" data-step="1"></div>
		</div>
		<div class="filter-block">
			<p class="block-title">Диаметр дымохода, см</p>
			<div class="values_wrap clearfix">
				<input type="text" class="min" value="10" readonly>
				<input type="text" class="max" value="15" readonly>	
			</div>
			<div class="slider" data-min="0" data-max="20" data-start-value="10" data-end-value="15" data-step="1"></div>
		</div>
		<div class="filter-block">
			<input type="checkbox" id="checkbox_1" name="checkbox_1" class="custom_checkbox">
			<label for="checkbox_1">Новинка</label>
			<input type="checkbox" id="checkbox_2" name="checkbox_2" class="custom_checkbox">
			<label for="checkbox_2">Участвует в акции</label>					
		</div>
		<input type="submit" class="input-btn gray-btn" value="Показать результаты">
	</form>
</div>
/-->
<?
#DebugMessage($arResult["VARIABLES"]["SECTION_ID"]);
#DebugMessage($arParams);
?>
	<?
	if ($section['DEPTH_LEVEL'] != 1) {
		$APPLICATION->IncludeComponent(
		"bitrix:catalog.smart.filter",
		"filter-mobile",
		array(
			"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
			"IBLOCK_ID" => $arParams["IBLOCK_ID"],
			//"SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
			"SECTION_ID" => $arParams["SECTION_ID"],
			"FILTER_NAME" => $arParams["FILTER_NAME"],
			"PRICE_CODE" => $arParams["PRICE_CODE"],
			"CACHE_TYPE" => $arParams["CACHE_TYPE"],
			"CACHE_TIME" => $arParams["CACHE_TIME"],
			"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
			"SAVE_IN_SESSION" => "N",
			"FILTER_VIEW_MODE" => $arParams["FILTER_VIEW_MODE"],
			"XML_EXPORT" => "Y",
			"SECTION_TITLE" => "NAME",
			"SECTION_DESCRIPTION" => "DESCRIPTION",
			'HIDE_NOT_AVAILABLE' => $arParams["HIDE_NOT_AVAILABLE"],
			"TEMPLATE_THEME" => $arParams["TEMPLATE_THEME"],


			//"RANGE_GROUPS" => $range_groups,
			"RANGE_GROUPS" => $arParams["range_groups"],
			//"STORES" => $stores,
			"STORES" => $arParams["stores"],
			"SELECTED_WAREHOUSE" =>  $_REQUEST['arrFilter_warehouse']

		),
		$component,
		array('HIDE_ICONS' => 'Y')
		);
	}
	?>


<section class="items">
	<? foreach ($arResult["ITEMS"] as $arItem): ?>
	<?#DebugMessage($arItem);?>
	<div class="one_item">
		<div class="item-img-wrap">
			<a href="<?= $arItem["DETAIL_PAGE_URL"] ?>">
				<? if (!empty($arItem["PREVIEW_PICTURE"]['SRC'])): ?>
				<?
					$file = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"]['ID'], array("width" => 108, "height" => 108), BX_RESIZE_IMAGE_PROPORTIONAL, true);
				?>
				<img src="<?= $file['src'] ?>" alt="" width="71" />
				<?elseif (!empty($arItem['PROPERTIES']['photos']['VALUE'][0])):?>
				<?
					$file = CFile::ResizeImageGet($arItem['PROPERTIES']['photos']['VALUE'][0], array("width" => 108, "height" => 108), BX_RESIZE_IMAGE_PROPORTIONAL, true);
				?>
				<img src="<?= $file['src'] ?>" alt="" width="71"/>
				<? else:	?>
				<img src="/images/project_no_img.jpg" alt="" width="71" />
				<? endif; ?>
			</a>
		</div>
		<div class="price"><a href="<?= $arItem["DETAIL_PAGE_URL"] ?>"><?= number_format($arItem["CATALOG_PRICE_1"],0,'.'," ") ?> Р</a></div>
		<div class="item_description">
			<span class="item_title"><a href="<?= $arItem["DETAIL_PAGE_URL"] ?>"><?= $arItem["NAME"] ?></a> Р</span>
			<div class="item_info">
				<a href="<?= $arItem["DETAIL_PAGE_URL"] ?>">Артикул: <?=$arItem["PROPERTIES"]["sku"]["VALUE"]?><br />
					<? if ($arItem["CATALOG_QUANTITY"] > 0): ?>
						В наличии
					<?else:?>
						Под заказ
					<? endif; ?>                                        
					<? if (!empty($arItem["PROPERTIES"]["delivery_period"]["VALUE"])): ?>
						<br>
						Срок поставки:<?= $arItem["PROPERTIES"]["delivery_period"]["VALUE"] ?> <?= number($arItem["PROPERTIES"]["delivery_period"]["VALUE"], array('день', 'дня', 'дней'))?>
					<? endif; ?>
                 		           </a>
			</div>
			<?$hiddenPropsExist=false;?>
			<div class="item_body">
			<?$k=0;?>
			<?
			$propertyName = 'brand';
			$arProperty = $arItem['PROPERTIES'][$propertyName]
			?>

			<?if (strlen($arResult["ALL_BRANDS"][$arProperty["VALUE"]]['NAME']) > 0):?>
			<dl class="clearfix">
				<dt><span><?= $arProperty["NAME"] ?></span></dt>
				<dd><span><?= $arResult["ALL_BRANDS"][$arProperty["VALUE"]]['NAME'] ?></span></dd>
			</dl>
			<?endif?>

			<?if (strlen($arItem['DISPLAY_PROPERTIES']["collection"]['NAME']) > 0):?>
			<dl class="clearfix">
				<dt><span><?= $arItem['DISPLAY_PROPERTIES']["collection"]['NAME'] ?></span></dt>
				<?$collectionElement = current($arItem['DISPLAY_PROPERTIES']["collection"]["LINK_ELEMENT_VALUE"]);?>
				<dd><span><?= $collectionElement['NAME'] ?></span></dd>
			</dl>
			<?endif?>
			<? foreach ($arItem["PROPERTIES"] as $propertyName => $arProperty): ?>
				<? if (!empty($arProperty["VALUE"]) && $arProperty['SMART_FILTER'] == 'Y'): ?>
					<? if (substr($propertyName, 0, 4) == "coll"): ?>
						<dl class="clearfix">
							<dt><span><?= $arProperty["NAME"] ?></span></dt>
							<dd><span><?= $arResult["ALL_COLLS"][$arProperty["VALUE"]]['NAME'] ?></span></dd>
						</dl>
					<? elseif (substr($propertyName, 0, 3) != "pho"): ?>
						<dl class="clearfix">
							<dt><span><?= $arProperty["NAME"] ?></span></dt>
							<dd><span><?= $arProperty["VALUE"] ?></span></dd>
						</dl>
					<? endif; ?>
				<? endif; ?>
				<?
				$k++;
				if ($k>4) break;
				?>
			<? endforeach; ?>
			</div>
			<!--<div class="item_body">
				<dl class="clearfix">
					<dt><span>Мощность</span> </dt>
					<dd><span>кВт70</span></dd>
				</dl>
				<dl class="clearfix">
					<dt><span>Тип котла </span></dt>
					<dd><span>напольный</span></dd>
				</dl>
				<dl class="clearfix">
					<dt><span>Предназначение</span></dt>
					<dd><span>отопление</span></dd>
				</dl>
				<dl class="clearfix">
					<dt><span>Камера сгорания</span></dt>
					<dd><span>открытая</span></dd>
				</dl>
				
			</div>/-->
		</div>
	</div>
	<?endforeach;?>
</section>
<?$APPLICATION->IncludeFile(
	INCLUDE_AREAS."block-news-bottom-menu.php",
	Array(),
	Array("MODE"=>"html", "NAME"=>"Right Panel")
);?>
<?endif;?>
<? $this->EndViewTarget() ?>
<?
#DebugMessage($arResult['SECTIONS']);
?>