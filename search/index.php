<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Результаты поиска");
use Bitrix\Main\Loader;

$sectionId = "";
if(isset($_REQUEST['section_id'])) {
    $sectionId = $_REQUEST['section_id'];
}
?>
    <aside class="sidebar js-fh js-fixed-block js-paralax-height" data-fixed="top">
        <div class="inner js-paralax-item">
            <div class="js-tab-item" data-id="#catalog_tab">
                <?
                $arFilterSectionsCnt = array();
                $sectionsId = array();

                if(isset($_REQUEST['q']) && !empty($_REQUEST['q'])) {
                    $arFilter = array('IBLOCK_ID' => 1, 'NAME' => '%'.$_REQUEST['q'].'%');
                    $rsSect = CIBlockSection::GetList(array(), $arFilter);
                    while($arSect = $rsSect->GetNext()) {
                        $arFilter2 = array(
                            "IBLOCK_ID" => 1,
                            '>=LEFT_BORDER' => $arSect['LEFT_MARGIN'],
                            '<=RIGHT_BORDER' => $arSect['RIGHT_MARGIN'],

                        );
                        $rsSect2 = CIBlockSection::GetList(array(), $arFilter2);
                        while($arSect2 = $rsSect2->GetNext()) {
                            $sectionsId[] = $arSect2['ID'];
                        }
                    }
                    $arFilterSectionsCnt[] = array(
                        "LOGIC" => "OR",
                        array("SECTION_ID" => $sectionsId),
                        array("NAME" => '%'.$_REQUEST['q'].'%'),
                    );
                }

                $arFilterSectionsCnt['IBLOCK_ID'] = 1;

                $rsSections = CIBlockSection::GetList(Array("left_margin"=>"asc"), $arFilterSectionsCnt);
                $sectionsList = [];
                while($arSection = $rsSections->GetNext()){
                    $sectionsList[] = $arSection;
                }
                foreach ($sectionsList as $key => $arSection) {
                    $arFilter = array(
                        "IBLOCK_ID"           => CATALOG_IBLOCK_ID,
                        "ACTIVE"              => "Y",
                        "INCLUDE_SUBSECTIONS" => "Y",
                        'SECTION_ID'          => $arSection['ID']
                    );

                    if (!empty($arFilterSectionsCnt))
                        $arFilter = array_merge($arFilter, $arFilterSectionsCnt);

                    $sectionsList[$key]['ELEMENT_CNT'] = CIBlockElement::GetList(array(), $arFilter, array(), false, array());
                    if($sectionsList[$key]['ELEMENT_CNT'] == 0){
                        unset($sectionsList[$key]);
                    }
                }
                ?>
                <div class="side_href">
                    <ul class="search-category-links js-menu-select">
                        <?$depth_level = 0;
                        foreach ($sectionsList as $arSection) {
                            if ($depth_level != 0){
                                if ($depth_level == $arSection['DEPTH_LEVEL']) {?>
                                    </li>
                                <?}
                                elseif ($arSection['DEPTH_LEVEL'] > $depth_level) {?>
                                    <ul class="sub-menu">
                                <?}
                                elseif ($arSection['DEPTH_LEVEL'] < $depth_level) {?>
                                    <?=str_repeat('</li></ul>', $depth_level - $arSection['DEPTH_LEVEL'])?></li>
                                <?}
                            }?>
                            <li><a<?if ($_REQUEST['section_id'] == $arSection['ID']):?> class="current-page"<?endif;?> href="?section_id=<?=$arSection['ID']?>&q=<?=$_REQUEST['q']?>"><?=$arSection['NAME']?> (<?=$arSection["ELEMENT_CNT"];?>)</a>
                            <?
                            $depth_level = $arSection['DEPTH_LEVEL'];
                        }?>
                        </ul>
                </div>
                <?
//                $APPLICATION->IncludeComponent("bitrix:catalog.section.list", "search", array(
//                    "IBLOCK_TYPE" => 'catalog',
//                    "IBLOCK_ID" => 1,
//                    "CACHE_TYPE" => "A",
//                    "CACHE_TIME" => "36000000",
//                    "CACHE_GROUPS" => "Y",
//                    "COUNT_ELEMENTS" => "Y",
//                    "TOP_DEPTH" => '3',
//                    "SHOW_PARENT_NAME" => 'Y',
//                    'FILTER' => $arFilterSectionsCnt
//                )); ?>
                <div class="sidebar_padding_cnt">
                    <div class="filter_cnt">
                        <? if(!empty($sectionId)) {
                            $arFilter = array(
                                "IBLOCK_ID" => 1,
                                "ACTIVE" => "Y",
                                "GLOBAL_ACTIVE" => "Y",
                                'ID' => $sectionId
                            );

                            $obCache = new CPHPCache();
                            if($obCache->InitCache(36000, serialize($arFilter), "/iblock/catalog")) {
                                $arCurSection = $obCache->GetVars();
                            }
                            elseif($obCache->StartDataCache()) {
                                $arCurSection = array();
                                if(Loader::includeModule("iblock")) {
                                    $dbRes = CIBlockSection::GetList(array(), $arFilter, false, array("ID"));

                                    if(defined("BX_COMP_MANAGED_CACHE")) {
                                        global $CACHE_MANAGER;
                                        $CACHE_MANAGER->StartTagCache("/iblock/catalog");

                                        if($arCurSection = $dbRes->Fetch()) {
                                            $CACHE_MANAGER->RegisterTag("iblock_id_1");
                                        }
                                        $CACHE_MANAGER->EndTagCache();
                                    }
                                    else {
                                        if(!$arCurSection = $dbRes->Fetch()) {
                                            $arCurSection = array();
                                        }
                                    }
                                }
                                $obCache->EndDataCache($arCurSection);
                            }
                            if(!isset($arCurSection)) {
                                $arCurSection = array();
                            }
                            ?><? $APPLICATION->IncludeComponent(
                                "bitrix:catalog.smart.filter",
                                "search",
                                array(
                                    "IBLOCK_TYPE" => 'catalog',
                                    "IBLOCK_ID" => 1,
                                    "SECTION_ID" => $sectionId,
                                    "FILTER_NAME" => 'arrFilter',
                                    "PRICE_CODE" => array('BASE'),
                                    "CACHE_TYPE" => "A",
                                    "CACHE_TIME" => "36000000",
                                    "CACHE_GROUPS" => "Y",
                                    "SAVE_IN_SESSION" => "N",
                                    "FILTER_VIEW_MODE" => "VERTICAL",
                                    "XML_EXPORT" => "Y",
                                    "SECTION_TITLE" => "NAME",
                                    "SECTION_DESCRIPTION" => "DESCRIPTION",
                                    "HIDE_NOT_AVAILABLE" => "N",
                                    "TEMPLATE_THEME" => "green",
                                )
                            );
                        } ?>
                    </div>
                </div>
            </div>

            <div class="js-tab-item" data-id="#site_tab">
                <div class="company-side-cnt padding">
                    <h2>Основные направления деятельности</h2>
                    <? $GetCurDir = explode("/", $APPLICATION->GetCurDir());

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
                            "IBLOCK_ID" => 19,
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

            <div class="js-tab-item" data-id="#text_tab">
                <div class="company-side-cnt padding">
                    <h2>Основные направления деятельности</h2>
                    <?
                    $APPLICATION->IncludeComponent(
                        "bitrix:news.list",
                        "advantages",
                        Array(
                            "COMPONENT_TEMPLATE" => ".default",
                            "IBLOCK_TYPE" => "advantages",
                            "IBLOCK_ID" => 19,
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
                    ); ?>
                </div>
            </div>

            <div class="js-tab-item" data-id="#doc_tab">
                <?
                $arFilter['PROPERTY_access_level'] = ($USER->IsAuthorized()) ? array(1, 2) : 1;
                $arFilter['ACTIVE'] = "Y";
                if(isset($_REQUEST['q'])) {
                    $arFilter['NAME'] = '%'.$_REQUEST['q'].'%';
                }
                $APPLICATION->IncludeComponent("kontora:element.list", "documentation_search_filter", array(
                    'IBLOCK_ID' => '10',
                    'SELECT' => array('NAME', 'PROPERTY_brand.NAME', 'IBLOCK_ID', 'ID'),
                    'PROPS' => 'N',
                    'FILTER' => $arFilter,
                )); ?>
            </div>
        </div>
    </aside>
    <div class="inner">
        <h1>Результаты поиска по запросу "<?=$_REQUEST['q']?>"</h1>

        <ul class="tab-list-trigger js-tabs-list">
            <li><a class="js-tab-trigger" href="#catalog_tab">В каталоге
                    (<? $APPLICATION->ShowViewContent("catalog_tab_cnt"); ?>)</a></li>
            <li><a class="js-tab-trigger" href="#doc_tab">В документации
                    (<? $APPLICATION->ShowViewContent('doc_tab_cnt') ?>)</a></li>
            <li><a class="js-tab-trigger" href="#text_tab">В статьях
                    (<? $APPLICATION->ShowViewContent('text_tab_cnt') ?>)</a></li>
            <li><a class="js-tab-trigger" href="#site_tab">На страницах сайта
                    (<? $APPLICATION->ShowViewContent('site_tab_cnt') ?>)</a></li>
        </ul>

        <div class="js-tab-item" data-id="#catalog_tab">
            <?
            if(empty($GLOBALS['arrFilter']['SECTION_ID']) && !isset($_REQUEST['section_id']) && empty($_REQUEST['section_id'])) {
                $sectionsId = array();
                $arFilter = array('IBLOCK_ID' => CATALOG_IBLOCK_ID, 'NAME' => '%'.$_REQUEST['q'].'%');
                $rsSect = CIBlockSection::GetList(array(), $arFilter);
                while($arSect = $rsSect->GetNext()) {
                    $arFilter2 = array(
                        "IBLOCK_ID" => CATALOG_IBLOCK_ID,
                        '>=LEFT_BORDER' => $arSect['LEFT_MARGIN'],
                        '<=RIGHT_BORDER' => $arSect['RIGHT_MARGIN'],

                    );
                    $rsSect2 = CIBlockSection::GetList(array(), $arFilter2);
                    while($arSect2 = $rsSect2->GetNext()) {
                        $sectionsId[] = $arSect2['ID'];
                    }
                }
                $GLOBALS['arrFilter'][] = array(
                    "LOGIC" => "OR",
                    array("SECTION_ID" => $sectionsId),
                    array("NAME" => '%'.$_REQUEST['q'].'%'),
                    array("PROPERTY_sku" => '%'.$_REQUEST['q'].'%'),
                );
            }
            else {
                if(!in_array($_REQUEST['section_id'], $sectionsId)) {
                    $GLOBALS['arrFilter'][] = array(
                        "LOGIC" => "OR",
                        array("NAME" => '%'.$_REQUEST['q'].'%'),
                        array("PROPERTY_sku" => '%'.$_REQUEST['q'].'%'),
                    );
                }

                $GLOBALS['arrFilter']['SECTION_ID'] = $_REQUEST['section_id'];
            }

            if(!empty($_REQUEST['stock'])) {
                $ids = array();
                $CACHE_LIFE_TIME = 3600;

                $ob_cache_prop = new CPHPCache();
                $cache_id_prop = "props_search_ids".md5($GLOBALS['arrFilter']);

                if($ob_cache_prop->InitCache($CACHE_LIFE_TIME, $cache_id_prop, "/")) {

                    $props = $ob_cache_prop->GetVars();
                    $ids = $props['ids'];
                }
                else {

                    foreach($_REQUEST['stock'] as $id) {
                        $arFilter = array("ID" => $id);
                        $res = CIBlockElement::GetList(array('sort' => 'asc'), $arFilter, false, false, array("PROPERTY_goods"));
                        while($ob = $res->GetNextElement()) {
                            $arFields = $ob->GetFields();
                            $ids[] = $arFields['PROPERTY_GOODS_VALUE'];
                        }
                    }

                    if($ob_cache_prop->StartDataCache()) {
                        $ob_cache_prop->EndDataCache(array('ids' => $ids));
                    }
                }

                $GLOBALS['arrFilter']["ID"] = $ids;
            }

            $APPLICATION->IncludeComponent("kontora:element.list", "search", array(
                "IBLOCK_ID" => CATALOG_IBLOCK_ID,
                "ELEMENT_COUNT" => 21,
                "NAV" => 'Y',
                'FILTER' => $GLOBALS['arrFilter'],
                'PROPS' => 'N',
                'SELECT' => array('ID',
                                  'DETAIL_PAGE_URL',
                                  'NAME',
                                  'CATALOG_GROUP_1',
                                  'PREVIEW_PICTURE'),
            )); ?>
        </div>

        <div class="js-tab-item" data-id="#doc_tab">
            <? $arFilter = array();

            if(isset($_REQUEST['q'])) {
                $arFilter['NAME'] = '%'.$_REQUEST['q'].'%';
            }

            if(isset($_REQUEST['types']) && !empty($_REQUEST['types'])) {
                $arFilter['PROPERTY_TYPE_VALUE'] = $_REQUEST['types'];
            }

            if(isset($_REQUEST['brands']) && !empty($_REQUEST['brands'])) {
                $arFilter['PROPERTY_BRAND'] = $_REQUEST['brands'];
            }

            if(!empty($_REQUEST["direction"])) {
                foreach($_REQUEST['direction'] as $direction) {
                    if(!empty($_REQUEST['section_'.$direction])) {
                        $arFilterSections = array(
                            'IBLOCK_ID' => 1,
                            '>=LEFT_BORDER' => $_REQUEST['section_'.$_REQUEST['section_'.$direction].'_left'],
                            '<=RIGHT_BORDER' => $_REQUEST['section_'.$_REQUEST['section_'.$direction].'_right'],
                        );
                    }
                    else {
                        $arFilterSections = array(
                            'IBLOCK_ID' => 1,
                            '>=LEFT_BORDER' => $_REQUEST['direction_'.$direction.'_left'],
                            '<=RIGHT_BORDER' => $_REQUEST['direction_'.$direction.'_right'],
                        );
                    }
                    $rsSect = CIBlockSection::GetList(array('left_margin' => 'asc'), $arFilterSections);
                    while($arSect = $rsSect->GetNext()) {
                        $arFilter["PROPERTY_direction"][] = $arSect["ID"];
                    }
                }
            }

            if(isset($_REQUEST['product']) && !empty($_REQUEST['product'])) {
                $arFilterProducts = array(
                    "IBLOCK_ID" => 1,
                    "ACTIVE" => "Y",
                    array(
                        "LOGIC" => "OR",
                        array("%NAME" => $_REQUEST['product']),
                        array("PROPERTY_sku" => $_REQUEST['product']),
                    ),
                );
                $res = CIBlockElement::GetList(array(), $arFilterProducts, array('PROPERTY_docs'), false, array());
                while($ob = $res->GetNextElement()) {
                    $arFields = $ob->GetFields();
                    $arFilter['ID'][] = $arFields['PROPERTY_DOCS_VALUE'];
                }
            }

            $arFilter['PROPERTY_access_level'] = ($USER->IsAuthorized()) ? array(1, 2) : 1;
            $arFilter['ACTIVE'] = "Y";
            $APPLICATION->IncludeComponent("kontora:element.list", "documentation_search", array(
                'IBLOCK_ID' => '10',
                'PROPS' => 'Y',
                'FILTER' => $arFilter,
                'ELEMENT_COUNT' => 10,
                'NAV' => 'Y'
            )); ?>
        </div>

        <div class="js-tab-item" data-id="#text_tab">
            <? $text_tab_cnt = $APPLICATION->IncludeComponent("bitrix:search.page", "pages", array(
                "RESTART" => "Y",
                "NO_WORD_LOGIC" => "N",
                "CHECK_DATES" => "N",
                "USE_TITLE_RANK" => "N",
                "DEFAULT_SORT" => "rank",
                "FILTER_NAME" => "",
                "arrFILTER" => array("iblock_catalog"),
                "arrFILTER_iblock_catalog" => array(11),
                "SHOW_WHERE" => "N",
                "SHOW_WHEN" => "N",
                "PAGE_RESULT_COUNT" => 10,
                "AJAX_MODE" => "N",
                "AJAX_OPTION_JUMP" => "N",
                "AJAX_OPTION_STYLE" => "Y",
                "AJAX_OPTION_HISTORY" => "N",
                "CACHE_TYPE" => "A",
                "CACHE_TIME" => "3600",
                "USE_LANGUAGE_GUESS" => "Y",
                "USE_SUGGEST" => "N",
                "DISPLAY_TOP_PAGER" => "Y",
                "DISPLAY_BOTTOM_PAGER" => "Y",
                "PAGER_TITLE" => "Результаты поиска",
                "PAGER_SHOW_ALWAYS" => "Y",
                "PAGER_TEMPLATE" => "",
                'INFORMATION' => 'Y',
            )); ?>
        </div>

        <div class="js-tab-item" data-id="#site_tab">
            <? $APPLICATION->IncludeComponent("bitrix:search.page", "pages", array(
                "RESTART" => "Y",
                "NO_WORD_LOGIC" => "N",
                "CHECK_DATES" => "N",
                "USE_TITLE_RANK" => "N",
                "DEFAULT_SORT" => "rank",
                "FILTER_NAME" => "",
                "arrFILTER" => array(
                    "main",
                    "iblock_training",
                    "iblock_vacancies",
                    "iblock_company",
                    "iblock_news",
                    "iblock_contacts",
                    "iblock_solutions"
                ),
                "arrFILTER_iblock_training" => array("all"),
                "arrFILTER_iblock_vacancies" => array("all"),
                "arrFILTER_iblock_company" => array("all"),
                "arrFILTER_iblock_news" => array("all"),
                "arrFILTER_iblock_contacts" => array("all"),
                "arrFILTER_iblock_solutions" => array("all"),
                "SHOW_WHERE" => "N",
                "SHOW_WHEN" => "N",
                "PAGE_RESULT_COUNT" => 10,
                "AJAX_MODE" => "N",
                "AJAX_OPTION_JUMP" => "N",
                "AJAX_OPTION_STYLE" => "Y",
                "AJAX_OPTION_HISTORY" => "N",
                "CACHE_TYPE" => "A",
                "CACHE_TIME" => "3600",
                "USE_LANGUAGE_GUESS" => "Y",
                "USE_SUGGEST" => "N",
                "DISPLAY_TOP_PAGER" => "Y",
                "DISPLAY_BOTTOM_PAGER" => "Y",
                "PAGER_TITLE" => "Результаты поиска",
                "PAGER_SHOW_ALWAYS" => "Y",
                "PAGER_TEMPLATE" => ""
            )); ?>
        </div>

    </div>

<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); ?>