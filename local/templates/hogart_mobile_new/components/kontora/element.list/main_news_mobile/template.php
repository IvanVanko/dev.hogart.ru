<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$this->setFrameMode(true);?>
<?if (!empty($arResult['ITEMS'])):?>
<div class="news_wrap menu_inner">
	<div class="owl-carousel" data-pagination="true">
		<?foreach ($arResult['ITEMS'] as $arItems):?>
		<div class="news-item">
			<?
				$date = explode('.', $arItems['DATE_ACTIVE_FROM']);
				$date_from = FormatDate("Y-m-d", MakeTimeStamp($arItems["DATE_ACTIVE_FROM"]));
				#DebugMessage($arItems);
			?>
			<time><?=$date_from?></time>
			<p><a href="<?=$arItems['DETAIL_PAGE_URL']?>"><?=$arItems['NAME']?></a></p>
		</div>
		<?endforeach;?>
	</div>
</div>
<?endif;?>
<?
#DebugMessage($arResult['ITEMS']);
?>
