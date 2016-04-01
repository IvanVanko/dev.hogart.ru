<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<div class="main-filter">
	<div class="btn show-filter-btn open-next"></div>
	<form action="" class="main-filter-form hidden_block open-block">
		<div class="filter-block ">
			<p class="block-title">Тип документа</p>
			<input type="checkbox" id="checkbox_1" name="checkbox_1" class="custom_checkbox">
			<label for="checkbox_1">Инструкция по монтажу</label>
			<input type="checkbox" id="checkbox_2" name="checkbox_2" class="custom_checkbox">
			<label for="checkbox_2">Каталог</label>
			<input type="checkbox" id="checkbox_3" name="checkbox_3" class="custom_checkbox">
			<label for="checkbox_3">Гарантийный талон</label>
			<input type="checkbox" id="checkbox_4" name="checkbox_4" class="custom_checkbox">
			<label for="checkbox_4">Буклет</label>
			<input type="checkbox" id="checkbox_5" name="checkbox_5" class="custom_checkbox">
			<label for="checkbox_5">Техническая карта</label>
			<input type="checkbox" id="checkbox_5" name="checkbox_5" class="custom_checkbox">
			<label for="checkbox_5">Сертификат</label>
		</div>
	
		<div class="filter-block">
			<p class="block-title">Направление</p>
			<select name="select1" id="select1">
				<option value="">Отопление</option>
				<option value="2">Отопление1</option>
			</select>
			<select name="select2" id="select2">
				<option value="">Категория 1</option>
				<option value="2">Категория 2</option>
			</select>
		</div>
		<div class="filter-block">
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
		</div>
		<div class="filter-block">
			<p class="block-title">Артикул или название</p>
			<input type="text" name="articul" id="articul" value="">
		</div>
		<input type="submit" class="input-btn gray-btn" value="Показать результаты">
	</form>
</div>
<?
#DebugMessage($arResult['DOCUMS']);
$i=0;
?>

<section class="results">
	<?$count = count($arResult['ITEMS'])?>
	<p class="results-title">Найдено <span class="green"><?=$count?></span> <?=number($count, array('документ', 'документа', 'документов'));?></p>
	<div class="result-block">
		<?foreach ($arResult['DOCUMS'] as $k=>$arItems):?>
		<h2 style="margin-top: 20px;"><?=$k?></h2>
		<?
		
		$type = $arResult['DOCUMS'][$k][$i]["PROPERTIES"]["type"]["VALUE"];
		#DebugMessage($type, "type");
		#DebugMessage($type, "oldtype");
		?>
		<h3><? echo $type?></h3>
		<?foreach ($arItems as $k1=>$arVal):?>
			<div class="result-item">
				<span class="icon"></span>
				<div class="item-title">
					<a href="<?=$arVal["FILE"]["SRC"]?>" target="_blank"><?=$arVal["NAME"]?></a>
					<span class="size"><?=$arVal["FILE"]["FILE_SIZE"]?> Мб</span>
				</div>
			</div>
			<?#$i++;?>
		<?endforeach;?>
		<?$i=$k1;###DebugMessage($i);?>
		<?$oldtype = $arResult['DOCUMS'][$k][$i]["PROPERTIES"]["type"]["VALUE"];?>
		<?endforeach;?>
	</div>
	<!--<a href="#" class="btn arrow_btn">остальные каталоги</a>
	<div class="result-block">
		<h3>Буклеты</h3>

		<div class="result-item">
			<span class="icon"></span>
			<div class="item-title">
				Каталог "Комплектующие и принадлежности для систем отопления, ГВС, комфортного климата 2013/2014"
				<span class="size">2,5 Мб</span>
			</div>
		</div>
		<div class="result-item">
			<span class="icon"></span>
			<div class="item-title">
				Каталог "Комплектующие и принадлежности для систем отопления, ГВС, комфортного климата 2013/2014"
				<span class="size">2,5 Мб</span>
			</div>
		</div>
		<div class="result-item">
			<span class="icon"></span>
			<div class="item-title">
				Каталог "Комплектующие и принадлежности для систем отопления, ГВС, комфортного климата 2013/2014"
				<span class="size">2,5 Мб</span>
			</div>
		</div>
	</div>/-->
	<!--<a href="#" class="btn arrow_btn">ЕЩЕ Документы</a>/-->
	<? echo $arResult["NAV_STRING"]; ?>
</section>
<?$APPLICATION->IncludeFile(
INCLUDE_AREAS."block-docum-bottom-menu.php",
Array(),
Array("MODE"=>"html", "NAME"=>"Right Panel")
);?>
<?
#DebugMessage($arResult["DOCUMS"]);
?>


<?/*?>
<?$page = $APPLICATION->GetCurDir(true);?>
<div class="inner doc-list-box">
	<h1><?$APPLICATION->ShowTitle()?></h1>
	<div class="row">
	    <div class="col2">
	        <?$count = count($arResult['ITEMS'])?>
	        <h2>Найдено <?=$count?> <?=number($count, array('документ', 'документа', 'документов'));?></h2>
	    </div>
	    <div class="col2 text-right">
	        <a href="/arch/" class="icon-doc-sc">Скачать выбранные</a>
	    </div>
	</div>

    <?
	$i = 1;
	foreach ($arResult["BRANDS"] as $brand => $arBrand):
	?>
	<ul class="doc-loadlist doc-box">
		<li class="head<?if($_REQUEST['fbrand'] == 'y'):?> active<?endif?>">
	        <div class="field custom_checkbox">
	            <input type="checkbox" name="breands_<?=$i?>" id="breands_<?=$i?>" />
	            <label for="breands_<?=$i?>"><?= !empty($brand)?$brand:'Без бренда' ?></label>
	        </div>
	    </li>
	    <?$i++;?>
	    <?foreach ($arBrand as $type => $arType):?>
	        <div class="item-box">
		    <li class="head_sub<?if($_REQUEST['fbrand'] == 'y'):?> active<?endif?>" style="display: <?if($_REQUEST['fbrand'] == 'y'):?>list-item<?else:?>none<?endif?>;">
		        <div class="field custom_checkbox">
		            <input type="checkbox" name="doc_brands_<?=$i?>" id="doc_brands<?=$i?>"/>
		            <label for="doc_brands_<?=$i?>"><h4><?=$type?> (<?=count($arType)?>)</h4></label>
		        </div>
		    </li>
		    <?$i++;?>
				<?if (count($arType)>3):?>
					<?foreach ($arType as $arItem):?>
						<li class="item" style="display: <?if($_REQUEST['fbrand'] == 'y'):?>list-item<?else:?>none<?endif?>;">
							<div class="field custom_checkbox">
								<input
									type="checkbox"
									value="<?=$arItem['DOWNLOAD_LINK']?>"
									name="document"
									id="doc_brands<?=$i?>"
									value="<?=CFile::GetPath($arItem['PROPERTIES']['file']['VALUE'])?>"
									data-file-id="<?=$arItem['ID'] ?>"
								/>
								<label for="doc_brands<?=$i?>">
									<span class="doc-download-link icon-acrobat"><?=$arItem['NAME']?></span>
									<span class="green">— .<?=$arItem['FILE']['EXTENTION']?>, <?=$arItem["FILE"]['FILE_SIZE']?> mb</span>
								</label>
								<img class="doc-download" src="/images/download2.png" alt=""/>
							</div>
						</li>
						<?$i++;?>
					<?endforeach;?>
				<?else:?>
					<?foreach ($arType as $arItem):?>
						<li class="item" style="display: <?if($_REQUEST['fbrand'] == 'y'):?>list-item<?else:?>none<?endif?>;">
							<div class="field custom_checkbox">
								<input
									type="checkbox"
									value="<?=$arItem['DOWNLOAD_LINK']?>"
									name="document"
									id="doc_brands<?=$i?>"
									value="<?=CFile::GetPath($arItem['PROPERTIES']['file']['VALUE'])?>" 
									data-file-id="<?=$arItem['ID'] ?>"
								/>
								<label for="doc_brands<?=$i?>">
									<span class="doc-download-link icon-acrobat"><?=$arItem['NAME']?></span>
									<span class="green">— .<?=$arItem['FILE']['EXTENTION']?>, <?=$arItem["FILE"]['FILE_SIZE']?> mb</span>
								</label>
	                            <img class="doc-download" src="/images/download2.png" alt=""/>
							</div>
						</li>
						<?$i++;?>
					<?endforeach;?>
				<?endif;?>
	        </div>
		<?endforeach;?>
	</ul>
	<?endforeach;?>
</div>
<aside class="sidebar js-fh js-fixed-block js-paralax-height" data-fixed="top">
    <div class="inner js-paralax-item">
        <div class="reg-side-cnt padding">
            <form action="">
				<?if (!empty($arResult['FILTER']['TYPES'])):?>
	            	<h2>Тип документа</h2>
	                <?foreach ($arResult['FILTER']['TYPES'] as $key => $type):?>
		                <div class="field custom_checkbox">
		                    <input 
		                    	type="checkbox" 
		                    	name="types[]" 
		                    	id="breands_<?=$type?>" 
		                    	value="<?=$type?>"
		                    	<?if (in_array($type, $_REQUEST['types'])):?> checked<?endif;?>
		                    />
		                    <label for="breands_<?=$type?>"><?=$type?></label>
		                </div>
	                <?endforeach;?>
	            <?endif;?>
                <?if (!empty($arResult["FILTER"]["DIRECTIONS"])):?>
	                <h2>Направление</h2>
	                <?foreach ($arResult["FILTER"]["DIRECTIONS"] as $key => $arDirection):?>
	                	<div class="field custom_checkbox">
		                    <input type="hidden" name="direction_<?=$arDirection['ID']?>_left" value=<?=$arDirection['LEFT_MARGIN']?> />
		                    <input type="hidden" name="direction_<?=$arDirection['ID']?>_right" value=<?=$arDirection['RIGHT_MARGIN']?> />
		                    <input name="direction[]" <?=(in_array($arDirection['ID'], $_REQUEST['direction']))?'checked':''?> id="doc_<?=$key+1?>" type="checkbox" value="<?=$arDirection['ID']?>">
		                    <label for="doc_<?=$key+1?>"><?=$arDirection['NAME']?></label>
		               		<div class="isChecked">
		                        <div class="field custom_select">
		                            <select name="section[]">
		                                <option value="">Выбрать категорию</option>
										<?foreach ($arDirection['SECTIONS'] as $arSection):?>
											<option <?if (in_array($arDirection['ID'], $_REQUEST['section'])):?>selected <?endif;?>value="<?=$arSection['ID']?>"><?=$arSection['NAME']?></option>
											<?foreach ($arSection['SECTIONS'] as $one_arSection):?>
												<option <?if (in_array($one_arSection['ID'], $_REQUEST['section'])):?>selected <?endif;?>value="<?=$one_arSection['ID']?>">-- <?=$one_arSection['NAME']?></option>
											<?endforeach;?>
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
	                <h2>Бренд</h2>
	                <div class="breands hide-big-cnt" data-hide="Еще бренды">
	                    <?$j = 1;
	                    foreach ($arResult['FILTER']['BRANDS'] as $brandId => $brandName):?>
		                    <?if ($j == 4):?>
		                    	<div class="hide-block">
		                    <?endif;?>
		                    <div class="field custom_checkbox">
		                        <input 
		                        	type="checkbox" 
		                        	name="brands[]" 
		                        	id="breands_<?=$brandId?>" 
		                        	value="<?=$brandId?>"
		                        	<?if (in_array($brandId, $_REQUEST['brands'])):?> checked<?endif;?>
		                        />
		                        <label for="breands_<?=$brandId?>"><?=$brandName?></label>
		                    </div>
		                    <?$j++;?>
	                    <?endforeach;?>
                	</div>
                <?endif;?>
                <h2>Название</h2>
                <div class="field custom_label">
                    <input type="text" id="email" name="product" value="<?=$_REQUEST['product']?>">
                </div>
                <button class="empty-btn">Найти документы</button>
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
