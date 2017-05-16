<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

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

?>
<div class="bx-filter-parameters-box-container">
    <? foreach (array_values($arResult["ITEMS"]) as $k => $arItem): ?>
        <div class="checkbox <?= $k > 3 ? 'more' : ''?>">
            <label class="bx-filter-param-label" for="brand_<?= $arItem['CODE'] ?>">
                    <span class="bx-filter-input-checkbox">
                        <input onchange="toggleBrandFilter(this)" data-code="<?= $arItem['CODE'] ?>" type="checkbox" value="Y" name="brand_<?= $arItem['CODE'] ?>" id="brand_<?= $arItem['CODE'] ?>">
                        <span class="bx-filter-param-text" title="<?= $arItem["NAME"] ?>"><?= $arItem["NAME"] ?></span>
                    </span>
            </label>
        </div>
    <? endforeach; ?>

    <div class="col-sm-12">
        <span class="btn-more" onclick="toggleMoreFilter(this)">Еще <i class="fa"></i></span>
    </div>
</div>

