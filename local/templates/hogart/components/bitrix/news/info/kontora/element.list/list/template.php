<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?foreach ($arResult["ITEMS"] as $arItem):
    $this->AddEditAction($arItem["ID"], $arItem["EDIT_LINK"], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
    $this->AddDeleteAction($arItem["ID"], $arItem["DELETE_LINK"], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));?>
    <div class="news-item">
        <a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="small_header small_header-dop">
            <div class="date">
                <?=CIBlockFormatProperties::DateFormat('j F Y', MakeTimeStamp($arItem["ACTIVE_FROM"], CSite::GetDateFormat()))?>
            </div>
            <h4><?=$arItem['NAME']?></h4>
            <p><?=$arItem['PREVIEW_TEXT']?></p>
        </a>
    </div>
<?endforeach;?>