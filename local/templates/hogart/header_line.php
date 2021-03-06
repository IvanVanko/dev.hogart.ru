<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
/**
 * @global $APPLICATION
 * @global $USER
 */
use \Bitrix\Main\Localization\Loc;

$authorized = $USER->IsAuthorized();
?>
<header class="header-cnt">
    <div class="inner">
        <? $APPLICATION->IncludeComponent("hogart.lk:site.search.form", "", Array(
                "PAGE" => "#SITE_DIR#search/index.php"
            )
        ); ?>
        <? if (LANGUAGE_ID != 'en'): ?>
            <? if ($USER->IsAuthorized()): ?>
                <ul data-depth="1" class="profile-url authorized">
                    <li class="item" data-depth="1">
                        <a href="/account/">
                            <span class="hide-text"><?= $USER->GetLogin() ?></span>
                            <i class="fa fa-chevron-down" aria-hidden="true"></i>
                        </a>
                        <ul data-depth="2">
                            <? $APPLICATION->IncludeComponent("hogart.lk:account.menu", "", []) ?>
                            <li class="item" data-depth="2">
                                <a href="?logout=yes"><i class="fa fa-sign-out" aria-hidden="true"></i> Выход</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            <? else: ?>
                <a class="profile-url" href="/account/">
                    <i class="icon-profile icon-full"></i>
                    <span class="hide-text"><?= Loc::getMessage("Личный кабинет") ?></span>
                </a>
            <? endif; ?>
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
    <div class="header-mobile">
        <a class="header-mobile__menu" href="#" title="">
            <img src="/images/header-menu.svg" />
        </a>
        <div class="header-mobile__search">
            <label for="input_search" class="header-mobile__search-label">
                <img src="/images/header-search.svg" />
            </label>
            <input id="input_search" class="header-mobile__search-input active" placeholder="Артикул или наименование..." />
        </div>
        <? if ($authorized) {?>
            <? $APPLICATION->IncludeComponent("hogart.lk:account.cart.add", "mobile", [
                'CART_URL' => '/account/cart/'
            ]); ?>
        <?}?>
    </div>
</header>