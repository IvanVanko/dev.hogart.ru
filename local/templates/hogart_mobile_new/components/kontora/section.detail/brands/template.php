<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
if (!empty($arResult["PROPERTIES"]["activities"]["VALUE"])):?>
	<aside class="sidebar js-fh js-fixed-block js-paralax-height" data-fixed="top">
	    <div class="inner js-paralax-item">
	        <div class="company-side-cnt padding">
		        <h2>Основные направления деятельности</h2>
		        <?=$arResult["PROPERTIES"]["activities"]["~VALUE"]["TEXT"]?>
	        </div>
	    </div>
	</aside>
<?endif;?>