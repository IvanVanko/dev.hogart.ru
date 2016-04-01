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
    <?if ($arResult['arForm']['ID']==8 || $arResult['arForm']['ID']==7):?>
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
    <?
    /*if ($arResult['ID']==8){
        $firstItemSelect = 1;
    } else {
        $firstItemSelect = 'Выбрать тему';
    }*/

    ?>
<div class="js-validation-form">
<?=$arResult["FORM_HEADER"]?>
<?$i = 1;?>
    <div class="inner">
	<?foreach ($arResult["QUESTIONS"] as $FIELD_SID => $arQuestion):?>
<!--    --><?//var_dump($arQuestion)?>
<?/*
		<?if ($i == 1):?>
			<div class="inner">
		<?elseif ($i == 3):?>
			</div>
            <div class="hr"><span>Контактная информация</span></div>
            <div class="inner">
		<?endif;?>
*/?>

		<?if ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'hidden'):
			echo $arQuestion["HTML_CODE"];
		elseif ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'textarea'):?>
			<div class="field custom_label nomargin js-validation-empty">
                <label for="text1"><?=$arQuestion["CAPTION"]?><?if ($arQuestion["REQUIRED"]=="Y") {?> *<?}?></label>
                <?=$arQuestion["HTML_CODE"]?>
            </div>
        <?elseif ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'dropdown'):?>
        	<div class="field custom_label custom_select js-validation-empty">
                <label for="form_dropdown_<?=$FIELD_SID?>"><?=$arQuestion["CAPTION"]?><?if ($arQuestion["REQUIRED"]=="Y") {?> *<?}?></label>
                <select id="form_dropdown_<?=$FIELD_SID?>" name="form_dropdown_<?=$FIELD_SID?>">
                    <option value="">Выбрать тему</option>
                    <?foreach ($arQuestion['STRUCTURE'] as $value):?>
                    	<option value="<?=$value['ID']?>"><?=$value['MESSAGE']?></option>
                    <?endforeach;?>
                </select>
            </div>
        <?else:?>
			<?if ($arQuestion["CAPTION"]=='Телефон'):
				$customClass = 'phone js-validation-phone';
			elseif($arQuestion["CAPTION"]=='E-mail'):
				$customClass = 'js-validation-email';
			else:
				$customClass = 'js-validation-empty';
			endif;?>
			<div class="field custom_label <?=$customClass?>">
                <label for="form_<?=$arQuestion['STRUCTURE'][0]['FIELD_TYPE'].'_'.$arQuestion['STRUCTURE'][0]['ID']?>"><?=$arQuestion["CAPTION"]?><?if ($arQuestion["REQUIRED"]=="Y") {?> *<?}?></label>
                    <?=$arQuestion["HTML_CODE"]?>
            </div>
		<?endif;?>
		<?$i++;?>
	<?endforeach;?>
<!--	</div>-->
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
		<input type="submit" name="web_form_submit" class="empty-btn" value="Отправить" />
<!--    <button type="submit" name="web_form_submit" class="empty-btn">Отправить заявку</button>-->
    <!--        <input type="submit" name="web_form_submit" class="empty-btn black" value="Отправить" />-->
    <br/><small>Все поля обязательны для заполнения</small>
<!--	<hr/>-->
    <!--<div class="inner">
        <button type="submit" name="web_form_submit" class="empty-btn">Отправить заявку</button>
        <input type="submit" name="web_form_submit" class="empty-btn black" value="Отправить" />
        <small>Поля, отмеченные * обязательны для заполнения.</small>
    </div>-->
    </div>

<?=$arResult["FORM_FOOTER"]?>
</div>
<?
} //endif (isFormNote)
?>
