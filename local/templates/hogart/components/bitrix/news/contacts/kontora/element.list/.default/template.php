<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die(); 
$mainItem = array();
?>
<div class="row">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-6">
                <h3><?$APPLICATION->ShowTitle()?></h3>
                <?if (count($arResult['ITEMS']) > 0):?>
                    <ul class="contacts-list">
                        <?foreach ($arResult['ITEMS'] as $key => $arItem):
                            if ($arItem['PROPERTIES']['main']['VALUE'] == 'Y')
                                $mainItem = $arItem;?>
                            <li class="icon-pin">
                                <a href="<?=$arItem['DETAIL_PAGE_URL']?>"><?=$arItem['NAME']?></a>
                            </li>
                        <?endforeach;?>
                    </ul>
                <?endif;?>
            </div>
            <div class="col-md-6 address-cnt">
                <h3><?=$mainItem['NAME']?></h3>
                <address>
                    <?=$mainItem['PROPERTIES']['adress']['VALUE']?><br>
                    <?if (!empty($mainItem['PROPERTIES']['phone']['VALUE'])):?>
                        тел.: <?=implode(', ', $mainItem['PROPERTIES']['phone']['VALUE'])?><br>
                    <?endif;
                    if (!empty($mainItem['PROPERTIES']['mail']['VALUE'])):?>
                        e-mail: <?=implode(', ', $mainItem['PROPERTIES']['mail']['VALUE'])?><br>
                    <?endif;?>
                </address>
            </div>
        </div>
        <?$coords = explode(',', $mainItem['PROPERTIES']['map']['VALUE']);?>
        <div class="contact-map inner" id="map" data-lat="<?=$coords[0]?>" data-long="<?=$coords[1]?>">
        </div>

        <p class="head icon-car"><?= GetMessage("Проезд на автомобиле") ?>:</p>

        <p>
            <?= $mainItem['PROPERTIES']['by_car']['~VALUE']['TEXT'] ?>
        </p>

        <p class="head icon-bus"><?= GetMessage("Проезд общественным транспортом") ?>:</p>
        <p>
            <?= $mainItem['PROPERTIES']['by_public']['~VALUE']['TEXT'] ?>
        </p>
        
    </div>
</div>

