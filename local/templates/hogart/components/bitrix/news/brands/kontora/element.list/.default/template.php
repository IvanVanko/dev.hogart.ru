<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
if (count($arResult["ITEMS"]) > 0):?>
    <? $brand_links = []; ?>
    <? $first_letter = null; ?>
    <? $this->SetViewTarget("brands-list"); ?>
    <? foreach ($arResult["ITEMS"] as $arItem):
        $this->AddEditAction($arItem["ID"], $arItem["EDIT_LINK"], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
        $this->AddDeleteAction($arItem["ID"], $arItem["DELETE_LINK"], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM'))); ?>
        <? $date = explode(".", $arItem["ACTIVE_FROM"]); ?>
        <? if ($first_letter != mb_substr($arItem['NAME'], 0, 1)): ?>
            <? if (null !== $first_letter): ?>
                </ul>
            <? endif; ?>
            <? $first_letter = mb_substr($arItem['NAME'], 0, 1); ?>
            <i id="<?= ("brand_letter_" . $first_letter) ?>"></i>
            <div class="h3 text-uppercase color-green"><?= $first_letter ?></div>
            <? $brand_links["brand_letter_" . $first_letter] = $first_letter; ?>
        <ul class="brands-list row">
        <? endif; ?>
            <li class="col-lg-2 col-md-3 col-sm-4 col-xs-6" id="<?= $this->GetEditAreaId($arItem['ID']) ?>">
                <a href="<?= $arItem['DETAIL_PAGE_URL'] ?>">
                    <span>
                        <? $pic = "/images/project_no_img.jpg"; ?>
                        <? if (!empty($arItem['PREVIEW_PICTURE']['SRC']) && file_exists($_SERVER["DOCUMENT_ROOT"] . $arItem['PREVIEW_PICTURE']["SRC"])): ?>
                            <? $file = CFile::ResizeImageGet($arItem['PREVIEW_PICTURE']['ID'], array("width" => 160, "height" => 90), BX_RESIZE_IMAGE_PROPORTIONAL); ?>
                            <? $pic = $file["src"]; ?>
                        <? endif; ?>
                        
                        <img class="js-vertical-center" src="<?= $pic ?>" alt=""/>
                    </span>
                    <span><?= $arItem['NAME'] ?></span>
                </a>
            </li>
    <? endforeach; ?>
    </ul>
    <? $this->EndViewTarget(); ?>
    <? $this->SetViewTarget("brands-letters"); ?>
    <div class="letters">
        <? foreach ($brand_links as $href => $letter): ?>
            <a href="#<?= $href ?>"><?= $letter ?></a>
        <? endforeach;?>
    </div>
    <? $this->EndViewTarget(); ?>
<? endif; ?>