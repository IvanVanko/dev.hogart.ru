<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Результаты поиска");?>
<?$APPLICATION->IncludeComponent("bitrix:search.page", "pages", array(
	"RESTART"                   => "Y",
	"NO_WORD_LOGIC"             => "N",
	"CHECK_DATES"               => "N",
	"USE_TITLE_RANK"            => "N",
	"DEFAULT_SORT"              => "rank",
	"FILTER_NAME"               => "",
	"arrFILTER"                 => array(
		"main",
		"iblock_training", 
		"iblock_vacancies", 
		"iblock_company", 
		"iblock_news", 
		"iblock_contacts", 
		"iblock_solutions"
	),
	"arrFILTER_iblock_training"  => array("all"),
	"arrFILTER_iblock_vacancies" => array("all"),
	"arrFILTER_iblock_company"   => array("all"),
	"arrFILTER_iblock_news"      => array("all"),
	"arrFILTER_iblock_contacts"  => array("all"),
	"arrFILTER_iblock_solutions" => array("all"),
	"SHOW_WHERE"                 => "N",
	"SHOW_WHEN"                  => "N",
	"PAGE_RESULT_COUNT"          =>10,
	"AJAX_MODE"                  => "N",
	"AJAX_OPTION_JUMP"           => "N",
	"AJAX_OPTION_STYLE"          => "Y",
	"AJAX_OPTION_HISTORY"        => "N",
	"CACHE_TYPE"                 => "N",
	"CACHE_TIME"                 => "3600",
	"USE_LANGUAGE_GUESS"         => "Y",
	"USE_SUGGEST"                => "N",
	"DISPLAY_TOP_PAGER"          => "N",
	"DISPLAY_BOTTOM_PAGER"       => "Y",
	"PAGER_TITLE"                => "Результаты поиска",
	"PAGER_SHOW_ALWAYS"          => "Y",
	"PAGER_TEMPLATE"             => "search_list"
));?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>