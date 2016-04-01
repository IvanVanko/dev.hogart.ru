<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?if (!empty($arResult["ITEMS"])):?>
	<ul class="doc-tab-list">
	    <?foreach ($arResult["ITEMS"] as $arItem):?>
		    <li>
		        <?/*if (!empty($arItem['BREADCRUMBS'])):?>
			        <ul class="breadcrumbs">
			            <?foreach ($arItem['BREADCRUMBS'] as $section):?>
			            	<li><a href="<?=$section['SECTION_PAGE_URL']?>"><?=$section['NAME']?></a></li>
			            <?endforeach;?>
			        </ul>
			    <?endif;*/?>
		        <a class="head" target="_blank" href="<?=CFile::GetPath($arItem['PROPERTIES']['file']['VALUE'])?>"><?=$arItem['NAME']?></a>
		    </li>
		<?endforeach;?>
	</ul>
	<div class="text-center">
		<?=$arResult["NAV_STRING"]?>
	</div>
<?else:?>
	<p><font class="notetext">По вашему запросу ничего не найдено, позвоните по телефону +7 (495) 788-11-12, +7 (812) 703-41-14 мы постараемся решить вашу задачу.</font></p>
<?endif;?>