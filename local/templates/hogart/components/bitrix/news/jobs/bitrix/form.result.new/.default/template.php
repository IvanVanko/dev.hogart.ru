<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
?>
<?
/***********************************************************************************
 * form header
 ***********************************************************************************/
if($arResult["isFormTitle"] && $arResult["SUCCESS"] !== "Y"):?>
<? endif; ?>

<? if($arResult["isFormErrors"] == "Y"): ?><?=$arResult["FORM_ERRORS_TEXT"];?><? endif; ?>
    <p><?=$arResult["SUCCESS"] == "Y" ? $arParams["SUCCESS_MESSAGE"] : $arResult["FORM_NOTE"]?></p>

<? if($arResult["isFormNote"] != "Y") {
    ?>
    <h3>Откликнуться на вакансию</h3>
    <?
    /***********************************************************************************
     * form questions
     ***********************************************************************************/
    ?>
    <div class="form-desc-txt">
        <span class="show-js-validation-form-new btn btn-primary">Заполнить форму</span>
    </div>
    
    <div class="js-validation-form-new"  style="display: none;">
        <?=$arResult["FORM_HEADER"]?>
        <? foreach($arResult["QUESTIONS"] as $FIELD_SID => $arQuestion):?>
            <? if($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'hidden'):
                echo $arQuestion["HTML_CODE"];
            elseif($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'textarea'):?>
                <div class="field js-validation-empty">
                    <a class="trigger-border-bottom<?=($arQuestion["CAPTION"] == 'Сопроводительное письмо') ? ' sopr' : '';?>"
                       href="#"><?=$arQuestion["CAPTION"]?></a> <?=($arQuestion["REQUIRED"] == "Y")?'*':'';?>
                    <?=$arQuestion["HTML_CODE"]?>
                </div>
            <? elseif($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'file'):?>

                <div class="field custom_upload js-validation-empty">
                    <input type="hidden" name="MAX_FILE_SIZE" value="4000000"/>
                    <input type="file" class="inputfile" name="form_file_<?=$arQuestion['STRUCTURE'][0]['ID']?>"
                           accept="application/pdf,application/msword,text/plain">
                    <a class="trigger-border-bottom resume add-file-hidden" href="#">Приложить резюме <?=($arQuestion["REQUIRED"] == "Y")?'*':'';?></a>
                </div>

                <p>
                    Вы можете приложить pdf, doc, docx или txt
                    размером не более 4Mb
                </p>
            <? else:?>
                <? if($arQuestion["CAPTION"] == 'Телефон'):
                    $customClass = 'phone js-validation-phone';
                elseif($arQuestion["CAPTION"] == 'E-mail'):
                    $customClass = 'js-validation-email';
                else:
                    $customClass = 'js-validation-empty';
                endif; ?>
                <div class="field custom_label <?=$customClass?>">
                    <label
                        for="form_<?=$arQuestion['STRUCTURE'][0]['FIELD_TYPE'].'_'.$arQuestion['STRUCTURE'][0]['ID']?>">
                        <?=$arQuestion["CAPTION"]?> <?=($arQuestion["REQUIRED"] == "Y")?'*':'';?>
                    </label>
                    <?=$arQuestion["HTML_CODE"]?>
                </div>
            <? endif; ?>
        <? endforeach; ?>
        <?
        if($arResult["isUseCaptcha"] == "Y") {
            ?>
            <tr>
                <th colspan="2"><b><?=GetMessage("FORM_CAPTCHA_TABLE_TITLE")?></b></th>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td><input type="hidden" name="captcha_sid" value="<?=htmlspecialcharsbx($arResult["CAPTCHACode"]);?>"/><img
                        src="/bitrix/tools/captcha.php?captcha_sid=<?=htmlspecialcharsbx($arResult["CAPTCHACode"]);?>"
                        width="180" height="40"/></td>
            </tr>
            <tr>
                <td><?=GetMessage("FORM_CAPTCHA_FIELD_TITLE")?><?=$arResult["REQUIRED_SIGN"];?></td>
                <td><input type="text" name="captcha_word" size="30" maxlength="50" value="" class="inputtext"/></td>
            </tr>
            <?
        } // isUseCaptcha
        ?>

        <input type="submit" name="web_form_submit" class="btn btn-primary" value="Отправить"/>
        <div><br> Поля, отмеченные * обязательны для заполнения.</div>
        <?=$arResult["FORM_FOOTER"]?>
    </div>
    <?
} //endif (isFormNote)
?>