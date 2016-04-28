<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Learn");
?>

    <div class="inner no-full">
        <div class="head-learn">
            <div class="col1">
                <h1><?$APPLICATION->ShowTitle()?></h1>
            </div>
            <div class="col2">
                <ul class="var-view">
                    <li><a href="#learn-calendar" class="icon-cal active">On calendar</a></li>
                    <li><a href="#calendar-list" class="icon-list">List</a></li>
                </ul>
            </div>
        </div>
    </div>
	<div class="inner no-full no-padding-new">
    <?
    $APPLICATION->IncludeComponent(
	"bitrix:news.list",
	"seminars",
	array(
		"IBLOCK_ID" => 39,
		"IBLOCK_TYPE" => "training",
		"PROPERTY_CODE" => array(
			0 => "adress",
			1 => "",
		),
		"NEWS_COUNT" => "20",
		"SORT_BY1" => "ACTIVE_FROM",
		"SORT_ORDER1" => "DESC",
		"SORT_BY2" => "SORT",
		"SORT_ORDER2" => "ASC",
		"FILTER_NAME" => "",
		"FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
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
		"SET_TITLE" => "Y",
		"SET_BROWSER_TITLE" => "Y",
		"SET_META_KEYWORDS" => "Y",
		"SET_META_DESCRIPTION" => "Y",
		"SET_STATUS_404" => "N",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"ADD_SECTIONS_CHAIN" => "N",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"PARENT_SECTION" => "",
		"PARENT_SECTION_CODE" => "",
		"INCLUDE_SUBSECTIONS" => "Y",
		"PAGER_TEMPLATE" => ".default",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"PAGER_TITLE" => "Новости",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"AJAX_OPTION_ADDITIONAL" => ""
	),
	false
);
    ?>
    <div class="calendar-cnt learn-list" id="learn-calendar" data-datepicker="#calendar-array"></div>
</div>
	<div class="inner no-full">
    <div id="calendar-list" class="inner learn-list" style="display: none;">
        <?
        $APPLICATION->IncludeComponent(
	"bitrix:news.list",
	"seminar-list",
	array(
		"IBLOCK_ID" => 39,
		"IBLOCK_TYPE" => "training",
		"SORT_BY1" => "PROPERTY_sem_start_date",
		"SORT_ORDER1" => "DESC",
		"PROPERTY_CODE" => array(
			0 => "adress",
			1 => "",
		),
		"SEF_MODE" => "Y",
		"SEF_FOLDER" => "/en/learn/",
		"NEWS_COUNT" => "20",
		"SORT_BY2" => "SORT",
		"SORT_ORDER2" => "ASC",
		"FILTER_NAME" => "",
		"FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
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
		"SET_TITLE" => "Y",
		"SET_BROWSER_TITLE" => "Y",
		"SET_META_KEYWORDS" => "Y",
		"SET_META_DESCRIPTION" => "Y",
		"SET_STATUS_404" => "N",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"ADD_SECTIONS_CHAIN" => "N",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"PARENT_SECTION" => "",
		"PARENT_SECTION_CODE" => "",
		"INCLUDE_SUBSECTIONS" => "Y",
		"PAGER_TEMPLATE" => ".default",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"PAGER_TITLE" => "Новости",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"AJAX_OPTION_ADDITIONAL" => ""
	),
	false
);
        ?>
    </div>
	</div>
<div class="inner bottom">
	<br/>
	<ul class="learn-head-link">
		<li><a href="/en/learn/zapis-na-seminary-s-otkrytoy-datoy/" class="icon-edit">Sign up for seminars with an open date</a></li>
		<li><a href="/en/learn/archive-seminarov/" class="icon-base">Archive of seminars</a></li>
	</ul>
	<br/>
</div>
<!--    <aside class="sidebar js-fh js-fixed-block js-paralax-height sidebar-popup" data-fixed="top">-->
    <aside class="sidebar js-fh js-fixed-block js-paralax-height" data-fixed="top">
        <div class="inner js-paralax-item">
            <div class="padding">
                <a href="#" class="close js-close-sidebar-popup"></a>
                <?$APPLICATION->IncludeComponent(
                    "bitrix:form.result.new",
                    "sem_quest",
                    Array(
                        "WEB_FORM_ID" => "4",
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
                        "CHAIN_ITEM_LINK" => ""
                    ), $component
                );?>
            </div>
        </div>
    </aside>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>