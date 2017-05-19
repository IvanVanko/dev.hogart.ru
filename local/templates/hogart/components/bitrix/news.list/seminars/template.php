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
?>
<?if (!empty($arResult['ITEMS'])):?>

    <ul class="js-dateArray" id="calendar-array">

        <? foreach ($arResult['ITEMS'] as $key => $arItem):?>
            <?if ($arItem['PROPERTIES']['sem_start_date']['VALUE']!=''):?>
		        <?$date = explode('.', $arItem['PROPERTIES']['sem_start_date']['VALUE']);?>
		        <?$date_from = FormatDate("m/d/Y", MakeTimeStamp($arItem['PROPERTIES']['sem_start_date']['VALUE']));?>
                <li data-date="<?=$date_from;?>">
                    <a href="<?=$arItem['DETAIL_PAGE_URL'];?>">
                        <h4><?=$arItem['NAME'];?></h4>
                    </a>
                    <br/>
                    <?
                    $obParser = new CTextParser;
                    $arItem["PREVIEW_TEXT"] = $obParser->html_cut($arItem["PREVIEW_TEXT"], 200);
                    ?>
                    <a href="<?= $arItem['DETAIL_PAGE_URL']; ?>">
                        <?=$arItem["PREVIEW_TEXT"];?>
                    </a>

                </li>
            <?endif;?>

        <?endforeach;?>
    </ul>
<?endif;?>
