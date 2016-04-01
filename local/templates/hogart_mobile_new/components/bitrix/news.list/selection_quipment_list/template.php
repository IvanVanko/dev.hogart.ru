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
<a href="javascript:void(0);" class="btn link-btn arrow-icon open-next">Отправить заполненный опросный лист</a>
<div class="green-form-wrap open-block hidden_block">
	<?$APPLICATION->IncludeComponent(
	"bitrix:form.result.new",
	"equipment-mobile",
		Array(
		"WEB_FORM_ID" => "6",
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
		"SUCCESS_MESSAGE" => "Спасибо, что обратились в нашу компанию! Ваша заявка принята. В ближайшее время с вами свяжется специалист компании для уточнения деталей проекта.",
		"AJAX_MODE" => "Y",  // режим AJAX
		"AJAX_OPTION_SHADOW" => "Y", // затемнять область
		"AJAX_OPTION_JUMP" => "N", // скроллить страницу до компонента
		"AJAX_OPTION_STYLE" => "N", // подключать стили
		"AJAX_OPTION_HISTORY" => "N",                        
		), $component 
	);?>
</div>
<?
#DebugMessage($arResult['ITEMS']);
?>

<?if (count($arResult['ITEMS_BY_SECTIONS']) > 0):?>
<div class="equpment-list-wrap">
	<span>Опросные листы по</span><br /><br />
	<?$n = true;?>
	<select name="select1" id="select1" class="choose-block-trigger">
		<option value="">...выберите</option>
		<?foreach ($arResult["SECTIONS"] as $key => $arItems):?>
		<option value="block_<?=$key?>"><?=$arItems["NAME"]?></option>
		<?endforeach;?>
	</select>
	<?
	# тут нужно проверить и добавить ID к block_ !!!!!
	?>
	<?foreach ($arResult["SECTIONS"] as $key => $arItems):?>
	<div class="choose-items block_<?=$key?>">
		<ul class="equpment-list">
		<?foreach ($arResult["ITEMS"] as $k1=> $arItem):?>
			<?if ($arItem["IBLOCK_SECTION_ID"]== $key):?>
				<li>
					<span class="icon"></span>
					<div class="item-title"><a href="<?=$arItem['DISPLAY_PROPERTIES']['file']['FILE_VALUE']['SRC']?>" target="_blank"><?=$arItem['NAME']?></a></div>
				</li>
			<?endif;?>
		<?endforeach;?>
		</ul>
	</div>	
	<?endforeach;?>	
</div>
<?endif;?>
<?$APPLICATION->IncludeFile(
	INCLUDE_AREAS."block-news-bottom-menu.php",
	Array(),
	Array("MODE"=>"html", "NAME"=>"Right Panel")
);?>
<?
#DebugMessage($arResult["ITEMS"]);
?>