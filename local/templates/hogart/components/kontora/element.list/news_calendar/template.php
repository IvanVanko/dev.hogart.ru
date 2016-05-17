<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
if (count($arResult['ITEMS']) > 0):?>
	<h2><?= GetMessage("Календарь событий")?></h2>
	<div class="side-datepicker-cnt">
        <ul class="js-dateArray" id="side_news_array">
			<?foreach ($arResult["ITEMS"] as $arItem):
				$date = ConvertDateTime($arItem['ACTIVE_FROM'], "MM/DD/YYYY", "ru");?>
				<li data-date="<?=$date?>">
					<a href="<?= $arItem['DETAIL_PAGE_URL'] ?>"><?= $arItem['NAME'] ?></a>
				</li>
			<?endforeach;?>
		</ul>
		<div data-datepicker="#side_news_array" class="js-datepicker"></div>
	</div>
<?endif; ?>