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
                        <img class="js-vertical-center" title="<?=$arItem['NAME']?>"
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
</aside>