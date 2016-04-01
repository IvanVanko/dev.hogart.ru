<?
include_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/urlrewrite.php');
global $APPLICATION;
CHTTP::SetStatus("404 Not Found");
@define("ERROR_404","Y");

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

$APPLICATION->SetPageProperty("title","404 Not Found");?>
<div class="inner">
    <h1>Ошибка 404 - Страница не найдена</h1><p>К сожалению такой страницы на сайте не существует. Вы можете воспользоваться меню сайта для перехода в нужный раздел.</p>
    <?$APPLICATION->IncludeComponent("bitrix:main.map", ".default", Array(
            "LEVEL"	=>	"3",
            "COL_NUM"	=>	"2",
            "SHOW_DESCRIPTION"	=>	"Y",
            "SET_TITLE"	=>	"Y",
            "CACHE_TIME"	=>	"36000000"
        )
    );

    require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
</div>
