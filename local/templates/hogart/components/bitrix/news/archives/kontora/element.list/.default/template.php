<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?$page = $APPLICATION->GetCurDir(true);?>
<div class="inner">
    <h1><?$APPLICATION->ShowTitle()?></h1>
	<?if (count($arResult["ITEMS"]) > 0):?>
		<ul class="list-base-learn">
			<?foreach ($arResult["ITEMS"] as $arItem):
				$this->AddEditAction($arItem["ID"], $arItem["EDIT_LINK"], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
				$this->AddDeleteAction($arItem["ID"], $arItem["DELETE_LINK"], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));?>
                <?$date = explode('.', $arItem['PROPERTIES']['sem_start_date']['VALUE']);?>
                <?$date_from = FormatDate("d F Y", MakeTimeStamp($arItem['PROPERTIES']['sem_start_date']['VALUE']));?>
                <!--                <li data-date="--><?//=$arItem['PROPERTIES']['time']['VALUE'];?><!--">-->
                <?
                $date_sem_start = FormatDate("d.m.Y", MakeTimeStamp($arItem['PROPERTIES']['sem_start_date']['VALUE']));
                $date_sem_start = strtotime($date_sem_start);
                $date_sem_start =(!empty($date_sem_start))?$date_sem_start:0;

                $now=date($DB->DateFormatToPHP(CSite::GetDateFormat("SHORT")), time());
                $now=strtotime($now);
                ?>
                <?//if ($date_sem_start < $now):?>

				<li id="<?=$this->GetEditAreaId($arItem['ID'])?>">
                    <?
                    $file = CFile::ResizeImageGet($arItem['PREVIEW_PICTURE']['ID'], array('width'=>100, 'height'=>100), BX_RESIZE_IMAGE_EXACT, true);
                    ?>
                    <img src="<?=$file['src']?>" alt=""/>
<!--                    <img src="--><?//=$arItem['PREVIEW_PICTURE']['SRC']?><!--" alt=""/>-->
                    <a href="<?=$arItem['DETAIL_PAGE_URL']?>">
                        <h2>
                            <?=$arItem['NAME']?>
                        </h2>
                    </a>
                    <div class="preview-txt">
                        <?$obParser = new CTextParser;
                        $arItem["PREVIEW_TEXT"] = $obParser->html_cut($arItem["PREVIEW_TEXT"], 400);?>
                        <?=$arItem['PREVIEW_TEXT'];?>
                    </div>
                    <?if (!empty($arItem['PROPERTIES']['sem_start_date']['VALUE'])):?>
                        <time> — <?=$date_from;?></time>
                    <?else:?>
                        <time>— Место проведения: </time><span>по запросу</span>
                    <?endif;?>
                </li>
            <?//endif;?>
			<?endforeach;?>
		</ul>
	<?endif; ?>
    <?=$arResult["NAV_STRING"];?>
</div>
<aside class="sidebar js-fh js-fixed-block js-paralax-height" data-fixed="top">
    <div class="inner js-paralax-item">
        <div class="padding">
            <a href="<?= SITE_DIR ?>learn/" class="side-back"><?= GetMessage("Календарь семинаров") ?> <i class="icon-white-back"></i></a>
        </div>
        <? if (!empty($arResult['FILTER']['BRANDS']) || (!empty($arResult["FILTER"]["DIRECTIONS"]) && LANGUAGE_ID != "en")): ?>
        <div class="company-side-cnt padding null-padding-top">
            <form action="#" class="archive_filter">
                <?if (!empty($arResult["FILTER"]["DIRECTIONS"]) && LANGUAGE_ID != "en"):?>
                    <h2>Направление</h2>
                    <?foreach ($arResult["FILTER"]["DIRECTIONS"] as $key => $arDirection):?>
                        <div class="field custom_checkbox">
                            <input type="hidden" name="direction_<?=$arDirection['ID']?>_left" value=<?=$arDirection['LEFT_MARGIN']?> />
                            <input type="hidden" name="direction_<?=$arDirection['ID']?>_right" value=<?=$arDirection['RIGHT_MARGIN']?> />
                            <input <?=(in_array($arDirection['ID'], $_REQUEST['direction']))?'checked':''?> name="direction[]" id="doc_<?=$key+1?>" type="checkbox" value="<?=$arDirection['ID']?>">
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
                    <?endforeach;?>
                    <br/>
                <?endif;?>

                <?if (!empty($arResult['FILTER']['BRANDS'])):?>
                    <h2 class="normal-margin"><?= GetMessage("Бренд") ?></h2>
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
                <button class="empty-btn"><?= GetMessage("Найти семинары") ?></button>
                <br/>
                <br/>
                <br/>
                <a href="<?= $page ?>" class="empty-btn link"><?= GetMessage("Сбросить запрос") ?></a>
                <br/><br/>
            </form>
        </div>
        <? endif; ?>
    </div>
</aside>