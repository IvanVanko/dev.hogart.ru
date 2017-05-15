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

<? foreach ($arResult["ITEMS"] as $arItem): ?>
    <div class="col-md-6">
        <a target="_blank" href="<?= $arItem["DETAIL_PAGE_URL"]?>">
            <?= $arItem["NAME"] ?>
        </a>
    </div>
<? endforeach; ?>


