<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
$date_from = FormatDate("d F", MakeTimeStamp($arResult["ACTIVE_FROM"]));
$date_to = !empty($arResult["DATE_ACTIVE_TO"]) ? FormatDate("d F", MakeTimeStamp($arResult["DATE_ACTIVE_TO"])) : FormatDate("d F", mktime(0, 0, 0, 1, 0, ((int)FormatDate("Y", MakeTimeStamp($arResult["ACTIVE_FROM"])) + 1)));

?>

<div class="inner">
    <div class="control control-action">
        <span class="prev <?if (isset($arResult['PREV'])):?> black <?endif;?>"><?if (!empty($arResult['PREV'])): ?><a href="<?=$arResult['PREV']?>"></a><? endif; ?></span>
        <span class="next <?if (isset($arResult['NEXT'])):?> black <?endif;?>"><?if (!empty($arResult['NEXT'])): ?><a href="<?=$arResult['NEXT']?>"></a><? endif; ?></span>
    </div>
    <h1><?=$arResult['NAME']?></h1>
    <ul class="action-list-one">
        <li>

                <div class="date">
                    <?=$date_from.' – '.$date_to?>
                    <?
                    $dateFinish = FormatDate("d.m.Y", MakeTimeStamp($arResult["DATE_ACTIVE_TO"]));
                    $now = date($DB->DateFormatToPHP(CSite::GetDateFormat("SHORT")), time());
                    if(strtotime($now) > strtotime($dateFinish)):?>
                        <strong>(Акция завершена)</strong>
                    <? endif; ?>
                </div>
                <p>
                    <?=$arResult['PREVIEW_TEXT']?>
                </p>

            <? $share_img_src = $arResult['DETAIL_PICTURE']['SRC']; ?>
            <img src="<?=$arResult['DETAIL_PICTURE']['SRC']?>" alt=""/>
            <br>
            <br>
                <?=$arResult['DETAIL_TEXT']?>
        </li>
    </ul>
    <br>
    <? $APPLICATION->IncludeFile(
        "/local/include/share.php",
        array(
            "TITLE" => $arResult["NAME"],
            "DESCRIPTION" => !empty($arResult["PREVIEW_TEXT"]) ? $arResult["PREVIEW_TEXT"] : $arResult["DETAIL_TEXT"],
            "LINK" => $APPLICATION->GetCurPage(),
            "IMAGE" => $share_img_src
        )
    ); ?>
    <? if(isset($arResult["this_goods"])): ?>
        <div class="catalog_page">
            <div class="products-similar-tabs">
                <h1>Товары, участвующие в акции <?=$arResult["NAME"]?></h1>
                <div class="items-similar">
                    <div id="tab-1" class="item-similar active" style="display: block;">
                        <? if(count($arResult["this_goods"]) > 3): ?>
                            <div id="con-4" class="controls">
                                <div class="prev"></div>
                                <div class="next"></div>
                            </div>
                        <? endif; ?>
                        <ul data-control="#con-4" class="js-slider-similar">
                            <? foreach($arResult["this_goods"] as $arProduct): ?>
                                <li>
		        	<span class="preview-img">
                        <? if(!empty($arProduct["PREVIEW_PICTURE"])) {
                            $file = CFile::ResizeImageGet($arProduct["PREVIEW_PICTURE"],
                                array('width' => 406, 'height' => 142), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true);
                            $file = $file['src'];
                        }
                        elseif(!empty($arProduct["PROPERTY_PHOTOS_VALUE"])) {
                            $file = CFile::ResizeImageGet($arProduct["PROPERTY_PHOTOS_VALUE"],
                                array('width' => 406, 'height' => 142), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true);
                            $file = $file['src'];
                        }
                        else {
                            $file = '/images/project_no_img.jpg';
                        } ?>
                        <a href="<?=$arProduct["DETAIL_PAGE_URL"]?>" style="background-image: url(<?=$file?>)"></a>
		        	</span>
                                    <a href="<?=$arProduct["DETAIL_PAGE_URL"]?>"><h3><?=$arProduct["NAME"]?></h3></a>
                                    <? if(!empty($arProduct["PROPERTY_SKU_VALUE"])): ?>
                                        <span
                                            class="art">Артикул: <span><?=$arProduct["PROPERTY_SKU_VALUE"]?></span></span>
                                    <? endif; ?>
                                    <div class="param">
                                        <div>
                                            <dl>
                                                <dt>Бренд</dt>
                                                <dd class="pr"><?=$arProduct['BRAND_NAME']?></dd>
                                            </dl>
                                            <?if(strlen($arProduct['COLLECTION_NAME']) > 0){?>
                                            <dl>
                                                <dt>Коллекция</dt>
                                                <dd class="pr"><?=$arProduct['COLLECTION_NAME']?></dd>
                                            </dl>
                                            <?}?>
                                        </div>
                                        <?=HogartHelpers::getAdjacentProductPropertyHtml($arProduct['ID'], $arProduct["SHOW_PROPS"], $arProduct["HIDDEN_PROPS"], array('brand',
                                                                                                                                                                       'photos',
                                                                                                                                                                       'sku',
                                                                                                                                                                       'collection'));?>
                                    </div>
                                    <div class="price currency-<?=strtolower($arProduct['CATALOG_CURRENCY_1'])?>">
                                        <?=HogartHelpers::wPrice($arProduct['PRICE'])?>
                                    </div>
                                </li>
                            <? endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    <? endif; ?>
    <a class="back_page icon-news-back" href="<?=$arParams['SEF_FOLDER']?>">Назад к акциям</a>
</div>
<!--form name="email" style="diapley:none;" action="/ajax/send_to_email.php"-->
<div class="strange-block hide-it">
    <input type="hidden" name="actionID" value="<?=$arResult['ID']?>"/>
    <? if($USER->IsAuthorized()): ?>
        <input type="hidden" name="user_mail" value="<?=$USER->GetEmail();?>"/>
    <? else: ?>
        <input type="text" name="user_mail" value=""/>
        <input type="submit" value="Отправить"/>
    <? endif; ?>
</div>
<!--/form-->

<aside class="sidebar js-fh js-fixed-block js-paralax-height" data-fixed="top">
    <div class="inner js-paralax-item">

        <?
        $date_stock_end = FormatDate("d.m.Y", MakeTimeStamp($arResult['DATE_ACTIVE_TO']));
        $date_stock_end = strtotime($date_stock_end);
        $date_stock_end = (!empty($date_stock_end)) ? $date_stock_end : 0;

        $now = date($DB->DateFormatToPHP(CSite::GetDateFormat("SHORT")), time());
        $now = strtotime($now);
        ?>
        <? // if ($arItem['PROPERTIES']['time']['VALUE'] != ''): ?>
        <? if($arResult['PROPERTIES']['need_reg']['VALUE'] == 'Y' && $date_stock_end > $now): ?>
            <script type="text/javascript">
                $(document).ready(function () {
                    $('.js-validation-empty.stock').hide();
                    $('.js-validation-empty.stock input').val('<?=$arResult['~NAME']?>');

                    setTimeout(function () {
                        $(window).resize();
                    }, 200);
                });
            </script>
            <div class="padding">
                <div class="preview-project-viewport">
                    <div class="preview-project-viewport-inner">
                        <?
                            global $MESS;
                            $MESS["FORM_NOTE_ADDOK"] = "Спасибо! Ваша заявка на участие в акции \"". $arResult['NAME'] ."\" принята.";
                        ?>
                        <? $APPLICATION->IncludeComponent(
                            "bitrix:form.result.new",
                            "sem_quest",
                            Array(
                                "WEB_FORM_ID" => "9",
                                "IGNORE_CUSTOM_TEMPLATE" => "N",
                                "USE_EXTENDED_ERRORS" => "N",
                                "SEF_MODE" => "N",
                                "VARIABLE_ALIASES" => Array("WEB_FORM_ID" => "WEB_FORM_ID", "RESULT_ID" => "RESULT_ID"),
                                "CACHE_TYPE" => "A",
                                "CACHE_TIME" => "3600",
                                "LIST_URL" => "",
                                "EDIT_URL" => "",
                                "SUCCESS_URL" => "",
                                "CHAIN_ITEM_TEXT" => "",
                                "CHAIN_ITEM_LINK" => "",
                                "ACTION_NAME" => $arResult['NAME']
                            ), $component
                        ); ?>
                    </div>
                </div>
            </div>
        <? else: ?>
            <? $APPLICATION->IncludeComponent(
                "kontora:element.list",
                "stock_detail",
                Array(
                    "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                    "PROPS" => "Y",
                    "ELEMENT_COUNT" => "3",
                    //                "FILTER" => array('!ID' => $ElementID),
                    "FILTER" => array('!ID' => $arResult['ID']),
                    'SEF_FOLDER' => $arParams['SEF_FOLDER'],
                )
            ); ?>


        <? endif; ?>
        <div class="side_href">
            <a href="#" class="icon-email js-popup-open" data-popup="#popup-subscribe-email">Отправить на e-mail</a>
            <a href="#" onclick="window.print(); return false;" class="icon-print">Распечатать</a>
            <a href="#" class="icon-phone js-popup-open" data-popup="#popup-subscribe-phone">Отправить SMS</a>
        </div>
    </div>
</aside>