<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetPageProperty("keywords", "Компания Хогарт");
$APPLICATION->SetPageProperty("description", "Компания Хогарт, основанная в 1996 году на сегодняшний день является одним из крупнейших поставщиков инженерных систем в области отопления, вентиляции и сантехники.");
$APPLICATION->SetPageProperty("TITLE", "Компания");
$APPLICATION->SetTitle("О компании Хогарт ");

$APPLICATION->IncludeComponent("kontora:element.detail", "company", array(
    "ID" => "21",
    "PROPS" => "Y",
    "SET_STATUS_404" => "Y",
    "ADD_CHAIN_ITEM" => "N"
)); ?>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>