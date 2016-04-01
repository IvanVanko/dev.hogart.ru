<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
if (count($arResult['ITEMS'] > 0)):?>
    <aside class="sidebar js-fh js-fixed-block js-paralax-height" data-fixed="top">
        <div class="inner js-paralax-item">
            <ul class="contacts-list">
                <?foreach ($arResult['ITEMS'] as $arItem):?>
                    <li class="icon-pin white<?if ($arItem['ID'] == $arParams['CURRENT_ID']):?> active<?endif;?>">
                        <a href="<?=$arItem['DETAIL_PAGE_URL']?>"><?=$arItem['NAME']?></a>
                    </li>
                <?endforeach;?>
            </ul>
        </div>
        <div class="action_page">
            <div class="side_href">
                <a href="#" class="icon-email js-popup-open" data-popup="#popup-subscribe">Отправить на e-mail</a>
                <a href="#" onclick="window.print(); return false;" class="icon-print">Распечатать</a>
                <a href="#" class="icon-phone js-popup-open" data-popup="#popup-subscribe-phone">Отправить SMS</a>
            </div>
        </div>
    </aside>
<?endif;?>