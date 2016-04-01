<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

//delayed function must return a string
if(empty($arResult))
	return "";
#DebugMessage($arResult);
$strReturn = '<aside class="breadcrumbs">';

$num_items = count($arResult);
for($index = 0, $itemSize = $num_items; $index < $itemSize; $index++)
{
	$title = htmlspecialcharsex($arResult[$index]["TITLE"]);
	
	if($arResult[$index]["LINK"] <> "" && $index != $itemSize-1)
		$strReturn .= '<div class="prev-pages-wrap"><a class="page" href="'.$arResult[$index]["LINK"].'" title="'.$title.'">'.$title.'</a></div>';
	else
		$strReturn .= '<span class="page active">'.$title.'</span>';
}

$strReturn .= '</aside>';

return $strReturn;
?>