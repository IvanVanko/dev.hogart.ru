<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
} ?>
<div class="inner">
    <? $APPLICATION->IncludeComponent("bitrix:menu", "section_menu", Array(
            "ROOT_MENU_TYPE" => "left",
            "MAX_LEVEL" => "1",
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
    <h1><?= $arResult['NAME'] ?></h1>

    <h2><?= $arResult["PREVIEW_TEXT"] ?></h2>

    <p><?= $arResult["DETAIL_TEXT"] ?></p>

    <? if (!empty($arResult['PROPERTIES']['points']['VALUE'])): ?>
        <ul class="counter-company">
            <? foreach ($arResult['PROPERTIES']['points']['VALUE'] as $key => $value): ?>
                <li>
                    <span><?= $key + 1 ?></span>

                    <p><?= $value ?></p>
                </li>
                <? if ($key % 2 != 0): ?>
                    <li class="clearfix"></li>
                <? endif; ?>
            <? endforeach; ?>
        </ul>
    <? endif; ?>

    <? if (!empty($arResult["PROPERTIES"]["partners"]["VALUE"])): ?>
        <h2><?=GetMessage("Наши партнеры")?></h2>
        <p><?= $arResult["PROPERTIES"]["partners"]["~VALUE"]["TEXT"] ?></p>
    <? endif; ?>
    <h2><?=GetMessage("Хогарт сегодня")?></h2>
</div>

<? $APPLICATION->IncludeComponent("kontora:element.list", "hogart_today", array(
    "IBLOCK_ID" => "21",
    "PROPS" => "Y",
    'ORDER' => array('sort' => 'asc'),
)); ?>

<? if (!empty($arResult["PROPERTIES"]["honors"]["VALUE"])): ?>
    <div class="inner">
        <h2><?=GetMessage("Достижения и награды")?></h2>
        <ul class="sert-slider-cnt js-company-slider">
            <? foreach ($arResult["PROPERTIES"]["honors"]["VALUE"] as $value):
                $file = CFile::ResizeImageGet($value, array('width' => 126, 'height' => 179), BX_RESIZE_IMAGE_EXACT, true);
                $fileBig = CFile::GetPath($value);
                ?>
                <li><img src="<?= $file['src'] ?>" data-group="gallG" data-big-img="<?= $fileBig ?>"
                         class="js-popup-open-img" alt=""/></li>
            <? endforeach ?>
        </ul>
        <? if (count($arResult["PROPERTIES"]["honors"]["VALUE"]) > 6): ?>
            <div id="js-control-company" class="control">
                <span class="prev black"></span>
                <span class="next black"></span>
            </div>
        <? else: ?>
            <br/>
        <? endif; ?>
    </div>
<? endif; ?>
<div class="inner">
    <?$APPLICATION->IncludeFile(
        "/local/include/share.php",
        array(
            "TITLE" => $arResult["NAME"],
            "DESCRIPTION" => !empty($arResult["PREVIEW_TEXT"]) ? $arResult["PREVIEW_TEXT"]:$arResult["DETAIL_TEXT"],
            "LINK" => $APPLICATION->GetCurPage(),
            "IMAGE"=> $share_img_src
        )
    );?>
</div>
<aside class="sidebar js-fh js-fixed-block js-paralax-height" data-fixed="top">
    <div class="inner js-paralax-item">

        <div class="company-side-cnt padding text-center">
            <h2><?=GetMessage("Основные направления деятельности")?></h2>
            <?
            $GetCurDir = explode("/", $APPLICATION->GetCurDir());

            $GetCurDir = array_filter(
                $GetCurDir,
                function ($el) {
                    return !empty($el);
                }
            );
            $GLOBALS['myFilter'] = array("PROPERTY_show_where" => $GetCurDir);
            $APPLICATION->IncludeComponent(
                "bitrix:news.list",
                "advantages",
                Array(
                    "COMPONENT_TEMPLATE" => ".default",
                    "IBLOCK_TYPE" => "advantages",
                    "IBLOCK_ID" => (LANGUAGE_ID == 'en' ? 31 : 19),
                    "NEWS_COUNT" => "3",
                    "SORT_BY1" => "SORT",
                    "SORT_ORDER1" => "ASC",
                    "SORT_BY2" => "ACTIVE_FROM",
                    "SORT_ORDER2" => "DESC",
                    "FILTER_NAME" => "myFilter",
                    "FIELD_CODE" => array("", ""),
                    "PROPERTY_CODE" => array("link"),
                    "CHECK_DATES" => "Y",
                    "DETAIL_URL" => "",
                    "AJAX_MODE" => "N",
                    "AJAX_OPTION_JUMP" => "N",
                    "AJAX_OPTION_STYLE" => "Y",
                    "AJAX_OPTION_HISTORY" => "N",
                    "CACHE_TYPE" => "A",
                    "CACHE_TIME" => "36000000",
                    "CACHE_FILTER" => "N",
                    "CACHE_GROUPS" => "Y",
                    "PREVIEW_TRUNCATE_LEN" => "",
                    "ACTIVE_DATE_FORMAT" => "d.m.Y",
                    "SET_TITLE" => "N",
                    "SET_BROWSER_TITLE" => "N",
                    "SET_META_KEYWORDS" => "N",
                    "SET_META_DESCRIPTION" => "N",
                    "SET_STATUS_404" => "N",
                    "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
                    "ADD_SECTIONS_CHAIN" => "N",
                    "HIDE_LINK_WHEN_NO_DETAIL" => "N",
                    "PARENT_SECTION" => "",
                    "PARENT_SECTION_CODE" => "",
                    "INCLUDE_SUBSECTIONS" => "Y",
                    "DISPLAY_DATE" => "N",
                    "DISPLAY_NAME" => "Y",
                    "DISPLAY_PICTURE" => "Y",
                    "DISPLAY_PREVIEW_TEXT" => "N",
                    "PAGER_TEMPLATE" => ".default",
                    "DISPLAY_TOP_PAGER" => "N",
                    "DISPLAY_BOTTOM_PAGER" => "N",
                    "PAGER_TITLE" => "Новости",
                    "PAGER_SHOW_ALWAYS" => "N",
                    "PAGER_DESC_NUMBERING" => "N",
                    "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                    "PAGER_SHOW_ALL" => "N"
                )
            );
            ?>
        </div>
</aside>