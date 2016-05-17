<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
} ?>
<? $page = $APPLICATION->GetCurDir(true); ?>
<div class="inner">
    <!-- <? $APPLICATION->IncludeComponent("bitrix:menu", "section_menu", Array(
            "ROOT_MENU_TYPE" => "left",
            "MAX_LEVEL" => "1",
            "CHILD_MENU_TYPE" => "left",
            "USE_EXT" => "Y",
            "DELAY" => "N",
            "ALLOW_MULTI_SELECT" => "Y",
            "MENU_CACHE_TYPE" => "N",
            "MENU_CACHE_TIME" => "3600",
            "MENU_CACHE_USE_GROUPS" => "Y",
            "MENU_CACHE_GET_VARS" => ""
        )
    ); ?> -->
    <h1><? $APPLICATION->ShowTitle() ?></h1>
    <? if(count($arResult["ITEMS"]) > 0): ?>
        <ul class="news-list">
            <? foreach($arResult["ITEMS"] as $arItem):
                $this->AddEditAction($arItem["ID"], $arItem["EDIT_LINK"], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                $this->AddDeleteAction($arItem["ID"], $arItem["DELETE_LINK"], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM'))); ?>
                <? $date_from = FormatDate("d F Y", MakeTimeStamp($arItem["ACTIVE_FROM"])); ?>
                <li id="<?=$this->GetEditAreaId($arItem['ID'])?>"
                    <? if(!empty($arItem['PREVIEW_PICTURE']['SRC'])): ?> style="padding-left: 150px; position: relative" <? endif; ?>>
                    <? if(!empty($arItem['PREVIEW_PICTURE']['SRC'])):
                        $file = CFile::ResizeImageGet($arItem['PREVIEW_PICTURE']['ID'], array('width' => 140,
                                                                                              'height' => 140), BX_RESIZE_IMAGE_EXACT, true);
                        ?>
                        <img style="position: absolute; left: 0; top: 15px; max-width: 140px" src="<?=$file['src'];?>"
                             alt="<?=$arItem["NAME"]?>"/>
                    <? endif; ?>

                    <div class="date">
                        <sub><?=$date_from?></sub>
                    </div>
                    <?
                    global $USER;
                    if(!$USER->IsAuthorized() && $arItem['PROPERTIES']['need_reg']['VALUE'] == 'Y') {
                        ?>
                        <h2>
                            <a class="profile-url js-popup-open" href="javascript:" data-popup="#popup-login""><?=$arItem["NAME"]?></a>
                        </h2>
                        <p>Для прочтения необходима авторизация на сайте</p>
                    <?
                    }
                    else { ?>
                        <h2>
                            <a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><?=$arItem["NAME"]?></a>
                        </h2>
                        <p><?=$arItem["PREVIEW_TEXT"]?></p>
                    <? } ?>

                    <? foreach($arItem["PROPERTIES"]["tag"]["VALUE"] as $key => $tag): ?>
                        <div class="tag">
                            <a href="<?=$APPLICATION->GetCurPageParam("tag[".$arItem['PROPERTIES']['tag']['VALUE_ENUM_ID'][$key]."]=".$tag, array("tag"));?>">— <?=$tag?></a>
                        </div>
                    <? endforeach; ?>
                </li>
            <? endforeach; ?>
        </ul>
    <? endif; ?>
    <?=$arResult["NAV_STRING"];?>
</div>
<aside class="sidebar js-fh js-fixed-block js-paralax-height" data-fixed="top">
    <div class="inner js-paralax-item">
        <div class="company-side-cnt padding news__aside__filter">
            <h2><?= GetMessage("Тип новости")?></h2>

            <form action="#" class="no-padding">
                <? foreach($arResult["FILTER"]["TAG"] as $key => $tag): ?>
                    <div class="field custom_checkbox news_tags tag<?=$tag['PROPERTY_TAG_ENUM_ID']?>">
                        <input
                            type="checkbox"
                            id="checkbox_<?=$key?>"
                            name="tag[<?=$tag["PROPERTY_TAG_VALUE_ENUM_ID"]?>]"
                            value="<?=$tag["PROPERTY_TAG_VALUE_VALUE"]?>"
                            <? if(isset($_REQUEST["tag"][$tag["PROPERTY_TAG_VALUE_ENUM_ID"]])): ?> checked<? endif; ?>
                        />
                        <label for="checkbox_<?=$key?>"><?=$tag["PROPERTY_TAG_VALUE_VALUE"]?></label>
                    </div>
                <? endforeach; ?>
            </form>
            <form action="#" class="no-padding">
                <div class="accordion-cnt">
                    <h2 class="trigger-accordion js-accordion-new"
                        data-accordion="#newsFilter"><?= GetMessage("Фильтр по продукции")?></h2>

                    <div
                        id="newsFilter"<? if(isset($_REQUEST['brand']) || isset($_REQUEST['direction']) || isset($_REQUEST['catalog_section'])): ?> style="display:block;"<? else: ?>style="display:none;"<? endif; ?>>
                        <div class="field custom_select">
                            <select name="direction">
                                <option value=""><?= GetMessage("Выбрать направление")?></option>
                                <? foreach($arResult["FILTER"]["DIRECTIONS"] as $direction): ?>
                                    <option
                                        value="<?=$direction["ID"]?>"<? if($_REQUEST["direction"] == $direction["ID"]): ?> selected<? endif ?>><?=$direction["NAME"]?></option>
                                <? endforeach ?>
                            </select>
                        </div>
                        <? foreach($arResult["FILTER"]["DIRECTIONS"] as $direction): ?>
                            <input type="hidden" name="direction_<?=$direction['ID']?>_left"
                                   value=<?=$direction['LEFT_MARGIN']?>/>
                            <input type="hidden" name="direction_<?=$direction['ID']?>_right"
                                   value=<?=$direction['RIGHT_MARGIN']?>/>
                        <? endforeach; ?>

                        <div class="field custom_select">
                            <select name="catalog_section">
                                <option value=""><?= GetMessage("Выбрать тип товара")?></option>
                                <? foreach($arResult["FILTER"]["TYPES"] as $type): ?>
                                    <option
                                        value="<?=$type["ID"]?>"<? if($_REQUEST["catalog_section"] == $type["ID"]): ?> selected<? endif ?>><?=$type["VALUE"]?></option>
                                <? endforeach ?>
                            </select>
                        </div>
                        <div class="field custom_select">
                            <select name="brand">
                                <option value=""><?= GetMessage("Выбрать бренд")?></option>
                                <? foreach($arResult["FILTER"]["BRANDS"] as $brand): ?>
                                    <option
                                        value="<?=$brand["ID"]?>"<? if($_REQUEST["brand"] == $brand["ID"]): ?> selected<? endif ?>><?=$brand["VALUE"]?></option>
                                <? endforeach; ?>
                            </select>
                        </div>
                        <button class="empty-btn flr"><?= GetMessage("Показать")?></button>
                        <a href="<?=$page?>" class="empty-btn link"><?= GetMessage("Сбросить")?></a>
                    </div>
                </div>
                <div class="fixheight"></div>

                <? $APPLICATION->IncludeComponent("kontora:element.list", "news_calendar", array(
                    "IBLOCK_ID" => "3",
                    "FILTER" => array(
                        array(
                            "LOGIC" => "OR",
                            array(">DATE_ACTIVE_TO" => date('d.m.Y H:i:s')),
                            array("DATE_ACTIVE_TO" => false),
                        ),
                    ),
                    "PROPS" => "Y",
                    "CHECK_PERMISSIONS" => "Y",
                )); ?>
                <!--.clearfix-->

            </form>

            <div class="accordion-cnt">
                <!--                    <span class="trigger-accordion js-accordion"  data-accordion="#feednews">Подписаться на новости</span>-->
                <a href="#" class="js-popup-open" data-popup="#popup-subscribe-mod">Подписаться на новости</a>

                <? /*
                <div id="feednews">
                    <?$APPLICATION->IncludeComponent(
                        "bitrix:subscribe.edit",
                        "left_form",
                        Array(
                            "COMPONENT_TEMPLATE" => ".default",
                            "SHOW_HIDDEN" => "N",
                            "AJAX_MODE" => "N",
                            "AJAX_OPTION_JUMP" => "N",
                            "AJAX_OPTION_STYLE" => "Y",
                            "AJAX_OPTION_HISTORY" => "N",
                            "CACHE_TYPE" => "A",
                            "CACHE_TIME" => "3600",
                            "ALLOW_ANONYMOUS" => "Y",
                            "SHOW_AUTH_LINKS" => "Y",
                            "SET_TITLE" => "N"
                        )
                    );?>
                    <?$APPLICATION->IncludeComponent(
                        "bitrix:subscribe.form",
                        "news",
                        Array(
                            "USE_PERSONALIZATION" => "Y",
                            "SHOW_HIDDEN" => "N",
                            "PAGE" => "#SITE_DIR#subscription/",
                            "CACHE_TYPE" => "A",
                            "CACHE_TIME" => "3600"
                        )
                    );
                    ?>
                </div>
                */ ?>
            </div>

        </div>
    </div>
</aside>