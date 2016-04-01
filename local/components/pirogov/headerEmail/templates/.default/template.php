<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
$this->setFrameMode(true);
?>
<a href="mailto:<?=$arResult['TOP_EMAIL']?>"><?=$arResult['TOP_EMAIL']?></a>
<a href="mailto:<?=$arResult['BOTTOM_EMAIL']?>"><?=$arResult['BOTTOM_EMAIL']?></a>