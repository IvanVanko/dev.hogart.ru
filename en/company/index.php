<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("keywords", "Компания Хогарт");
$APPLICATION->SetPageProperty("description", "Компания Хогарт, основанная в 1996 году на сегодняшний день является одним из крупнейших поставщиков инженерных систем в области отопления, вентиляции и сантехники.");
$APPLICATION->SetPageProperty("TITLE", "Company");
$APPLICATION->SetTitle("About Hogart ");

$APPLICATION->IncludeComponent("kontora:element.detail", "company", array(
	"ID"    => "26247",
	"PROPS" => "Y",
    "SET_STATUS_404" => "Y"
));?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>