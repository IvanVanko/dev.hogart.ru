<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

global $SUBSCRIBE_TEMPLATE_RESULT, $SUBSCRIBE_TEMPLATE_RUBRIC, $APPLICATION;

$SUBSCRIBE_TEMPLATE_RESULT = false;
$SUBSCRIBE_TEMPLATE_RUBRIC = $arRubric;?>

<style type=text/css>
   .text {font-family: Verdana, Arial, Helvetica, sans-serif; font-size:12px; color: #1C1C1C; font-weight: normal;}
   .newsdata {font-family: Arial, Helvetica, sans-serif; font-size:12px; font-weight:bold; color: #346BA0; text-decoration:none;}
   h1 {font-family: Verdana, Arial, Helvetica, sans-serif; color:#346BA0; font-size:15px; font-weight:bold; line-height: 16px; margin-bottom: 1mm;}
</style>

<p>Добрый день!</p>
<p>
   <?$SUBSCRIBE_TEMPLATE_RESULT = $APPLICATION->IncludeComponent(
      "bitrix:subscribe.news",
      "subscribe",
      Array(
         "SITE_ID"     => "s1",
         "IBLOCK_TYPE" => "news",
         "ID"          => '3',
         "SORT_BY"     => "ACTIVE_FROM",
         "SORT_ORDER"  => "DESC",
         'FILTER'      => array('PROPERTY_TAG_VALUE' => 'Акция'),
      ),
   false
   );?>
</p>
<p>Всего хорошего</p>
<?$new_date = $DB->FormatDate(date("d.m.Y H:i:s"), "DD.MM.YYYY HH:MI:SS", CSite::GetDateFormat("FULL", "ru"));
if($SUBSCRIBE_TEMPLATE_RESULT)
   return array(
      "SUBJECT"        => 'Новости - О компании',
      "BODY_TYPE"      => "html",
      "CHARSET"        => "Windows-1251",
      "DIRECT_SEND"    => "Y",
      "FROM_FIELD"     => COption::GetOptionString("main", "email_from"),
      "AUTO_SEND_FLAG" => "Y",
      "AUTO_SEND_TIME" => $new_date
   );
else
   return false;
?>