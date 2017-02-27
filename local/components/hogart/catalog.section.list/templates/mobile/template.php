<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
?>
<ul id="menu-product" role="tablist" class="catalog-mobile">
    <? foreach ($arResult['SECTION_SORT'] as $arTopSection): ?>
        <? if (empty($arTopSection["ELEMENT_CNT"])) continue; ?>

        <li class="catalog-mobile__column">
            <a class="catalog-mobile__accordion" data-toggle="collapse" data-parent="#menu-product" href="#heating_<?= $arTopSection["ID"]?>" aria-expanded="false" title="<?= $arTopSection["NAME"]?>"><?= $arTopSection["NAME"]?></a>

            <? if (!empty($arTopSection["SUB_SECTION"])): ?>
                <ul id="heating_<?= $arTopSection["ID"]?>" class="catalog-mobile__sub-menu panel-group collapse panel-collapse">
                    <? foreach ($arTopSection["SUB_SECTION"] as $arSecondSection): ?>
                        <? if (empty($arSecondSection["ELEMENT_CNT"])) continue; ?>
                        <? if (empty($arSecondSection["SUB_SECTION"])): ?>
                            <li calss="catalog-mobile__sub-item">
                                <a class="catalog-mobile__sub-link" href="<?= $arSecondSection["SECTION_PAGE_URL"]?>" title="<?= $arSecondSection["NAME"]?>"><?= $arSecondSection["NAME"]?></a>
                            </li>
                        <? else: ?>
                            <li id="sub-accordion_<?= $arSecondSection["ID"]?>" class="catalog-mobile panel panel-default">
                                <a class="catalog-mobile__sub-link" role="tab" data-toggle="collapse" data-parent="#heating_<?= $arTopSection["ID"]?>" href="#sec_elem_<?= $arSecondSection["ID"]?>" aria-expanded="false" title="Арматура"><?= $arSecondSection["NAME"]?></a>
                                <ul id="sec_elem_<?= $arSecondSection["ID"]?>" role="tabpanel" class="catalog-mobile__description collapse panel-collapse">
                                    <? foreach ($arSecondSection["SUB_SECTION"] as $arThirdSection): ?>
                                        <? if (empty($arThirdSection["ELEMENT_CNT"])) continue; ?>
                                        <li>
                                            <a href="<?= $arThirdSection["SECTION_PAGE_URL"]?>" title="<?= $arThirdSection["NAME"]?>"><?= $arThirdSection["NAME"]?></a>

                                            <? foreach ($arResult["SECTION_BRANDS"][$arThirdSection["ID"]] as $brand): ?>
                                                <a href="<?= $arThirdSection["SECTION_PAGE_URL"] ?>?arrFilter_<?= $arParams["FILTER"]["brand"] ?>_<?= abs(crc32($brand["ID"])) ?>=Y&set_filter=Показать" title="<?= $brand["NAME"]?>"><?= $brand["NAME"] ?></a>
                                            <? endforeach; ?>
                                        </li>
                                    <? endforeach; ?>
                                </ul>
                            </li>
                        <? endif; ?>
                    <? endforeach; ?>
                </ul>
            <? endif; ?>
        </li>
    <? endforeach; ?>
</ul>