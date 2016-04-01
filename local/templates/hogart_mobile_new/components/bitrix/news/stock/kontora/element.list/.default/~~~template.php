<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<div class="main-filter news-filter">
				<div class="btn show-filter-btn open-next"></div>
				<form action="" class="main-filter-form hidden_block open-block">
					<div class="filter-block">
						<input type="reset" class="input-btn gray-btn" value="сбросить">
					</div>
					<div class="filter-block">
						<p class="block-title">Направление</p>
						<select name="select1" id="select1">
							<option value="1">Отопление</option>
							<option value="2">Отопление</option>
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
						<p class="block-title">Город</p>
						<select name="select2" id="select2">
							<option value="">Все</option>
							<option value="2">город1</option>
						</select>
						
					</div>
					
					<input type="submit" class="input-btn gray-btn" value="Показать результаты">

					
				</form>
			</div>

<? if (count($arResult["ITEMS"]) > 0): ?>
<div class="news-items">
	<article class="news-one">
		<h2><a href="#">ПЕРЕХОДИ НА REHAU –ПОЛУЧАЙ ИНСТРУМЕНТ М1</a></h2>
		<p>Акция для новых покупателей продукции REHAU!</p>
		<div class="article-info"> <span class="time">01 АПРЕЛЯ – 15 ОКТЯБРЯ</span></div>
	</article>
	<article class="news-one">
		<h2><a href="#">ПЕРЕХОДИ НА REHAU –ПОЛУЧАЙ ИНСТРУМЕНТ М1</a></h2>
		<p>Акция для новых покупателей продукции REHAU!</p>
		<div class="article-info"> <span class="time">01 АПРЕЛЯ – 15 ОКТЯБРЯ</span></div>
	</article>
	<article class="news-one">
		<h2><a href="#">ПЕРЕХОДИ НА REHAU –ПОЛУЧАЙ ИНСТРУМЕНТ М1</a></h2>
		<p>Акция для новых покупателей продукции REHAU!</p>
		<div class="article-info"> <span class="time">01 АПРЕЛЯ – 15 ОКТЯБРЯ</span></div>
	</article>
	<article class="news-one">
		<h2><a href="#">ПЕРЕХОДИ НА REHAU –ПОЛУЧАЙ ИНСТРУМЕНТ М1</a></h2>
		<p>Акция для новых покупателей продукции REHAU!</p>
		<div class="article-info"> <span class="time">01 АПРЕЛЯ – 15 ОКТЯБРЯ</span></div>
	</article>
</div>
<?endif;?>

<a href="#" class="btn arrow_btn">ПОКАЗАТЬ ЕЩЕ Акции</a>
<?$APPLICATION->IncludeFile(
	INCLUDE_AREAS."block-news-bottom-menu.php",
	Array(),
	Array("MODE"=>"html", "NAME"=>"Right Panel")
);?>            
<?/*?>
<? $page = $APPLICATION->GetCurDir(true); ?>
<div class="inner">
	<h1><? $APPLICATION->ShowTitle() ?></h1>
	<? if (count($arResult["ITEMS"]) > 0): ?>
		<ul class="action-list">
			<?
			foreach ($arResult["ITEMS"] as $arItem):
				$this->AddEditAction($arItem["ID"], $arItem["EDIT_LINK"], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
				$this->AddDeleteAction($arItem["ID"], $arItem["DELETE_LINK"], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
				?>
		<? $date_from = FormatDate("d F", MakeTimeStamp($arItem["ACTIVE_FROM"]));
		$date_to = FormatDate("d F", MakeTimeStamp($arItem["ACTIVE_TO"]));
		?>
				<li id="<?= $this->GetEditAreaId($arItem['ID']) ?>">
					<div class="img-wrap">
						<img class="js-vertical-center" title="<?=$arItem['NAME']?>" src="<?= $arItem['PREVIEW_PICTURE']['SRC'] ?>" alt=""/>
					</div>

					<div class="info-wrap">
						<div class="date">
							<?= $date_from . ' – ' . $date_to ?>
							<?
							$dateFinish = FormatDate("d.m.Y", MakeTimeStamp($arItem["ACTIVE_TO"]));
							$now = date($DB->DateFormatToPHP(CSite::GetDateFormat("SHORT")), time());
							if ($arItem['ACTIVE'] == Y && strtotime($now) > strtotime($dateFinish)):
								?>
								<strong>(Акция завершена)</strong>
							<? endif; ?>
						</div>
						<a class="head" href="<?= $arItem['DETAIL_PAGE_URL'] ?>"><?= $arItem['NAME'] ?></a>
						<p>
				<?= $arItem['PREVIEW_TEXT'] ?>
						</p>

					</div>
				</li>
	<? endforeach; ?>
		</ul>
<? endif; ?>
</div>
<aside class="sidebar js-fh js-fixed-block js-paralax-height" data-fixed="top">
	<div class="inner js-paralax-item">
		<div class="padding">
			<form action="#" class="action_filter">
<? if (count($arResult['FILTER']['DIRECTIONS']) > 0): ?>
					<h2>Направление</h2>

	<? foreach ($arResult['FILTER']['DIRECTIONS'] as $key => $arDirection): ?>
						<div class="field custom_checkbox">
							<input 
								name="direction[]" 
								id="doc_<?= $key + 1 ?>" 
								type="checkbox" 
								value="<?= $arDirection['ID'] ?>"
		<? if (in_array($arDirection['ID'], $_REQUEST['direction'])): ?>
									checked
						<? endif; ?>
								/>
							<label for="doc_<?= $key + 1 ?>"><?= $arDirection['NAME'] ?></label>
						</div>
					<? endforeach; ?>
					<div class="fixheight"></div>
					<? endif; ?>

<? if (count($arResult['FILTER']['BRANDS']) > 0): ?>
					<h2>Бренд</h2>
					<div class="breands hide-big-cnt" data-hide="Еще бренды">
	<? foreach ($arResult['FILTER']['BRANDS'] as $key => $arBrand): ?>
							<div class="field custom_checkbox">
								<input 
									type="checkbox" 
									name="brand[]" 
									id="breands_<?= $key + 1 ?>" 
									value="<?= $arBrand['ID'] ?>"
		<? if (in_array($arBrand['ID'], $_REQUEST['brand'])): ?>
										checked
							<? endif; ?>
									/>
								<label for="breands_<?= $key + 1 ?>"><?= $arBrand['VALUE'] ?></label>
							</div>
					<? endforeach; ?>
					</div>
<? endif; ?>
				<br/>

<? if ($arResult["custom_filter_count"]["sale"] > 0) { ?>
					<div class="field custom_checkbox">
						<input 
							type="checkbox" 
							name="sale" 
							id="breands_122" 
							value="Y"
	<? if ($_REQUEST['sale'] == 'Y'): ?>
								checked
					<? endif; ?>
							/>
						<label for="breands_122">Распродажа</label>
					</div>
<? } ?>
<? if ($arResult["custom_filter_count"]["markdown"] > 0) { ?>       
					<div class="field custom_checkbox">
						<input 
							type="checkbox" 
							name="markdown" 
							id="breands_133" 
							value="Y"
	<? if ($_REQUEST['markdown'] == 'Y'): ?>
								checked
					<? endif; ?>
							/>
						<label for="breands_133">Уценка</label>
					</div>
<? } ?>

				<div class="fixheight"></div>
						<? if (count($arResult['FILTER']['CITY']) > 0): ?>
					<h2>Город</h2>
					<div class="field custom_select">
						<select name="city">
							<option value="">Выбрать город</option>
								<? foreach ($arResult['FILTER']['CITY'] as $city): ?>
								<option 
									value="<?= $city['ID'] ?>"
										<? if ($_REQUEST['city'] == $city['ID']): ?>
										selected
								<? endif; ?>
									>
		<?= $city['VALUE'] ?>
								</option>
					<? endforeach; ?>
						</select>
					</div>
					<div class="fixheight"></div>
<? endif; ?>
				<div class="fixheight"></div>

				<a href="<?= $page ?>" class="empty-btn link">сбросить запрос</a>
				<br/><br/>
			</form>
		</div>
	</div>
</aside>
<?*/?>
