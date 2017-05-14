<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
use \Bitrix\Main\Localization\Loc;

Loc::loadLanguageFile(__FILE__);

header("Content-Security-Policy-Report-Only:
script-src 'self' *.hogart.ru hogart.ru bitrix.info bootstrap-notify.remabledesigns.com cdn.rawgit.com cdnjs.cloudflare.com maps.google.com mc.yandex.ru googleapis.com google-analytics.com;
connect-src 'self' *.hogart.ru hogart.ru bitrix.info hogart.ru;
report-uri /csp-report.php");

?>
<!DOCTYPE html>
<html data-lang="<?= LANGUAGE_ID ?>">
<head>
    <meta charset="UTF-8">
    <meta name="mailru-verification" content="7d762af6578aa853"/>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    
    <title><? $APPLICATION->ShowTitle("title") ?></title>
    <?

    global $USER;
    global $APPLICATION;

    $APPLICATION->AddHeadString("<link href='//fonts.googleapis.com/css?family=Roboto+Condensed:300,400,700&subset=latin,cyrillic' rel='stylesheet' type='text/css'>");
    $APPLICATION->AddHeadString("<link href='//fonts.googleapis.com/css?family=Roboto:900,700,500,400,300italic,300&subset=latin,cyrillic' rel='stylesheet' type='text/css'>");
    $APPLICATION->AddHeadString("<link rel=\"stylesheet\" href=\"//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css\">");
    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . "/bootstrap.css");
    $APPLICATION->SetAdditionalCSS("/local/assets/fontello/css/animation.css");
    $APPLICATION->SetAdditionalCSS("/local/assets/fontello/css/fontello.css");


    $APPLICATION->SetAdditionalCSS("/h/css/main.css");
    $APPLICATION->SetAdditionalCSS("/h/css/dop-css.css");
    $APPLICATION->SetAdditionalCSS("/h/css/bug-fix.css");
    $APPLICATION->SetAdditionalCSS("/h/css/jquery-ui.min.css");
    $APPLICATION->SetAdditionalCSS("/h/css/jquery-ui.hogart-dark.theme.min.css");
    $APPLICATION->SetAdditionalCSS("/h/css/nouislider.min.css");
    $APPLICATION->SetAdditionalCSS("/h/css/jquery.mCustomScrollbar.css");

    $APPLICATION->AddHeadScript("/h/js/jquery-1.11.2.min.js");
    $APPLICATION->AddHeadScript("/local/assets/bootstrap/js/transition.js");
    $APPLICATION->AddHeadScript("/local/assets/bootstrap/js/collapse.js");
    $APPLICATION->AddHeadScript("/local/assets/bootstrap/js/tab.js");
    $APPLICATION->AddHeadScript("/local/assets/bootstrap/js/tooltip.js");
    $APPLICATION->AddHeadScript("/local/assets/bootstrap/js/alert.js");
    $APPLICATION->AddHeadScript("/local/assets/bootstrap/js/dropdown.js");
    $APPLICATION->AddHeadScript("//cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick.min.js", true);

    $APPLICATION->SetAdditionalCSS("/h/css/houdini.min.css");
    $APPLICATION->AddHeadScript("/h/js/houdini.min.js");

    $APPLICATION->AddHeadScript("/h/js/jquery.reject.js");
    $APPLICATION->SetAdditionalCSS("/h/css/jquery.reject.css");
    $APPLICATION->AddHeadString('<script src="//maps.google.com/maps/api/js?sensor=true"></script>');
    $APPLICATION->AddHeadScript("/h/js/jquery.bxslider.min.js");
    $APPLICATION->AddHeadScript("/h/js/jquery-ui.min.js");
    $APPLICATION->AddHeadScript("/h/js/jquery.mask.min.js");
    $APPLICATION->AddHeadScript("/h/js/jquery.validate.min.js");
    $APPLICATION->AddHeadScript("/h/js/nouislider.js");
    $APPLICATION->AddHeadScript("/h/js/readmore.min.js");
    $APPLICATION->AddHeadScript("/h/js/classList.min.js");
    $APPLICATION->AddHeadScript("/h/js/jquery.expander.min.js");
    $APPLICATION->AddHeadScript("/h/js/ResizeSensor.js");
    $APPLICATION->AddHeadScript("/h/js/main.js");
    $APPLICATION->AddHeadScript("/h/js/docs.js");
    $APPLICATION->AddHeadScript("/h/js/learn.js");
    $APPLICATION->AddHeadScript("/h/js/right-sidebox-forms.js");
    $APPLICATION->AddHeadScript("/h/js/jquery.mCustomScrollbar.concat.min.js");
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . "/js/vendor/parsley.min.js");
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . "/js/lib.js");
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . "/js/main.js");
    $APPLICATION->AddHeadScript("/h/js/script_dev.js");
    $APPLICATION->SetAdditionalCSS("/h/css/style_dev.css");
    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . "/less/main-page.css");
    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . "/less/topmenu.css");
    $APPLICATION->SetAdditionalCSS("/h/css/price-list.css");
    $APPLICATION->AddHeadScript("/h/js/script_dev.js");
    $APPLICATION->AddHeadScript('//cdnjs.cloudflare.com/ajax/libs/jquery.sticky/1.0.4/jquery.sticky.min.js', true);
    $APPLICATION->AddHeadScript('//cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.4/js/standalone/selectize.min.js', true);
    $APPLICATION->SetAdditionalCSS("//cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.4/css/selectize.min.css", true);


    $authorized = $USER->IsAuthorized();

    foreach ($_GET as $key => $param) {
        if (strpos($key, "PAGEN_") === 0) {
            ?>
            <link rel="canonical" href="//<?= $_SERVER['SERVER_NAME'] . $APPLICATION->GetCurDir() ?>">
        <?
        }
    }
    ?>

    <? $APPLICATION->ShowHead(); ?>
</head>
<body class="<? $APPLICATION->ShowProperty("body_class") ?>"><? $APPLICATION->ShowPanel() ?>

<div id="search" class="header-search-overlay">
    <? $APPLICATION->IncludeComponent("hogart.lk:site.search.form", "", Array(
            "PAGE" => "#SITE_DIR#search/index.php"
        )
    ); ?>
    <a class="close-overlay" href="javascript:void(0)" onclick="toggleSearch(this)" title="Закрыть">
        <i class="fa fa-close" aria-hidden="true"></i>
    </a>
</div>

<div class="perspective">
    <a href="#" title="" class="hamburger-mobile__close">
        <i class="close-mobile-menu fa fa-close"></i>
    </a>
    <div class="hamburger-mobile__content">
        <div class="hamburger-mobile">
            <div class="hamburger-mobile__scroll">
                <ul class="main-navigation main-navigation--hamburger panel-group"  id="accordion-hamburger" role="tablist" aria-multiselectable="true">
                    <li class="panel panel-default">
                        <a class="main-navigation__link" data-toggle="collapse" data-parent="#accordion-hamburger" href="#hamburger-about-company" aria-expanded="false" title="Хогарт">
                            <div class="image">
                                <img src="/images/navigation-1.svg" alt="" title="" />
                            </div>
                            <span>Хогарт</span>
                        </a>
                        <ul id="hamburger-about-company" class="navigation-sub-menu collapse panel-collapse">
                            <li class="catalog-mobile__column">
                                <a href="/company/" title="Вентиляция">О компании</a>
                            </li>
                            <li>
                                <a href="/stock/" title="Акции">Акции</a>
                            </li>
                            <li>
                                <a href="/company/news/" title="Новости">Новости</a>
                            </li>
                            <li>
                                <a href="/company/comments/" title="Отзывы">Отзывы</a>
                            </li>
                            <li>
                                <a href="/contacts/tsentralnyy-ofis-khogart-v-moskve-sklad-i-servisnaya-sluzhba/" title="Контакты">Контакты</a>
                            </li>
                            <li>
                                <a href="/company/jobs/" title="Вакансии">Вакансии</a>
                            </li>
                        </ul>
                    </li>

                    <li class="panel panel-default">
                        <a role="tab" class="main-navigation__link" data-toggle="collapse" data-parent="#accordion-hamburger" href="#hamburger-product" aria-expanded="true" title="Продукция">
                            <div class="image">
                                <img src="/images/navigation-3.svg" alt="" title="">
                            </div>
                            <span>Продукция</span>
                        </a>
                        <ul id="hamburger-product" class="navigation-sub-menu catalog-mobile--main panel-collapse collapse in" "="" aria-expanded="true" style="">

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
                    <li class="panel panel-default">
                        <a class="main-navigation__link" data-toggle="collapse" data-parent="#accordion-hamburger" href="#hamburger-teaching" aria-expanded="false" title="Обучение">
                            <div class="image">
                                <img src="/images/open-book.svg" alt="" title="" />
                            </div>
                            <span>Обучение</span>
                        </a>
                        <ul id="hamburger-teaching" class="navigation-sub-menu catalog-mobile--main panel-collapse collapse" "="" aria-expanded="false" style="">
                            <li class="catalog-mobile__column">
                                <a href="/learn/" title="Семинары">Семинары</a>
                            </li>
                            <li class="catalog-mobile__column">
                                <a href="/learn/archive-seminarov/" title="Архив семинаров">Архив семинаров</a>
                            </li>
                            <li class="catalog-mobile__column">
                                <a href="/helpful-information/" title="Полезная информация">Полезная информация</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="/services/" title="Сервис">
                            <div class="image">
                                <img src="/images/repairing-service.svg" alt="" title="" />
                            </div>
                            <span>Сервис</span>
                        </a>
                    </li>
                    <?php if ($authorized) {?>
                        <li class="panel panel-default">
                            <a class="main-navigation__link" data-toggle="collapse" data-parent="#accordion-hamburger" href="#gamburger-lk" aria-expanded="false" title="Личный кабинет">
                                <div class="image">
                                    <img src="/images/navigation-6.svg" alt="" title="" />
                                </div>
                                <span>Личный кабинет</span>
                            </a>
                            <ul id="gamburger-lk" class="navigation-sub-menu collapse panel-collapse">
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
        </div>
    </div>
    <div class="perspective-layout">
        <div class="b-container">
            <div class="sub-header__line">
                <div class="city-selector">
                    <select name="city" id="select-city"></select>
                </div>
                <a class="sub-phone unstyled" href="" title="Телефон"></a>
                <a class="sub-email unstyled" href="" title=""></a>
                <a class="sub-contact-url unstyled" href="" title="Склады и офисы">
                    <i class="fa fa-map-marker" aria-hidden="true"></i> Склады и офисы
                </a>
            </div>
            <header class="b-header">

                <a class="b-header__logo" href="/" title="">
                    <img src="/images/m_logo.svg" alt="" title="" />
                </a>
                <div class="b-header__panel">
                    <div class="b-header__main">

                        <?
                        $APPLICATION->IncludeComponent("bitrix:menu", "top_menu", Array(
                                "ROOT_MENU_TYPE" => "top",
                                "MAX_LEVEL" => "2",
                                "CHILD_MENU_TYPE" => "left",
                                "USE_EXT" => "Y",
                                "DELAY" => "N",
                                "ALLOW_MULTI_SELECT" => "Y",
                                "MENU_CACHE_TYPE" => "N",
                                "MENU_CACHE_TIME" => "3600",
                                "MENU_CACHE_USE_GROUPS" => "Y",
                                "MENU_CACHE_GET_VARS" => "",
                                "CLASS" => "b-header__list",
                                "ITEM_CLASS" => "b-header__item",
                                "LINK_CLASS" => "b-header__link"
                            )
                        );
                        ?>
                    </div>
                    <div class="lk-container">
                        <a class="b-header__lk" href="/account/" title="<?= ($USER ? $USER->GetLogin() : Loc::getMessage("Личный кабинет")) ?>">
                            <i class="fa fa-user-circle-o" aria-hidden="true"></i>
                        </a>
                        <? if ($USER->IsAuthorized()): ?>
                            <ul class="profile-url authorized">
                                <? $APPLICATION->IncludeComponent("hogart.lk:account.menu", "", []) ?>
                                <li class="item ">
                                    <a class="b-header__link" href="?logout=yes"><i class="fa fa-sign-out" aria-hidden="true"></i> Выход</a>
                                </li>
                            </ul>
                        <? endif; ?>
                    </div>

                    <a class="b-header__search" href="javascript:void(0)" onclick="toggleSearch(this)" title="Поиск">
                        <i class="fa fa-search" aria-hidden="true"></i>
                    </a>
                    <? if ($authorized) {?>
                        <? $APPLICATION->IncludeComponent("hogart.lk:account.cart.add", "mobile", [
                            'CART_URL' => '/account/cart/'
                        ]); ?>
                    <?}?>
                    <div class="header-mobile">
                        <a class="header-mobile__menu color-black" href="javascript:void(0)" title="Мобильное меню">
                            <i class="fa fa-bars" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
            </header>
            <div class="container-fluid main-container">
