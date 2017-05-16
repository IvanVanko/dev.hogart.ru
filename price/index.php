<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetPageProperty("title", "Прайс-лист");
$APPLICATION->SetTitle("Прайс-лист");

$catalogMenu = $APPLICATION->IncludeComponent(
    "bitrix:menu.sections",
    "",
    array(
        "IS_SEF" => "Y",
        "SEF_BASE_URL" => "/catalog/",
        "SECTION_PAGE_URL" => "#bx_cat_#SECTION_ID#",
        "DETAIL_PAGE_URL" => "#SECTION_CODE#/#ELEMENT_ID#/",
        "IBLOCK_TYPE" => "catalog",
        "IBLOCK_ID" => "1",
        "DEPTH_LEVEL" => "1",
        "CACHE_TYPE" => "Y",
        "CACHE_TIME" => "3600",
        "INCLUDE_SUBSECTIONS" => "Y"
    ),
    false
);

?>

<div class="row">
    <div class="col-md-9 col-xs-12">
        <h3><?= $APPLICATION->GetTitle() ?></h3>
        <p>
            <?= HogartHelpers::ShowStaticContent("price-list", "PREVIEW_TEXT") ?>
        </p>
		<ul class="b-price-list">

            <? foreach ($catalogMenu as $catalogItem): ?>
                <? if(empty($catalogItem[3]["PRICE"])) continue;?>
                <li class="b-price-list__item">
                    <a target="_blank" href="<?= $catalogItem[1] ?>" class="b-header__link b-price-list__title" title="<?= $catalogItem[0] ?>">
                        <span><?= $catalogItem[0] ?></span>
                    </a>

                    <div class="b-price-list__information">
                        <? if (!empty($catalogItem[3]['PRICE_LIST_COVER'])): ?>
                            <? $file = CFile::ResizeImageGet($catalogItem[3]['PRICE_LIST_COVER'], array('width' => 300, 'height' => 424), BX_RESIZE_IMAGE_EXACT, true); ?>
                            <a target="_blank" href="<?= CFile::GetPath($catalogItem[3]["PRICE"]); ?>" class="b-price-list__image">
                                <img src="<?= $file['src']; ?>" alt="<?= $catalogItem[0] ?>">
                            </a>
                        <? endif; ?>

                        <? if(!empty($catalogItem[3]["PRICE"])): ?>
                            <? $priceFileMeta = CFile::MakeFileArray($catalogItem[3]["PRICE"]) ?>
                            <a target="_blank" href="<?= CFile::GetPath($catalogItem[3]["PRICE"]); ?>" class="b-price-list__link" title="<?= $catalogItem[3]["PRICE_LABEL"] ?>">
                                <span class="icon-acrobat">
                                    <?= $catalogItem[3]["PRICE_LABEL"] ?>
                                    <i class="download-link fa fa-download"></i>
                                </span>
                                <span class="h6 green b-price-list__pdf">
                                    - <?= ucfirst(explode('/', $priceFileMeta['type'])[1]) ?>, <?= convert($priceFileMeta['size']) ?>
                                </span>
                            </a>
                        <? endif; ?>
                    </div>
                </li>
            <? endforeach; ?>

		</ul>
    </div>
    <div class="col-md-3 col-xs-12 aside aside-mobile">
        <div class="brand-links">
            <div class="row">
                <? $APPLICATION->IncludeComponent("kontora:element.list", "price_brand_list", array(
                    'IBLOCK_ID' => BRAND_IBLOCK_ID,
                    'ORDER' => array('NAME' => 'ASC'),
                    'PROPS' => 'Y',
                    "CACHE_TYPE" => "A",
                    "CACHE_TIME" => "0",
                ));?>
            </div>
        </div>
    </div>
</div>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>