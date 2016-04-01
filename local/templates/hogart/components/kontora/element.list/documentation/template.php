<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?$page = $APPLICATION->GetCurDir(true);?>

<div class="inner doc-list-box">
	<h1><?=\Bitrix\Main\Localization\Loc::getMessage("doc_title")?></h1>
	<div class="row sticky">
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
		<li class="li-container head<?if($_REQUEST['fbrand'] == 'y'):?> active<?endif?>">
	        <div class="field custom_checkbox">
	            <input type="checkbox" name="breands_<?=$i?>" id="breands_<?=$i?>" />
	            <label><span class="fake-checkbox"></span><?= !empty($brand)?$brand:'Без бренда' ?></label>
	        </div>
	    </li>
	    <?$i++;?>
	    <?foreach ($arBrand as $type => $arType):?>
	        <div class="item-box">
		    <li class="li-container head_sub<?if($_REQUEST['fbrand'] == 'y'):?> active<?endif?>" style="display: <?if($_REQUEST['fbrand'] == 'y'):?>list-item<?else:?><?endif?>;">
		        <div class="field custom_checkbox">
		            <input type="checkbox" name="doc_brands_<?=$i?>" id="doc_brands<?=$i?>"/>
		            <label for="doc_brands_<?=$i?>"><span class="fake-checkbox"></span><h4><?=$type?> (<?=count($arType)?>)</h4></label>
		        </div>
		    </li>
		    <?$i++;?>
				<?if (count($arType)>3):?>
					<?foreach ($arType as $arItem):?>
						<li class="item" style="display: <?if($_REQUEST['fbrand'] == 'y'):?>list-item<?else:?><?endif?>;">
							<div class="field custom_checkbox">
								<input
									type="checkbox"
									name="document"
									id="doc_brands<?=$i?>"
									value="/download.php?id=<?=$arItem['PROPERTIES']['file']['VALUE']?>&name=<?=$arItem['NAME']?>.<?=$arItem['FILE']['EXTENTION']?>"
									data-file-id="<?=$arItem['ID'] ?>"
								/>
								<label class="doc-download-link">
									<span class="fake-checkbox"></span>
									<span class="icon-acrobat"><?=$arItem['NAME']?></span>
									<span class="green">— .<?=$arItem['FILE']['EXTENTION']?>, <?=$arItem["FILE"]['FILE_SIZE']?> mb</span>
								</label>
								<img class="doc-download" src="/images/download2.png" alt=""/>
							</div>
						</li>
						<?$i++;?>
					<?endforeach;?>
				<?else:?>
					<?foreach ($arType as $arItem):?>
						<li class="item" style="display: <?if($_REQUEST['fbrand'] == 'y'):?>list-item<?else:?><?endif?>;">
							<div class="field custom_checkbox">
								<input
									type="checkbox"
									name="document"
									id="doc_brands<?=$i?>"
									value="/download.php?id=<?=$arItem['PROPERTIES']['file']['VALUE']?>&name=<?=$arItem['NAME']?>.<?=$arItem['FILE']['EXTENTION']?>"
									data-file-id="<?=$arItem['ID'] ?>"
								/>
								<label class="doc-download-link" for="doc_brands<?=$i?>">
									<span class="fake-checkbox"></span>
									<span class="icon-acrobat"><?=$arItem['NAME']?></span>
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
		                    <label for="breands_<?=$type?>"><span class="fake-checkbox"></span><?=$type?></label>
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
		                        <label for="breands_<?=$brandId?>"><span class="fake-checkbox"></span><?=$brandName?></label>
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