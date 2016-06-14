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
    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . "/bootstrap.css");
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
    
    <? ob_start(); ?>
    <header class="header-cnt">
        <div class="inner">
            <? $APPLICATION->IncludeComponent("bitrix:search.form", "header", Array(
                    "USE_SUGGEST" => "N",
                    "PAGE" => "#SITE_DIR#search/index.php"
                )
            ); ?>
            <? if (LANGUAGE_ID != 'en'): ?>
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
            <? endif; ?>
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
                <? $switcher = $APPLICATION->GetLangSwitcherArray(); ?>
                <? if (count($switcher) > 1): ?>
                    <div class="lang-switcher">
                        <? foreach ($switcher as $lang): ?>
                            <? if ($lang["LANGUAGE_ID"] == LANGUAGE_ID): ?>
                                <? continue; ?>
                            <? endif; ?>
                            <a class="switcher-<?= $lang["LANGUAGE_ID"] ?>" style="display: inline-block;line-height: 55px;color: white;text-decoration: none;" href="<?= $lang["DIR"] ?>"><?= $lang["LANGUAGE_ID"] ?></a>
                        <? endforeach; ?>
                    </div>
                <? endif; ?>
            </nav>
        </div>

    </header>
    <? $header = ob_get_clean(); ?>
    <? if($APPLICATION->GetCurDir() == SITE_DIR): ?>
    <?= $header ?>
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
                            <? if($APPLICATION->GetCurDir() == SITE_DIR) {
                                $date = new DateTime();
                                date_sub($date, date_interval_create_from_date_string('2 month'));
                                $APPLICATION->IncludeComponent("kontora:element.list", "main_news", array(
                                    'IBLOCK_ID' => $newsIBlockId,
                                    'FILTER' => array(
                                        "PROPERTY_tag" => array($propertyTagValues['450e18f7257ca2e9d1202d8f58eb6ae8'], $propertyTagValues['19b9ef6f18390872303b696b849ee374']),
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
                            <?$APPLICATION->IncludeComponent("bitrix:main.include","",Array(
                                "AREA_FILE_SHOW" => "sect",
                                "AREA_FILE_SUFFIX" => "inc_main_sidebar",
                                "AREA_FILE_RECURSIVE" => "Y",
                                "EDIT_TEMPLATE" => "standard.php"
                            ));?>
                        </div>
                        <div class="scroll-to-top"><?= GetMessage("Наверх") ?></div>
                    </div>
                    <? if($APPLICATION->GetCurDir() == SITE_DIR) {
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
                <li><a href="<?= SITE_DIR ?>contacts/tsentralnyy-ofis-khogart-v-moskve-sklad-i-servisnaya-sluzhba/"><?= GetMessage("Склады и офисы") ?></a>
                </li>
            </ul>
        </div>
    <div class="main-container">
        <div class="container-inner">
            <? if($APPLICATION->GetCurDir() != SITE_DIR) {
                $APPLICATION->IncludeFile("/local/include/breadcrumb.php");
            } ?>
    <? else: ?>
    <div class="row">
        <div class="col-md-1" style="padding-right: 0">
            <a style="display: inline-block; margin: 10px 0 0 10px;" href="<?= SITE_DIR ?>">
                <img style="width: 100%" src="/images/<?=LANGUAGE_ID?>-logo-black.svg" class="logo" alt="Hogart"/>
            </a>
        </div>
        <div class="col-md-11">
            <div class="row">
                <div class="col-md-12">
                    <?= $header ?>
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
                    <div class="main-container">
                        <div class="container-inner">
                            <? if($APPLICATION->GetCurDir() != SITE_DIR) {
                                $APPLICATION->IncludeFile("/local/include/breadcrumb.php");
                            } ?>
    <? endif; ?>
