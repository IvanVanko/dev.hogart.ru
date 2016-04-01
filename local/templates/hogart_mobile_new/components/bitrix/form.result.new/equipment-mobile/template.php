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
		</div> 
		<?elseif ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'file'):?>
		<?
		if ($file_cnt > 0) $cls = "hide hidden-field"; else $cls = "";
		?>
		<div class="field <?=$cls ?>">
			<label for="add-file<?=$arQuestion['STRUCTURE'][0]['ID']?>" class="input-btn">Прикрепить лист<?#=$arQuestion["CAPTION"]?></label>
			<input type="file" id="add-file<?=$arQuestion['STRUCTURE'][0]['ID']?>" name="form_file_<?=$arQuestion['STRUCTURE'][0]['ID']?>" accept="application/pdf,application/msword,text/plain" class="add-file hide">
			<small class="place_file"></small>
		</div> 	
		<?$file_cnt++;?>
		<?else:?>
		<div class="field">
			<?
			$class = "company";
			if ($arQuestion["CAPTION"] == "Телефон")
				$class = "tel";
			if ($arQuestion["CAPTION"] == "E-mail"){
				$class = "email";
			}
			?>
					
			<label for="<?=$class?>"><?=$arQuestion["CAPTION"]?></label>
			<?=$arQuestion["HTML_CODE"]?>
			<!--<input type="text <?=$class?>" size="0" value="<?=$arQuestion['STRUCTURE']['VALUE']?>" name="form_<?=$arQuestion['STRUCTURE'][0]["FIELD_TYPE"]?>_<?=$arQuestion['STRUCTURE'][0]["ID"]?>" >/-->
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
	<p align="center"><span class="add-field">добавить еще один файл</span></p>
    	<p align="center"><span>Все поля обязательны для заполнения</span></p>
	<input type="submit" name="web_form_submit" value="Отправить" />


<?=$arResult["FORM_FOOTER"]?>
<?
} //endif (isFormNote)
?>
<script>
$(document).ready(function () {
	$('form[name="<?=$arResult["arForm"]["SID"]?>"]').addClass("equpment-form");
	 $('form[name="<?=$arResult["arForm"]["SID"]?>"]').addClass("green-form");
	 $('input[name="form_text_29"]').inputmask("+7 (999)999-99-99");
	 
	<?if ($arResult["isFormErrors"] == "Y" || $arResult["SUCCESS"] == "Y"):?>
	$('.open-block').addClass("opened");
	$('.open-next').addClass("opened");
	<?endif;?>
});
</script>