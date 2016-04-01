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
if ($arResult["isFormTitle"]):?>
	<h2><?=$arResult["FORM_TITLE"]?></h2>
<?endif;?>

<?
/***********************************************************************************
						form questions
***********************************************************************************/
?>
<?=$arResult["FORM_HEADER"]?>
	<?foreach ($arResult["QUESTIONS"] as $FIELD_SID => $arQuestion):?>
		<?if ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'hidden'):
			echo $arQuestion["HTML_CODE"];
		elseif ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'textarea'):?>
			<a href="#" class="trigger-border-bottom js-accordion" data-accordion="#appendmessage"><?=$arQuestion["CAPTION"]?></a>
            <div class="field" id="appendmessage">
                 <?=$arQuestion["HTML_CODE"]?>
            </div>
        <?elseif ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'file'):?>
            <div class="field custom_upload white-btn">
                <input type="file" name="form_file_<?=$arQuestion['STRUCTURE'][0]['ID']?>" accept="application/pdf,application/msword,text/plain">
                <label><?=$arQuestion["CAPTION"]?></label>
            </div>
            <a class="append-file-input trigger-border-bottom" href="#">Добавить еще один файл</a>
            
            <?/*div class="field custom_upload">
                <input type="hidden" name="MAX_FILE_SIZE" value="4000000" />
                <input type="file" name="form_file_<?=$arQuestion['STRUCTURE'][0]['ID']?>" accept="application/pdf,application/msword,text/plain">
                <label class="trigger-border-bottom"><?=$arQuestion["HTML_CODE"]?></label>
            </div*/?>
        <?else:?>
			<div class="field custom_label">
				<label for="form_<?=$arQuestion['STRUCTURE'][0]['FIELD_TYPE'].'_'.$arQuestion['STRUCTURE'][0]['ID']?>">
					<?=$arQuestion["CAPTION"]?>
				</label>
				<?//=$arQuestion["HTML_CODE"]?>
                <?if ($arQuestion["CAPTION"]=='Телефон'):?>
                    <div class="phone">
                        <?=$arQuestion["HTML_CODE"]?>
                    </div>
                <?else:?>
                    <?=$arQuestion["HTML_CODE"]?>
                <?endif;?>
			</div>
		<?endif;?>
	<?endforeach;?>
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
    <br/>
	<input type="submit" name="web_form_submit" class="empty-btn" value="Отправить" />

<?=$arResult["FORM_FOOTER"]?>
<?
} //endif (isFormNote)
?>