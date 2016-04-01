<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
?>
<?/*if ($arResult["isFormErrors"] == "Y"):?>
<div class="field"><p>
<?#=$arResult["FORM_ERRORS_TEXT"];?>
</p></div>
<?endif;*/?>
<?if ($arResult["SUCCESS"] == "Y"):?>
 <div class="field"><p><?=$arParams["SUCCESS_MESSAGE"] ?></p></div>
 <?endif;?>
<?//=$arResult["FORM_NOTE"]?>
<?
#DebugMessage($arResult["QUESTIONS"]);
?>
<?if ($arResult["isFormNote"] != "Y")
{
?>


<?
/***********************************************************************************
					form header
***********************************************************************************/
/*
if ($arResult["isFormTitle"]):?>
	<h2><?=$arResult["FORM_TITLE"]?></h2>
<?endif;*/?>

<?
/***********************************************************************************
						form questions
		<input type="hidden" value="<?=$arParams["WEB_FORM_ID"]?>" name="WEB_FORM_ID">
		<?=bitrix_sessid_post()?>
***********************************************************************************/
?>
<?$file_cnt = 0;?>
<?=$arResult["FORM_HEADER"]?>
	<?foreach ($arResult["QUESTIONS"] as $FIELD_SID => $arQuestion):?>
		<?#DebugMessage($arQuestion);?>
		<?if ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'hidden'):?>
			<?echo $arQuestion["HTML_CODE"];?>
		<?elseif ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'textarea'):?>
		<div class="field">
			<label for="company"><?=$arQuestion["CAPTION"]?></label>
			<?=$arQuestion["HTML_CODE"]?>
				<?if (is_array($arResult["FORM_ERRORS"]) && array_key_exists($FIELD_SID, $arResult['FORM_ERRORS'])):?>
					<script>
						$('textarea[name="form_<?=$arQuestion['STRUCTURE'][0]["FIELD_TYPE"]?>_<?=$arQuestion['STRUCTURE'][0]["ID"]?>"]').addClass("error")
					</script>
				<?else:?>
					<script>
						$('textarea[name="form_<?=$arQuestion['STRUCTURE'][0]["FIELD_TYPE"]?>_<?=$arQuestion['STRUCTURE'][0]["ID"]?>"]').addClass("success")
					</script>
				<?endif;?>			
		</div> 
		<?else:?>
		<div class="field">
			<label><?=$arQuestion["CAPTION"]?></label>
			<?=$arQuestion["HTML_CODE"]?>
			<?if (is_array($arResult["FORM_ERRORS"]) && array_key_exists($FIELD_SID, $arResult['FORM_ERRORS'])):?>
			<script>
				$('input[name="form_<?=$arQuestion['STRUCTURE'][0]["FIELD_TYPE"]?>_<?=$arQuestion['STRUCTURE'][0]["ID"]?>"]').addClass("error")
			</script>
			<?else:?>
			<script>
				$('input[name="form_<?=$arQuestion['STRUCTURE'][0]["FIELD_TYPE"]?>_<?=$arQuestion['STRUCTURE'][0]["ID"]?>"]').addClass("success")
			</script>
			<?endif;?>
		</div>
		<?endif;?>
	<?endforeach;?>
	

<?/*
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
<?}*/?>
	<input type="submit" name="web_form_submit" value="Предложить семинар" />


<?=$arResult["FORM_FOOTER"]?>
<?
} //endif (isFormNote)
?>
<script>
$(document).ready(function () {
	$('form[name="<?=$arResult["arForm"]["SID"]?>"]').addClass("add-subject-form");
	 $('form[name="<?=$arResult["arForm"]["SID"]?>"]').addClass("green-form");
	 $('input[name="form_text_16"]').inputmask("+7 (999)999-99-99");
	 
	<?if ($arResult["isFormErrors"] == "Y" || $arResult["SUCCESS"] == "Y"):?>
	$('.open-block').addClass("opened");
	$('.open-next').addClass("opened");
	<?endif;?>
});
</script>