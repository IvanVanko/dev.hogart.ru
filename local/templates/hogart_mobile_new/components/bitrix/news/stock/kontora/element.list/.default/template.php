<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<div class="main-filter news-filter">
	<div class="btn show-filter-btn open-next"></div>
	<form action="" class="main-filter-form hidden_block open-block">
		<div class="filter-block">
			<input type="reset" class="input-btn gray-btn" value="сбросить">
		</div>
		<?#DebugMessage($arResult['FILTER']['DIRECTIONS']);?>
		<? if (count($arResult['FILTER']['DIRECTIONS']) > 0): ?>
		<div class="filter-block">
			<p class="block-title">Направление</p>
			<select name="direction[]" >	
			<? foreach ($arResult['FILTER']['DIRECTIONS'] as $key => $arDirection): ?>
			<option value="<?= $arDirection['ID'] ?>" id="doc_<?= $key + 1 ?>" <? if (in_array($arDirection['ID'], $_REQUEST['direction'])): ?>selected<? endif; ?>><?= $arDirection['NAME'] ?></option>
			<? endforeach; ?>
			</select>
		</div>
		<? endif; ?>
		<? if (count($arResult['FILTER']['BRANDS']) > 0): ?>
		<div class="filter-block">
			<p class="block-title">Бренд</p>
			<div class="checkbox_wrap open-block">
			<? foreach ($arResult['FILTER']['BRANDS'] as $key => $arBrand): ?>
				<input type="checkbox" id="breands_<?= $key + 1 ?>" name="brand[]" class="custom_checkbox" value="<?= $arBrand['ID'] ?>" <? if (in_array($arBrand['ID'], $_REQUEST['brand'])): ?> checked <? endif; ?> />
				<label for="breands_<?= $key + 1 ?>"><?= $arBrand['VALUE'] ?></label>
			<? endforeach; ?>
			</div>
			<a href="javascriptvoid(0);" class="input-btn gray-btn all open-next">показать все</a>
		</div>
		<? endif; ?>
		<? if (count($arResult['FILTER']['CITY']) > 0): ?>
		<div class="filter-block">
			<p class="block-title">Город</p>
			<select name="city">
				<option value="">Выбрать город</option>
				<? foreach ($arResult['FILTER']['CITY'] as $city): ?>
				<option value="<?= $city['ID'] ?>" <? if ($_REQUEST['city'] == $city['ID']): ?> selected<? endif; ?>><?= $city['VALUE'] ?></option>
			<? endforeach; ?>
			</select>
		</div>
		<?endif?>
		<input type="submit" class="input-btn gray-btn" value="Показать результаты">
	</form>
</div>

<? if (count($arResult["ITEMS"]) > 0): ?>
<div class="news-items">
	<?foreach ($arResult["ITEMS"] as $arItem):?>
	<article class="news-one">
		<h2><a href="<?= $arItem['DETAIL_PAGE_URL'] ?>"><?=$arItem['NAME']?></a></h2>
		<p><?= $arItem['PREVIEW_TEXT'] ?></p>
		<? $date_from = FormatDate("d F", MakeTimeStamp($arItem["ACTIVE_FROM"]));
		$date_to = FormatDate("d F", MakeTimeStamp($arItem["ACTIVE_TO"]));
		?>
		<div class="article-info"> 
			<span class="time"><?= $date_from . ' – ' . $date_to ?>&nbsp;<?
				$dateFinish = FormatDate("d.m.Y", MakeTimeStamp($arItem["ACTIVE_TO"]));
				$now = date($DB->DateFormatToPHP(CSite::GetDateFormat("SHORT")), time());
				if ($arItem['ACTIVE'] == Y && strtotime($now) > strtotime($dateFinish)):
					?>
					<br /><strong>(Акция завершена)</strong>
				<? endif; ?>
			</span>
		</div>
	</article>
	<?endforeach;?>
</div>
<?endif;?>

<!--<a href="#" class="btn arrow_btn">ПОКАЗАТЬ ЕЩЕ Акции</a>/-->
<br />
<?#= $arResult["NAV_STRING"];  echo "<br />";?>

<?$APPLICATION->IncludeFile(
	INCLUDE_AREAS."block-news-bottom-menu.php",
	Array(),
	Array("MODE"=>"html", "NAME"=>"Right Panel")
);?>            
<?
#DebugMessage($arResult);
?>
