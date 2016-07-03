<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<? if (count($arResult['ITEMS'] > 0)): ?>
    <div class="col-md-3 aside">
        <ul class="contacts-list">
            <?foreach ($arResult['ITEMS'] as $arItem):?>
                <li class="<?if ($arItem['ID'] == $arParams['CURRENT_ID']):?> active<?endif;?>">
                    <i class="fa fa-map-marker" aria-hidden="true"></i>
                    <a href="<?=$arItem['DETAIL_PAGE_URL']?>">
                        <?=$arItem['NAME']?>
                    </a>
                </li>
            <?endforeach;?>
        </ul>
    </div>
<? endif; ?>