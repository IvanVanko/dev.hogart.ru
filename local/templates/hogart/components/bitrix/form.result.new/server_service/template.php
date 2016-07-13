<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
?>
<?
?>
<?if($arResult["isFormTitle"]): ?>
    <? if($arResult['arForm']['SID'] == 'REQUEST_FOR_SERVICE_EN' || $arResult['arForm']['SID'] == 'REQUEST_FOR_SERVICE_RU'): ?>
        <h3 class="nomargin"><?=$arResult["FORM_TITLE"]?></h3>
        <div class="form-desc-txt">
            <span class="show-js-validation-form-new btn btn-primary"><?= GetMessage("Заполнить заявку")?></span>
        </div>
        <br>
    <? else: ?>
        <div class="head inner">
            <h3><?=$arResult["FORM_TITLE"]?></h3>
            <?if($arResult["isFormNote"] != "Y"){?>
            <a href="#" class="close"></a>
            <?}?>
        </div>
    <? endif; ?>
<? endif; ?>
<? if($arResult["isFormErrors"] == "Y"): ?><?=$arResult["FORM_ERRORS_TEXT"];?><? endif; ?>

    <p class="nomargin success-message"><?=$arResult["SUCCESS"] == "Y" ? $arParams["SUCCESS_MESSAGE"] : $arResult["FORM_NOTE"]?></p>

<?//if($arResult["isFormNote"] != "Y") {
    /***********************************************************************************
     * form questions
     ***********************************************************************************/
    ?>
    <div class="preview-project-viewport">
        <div class="preview-project-viewport-inner">
            <div class="js-validation-form-new" style="display: none;">
                <?=str_replace("<form", "<form autocomplete='false' ", $arResult["FORM_HEADER"])?>
                <? $i = 1; ?>
                <!-- fuck you blink engine -->
                <input style="display:none" type="text" name="fakeusernameremembered"/>
                <input style="display:none" type="password" name="fakepasswordremembered"/>
                <? foreach($arResult["QUESTIONS"] as $FIELD_SID => $arQuestion): ?>
                        <?if($i == 1) { ?>
                            <div class="hr"><span><?= GetMessage("Контактная информация") ?></span></div>
                        <? } ?>
                        <? if($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'hidden'):
                            echo $arQuestion["HTML_CODE"];
                        elseif($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'textarea'):?>
                            <?
                            if($arQuestion['REQUIRED'] !== 'Y'):
                                $customClass = 'not';
                            else:
                                $customClass = 'js-validation-empty';
                            endif;
                            ?>
                            <div class="field custom_label <?=$customClass?>">
                                <label
                                    for="text1"><?=$arQuestion["CAPTION"]?><?=$arQuestion['REQUIRED'] == 'Y' ? ' *' : ''?></label>
                                <?=$arQuestion["HTML_CODE"]?>
                            </div>
                            <?
                        elseif($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'dropdown'): ?>
                            <div class="field custom_label">
                                <label for="form_dropdown_<?=$FIELD_SID?>"><?=$arQuestion["CAPTION"]?> *</label>
                            </div>
                            <div class="form-group js-validation-empty">
                                <select class="form-control" id="form_dropdown_<?=$FIELD_SID?>" name="form_dropdown_<?=$FIELD_SID?>">
                                    <? if($arResult['arForm']['SID'] == 'REQUEST_FOR_SERVICE_EN' || $arResult['arForm']['SID'] == 'REQUEST_FOR_SERVICE_RU'): ?>
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
                        else:
                            ?>
                            <? if($arQuestion["CAPTION"] == 'Телефон' || $arQuestion["CAPTION"] == 'Tel. number'):
                                $customClass = 'phone js-validation-phone';
                            elseif($arQuestion["CAPTION"] == 'E-mail'):
                                $customClass = 'js-validation-email';
                            elseif($arQuestion['REQUIRED'] !== 'Y'):
                                $customClass = 'not';
                            else:
                                $customClass = 'js-validation-empty';
                            endif;
                            ?>
                            <div class="field custom_label <?=$customClass?>">
                                <label for="form_<?=$arQuestion['STRUCTURE'][0]['FIELD_TYPE'].'_'.$arQuestion['STRUCTURE'][0]['ID']?>"><?=$arQuestion["CAPTION"]?><?=$arQuestion['REQUIRED'] == 'Y' ? ' *' : ''?></label>
                                <?=str_replace("<input", "<input autocomplete='false' ", $arQuestion["HTML_CODE"])?>
                            </div><?
                        endif; ?>
                        <? $i++; ?>
                <? endforeach; ?>
                <div class="inner">
                    <input type="submit" name="web_form_submit" class="btn btn-primary" value="<?= GetMessage("Отправить") ?>"/> <br/><br/>
                    <small><?= GetMessage("Поля, отмеченные * обязательны для заполнения.") ?></small>
                </div>

                <?=$arResult["FORM_FOOTER"]?>
            </div>
        </div>
    </div>
    <?
//} //endif (isFormNote)
?>