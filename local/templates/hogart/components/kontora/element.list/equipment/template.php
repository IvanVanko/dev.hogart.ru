<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

foreach ($arResult["ITEMS"] as $arItem):?>
	 <li><a class="icon-acrobat" href="<?=CFile::GetPath($arItem['PROPERTIES']['file']['VALUE'])?>"><?=$arItem['NAME']?></a></li>
<?endforeach;?>
