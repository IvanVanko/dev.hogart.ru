<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<div class="event-content">
    <? $semStartDate = FormatDate("d F ", MakeTimeStamp($arResult['PROPERTIES']['sem_start_date']['VALUE'])); ?>
    <? $semStartDateFull = FormatDate("d F Y ", MakeTimeStamp($arResult['PROPERTIES']['sem_start_date']['VALUE'])); ?>
    <? $semEndDate = FormatDate("d F Y", MakeTimeStamp($arResult['PROPERTIES']['sem_end_date']['VALUE'])); ?>
    <? $semStartTime = FormatDate("d F Y", MakeTimeStamp($arResult['PROPERTIES']['sem_end_date']['VALUE'])); ?>	
	<div class="event" data-date="<?=date("d.m.Y", strtotime($arResult['PROPERTIES']['sem_start_date']['VALUE']))?>"  data-is_last_day="false">
		<?if (strlen($arResult["DETAIL_PICTURE"]['SRC']) > 0):?>
		<img src="<?= $arResult["DETAIL_PICTURE"]['SRC'] ?>" alt="" width="281px" height="206px" />
		<?endif;?>
		<h1><?= $arResult["NAME"] ?></h1>
		<time datetime="<?=date("Y-m-d", strtotime($arResult['PROPERTIES']['sem_start_date']['VALUE']))?>">
			<? if (!empty($arResult['PROPERTIES']['sem_start_date']['VALUE'])): ?>
				<? if (!empty($semStartDate) && FormatDate("Y", MakeTimeStamp($arResult['PROPERTIES']['sem_end_date']['VALUE'])) != "1970"): ?>
				с <?= $semStartDate ?> по <?= $semEndDate ?>
				<? else: ?>
				<?= $semStartDateFull; ?>
				<?endif; ?>
				<br />Начало: <?= $arResult['PROPERTIES']['time']['VALUE'] ?>
				<br />Окончание: <?= $arResult['PROPERTIES']['end_time']['VALUE'] ?>
			<? endif; ?>
		</time>
		<?=$arResult["DETAIL_TEXT"]?>
	</div>              
</div>
<?if (strlen($arResult["PROPERTIES"]["program_txt"]["~VALUE"]['TEXT']) > 0):?>
<div class="list_event content-text">
	<h2>Программа семинара</h2>
	<?= $arResult["PROPERTIES"]["program_txt"]["~VALUE"]['TEXT']; ?>
</div>
<?endif;?>
<div class="list_event content-text">
	<h2>Лекторы семинара</h2>
	<?foreach ($arResult['LECTORS'] as $key => $arItem): ?>
	<div class="event-person clearfix">
		<?if ($arItem['PREVIEW_PICTURE'] > 0):?>
			<div class="person_photo">
				<?$arNewFile = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"], array("width" => 130, "height" => 130), BX_RESIZE_IMAGE_PROPORTIONAL, true);?>
				<img src="<?= $arNewFile["src"] ?>"  style="width:130px !important;" />
			</div>
		<?endif;?>
		<div class="person-info">
			<h3><?= $arItem['NAME']; ?></h3>
			<p><?= $arItem['props']['company']['VALUE']; ?><br><?=$arItem['props']['status']['VALUE']; ?></p>
			<!--<a href="tel:<?= $arItem['props']['phone']['VALUE']; ?>"><?= $arItem['props']['phone']['VALUE']; ?></a>/-->
			<!--<a href="mailto:<?= $arItem['props']['mail']['VALUE']; ?>"><?= $arItem['props']['mail']['VALUE']; ?></a>/-->
		</div>
	</div>
	<? endforeach; ?>
</div>
<a href="javascript:void(0);" class="btn link-btn arrow-icon open-next">Регистрация на семинар</a>
<div class="green-form-wrap open-block hidden_block">
<?$APPLICATION->IncludeComponent(
"bitrix:form.result.new",
"seminar-register-mobile",
	Array(
	"WEB_FORM_ID" => "5",
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
	"SUCCESS_MESSAGE" => "Спасибо Ваша заявка на участие в семинаре принята.",
	"AJAX_MODE" => "Y",  // режим AJAX
	"AJAX_OPTION_SHADOW" => "Y", // затемнять область
	"AJAX_OPTION_JUMP" => "N", // скроллить страницу до компонента
	"AJAX_OPTION_STYLE" => "N", // подключать стили
	"AJAX_OPTION_HISTORY" => "N",
	"SEMINAR_ID"=>$arResult["ID"],
	), $component 
);?>	
</div>

<div class="contacts">
	<h2>Контакты и схема проезда</h2>
	<div class="list_event content-text">
		<p>По всем вопросам вы можете обращаться к организатору семинара</p>
		
		<div class="event-person clearfix">
			<?if ($arResult['ORGS']['PREVIEW_PICTURE'] > 0):?>
				<div class="person_photo">
					<?$arNewFile = CFile::ResizeImageGet($arResult['ORGS']["PREVIEW_PICTURE"], array("width" => 130, "height" => 130), BX_RESIZE_IMAGE_PROPORTIONAL, true);?>
					<img src="<?= $arNewFile["src"] ?>"  style="width:130px !important;height:130px !important;" />
				</div>
			<?endif;?>
			<div class="person-info">
				<h3><?= $arResult['ORGS']['NAME']; ?></h3>
				<p>
					<?= $arResult['ORGS']['props']['company']['VALUE']; ?><br><?= $arResult['ORGS']['props']['status']['VALUE']; ?>
					<a href="tel:+<?= $arResult['ORGS']['props']['phone']['VALUE']; ?>"><?= $arResult['ORGS']['props']['phone']['VALUE']; ?></a>
					<a href="mailto:<?= $arResult['ORGS']['props']['mail']['VALUE']; ?>"><?= $arResult['ORGS']['props']['mail']['VALUE']; ?></a>
				</p>
			</div>
		</div>
		
	</div>
	<div class="list_event content-text">
		<?=$arResult["PREVIEW_TEXT"]?>
	</div>  
	<?if (!empty($arResult['PROPERTIES']['public_transport']['~VALUE']['TEXT'])):?>
		<div class="list_event content-text">
        			<p><?= $arResult['PROPERTIES']['public_transport']['NAME'] ?>:<?= $arResult['PROPERTIES']['public_transport']['~VALUE']['TEXT'] ?></p>
		</div>                          
	<?endif;?>
</div>

<?
#DebugMessage($arResult);
?>