<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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
$page = $APPLICATION->GetCurDir(true);
?>

<div class="inner no-full">
    <h1><?$APPLICATION->ShowTitle()?></h1>
<!--    <pre>--><?//var_dump($arResult);?><!--</pre>-->

    <?if (!empty($arResult['ITEMS'])):?>
        <ul class="list-base-learn">
            <? foreach ($arResult['ITEMS'] as $key => $arItem):?>
<!--                <?//var_dump($arItem)?>-->
                <?//if ($arItem['PROPERTIES']['time']['VALUE']==''):?>
                    <li>
                        <img src="<?=$arItem['PREVIEW_PICTURE']['SRC'];?>" alt=""/>
                        <a href="<?=$arItem['DETAIL_PAGE_URL'];?>"><h2><?=$arItem['NAME'];?></h2></a>
                        <div class="preview-txt">
                            <?$obParser = new CTextParser;
                            $arItem["PREVIEW_TEXT"] = $obParser->html_cut($arItem["PREVIEW_TEXT"], 400);?>
                            <?=$arItem['PREVIEW_TEXT'];?>
                        </div>
                        <time>— Место проведения: </time><span>по запросу</span>
                    </li>
                <?//endif;?>

            <?endforeach;?>
        </ul>
    <?endif;?>
    <ul class="lear-base-bottom-href">
        <li><a href="/learn/" class="cal">календарь Семинаров</a></li>
        <li><a href="/learn/archive-seminarov/" class="base">Архив Семинаров</a></li>
    </ul>
</div>
<aside class="sidebar js-fh js-fixed-block js-paralax-height" data-fixed="top">
    <div class="inner js-paralax-item">
        <div class="padding">
            <a href="/learn/" class="side-back">Календарь семинаров <i class="icon-white-back"></i></a>
        </div>
        <div class="company-side-cnt padding null-padding-top">

            <form action="#">
                <?if (!empty($arResult["FILTER"]["DIRECTIONS"])):?>
                    <h2>Направление</h2>
                    <?foreach ($arResult["FILTER"]["DIRECTIONS"] as $key => $arDirection):?>
                    <?//if (count($arDirection['SECTIONS'])>0):?>
                        <div class="field custom_checkbox">
                            <input type="hidden" name="direction_<?=$arDirection['ID']?>_left" value=<?=$arDirection['LEFT_MARGIN']?> />
                            <input type="hidden" name="direction_<?=$arDirection['ID']?>_right" value=<?=$arDirection['RIGHT_MARGIN']?> />
                            <input name="direction[]" <?=(in_array($arDirection['ID'], $_REQUEST['direction']))?'checked':''?> id="doc_<?=$key+1?>" type="checkbox" value="<?=$arDirection['ID']?>">
                            <label for="doc_<?=$key+1?>"><?=$arDirection['NAME']?></label>
                            <div class="isChecked">
                                <div class="field custom_select">
                                    <select name="section_<?=$arDirection['ID']?>">
                                        <option value="">Выбрать категорию</option>
                                        <?foreach ($arDirection['SECTIONS'] as $arSection):?>
                                            <option <?if ($_REQUEST['section_'.$arDirection['ID']] == $arSection['ID']):?>selected <?endif?>value="<?=$arSection['ID']?>"><?=$arSection['NAME']?></option>
                                        <?endforeach;?>
                                    </select>
                                </div>
                            </div>
                            <?foreach ($arDirection['SECTIONS'] as $arSection):?>
                                <input type="hidden" name="section_<?=$arSection['ID']?>_left" value=<?=$arSection['LEFT_MARGIN']?> />
                                <input type="hidden" name="section_<?=$arSection['ID']?>_right" value=<?=$arSection['RIGHT_MARGIN']?> />
                            <?endforeach;?>
                        </div>
                    <?//endif;?>
                    <?endforeach;?>
                    <br/>
                <?endif;?>

                <?if (!empty($arResult['FILTER']['BRANDS'])):?>
                    <h2 class="normal-margin">Бренд</h2>
                    <div class="breands hide-big-cnt" data-hide="Еще бренды">
                        <?foreach ($arResult['FILTER']['BRANDS'] as $key => $arBrand):?>
                            <?if ($key == 3):?>
                                <div class="hide-block">
                            <?endif;?>
                            <div class="field custom_checkbox">
                                <input <?if (in_array($arBrand['ID'], $_REQUEST['brand'])):?>checked <?endif?>type="checkbox" name="brand[]" id="breands_<?=$key+1?>" value="<?=$arBrand['ID']?>"/>
                                <label for="breands_<?=$key+1?>"><?=$arBrand['VALUE']?></label>
                            </div>
                        <?endforeach;?>
                        <?if (count($arResult['FILTER']['BRANDS']) > 3):?>
                            </div>
                        <?endif;?>
                    </div>
                <?endif;?>
                <br/>
                <button class="empty-btn">Найти семинары</button>
                <br/>
                <br/>
                <br/>
                <a href="<?= $page ?>" class="empty-btn link">сбросить запрос</a>
            </form>

        </div>
    </div>
</aside>