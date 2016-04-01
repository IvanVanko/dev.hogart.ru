<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<aside class="sidebar js-fh js-fixed-block js-paralax-height" data-fixed="top">
    <div class="inner js-paralax-item">
        <div class="padding">
            <h2>Вакансии</h2>
            <?if (count($arResult['ITEMS']) > 0):?>
	            <ul class="history-vac">
	                <?foreach ($arResult['ITEMS'] as $arItem):?>
	                	<li><a href="<?=$arItem['DETAIL_PAGE_URL']?>"><?=$arItem['NAME']?></a></li>
	                <?endforeach;?>
	            </ul>
            <?endif;?>
        </div>
    </div>
</aside>