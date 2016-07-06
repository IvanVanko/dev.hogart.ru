<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
$date_from = FormatDate("d F", MakeTimeStamp($arResult["ACTIVE_FROM"]));
$date_to = !empty($arResult["DATE_ACTIVE_TO"]) ? FormatDate("d F", MakeTimeStamp($arResult["DATE_ACTIVE_TO"])) : FormatDate("d F", mktime(0, 0, 0, 1, 0, ((int)FormatDate("Y", MakeTimeStamp($arResult["ACTIVE_FROM"])) + 1)));
?>

<div class="row">
    <div class="col-md-9">
        <div class="row vertical-align">
            <div class="col-md-10">
                <h3><?= $arResult['NAME'] ?></h3>
                <div class="controls text-right">
                    <? if (!empty($arResult["PREV"])): ?>
                        <div class="prev">
                            <a href="<?= $arResult["PREV"] ?>">
                                <i class="fa fa-arrow-circle-o-left"></i>
                            </a>
                        </div>
                    <? endif; ?>
                    <? if (!empty($arResult["NEXT"])): ?>
                        <div class="next">
                            <a href="<?= $arResult["NEXT"] ?>">
                                <i class="fa fa-arrow-circle-o-right"></i>
                            </a>
                        </div>
                    <? endif; ?>
                </div>
            </div>
            <div class="col-md-2 text-right">
                <div class="hogart-share text-right">
                    <a data-toggle="tooltip" data-placement="top" href="#" class="js-popup-open"
                       data-popup="#popup-subscribe" title="<?= GetMessage("Отправить на e-mail") ?>"><i
                            class="fa fa-envelope" aria-hidden="true"></i></a>
                    <a data-toggle="tooltip" data-placement="top" href="#" onclick="window.print(); return false;"
                       title="<?= GetMessage("Распечатать") ?>"><i class="fa fa-print" aria-hidden="true"></i></a>
                    <a data-toggle="tooltip" data-placement="top" href="#" class="js-popup-open"
                       data-popup="#popup-subscribe-phone" title="<?= GetMessage("Отправить SMS") ?>"><i
                            class="fa fa-mobile" aria-hidden="true"></i></a>
                </div>
            </div>
        </div>

        <ul class="action-list-one">
            <li>
                <div class="date">
                    <?= $date_from . ' – ' . $date_to ?>
                    <?
                    $dateFinish = FormatDate("d.m.Y", MakeTimeStamp($arResult["DATE_ACTIVE_TO"]));
                    $now = date($DB->DateFormatToPHP(CSite::GetDateFormat("SHORT")), time());
                    if (strtotime($now) > strtotime($dateFinish)):?>
                        <strong>(Акция завершена)</strong>
                    <? endif; ?>
                </div>
                <p>
                    <?= $arResult['PREVIEW_TEXT'] ?>
                </p>

                <? $share_img_src = $arResult['DETAIL_PICTURE']['SRC']; ?>
                <img src="<?= $arResult['DETAIL_PICTURE']['SRC'] ?>" alt=""/>
                <br>
                <br>
                <?= $arResult['DETAIL_TEXT'] ?>
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
        <? if (!empty($arResult["PROPERTIES"]["ORG"]["VALUE"])): ?>
            <h3>По всем вопросам вы можете обратиться:</h3>
            <?
            $res = CIBlockElement::GetList(Array(), ["ID" => $arResult["PROPERTIES"]["ORG"]["VALUE"]], false, false, array());
            $orgs = [];
            ?>
            <ul class="organizers">
                <? while ($ob = $res->GetNextElement()): ?>
                    <?
                    $org = $ob->GetFields();
                    $org['props'] = $ob->GetProperties();
                    $orgs[] = $org;
                    $picture = "";
                    if (!empty($org["PREVIEW_PICTURE"])) {
                        $picture = \CFile::ResizeImageGet($org["PREVIEW_PICTURE"], array('height' => 80), BX_RESIZE_IMAGE_EXACT, true);
                    }
                    ?>
                    <li class="organizer-item">
                        <? if (!empty($picture)): ?>
                            <img height="80" src="<?= $picture["src"] ?>" alt="">
                        <? endif; ?>
                        <span class="title">
                    <?= $org["NAME"] ?>
                </span>
                <span class="status">
                    <?= $org["props"]["status"]["VALUE"] ?>
                </span>
                <span class="phone">
                    <?= $org["props"]["phone"]["VALUE"] ?>
                </span>
                <span class="email">
                    <a href="mailto:<?= $org["props"]["mail"]["VALUE"] ?>"><?= $org["props"]["mail"]["VALUE"] ?></a>
                </span>
                    </li>
                <? endwhile; ?>
            </ul>
        <? endif; ?>
        <? if (isset($arResult["this_goods"])): ?>
            <div class="catalog_page">
                <div class="products-similar-tabs">
                    <h1>Товары, участвующие в акции "<?= $arResult["NAME"] ?>"</h1>
                    <div class="items-similar">
                        <div id="tab-1" class="item-similar active" style="display: block;">
                            <? if (count($arResult["this_goods"]) > 3): ?>
                                <div id="con-4" class="controls">
                                    <div class="prev"></div>
                                    <div class="next"></div>
                                </div>
                            <? endif; ?>
                            <ul data-control="#con-4" class="js-slider-similar">
                                <? foreach ($arResult["this_goods"] as $arProduct): ?>
                                    <li>
		        	<span class="preview-img">
                        <? if (!empty($arProduct["PREVIEW_PICTURE"])) {
                            $file = CFile::ResizeImageGet($arProduct["PREVIEW_PICTURE"],
                                array('width' => 406, 'height' => 142), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true);
                            $file = $file['src'];
                        } elseif (!empty($arProduct["PROPERTY_PHOTOS_VALUE"])) {
                            $file = CFile::ResizeImageGet($arProduct["PROPERTY_PHOTOS_VALUE"],
                                array('width' => 406, 'height' => 142), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true);
                            $file = $file['src'];
                        } else {
                            $file = '/images/project_no_img.jpg';
                        } ?>
                        <a href="<?= $arProduct["DETAIL_PAGE_URL"] ?>" style="background-image: url(<?= $file ?>)"></a>
		        	</span>
                                        <a href="<?= $arProduct["DETAIL_PAGE_URL"] ?>">
                                            <h3><?= $arProduct["NAME"] ?></h3></a>
                                        <? if (!empty($arProduct["PROPERTY_SKU_VALUE"])): ?>
                                            <span
                                                class="art">Артикул: <span><?= $arProduct["PROPERTY_SKU_VALUE"] ?></span></span>
                                        <? endif; ?>
                                        <div class="param">
                                            <div>
                                                <dl>
                                                    <dt>Бренд</dt>
                                                    <dd class="pr"><?= $arProduct['BRAND_NAME'] ?></dd>
                                                </dl>
                                                <? if (strlen($arProduct['COLLECTION_NAME']) > 0) { ?>
                                                    <dl>
                                                        <dt>Коллекция</dt>
                                                        <dd class="pr"><?= $arProduct['COLLECTION_NAME'] ?></dd>
                                                    </dl>
                                                <? } ?>
                                            </div>
                                            <?= HogartHelpers::getAdjacentProductPropertyHtml($arProduct['ID'], $arProduct["SHOW_PROPS"], $arProduct["HIDDEN_PROPS"], array('brand',
                                                'photos',
                                                'sku',
                                                'collection')); ?>
                                        </div>
                                        <div class="price currency-<?= strtolower($arProduct['CATALOG_CURRENCY_1']) ?>">
                                            <?= HogartHelpers::wPrice($arProduct['PRICE']) ?>
                                        </div>
                                    </li>
                                <? endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        <? endif; ?>
        <a class="back_page icon-news-back" href="<?= $arParams['SEF_FOLDER'] ?>">Назад к акциям</a>
    </div>
    <div class="col-md-3 aside">
        <?
        $date_stock_end = FormatDate("d.m.Y", MakeTimeStamp($arResult['DATE_ACTIVE_TO']));
        $date_stock_end = strtotime($date_stock_end);
        $date_stock_end = (!empty($date_stock_end)) ? $date_stock_end : 0;

        $now = date($DB->DateFormatToPHP(CSite::GetDateFormat("SHORT")), time());
        $now = strtotime($now);
        ?>
        <? if ($arResult['PROPERTIES']['need_reg']['VALUE'] == 'Y' && $date_stock_end > $now): ?>
            <script type="text/javascript">
                $(document).ready(function () {
                    $('.js-validation-empty.stock').hide();
                    $('.js-validation-empty.stock input').val('<?=$arResult['~NAME']?>');
                });
            </script>
            <div class="padding">
                <div class="preview-project-viewport">
                    <div class="preview-project-viewport-inner">
                        <?
                        global $MESS;
                        $MESS["FORM_NOTE_ADDOK"] = "Спасибо! Ваша заявка на участие в акции \"" . $arResult['NAME'] . "\" принята.";
                        $MESS["FORM_NOTE_ADDOK"] .= "<br><br>В случае утверждения вы получите информацию на указанный электронный адрес.
Без подтверждения от организаторов, участие в мероприятии невозможно.";
                        if (!empty($orgs)) {
                            $MESS["FORM_NOTE_ADDOK"] .= "<br><br>По дополнительным вопросам просим обращаться к ответственным за проведение: ";
                            foreach ($orgs as $org) {
                                $MESS["FORM_NOTE_ADDOK"] .= "<br><br>{$org['NAME']}<br>{$org['props']['phone']['VALUE']}";
                            }
                        }
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
                                "ACTION_NAME" => $arResult['NAME'],
                                "ACTION_ID" => $arResult['ID']
                            ), $component
                        ); ?>
                    </div>
                </div>
            </div>
        <? else: ?>
            <h3 class="title"><?= GetMessage("Другие акции") ?></h3>
            <h6 class="more text-right">
                <a href="<?= SITE_DIR ?>stock/"><?= GetMessage("Все акции") ?> <i class="fa fa-angle-double-right" aria-hidden="true"></i></a>
            </h6>
            <div class="clearfix"></div>
            <? $APPLICATION->IncludeComponent(
                "kontora:element.list",
                "stock_detail",
                Array(
                    "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                    "PROPS" => "Y",
                    "ELEMENT_COUNT" => "5",
                    "FILTER" => array('!ID' => $arResult['ID']),
                    'SEF_FOLDER' => $arParams['SEF_FOLDER'],
                )
            ); ?>
        <? endif; ?>
    </div>
</div>