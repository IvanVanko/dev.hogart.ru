<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
//***********************************
//setting section
//***********************************
?>
<div class="inner">
<form action="<?=$arResult["FORM_ACTION"]?>" method="post">
<?echo bitrix_sessid_post();?>


		<div class="field">
			<input placeholder="Введите ваш e-mail" type="text" name="EMAIL" value="<?=$arResult["SUBSCRIPTION"]["EMAIL"]!=""?$arResult["SUBSCRIPTION"]["EMAIL"]:$arResult["REQUEST"]["EMAIL"];?>" />
        </div>
<input type="hidden" name="FORMAT" value="html" />
        <!--div class="accordion-cnt">
            <span class="trigger-accordion js-accordion" data-accordion="#newsSms">А так же на sms уведомления</span>

            <div id="newsSms">
                <div class="field custom_checkbox">
                    <input type="checkbox" id="sms"/>
                    <label for="sms">Все sms</label>
                </div>
            </div>
        </div-->

<!--        <button name="Save" class="empty-btn black">--><?//=GetMessage("subscr_add")?><!--</button>-->
    <br/>
        <button name="Save" class="empty-btn black">отправить</button>


	<!--input type="submit" value="<?echo ($arResult["ID"] > 0? GetMessage("subscr_upd"):GetMessage("subscr_add"))?>" /-->


<input type="hidden" name="PostAction" value="<?echo ($arResult["ID"]>0? "Update":"Add")?>" />
<input type="hidden" name="ID" value="<?echo $arResult["SUBSCRIPTION"]["ID"];?>" />
<?if($_REQUEST["register"] == "YES"):?>
	<input type="hidden" name="register" value="YES" />
<?endif;?>
<?if($_REQUEST["authorize"]=="YES"):?>
	<input type="hidden" name="authorize" value="YES" />
<?endif;?>
</form>
</div>
