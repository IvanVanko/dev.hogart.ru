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

    <ul class="js-dateArray" id="calendar-array">

        <? foreach ($arResult['ITEMS'] as $key => $arItem):?>
            <?if ($arItem['PROPERTIES']['sem_start_date']['VALUE']!=''):?>
		        <?$date = explode('.', $arItem['PROPERTIES']['sem_start_date']['VALUE']);?>
		        <?$date_from = FormatDate("m/d/Y", MakeTimeStamp($arItem['PROPERTIES']['sem_start_date']['VALUE']));?>
                <?
                /*$date_sem_start = FormatDate("d.m.Y", MakeTimeStamp($arItem['PROPERTIES']['sem_start_date']['VALUE']));
                $date_sem_start = strtotime($date_sem_start);
                $date_sem_start =(!empty($date_sem_start))?$date_sem_start:0;

                $now=date($DB->DateFormatToPHP(CSite::GetDateFormat("SHORT")), time());
                $now=strtotime($now);*/
                ?>
                <?//=$date_sem_start =$now;?>
                <li data-date="<?=$date_from;?>">
                    <a href="<?=$arItem['DETAIL_PAGE_URL'];?>">
                        <h3><?=$arItem['NAME'];?></h3>
                    </a>
                    <br/>
                    <?
                    $obParser = new CTextParser;
                    $arItem["PREVIEW_TEXT"] = $obParser->html_cut($arItem["PREVIEW_TEXT"], 200);
                    ?>
                    <?=$arItem["PREVIEW_TEXT"];?>
                    <?//=$arItem['PREVIEW_TEXT'];?>
                </li>
            <?endif;?>

        <?endforeach;?>
    </ul>
<?endif;?>
