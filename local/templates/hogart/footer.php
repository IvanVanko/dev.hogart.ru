<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
use \Bitrix\Main\Localization\Loc;
Loc::loadLanguageFile(__FILE__);
global $MESS;
?>
<? if($APPLICATION->GetCurDir() != SITE_DIR): ?>
    <footer class="inner">
        <span>© 2014, ООО «Хогарт», 117041,  г. Москва, ул Поляны, 52</span>
        <a href="#" class="p_logo"></a>
    </footer>
<? endif; ?>

<!-- Yandex.Metrika counter -->
<script type="text/javascript">
    (function (d, w, c) {
        (w[c] = w[c] || []).push(function () {
            try {
                w.yaCounter31665391 = new Ya.Metrika({
                    id: 31665391,
                    clickmap: true,
                    trackLinks: true,
                    accurateTrackBounce: true,
                    webvisor: true,
                    trackHash: true
                });
            } catch (e) {
            }
        });

        var n = d.getElementsByTagName("script")[0],
            s = d.createElement("script"),
            f = function () {
                n.parentNode.insertBefore(s, n);
            };
        s.type = "text/javascript";
        s.async = true;
        s.src = "https://mc.yandex.ru/metrika/watch.js";

        if (w.opera == "[object Opera]") {
            d.addEventListener("DOMContentLoaded", f, false);
        } else {
            f();
        }
    })(document, window, "yandex_metrika_callbacks");
</script>
<noscript>
    <div><img src="https://mc.yandex.ru/watch/31665391" style="position:absolute; left:-9999px;" alt=""/>
    </div>
</noscript>
<!-- /Yandex.Metrika counter -->


<script>
    (function (i, s, o, g, r, a, m) {
        i['GoogleAnalyticsObject'] = r;
        i[r] = i[r] || function () {
                (i[r].q = i[r].q || []).push(arguments)
            }, i[r].l = 1 * new Date();
        a = s.createElement(o),
            m = s.getElementsByTagName(o)[0];
        a.async = 1;
        a.src = g;
        m.parentNode.insertBefore(a, m)
    })(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');

    ga('create', 'UA-65677084-1', 'auto');
    ga('send', 'pageview');

</script>

</div>
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

</body>
</html>
