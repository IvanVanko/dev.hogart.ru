<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
// заменяем $arResult эпилога значением, сохраненным в шаблоне
if(isset($arResult['arResult'])) {
   $arResult =& $arResult['arResult'];
         // подключаем языковой файл
   global $MESS;
   include_once(GetLangFileName(dirname(__FILE__).'/lang/', '/template.php'));
} else {
   return;
}
?>
<?$page = $APPLICATION->GetCurDir(true);?>
<div class="inner">
    <h1><?$APPLICATION->ShowTitle()?></h1>
	<?if (count($arResult["ITEMS"]) > 0):?>
		<ul class="info-list">
			<?foreach ($arResult["ITEMS"] as $arItem): 
				$this->AddEditAction($arItem["ID"], $arItem["EDIT_LINK"], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
				$this->AddDeleteAction($arItem["ID"], $arItem["DELETE_LINK"], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));?>
				<li id="<?=$this->GetEditAreaId($arItem['ID'])?>">
	                <div class="date"><?=CIBlockFormatProperties::DateFormat('j F Y', MakeTimeStamp($arItem["ACTIVE_FROM"], CSite::GetDateFormat()))?> Г.</div>
	                <h2><a href="<?=$arItem['DETAIL_PAGE_URL']?>"><?=$arItem["NAME"]?></a></h2>

	                <p><?=$arItem['PREVIEW_TEXT']?></p>
	            </li>
			<?endforeach;?>
		</ul>
	<?endif; ?>
</div>
<aside class="sidebar js-fh js-fixed-block js-paralax-height" data-fixed="top">
    <div class="inner js-paralax-item">
        <div class="sidebar_padding_cnt padding">
        <!-- div class="sidebar_padding_cnt small-news-cnt" -->
            <form action="#">
                <?if (!empty($arResult["FILTER"]["DIRECTIONS"])):?>
	                <h2>Направление</h2>
	                <?foreach ($arResult["FILTER"]["DIRECTIONS"] as $key => $arDirection):?>
	                	<div class="field custom_checkbox">
		                    <input type="hidden" name="direction_<?=$arDirection['ID']?>_left" value=<?=$arDirection['LEFT_MARGIN']?> />
		                    <input type="hidden" name="direction_<?=$arDirection['ID']?>_right" value=<?=$arDirection['RIGHT_MARGIN']?> />
		                    <input<?=(in_array($arDirection['ID'], $_REQUEST['direction']))?' checked':''?> name="direction[]" id="doc_<?=$key+1?>" type="checkbox" value="<?=$arDirection['ID']?>">
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
                <h2>Ключевое слово</h2>
                <div class="field">
                    <input type="text" placeholder="" id="name" name="keyword" value="<?=$_REQUEST['keyword']?>">
                </div>
                <button class="empty-btn">Найти Статьи</button>
                <br/>
                <br/>
                <br/>
                <a href="<?= $page ?>" class="empty-btn link">сбросить запрос</a>
                <br/><br/>
            </form>
        </div>

    </div>
</aside>