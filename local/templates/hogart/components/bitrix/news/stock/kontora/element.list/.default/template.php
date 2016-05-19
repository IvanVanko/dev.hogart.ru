<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
} ?>
<? $page = $APPLICATION->GetCurDir(true); ?>
<div class="inner">
    <h1><? $APPLICATION->ShowTitle() ?></h1>
    <? if(count($arResult["ITEMS"]) > 0): ?>
        <ul class="action-list">
            <?
            foreach($arResult["ITEMS"] as $arItem):
                $this->AddEditAction($arItem["ID"], $arItem["EDIT_LINK"], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                $this->AddDeleteAction($arItem["ID"], $arItem["DELETE_LINK"], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
                ?>
                <? $date_from = FormatDate("d F", MakeTimeStamp($arItem["ACTIVE_FROM"]));
                if(!$arItem["ACTIVE_TO"]){
                    $arItem["ACTIVE_TO"] = "31.12.".date("Y");
                }
                $date_to = FormatDate("d F", MakeTimeStamp($arItem["ACTIVE_TO"]));
                ?>
                <li id="<?=$this->GetEditAreaId($arItem['ID'])?>">
                    <div class="img-wrap">
                        <img title="<?=$arItem['NAME']?>"
                             src="<?=$arItem['PREVIEW_PICTURE']['SRC']?>" alt=""/>
                    </div>

                    <div class="info-wrap">
                        <div class="date">
                            <?=$date_from.' – '.$date_to?>
                            <?
                            $dateFinish = FormatDate("d.m.Y", MakeTimeStamp($arItem["ACTIVE_TO"]));
                            $now = date($DB->DateFormatToPHP(CSite::GetDateFormat("SHORT")), time());
                            if($arItem['ACTIVE'] == Y && strtotime($now) > strtotime($dateFinish)):
                                ?>
                                <strong>(Акция завершена)</strong>
                            <? endif; ?>
                        </div>
                        <?
                        global $USER;
                        if(!$USER->IsAuthorized() && $arItem['PROPERTIES']['REGISTERED_ONLY']['VALUE'] == 'Y') {
                            ?>
                            <a class="profile-url js-popup-open" href="javascript:" data-popup="#popup-login">
                                Авторизуйтесь, чтобы увидеть акцию
                            </a>
                            <?
                        }
                        else { ?>
                            <a class="head" href="<?=$arItem['DETAIL_PAGE_URL']?>"><?=$arItem['NAME']?></a>

                            <p>
                                <?=$arItem['PREVIEW_TEXT']?>
                            </p>
                        <? } ?>
                    </div>
                </li>
            <? endforeach; ?>
        </ul>
    <? endif; ?>
</div>
<aside class="sidebar js-fh js-fixed-block js-paralax-height" data-fixed="top">
    <? if (LANGUAGE_ID != "en"): ?>
    <div class="inner js-paralax-item">
        <div class="padding">
            <form action="#" class="action_filter">
                <? if(count($arResult['FILTER']['DIRECTIONS']) > 0): ?>
                    <h2>Направление</h2>

                    <? foreach($arResult['FILTER']['DIRECTIONS'] as $key => $arDirection): ?>
                        <div class="field custom_checkbox">
                            <input
                                name="direction[]"
                                id="doc_<?=$key + 1?>"
                                type="checkbox"
                                value="<?=$arDirection['ID']?>"
                                <? if(in_array($arDirection['ID'], $_REQUEST['direction'])): ?>
                                    checked
                                <? endif; ?>
                            />
                            <label for="doc_<?=$key + 1?>"><?=$arDirection['NAME']?></label>
                        </div>
                    <? endforeach; ?>
                    <div class="fixheight"></div>
                <? endif; ?>

                <? if(count($arResult['FILTER']['BRANDS']) > 0): ?>
                    <h2>Бренд</h2>
                    <div class="breands hide-big-cnt" data-hide="Еще бренды">
                        <? foreach($arResult['FILTER']['BRANDS'] as $key => $arBrand): ?>
                            <div class="field custom_checkbox">
                                <input
                                    type="checkbox"
                                    name="brand[]"
                                    id="breands_<?=$key + 1?>"
                                    value="<?=$arBrand['ID']?>"
                                    <? if(in_array($arBrand['ID'], $_REQUEST['brand'])): ?>
                                        checked
                                    <? endif; ?>
                                />
                                <label for="breands_<?=$key + 1?>"><?=$arBrand['VALUE']?></label>
                            </div>
                        <? endforeach; ?>
                    </div>
                <? endif; ?>
                <br/>

                <? if($arResult["custom_filter_count"]["sale"] > 0) { ?>
                    <div class="field custom_checkbox">
                        <input
                            type="checkbox"
                            name="sale"
                            id="breands_122"
                            value="Y"
                            <? if($_REQUEST['sale'] == 'Y'): ?>
                                checked
                            <? endif; ?>
                        />
                        <label for="breands_122">Распродажа</label>
                    </div>
                <? } ?>
                <? if($arResult["custom_filter_count"]["markdown"] > 0) { ?>
                    <div class="field custom_checkbox">
                        <input
                            type="checkbox"
                            name="markdown"
                            id="breands_133"
                            value="Y"
                            <? if($_REQUEST['markdown'] == 'Y'): ?>
                                checked
                            <? endif; ?>
                        />
                        <label for="breands_133">Уценка</label>
                    </div>
                <? } ?>

                <div class="fixheight"></div>
                <? if(count($arResult['FILTER']['CITY']) > 0): ?>
                    <h2>Город</h2>
                    <div class="field custom_select">
                        <select name="city">
                            <option value="">Выбрать город</option>
                            <? foreach($arResult['FILTER']['CITY'] as $city): ?>
                                <option
                                    value="<?=$city['ID']?>"
                                    <? if($_REQUEST['city'] == $city['ID']): ?>
                                        selected
                                    <? endif; ?>
                                >
                                    <?=$city['VALUE']?>
                                </option>
                            <? endforeach; ?>
                        </select>
                    </div>
                    <div class="fixheight"></div>
                <? endif; ?>
                <div class="fixheight"></div>

                <a href="<?=$page?>" class="empty-btn link">сбросить запрос</a>
                <br/><br/>
            </form>
        </div>
    </div>
    <? else: ?>
    <div class="inner js-paralax-item">

        <div class="company-side-cnt padding">
            <h2><?=GetMessage("Основные направления деятельности")?></h2>
            <?
            $GetCurDir = explode("/", $APPLICATION->GetCurDir());

            $GetCurDir = array_filter(
                $GetCurDir,
                function ($el) {
                    return !empty($el);
                }
            );
            $GLOBALS['myFilter'] = array("PROPERTY_show_where" => $GetCurDir);
            $APPLICATION->IncludeComponent(
                "bitrix:news.list",
                "advantages",
                Array(
                    "COMPONENT_TEMPLATE" => ".default",
                    "IBLOCK_TYPE" => "advantages",
                    "IBLOCK_ID" => 19,
                    "NEWS_COUNT" => "3",
                    "SORT_BY1" => "SORT",
                    "SORT_ORDER1" => "ASC",
                    "SORT_BY2" => "ACTIVE_FROM",
                    "SORT_ORDER2" => "DESC",
                    "FILTER_NAME" => "myFilter",
                    "FIELD_CODE" => array("", ""),
                    "PROPERTY_CODE" => array("link"),
                    "CHECK_DATES" => "Y",
                    "DETAIL_URL" => "",
                    "AJAX_MODE" => "N",
                    "AJAX_OPTION_JUMP" => "N",
                    "AJAX_OPTION_STYLE" => "Y",
                    "AJAX_OPTION_HISTORY" => "N",
                    "CACHE_TYPE" => "A",
                    "CACHE_TIME" => "36000000",
                    "CACHE_FILTER" => "N",
                    "CACHE_GROUPS" => "Y",
                    "PREVIEW_TRUNCATE_LEN" => "",
                    "ACTIVE_DATE_FORMAT" => "d.m.Y",
                    "SET_TITLE" => "N",
                    "SET_BROWSER_TITLE" => "N",
                    "SET_META_KEYWORDS" => "N",
                    "SET_META_DESCRIPTION" => "N",
                    "SET_STATUS_404" => "N",
                    "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
                    "ADD_SECTIONS_CHAIN" => "N",
                    "HIDE_LINK_WHEN_NO_DETAIL" => "N",
                    "PARENT_SECTION" => "",
                    "PARENT_SECTION_CODE" => "",
                    "INCLUDE_SUBSECTIONS" => "Y",
                    "DISPLAY_DATE" => "N",
                    "DISPLAY_NAME" => "Y",
                    "DISPLAY_PICTURE" => "Y",
                    "DISPLAY_PREVIEW_TEXT" => "N",
                    "PAGER_TEMPLATE" => ".default",
                    "DISPLAY_TOP_PAGER" => "N",
                    "DISPLAY_BOTTOM_PAGER" => "N",
                    "PAGER_TITLE" => "Новости",
                    "PAGER_SHOW_ALWAYS" => "N",
                    "PAGER_DESC_NUMBERING" => "N",
                    "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                    "PAGER_SHOW_ALL" => "N"
                )
            );
            ?>
        </div>
    <? endif; ?>
</aside>