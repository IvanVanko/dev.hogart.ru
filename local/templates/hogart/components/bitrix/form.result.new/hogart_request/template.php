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
    <input type="hidden" name="web_form_apply" value="Y"/>
<? if($arParams['HIDE_SUBMIT'] != 'Y') { ?>
        <input type="submit" class="empty-btn" value="Отправить">
    <?} ?>
    <br>
    <br>
    <small><?= GetMessage("FORM_REQUIRED_FIELDS")?></small>
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
    <?=$arResult["FORM_FOOTER"]?>
    <?
}
?>