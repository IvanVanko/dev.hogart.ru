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
        <div class="hamburger-mobile">
            <ul class="main-navigation"  id="accordion-hamburger" role="tablist" aria-multiselectable="true">
                <li>
                    <a class="main-navigation__link" role="tab" data-toggle="collapse" data-parent="#accordion-hamburger" href="#hamburger-about-company" aria-expanded="false" aria-controls="amenities" title="О компании">
                        <div class="image">
                            <img src="/images/navigation-1.svg" alt="" title="" />
                        </div>
                        <span>О компании</span>
                    </a>
                    <ul id="hamburger-about-company" role="tabpanel" class="navigation-sub-menu collapse panel-collapse">
                        <li>
                            <a class="not-line" href="/contacts/tsentralnyy-ofis-khogart-v-moskve-sklad-i-servisnaya-sluzhba/" title="Контакты">
                                <div class="image">
                                    <img src="/images/navigation-2.svg" alt="" title="" />
                                </div>
                                <span>Контакты</span>
                            </a>
                        </li>
                    </ul>

                </li>

                <li>
                    <a role="tab" class="main-navigation__link" data-toggle="collapse" data-parent="#accordion-hamburger" href="#hamburger-product" aria-expanded="false" aria-controls="amenities" title="Продукция">
                        <div class="image">
                            <img src="/images/navigation-3.svg" alt="" title="">
                        </div>
                        <span>Продукция</span>
                    </a>
                    <ul id="hamburger-product" role="tablist" aria-multiselectable="true" class="navigation-sub-menu catalog-mobile--main panel-collapse collapse" "="" aria-expanded="true" style="">
        
                        <li class="catalog-mobile__column">
                            <a href="/catalog/#heating_1383" title="Вентиляция">Вентиляция</a>
                        </li>
                            
                        <li class="catalog-mobile__column">
                            <a href="/catalog/#heating_1380" title="Канализация">Канализация</a>
                        </li>
                            
                        <li class="catalog-mobile__column">
                            <a href="/catalog/#heating_1381" title="Отопление">Отопление</a>
                        </li>
                            
                        <li class="catalog-mobile__column">
                            <a href="/catalog/#heating_1379" title="Плитка">Плитка</a>
                        </li>
                            
                        <li class="catalog-mobile__column">
                            <a href="/catalog/#heating_1382" title="Сантехника">Сантехника</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="/brands/" title="Бренды">
                        <div class="image">
                            <img src="/images/navigation-4.svg" alt="" title="" />
                        </div>
                        <span>Бренды</span>
                    </a>
                </li>
                <li>
                    <a href="/documentation/" title="Документация">
                        <div class="image">
                            <img src="/images/navigation-5.svg" alt="" title="" />
                        </div>
                        <span>Документация</span>
                    </a>
                </li>
                <?php if ($authorized) {?>
                    <li>
                        <a class="main-navigation__link" role="tab" data-toggle="collapse" data-parent="#accordion-main" href="#gamburger-lk" aria-expanded="false" aria-controls="amenities" title="Личный кабинет">
                            <div class="image">
                                <img src="/images/navigation-6.svg" alt="" title="" />
                            </div>
                            <span>Личный кабинет</span>
                        </a>
                        <ul id="gamburger-lk" role="tabpanel" class="navigation-sub-menu collapse panel-collapse">
                            <li>
                                <a class="not-line" href="/account/orders/active/" title="Заказы">
                                    <div class="image">
                                        <img src="/images/navigation-7.svg" alt="" title="" />
                                    </div>
                                    <span>Заказы</span>
                                </a>
                            </li>
                            <li>
                                <a class="not-line" href="/account/reports/" title="Отчеты">
                                    <div class="image">
                                        <img src="/images/navigation-8.svg" alt="" title="" />
                                    </div>
                                    <span>Отчеты</span>
                                </a>
                            </li>
                            <li>
                                <a class="not-line" href="/account/settings/" title="Настройки">
                                    <div class="image">
                                        <img src="/images/navigation-9.svg" alt="" title="" />
                                    </div>
                                    <span>Настройки</span>
                                </a>
                            </li>
                            <li>
                                <a class="not-line" href="/account/documents/" title="Юридические лица">
                                    <div class="image">
                                        <img src="/images/navigation-10.svg" alt="" title="" />
                                    </div>
                                    <span>Юридические лица</span>
                                </a>
                            </li>
                            <li>
                                <a class="not-line" href="?logout=yes" title="Выход">
                                    <div class="image">
                                        <img src="/images/navigation-11.svg" alt="" title="" />
                                    </div>
                                    <span>Выход</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                <?} else {?>
                    <li>
                        <a href="/account/" title="Личный кабинет">
                            <div class="image">
                                <img src="/images/navigation-6.svg" alt="" title="" />
                            </div>
                            <span>Личный кабинет</span>
                        </a>
                    </li>
                <?}?>
            </ul>
        </div>
        <div class="header-mobile__search">
            <label for="input_search" class="header-mobile__search-label">
                <img src="/images/header-search.svg" />
            </label>
            <input id="input_search" class="header-mobile__search-input" />
        </div>
        <? if ($authorized) {?>
            <? $APPLICATION->IncludeComponent("hogart.lk:account.cart.add", "mobile", [
                'CART_URL' => '/account/cart/'
            ]); ?>
        <?}?>
    </div>
</header>