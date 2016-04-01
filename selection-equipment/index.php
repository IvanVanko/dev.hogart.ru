<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Подбор оборудования");
?>


    <div class="inner">
        <h1><?$APPLICATION->ShowTitle()?></h1>
        <?$APPLICATION->IncludeComponent("bitrix:main.include","",Array(
                "AREA_FILE_SHOW" => "page",
                "AREA_FILE_SUFFIX" => "inc_top",
                "AREA_FILE_RECURSIVE" => "Y",
                "EDIT_TEMPLATE" => "standard.php"
            )
        );?>
    </div>

<div class="add-padding">
<?$APPLICATION->IncludeComponent(
    "bitrix:news.list",
    "selection_quipment_list",
    array(
        "DISPLAY_DATE" => "Y",
        "DISPLAY_NAME" => "Y",
        "DISPLAY_PICTURE" => "Y",
        "DISPLAY_PREVIEW_TEXT" => "Y",
        "AJAX_MODE" => "Y",
        "IBLOCK_TYPE" => "catalog",
        "IBLOCK_ID" => "12",
        "NEWS_COUNT" => "20",
        "SORT_BY1" => "ACTIVE_FROM",
        "SORT_ORDER1" => "DESC",
        "SORT_BY2" => "SORT",
        "SORT_ORDER2" => "ASC",
        "FILTER_NAME" => "",
        "FIELD_CODE" => array(
            0 => "ID",
            1 => "IBLOCK_SECTION_ID",
        ),
        "PROPERTY_CODE" => array(
            0 => "file",
            1 => "DESCRIPTION",
            2 => "",
        ),
        "CHECK_DATES" => "Y",
        "DETAIL_URL" => "",
        "PREVIEW_TRUNCATE_LEN" => "",
        "ACTIVE_DATE_FORMAT" => "d.m.Y",
        "SET_TITLE" => "Y",
        "SET_BROWSER_TITLE" => "Y",
        "SET_META_KEYWORDS" => "Y",
        "SET_META_DESCRIPTION" => "Y",
        "SET_STATUS_404" => "Y",
        "SET_LAST_MODIFIED" => "Y",
        "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
        "ADD_SECTIONS_CHAIN" => "N",
        "HIDE_LINK_WHEN_NO_DETAIL" => "Y",
        "PARENT_SECTION" => "",
        "PARENT_SECTION_CODE" => "",
        "INCLUDE_SUBSECTIONS" => "Y",
        "CACHE_TYPE" => "A",
        "CACHE_TIME" => "3600",
        "CACHE_FILTER" => "Y",
        "CACHE_GROUPS" => "Y",
        "DISPLAY_TOP_PAGER" => "Y",
        "DISPLAY_BOTTOM_PAGER" => "Y",
        "PAGER_TITLE" => "Новости",
        "PAGER_SHOW_ALWAYS" => "Y",
        "PAGER_TEMPLATE" => "",
        "PAGER_DESC_NUMBERING" => "Y",
        "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
        "PAGER_SHOW_ALL" => "Y",
        "AJAX_OPTION_JUMP" => "N",
        "AJAX_OPTION_STYLE" => "Y",
        "AJAX_OPTION_HISTORY" => "N",
        "AJAX_OPTION_ADDITIONAL" => ""
    ),
    false, array('HIDE_ICONS' => "Y")
);?>
</div>
    <?/*<div class="inner selection-equip-line">
        <div class="row">
            <div class="col2"><h2>Опросные листы</h2></div>
            <?$arSectionsId = $APPLICATION->IncludeComponent("kontora:section.list", "equipment", array(
                'IBLOCK_ID' => '12',
                'SELECT'    => array('UF_CATALOG_SECTION', 'ID', 'IBLOCK_ID', 'NAME'),
            ));?>
        </div>
    </div>

    <div class="inner">
        <?foreach ($arSectionsId as $key => $sectionId) {?>
            <? if (CIBlockElement::GetList(array(), array('IBLOCK_ID' => '12', 'SECTION_ID' => $sectionId, 'ACTIVE' => Y), array(), false, array())): ?>
            <ul class="js-tab-item file-list" data-id="#tab<?=$key+1?>">
                <?$APPLICATION->IncludeComponent("kontora:element.list", "equipment", array(
                    'IBLOCK_ID' => '12',
                    'PROPS'     => 'Y',
                    'FILTER'    => array('SECTION_ID' => $sectionId),
                ));?>
            </ul>
            <? endif; ?>
        <?}?>
    </div>*/?>

    <aside class="sidebar js-fh js-fixed-block js-paralax-height" data-fixed="top">
        <div class="inner js-paralax-item">
            <div class="reg-side-cnt padding">
                <?$APPLICATION->IncludeComponent(
                    "bitrix:form.result.new",
                    "equipment",
                    Array(
                        "WEB_FORM_ID" => "6",
                        "IGNORE_CUSTOM_TEMPLATE" => "N",
                        "USE_EXTENDED_ERRORS" => "N",
                        "SEF_MODE" => "N",
                        "VARIABLE_ALIASES" => Array("WEB_FORM_ID"=>"WEB_FORM_ID","RESULT_ID"=>"RESULT_ID"),
                        "CACHE_TYPE" => "A",
                        "CACHE_TIME" => "3600",
                        "LIST_URL" => "",
                        "EDIT_URL" => "",
                        "SUCCESS_URL" => "",
                        "CHAIN_ITEM_TEXT" => "",
                        "CHAIN_ITEM_LINK" => "",

                        "SUCCESS_MESSAGE" => "Спасибо, что обратились в нашу компанию! Ваша заявка принята. В ближайшее время с вами свяжется специалист компании для уточнения деталей проекта."
                    ), $component
                );?>
            </div>
        </div>
    </aside>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>