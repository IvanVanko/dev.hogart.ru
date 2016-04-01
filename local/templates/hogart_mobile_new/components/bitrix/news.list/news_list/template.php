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

if (count($arResult['ITEMS']) > 0):?>
    <div class="sidebar_padding_cnt <?=$arParams["WRAPPER_CSS"]?>">
        <?foreach ($arResult["ITEMS"] as $arItem): //Цикл по всем элементам
            $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
            $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));?>
            <?if ($arParams["SHOW_PERIOD"] == "Y") {?>
                <?$date_from = FormatDate("d F", MakeTimeStamp($arItem["ACTIVE_FROM"]));
                $date_to = FormatDate("d F", MakeTimeStamp($arItem["ACTIVE_TO"]));?>
                <div class="date_small">
                    <?=$date_from.' – '.$date_to?>
                </div>
            <?} else {?>
                <?$date = explode('.', $arItem['ACTIVE_FROM']);?>
                <?$date_from = FormatDate("d F Y", MakeTimeStamp($arItem["ACTIVE_FROM"]));?>
                <div class="date new-date">
                    <div><?=$date_from?></div>
                </div>
            <?}?>
            <div id="<?=$this->GetEditAreaId($arItem['ID'])?>" class="small_news">
                <p><a href="<?=$arItem['DETAIL_PAGE_URL']?>"><?=$arItem['NAME']?></a></p>
            </div>
        <?endforeach;?>
        <?=$arParams["~DISPLAY_LINK_HTML"]?>
    </div>
<?endif; ?>