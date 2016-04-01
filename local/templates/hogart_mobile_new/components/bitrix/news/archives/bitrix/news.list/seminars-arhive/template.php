<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?$page = $APPLICATION->GetCurDir(true);?>
<?if (count($arResult['ITEMS']) > 0 && is_array($arResult['ITEMS'])):?>
    <div class="place-event-content event-content">
        <?foreach($arResult['ITEMS'] as $k=>$arItem):?>
            <?
         #   DebugMessage( $arItem);
                $date_sem_start = FormatDate("d.m.Y", MakeTimeStamp($arItem['PROPERTIES']['sem_start_date']['VALUE']));
                $date_sem_start = strtotime($date_sem_start);
                $date_sem_start =(!empty($date_sem_start))?$date_sem_start:0;

                $now=date($DB->DateFormatToPHP(CSite::GetDateFormat("SHORT")), time());
                $now=strtotime($now);
            ?>      
            <div class="events">
                
            
                    <div class="event" data-date="<?=$date_start?>"  data-is_last_day="<?=$last?>">
                        <?
                        #osobennosti-montazha-nastroyki-i-obsluzhivaniya-vodogrevatelnykh-kotlov-de-dietrich613
                        ?>
                    <p class="title"><a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><?=$arItem["NAME"]?></a></p>
                    <? if ($arItem['PROPERTIES']['sem_start_date']['VALUE'] != ''): ?>
                        <? $date_from = FormatDate("d.m.Y", MakeTimeStamp($arItem['PROPERTIES']['sem_start_date']['VALUE'])); ?>
                        <time datetime="<?= $date_from; ?>"><?= $date_from; ?></time>
                    <? endif; ?>

                    <?
                    $obParser = new CTextParser;
                    $arItem["PREVIEW_TEXT"] = $obParser->html_cut($arItem["PREVIEW_TEXT"], 400);
                    ?>
                    <?= $arItem['PREVIEW_TEXT']; ?>
                  <p class="place_wrap">Место проведения: <span class="place">по запросу</span></p>
            </div>
        <?endforeach;?>
    </div>
<?=$arResult["NAV_STRING"];?>
<?endif;?>

<a href="/learning/" class="btn link-btn arrow-icon">Календарь семинаров</a>
<a href="/learning/zapis-na-seminary-s-otkrytoy-datoy/" class="btn link-btn arrow-icon wide">Запись на семинары с открытой датой</a>

<?
#DebugMessage($arResult);
?>
