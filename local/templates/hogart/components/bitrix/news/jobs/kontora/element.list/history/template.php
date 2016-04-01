<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?if (count($arResult['ITEMS']) > 0):?>
	<aside class="sidebar js-fh js-fixed-block js-paralax-height" data-fixed="top">
	    <div class="inner js-paralax-item">
	        <div class="sidebar-vacancy">
	        	<h2>Истории успеха</h2>
	        	<ul class="faces">
					<?foreach ($arResult["ITEMS"] as $arItem): //Цикл по всем элементам
						$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
						$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));?>
						<li>
		        			<span class="img"><img src="<?=$arItem['PREVIEW_PICTURE']['SRC']?>" alt="" title="" /></span>
		        			<span class="face-name"><?=$arItem['NAME']?><br /><?=$arItem['PROPERTIES']['name']['VALUE']?></span>
		        			<span class="face-job"><?=$arItem['PROPERTIES']['post']['VALUE']?></span>
		        			<span class="face-pre-text"><?=$arItem['PREVIEW_TEXT']?></span>
		        			<a href="/history/" class="read-more">Читать историю полностью</a>
		        		</li>
					<?endforeach;?>
				</ul>
			</div>
		</div>
	</aside>
<?endif; ?>


