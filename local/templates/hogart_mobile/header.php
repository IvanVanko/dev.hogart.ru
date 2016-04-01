<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<!DOCTYPE html>
<?
global $APPLICATION;
global $USER;
?>
<html>
<head>
    <meta charset="utf-8">

    <title></title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="<?=MOBILE_STATIC_PATH?>css/all.css" rel="stylesheet" media="all">
    <script src="http://maps.googleapis.com/maps/api/js"></script>
    <script src="<?=MOBILE_STATIC_PATH?>js/jquery.min.js"></script>
    <script src="<?=MOBILE_STATIC_PATH?>js/owl.carousel.min.js"></script>
    <script src="<?=MOBILE_STATIC_PATH?>js/jquery.touchSwipe.min.js"></script>
    <script src="<?=MOBILE_STATIC_PATH?>js/jquery.inputmask.bundle.js"></script>
    <script src="<?=MOBILE_STATIC_PATH?>js/markerwithlabel.js"></script>
    <script src="<?=MOBILE_STATIC_PATH?>js/jquery-ui.min.js"></script>
    <script src="<?=MOBILE_STATIC_PATH?>js/jquery.ui.touch-punch.dk.js"></script>
    <script src="<?=MOBILE_STATIC_PATH?>js/jquery.magnific-popup.min.js"></script>
    <script src="<?=MOBILE_STATIC_PATH?>js/jquery.validate.min.js"></script>
    <script src="<?=MOBILE_STATIC_PATH?>js/main.js"></script>
    <?/*<script src="<?=MOBILE_STATIC_PATH?>js/main.js"></script>*/?>

    <!-- <script data-main="js/init" src="js/require.js"></script> -->
</head>
<body>
<?$BX_MENU_CUSTOM->AddItem()?>
<div class="wrap <?=$APPLICATION->ShowProperty("PAGE_CLASS")?>">
    <?$APPLICATION->IncludeComponent("bitrix:menu",".default",Array(
            "ROOT_MENU_TYPE" => "mobile_top",
            "MAX_LEVEL" => "1",
            "CHILD_MENU_TYPE" => "",
            "USE_EXT" => "N",
            "DELAY" => "Y",
            "ALLOW_MULTI_SELECT" => "Y",
            "MENU_CACHE_TYPE" => "N",
            "MENU_CACHE_TIME" => "3600",
            "MENU_CACHE_USE_GROUPS" => "Y",
            "MENU_CACHE_GET_VARS" => ""
        )
    );?>
    <aside class="nav-selecter">
        <ul>
            <li class="menu_1"><a href="#main_slide" class="slide-trigger active"></a></li>
            <li class="menu_2"><a href="#map_slide" class="slide-trigger" data-effect="resizeMap"></a></li>
            <li class="menu_3"><a href="#" class=""></a></li>
            <li class="menu_4"><a href="#message_slide" class="slide-trigger"></a></li>
            <li class="menu_5"><a href="#search" class="slide-trigger"></a></li>
            <li class="menu_6"><a href="#profile_slide" class="slide-trigger"> <span class="message-count">(2)</span>  </a></li>
            <li class="menu_7"><a href="#second_menu_slide" class="slide-trigger"></a></li>
        </ul>
    </aside>
    <section class="slide " id="main_slide">
        <aside class="breadcrumbs">
            <div class="prev-pages-wrap">
                <a href="#" class="page">Главная</a>
            </div>

            <span class="page active">Семинары</span>
        </aside>