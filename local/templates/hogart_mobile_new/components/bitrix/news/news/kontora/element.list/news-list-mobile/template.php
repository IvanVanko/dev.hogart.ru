<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<div class="main-filter news-filter">
	<div class="btn show-filter-btn open-next"></div>
	<form action="#" class="main-filter-form hidden_block open-block action_filter">
		<div class="filter-block">
			<input type="button" class="input-btn gray-btn" value="сбросить" id="reset_form" />
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
				<option value="">Направление</option>
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
				<option value="">Тип товара</option>
				<? foreach ($arResult["FILTER"]["TYPES"] as $type): ?>
				<option value="<?= $type["ID"] ?>"<? if ($_REQUEST["catalog_section"] == $type["ID"]): ?> selected<? endif ?>><?= $type["VALUE"] ?></option>
				<? endforeach ?>
			</select>
			<?endif?>
			<?if(count($arResult["FILTER"]["BRANDS"]) > 0):?>
			<select name="brand" id="brand">
				<option value="">Бренд</option>
				<? foreach ($arResult["FILTER"]["BRANDS"] as $brand): ?>
				<option value="<?= $brand["ID"] ?>"<? if ($_REQUEST["brand"] == $brand["ID"]): ?> selected<? endif ?>><?= $brand["VALUE"] ?></option>
				<? endforeach; ?>
			</select>
			<?endif?>
			
		</div>
		<input type="submit" class="input-btn gray-btn" value="Показать результаты">
	</form>
</div>

<?
#DebugMessage($arResult["FILTER"]);
?>
<?$APPLICATION->IncludeComponent(
	"bitrix:subscribe.edit",
	"popup_subscribe_mobile",
	Array(
		"COMPONENT_TEMPLATE" => ".default",
		"SHOW_HIDDEN" => "N",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"CACHE_TYPE" => "N",
		"CACHE_TIME" => "3600",
		"ALLOW_ANONYMOUS" => "Y",
		"SHOW_AUTH_LINKS" => "Y",
		"SET_TITLE" => "N",
		"PROP" => Array(
			1 => "UF_SUBSCRIBER_PHONE"
			)
	)
);?>
<? if (count($arResult["ITEMS"]) > 0): ?>
<div class="news-items" data-count="<?=$arParams["NEWS_CNT"]?>">
	<?foreach ($arResult["ITEMS"] as $arItem):?>
	<article class="news-one">
		<h2><a href="<?= $arItem["DETAIL_PAGE_URL"]?>"><?= $arItem["NAME"]?></a></h2>
		<p><?=$arItem["PREVIEW_TEXT"]?></p>
		<? $date_from = FormatDate("d F", MakeTimeStamp($arItem["ACTIVE_FROM"])); ?>
		<div class="article-info"> <time datetime="<?=$date_from?>"><?=$date_from?> </time> 
			<?if (count($arItem["PROPERTIES"]["tag"]["VALUE"]) > 0 ):?>
				<? foreach ($arItem["PROPERTIES"]["tag"]["VALUE"] as $key => $tag): ?>
					<?
					$page = $APPLICATION->GetCurPageParam("tag[" . $arItem['PROPERTIES']['tag']['VALUE_ENUM_ID'][$key] . "]=" . $tag, array("tag"));
					#$page = str_replace("news_list.php","index.php",$page);
					?>
					<a href="<?= $page ?>"> <?= $tag ?></a><br />
				<? endforeach; ?>
				<?#DebugMessage($APPLICATION->GetCurPageParam());?>
			<?endif;?>
		</div>
	</article>
	<?endforeach;?>
</div>
<?#= $arResult["NAV_STRING"]; ?>
<?
#DebugMessage($arResult["ITEMS"]);
?>
<a href="javascript:void(0);" class="btn arrow_btn show-more">ПОКАЗАТЬ ЕЩЕ новости</a>
<br /><br />
<?
if (!empty($_GET["tag"])) {
	foreach ($_GET["tag"] as $k=>$tag)
		$arFilter["PROPERTY_tag_VALUE"][$k] = $tag;
	$filter = ", filter: '".serialize($arFilter)."'";
	if (strlen($filter)<=0) $filter = '';
}

?>
<script>
    $("#reset_form").click(function(){
    	window.location.href = "/company/news/";
    });
$(function(){

    //путь к файлу с компонентом. Указываем параметр
    var path = "/company/news/news_list.php?ajax=Y";
    //счетчик страниц
    var currentPage = 1;
    $(".show-more").click(function(e){
        //делаем ajax запрос и сразу инкремент номера страницы
        $.get(path, {PAGEN_1: ++currentPage <?=$filter?>}, function(data){
            //добавим новости к списку
            $(".news-items").append(data);
             	var count = $(".news-items").data("count");
 	if(currentPage * <?=IntVal($arParams["ELEMENT_COUNT"])?> >= count){
 	   $(".show-more").hide();
	}
        });

        //отключим скролл к верху документа
        e.preventDefault();
    });
});
</script>
<?endif;?>
<?$APPLICATION->IncludeFile(
	INCLUDE_AREAS."block-news-bottom-menu.php",
	Array(),
	Array("MODE"=>"html", "NAME"=>"Right Panel")
);?>