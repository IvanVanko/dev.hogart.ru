<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
?>
<ul id="menu-product" role="tablist" aria-multiselectable="true" class="navigation-sub-menu catalog-mobile--main collapse panel-collapse in"">
    <? foreach ($arResult['SECTION_SORT'] as $arTopSection): ?>
        <? if (empty($arTopSection["ELEMENT_CNT"])) continue; ?>

        <li class="catalog-mobile__column">
            <a href="/catalog/#heating_<?= $arTopSection["ID"]?>" title="<?= $arTopSection["NAME"]?>"><?= $arTopSection["NAME"]?></a>
        </li>
    <? endforeach; ?>
</ul>