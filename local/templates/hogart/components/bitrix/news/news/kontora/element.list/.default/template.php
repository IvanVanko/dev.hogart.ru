<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
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
$page = $APPLICATION->GetCurDir();
?>

<div class="row">
    <div class="col-md-9">
        <h3><? $APPLICATION->ShowTitle() ?></h3>
        
        <? if(count($arResult["ITEMS"]) > 0): ?>
            <ul class="news-list">
                <? foreach($arResult["ITEMS"] as $arItem):
                    $this->AddEditAction($arItem["ID"], $arItem["EDIT_LINK"], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                    $this->AddDeleteAction($arItem["ID"], $arItem["DELETE_LINK"], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM'))); ?>
                    <? $date_from = FormatDate("d F Y", MakeTimeStamp($arItem["ACTIVE_FROM"])); ?>
                    <li id="<?=$this->GetEditAreaId($arItem['ID'])?>"
                        <? if(!empty($arItem['PREVIEW_PICTURE']['SRC']) && file_exists($_SERVER["DOCUMENT_ROOT"] . "/" . $arItem['PREVIEW_PICTURE']['SRC'])): ?> style="padding-left: 150px; position: relative" <? endif; ?>>
                        <? if(!empty($arItem['PREVIEW_PICTURE']['SRC']) && file_exists($_SERVER["DOCUMENT_ROOT"] . "/" . $arItem['PREVIEW_PICTURE']['SRC'])):
                            $file = CFile::ResizeImageGet($arItem['PREVIEW_PICTURE']['ID'], array('width' => 140,
                                'height' => 140), BX_RESIZE_IMAGE_EXACT, true);
                            ?>
                            <img style="position: absolute; left: 0; top: 15px; max-width: 140px" src="<?=$file['src'];?>"
                                 alt="<?=$arItem["NAME"]?>"/>
                        <? endif; ?>

                        <div class="date">
                            <sub><?=$date_from?></sub>
                        </div>
                        <?
                        global $USER;
                        if(!$USER->IsAuthorized() && $arItem['PROPERTIES']['need_reg']['VALUE'] == 'Y') {
                            ?>
                            <h4>
                                <a class="profile-url js-popup-open" href="javascript:" data-popup="#popup-login""><?=$arItem["NAME"]?></a>
                            </h4>
                            <p>Для прочтения необходима авторизация на сайте</p>
                            <?
                        }
                        else { ?>
                            <h4>
                                <a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><?=$arItem["NAME"]?></a>
                            </h4>
                            <p><?=$arItem["PREVIEW_TEXT"]?></p>
                        <? } ?>

                        <? foreach($arItem["PROPERTIES"]["tag"]["VALUE"] as $key => $tag): ?>
                            <div class="tag">
                                <a href="<?=$APPLICATION->GetCurPageParam("tag[".$arItem['PROPERTIES']['tag']['VALUE_ENUM_ID'][$key]."]=".$tag, array("tag"));?>">— <?=$tag?></a>
                            </div>
                        <? endforeach; ?>
                    </li>
                <? endforeach; ?>
            </ul>
        <? endif; ?>
        <?=$arResult["NAV_STRING"];?>
    </div>
    <div class="col-md-3 aside">
        <h3><?= GetMessage("Тип новости")?></h3>

        <form action="#" class="no-padding">
            <? foreach($arResult["FILTER"]["TAG"] as $key => $tag): ?>
                <div class="checkbox">
                    <label>
                        <input type="checkbox"
                               id="checkbox_<?=$key?>"
                               name="tag[<?=$tag["PROPERTY_TAG_VALUE_ENUM_ID"]?>]"
                               value="<?=$tag["PROPERTY_TAG_VALUE_VALUE"]?>"
                               onchange="this.form.submit()"
                            <? if(isset($_REQUEST["tag"][$tag["PROPERTY_TAG_VALUE_ENUM_ID"]])): ?> checked<? endif; ?>
                        > <?=$tag["PROPERTY_TAG_VALUE_VALUE"]?>
                    </label>
                </div>
            <? endforeach; ?>
        </form>
        <? if (LANGUAGE_ID != "en"): ?>
            <? /*$APPLICATION->IncludeComponent("kontora:element.list", "news_calendar", array(
                "IBLOCK_ID" => "3",
                "FILTER" => array(
                    array(
                        "LOGIC" => "OR",
                        array(">DATE_ACTIVE_TO" => date('d.m.Y H:i:s')),
                        array("DATE_ACTIVE_TO" => false),
                    ),
                ),
                "PROPS" => "Y",
                "CHECK_PERMISSIONS" => "Y",
            )); */ ?>
        <? endif; ?>
        <a href="#" class="js-popup-open" data-popup="#popup-subscribe-mod"><?= GetMessage("Подписаться на новости")?></a>
    </div>
</div>