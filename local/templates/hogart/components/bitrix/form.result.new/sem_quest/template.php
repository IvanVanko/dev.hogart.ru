<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
?>

<? if ($_REQUEST["formresult"] == "addok" && !empty($_REQUEST["WEB_FORM_ID"]) && !empty($_REQUEST["RESULT_ID"])) : ?>
    <div data-form-message>
        <div data-text-holder>
            <div class="" data-place-text>
                Спасибо! Ваша заявка на участие в акции "<?=$arParams['ACTION_NAME']?>" принята.
            </div>
        </div>
    </div>
<? else : ?>

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
	<div id="form-<?=$arResult["arForm"]['ID'];?>" class="js-validation-form-new">
    <?=$arResult["FORM_HEADER"]?>
	<?foreach ($arResult["QUESTIONS"] as $FIELD_SID => $arQuestion):?>
		<?if ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'hidden'):
			echo $arQuestion["HTML_CODE"];
		elseif ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'textarea'):?>
			<a href="#" class="trigger-border-bottom js-accordion" data-accordion="#appendmessage"><?=$arQuestion["CAPTION"]?></a>
            <div class="field <?=($arQuestion["REQUIRED"] == "Y")?'js-validation-empty':''?>" id="appendmessage" style="display: none;">
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
			<?if ($arQuestion["CAPTION"] == 'Телефон'):
				$customClass = 'phone js-validation-phone';
                $customStyle = '';
			elseif ($arQuestion["CAPTION"] == 'E-mail'):
				$customClass = 'js-validation-email';
                $customStyle = '';
            elseif (strtolower($arQuestion["CAPTION"]) == 'отчество'):
				$customClass = 'not';
                $customStyle = '';
            elseif ($arQuestion['STRUCTURE'][0]['ID'] == 25):
                $customClass = 'js-validation-empty to-add-box not';
                $customStyle = '';
            elseif ($arQuestion['STRUCTURE'][0]['ID'] >= 23 && $arQuestion['STRUCTURE'][0]['ID'] <= 26 && $arQuestion['STRUCTURE'][0]['ID'] != 25):
                $customClass = 'js-validation-empty to-add-box';
                $customStyle = '';

            elseif ($arQuestion['STRUCTURE'][0]['ID'] == 27):
                $customClass = 'js-validation-empty seminar';
                $customStyle = '';
            elseif ($arQuestion['STRUCTURE'][0]['ID'] == 67):
//                $arQuestion['STRUCTURE'][0]['ID']
                $customClass = 'js-validation-empty stock';
                $customStyle = '';
            elseif($arQuestion["REQUIRED"] == "Y"):
				$customClass = 'js-validation-empty';
                $customStyle = '';
            elseif ($arQuestion["CAPTION"] === 'Удобная дата посещения'):
                $customClass = 'date-picker';
                $customStyle = 'style="font-size:0"';
            else:
                $customClass = '';
			endif;?>
			<div class="field custom_label <?= $customClass ?> " <?=$customStyle?>>
				<label for="form_<?=$arQuestion['STRUCTURE'][0]['FIELD_TYPE'].'_'.$arQuestion['STRUCTURE'][0]['ID']?>">
					<?=$arQuestion["CAPTION"]?> <?=($arQuestion["REQUIRED"] == "Y")?'*':'';?>
				</label>
					<?=$arQuestion["HTML_CODE"]?>
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
</div>
<? endif; ?>