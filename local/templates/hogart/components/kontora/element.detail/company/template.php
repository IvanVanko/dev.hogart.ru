<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
?>

<div class="row company">
    <div class="col-md-9">
        <h3 style="margin-top: 10px"><?= $arResult['NAME'] ?></h3>
        <div class="preview-text">
            <?= $arResult["PREVIEW_TEXT"] ?>
        </div>
        <div class="detail-text">
            <?= $arResult["DETAIL_TEXT"] ?>
        </div>

        <h3><?=GetMessage("Хогарт сегодня")?></h3>
        <? $APPLICATION->IncludeComponent("kontora:element.list", "hogart_today", array(
            "IBLOCK_ID" => "21",
            "PROPS" => "Y",
            'ORDER' => array('sort' => 'asc'),
        )); ?>

        <? if (!empty($arResult["PROPERTIES"]["honors"]["VALUE"])): ?>
            <div class="inner">
                <h3><?=GetMessage("Достижения и награды")?></h3>
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
    <div class="col-md-3 aside">
        <? if (!empty($arResult['PROPERTIES']['points']['VALUE'])): ?>
            <ul class="counter-company row">
                <? foreach ($arResult['PROPERTIES']['points']['VALUE'] as $key => $value): ?>
                    <li class="col-md-12">
                        <span><?= $key + 1 ?></span>
                        <p><?= $value ?></p>
                    </li>
                <? endforeach; ?>
            </ul>
        <? endif; ?>

        <? if (!empty($arResult["PROPERTIES"]["partners"]["VALUE"])): ?>
            <h4><?=GetMessage("Наши партнеры")?></h4>
            <p><?= $arResult["PROPERTIES"]["partners"]["~VALUE"]["TEXT"] ?></p>
        <? endif; ?>

        <h4><?=GetMessage("Основные направления деятельности")?></h4>
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
</div>

