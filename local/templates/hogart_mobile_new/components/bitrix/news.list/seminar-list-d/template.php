<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
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
//var_dump($arResult['ITEMS']);
?>


<?if (count($arResult['ITEMS']) > 0 && is_array($arResult['ITEMS'])):?>
	<div class="place-event-content event-content">
		<?foreach($arResult['ITEMS'] as $k=>$arItem):?>
			<?

			$date_start = date("d.m.Y", strtotime($arItem['PROPERTIES']['sem_start_date']['VALUE']));
			$sem_start_date = strtotime($date_start);
			$date_end = date("d.m.Y", strtotime($arItem['PROPERTIES']['sem_end_date']['VALUE']));
			$sem_end_date = strtotime($date_end);
			$date_curr = date("d.m.Y", time());
			$sem_date_curr = time();
			$date_show = "";
			if ($date_start == $date_end)
			{
				$last = "false";
				if (strlen($arItem['PROPERTIES']['sem_start_date']['VALUE']) > 0)
					$date_show = FormatDate("d F", strtotime($arItem['PROPERTIES']['sem_start_date']['VALUE']));
				else
					$date_show = "";
			}
			else
			{
				$last = "true";
				if (strlen($arItem['PROPERTIES']['sem_end_date']['VALUE']) > 0)
					$date_show = "c ".FormatDate("d F", strtotime($arItem['PROPERTIES']['sem_start_date']['VALUE']))." по ".FormatDate("d F Y", strtotime($arItem['PROPERTIES']['sem_end_date']['VALUE']));
				if (strlen($arItem['PROPERTIES']['sem_start_date']['VALUE']) > 0)
					$date_show = FormatDate("d F", strtotime($arItem['PROPERTIES']['sem_start_date']['VALUE']));
				else
					$date_show = "";
			}

			?>		
			<?if ($sem_start_date >= $sem_date_curr-(3600*24*30) && $sem_end_date <= $sem_date_curr):?>
			<div class="events">
				
			
					<div class="event" data-date="<?=$date_start?>"  data-is_last_day="<?=$last?>">
					<p class="title"><a href="<?=str_replace("learn", "learning", $arItem["DETAIL_PAGE_URL"])?>"><?=$arItem["NAME"]?></a></p>
					<? if ($arItem['PROPERTIES']['sem_start_date']['VALUE'] != ''): ?>
						<? $date_from = FormatDate("d.m.Y", MakeTimeStamp($arItem['PROPERTIES']['sem_start_date']['VALUE'])); ?>
						<time datetime="<?= $date_from; ?>"><?= $date_from; ?></time>
					<? endif; ?>

					<?
					$obParser = new CTextParser;
					$arItem["PREVIEW_TEXT"] = $obParser->html_cut($arItem["PREVIEW_TEXT"], 400);
					?>
					<?= $arItem['PREVIEW_TEXT']; ?>
					<p class="place_wrap">Место проведения: <span class="place"><?=$arItem['PROPERTIES']['address']['VALUE'];?></span></p>
			</div>
			<? endif;?>
		<?endforeach;?>
	</div>
<?else:?>

<?endif;?>

<Br />
<a href="javascript:void(0);" class="btn link-btn arrow-icon open-next">Предожить тему семинара</a>
<div class="green-form-wrap open-block hidden_block">
<?$APPLICATION->IncludeComponent(
"bitrix:form.result.new",
"seminar-theme-mobile",
	Array(
	"WEB_FORM_ID" => "4",
	"IGNORE_CUSTOM_TEMPLATE" => "N",
	"USE_EXTENDED_ERRORS" => "Y",
	"SEF_MODE" => "N",
	"VARIABLE_ALIASES" => Array("WEB_FORM_ID" => "WEB_FORM_ID","RESULT_ID" => "RESULT_ID"),
	"CACHE_TYPE" => "N",
	"CACHE_TIME" => "3600",
	"LIST_URL" => "",
	"EDIT_URL" => "",
	"SUCCESS_URL" => "",
	"CHAIN_ITEM_TEXT" => "",
	"CHAIN_ITEM_LINK" => "",
	"SUCCESS_MESSAGE" => "Спасибо, за Ваше предложение.",
	"AJAX_MODE" => "Y",  // режим AJAX
	"AJAX_OPTION_SHADOW" => "Y", // затемнять область
	"AJAX_OPTION_JUMP" => "N", // скроллить страницу до компонента
	"AJAX_OPTION_STYLE" => "N", // подключать стили
	"AJAX_OPTION_HISTORY" => "N",                        
	), $component 
);?>    
</div>
<a href="/learning/zapis-na-seminary-s-otkrytoy-datoy/" class="btn link-btn arrow-icon wide">Запись на семинары с открытой датой</a>
<a href="/learning/archive-seminarov/" class="btn link-btn arrow-icon">Архив семинаров</a>
<?
#DebugMessage($arResult['ITEMS']);
?>
