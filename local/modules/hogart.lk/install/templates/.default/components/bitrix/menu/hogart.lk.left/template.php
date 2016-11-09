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
<? if (!empty($arResult)): ?>
<ul class="tag-left-menu fixed-block" data-rel-fixed-block="#header-block">
    <?
    foreach ($arResult as $arItem):
        if ($arParams["MAX_LEVEL"] == 1 && $arItem["DEPTH_LEVEL"] > 1)
            continue;
        ?>
        <li class="<?= ($arItem["SELECTED"] ? "active" : "") ?>">
            <a href="<?= $arItem["LINK"] ?>">
                <? if(!empty($arItem["PARAMS"]["icon"])): ?>
                    <i class="<?= $arItem["PARAMS"]["icon"] ?>"></i>
                <? endif; ?>
                <?= $arItem["TEXT"] ?>
            </a>
        </li>
    <? endforeach ?>
</ul>
<? endif ?>
