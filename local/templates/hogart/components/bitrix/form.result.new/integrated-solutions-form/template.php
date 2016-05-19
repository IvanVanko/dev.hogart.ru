<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
?>
<? if($arResult["isFormErrors"] == "Y"): ?><?=$arResult["FORM_ERRORS_TEXT"];?><? endif; ?>

<?=$arResult["FORM_NOTE"]?>

<? if($arResult["isFormNote"] != "Y") {
    ?>


    <?
    /***********************************************************************************
     * form header
     ***********************************************************************************/
    ?>
    <? if($arResult["isFormTitle"]):?>
        <? if($arResult['arForm']['ID'] == 8 || $arResult['arForm']['ID'] == 7):?>
            <h2 class="nomargin"><?=$arResult["FORM_TITLE"]?></h2>
        <? else:?>
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
    <div class="js-validation-form">
        <?=$arResult["FORM_HEADER"]?>
        <div class="inner">
            <? foreach($arResult["QUESTIONS"] as $FIELD_SID => $arQuestion):?>
                <? if($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'hidden'):
                    echo $arQuestion["HTML_CODE"];
                elseif($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'textarea'):?>
                    <div class="field custom_label nomargin js-validation-empty">
                        <label
                            for="text1"><?=$arQuestion["CAPTION"]?><? if($arQuestion["REQUIRED"] == "Y") { ?> *<? } ?></label>
                        <?=$arQuestion["HTML_CODE"]?>
                    </div>
                <? elseif($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'dropdown'):?>
                    <div class="field custom_label custom_select js-validation-empty">
                        <label
                            for="form_dropdown_<?=$FIELD_SID?>"><?=$arQuestion["CAPTION"]?><? if($arQuestion["REQUIRED"] == "Y") { ?> *<? } ?></label>
                        <select id="form_dropdown_<?=$FIELD_SID?>" name="form_dropdown_<?=$FIELD_SID?>">
                            <option value="">Выбрать тему</option>
                            <? foreach($arQuestion['STRUCTURE'] as $value):?>
                                <option value="<?=$value['ID']?>"><?=$value['MESSAGE']?></option>
                            <? endforeach; ?>
                        </select>
                    </div>
                <? else:?>
                    <?
                    if(strtolower($arQuestion["CAPTION"]) == 'телефон'):
                        $customClass = 'phone js-validation-phone';
                    elseif($arQuestion["CAPTION"] == 'E-mail'):
                        $customClass = 'js-validation-email';
                    elseif(strtolower($arQuestion["CAPTION"]) == 'отчество'):
                        $customClass = 'not';
                    else:
                        $customClass = 'js-validation-empty';
                    endif; ?>
                    <div class="field custom_label <?=$customClass?>">
                        <label
                            for="form_<?=$arQuestion['STRUCTURE'][0]['FIELD_TYPE'].'_'.$arQuestion['STRUCTURE'][0]['ID']?>"><?=$arQuestion["CAPTION"]?><? if($arQuestion["REQUIRED"] == "Y") { ?> *<? } ?></label>
                        <?=$arQuestion["HTML_CODE"]?>
                    </div>
                <? endif; ?>
            <? endforeach; ?>
            <input type="submit" name="web_form_submit" class="empty-btn" value="<?= GetMessage("Отправить") ?>"/>
            <br/>
            <small><?= GetMessage("Поля, отмеченные * обязательны для заполнения.") ?></small>
        </div>
        <?=$arResult["FORM_FOOTER"]?>
    </div>
    <?
} //endif (isFormNote)
?>
