<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>


<div class="main-filter news-filter">
	<div class="btn show-filter-btn open-next"></div>
	<form action="#" class="main-filter-form hidden_block open-block">

		<?if (!empty($arResult["FILTER"]["DIRECTIONS"])):?>
			<div class="filter-block">
				<p class="block-title">Направление</p>
				<?foreach ($arResult["FILTER"]["DIRECTIONS"] as $key => $arDirection):?>
					<?#DebugMessage($arDirection);?>
					<input type="hidden" name="direction_<?=$arDirection['ID']?>_left" value=<?=$arDirection['LEFT_MARGIN']?> />
					<input type="hidden" name="direction_<?=$arDirection['ID']?>_right" value=<?=$arDirection['RIGHT_MARGIN']?> />
					<select name="section_<?=$arDirection['ID']?>">
						<option value="">Выбрать категорию</option>
						<?foreach ($arDirection['SECTIONS'] as $arSection):?>
							<option <?if ($_REQUEST['section_'.$arDirection['ID']] == $arSection['ID']):?>selected <?endif?>value="<?=$arSection['ID']?>"><?=$arSection['NAME']?></option>
						<?endforeach;?>
					</select>
					<?foreach ($arDirection['SECTIONS'] as $arSection):?>
					<input type="hidden" name="section_<?=$arSection['ID']?>_left" value=<?=$arSection['LEFT_MARGIN']?> />
					<input type="hidden" name="section_<?=$arSection['ID']?>_right" value=<?=$arSection['RIGHT_MARGIN']?> />
					<?endforeach;?>
				<?endforeach;?> 
			</div>
		<?endif;?>

		<!--<div class="filter-block">
			<p class="block-title">Направление</p>
			<select name="select2" id="select2">
				<option value="1">Отопление</option>
				<option value="2">Отопление</option>
			</select>	
			<select name="select2" id="select2">
				<option value="1">Категория 1</option>
				<option value="2">Категория 2</option>
			</select>
		</div>/-->
		
		
		<?if (!empty($arResult['FILTER']['BRANDS'])):?>
		<div class="filter-block">
			<p class="block-title">Бренд</p>
			<div class="checkbox_wrap open-block">
				<?foreach ($arResult['FILTER']['BRANDS'] as $key => $arBrand):?>
					<?/*if ($key == 3):?>
						<div class="hide-block">
					<?endif;*/?>
					
						<input  class="custom_checkbox" <?if (in_array($arBrand['ID'], $_REQUEST['brand'])):?>checked <?endif?>type="checkbox" name="brand[]" id="breands_<?=$key+1?>" value="<?=$arBrand['ID']?>"/>
						<label for="breands_<?=$key+1?>"><?=$arBrand['VALUE']?></label>
					
				<?endforeach;?>
			</div>
			<a href="#" class="input-btn gray-btn all open-next">показать все</a>
		</div>
		<?endif;?>
		
		<!--<div class="filter-block">
			<p class="block-title">Бренд</p>
			<div class="checkbox_wrap open-block">
				<input type="checkbox" id="checkbox_1" name="checkbox_1" class="custom_checkbox">
				<label for="checkbox_1">Buderus</label>
				<input type="checkbox" id="checkbox_2" name="checkbox_2" class="custom_checkbox">
				<label for="checkbox_2">Kiturami</label>
				<input type="checkbox" id="checkbox_3" name="checkbox_3" class="custom_checkbox">
				<label for="checkbox_3">Protherm</label>
				<input type="checkbox" id="checkbox_4" name="checkbox_4" class="custom_checkbox">
				<label for="checkbox_4">Unical</label>
				<input type="checkbox" id="checkbox_5" name="checkbox_5" class="custom_checkbox">
				<label for="checkbox_5">Saturn</label>
				<input type="checkbox" id="checkbox_6" name="checkbox_6" class="custom_checkbox">
				<label for="checkbox_6">Olympia</label>	

				<input type="checkbox" id="checkbox_6" name="checkbox_6" class="custom_checkbox">
				<label for="checkbox_6">Olympia</label>	
				<input type="checkbox" id="checkbox_6" name="checkbox_6" class="custom_checkbox">
				<label for="checkbox_6">Olympia</label>	
				<input type="checkbox" id="checkbox_6" name="checkbox_6" class="custom_checkbox">
				<label for="checkbox_6">Olympia</label>	
				<input type="checkbox" id="checkbox_6" name="checkbox_6" class="custom_checkbox">
				<label for="checkbox_6">Olympia</label>							
			</div>
			<a href="#" class="input-btn gray-btn all open-next">показать все</a>

		</div>/-->

		<div class="filter-block">
			<p class="block-title">Ключевое слово</p>
				<input type="text" placeholder="" id="name" name="keyword" value="<?=$_REQUEST['keyword']?>">
		</div>	
		<input type="submit" class="input-btn gray-btn" value="Показать результаты">
	</form>
</div>

<?if (count($arResult["ITEMS"]) > 0 && is_array($arResult["ITEMS"])):?>
<div class="news-items">
	<?foreach ($arResult["ITEMS"] as $arItem): ?>
	<article class="news-one">
		<h2><a href="<?= $arItem["DETAIL_PAGE_URL"]?>"><?= $arItem["NAME"]?></a></h2>
		<p><?= $arItem["PREVIEW_TEXT"]?></p>
		<?$date_from = FormatDate("d F", MakeTimeStamp($arItem["ACTIVE_FROM"]));?>
		<div class="article-info"> <span class="time"><?=$date_from?></span></div>
	</article>
	<?endforeach?>
</div>
<br />
<?= $arResult["NAV_STRING"]; ?>
<?endif;?>

<!--<a href="#" class="btn arrow_btn">ПОКАЗАТЬ ЕЩЕ статьи</a>/-->
<?$APPLICATION->IncludeFile(
	INCLUDE_AREAS."block-news-bottom-menu.php",
	Array(),
	Array("MODE"=>"html", "NAME"=>"Right Panel")
);?>
<?
#DebugMessage($arResult["ITEMS"]);
?>
<?/*?>
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
<?*/?>