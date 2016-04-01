<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
if (!empty($arResult["PROPERTIES"]["activities"]["VALUE"])):?>
	<div class="company-side-cnt padding">
        <h2>Основные направления деятельности</h2>
		<?=$arResult["PROPERTIES"]["activities"]["~VALUE"]["TEXT"]?>
	</div>
<?endif;?>