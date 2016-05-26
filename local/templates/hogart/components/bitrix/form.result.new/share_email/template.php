<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
?>
<? if ($arResult["isFormErrors"] == "Y"): ?><?= $arResult["FORM_ERRORS_TEXT"]; ?><? endif; ?>

<? if ($arResult["FORM_NOTE"]) { ?>
    <div class="js-validation-form">
        <div class="head inner">
            <h2><?= $arResult["FORM_TITLE"] ?></h2>
            <a href="#" class="close"></a>
        </div>
        <div class="inner">
            <div id="form_note">
                <?= $arResult["FORM_NOTE"] ?>
            </div>
        </div>
    </div>
<? } ?>
<?if ($arResult["isFormNote"] != "Y") {
    ?>

    <?
    /***********************************************************************************
     * form header
     ***********************************************************************************/
    ?>
    <? if ($arResult["isFormTitle"]): ?>
        <div class="head inner">
            <h2><?= $arResult["FORM_TITLE"] ?></h2>
            <a href="#" class="close"></a>
        </div>
    <? endif; ?>

    <?
    /***********************************************************************************
     * form questions
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

        <?= $arResult["FORM_HEADER"] ?>

        <? $i = 1; ?>
        <? foreach ($arResult["QUESTIONS"] as $FIELD_SID => $arQuestion): ?>

    <? if ($i == 1): ?>
    <div class="inner">
    <p><?= GetMessage("Имя страницы") ?>: <? $APPLICATION->ShowTitle() ?></p>
    <p><a href="http://<?=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']?>"><?= GetMessage("Ссылка на страницу") ?></a>: http://<?=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']?></p>
    <? elseif ($i == 3): ?>
    </div>
        <div class="hr"><span><?= GetMessage("Контактная информация") ?></span></div>
        <div class="inner">
            <? endif; ?>

            <?if ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'hidden'):
                echo $arQuestion["HTML_CODE"];
            elseif ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'textarea'):?>

                <div
                    class="field custom_label <?=(in_array(strtolower(trim($arQuestion['CAPTION'])), ['materials', 'материалы страницы']))?'js-validation-empty materials':''?>">
                    <label
                        for="text1"><?= $arQuestion["CAPTION"] ?> <? if ($arQuestion["REQUIRED"] == "Y"): ?><?= $arResult["REQUIRED_SIGN"]; ?><? endif; ?></label>
                    <?= $arQuestion["HTML_CODE"] ?>
                </div>
            <?
            elseif ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'dropdown'): ?>
                <div
                    class="field custom_label custom_select <? if ($arQuestion["REQUIRED"] == "Y"): ?>js-validation-empty<? endif ?>">
                    <label
                        for="form_dropdown_<?= $FIELD_SID ?>"><?= $arQuestion["CAPTION"] ?> <? if ($arQuestion["REQUIRED"] == "Y"): ?><?= $arResult["REQUIRED_SIGN"]; ?><? endif; ?></label>
                    <select id="form_dropdown_<?= $FIELD_SID ?>" name="form_dropdown_<?= $FIELD_SID ?>">
                        <option value="">Выбрать тему</option>
                        <? foreach ($arQuestion['STRUCTURE'] as $value): ?>
                            <option value="<?= $value['ID'] ?>"><?= $value['MESSAGE'] ?></option>
                        <? endforeach; ?>
                    </select>
                </div>
            <?
            else: ?>
                <?if ($arQuestion["CAPTION"] == 'Телефон'):
                    $customClass = 'phone js-validation-phone';
                elseif (in_array(strtolower(trim($arQuestion['CAPTION'])), ['materials', 'материалы страницы'])):
                    $customClass = 'js-validation-empty materials';
                elseif ($arQuestion["CAPTION"] == 'E-mail'):
                    $customClass = 'js-validation-email';
                elseif ($arQuestion["REQUIRED"] == "Y"):
                    $customClass = 'js-validation-empty';
                else:
                    $customClass = '';
                endif;?>
                <div class="field custom_label <?= $customClass ?>">

                    <label
                        for="form_<?= $arQuestion['STRUCTURE'][0]['FIELD_TYPE'] . '_' . $arQuestion['STRUCTURE'][0]['ID'] ?>"><?= $arQuestion["CAPTION"] ?> <? if ($arQuestion["REQUIRED"] == "Y"): ?><?= $arResult["REQUIRED_SIGN"]; ?><? endif; ?></label>
                    <?= $arQuestion["HTML_CODE"] ?>
                </div>
            <?endif; ?>
            <? $i++; ?>
            <? endforeach; ?>
        </div>
        <?
        if ($arResult["isUseCaptcha"] == "Y") {
            ?>
            <tr>
                <th colspan="2"><b><?= GetMessage("FORM_CAPTCHA_TABLE_TITLE") ?></b></th>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td><input type="hidden" name="captcha_sid"
                           value="<?= htmlspecialcharsbx($arResult["CAPTCHACode"]); ?>"/><img
                        src="/bitrix/tools/captcha.php?captcha_sid=<?= htmlspecialcharsbx($arResult["CAPTCHACode"]); ?>"
                        width="180" height="40"/></td>
            </tr>
            <tr>
                <td><?= GetMessage("FORM_CAPTCHA_FIELD_TITLE") ?><?= $arResult["REQUIRED_SIGN"]; ?></td>
                <td><input type="text" name="captcha_word" size="30" maxlength="50" value="" class="inputtext"/></td>
            </tr>
        <?
        } // isUseCaptcha
        ?>

        <hr/>
        <div class="inner">
            <input type="submit" name="web_form_submit" class="empty-btn black" value="<?= GetMessage("Отправить") ?>"/>
            <small><?= GetMessage("Поля, отмеченные * обязательны для заполнения.") ?></small>
        </div>

        <?= $arResult["FORM_FOOTER"] ?>
    </div>
<?
} //endif (isFormNote)
?>
<?if ($arResult['arForm']['ID']==10):?>
    <script type="text/javascript">
        $('.js-validation-empty.materials textarea').val('материалы со страницы');
        <?if ($USER->IsAuthorized()):?>
        <?
        global $USER;
        $user_mail = $USER->GetEmail();
        ?>
        $('.js-validation-empty input[name=form_text_68]').val('<?=$user_mail?>');
        <?endif;?>
    </script>
<?endif;?>