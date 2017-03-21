<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<? if (count($arResult['ITEMS'] > 0)): ?>
    <div class="col-md-3 col-xs-12 aside aside-mobile">
        <ul class="contacts-list">
            <?foreach ($arResult['ITEMS'] as $arItem):?>
                <li class="<?if ($arItem['ID'] == $arParams['CURRENT_ID']):?> active<?endif;?>">
                    <a href="<?=$arItem['DETAIL_PAGE_URL']?>">
                        <i class="fa fa-map-marker" aria-hidden="true"></i>
                        <?=$arItem['NAME']?>
                    </a>
                </li>
            <?endforeach;?>
        </ul>
    </div>
<? endif; ?>