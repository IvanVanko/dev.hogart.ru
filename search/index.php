<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Результаты поиска");
use Bitrix\Main\Loader;

$sectionId = "";
if(isset($_REQUEST['section_id'])) {
    $sectionId = $_REQUEST['section_id'];
}
?>
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

            $APPLICATION->IncludeComponent(
	"kontora:element.list", 
	"search", 
	array(
		"IBLOCK_ID" => CATALOG_IBLOCK_ID,
		"ELEMENT_COUNT" => "21",
		"NAV" => "Y",
		"FILTER" => $GLOBALS["arrFilter"],
		"PROPS" => "N",
		"SELECT" => "PREVIEW_PICTURE",
		
		"COMPONENT_TEMPLATE" => "search",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "3600"
	),
	false
); ?>
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