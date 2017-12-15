<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
?>
<div class="col-md-9 sections">
    <? include($_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/catalog_menu.php'); ?>

    <? $relative_level = 0; ?>
    <? foreach ($arResult['SECTIONS'] as $key => $arSection): ?>
        <?
            if (empty($arSection["ELEMENT_CNT"])) continue;
            $this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], $strSectionEdit);
            $this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams);
        ?>
        <? if($relative_level != $arSection["DEPTH_LEVEL"] || $arSection["DEPTH_LEVEL"] < 3): ?>
            <? if ($relative_level != 0): ?>
            </div></div>
            <? endif; ?>
            <div class="row d-<?= $arSection["DEPTH_LEVEL"] ?>" data-id="<?= $arSection['ID'] ?>" data-parent="<?= $arSection['IBLOCK_SECTION_ID'] ?>"><div class="col-md-12">
        <? endif; ?>

        <? if ($arSection["DEPTH_LEVEL"] == 1): ?>
            <div class="row depth-1 vertical-align">
                <i id="<?= ("bx_cat_" . $arSection['ID']) ?>"></i>
                <div class="col-md-6">
                    <div class="row vertical-align">
                        <div class="col-md-1">
                            <i class="icon-<?= $arSection["CODE"] ?>"></i>
                        </div>
                        <div class="col-md-11 text-uppercase title"><?= $arSection["NAME"] ?></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <? if(!empty($arSection["UF_PRICE"])): ?>
                        <? $priceFileMeta = CFile::MakeFileArray($arSection["UF_PRICE"]) ?>
                        <span class="price-list">
                            <a href="<?=CFile::GetPath($arSection["UF_PRICE"]); ?>" class="download">
                                <i class="fa fa-download"></i> <span class="h5 color-black"><?=$arSection["UF_PRICE_LABEL"]?></span>
                            </a>
                            <span class="file-metadata">
                                <?= ucfirst(explode('/', $priceFileMeta['type'])[1]) ?>, <?= convert($priceFileMeta['size']) ?>
                            </span>
                        </span>
                    <? endif; ?>
                </div>
            </div>
        <? endif; ?>

        <? if ($arSection["DEPTH_LEVEL"] == 2): ?>
            <div class="row depth-2">
                <div class="col-md-6" id="<?= ("bx_cat_" . $arSection['ID']) ?>">
                    <div class="row">
                        <div class="col-md-offset-1 col-md-11 text-uppercase title"><a href="<?= $arSection["SECTION_PAGE_URL"] ?>"><?= $arSection["NAME"] ?></a></div>
                    </div>
                </div>
            </div>
        <? endif; ?>
        <? if ($arSection["DEPTH_LEVEL"] == 3): ?>
            <div class="row depth-3">
                <div class="col-md-6" id="<?= ("bx_cat_" . $arSection['ID']) ?>">
                    <div class="row">
                        <div class="col-md-offset-1 col-md-11 title"><a href="<?= $arSection["SECTION_PAGE_URL"] ?>"><?= $arSection["NAME"] ?></a></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <? foreach ($arSection["BRANDS"] as $brand): ?>
                        <span data-code="<?= $brand['CODE'] ?>" class="brand"><a href="<?= $arSection["SECTION_PAGE_URL"] ?>?arrFilter_<?= $arParams["FILTER"]["brand"] ?>_<?= abs(crc32($brand["ID"])) ?>=Y&set_filter=Показать"><?= $brand["NAME"] ?></a></span>
                    <? endforeach; ?>
                </div>
            </div>
        <? endif; ?>
    <? $relative_level = $arSection["DEPTH_LEVEL"]; ?>
    <? endforeach; ?>

    </div>
    </div>
</div>

<!-- блок онлайн фильтра по бренду -->
<div class="col-md-3 aside" style="padding-right: 0; overflow-x: hidden">
    <div class="brand-links">
        <h3>
            Бренды
        </h3>
        <div class="bx-filter-parameters-box-container">
            <? foreach (array_values($arResult["BRANDS"]) as $k => $arItem): ?>
                <? if (empty($arItem['CODE'])) continue; ?>
                <div class="checkbox">
                    <label class="bx-filter-param-label" for="brand_<?= $arItem['CODE'] ?>">
                    <span class="bx-filter-input-checkbox">
                        <input onchange="toggleBrandFilter(this)" data-code="<?= $arItem['CODE'] ?>" type="checkbox" value="Y" name="brand_<?= $arItem['CODE'] ?>" id="brand_<?= $arItem['CODE'] ?>">
                        <span class="bx-filter-param-text" title="<?= $arItem["NAME"] ?>"><?= $arItem["NAME"] ?></span>
                    </span>
                    </label>
                </div>
            <? endforeach; ?>
        </div>
    </div>
</div>