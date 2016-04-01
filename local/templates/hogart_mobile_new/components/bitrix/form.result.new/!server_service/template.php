<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
?>
<?if ($arResult["isFormErrors"] == "Y"):?><?=$arResult["FORM_ERRORS_TEXT"];?><?endif;?>

<?=$arResult["FORM_NOTE"]?>

<?if ($arResult["isFormNote"] != "Y")
{
?>


<?
/***********************************************************************************
					form header
***********************************************************************************/
?>
    <?if ($arResult["isFormTitle"]):?>
        <?if ($arResult['arForm']['ID']==8):?>
            <h2 class="nomargin"><?=$arResult["FORM_TITLE"]?></h2>
        <?else:?>
            <div class="head inner">
                <h2><?=$arResult["FORM_TITLE"]?></h2>
                <a href="#" class="close"></a>
            </div>
        <?endif;?>
    <?endif;?>
<?
/***********************************************************************************
						form questions
***********************************************************************************/
?>
<div class="js-validation-form">
<pre><?//print_r($arResult["QUESTIONS"]);?></pre>
<?=$arResult["FORM_HEADER"]?>
<?$i = 1;?>
	<?foreach ($arResult["QUESTIONS"] as $FIELD_SID => $arQuestion):?>
        <?if ($arResult['arForm']['ID']==8):?>
            <div class="inner">
        <?else:?>

            <?if ($i == 1):?>
                <div class="inner">
            <?elseif ($i == 3):?>
                </div>
                <div class="hr"><span>Контактная информация</span></div>
                <div class="inner">
            <?endif;?>
        <?endif;?>
		<?if ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'hidden'):
			echo $arQuestion["HTML_CODE"];
		elseif ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'textarea'):?>
			<div class="field custom_label">
                <label for="text1"><?=$arQuestion["CAPTION"]?> *</label>
                <div class="js-validation-empty"><?=$arQuestion["HTML_CODE"]?></div>
            </div>
        <?elseif ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'dropdown'):?>
        	<div class="field custom_label">
                <label for="form_dropdown_<?=$FIELD_SID?>"><?=$arQuestion["CAPTION"]?> *</label>
            </div>
        	<div class="field custom_select nomargin">
                <select id="form_dropdown_<?=$FIELD_SID?>" class="js-validation-empty" name="form_dropdown_<?=$FIELD_SID?>">
                    <?if ($arResult['arForm']['ID']==8):?>
                        <?foreach ($arQuestion['STRUCTURE'] as $value):?>
                            <option value="<?=$value['ID']?>"><?=$value['MESSAGE']?></option>
                        <?endforeach;?>
                    <?else:?>
                        <option value="">Выбрать тему</option>
                        <?foreach ($arQuestion['STRUCTURE'] as $value):?>
                            <option value="<?=$value['ID']?>"><?=$value['MESSAGE']?></option>
                        <?endforeach;?>
                    <?endif;?>

                </select>
            </div>
        <?else:?>
			<div class="field custom_label">
                <label for="form_<?=$arQuestion['STRUCTURE'][0]['FIELD_TYPE'].'_'.$arQuestion['STRUCTURE'][0]['ID']?>"><?=$arQuestion["CAPTION"]?></label>

                <?if ($arQuestion["CAPTION"]=='Телефон'):?>
                <div class="phone">
                    <?=$arQuestion["HTML_CODE"]?>
                </div>
                <?else:?>
                    <?=$arQuestion["HTML_CODE"]?>
                <?endif;?>

            </div>
		<?endif;?>
		<?$i++;?>
	<?endforeach;?>
	</div>
<?
if($arResult["isUseCaptcha"] == "Y")
{
?>
		<tr>
			<th colspan="2"><b><?=GetMessage("FORM_CAPTCHA_TABLE_TITLE")?></b></th>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><input type="hidden" name="captcha_sid" value="<?=htmlspecialcharsbx($arResult["CAPTCHACode"]);?>" /><img src="/bitrix/tools/captcha.php?captcha_sid=<?=htmlspecialcharsbx($arResult["CAPTCHACode"]);?>" width="180" height="40" /></td>
		</tr>
		<tr>
			<td><?=GetMessage("FORM_CAPTCHA_FIELD_TITLE")?><?=$arResult["REQUIRED_SIGN"];?></td>
			<td><input type="text" name="captcha_word" size="30" maxlength="50" value="" class="inputtext" /></td>
		</tr>
<?
} // isUseCaptcha
?>
	
	<hr/>
    <div class="inner">
        <input type="submit" name="web_form_submit" class="empty-btn" value="Отправить" />
        <small>Поля, отмеченные * обязательны для заполнения.</small>
    </div>

<?=$arResult["FORM_FOOTER"]?>
</div>
<?
} //endif (isFormNote)
?>