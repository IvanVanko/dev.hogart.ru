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

    $APPLICATION->AddHeadString("<link href='http://fonts.googleapis.com/css?family=Roboto+Condensed:300,400,700&subset=latin,cyrillic' rel='stylesheet' type='text/css'>");
    $APPLICATION->AddHeadString("<link href='http://fonts.googleapis.com/css?family=Roboto:900,700,500,400,300italic,300&subset=latin,cyrillic' rel='stylesheet' type='text/css'>");
    $APPLICATION->AddHeadString("<link rel=\"stylesheet\" href=\"https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css\">");
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
    $APPLICATION->AddHeadScript("/local/assets/slick/slick/slick.min.js");
    $APPLICATION->AddHeadScript("/local/assets/slick/slick/slick.js");

    $APPLICATION->SetAdditionalCSS("/h/css/houdini.min.css");
    $APPLICATION->AddHeadScript("/h/js/houdini.min.js");

    $APPLICATION->AddHeadScript("/h/js/jquery.reject.js");
    $APPLICATION->SetAdditionalCSS("/h/css/jquery.reject.css");
    $APPLICATION->AddHeadString('<script src="http://maps.google.com/maps/api/js?sensor=true"></script>');
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
//    $APPLICATION->AddHeadScript("/h/js/retina.min.js");
    $APPLICATION->AddHeadScript("/h/js/jquery.mCustomScrollbar.concat.min.js");
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . "/js/vendor/parsley.min.js");
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . "/js/lib.js");
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . "/js/main.js");
    $APPLICATION->AddHeadScript("/h/js/script_dev.js");
    $APPLICATION->SetAdditionalCSS("/h/css/style_dev.css");
    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . "/less/main-page.css");
    $APPLICATION->SetAdditionalCSS("/h/css/price-list.css");
    $APPLICATION->AddHeadScript("/h/js/script_dev.js");
    $APPLICATION->AddHeadScript('//cdnjs.cloudflare.com/ajax/libs/jquery.sticky/1.0.4/jquery.sticky.min.js', true);


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
    <form action="">
        <div class="search-container">
            <div class="b-catalog-main__search">
                <input type="text" id="" name="" class="b-catalog-main__input" placeholder="Артикул или наименование" />
                <a class="b-catalog-main__icon" href="#" title="">
                    <i class="fa fa-search" aria-hidden="true"></i>
                </a>
            </div>
            <button class="btn btn-primary" type="submit" value="Искать">Искать</button>
        </div>
    </form>
    <a class="close-overlay" href="javascript:void(0)" onclick="toggleSearch(this)" title="Закрыть">
        <i class="fa fa-close" aria-hidden="true"></i>
    </a>
</div>

<div class="perspective">
    <a href="#" title="" class="hamburger-mobile__close"></a>
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
                        <a class="main-navigation__link" data-toggle="collapse" data-parent="#accordion-hamburger" href="#hamburger-solution" aria-expanded="false" title="Комплексные решения">
                            <div class="image">
                                <img src="/images/puzzle.svg" alt="" title="" />
                            </div>
                            <span>Комплексные решения</span>
                        </a>
                        <ul id="hamburger-solution" class="navigation-sub-menu catalog-mobile--main panel-collapse collapse" "="" aria-expanded="false" style="">
                            <li class="catalog-mobile__column">
                                <a href="/integrated-solutions/all_projects.php" title="Реализованные проекты">Реализованные проекты</a>
                            </li>
                        </ul>
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
        <? if ($APPLICATION->GetCurDir() == SITE_DIR): ?>
            <div class="b-container">
                <div class="sub-header__line">
                    <a class="unstyled" href="tel:74957034114" title="Телефон">+7 (495) 703-41-14</a>
                    <a class="unstyled" href="mailto:info@hogart.ru" title="">info@hogart.ru</a>
                    <a class="unstyled" href="javascript:void(0)" title="Склады и офисы">
                        <i class="fa fa-map-marker" aria-hidden="true"></i> Склады и офисы
                    </a>
                </div>
                <header class="b-header">

                    <a class="b-header__logo" href="" title="">
                        <img src="/images/m_logo.svg" alt="" title="" />
                    </a>
                    <div class="b-header__panel">
                        <div class="b-header__main">
                            <ul class="b-header__list">
                                <li class="b-header__item">
                                    <a class="b-header__link" href="/company/" title="О компании">О компании</a>
                                </li>
                                <li class="b-header__item">
                                    <a class="b-header__link" href="/company/news/" title="Новости">Новости</a>
                                </li>
                                <li class="b-header__item">
                                    <a class="b-header__link" href="/stock/" title="Акции">Акции</a>
                                </li>
                                <li class="b-header__item">
                                    <a class="b-header__link" href="javascript:void(0)" title="Продукция">Продукция</a>
                                </li>
                                <li class="b-header__item">
                                    <a class="b-header__link" href="/learn/" title="Обучение">Обучение</a>
                                </li>
                                <li class="b-header__item">
                                    <a class="b-header__link" href="/services/" title="Сервис">Сервис</a>
                                </li>
                            </ul>
                        </div>
                        <a class="b-header__lk" href="#" title="Личный кабинет">
                            <i class="fa fa-user-circle-o" aria-hidden="true"></i>
                        </a>
                        <a class="b-header__search" href="javascript:void(0)" onclick="toggleSearch(this)" title="Поиск">
                            <i class="fa fa-search" aria-hidden="true"></i>
                        </a>
                    </div>
                    <div class="header-mobile">
                        <a class="header-mobile__menu" href="#" title="">
                            <img src="/images/header-menu.svg" />
                        </a>
                        <? if ($authorized) {?>
                            <? $APPLICATION->IncludeComponent("hogart.lk:account.cart.add", "mobile", [
                                'CART_URL' => '/account/cart/'
                            ]); ?>
                        <?}?>
                    </div>  
                </header>
                <div class="b-wrapper">
                    <main class="b-main">
                        <div class="b-slider">
                            <div class="b-slider__content b-slider__content--main js-slider-main-page">
                                <div class="b-slider__item b-slider__item--main">
                                    <img src="/images/banner-hogart-1.jpg" alt="" title="" />
                                </div>
                                <div class="b-slider__item b-slider__item--main">
                                    <img src="/images/banner_hogart3.jpg" alt="" title="" />
                                </div>
                                <div class="b-slider__item b-slider__item--main">
                                    <img src="/images/banner_hogart3.jpg" alt="" title="" />
                                </div>
                            </div>
                        </div>
                        <div class="b-catalog-main">
                            <div class="b-catalog-main__title">
                                <a href="/catalog/" class="b-title-link b-title-link--catalog" title="Каталог">Каталог</a>
                                <div class="b-catalog-main__buttons">
                                    <a href="/documentation/" class="b-catalog-main__button" title="Документация">Документация</a>
                                    <a href="#" class="b-catalog-main__button" title="Прайс-лист">Прайс-лист</a>
                                </div>
                            </div>
                            <ul class="b-catalog-main__list">
                                <li class="b-catalog-main__item">
                                    <a href="/catalog/#bx_cat_1381" class="b-catalog-main__link" title="Отопление">
                                        <img src="/images/heating.png" alt="" title="" />
                                        <span>Отопление</span>
                                    </a>
                                </li>
                                <li class="b-catalog-main__item">
                                    <a href="/catalog/#bx_cat_1383" class="b-catalog-main__link" title="Вентиляция">
                                        <img src="/images/ventilation.png" alt="" title="" />
                                        <span>Вентиляция</span>
                                    </a>
                                </li>
                                <li class="b-catalog-main__item">
                                    <a href="/catalog/#bx_cat_1382" class="b-catalog-main__link" title="Сантехника">
                                        <img src="/images/sanitary.png" alt="" title="" />
                                        <span>Сантехника</span>
                                    </a>
                                </li>
                                <li class="b-catalog-main__item">
                                    <a href="/catalog/#bx_cat_1380" class="b-catalog-main__link" title="Канализация">
                                        <img src="/images/sewerage.png" alt="" title="" />
                                        <span>Канализация</span>
                                    </a>
                                </li>
                                <li class="b-catalog-main__item">
                                    <a href="/catalog/#bx_cat_1379" class="b-catalog-main__link" title="Плитка">
                                        <img src="/images/tile.png" alt="" title="" />
                                        <span>Плитка</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="b-brands-main">
                            <a href="/brands/" class="b-title-link" title="Бренды">Бренды</a>
                            <ul class="b-brands-main__list">
                                <li class="b-brands-main__item">
                                    <a href="#" class="b-brands-main__link" title="">
                                        <img src="/images/brand-1.jpg" alt="" title="" />
                                    </a>
                                </li>
                                 <li class="b-brands-main__item">
                                    <a href="#" class="b-brands-main__link" title="">
                                        <img src="/images/brand-2.jpg" alt="" title="" />
                                    </a>
                                </li>
                                 <li class="b-brands-main__item">
                                    <a href="#" class="b-brands-main__link" title="">
                                        <img src="/images/brand-1.jpg" alt="" title="" />
                                    </a>
                                </li>
                                 <li class="b-brands-main__item">
                                    <a href="#" class="b-brands-main__link" title="">
                                        <img src="/images/brand-2.jpg" alt="" title="" />
                                    </a>
                                </li>
                                 <li class="b-brands-main__item">
                                    <a href="#" class="b-brands-main__link" title="">
                                        <img src="/images/brand-1.jpg" alt="" title="" />
                                    </a>
                                </li>
                                 <li class="b-brands-main__item">
                                    <a href="#" class="b-brands-main__link" title="">
                                        <img src="/images/brand-2.jpg" alt="" title="" />
                                    </a>
                                </li>
                                 <li class="b-brands-main__item">
                                    <a href="#" class="b-brands-main__link" title="">
                                        <img src="/images/brand-1.jpg" alt="" title="" />
                                    </a>
                                </li>
                                <li class="b-brands-main__item">
                                    <a href="#" class="b-brands-main__link" title="Все бренды">Все бренды</a>
                                </li>
                            </ul>
                        </div>
                        <div class="b-slider">
                            <a href="/integrated-solutions/all_projects.php" class="b-title-link" title="Референс-объекты">Референс-объекты</a>
                            <div class="b-slider__content js-slider-media-main-page">
                                <div class="b-slider__item">
                                    <img src="/images/slider.jpg" alt="" title="" />
                                </div>
                                <div class="b-slider__item">
                                    <img src="/images/slider.jpg" alt="" title="" />
                                </div>
                                <div class="b-slider__item">
                                    <img src="/images/slider.jpg" alt="" title="" />
                                </div>
                                <div class="b-slider__item">
                                    <img src="/images/slider.jpg" alt="" title="" />
                                </div>
                                <div class="b-slider__item">
                                    <img src="/images/slider.jpg" alt="" title="" />
                                </div>
                            </div>
                        </div>
                        <div class="b-history">
                            <a href="/company/" class="b-title-link" title="О компании">О компании</a>
                            <p>Компания Хогарт, основанная в&nbsp;1996 году на&nbsp;сегодняшний день является одним из&nbsp;крупнейших поставщиков инженерных систем в&nbsp;области отопления, вентиляции и&nbsp;сантехники.</p>
                            <p>Компания имеет собственный офисно-складской комплекс, два салона розничных продаж в&nbsp;Москве и&nbsp;офисно-складской комплекс в&nbsp;Санкт-Петербурге.</p>
                            <p>За&nbsp;20&nbsp;лет работы нам доверили поставку оборудования более 50&nbsp;000 различных компаний. Компания &laquo;Хогарт&raquo; является членом апик и&nbsp;авок, активно содействуя развитию отрасли, вместе с&nbsp;другими ведущими игроками климатической отрасли.</p>
                        </div>
                    </main>
                    <aside class="b-aside">
                        <div class="b-contact-main">
                            <ul class="b-contact-main__list">
                                <li class="b-contact-main__item">
                                    <a href="#" title="">
                                        <i class="icon-baloon"></i>
                                        <span>Москва</span>
                                    </a>
                                </li>
                                <li class="b-contact-main__item b-contact-main__item--no-margin">
                                    <a href="tel:84957034114" title="">+7 (495) 703-41-14</a>
                                </li>
                                <li class="b-contact-main__item">
                                    <a href="mailto:vlad@htmlbook.ru" title="">info@hogart.ru</a>
                                </li>
                                <li class="b-contact-main__item">
                                    <a href="#" title="Склады и офисы">Склады и офисы</a>
                                </li>
                            </ul>
                        </div>
                        <div class="b-news-main">
                            <a href="/news/" class="b-title-link" title="Новости">Новости</a>
                            <ul class="b-news-main__list">
                                <li class="b-news-main__item">
                                    <a href="#" title="" class="b-news-main__link">
                                        <span>22.02.2017</span>
                                        <h3>Поздравляем с&nbsp;23&nbsp;февраля!</h3>
                                        <p>Поздравляем дорогих мужчин с&nbsp;Днем защитника Отечества</p>
                                        <p>Желаем огромного благополучия, материального достатка, реализации в&nbsp;любимом деле, семейного тепла и&nbsp;успехов во&nbsp;всех начинаниях.</p>
                                    </a>
                                </li>
                                <li class="b-news-main__item">
                                    <a href="#" title="" class="b-news-main__link">
                                        <span>22.02.2017</span>
                                        <h3>Поздравляем с&nbsp;23&nbsp;февраля!</h3>
                                        <p>Поздравляем дорогих мужчин с&nbsp;Днем защитника Отечества</p>
                                        <p>Желаем огромного благополучия, материального достатка, реализации в&nbsp;любимом деле, семейного тепла и&nbsp;успехов во&nbsp;всех начинаниях.</p>
                                    </a>
                                </li>
                                <li class="b-news-main__item">
                                    <a href="#" title="" class="b-news-main__link">
                                        <span>22.02.2017</span>
                                        <h3>Поздравляем с&nbsp;23&nbsp;февраля!</h3>
                                        <p>Поздравляем дорогих мужчин с&nbsp;Днем защитника Отечества</p>
                                        <p>Желаем огромного благополучия, материального достатка, реализации в&nbsp;любимом деле, семейного тепла и&nbsp;успехов во&nbsp;всех начинаниях.</p>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </aside>
                </div>
                <ul class="main-navigation"  id="accordion-main" role="tablist" aria-multiselectable="true">
                    <li class="main-navigation__item">
                        <a class="main-navigation__link" data-toggle="collapse" data-parent="#accordion-main" href="#menu-about-company" aria-expanded="false" title="Хогарт">
                            <div class="image">
                                <img src="/images/navigation-1.svg" alt="" title="" />
                            </div>
                            <span>Хогарт</span>
                        </a>
                        <ul id="menu-about-company" class="navigation-sub-menu collapse panel-collapse">
                            <li class="catalog-mobile__column">
                                <a href="/company/" title="Вентиляция">О компании</a>
                            </li>
                            <li class="catalog-mobile__column">
                                <a title="Контакты" href="/contacts/tsentralnyy-ofis-khogart-v-moskve-sklad-i-servisnaya-sluzhba/">Контакты</a>
                            </li>
                        </ul>
                    </li>

                    <li class="main-navigation__item">
                        <a role="tab" class="main-navigation__link" data-toggle="collapse" data-parent="#accordion-main" href="#menu-product" aria-expanded="true" aria-controls="amenities" title="Продукция">
                            <div class="image">
                                <img src="/images/navigation-3.svg" alt="" title="" />
                            </div>
                            <span>Продукция</span>
                        </a>
                        <?
                        $APPLICATION->IncludeComponent(
                            "hogart:catalog.section.list",
                            "mobile_main_page",
                            array(
                                "IBLOCK_TYPE" => "catalog",
                                "IBLOCK_ID" => CATALOG_IBLOCK_ID,
                                "CACHE_TYPE" => "A",
                                "CACHE_TIME" => "36000000",
                                "TOP_DEPTH" => "1",
                            )
                        );
                        ?>
                    </li>
                    <li class="main-navigation__item">
                        <a href="/brands/" title="Бренды">
                            <div class="image">
                                <img src="/images/brands.jpg" alt="" title="" />
                            </div>
                            <span>Бренды</span>
                        </a>
                    </li>
                    <li class="main-navigation__item">
                        <a href="/documentation/" title="Документация">
                            <div class="image">
                                <img src="/images/navigation-5.svg" alt="" title="" />
                            </div>
                            <span>Документация</span>
                        </a>
                    </li>
                    <?php if ($authorized) {?>
                        <li class="main-navigation__item">
                            <a class="main-navigation__link" role="tab" data-toggle="collapse" data-parent="#accordion-main" href="#menu-lk" aria-expanded="false" aria-controls="amenities" title="Личный кабинет">
                                <div class="image">
                                    <img src="/images/navigation-6.svg" alt="" title="" />
                                </div>
                                <span>Личный кабинет</span>
                            </a>
                            <ul id="menu-lk" role="tabpanel" class="navigation-sub-menu collapse panel-collapse">
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
                        <li class="main-navigation__item">
                            <a href="/account/" title="Личный кабинет">
                                <div class="image">
                                    <img src="/images/navigation-6.svg" alt="" title="" />
                                </div>
                                <span>Личный кабинет</span>
                            </a>
                        </li>
                    <?}?>
                </ul>
                <footer class="b-footer">
                    <div class="b-footer__copyright">© 2017, ООО «Хогарт», 117041 г. Москва, ул Поляны, 52
                        <a href="" title="ОБратная связь">Обратная связь</a>
                    </div>
                    <ul class="b-social clearfix">
                        <li class="b-social__item">
                            <a class="b-social__link" href="" title="">
                                <i class="icon"></i>
                            </a>
                        </li>
                        <li class="b-social__item">
                            <a class="b-social__link" href="" title="">
                                <i class="icon"></i>
                            </a>
                        </li>
                        <li class="b-social__item">
                            <a class="b-social__link" href="" title="">
                                <i class="icon"></i>
                            </a>
                        </li>
                    </ul>
                    <div class="credits-mobile">
                        <a href="#" class="m_logo">
                            <img src="/images/m_logo.svg" alt="" title="" />
                        </a>
                        <div class="footer-mobile__right">
                            <a href="#" title="" class="help js-help-main">
                                <img src="/images/help.svg" alt="" title="" />
                            </a>
                            <div class="footer-menu-main" id="accordion-footer">
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
                                                Салон ХОГАРТ_арт в ARTPLAY
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
                            <span class="address">© 2014, ООО «Хогарт»</span>
                        </div>
                    </div>
                </footer>
            </div>
        <? else: ?>
        <div class="container-fluid" style="overflow: hidden;">
        <div id="header-block" class="row fixed-block">
            <div class="col-md-1 header-mobile__top-menu" style="padding-right: 0">
                <a style="display: inline-block; margin: 10px 0 0 10px;" href="<?= SITE_DIR ?>">
                    <img style="width: 100%" src="/images/<?= LANGUAGE_ID ?>-logo-black.svg" class="logo"
                         alt="Hogart"/>
                </a>
            </div>
            <div class="col-md-11 col-sm-12 header" style="padding-right: 0">
                <div class="row">
                    <div class="col-md-12 header__mobile">
                        <? include __DIR__ . "/header_line.php" ?>
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
                                "CLASS" => "top_menu"
                            )
                        );
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div id="main-wrapper" class="row">
            <div class="col-md-1 main-wrapper__left-menu">
                <? $APPLICATION->IncludeComponent("bitrix:main.include", "", Array(
                    "AREA_FILE_SHOW" => "sect",
                    "AREA_FILE_SUFFIX" => "inc_left",
                    "AREA_FILE_RECURSIVE" => "Y",
                    "EDIT_TEMPLATE" => "standard.php"
                )); ?>
            </div>
            <div class="col-md-11 col-sm-12 main-wrapper__content">
                <div class="main-container">
                    <div class="container-inner">
                        <? if ($APPLICATION->GetCurDir() != SITE_DIR) {
                            $APPLICATION->IncludeFile("/local/include/breadcrumb.php");
                        } ?>

<? endif; ?>