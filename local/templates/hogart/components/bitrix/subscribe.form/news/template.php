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
$frame = $this->createFrame("subscribe-form", false)->begin();?>
<?
global $USER;
$user_mail = $USER->GetEmail();
?>
	<form action="<?=$arResult["FORM_ACTION"]?>">
<!--		<h2>Подписаться на новости и события</h2>-->

		<?foreach($arResult["RUBRICS"] as $itemID => $itemValue):?>
			<div class="field custom_checkbox">
                <input type="checkbox" id="sf_RUB_ID_<?=$itemValue["ID"]?>" name="sf_RUB_ID[]" value="<?=$itemValue["ID"]?>"<?if($itemValue["CHECKED"]) echo " checked"?>/>
                <label for="sf_RUB_ID_<?=$itemValue["ID"]?>"><?=$itemValue["NAME"]?></label>
            </div>
		<?endforeach;?>

		<div class="field">
            <input name="sf_EMAIL" value="<?=($USER->IsAuthorized())?$user_mail:$arResult["EMAIL"]?>" type="text" placeholder="Введите ваш e-mail">
        </div>

        <div class="accordion-cnt">
            <span class="trigger-accordion js-accordion" data-accordion="#newsSms">А так же на sms уведомления</span>

            <div id="newsSms">
                <div class="field custom_checkbox">
                    <input type="checkbox" id="sms"/>
                    <label for="sms">Все sms</label>
                </div>
            </div>
        </div>

        <button name="OK" class="empty-btn">Подписаться</button>
	</form>

<?$frame->beginStub();?>

	<form action="<?=$arResult["FORM_ACTION"]?>">
		<h2>Подписатьсяна новости и события</h2>
		
		<?foreach($arResult["RUBRICS"] as $itemID => $itemValue):?>
			<div class="field custom_checkbox">
                <input type="checkbox" id="sf_RUB_ID_<?=$itemValue["ID"]?>" name="sf_RUB_ID[]" value="<?=$itemValue["ID"]?>"<?if($itemValue["CHECKED"]) echo " checked"?>/>
                <label for="sf_RUB_ID_<?=$itemValue["ID"]?>"><?=$itemValue["NAME"]?></label>
            </div>
		<?endforeach;?>

		<div class="field">
            <input name="sf_EMAIL" value="<?=$arResult["EMAIL"]?>" type="text" placeholder="Введите ваш e-mail">
        </div>

        <div class="accordion-cnt">
            <span class="trigger-accordion js-accordion" data-accordion="#newsSms">А так же на sms уведомления</span>

            <div id="newsSms">
                <div class="field custom_checkbox">
                    <input type="checkbox" id="sms"/>
                    <label for="sms">Все sms</label>
                </div>
            </div>
        </div>

        <button name="OK" class="empty-btn">Подписаться</button>
	</form>

<?$frame->end();?>