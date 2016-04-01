<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
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
                    <input<?if (in_array($arDirection['ID'], $_REQUEST['direction'])):?> checked<?endif?> name="direction[]" id="doc_<?=$key+1?>" type="checkbox" value="<?=$arDirection['ID']?>">
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

        <h2>Артикул или название</h2>

        <div class="field custom_label">
            <input type="text" id="email" name="product" value="<?=$_REQUEST['product']?>">
        </div>

        <button class="empty-btn">Найти документы</button>
        <br /><br /><br />
        <a href="<?=$APPLICATION->GetCurDir(true)?>?q=<?=$_REQUEST['q']?>#doc_tab" class="empty-btn link">сбросить запрос</a>
        <br /><br /><br />
    </form>
</div>