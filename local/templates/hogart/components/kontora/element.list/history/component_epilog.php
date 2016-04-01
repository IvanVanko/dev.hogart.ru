<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?
// заменяем $arResult эпилога значением, сохраненным в шаблоне
if(isset($arResult['arResult'])) {
   $arResult =& $arResult['arResult'];
} else {
   return;
}
?>
<div class="inner">
    <h1><?$APPLICATION->ShowTitle();?></h1>
    <?if (count($arResult['ITEMS']) > 0):?>
	    <ul class="history-human-list">
	        <?foreach ($arResult['ITEMS'] as $arItem):?>
		        <li>
		            <?if (!empty($arItem['PREVIEW_PICTURE']['SRC'])):?>
			            <div class="photo">
			                <div class="inner">
			                    <img src="<?=$arItem['PREVIEW_PICTURE']['SRC']?>" alt=""/>
			                </div>
			            </div> 
		            <?endif;?>               
		            <div class="info">
		                <div class="name">
		                    <div class="line"><?=$arItem['NAME']?></div>
		                    <?if (!empty($arItem['PROPERTIES']['name']['VALUE'])):?>
		                    	<div class="line"><?=$arItem['PROPERTIES']['name']['VALUE']?></div>
		                    <?endif;?>
		                </div>
		                <?if (!empty($arItem['PROPERTIES']['post']['VALUE'])):?>
		                	<div class="sign"><?=$$arItem['PROPERTIES']['post']['VALUE']?></div>
		                <?endif;?>
		                <?=$arItem['DETAIL_TEXT']?>
		            </div>
		        </li>
	        <?endforeach;?>
	    </ul>
    <?endif;?>
</div>