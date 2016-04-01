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
<?if (!empty($arResult['ITEMS'])):?>
    <ul class="list-base-learn">
        <? foreach ($arResult['ITEMS'] as $key => $arItem):?>
            <?if ($arItem['PROPERTIES']['time']['VALUE']!=''):?>
                <li>
                    <img src="<?=$arItem['PREVIEW_PICTURE']['SRC'];?>" alt=""/>
                    <a href="<?=$arItem['DETAIL_PAGE_URL'];?>"><h2><?=$arItem['NAME'];?></h2></a>
                    <p><?=$arItem['PREVIEW_TEXT'];?></p>
                    <time>— <?=$arItem['PROPERTIES']['time']['VALUE'];?><!--8 ноября 2015 г.--></time>
                </li>
            <?endif;?>
        <?endforeach;?>
    </ul>
<?endif;?>
