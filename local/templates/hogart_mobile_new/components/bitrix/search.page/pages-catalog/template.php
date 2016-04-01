<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
?>
<div class="main-filter">
	<div class="btn show-filter-btn open-next"></div>
	<form action="<?=$arResult["FORM_ACTION"]?>" class="main-filter-form hidden_block open-block">
		
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
			<!--<input type="text" name="articul" id="articul" value="">/-->
			<input type="text" name="q" value="<?=$_REQUEST['q']?>" id="articul" placeholder="Наименование или артикул"/>
		</div>
		<input type="submit" class="input-btn gray-btn" value="Показать результаты">
	</form>
</div>

<div class="search-content">
	<?if (count($arResult["SEARCH_GOODS"]) > 0):?>
	<h1>Результаты поиска по запросу "<?=$_REQUEST['q']?>"</h1>
	<div class="search-result-count">На сайте найдено <span class="green"><?=$arResult["COUNT"]?></span> результатов</div>

	<div class="search-results">
		<?foreach ($arResult["SEARCH_GOODS"] as $arItem):?>
		<div class="one_item">
			<?if (strlen ($arItem["IMG"]["src"]) > 0):?>
			<div class="item-img-wrap"><a href="<?=$arItem["URL"]?>"><img src="<?=$arItem["IMG"]["src"]?>"></a></div>
			<?endif;?>
			<div class="price"><a href="<?=$arItem["URL"]?>"><?=number_format($arItem["CATALOG_PRICE_1"], 0, ".", " ")?> Р</a></div>
			<div class="item_description">
				<span class="item_title"><a href="<?=$arItem["URL"]?>"><?=$arItem["NAME"]?></a></span>
				<div class="item_info">
					<a href="<?=$arItem["URL"]?>"></a><!--Артикул: 734 (2352 0001 3001)/-->
					<!--Под заказ. Срок поставки 3 дн/-->
				</div>
				<!--<div class="item_body">
					<dl class="clearfix">
						<dt><span>Мощность</span> </dt>
						<dd><span>кВт70</span></dd>
					</dl>
					<dl class="clearfix">
						<dt><span>Тип котла </span></dt>
						<dd><span>напольный</span></dd>
					</dl>
					<dl class="clearfix">
						<dt><span>Предназначение</span></dt>
						<dd><span>отопление</span></dd>
					</dl>
					<dl class="clearfix">
						<dt><span>Камера сгорания</span></dt>
						<dd><span>открытая</span></dd>
					</dl>
					
				</div>/-->
			</div>
		</div>
		<?endforeach;?>
	</div>	
	<?elseif(strlen($_REQUEST['q']) <= 0):?>
		<div class="search-result-item clearfix">
			<div class="title">
				Воспользуйтесь формой для поиска нужного Вам товара.
			</div>
		</div>	
	<?else:?>
		<div class="search-result-item clearfix">
			<div class="title">
				<?ShowNote(GetMessage("SEARCH_NOTHING_TO_FOUND"));?>
			</div>
		</div>
	<?endif;?>			
</div>


<!--<a href="#" class="btn arrow_btn">ПОКАЗАТЬ ЕЩЕ страницы</a>/-->
<? echo $arResult["NAV_STRING"]; ?>
<?$APPLICATION->IncludeFile(
	INCLUDE_AREAS."block-news-bottom-menu.php",
	Array(),
	Array("MODE"=>"html", "NAME"=>"Right Panel")
);?>
<?
#DebugMessage($arResult["SEARCH_GOODS"]);
?>