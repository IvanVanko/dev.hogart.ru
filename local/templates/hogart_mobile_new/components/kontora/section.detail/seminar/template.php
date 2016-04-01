<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<div class="control control-action">

    <? /* if (isset($arResult['PREV'])):?>
        <span class="prev black"><a href="<?=$arResult['PREV']?>"></a></span>
    <?endif;?>
    <?if (isset($arResult['NEXT'])):?>
        <span class="next black"><a href="<?=$arResult['NEXT']?>"></a></span>
    <?endif; */ ?>
    <span class="prev <?if (isset($arResult['PREV'])):?> black <?endif;?>"><a href="<?=$arResult['PREV']?>"></a></span>
    <span class="next <?if (isset($arResult['NEXT'])):?> black <?endif;?>"><a href="<?=$arResult['NEXT']?>"></a></span>
</div>
<h1><?= $arResult["NAME"] ?></h1>
<div class="img-title" style="height: 362px;">
    <img src="<?= $arResult["DETAIL_PICTURE"]['SRC'] ?>" style="position: absolute;max-width: 100%;display: block;"
         alt=""/>

    <div class="date">
        <?
        $start = $arResult["PROPERTIES"]["time"]["VALUE"];
        $end = $arResult["PROPERTIES"]["end_time"]["VALUE"];
        $start = explode('/', $start);
        $end = explode('/', $end);
        $month = array(
            '01' => 'января',
            '02' => 'февраля',
            '03' => 'марта',
            '04' => 'апреля',
            '05' => 'мая',
            '06' => 'июня',
            '07' => 'июля',
            '08' => 'августа',
            '09' => 'сентября',
            '10' => 'октября',
            '11' => 'ноября',
            '12' => 'декабря',
        );
        ?>
        <sup>с <?= $start[1]; ?><? ($start[1] != $end[1]) ? $month[$start[0]] : ''; ?>
            по <?= $end[1] ?> <?= $month[$end[0]]; ?></sup>
        <span>/</span>
        <sub><?= $end[2] ?> Г.</sub>
    </div>
</div>
<h2>Программа семинара</h2>
<!--    --><? //var_dump($arResult["PROPERTIES"]["program_txt"]);?>
<?= $arResult["PROPERTIES"]["program_txt"]["~VALUE"]['TEXT']; ?>

<!--    program_txt-->
<div class="clearfix">
    <h2 class="display-inline-block"><?= $arResult["PROPERTIES"]["lecturer"]["NAME"]; ?></h2>
    <? if (count($arResult['LECTORS']) > 2): ?>
        <div class="control control-action null-margin">
            <span class="prev black"><a href="#" id="prev"></a></span>
            <span class="next black"><a href="#" id="next"></a></span>
        </div>
    <? endif; ?>
</div>

<? if (count($arResult['LECTORS']) > 2): ?>
<ul class="learn-people-list js-normal-slider" data-next="#next" data-prev="#prev">
    <? else: ?>
    <ul class="learn-people-list">
        <? endif; ?>
        <? foreach ($arResult['LECTORS'] as $key => $arItem): ?>
            <li>
                <img src="<?= CFile::GetPath($arItem['PREVIEW_PICTURE']); ?>" alt=""/>

                <h3><?= $arItem['NAME']; ?></h3>
                <span class="head"><?= $arItem['props']['company']['VALUE']; ?></span>
                <span>–</span>
                <i><?= $arItem['props']['status']['VALUE']; ?></i>
            </li>
        <? endforeach; ?>
    </ul>



