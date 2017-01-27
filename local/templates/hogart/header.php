<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
use \Bitrix\Main\Localization\Loc;

Loc::loadLanguageFile(__FILE__);
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
    $APPLICATION->AddHeadString("<link rel=\"stylesheet\" href=\"https://maxcdn.bootstrapcdn.com/font-awesome/4.6.2/css/font-awesome.min.css\">");
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
    $APPLICATION->AddHeadScript("/h/js/retina.min.js");
    $APPLICATION->AddHeadScript("/h/js/jquery.mCustomScrollbar.concat.min.js");
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . "/js/vendor/parsley.min.js");
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . "/js/lib.js");
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . "/js/main.js");
    $APPLICATION->AddHeadScript("/h/js/script_dev.js");
    $APPLICATION->SetAdditionalCSS("/h/css/style_dev.css");


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
<? if ($APPLICATION->GetCurDir() == SITE_DIR): ?>
<body class="index-page no-load">
<? else: ?>
<body class="<? $APPLICATION->ShowProperty("body_class") ?>">
<? endif; ?>
<? $APPLICATION->ShowPanel() ?>

<? if ($APPLICATION->GetCurDir() == SITE_DIR): ?>
<div class="wrapper">
    <? include __DIR__ . "/header_line.php" ?>
    <div class="blur-main">
        <aside class="main-sidebar js-fp">
            <div class="wrap js-fp">
                <div class="present-cnt js-fh js-fp">
                    <a style="display: inline-block" href="<?= SITE_DIR ?>">
                        <? if (LANGUAGE_ID == 'en'): ?>
                            <img src="/images/en-logo.png" class="logo" alt="Hogart"/>
                        <? else: ?>
                            <img src="/images/logo.png" class="logo" alt="Hogart"/>
                        <? endif; ?>
                    </a>

                    <div class="js-fh js-fhi">
                        <?
                        $newsIBlockId = (LANGUAGE_ID == 'en' ? '28' : '3');
                        $propertyTagValuesRes = CIBlockProperty::GetPropertyEnum(CIBlockProperty::GetPropertyArray('tag', $newsIBlockId)['ORIG_ID']);
                        $propertyTagValues = [];
                        while (($propertyTagValue = $propertyTagValuesRes->GetNext())) {
                            $propertyTagValues[$propertyTagValue["XML_ID"]] = $propertyTagValue["ID"];
                        }
                        ?>
                        <div class="content">
                            <? if ($APPLICATION->GetCurDir() == SITE_DIR) {
                                $date = new DateTime();
                                date_sub($date, date_interval_create_from_date_string('2 month'));
                                $APPLICATION->IncludeComponent("kontora:element.list", "main_news", array(
                                    'IBLOCK_ID' => $newsIBlockId,
                                    'FILTER' => array(
                                        "PROPERTY_tag" => array($propertyTagValues['450e18f7257ca2e9d1202d8f58eb6ae8'], $propertyTagValues['19b9ef6f18390872303b696b849ee374']),
                                        ">=DATE_ACTIVE_FROM" => date_format($date, 'd-m-Y') . " 00:00:00"
                                    ),
                                    "CHECK_PERMISSIONS" => "Y",
                                    "CACHE_TYPE" => "A",
                                    "CACHE_TIME" => "0",
                                    "PROPS" => "Y",
                                    'ORDER' => array('property_priority' => 'asc,nulls', 'active_from' => 'desc'),
                                    'ELEMENT_COUNT' => 100,
                                ));
                            } ?>
                            <? $APPLICATION->IncludeComponent("bitrix:main.include", "", Array(
                                "AREA_FILE_SHOW" => "sect",
                                "AREA_FILE_SUFFIX" => "inc_main_sidebar",
                                "AREA_FILE_RECURSIVE" => "Y",
                                "EDIT_TEMPLATE" => "standard.php"
                            )); ?>
                        </div>
                        <div class="scroll-to-top"><?= GetMessage("Наверх") ?></div>
                    </div>
                    <? if ($APPLICATION->GetCurDir() == SITE_DIR) {
                        $APPLICATION->IncludeComponent("kontora:element.list", "main_calendar", array(
                            'IBLOCK_ID' => $newsIBlockId,
                            'FILTER' => array('PROPERTY_tag' => array($propertyTagValues['160c3efcdbbba1bc7128cb336546694e'], $propertyTagValues['0e6085ec84e14cae3d60582f6107641b'])),
                        ));
                    } ?>
                </div>
            </div>
        </aside>
    </div>
    <div class="credits">
        <p class="address">
            <? $APPLICATION->IncludeComponent("bitrix:main.include", "", Array(
                    "AREA_FILE_SHOW" => "sect",
                    "AREA_FILE_SUFFIX" => "inc_footer",
                    "AREA_FILE_RECURSIVE" => "Y",
                    "EDIT_TEMPLATE" => "standard.php"
                )
            ); ?>
        </p>
        <a href="#" class="p_logo"></a>
        <ul class="main-foot-menu">
            <li>
                <a href="<?= SITE_DIR ?>contacts/tsentralnyy-ofis-khogart-v-moskve-sklad-i-servisnaya-sluzhba/"><?= GetMessage("Склады и офисы") ?></a>
            </li>
        </ul>
    </div>
    <div class="main-container">
        <div class="container-inner">
            <ul class="main-navigation"  id="accordion-main" role="tablist" aria-multiselectable="true">
                <li>
                    <a class="main-navigation__link" role="tab" data-toggle="collapse" data-parent="#accordion-main" href="#menu-about-company" aria-expanded="false" aria-controls="amenities" title="О компании">
                        <div class="image">
                            <img src="/images/navigation-1.svg" alt="" title="" />
                        </div>
                        <span>О компании</span>
                    </a>
                    <ul id="menu-about-company" role="tabpanel" class="navigation-sub-menu collapse panel-collapse">
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
                    <a role="tab" class="main-navigation__link" data-toggle="collapse" data-parent="#accordion-main" href="#menu-product" aria-expanded="false" aria-controls="amenities" title="Продукция">
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
                        ),
                        $component,
                        array("HIDE_ICONS" => "Y")
                    );
                    ?>
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
            <footer class="inner footer-mobile">
                <div class="credits-mobile">
                    <a href="#" class="m_logo">
                        <img src="/images/m_logo.svg" alt="" title="" />
                    </a>
                    <div class="footer-mobile__right">
                        <a href="#" title="" class="help">
                            <img src="/images/help.svg" alt="" title="" />
                        </a>
                        <span class="address">© 2014, ООО «Хогарт»</span>
                    </div>
                </div>
            </footer>
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