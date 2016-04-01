<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
//***********************************
//setting section
//***********************************
?>
<script type="text/javascript">
    $(document).ready(function(){
        $('#subs-box .all').click(function () {
            if ($(this).children('input').is(':checked')){
                $('#subs-box .field.custom_checkbox').each(function () {
                    if(!$(this).hasClass('all')){
                        $(this).children('input').prop({"checked": true});
                    }
                });
            } else {
                $('#subs-box .field.custom_checkbox').each(function () {
                    if(!$(this).hasClass('all')){
                        $(this).children('input').prop({"checked": false});
                    }
                });
            }
        });
        $('#subs-box .field.custom_checkbox').click(function () {
            if (!$(this).hasClass('all')) {
                if ($('#subs-box .field.custom_checkbox.all input').is(':checked')) {
                    console.log('+');
                    $('#subs-box .field.custom_checkbox.all input').prop({"checked": false});
                } else {
                    console.log('-');
                }
                console.clear();
                console.log($('#subs-box .field.custom_checkbox').find('input').filter(':checked').length);

                if ($('#subs-box .field.custom_checkbox').find('input').filter(':checked').length ==($('#subs-box .field.custom_checkbox').length-1)){
                    $('#subs-box .field.custom_checkbox.all input').prop({"checked": true});
                }
                /*$('#subs-box .field.custom_checkbox').each(function () {
                    if(!$(this).hasClass('all')){
                        $(this).children('input').filter()
                    }
                });*/
            }
        });
    });

</script>
<div class="js-validation-form">


<form action="<?=$arResult["FORM_ACTION"]?>" method="post">
<?echo bitrix_sessid_post();?>
        <div id="subs-box">
            <?foreach($arResult["RUBRICS"] as $itemID => $itemValue):?>
                <div class="field custom_checkbox <?=($itemValue["NAME"]=='Все')?'all':'' ?>">
                    <input id="s_<?= $itemValue["ID"] ?>" type="checkbox" name="RUB_ID[]"
                           value="<?= $itemValue["ID"] ?>"<? if ($itemValue["CHECKED"]) echo " checked" ?> />
                    <label for="s_<?= $itemValue["ID"] ?>"><?= $itemValue["NAME"] ?></label>
                </div>
            <?endforeach;?>
        </div>



		<div class="field js-validation-empty">
			<input placeholder="Введите ваш e-mail" type="text" name="EMAIL" value="<?=$arResult["SUBSCRIPTION"]["EMAIL"]!=""?$arResult["SUBSCRIPTION"]["EMAIL"]:$arResult["REQUEST"]["EMAIL"];?>" />
        </div>
    <div class="field custom_label phone js-validation-phone">
        <label for="PHONE">Телефон <font color="red"><span class="form-required starrequired">*</span></font></label>
        <input id="PHONE" placeholder="Введите ваш e-mail" type="text" name="entity[UF_SUBSCRIBER_PHONE]" value="<?=$arResult["SUBSCRIPTION"]["PHONE"]!=""?$arResult["SUBSCRIPTION"]["PHONE"]:$arResult["REQUEST"]["PHONE"];?>" />
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

        <button name="Save" class="empty-btn"><?=GetMessage("subscr_add")?></button>


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