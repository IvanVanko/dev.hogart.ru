<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
//delayed function must return a string
if(empty($arResult))
	return "";

global $APPLICATION;

$strReturn = '<ul class="breadcrumbs">';
if ($APPLICATION->GetCurDir() == "/catalog/") {
    $strReturn = '<ul class="breadcrumbs breadcrumbs-hide">';
}

$num_items = count($arResult);
for($index = 0, $itemSize = $num_items; $index < $itemSize; $index++)
{
    $title = $arResult[$index]["TITLE"];
    if (!defined("NO_SPECIAL_CHARS_CHAIN"))
	    $title = htmlspecialcharsEx($title);

	if($arResult[$index]["LINK"] <> "" && $index != $itemSize-1)
		$strReturn .= '<li><a href="'.$arResult[$index]["LINK"].'" title="'.$title.'">'.$title.'</a></li>';
	else
		$strReturn .= '<li><span>'.$title.'</span></li>';
}

$strReturn .= '</ul>';

return $strReturn;
?>