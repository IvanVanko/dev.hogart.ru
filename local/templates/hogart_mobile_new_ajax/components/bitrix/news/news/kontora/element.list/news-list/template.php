<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<div class="main-filter news-filter">
	<div class="btn show-filter-btn open-next"></div>
	<form action="#" class="main-filter-form hidden_block open-block action_filter">
		<div class="filter-block">
			<input type="reset" class="input-btn gray-btn" value="сбросить">
		</div>
		<?if (count($arResult["FILTER"]["TAG"]) > 0):?>
			<div class="filter-block">
			<? foreach ($arResult["FILTER"]["TAG"] as $key => $tag): ?>
				<input type="checkbox" id="checkbox_<?= $key ?>" name="tag[<?= $tag["PROPERTY_TAG_VALUE_ENUM_ID"] ?>]" class="custom_checkbox tag<?=$tag['PROPERTY_TAG_ENUM_ID']?>" value="<?= $tag["PROPERTY_TAG_VALUE_VALUE"] ?>" <? if (isset($_REQUEST["tag"][$tag["PROPERTY_TAG_VALUE_ENUM_ID"]])): ?> checked<? endif; ?> />
				<label for="checkbox_<?= $key ?>"><?= $tag["PROPERTY_TAG_VALUE_VALUE"] ?></label>
			<? endforeach; ?>	
			</div>
		<?endif?>
		<div class="filter-block">
			<p class="block-title">Фильр по продукции</p>
			<?if(count($arResult["FILTER"]["DIRECTIONS"]) > 0):?>
			<select name="direction">
				<option value="">Выбрать направление</option>
				<? foreach ($arResult["FILTER"]["DIRECTIONS"] as $direction): ?>
					<option value="<?= $direction["ID"] ?>"<? if ($_REQUEST["direction"] == $direction["ID"]): ?> selected<? endif ?>><?= $direction["NAME"] ?></option>
				<? endforeach ?>
			</select>
			<? foreach ($arResult["FILTER"]["DIRECTIONS"] as $direction): ?>
				<input type="hidden" name="direction_<?= $direction['ID'] ?>_left" value=<?= $direction['LEFT_MARGIN'] ?> />
				<input type="hidden" name="direction_<?= $direction['ID'] ?>_right" value=<?= $direction['RIGHT_MARGIN'] ?> />
			<? endforeach; ?>
			<?endif?>
			<?if(count($arResult["FILTER"]["TYPES"]) > 0):?>
			<select name="catalog_section" id="catalog_section">
				<option value="">Выбрать тип товара</option>
				<? foreach ($arResult["FILTER"]["TYPES"] as $type): ?>
				<option value="<?= $type["ID"] ?>"<? if ($_REQUEST["catalog_section"] == $type["ID"]): ?> selected<? endif ?>><?= $type["VALUE"] ?></option>
				<? endforeach ?>
			</select>
			<?endif?>
			<?if(count($arResult["FILTER"]["BRANDS"]) > 0):?>
			<select name="brand" id="brand">
				<option value="">Выбрать бренд</option>
				<? foreach ($arResult["FILTER"]["BRANDS"] as $brand): ?>
				<option value="<?= $brand["ID"] ?>"<? if ($_REQUEST["brand"] == $brand["ID"]): ?> selected<? endif ?>><?= $brand["VALUE"] ?></option>
				<? endforeach; ?>
			</select>
			<?endif?>
			
		</div>
		<input type="submit" class="input-btn gray-btn" value="Показать результаты">
	</form>
</div>
<?/*$APPLICATION->IncludeComponent(
	"bitrix:subscribe.edit",
	"left_form",
	Array(
		"COMPONENT_TEMPLATE" => ".default",
		"SHOW_HIDDEN" => "N",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "3600",
		"ALLOW_ANONYMOUS" => "Y",
		"SHOW_AUTH_LINKS" => "Y",
		"SET_TITLE" => "N"
	)
);?>
<?$APPLICATION->IncludeComponent(
	"bitrix:subscribe.form",
	"news",
	Array(
		"USE_PERSONALIZATION" => "Y",
		"SHOW_HIDDEN" => "N",
		"PAGE" => "#SITE_DIR#subscription/",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "3600"
	)
);*/
?>
<div class="main-filter news-filter">
	<div class="btn show-subscribe open-next">Подписка</div>
	<form action="" class="main-filter-form open-block hidden_block">
		<div class="filter-block">
			<a href="#" class="input-btn gray-btn unsubscribe">отписаться</a>
		</div>
		
		<div class="filter-block">
			<input type="checkbox" id="checkbox_1" name="checkbox_1" class="custom_checkbox" checked>
			<label for="checkbox_1">Новости о компании</label>
			<input type="checkbox" id="checkbox_2" name="checkbox_2" class="custom_checkbox">
			<label for="checkbox_2">Акции</label>
			<input type="checkbox" id="checkbox_3" name="checkbox_3" class="custom_checkbox">
			<label for="checkbox_3">Обучение</label>
			<input type="checkbox" id="checkbox_4" name="checkbox_4" class="custom_checkbox">
			<label for="checkbox_4">Мероприятия</label>
		</div>
	
		<div class="filter-block">
			<label>sms уведомления</label>
			<input type="tel" class="masked">
			<label>E-mail</label>
			<input type="email">
			
		</div>
		<input type="submit" class="input-btn gray-btn" value="подписаться">
	</form>
</div>
<? if (count($arResult["ITEMS"]) > 0): ?>
<div class="news-items">
	<?foreach ($arResult["ITEMS"] as $arItem):?>
	<?#DebugMessage($arItem);?>
	<article class="news-one">
		<h2><a href="<?= $arItem["DETAIL_PAGE_URL"]?>"><?= $arItem["NAME"]?></a></h2>
		<p><?=$arItem["PREVIEW_TEXT]"]?></p>
		<? $date_from = FormatDate("d F", MakeTimeStamp($arItem["ACTIVE_FROM"])); ?>
		<?#DebugMessage( $date_from );?>
		<div class="article-info"> <time datetime="<?=$date_from?>"><?=$date_from?> </time> 
			<?if (count($arItem["PROPERTIES"]["tag"]["VALUE"]) > 0 ):?>
				<? foreach ($arItem["PROPERTIES"]["tag"]["VALUE"] as $key => $tag): ?>
					<a href="<?= $APPLICATION->GetCurPageParam("tag[" . $arItem['PROPERTIES']['tag']['VALUE_ENUM_ID'][$key] . "]=" . $tag, array("tag")); ?>"> <?= $tag ?></a>
				<? endforeach; ?>
			<?endif;?>
		</div>
	</article>
	<?endforeach;?>
</div>
<?= $arResult["NAV_STRING"]; ?>
<?endif;?>
<?$APPLICATION->IncludeFile(
	INCLUDE_AREAS."block-news-bottom-menu.php",
	Array(),
	Array("MODE"=>"html", "NAME"=>"Right Panel")
);?>