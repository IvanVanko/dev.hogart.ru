<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
if (count($arResult['ITEMS']) > 0):?>
	<h3><?= GetMessage("Календарь событий")?></h3>
	<div class="side-datepicker-cnt">
        <ul class="js-dateArray" id="side_news_array">
			<?foreach ($arResult["ITEMS"] as $arItem):
				$date = ConvertDateTime($arItem['ACTIVE_FROM'], "MM/DD/YYYY", LANGUAGE_ID);?>
				<li data-date="<?=$date?>">
					<a href="<?= $arItem['DETAIL_PAGE_URL'] ?>"><?= $arItem['NAME'] ?></a>
				</li>
			<?endforeach;?>
		</ul>
		<div data-datepicker="#side_news_array" class="hogart-dark js-datepicker-hogart" data-lang="<?=LANGUAGE_ID?>"></div>
	</div>
<?endif; ?>