<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?if (strlen($arResult['PREV']) > 0 || strlen($arResult['NEXT']) > 0):?>
<aside class="pages-nav">
	<?if (strlen($arResult['NEXT']) > 0):?>
	<a href="<?=$arResult['NEXT']?>" class="page-prev">назад</a>
	<?endif;?>
	<?if (strlen($arResult['PREV']) > 0):?>
	<a href="<?=$arResult['PREV']?>" class="page-next">вперед</a>
	<?endif;?>
</aside>
<?endif;?>
<div class="news-items">
	<section class="news-one">
		<div class="article-meta">
			<h2><a href="<?=$arResult["DETAIL_PAGE_URL"]?>"><?=$arResult["NAME"]?></a></h2>
			<?$date_from = FormatDate("d F", MakeTimeStamp($arResult["ACTIVE_FROM"]));?>
			<div class="article-info"> <span class="time"><?=$date_from ?></span></div>                        
		</div>
		<?if (strlen($arResult['DETAIL_PICTURE']['SRC']) > 0):?>
			<img src="<?=$arResult['DETAIL_PICTURE']['SRC']?>" alt="<?=$arResult['DETAIL_PICTURE']['ALT']?>">
		<?endif;?>
		<div class="article-body">
			<?=$arResult['DETAIL_TEXT']?>
		</div>
	</section>

	<!--<div class="one-news-slider">
		<div class="owl-carousel" data-pagination="false">
			<div class="item">
				<img src="<?=MOBILE_PATH?>images/img2.jpg">
			</div>
			<div class="item">
				<img src="<?=MOBILE_PATH?>images/img2.jpg">
			</div>
			<div class="item">
				<img src="<?=MOBILE_PATH?>images/img2.jpg">
			</div>
		</div>                  
	</div>/-->
</div>

<a href="<?=$arParams['SEF_FOLDER']?>" class="btn arrow_btn">ПОКАЗАТЬ ЕЩЕ статьи</a>
<?$APPLICATION->IncludeFile(
	INCLUDE_AREAS."block-news-bottom-menu.php",
	Array(),
	Array("MODE"=>"html", "NAME"=>"Right Panel")
);?>
<?#DebugMessage($arResult);?>