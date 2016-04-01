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
$this->setFrameMode(true);
?>
<? if(!empty($arResult["ITEMS"])): ?>
    <ul class="perechen-produts">
        <? foreach($arResult["ITEMS"] as $arItem): ?>
            <li>
                <div>
                    <a href="<?=$arItem["DETAIL_PAGE_URL"]?>" target="_blank">
                        <? if(!empty($arItem["PREVIEW_PICTURE"]['SRC'])) {
                            $file = CFile::ResizeImageGet($arItem['PREVIEW_PICTURE']['ID'], array('width' => 290,
                                                                                                  'height' => 250), BX_RESIZE_IMAGE_PROPORTIONAL, true);
                            ?>
                            <img data-big-img="<?=$arItem["PREVIEW_PICTURE"]['SRC']?>"
                                 src="<?=$file['src']?>" alt=""/>
                        <?
                        }
                        elseif(!empty($arItem["DETAIL_PICTURE"]['SRC'])) {
                            $file = CFile::ResizeImageGet($arItem['DETAIL_PICTURE']['ID'], array('width' => 290,
                                                                                                 'height' => 250), BX_RESIZE_IMAGE_PROPORTIONAL, true);
                            ?>
                            <img data-big-img="<?=$arItem["DETAIL_PICTURE"]['SRC']?>"
                                 src="<?=$file['src']?>" alt=""/>
                        <?
                        }
                        else { ?>
                            <img src="/images/no-img-big-search.jpg" alt=""/>
                        <? } ?>
                    </a>
                    <a href="<?=$arItem["DETAIL_PAGE_URL"]?>" target="_blank"><h3><?=TruncateText($arItem["NAME"], 105)?></h3></a>
<!--                    <ul class="param">-->
<!--                        --><?// foreach($arItem["PROPERTIES"] as $propertyName => $arProperty): ?>
<!--                            --><?// if(substr($propertyName, 0, 3) == "pr_" && !empty($arProperty["VALUE"])): ?>
<!--                                <li><span>--><?//=$arProperty["NAME"]?><!--</span><span-->
<!--                                        class="pr">--><?//=$arProperty["VALUE"]?><!--</span></li>-->
<!--                            --><?// endif; ?>
<!--                        --><?// endforeach; ?>
<!--                    </ul>-->
                    <div class="price currency-<?=strtolower($arItem['CATALOG_CURRENCY_1'])?>">
                        <?=HogartHelpers::wPrice($arItem['PRICE'])?>
                    </div>
                </div>
            </li>
        <? endforeach; ?>
    </ul>
    <div class="text-center">
        <? echo $arResult["NAV_STRING"]; ?>
    </div>
<? else: ?>
    <p><font class="notetext">По вашему запросу ничего не найдено, позвоните по телефону +7 (495) 788-11-12, +7 (812)
            703-41-14 мы постараемся решить вашу задачу.</font></p>
<? endif; ?>