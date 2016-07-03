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

        <div class="side_href">
            <div>
                <i class="fa fa-envelope" aria-hidden="true"></i><a href="#" class="js-popup-open" data-popup="#popup-subscribe"><?= GetMessage("Отправить на e-mail")?></a>
            </div>
            <div>
                <i class="fa fa-print" aria-hidden="true"></i><a href="#" onclick="window.print(); return false;"><?= GetMessage("Распечатать")?></a>
            </div>
            <div>
                <i class="fa fa-mobile" aria-hidden="true"></i><a href="#" class="js-popup-open" data-popup="#popup-subscribe-phone"><?= GetMessage("Отправить SMS")?></a>
            </div>
        </div>
    </div>
<? endif; ?>