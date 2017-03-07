<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
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
<? if (!empty($arResult['ITEMS'])): ?>
    <ul class="list-base-learn">
        <? foreach ($arResult['ITEMS'] as $key => $arItem): ?>
            <?
            $date_sem_start = FormatDate("d.m.Y", MakeTimeStamp($arItem['PROPERTIES']['sem_start_date']['VALUE']));
            $date_sem_start = strtotime($date_sem_start);
            $date_sem_start =(!empty($date_sem_start))?$date_sem_start:0;

            $now=date($DB->DateFormatToPHP(CSite::GetDateFormat("SHORT")), time());
            $now=strtotime($now);
            ?>
            <?if ($date_sem_start > $now):?>
                <li>
                    <?//$file = CFile::ResizeImageGet($arItem['PREVIEW_PICTURE']['ID'], array("width" => 101, "height" => 101), BX_RESIZE_IMAGE_EXACT);?>
                   
                    <time class="top-time">
                        <? if ($arItem['PROPERTIES']['sem_start_date']['VALUE'] != ''): ?>
                            <? $date_from = FormatDate("d.m.Y", MakeTimeStamp($arItem['PROPERTIES']['sem_start_date']['VALUE'])); ?>
                            <?= $date_from; ?>
                        <? endif; ?>
                        <?= $arItem['PROPERTIES']['time']['VALUE']; ?>
                    </time>
                    <a href="<?= $arItem['DETAIL_PAGE_URL']; ?>"><h2><?= $arItem['NAME']; ?></h2></a>

                    <div class="preview-txt">
                        <?
                        $obParser = new CTextParser;
                        $arItem["PREVIEW_TEXT"] = $obParser->html_cut($arItem["PREVIEW_TEXT"], 400);
                        ?>
                        <?= $arItem['PREVIEW_TEXT']; ?>

                    </div>
                    <time>
                        <?/*— Начало
                        <? if ($arItem['PROPERTIES']['sem_start_date']['VALUE'] != ''): ?>
                            <? $date_from = FormatDate("d.m.Y", MakeTimeStamp($arItem['PROPERTIES']['sem_start_date']['VALUE'])); ?>
                            <?= $date_from; ?>
                        <? endif; ?>
                        <?= $arItem['PROPERTIES']['time']['VALUE']; ?>
                        */?>
                        <?/* if ($arItem['PROPERTIES']['sem_end_date']['VALUE'] != ''): ?>
                            <br>— Конец
                            <? $date_from = FormatDate("d.m.Y", MakeTimeStamp($arItem['PROPERTIES']['sem_end_date']['VALUE'])); ?>
                            <?= $date_from; ?>
                        <? endif; */?>
                        <!--8 ноября 2015 г.--></time>
                    <br/>
                    <time>— <?=$arItem['PROPERTIES']['adress']['VALUE'];?></time>
                </li>
            <? endif; ?>
        <? endforeach; ?>
    </ul>
<? endif; ?>
