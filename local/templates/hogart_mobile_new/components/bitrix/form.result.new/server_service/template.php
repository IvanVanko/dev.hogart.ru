<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
?>
<? if($arResult["isFormErrors"] == "Y"): ?><?=$arResult["FORM_ERRORS_TEXT"];?><? endif; ?>

    <p><?=$arResult["SUCCESS"] == "Y" ? $arParams["SUCCESS_MESSAGE"] : $arResult["FORM_NOTE"]?></p>

    <!-- <? //var_dump($arResult)?> -->
<? if($arResult["isFormNote"] != "Y") {
    ?>


    <?
    /***********************************************************************************
     * form header
     ***********************************************************************************/
    ?>
    <? if($arResult["isFormTitle"]): ?>
        <? if($arResult['arForm']['ID'] == 8): ?>
            <h2 class="nomargin"><?=$arResult["FORM_TITLE"]?></h2>
            <div class="form-desc-txt">
                <?=$arResult['arForm']['DESCRIPTION'];?>
                <span class="show-js-validation-form-new empty-btn">Заполнить заявку</span>
            </div>

        <? else: ?>
            <div class="head inner">
                <h2><?=$arResult["FORM_TITLE"]?></h2>
                <a href="#" class="close"></a>
            </div>
        <? endif; ?>
    <? endif; ?>
    <?
    /***********************************************************************************
     * form questions
     ***********************************************************************************/
    ?>
    <div class="preview-project-viewport">
    <div class="preview-project-viewport-inner">
        <div class="js-validation-form-new" style="display: none;">
            <pre><? //print_r($arResult["QUESTIONS"]);?></pre>
            <?=$arResult["FORM_HEADER"]?>
            <? $i = 1; ?>
            <? foreach($arResult["QUESTIONS"] as $FIELD_SID => $arQuestion): ?>
            <? if($arResult['arForm']['ID'] == 8): ?>
            <div class="inner">
                <? else: ?>

            <? if($i == 1): ?>
            <div class="inner">
            <? elseif($i == 3): ?>
            </div>
                <div class="hr"><span>Контактная информация</span></div>
                <div class="inner">
                    <? endif; ?>
                    <? endif; ?>
                    <? if($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'hidden'):
                        echo $arQuestion["HTML_CODE"];
                    elseif($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'textarea'):?>
                        <div class="field custom_label js-validation-empty">
                            <label
                                for="text1"><?=$arQuestion["CAPTION"]?><?=$arQuestion['REQUIRED'] == 'Y' ? ' *' : ''?></label>

                            <!--            <div class="js-validation-empty">--><? //= $arQuestion["HTML_CODE"]
                            ?><!--</div>-->
                            <?=$arQuestion["HTML_CODE"]?>
                        </div>
                        <?
                    elseif($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'dropdown'): ?>
                        <div class="field custom_label">
                            <label for="form_dropdown_<?=$FIELD_SID?>"><?=$arQuestion["CAPTION"]?> *</label>
                        </div>
                        <div class="field custom_select js-validation-empty">
                            <select id="form_dropdown_<?=$FIELD_SID?>" name="form_dropdown_<?=$FIELD_SID?>">
                                <? if($arResult['arForm']['ID'] == 8): ?>
                                    <? foreach($arQuestion['STRUCTURE'] as $value): ?>
                                        <option value="<?=$value['ID']?>"><?=$value['MESSAGE']?></option>
                                    <? endforeach; ?>
                                <? else: ?>
                                    <option value="">Выбрать тему</option>
                                    <? foreach($arQuestion['STRUCTURE'] as $value): ?>
                                        <option value="<?=$value['ID']?>"><?=$value['MESSAGE']?></option>
                                    <? endforeach; ?>
                                <? endif; ?>

                            </select>
                        </div>
                        <?
                    else: ?>
                        <? if($arQuestion["CAPTION"] == 'Телефон'):
                            $customClass = 'phone js-validation-phone';
                        elseif($arQuestion["CAPTION"] == 'E-mail'):
                            $customClass = 'js-validation-email';
                        else:
                            $customClass = 'js-validation-empty';
                        endif;
                        ?>
                        <div class="field custom_label <?=$customClass?>">
                            <!--    <div class="field custom_label">-->
                            <label
                                for="form_<?=$arQuestion['STRUCTURE'][0]['FIELD_TYPE'].'_'.$arQuestion['STRUCTURE'][0]['ID']?>"><?=$arQuestion["CAPTION"]?><?=$arQuestion['REQUIRED'] == 'Y' ? ' *' : ''?></label>
                            <?=$arQuestion["HTML_CODE"]?>
                        </div>
                    <? endif; ?>
                    <? $i++; ?>
                    <? endforeach; ?>
                </div>
                <?
                if($arResult["isUseCaptcha"] == "Y") {
                    ?>
                    <tr>
                        <th colspan="2"><b><?=GetMessage("FORM_CAPTCHA_TABLE_TITLE")?></b></th>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td><input type="hidden" name="captcha_sid"
                                   value="<?=htmlspecialcharsbx($arResult["CAPTCHACode"]);?>"/><img
                                src="/bitrix/tools/captcha.php?captcha_sid=<?=htmlspecialcharsbx($arResult["CAPTCHACode"]);?>"
                                width="180" height="40"/></td>
                    </tr>
                    <tr>
                        <td><?=GetMessage("FORM_CAPTCHA_FIELD_TITLE")?><?=$arResult["REQUIRED_SIGN"];?></td>
                        <td><input type="text" name="captcha_word" size="30" maxlength="50" value="" class="inputtext"/>
                        </td>
                    </tr>
                    <?
                } // isUseCaptcha
                ?>

                <hr/>
                <div class="inner">
                    <input type="submit" name="web_form_submit" class="empty-btn" value="Отправить"/> <br/><br/>
                    <small>Поля, отмеченные * обязательны для заполнения.</small>
                </div>

                <?=$arResult["FORM_FOOTER"]?>
            </div>
        </div>
    </div>
    <?
} //endif (isFormNote)
?>