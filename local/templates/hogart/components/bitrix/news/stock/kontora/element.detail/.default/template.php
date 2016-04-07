<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$date_from = FormatDate("d F", MakeTimeStamp($arResult["ACTIVE_FROM"]));
$date_to = FormatDate("d F", MakeTimeStamp($arResult["ACTIVE_TO"]));?>

<div class="inner">
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
    <h1><?=$arResult['NAME']?></h1>
    <ul class="action-list-one">
        <li>
                <div class="date">
                    <?=$date_from.' – '.$date_to?>
                    <?
                    $dateFinish = FormatDate("d.m.Y", MakeTimeStamp($arResult["ACTIVE_TO"]));
                    $now=date($DB->DateFormatToPHP(CSite::GetDateFormat("SHORT")), time());
                    if ($arResult['ACTIVE']==Y && strtotime($now) > strtotime($dateFinish)):?>
                        <strong>(Акция завершена)</strong>
                    <?endif;?>
                </div>
                <p>
                    <?=$arResult['PREVIEW_TEXT']?>
                </p>
            <?$share_img_src = $arResult['DETAIL_PICTURE']['SRC'];?>
            <img src="<?=$arResult['DETAIL_PICTURE']['SRC']?>" alt=""/>
            <?=$arResult['DETAIL_TEXT']?>
        </li>
    </ul>
    <?/*if (!$USER->IsAuthorized()):?>
        <a class="back_page to-reg" href="/register/">регистрация</a> <br/>
    <?endif;*/?>
    <?$APPLICATION->IncludeFile(
        "/local/include/share.php",
        array(
            "TITLE" => $arResult["NAME"],
            "DESCRIPTION" => !empty($arResult["PREVIEW_TEXT"]) ? $arResult["PREVIEW_TEXT"]:$arResult["DETAIL_TEXT"],
            "LINK" => $APPLICATION->GetCurPage(),
            "IMAGE"=> $share_img_src
        )
    );?>
    <a class="back_page icon-news-back" href="<?=$arParams['SEF_FOLDER']?>">Назад к акциям</a>

</div>
<!--form name="email" style="diapley:none;" action="/ajax/send_to_email.php"-->
    <div class="strange-block hide-it">
        <input type="hidden" name="actionID" value="<?=$arResult['ID']?>" />
        <?if ($USER->IsAuthorized()):?>
            <input type="hidden" name="user_mail" value="<?=$USER->GetEmail();?>"/>
        <?else:?>
            <input type="text" name="user_mail" value=""/>
            <input type="submit" value="Отправить" />
        <?endif;?>
    </div>
<!--/form-->

<aside class="sidebar js-fh js-fixed-block js-paralax-height" data-fixed="top">
    <div class="inner js-paralax-item">

        <?
        $date_stock_end = FormatDate("d.m.Y", MakeTimeStamp($arResult['DATE_ACTIVE_TO']));
        $date_stock_end = strtotime($date_stock_end);
        $date_stock_end =(!empty($date_stock_end))?$date_stock_end:0;

        $now=date($DB->DateFormatToPHP(CSite::GetDateFormat("SHORT")), time());
        $now=strtotime($now);
        ?>
        <?// if ($arItem['PROPERTIES']['time']['VALUE'] != ''): ?>

        <?if ($arResult['PROPERTIES']['need_reg']['VALUE']=='Y' && $date_stock_end > $now):?>
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
                        <?$APPLICATION->IncludeComponent(
                            "bitrix:form.result.new",
                            "sem_quest",
                            Array(
                                "WEB_FORM_ID" => "9",
                                "IGNORE_CUSTOM_TEMPLATE" => "N",
                                "USE_EXTENDED_ERRORS" => "N",
                                "SEF_MODE" => "N",
                                "VARIABLE_ALIASES" => Array("WEB_FORM_ID"=>"WEB_FORM_ID","RESULT_ID"=>"RESULT_ID"),
                                "CACHE_TYPE" => "A",
                                "CACHE_TIME" => "3600",
                                "LIST_URL" => "",
                                "EDIT_URL" => "",
                                "SUCCESS_URL" => "",
                                "CHAIN_ITEM_TEXT" => "",
                                "CHAIN_ITEM_LINK" => ""
                            ), $component
                        );?>
                    </div>
                </div>
            </div>
        <?else:?>
            <?$APPLICATION->IncludeComponent(
                "kontora:element.list",
                "stock_detail",
                Array(
                    "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                    "PROPS" => "Y",
                    "CHECK_PERMISSIONS" => "Y",
                    "ELEMENT_COUNT" => "3",
//                "FILTER" => array('!ID' => $ElementID),
                    "FILTER" => array('!ID' => $arResult['ID']),
                    'SEF_FOLDER' => $arParams['SEF_FOLDER'],
                )
            );?>


        <?endif;?>
        <div class="side_href">
            <a href="#" class="icon-email">Отправить на e-mail</a>
            <a href="#" onclick="window.print(); return false;" class="icon-print">Распечатать</a>
            <a href="#" class="icon-phone">Отправить SMS</a>
        </div>
    </div>
</aside>