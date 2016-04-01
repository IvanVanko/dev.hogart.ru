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
//var_dump($arResult['ITEMS']);
?>
<?//var_dump($arResult['ITEMS']);?>
<?if (!empty($arResult['ITEMS'])):?>
    <ul class="list-base-learn">
        <? foreach ($arResult['ITEMS'] as $key => $arItem):?>
            <li>
                <img src="<?=$arItem['PREVIEW_PICTURE']['SRC'];?>" alt=""/>
                <a href="<?=$arItem['DETAIL_PAGE_URL'];?>"><h2><?=$arItem['NAME'];?></h2></a>
<!--                <p>--><?//=$arItem['PREVIEW_TEXT'];?><!--</p>-->
                <div class="list-base-learn-text-box">
                    <?
                    $obParser = new CTextParser;
                    $arItem["DETAIL_TEXT"] = $obParser->html_cut($arItem["DETAIL_TEXT"], 400);
                    ?>
                    <?=$arItem["DETAIL_TEXT"];?>
                </div>

                <?if ($arItem['PROPERTIES']['time']['VALUE']!=''):?>
                    <time>— <?=$arItem['PROPERTIES']['time']['VALUE'];?><!--8 ноября 2015 г.--></time>
                <?else:?>
                    <time>— Место проведения: </time><span>по запросу</span>
                <?endif;?>
            </li>

        <?endforeach;?>
    </ul>
<?endif;?>
