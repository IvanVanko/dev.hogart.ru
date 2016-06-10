<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
?>
<? if($arResult["isFormErrors"] == "Y"): ?><?=$arResult["FORM_ERRORS_TEXT"];?><? endif; ?>
    <p><?=$arResult["SUCCESS"] == "Y" ? $arParams["SUCCESS_MESSAGE"] : $arResult["FORM_NOTE"]?></p>
<? if($arResult["isFormNote"] != "Y") {
    ?>
    <? if($arResult["isFormTitle"]) { ?>
        <h2 class="nomargin"><?=$arResult["FORM_TITLE"]?></h2>
        <?
    } ?>
    <?=$arResult["FORM_HEADER"]?>
    <?
    /***********************************************************************************
     * form questions
     ***********************************************************************************/
    ?>
    <? foreach($arResult["QUESTIONS"] as $code => $arQuestion) {
        $type = $arResult["arAnswers"][$code][0]['FIELD_TYPE'];
        if($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'hidden') {
            echo $arQuestion["HTML_CODE"];
            if(!empty($arParams['~CUSTOM_HIDDEN_HTML'][$code])) {
                echo $arQuestion["TOP_WRAPPER"];
                echo $arParams['~CUSTOM_HIDDEN_HTML'][$code];
                echo $arQuestion["BOT_WRAPPER"];
            }
        }
        else { ?>
            <?=$arQuestion["TOP_WRAPPER"]?>
            <label for="<?=$arQuestion["STRUCTURE"]['HTML_ID']?>"
                   class="<?=($type == 'file') ? "add_file" : ""?>"><?=!empty($arParams['CUSTOM_CAPTION'][$code]) ? $arParams['CUSTOM_CAPTION'][$code] : $arQuestion['CAPTION']?><? if($arQuestion['REQUIRED'] == 'Y') { ?>*<?
                } ?></label>
            <?=$arQuestion["HTML_CODE"];?>
            <? /*if ($type == 'file') {?>
                <div class="place_file">
                    <?=$arQuestion["HTML_CODE"];?>
                    <input type="hidden" name="<?=$arQuestion["STRUCTURE"]['HTML_ID']?>_file64" value="">
                    <ul class="files_list">

                    </ul>
                </div>
            <?}*/ ?>
            <?=$arQuestion["BOT_WRAPPER"]?>
            <?
        } ?>
        <?
    } ?>
    <? if ($arResult["isUseCaptcha"] == "Y"): ?>
        <div class="field custom_label"><label for="captcha_word" class="">Код с картинки*</label>
            <input type="hidden" name="captcha_sid" value="<?=htmlspecialcharsbx($arResult["CAPTCHACode"]);?>" />
            <img src="/bitrix/tools/captcha.php?captcha_sid=<?=htmlspecialcharsbx($arResult["CAPTCHACode"]);?>" width="180" height="40" />
            <input type="text" class="" name="captcha_word" value="" size="0" id="form_text_34"
                   data-rule-required="true" data-msg-required="Пожалуйста заполните это поле"
                   aria-required="true">
        </div>
    <? endif; // isUseCaptcha ?>

    <input type="hidden" name="web_form_apply" value="Y"/>
<? if($arParams['HIDE_SUBMIT'] != 'Y') { ?>
        <input type="submit" class="empty-btn" value="<?= GetMessage("Отправить") ?>">
    <?} ?>
    <br>
    <br>
    <small><?= GetMessage("Поля, отмеченные * обязательны для заполнения.")?></small>
    <?=$arResult["FORM_FOOTER"]?>
    <?
}
?>