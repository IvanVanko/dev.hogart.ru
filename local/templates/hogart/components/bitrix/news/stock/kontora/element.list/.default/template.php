<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
} ?>
<? $page = $APPLICATION->GetCurDir(true); ?>
<div class="row">
    <div class="col-md-9">
        <h3><? $APPLICATION->ShowTitle() ?></h3>
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
                            <? if (!empty($arItem['PREVIEW_PICTURE']['SRC']) && file_exists($_SERVER["DOCUMENT_ROOT"] . $arItem['PREVIEW_PICTURE']['SRC'])): ?>
                                <? $pic = $arItem['PREVIEW_PICTURE']['SRC']; ?>
                            <? else: ?>
                                <? $pic = "/images/project_no_img.jpg"; ?>
                            <? endif; ?>
                            <img title="<?=$arItem['NAME']?>"
                                 src="<?= $pic ?>" alt=""/>
                        </div>

                        <div class="info-wrap">
                            <div class="date">
                                <?=$date_from.' – '.$date_to?>
                                <?
                                $dateFinish = FormatDate("d.m.Y", MakeTimeStamp($arItem["ACTIVE_TO"]));
                                $now = date($DB->DateFormatToPHP(CSite::GetDateFormat("SHORT")), time());
                                if($arItem['ACTIVE'] == Y && strtotime($now) > strtotime($dateFinish)):
                                    ?>
                                    <strong>(<?= GetMessage("Акция завершена") ?>)</strong>
                                <? endif; ?>
                            </div>
                            <?
                            global $USER;
                            if(!$USER->IsAuthorized() && $arItem['PROPERTIES']['REGISTERED_ONLY']['VALUE'] == 'Y') {
                                ?>
                                <a class="profile-url js-popup-open" href="javascript:" data-popup="#popup-login">
                                    <?= GetMessage("Авторизуйтесь, чтобы увидеть акцию") ?>
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
    <div class="col-md-3 aside">
        <? if (LANGUAGE_ID != "en"): ?>
            <form action="#" class="action_filter">
                <? if(count($arResult['FILTER']['DIRECTIONS']) > 0): ?>
                    <h3>Направление</h3>

                    <? foreach($arResult['FILTER']['DIRECTIONS'] as $key => $arDirection): ?>
                        <div class="checkbox">
                            <label>
                                <input 
                                    name="direction[]" 
                                    id="doc_<?=$key + 1?>" 
                                    type="checkbox" 
                                    value="<?=$arDirection['ID']?>"
                                    <? if(in_array($arDirection['ID'], $_REQUEST['direction'])): ?>
                                        checked
                                    <? endif; ?>
                                > <?=$arDirection['NAME']?>
                            </label>
                        </div>
                    <? endforeach; ?>
                <? endif; ?>

                <? if(count($arResult['FILTER']['BRANDS']) > 0): ?>
                    <h3>Бренд</h3>
                    <div class="row breands hide-big-cnt" data-hide="Еще бренды">
                        <? foreach($arResult['FILTER']['BRANDS'] as $key => $arBrand): ?>
                            <div class="col-md-6 checkbox" style="margin-top: 0;">
                                <label>
                                    <input
                                        name="brand[]"
                                        id="breands_<?=$key + 1?>"
                                        type="checkbox"
                                        value="<?=$arBrand['ID']?>"
                                        <? if(in_array($arBrand['ID'], $_REQUEST['brand'])): ?>
                                            checked
                                        <? endif; ?>
                                    > <?=$arBrand['VALUE']?>
                                </label>
                            </div>
                        <? endforeach; ?>
                    </div>
                <? endif; ?>

                <? if($arResult["custom_filter_count"]["sale"] > 0): ?>
                    <div class="checkbox">
                        <label>
                            <input
                                type="checkbox"
                                name="sale"
                                id="breands_122"
                                value="Y"
                                <? if($_REQUEST['sale'] == 'Y'): ?>
                                    checked
                                <? endif; ?>
                            > Распродажа
                        </label>
                    </div>
                <? endif; ?>
                <? if($arResult["custom_filter_count"]["markdown"] > 0): ?>
                    <div class="checkbox">
                        <label>
                            <input
                                type="checkbox"
                                name="markdown"
                                id="breands_133"
                                value="Y"
                                <? if($_REQUEST['markdown'] == 'Y'): ?>
                                    checked
                                <? endif; ?>
                            > Уценка
                        </label>
                    </div>
                <? endif; ?>
                <? if(count($arResult['FILTER']['CITY']) > 0): ?>
                    <h3>Город</h3>
                    <div class="form-group">
                        <select class="form-control" name="city">
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
                <? endif; ?>
                <a href="<?=$page?>" class="btn btn-primary">Сбросить запрос</a>
            </form>
        <? else: ?>
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
                "IBLOCK_ID" => (LANGUAGE_ID == 'en' ? 31 : 19),
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
        <? endif; ?>
    </div>
</div>