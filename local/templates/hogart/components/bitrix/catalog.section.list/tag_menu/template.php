<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>
<ul class="tag-left-menu fixed-block" data-rel-fixed-block="#header-block">
    <? foreach ($arResult['SECTIONS'] as &$arSection): ?>
        <? if (empty($arSection["ELEMENT_CNT"])) continue; ?>
        <li data-rel="<?= ("bx_cat_" . $arSection['ID']) ?>">
            <a href="#<?= ("bx_cat_" . $arSection['ID']) ?>">
                <i class="icon-<?= $arSection["CODE"] ?>"></i> <?= $arSection["NAME"] ?>
            </a>
        </li>
    <? endforeach; ?>
</ul>
