<?
/**
 * Bitrix Framework
 * @package bitrix
 * @subpackage main
 * @copyright 2001-2014 Bitrix
 */

/**
 * Bitrix vars
 * @global CMain $APPLICATION
 * @global CUser $USER
 * @param array $arParams
 * @param array $arResult
 * @param CBitrixComponentTemplate $this
 */

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
$arResult["SHOW_FIELDS"] = $arParams['SHOW_FIELDS'];
?>
<aside class="sidebar js-fh js-fixed-block js-paralax-height" data-fixed="top">
    <div class="inner js-paralax-item">
        <div class="reg-side-cnt padding js-validation-form-new">
            <? if($USER->IsAuthorized() || isset($_GET['success'])): ?>
                <p><? echo GetMessage("MAIN_REGISTER_AUTH") ?></p>
            <? else: ?>
                <h2>Для регистрации
                    в личном кабинете
                    оставьте заявку</h2>
                <?
                if(count($arResult["ERRORS"]) > 0) {
                    foreach($arResult["ERRORS"] as $key => $error) {
                        if(intval($key) == 0 && $key !== 0) {
                            $arResult["ERRORS"][$key] = str_replace("#FIELD_NAME#", "&quot;".GetMessage("REGISTER_FIELD_".$key)."&quot;", $error);
                        }
                    }
                    ShowError(implode("<br />", $arResult["ERRORS"]));
                }
                elseif($arResult["USE_EMAIL_CONFIRMATION"] === "Y") {
                    ?>
                    <p><? echo GetMessage("REGISTER_EMAIL_WILL_BE_SENT") ?></p>
                <? } ?>

                <form method="post" action="<?=POST_FORM_ACTION_URI?>" name="regform" enctype="multipart/form-data">
                    <? if($arResult["BACKURL"] <> ''): ?>
                        <input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>"/>
                    <? endif; ?>
                    <?foreach($arResult["SHOW_FIELDS"] as $FIELD):
                        switch($FIELD) {
                            case "LOGIN":
                                continue 2;
                                break;
                            case "CONFIRM_PASSWORD":
                                continue 2;
                                break;
                            default:
                                break;
                        }
                        $class = "";
                        if($FIELD == "PERSONAL_MOBILE"){
                            $class = "js-validation-phone";
                        }
                        ?>
                        <div class="field custom_label <?=$arResult["REQUIRED_FIELDS_FLAGS"][$FIELD] !== "Y" ? "not" : ""?>">
                            <label><?=GetMessage("REGISTER_FIELD_".$FIELD)?>
                                :<? if($arResult["REQUIRED_FIELDS_FLAGS"][$FIELD] == "Y"): ?><span
                                    class="starrequired">*</span><? endif ?></label>
                            <?
                            switch($FIELD) {
                                case "PASSWORD":
                                    ?><input size="30" placeholder="Придумайте пароль" type="password" name="REGISTER[<?=$FIELD?>]"
                                             value="<?=$arResult["VALUES"][$FIELD]?>" autocomplete="off"/>
                                    <?
                                    break;
                                case "LOGIN":
                                    break;
                                case "CONFIRM_PASSWORD":
                                    break;

                                case "PERSONAL_NOTES":
                                case "WORK_NOTES":
                                    ?><input type="text" name="REGISTER[<?=$FIELD?>]" value="<?=$arResult["VALUES"][$FIELD]?>" /><?
                                    break;
                                default:
                                    ?><input size="30" type="text" class="<?=$class?>" name="REGISTER[<?=$FIELD?>]"
                                             value="<?=$arResult["VALUES"][$FIELD]?>" /><?
                                    break;
                            } ?>
                        </div>
                    <? endforeach ?>
                    <input type="hidden" name="register_submit_button" value="y"/>
                    <button class="empty-btn" type="submit">оставить заявку</button>
                    <div><br> Поля, отмеченные * обязательны для заполнения.</div>
                </form>
                <?
                /* CAPTCHA */
                /*if($arResult["USE_CAPTCHA"] == "Y") {
                    ?>
                    <tr>
                        <td colspan="2"><b><?=GetMessage("REGISTER_CAPTCHA_TITLE")?></b></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <input type="hidden" name="captcha_sid" value="<?=$arResult["CAPTCHA_CODE"]?>"/>
                            <img src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult["CAPTCHA_CODE"]?>"
                                 width="180" height="40" alt="CAPTCHA"/>
                        </td>
                    </tr>
                    <tr>
                        <td><?=GetMessage("REGISTER_CAPTCHA_PROMT")?>:<span class="starrequired">*</span></td>
                        <td><input type="text" name="captcha_word" maxlength="50" value=""/></td>
                    </tr>
                    <?
                }*/
                /* !CAPTCHA */
                ?>
            <? endif ?>
        </div>
    </div>
</aside>