<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
// заменяем $arResult эпилога значением, сохраненным в шаблоне
if(isset($arResult['arResult'])) {
   $arResult =& $arResult['arResult'];
         // подключаем языковой файл
   global $MESS;
   include_once(GetLangFileName(dirname(__FILE__).'/lang/', '/template.php'));
} else {
   return;
}
?>
<?$page = $APPLICATION->GetCurDir(true);?>
<div class="row">
	<div class="col-md-9">
		<h3><?$APPLICATION->ShowTitle()?></h3>
		<?if (count($arResult["ITEMS"]) > 0):?>
			<ul class="info-list">
				<?foreach ($arResult["ITEMS"] as $arItem):
					$this->AddEditAction($arItem["ID"], $arItem["EDIT_LINK"], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
					$this->AddDeleteAction($arItem["ID"], $arItem["DELETE_LINK"], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));?>
					<li id="<?=$this->GetEditAreaId($arItem['ID'])?>">
						<div class="date"><?=CIBlockFormatProperties::DateFormat('j F Y', MakeTimeStamp($arItem["ACTIVE_FROM"], CSite::GetDateFormat()))?> Г.</div>
						<h4><a href="<?=$arItem['DETAIL_PAGE_URL']?>"><?=$arItem["NAME"]?></a></h4>

						<p><?=$arItem['PREVIEW_TEXT']?></p>
					</li>
				<?endforeach;?>
			</ul>
		<?endif; ?>
	</div>
	<div class="col-md-3 aside">
		<form action="#">
			<?if (!empty($arResult['FILTER']['BRANDS'])):?>
				<h3><?= GetMessage("Бренд") ?></h3>
				<div class="row breands hide-big-cnt" data-hide="Еще">
					<?foreach ($arResult['FILTER']['BRANDS'] as $key => $arBrand):?>
						<div data-brand-key="<?= $key ?>" class="col-md-6 checkbox <?= ($key > 3 ? "more" : "") ?>" style="margin-top: 0">
							<label>
								<input <?= ($arBrand['CHECKED'] ? "checked" : "") ?> type="checkbox" name="brand[]" id="breands_<?=$key+1?>" value="<?=$arBrand['ID']?>"/>
								<?=$arBrand['VALUE']?>
							</label>
						</div>
					<?endforeach;?>
					<? if ($key > 3): ?>
						<div class="col-sm-12">
							<span class="btn-more" onclick="__more(this)">Еще <i class="fa"></i></span>
							<script>
								function __more (more) {
									$('.more', $(more).parents('.breands')).animate({ height: "toggle" });
									$(more).toggleClass('opened');
								}
							</script>
						</div>
					<? endif; ?>
				</div>
			<?endif;?>
			<h3><?= GetMessage("Ключевое слово") ?></h3>
			<div class="field">
				<input type="text" placeholder="" id="name" name="keyword" value="<?=$_REQUEST['keyword']?>">
			</div>
			<button class="btn btn-primary"><?= GetMessage("Найти Статьи") ?></button>
			<a href="<?= $page ?>" class="btn btn-link"><?=GetMessage("Сбросить запрос") ?></a>
			<br/><br/>
		</form>
	</div>
</div>