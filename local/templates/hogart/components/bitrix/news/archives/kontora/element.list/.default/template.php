<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
$page = $APPLICATION->GetCurDir(true);
?>
<div class="row">
    <div class="col-md-9 col-xs-12">
        <h3><?= $APPLICATION->GetTitle() ?></h3>
        <ul class="list-base-learn">
            <?foreach ($arResult["ITEMS"] as $arItem):
                $this->AddEditAction($arItem["ID"], $arItem["EDIT_LINK"], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                $this->AddDeleteAction($arItem["ID"], $arItem["DELETE_LINK"], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));?>
                <?$date = explode('.', $arItem['PROPERTIES']['sem_start_date']['VALUE']);?>
                <?$date_from = FormatDate("d F Y", MakeTimeStamp($arItem['PROPERTIES']['sem_start_date']['VALUE']));?>
                <?
                $date_sem_start = FormatDate("d.m.Y", MakeTimeStamp($arItem['PROPERTIES']['sem_start_date']['VALUE']));
                $date_sem_start = strtotime($date_sem_start);
                $date_sem_start =(!empty($date_sem_start))?$date_sem_start:0;

                $now=date($DB->DateFormatToPHP(CSite::GetDateFormat("SHORT")), time());
                $now=strtotime($now);
                ?>
                <li id="<?=$this->GetEditAreaId($arItem['ID'])?>">
                    <?
                    $file = CFile::ResizeImageGet($arItem['PREVIEW_PICTURE']['ID'], array('width'=>100, 'height'=>100), BX_RESIZE_IMAGE_PROPORTIONAL, true);
                    ?>
                    <img src="<?=$file['src']?>" alt=""/>
                    <a href="<?=$arItem['DETAIL_PAGE_URL']?>">
                        <h4>
                            <?=$arItem['NAME']?>
                        </h4>
                    </a>
                    <div class="preview-txt">
                        <?$obParser = new CTextParser;
                        $arItem["PREVIEW_TEXT"] = $obParser->html_cut($arItem["PREVIEW_TEXT"], 400);?>
                        <?=$arItem['PREVIEW_TEXT'];?>
                    </div>
                    <? if (!empty($arItem['PROPERTIES']['sem_start_date']['VALUE'])): ?>
                        <time> — <?=$date_from;?></time>
                    <? else: ?>
                        <time>— Место проведения: </time><span>по запросу</span>
                    <? endif; ?>
                </li>
            <? endforeach; ?>
        </ul>
        <?= $arResult["NAV_STRING"]; ?>
    </div>
    <div class="col-md-3 col-xs-12 aside aside-mobile">
        <div class="filter-stock">
            <? if (!empty($arResult['FILTER']['BRANDS']) || (!empty($arResult["FILTER"]["DIRECTIONS"]) && LANGUAGE_ID != "en")): ?>
                <a class="filter-stock__link js-filter-stock-mobile" href="#" title=""></a>
                <form action="#" class="archive_filter">
                    <?if (!empty($arResult['FILTER']['BRANDS'])):?>
                        <h3><?= GetMessage("Бренд") ?></h3>
                        <div class="row breands hide-big-cnt" data-hide="Еще">
                            <?foreach ($arResult['FILTER']['BRANDS'] as $key => $arBrand):?>
                                <div data-brand-key="<?= $key ?>" class="col-md-6 checkbox checkbox-archive <?= ($key > 3 ? "more" : "") ?>" style="margin-top: 0">
                                    <label>
                                        <input <?= ($arBrand['CHECKED'] ? "checked" : "") ?> type="checkbox" name="brand[]" id="breands_<?=$key+1?>" value="<?=$arBrand['ID']?>"/>
                                        <span class="checkbox-text"><?=$arBrand['VALUE']?></span>
                                    </label>
                                </div>
                            <?endforeach;?>
                            <? if ($key > 3): ?>
                                <div class="col-sm-12">
                                    <span class="btn-more" onclick="__more(this)">Еще <i class="fa"></i></span>
                                    <script>
                                        function __more (more) {
                                            $('.more', $(more).parents('.breands')).animate({ height: "toggle" });
                                            $(more).toggleClass('opened');
                                        }
                                    </script>
                                </div>
                            <? endif; ?>
                        </div>
                    <?endif;?>
                    <br/>
                    <button class="btn btn-primary"><?= GetMessage("Найти семинары") ?></button> 
                    <a href="<?= $page ?>" class="btn btn-link"><?= GetMessage("Сбросить запрос") ?></a>
                    <br/><br/>
                </form>
            <? endif; ?>
        </div>
    </div>
</div>
