<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
/**
 * @global $APPLICATION
 * @global $USER
 */
use \Bitrix\Main\Localization\Loc;
?>
<header class="header-cnt">
    <div class="inner">
        <? $APPLICATION->IncludeComponent("bitrix:search.form", "header", Array(
                "USE_SUGGEST" => "N",
                "PAGE" => "#SITE_DIR#search/index.php"
            )
        ); ?>
        <? if (LANGUAGE_ID != 'en'): ?>
            <a class="profile-url<? if ($USER->IsAuthorized()): ?> authorized<? endif; ?>"
               href="/account/">
                <i class="icon-profile icon-full"></i>
                <? if ($USER->IsAuthorized()): ?>
                    <span class="hide-text"><?= $USER->GetLogin() ?></span>
                <? else: ?>
                    <span class="hide-text"><?= Loc::getMessage("Личный кабинет") ?></span>
                <? endif; ?>
            </a>
        <? endif; ?>
        <nav class="header-nav">
            <ul>
                <li class="first">
                    <a href="<?= SITE_DIR ?>stock/"><?= Loc::getMessage("Акции") ?></a>
                </li>
                <li class="ya-phone">
                    <? $APPLICATION->IncludeComponent("bitrix:main.include", "", Array(
                            "AREA_FILE_SHOW" => "sect",
                            "AREA_FILE_SUFFIX" => "inc_phone",
                            "AREA_FILE_RECURSIVE" => "Y",
                            "EDIT_TEMPLATE" => "standard.php"
                        )
                    ); ?>
                    <? $APPLICATION->IncludeComponent("bitrix:main.include", "", Array(
                            "AREA_FILE_SHOW" => "sect",
                            "AREA_FILE_SUFFIX" => "inc_piter_phone",
                            "AREA_FILE_RECURSIVE" => "Y",
                            "EDIT_TEMPLATE" => "standard.php"
                        )
                    ); ?>
                </li>
                <li class="email">
                    <? $APPLICATION->IncludeComponent("pirogov:headerEmail", "", Array(
                            "TOP_EMAIL" => "info@hogart.ru",
                            "BOTTOM_EMAIL" => "info@spb.hogart.ru",
                        )
                    ); ?>
                </li>
                <li class="feedback"><a class="js-popup-open" data-popup="#popup-os"
                                        href="#"><?= Loc::getMessage('Обратная связь') ?></a></li>
            </ul>
            <div class="cart">
                <? $APPLICATION->IncludeComponent("hogart.lk:account.cart.add", "", [
                    'CART_URL' => '/account/cart/'
                ]); ?>
            </div>
            <? $switcher = $APPLICATION->GetLangSwitcherArray(); ?>
            <? /*if (count($switcher) > 1): ?>
                <div class="lang-switcher">
                    <? foreach ($switcher as $lang): ?>
                        <? if ($lang["LANGUAGE_ID"] == LANGUAGE_ID): ?>
                            <? continue; ?>
                        <? endif; ?>
                        <a class="switcher-<?= $lang["LANGUAGE_ID"] ?>"
                           style="display: inline-block;line-height: 55px;color: white;text-decoration: none;"
                           href="<?= $lang["DIR"] ?>"><?= $lang["LANGUAGE_ID"] ?></a>
                    <? endforeach; ?>
                </div>
            <? endif;*/ ?>
        </nav>
    </div>
</header>