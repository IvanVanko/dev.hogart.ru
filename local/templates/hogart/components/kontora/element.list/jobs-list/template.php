<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<h3>Вакансии</h3>
<?if (count($arResult['ITEMS']) > 0):?>
    <ul class="job-list">
        <?foreach ($arResult['ITEMS'] as $arItem):?>
            <li><a href="<?=$arItem['DETAIL_PAGE_URL']?>"><?=$arItem['NAME']?></a></li>
        <?endforeach;?>
    </ul>
<?endif;?>
