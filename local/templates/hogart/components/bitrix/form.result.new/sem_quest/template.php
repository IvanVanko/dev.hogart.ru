<?
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
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
?>

<div style="text-align: center">
    <?=$arResult["FORM_NOTE"]?>
</div>

<? if ($arResult["isFormNote"] != "Y") :
/***********************************************************************************
					form header
***********************************************************************************/
if ($arResult["isFormTitle"]):?>
    <div class="form-desc-txt">
        <span class="show-js-validation-form-new btn btn-primary"><?= $arResult["FORM_TITLE"] ?></span>
    </div>
<?endif;?>

<?
/***********************************************************************************
						form questions
***********************************************************************************/
?>
	<div id="form-<?=$arResult["arForm"]['ID'];?>" class="js-validation-form-new" style="display: none;">
        <h4><?= $arResult["FORM_TITLE"] ?></h4>
    <?=$arResult["FORM_HEADER"]?>
	<?foreach ($arResult["QUESTIONS"] as $FIELD_SID => $arQuestion):?>
        <? if (in_array($FIELD_SID, ["EVENT_NAME", "EVENT_ID"])): ?>
            <input type="hidden" class="inputtext" name="form_text_<?=$arQuestion['STRUCTURE'][0]['ID']?>" value="<?=$arResult[$FIELD_SID]?>">
		<?elseif ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'hidden'):
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
			<?if ($arQuestion["CAPTION"] == 'Телефон' || $arQuestion["CAPTION"] == 'Tel. number'):
				$customClass = 'phone js-validation-phone';
                $customStyle = '';
			elseif ($arQuestion["CAPTION"] == 'E-mail'):
				$customClass = 'js-validation-email';
                $customStyle = '';
            elseif (strtolower($arQuestion["CAPTION"]) == 'отчество' || strtolower($arQuestion["CAPTION"]) == 'last name'):
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
	<input type="submit" name="web_form_submit" class="btn btn-primary" value="<?= GetMessage("Отправить") ?>" />

<?=$arResult["FORM_FOOTER"]?>
</div>
<? endif; ?>