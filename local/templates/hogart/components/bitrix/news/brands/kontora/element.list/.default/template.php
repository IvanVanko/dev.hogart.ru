<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
if (count($arResult["ITEMS"]) > 0):?>
    <? $brand_links = []; ?>
    <? $first_letter = null; ?>
    <? $this->SetViewTarget("brands-list"); ?>
    <ul class="brands-list">
        <? foreach ($arResult["ITEMS"] as $arItem):
            $this->AddEditAction($arItem["ID"], $arItem["EDIT_LINK"], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
            $this->AddDeleteAction($arItem["ID"], $arItem["DELETE_LINK"], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM'))); ?>
            <? $date = explode(".", $arItem["ACTIVE_FROM"]); ?>
            <? if ($first_letter != mb_substr($arItem['NAME'], 0, 1)): ?>
                <? $first_letter = mb_substr($arItem['NAME'], 0, 1); ?>
                <i id="<?= ("brand_letter_" . $first_letter) ?>"></i>
                <? $brand_links["brand_letter_" . $first_letter] = $first_letter; ?>
            <? endif; ?>
            <?  ?>
            <li id="<?= $this->GetEditAreaId($arItem['ID']) ?>">
                <a href="<?= $arItem['DETAIL_PAGE_URL'] ?>">
                    <span>
                        <? $file = CFile::ResizeImageGet($arItem['PREVIEW_PICTURE']['ID'], array("width" => 160, "height" => 90), BX_RESIZE_IMAGE_EXACT); ?>
                        <img class="js-vertical-center" src="<?= $arItem['PREVIEW_PICTURE']['SRC'] ?>" alt=""/>
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