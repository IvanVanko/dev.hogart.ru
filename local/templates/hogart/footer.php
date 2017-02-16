<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
use \Bitrix\Main\Localization\Loc;
Loc::loadLanguageFile(__FILE__);
global $MESS;
?>
<? if($APPLICATION->GetCurDir() != SITE_DIR): ?>
    <footer class="inner col-md-12 col-xs-12 col-sm-12">
        <span class="footer__address">© 2014, ООО «Хогарт», 117041,  г. Москва, ул Поляны, 52</span>
        <a target="_blank" href="http://oldschool.agency" class="p_logo"></a>
        <div class="credits-mobile">
            <a href="http://hogart.bx.oldschool.ru/" class="m_logo">
                <img src="/images/m_logo.svg" alt="" title="" />
            </a>
            <div class="footer-mobile__right">
                <span class="address">© 2014, ООО «Хогарт»</span>
            </div>
            <a href="#" title="" class="help js-help">
                <img src="/images/help.svg" alt="" title="" />
            </a>
            <div class="footer-menu" id="accordion-footer">
                <div class="footer-menu__panel panel panel-default">
                    <a class="footer-menu__link" data-toggle="collapse" data-parent="#accordion-footer" href="#footer-contact" aria-expanded="false" title="Позвонить">Позвонить
                    </a>
                    <div id="footer-contact" class="footer-menu__content collapse panel-collapse">
                        <a class="footer-menu__tel" href="tel:84957881112" title="">+7 (495) 788-11-12</a>
                        <a class="footer-menu__tel" href="tel:88127034114" title="">+7 (812) 703-41-14</a>
                    </div>
                </div>
                <div class="footer-menu__panel panel panel-default">
                    <a class="footer-menu__link" data-toggle="collapse" data-parent="#accordion-footer" href="#footer-map" aria-expanded="false" title="Проехать">Проехать
                    </a>
                    <div id="footer-map" class="footer-menu__content collapse panel-collapse">
                        <ul class="contacts-list-mobile">
                            <li class=" active">
                                <a href="/contacts/tsentralnyy-ofis-khogart-v-moskve-sklad-i-servisnaya-sluzhba/">
                                <i class="fa fa-map-marker" aria-hidden="true"></i>
                                Центральный офис "Хогарт" в Москве, склад и сервисная служба
                                </a>
                            </li>
                            <li class="">
                                <a href="/contacts/ofis-kompanii-khogart-v-sankt-peterburge/">
                                <i class="fa fa-map-marker" aria-hidden="true"></i>
                                Офис компании «Хогарт» в Санкт-Петербурге
                                </a>
                            </li>
                            <li class="">
                                <a href="/contacts/salon-khogart-art-v-tsentre-dizayna-i-arkhitektury-artplay/">
                                <i class="fa fa-map-marker" aria-hidden="true"></i>
                                Салоно ХОГАРТ_арт в ARTPLAY
                                </a>
                            </li>
                                <li class="">
                                <a href="/contacts/calon-khogart-art-na-ulitse-khamovnicheskiy-val/">
                                <i class="fa fa-map-marker" aria-hidden="true"></i>
                                Cалон «Хогарт арт» на улице Хамовнический Вал
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </footer>
<? endif; ?>

<? if($APPLICATION->GetCurDir() == SITE_DIR): ?>
    </div>
    </div>
    </div>
<? else: ?>
    </div>
    </div>
    </div>
    </div>
    </div>
<?endif; ?>
</div>
</div>

<div class="popup-cnt">
    <div class="inner-cnt" id="popup-os">
        <? $APPLICATION->IncludeComponent(
            "bitrix:form.result.new",
            "feedback",
            Array(
                "WEB_FORM_ID" => "FEEDBACK_" . strtoupper(LANGUAGE_ID),
                "IGNORE_CUSTOM_TEMPLATE" => "N",
                "USE_EXTENDED_ERRORS" => "N",
                "SEF_MODE" => "N",
                "VARIABLE_ALIASES" => Array("WEB_FORM_ID" => "WEB_FORM_ID", "RESULT_ID" => "RESULT_ID"),
                "CACHE_TYPE" => "A",
                "CACHE_TIME" => "3600",
                "LIST_URL" => "",
                "EDIT_URL" => "",
                "SUCCESS_URL" => "",
                "CHAIN_ITEM_TEXT" => "",
                "CHAIN_ITEM_LINK" => ""
            ), $component
        ); ?>
    </div>
</div>
<div class="popup-cnt">
    <div class="inner-cnt" id="popup-pm">
        <? $APPLICATION->IncludeComponent(
            "bitrix:form.result.new",
            "feedback",
            Array(
                "WEB_FORM_ID" => "2",
                "IGNORE_CUSTOM_TEMPLATE" => "N",
                "USE_EXTENDED_ERRORS" => "Y",
                "SEF_MODE" => "N",
                "VARIABLE_ALIASES" => Array("WEB_FORM_ID" => "WEB_FORM_ID", "RESULT_ID" => "RESULT_ID"),
                "CACHE_TYPE" => "A",
                "CACHE_TIME" => "3600",
                "LIST_URL" => "",
                "EDIT_URL" => "",
                "SUCCESS_URL" => "",
                "CHAIN_ITEM_TEXT" => "",
                "CHAIN_ITEM_LINK" => ""
            ), $component
        ); ?>

    </div>
</div>
<div class="popup-cnt popup-email">
    <div class="inner-cnt" id="popup-subscribe">
        <?
        global $USER;
        $user_mail = $USER->GetEmail();
        $user_fio = $USER->GetFullName();
        $form_sid = "SHARE_EMAIL_" . strtoupper(LANGUAGE_ID);
        CModule::IncludeModule("form");
        $form_id = CForm::GetById($form_sid, "Y")->Fetch()["ID"];
        ?>
        <? $APPLICATION->IncludeComponent(
            "bitrix:form.result.new",
            "share_email",
            Array(
                "WEB_FORM_ID" => $form_id,
                "IGNORE_CUSTOM_TEMPLATE" => "N",
                "USE_EXTENDED_ERRORS" => "Y",
                "SEF_MODE" => "N",
                "VARIABLE_ALIASES" => Array("WEB_FORM_ID" => "WEB_FORM_ID", "RESULT_ID" => "RESULT_ID"),
                "CACHE_TYPE" => "A",
                "CACHE_TIME" => "3600",
                "LIST_URL" => "",
                "EDIT_URL" => "",
                "SUCCESS_URL" => "",
                "CHAIN_ITEM_TEXT" => "",
                "CHAIN_ITEM_LINK" => ""
            ), $component
        ); ?>
        <? /*
        <form action="/ajax/send.php" method="post">

            <div class="inner form-cont-box">

                <p>Название старницы: <?= $APPLICATION->GetTitle() ?>
                    <input name="title_name" value="<?= $APPLICATION->GetTitle() ?>" type="hidden"/>
                </p>

                <p>Ссылка: http://hogart.ru<?= $APPLICATION->GetCurDir() ?></p>
                <input name="page_href" value="http://hogart.ru<?=$APPLICATION->GetCurDir() ?>" type="hidden"/>
                </div>
            <hr>
            <div class="inner form-cont-box">
                <div class="field custom_label">
                    <label for="sending_email">e-mail:<span class="form-required starrequired">*</span></label>
                    <input type="text" class="inputtext" name="sending_email" value="<?=($GLOBALS['USER']->IsAuthorized())?$user_mail:'' ?>" size="0">
                </div>
            </div>
            <hr>
            <div class="inner form-cont-box">
                <input type="submit" name="sending_email_form" class="empty-btn black" value="Отправить">
                <small>Поля, отмеченные * обязательны для заполнения.</small>
            </div>
            <div class="inner success" style="display: none;">
                Вы поделились ссылкой успешно!
            </div>
        </form>
*/
        ?>
    </div>
</div>
<div class="popup-cnt">
    <div class="inner-cnt" id="popup-subscribe-email">
        <form action="/ajax/send_to_email.php" method="post">
            <div class="inner form-cont-box">
                <p><?= GetMessage("Название страницы") ?>: <?= $APPLICATION->GetTitle() ?>
                    <input name="title_name" value="<?= $APPLICATION->GetTitle() ?>" type="hidden"/>
                </p>

                <p><?= GetMessage("Ссылка") ?>: http://hogart.ru<?= $APPLICATION->GetCurDir() ?></p>
                <input name="page_href" value="http://hogart.ru<?=$APPLICATION->GetCurDir() ?>" type="hidden"/>
                </div>
            <hr>
            <div class="inner form-cont-box">
                <div class="field custom_label">
                    <label for="sending_email">e-mail:<span class="form-required starrequired">*</span></label>
                    <input type="text" class="inputtext" name="sending_email" value="<?=($GLOBALS['USER']->IsAuthorized())?$user_mail:'' ?>" size="0">
                </div>
            </div>
            <hr>
            <div class="inner form-cont-box">
                <input type="submit" name="sending_email_form" class="empty-btn black" value="<?= GetMessage("Отправить") ?>">
                <small><?= GetMessage("Поля, отмеченные * обязательны для заполнения.") ?></small>
            </div>
            <div class="inner success" style="display: none;">
                <?= GetMessage("Вы поделились ссылкой успешно!")?>
            </div>
        </form>
    </div>
</div>
<div class="popup-cnt">
    <div class="inner-cnt" id="popup-subscribe-mod">
        <div class="head inner">
            <h2><?= GetMessage("Подписаться на новости") ?></h2>
            <a href="#" class="close"></a>
        </div>
        <div class="inner">

            <div id="feednews" class="subscribe-black">
                <? $APPLICATION->IncludeComponent(
                    "bitrix:subscribe.edit",
                    "left_form",
                    Array(
                        "COMPONENT_TEMPLATE" => ".default",
                        "SHOW_HIDDEN" => "N",
                        "AJAX_MODE" => "N",
                        "AJAX_OPTION_JUMP" => "N",
                        "AJAX_OPTION_STYLE" => "Y",
                        "AJAX_OPTION_HISTORY" => "N",
                        "CACHE_TYPE" => "A",
                        "CACHE_TIME" => "3600",
                        "ALLOW_ANONYMOUS" => "Y",
                        "SHOW_AUTH_LINKS" => "Y",
                        "SET_TITLE" => "N"
                    )
                ); ?>
            </div>
        </div>
    </div>
</div>

<div class="popup-cnt">
    <div class="inner-cnt" id="popup-subscribe-phone">

        <div class="head inner">
            <h2><?= GetMessage("Отправить по SMS") ?></h2>
            <a href="#" class="close"></a>
        </div>

        <form action="/ajax/smsc_send.php" method="post">
            <div class="inner form-cont-box">

                <p><?= GetMessage("Название страницы") ?>: <?=$APPLICATION->GetTitle()?>
                    <input name="title_name" value="<?=$APPLICATION->GetTitle()?>" type="hidden"/>
                </p>

                <p><?= GetMessage("Ссылка") ?>: <?= ("http://" . ($_SERVER["SERVER_NAME"] ?: $_SERVER['HTTP_HOST'])) ?><?=$APPLICATION->GetCurDir()?></p>
                <input name="page_href" value="<?= ("http://" . ($_SERVER["SERVER_NAME"] ?: $_SERVER['HTTP_HOST'])) ?><?=$APPLICATION->GetCurDir()?>" type="hidden"/>

            </div>
            <hr>
            <div class="inner form-cont-box">
                <div class="field custom_label phone">
                    <label for="sending_phone"><?= GetMessage("Телефон") ?>:<span class="form-required starrequired">*</span></label>
                    <input type="text" class="inputtext" name="sending_phone" value="" size="0">
                </div>
            </div>
            <div class="inner form-cont-box">
                <div class="field custom_label">
                    <label for="sending_phone"><?= GetMessage("Подпись") ?>:</label>
                    <input type="text" class="inputtext" name="user_msg"
                           value="<?=($GLOBALS['USER']->IsAuthorized()) ? "от ".$user_fio : ''?>" size="25">
                </div>
            </div>
            <hr>
            <div class="inner form-cont-box">
                <input type="hidden" name="message_title" value="<?= $MESS['SMS_TITLE']; ?>">
                <input type="submit" name="sending_phone_form" class="empty-btn black" value="<?= GetMessage("Отправить") ?>">
                <small><?= GetMessage("Поля, отмеченные * обязательны для заполнения.")?></small>
            </div>
            <div class="inner success" style="display: none;">
                <?= GetMessage("Вы поделились ссылкой успешно!") ?>
            </div>
        </form>
    </div>
</div>
<? if (LANGUAGE_ID != 'en'): ?>
<div class="popup-cnt">
    <div class="inner-cnt" id="popup-login">
        <div class="head inner">
            <h2><?= GetMessage("Войти в личный кабинет") ?></h2>
            <a href="#" class="close"></a>
        </div>
        <? $APPLICATION->IncludeComponent("bitrix:system.auth.form", "auth", Array(
                "REGISTER_URL" => "/register/",
                "FORGOT_PASSWORD_URL" => "/",
                "PROFILE_URL" => "/profile/",
                "SHOW_ERRORS" => "Y"
            )
        ); ?>

    </div>
</div>
<? endif; ?>
<? $description = substr($APPLICATION->GetProperty('description'), "0", "400"); ?>
<? $APPLICATION->SetPageProperty('description', $description); ?>
<? ?>
<div class="popup-cnt">
    <div class="inner-cnt" id="popup-msg-product">
        <div class="head inner">
            <h2>Сообщение</h2>
            <a href="#" class="close"></a>
        </div>

        <div class="js-validation-form">
            <form action="#" onsubmit="return false;">
                <div class="inner">
                    <p>Для покупки данного товара необходимо авторизоваться в Личном Кабинете или обратиться в компанию
                        для связи с менеджером по продажам</p>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="scroll-up">
    <i class="fa fa-chevron-up" aria-hidden="true"></i>
</div>

</body>
</html>
