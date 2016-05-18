<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
use \Bitrix\Main\Localization\Loc;
Loc::loadLanguageFile(__FILE__);
?>
<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <meta name="mailru-verification" content="7d762af6578aa853" />
    <title><? $APPLICATION->ShowTitle("title") ?></title>
    <?

    global $USER;
    global $APPLICATION;

    $APPLICATION->AddHeadString("<link href='http://fonts.googleapis.com/css?family=Roboto+Condensed:300,400&subset=latin,cyrillic' rel='stylesheet' type='text/css'>");
    $APPLICATION->AddHeadString("<link href='http://fonts.googleapis.com/css?family=Roboto:400,300italic,300&subset=latin,cyrillic' rel='stylesheet' type='text/css'>");
    $APPLICATION->AddHeadString("<link rel=\"stylesheet\" href=\"https://maxcdn.bootstrapcdn.com/font-awesome/4.6.2/css/font-awesome.min.css\">");
    $APPLICATION->SetAdditionalCSS("/h/css/main.css");
    $APPLICATION->SetAdditionalCSS("/h/css/dop-css.css");
    $APPLICATION->SetAdditionalCSS("/h/css/bug-fix.css");
    $APPLICATION->SetAdditionalCSS("/h/css/jquery-ui.min.css");
    $APPLICATION->SetAdditionalCSS("/h/css/nouislider.min.css");
    $APPLICATION->SetAdditionalCSS("/h/css/jquery.mCustomScrollbar.css");
    $APPLICATION->SetAdditionalCSS("/h/css/houdini.min.css");

    $APPLICATION->AddHeadScript("/h/js/jquery-1.11.2.min.js");
    $APPLICATION->AddHeadScript("/h/js/jquery.reject.js");
    $APPLICATION->SetAdditionalCSS("/h/css/jquery.reject.css");
    $APPLICATION->AddHeadString('<script src="http://maps.google.com/maps/api/js?sensor=true"></script>');
    $APPLICATION->AddHeadScript("/h/js/jquery.bxslider.min.js");
    $APPLICATION->AddHeadScript("/h/js/jquery-ui.min.js");
    $APPLICATION->AddHeadScript("/h/js/jquery.mask.min.js");
    $APPLICATION->AddHeadScript("/h/js/jquery.validate.min.js");
    $APPLICATION->AddHeadScript("/h/js/nouislider.js");
    $APPLICATION->AddHeadScript("/h/js/readmore.min.js");
    $APPLICATION->AddHeadScript("/h/js/houdini.min.js");
    $APPLICATION->AddHeadScript("/h/js/classList.min.js");
    $APPLICATION->AddHeadScript("/h/js/jquery.expander.min.js");
    $APPLICATION->AddHeadScript("/h/js/ResizeSensor.js");
    $APPLICATION->AddHeadScript("/h/js/main.js");
    $APPLICATION->AddHeadScript("/h/js/docs.js");
    $APPLICATION->AddHeadScript("/h/js/learn.js");
    $APPLICATION->AddHeadScript("/h/js/right-sidebox-forms.js");
    $APPLICATION->AddHeadScript("/h/js/retina.min.js");
    $APPLICATION->AddHeadScript("/h/js/jquery.mCustomScrollbar.concat.min.js");
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/js/vendor/parsley.min.js");
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/js/lib.js");
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/js/main.js");

    foreach($_GET as $key => $param){
        if(strpos($key, "PAGEN_") === 0){?>
            <link rel="canonical" href="//<?=$_SERVER['SERVER_NAME'].$APPLICATION->GetCurDir()?>">
        <?}
    }
    ?>

    <? $APPLICATION->ShowHead(); ?>
</head>
<? if($APPLICATION->GetCurDir() == '/'): ?>
<body class="index-page no-load">
<? else: ?>
<body class="<? $APPLICATION->ShowProperty("body_class") ?> no-load">
<? endif; ?>
<? $APPLICATION->ShowPanel() ?>
<div class="wrapper">
    <header class="header-cnt js-fixed-block" data-fixed="top">
        <div class="inner">
            <? $APPLICATION->IncludeComponent("bitrix:search.form", "header", Array(
                    "USE_SUGGEST" => "N",
                    "PAGE" => "#SITE_DIR#search/index.php"
                )
            ); ?>
            <a class="profile-url<? if($USER->IsAuthorized()): ?> authorized<? else: ?> js-popup-open<? endif; ?>"
               href="<? if($USER->IsAuthorized()): ?>/profile/<? else: ?>#<? endif; ?>"
               <? if(!$USER->IsAuthorized()): ?>data-popup="#popup-login"<? endif; ?>>
                <i class="icon-profile icon-full"></i>
                <? if($USER->IsAuthorized()): ?>
                    <span class="hide-text"><?=$USER->GetFullName()?></span>
                <? else: ?>
                    <span class="hide-text"><?=Loc::getMessage("Личный кабинет")?></span>
                <? endif; ?>
            </a>
            <nav class="header-nav">
                <ul>
                    <li class="first">
                        <a href="<?=SITE_DIR?>stock/"><?=Loc::getMessage("Акции")?></a>
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
                    <li class="feedback"><a class="js-popup-open" data-popup="#popup-os" href="#"><?=Loc::getMessage('Обратная связь')?></a></li>
                </ul>
            </nav>

        </div>

    </header>
    <div class="blur-main">
        <aside class="main-sidebar js-fp">
            <div class="wrap js-fp">
                <div class="trigger-menu-cnt js-fh js-fixed-block" data-fixed="top">
                    <label for="main-menu-trigger" class="icon-menu icon-full"></label>
                    <label for="main-menu-trigger" class="menu-trigger-line"></label>
                </div>
                <div class="present-cnt js-fh js-fp">
                    <?
                        $lang = $APPLICATION->GetLang();
                    ?>
                    <a href="<?=$lang['DIR']?>">
                    <? if ($lang['LANGUAGE_ID'] == 'en'): ?>
                        <img src="/images/en-logo.png" class="logo" alt="Hogart"/>
                    <? else: ?>
                        <img src="/images/logo.png" class="logo" alt="Hogart"/>
                    <? endif; ?>
                    </a>

                    <div class="js-fh js-fhi">
                        <div class="content">
                            <? if($APPLICATION->GetCurDir() == SITE_DIR) {
                                $date = new DateTime();
                                date_sub($date, date_interval_create_from_date_string('2 month'));
                                $APPLICATION->IncludeComponent("kontora:element.list", "main_news", array(
                                    'IBLOCK_ID' => (LANGUAGE_ID == 'en' ? '28' : '3'),
                                    'FILTER' => array(
                                        "PROPERTY_tag" => array(2, 4, '0e6085ec84e14cae3d60582f6107641b'),
                                        ">=DATE_ACTIVE_FROM" => date_format($date, 'd-m-Y')." 00:00:00"
                                    ),
                                    "CHECK_PERMISSIONS" => "Y",
                                    "CACHE_TYPE" => "A",
                                    "CACHE_TIME" => "0",
                                    "PROPS" => "Y",
                                    'ORDER' => array('property_priority' => 'asc,nulls', 'active_from' => 'desc'),
                                    'ELEMENT_COUNT' => 100,
                                ));
                            } ?>
                        </div>
                        <div class="scroll-to-top">Наверх</div>
                    </div>
                    <? if($APPLICATION->GetCurDir() == SITE_DIR) {
                        $APPLICATION->IncludeComponent("kontora:element.list", "main_calendar", array(
                            'IBLOCK_ID' => (LANGUAGE_ID == 'en' ? '28' : '3'),
                            'FILTER' => array('PROPERTY_tag' => array(3, 5)),
                        ));
                    } ?>
                </div>
            </div>
        </aside>
        <? $APPLICATION->IncludeComponent("bitrix:menu", "left_menu", Array(
                "ROOT_MENU_TYPE" => "top",
                "MAX_LEVEL" => "2",
                "CHILD_MENU_TYPE" => "left",
                "USE_EXT" => "Y",
                "DELAY" => "N",
                "ALLOW_MULTI_SELECT" => "Y",
                "MENU_CACHE_TYPE" => "N",
                "MENU_CACHE_TIME" => "3600",
                "MENU_CACHE_USE_GROUPS" => "Y",
                "MENU_CACHE_GET_VARS" => ""
            )
        ); ?>
    </div>
    <? if($APPLICATION->GetCurDir() == '/'): ?>
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
                <!--                <li><a href="/helpful-information/">Статьи</a></li>-->
                <!--                <li><a href="/selection-equipment/">Подбор оборудования</a></li>-->
                <li><a href="/contacts/tsentralnyy-ofis-khogart-v-moskve-sklad-i-servisnaya-sluzhba/">Склады и офисы</a>
                </li>
            </ul>
        </div>
    <? endif ?>
    <div class="container main-container">
        <div class="container-inner">
            <? if($APPLICATION->GetCurDir() != '/' && $APPLICATION->GetCurDir() != '/en/') {
                $APPLICATION->IncludeFile("/local/include/breadcrumb.php");
            } ?>
