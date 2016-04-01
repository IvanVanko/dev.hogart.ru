<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<div class="main-filter">
	<div class="btn show-filter-btn open-next"></div>
	<form action="" class="main-filter-form hidden_block open-block">
		<?if (!empty($arResult['FILTER']['TYPES'])):?>
		<div class="filter-block ">
			<p class="block-title">Тип документа</p>
			<?foreach ($arResult['FILTER']['TYPES'] as $key => $type):?>
			<?if (strlen($type) <= 0) continue;?>
			<!--<input type="checkbox" id="checkbox_1" name="checkbox_1" class="custom_checkbox">/-->
			<input class="custom_checkbox"  type="checkbox" name="types[]" id="breands_<?=$key?>"  value="<?=$type?>" <?if (in_array($type, $_REQUEST['types'])):?> checked<?endif;?> />
			<label for="breands_<?=$key?>"><?=$type?></label>
			<?endforeach;?>
		</div>
		<?endif;?>
		<?if (!empty($arResult["FILTER"]["DIRECTIONS"])):?>
			<div class="filter-block">
				<p class="block-title">Направление</p>
				<?foreach ($arResult["FILTER"]["DIRECTIONS"] as $key => $arDirection):?>
					<input type="hidden" name="direction_<?=$arDirection['ID']?>_left" value=<?=$arDirection['LEFT_MARGIN']?> />
					<input type="hidden" name="direction_<?=$arDirection['ID']?>_right" value=<?=$arDirection['RIGHT_MARGIN']?> />
					<input  class="custom_checkbox"  name="direction[]" <?=(in_array($arDirection['ID'], $_REQUEST['direction']))?'checked':''?> id="doc_<?=$key+1?>" type="checkbox" value="<?=$arDirection['ID']?>">
					<label for="doc_<?=$key+1?>"><?=$arDirection['NAME']?></label>
					<select name="section[]">
						<option value="">Выбрать категорию</option>
						<?foreach ($arDirection['SECTIONS'] as $arSection):?>
							<option <?if (in_array($arDirection['ID'], $_REQUEST['section'])):?>selected <?endif;?>value="<?=$arSection['ID']?>"><?=$arSection['NAME']?></option>
							<?foreach ($arSection['SECTIONS'] as $one_arSection):?>
							<option <?if (in_array($one_arSection['ID'], $_REQUEST['section'])):?>selected <?endif;?>value="<?=$one_arSection['ID']?>">-- <?=$one_arSection['NAME']?></option>
							<?endforeach;?>
						<?endforeach;?>
					</select>
					<?foreach ($arDirection['SECTIONS'] as $arSection):?>
						<input type="hidden" name="section_<?=$arSection['ID']?>_left" value=<?=$arSection['LEFT_MARGIN']?> />
						<input type="hidden" name="section_<?=$arSection['ID']?>_right" value=<?=$arSection['RIGHT_MARGIN']?> />
					<?endforeach;?>
				<?endforeach;?> 
			</div>
		<?endif;?>
		<?if (!empty($arResult['FILTER']['BRANDS'])):?>
		<div class="filter-block">
			<p class="block-title">Бренд</p>
			<div class="checkbox_wrap open-block">
			<?foreach ($arResult['FILTER']['BRANDS'] as $brandId => $brandName):?>
				<input class="custom_checkbox"  type="checkbox" name="brands[]" id="breands_<?=$brandId?>" value="<?=$brandId?>" <?if (in_array($brandId, $_REQUEST['brands'])):?> checked<?endif;?> />
				<label for="breands_<?=$brandId?>"><?=$brandName?></label>
			<?endforeach;?>
			</div>	
			<a href="#" class="input-btn gray-btn all open-next">показать все</a>
		</div>
		<?endif;?>
		<div class="filter-block">
			<p class="block-title">Артикул или название</p>
			<input type="text" id="articul" name="product" value="<?=$_REQUEST['product']?>">
		</div>
		<input type="submit" class="empty-btn input-btn gray-btn" value="Показать результаты">
	</form>
</div>
<?
#DebugMessage($arResult['FILTER']);
# Ниже список: иерархия БРЕНД -> Тип документа -> документы этого типа
?>
<section class="results">
	<?$count = count($arResult['ITEMS'])?>
	<p class="results-title">Найдено <span class="green"><?=$count?></span> <?=number($count, array('документ', 'документа', 'документов'));?></p>
	<div class="result-block">
		<?$i = 1;?>
		 <?foreach ($arResult["BRANDS"] as $brand => $arBrand):?>
		 	<?if ($i == 1) $vis = ""; else $vis = "display:none;";?>
			 <?$i++;?>
		 	<div class="brand-item" style="<?=$vis?>">
		 	<h2 style="margin-top: 20px;"><?=$brand?></h2>
	    		<?foreach ($arBrand as $type => $arType):?>
	    			<div class="brand-doc-type">
				<h3 style="margin-top: 20px;"><?=$type?></h3>
					<? $cnt = 3;?>
					<? $visisble = "";?>
					<div class="result-item">
					<?foreach ($arType as $arItem):?>
						<div class="brand-doc-name"  style="<?=$visisble?>">
							<span class="icon"></span>
							<div class="item-title">
								<a href="<?=$arItem["FILE"]["SRC"]?>" target="_blank"><?=$arItem['NAME']?></a>
								<span class="size"><?#=$arItem['FILE']['EXTENTION']?> <?=$arItem["FILE"]['FILE_SIZE']?> mb</span>
							</div>
							<?$cnt--;?>
						</div>
						<?if ($cnt <= 0 && count($arType) > 3):?>
							<? $visisble = "display:none;";?>
						<?endif;?>
					<?endforeach;?>
					<?if (count($arType) > 3):?>
					<a  href="#" class="btn arrow_btn" style="margin-top: 20px; margin-bottom: 20px;">ЕЩЕ <?=$type?></a>
					<?endif;?>
					</div>
				</div>
	    		<?endforeach;?>
	    		</div>
		 <?endforeach;?>
		 <?if ($i > 2):?>
		 <a href="javascript:void(0);" class="btn arrow_btn show_next_brand" style="margin-top: 20px; margin-bottom: 20px;">ЕЩЕ Бренды</a>
		 <?endif;?>
	</div>
</section>