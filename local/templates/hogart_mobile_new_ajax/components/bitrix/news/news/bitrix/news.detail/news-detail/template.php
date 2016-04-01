<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$date_from = FormatDate("d F Y", MakeTimeStamp($arResult["ACTIVE_FROM"]));?>
<aside class="pages-nav">
	<a href="<?=$arResult['NEXT']?>" class="page-prev">назад</a>
	<a href="<?=$arResult['PREV']?>" class="page-next">вперед</a>
</aside>
<div class="news-items">
	<section class="news-one">
		<div class="article-meta">
			<h2><a href="<?= $arResult['DETAIL_PAGE_URL'] ?>"><?= $arResult['NAME'] ?></a></h2>
			<div class="article-info"> <time datetime="<?= $date_from ?>"><?= $date_from ?> </time> <a href="#">Информация</a>
			<?/*if (count($arItem["PROPERTIES"]["tag"]["VALUE"]) > 0 ):?>
				<? foreach ($arItem["PROPERTIES"]["tag"]["VALUE"] as $key => $tag): ?>
					<a href="<?= $APPLICATION->GetCurPageParam("tag[" . $arItem['PROPERTIES']['tag']['VALUE_ENUM_ID'][$key] . "]=" . $tag, array("tag")); ?>">— <?= $tag ?></a>
				<? endforeach; ?>
			<?endif;*/?>                
			</div>     
		</div>
		<? if (strlen($arResult['DETAIL_PICTURE']['SRC']) > 0):
			#DebugMessage($arResult['DETAIL_PICTURE']['SRC']);
			$share_img_src = $arResult['DETAIL_PICTURE']['SRC'];?>
				<!--<img src="<?= $arResult['DETAIL_PICTURE']['SRC'] ?>" alt="<?=$arResult['DETAIL_PICTURE']['ALT']?>" />/-->
				<img src="<?=MOBILE_PATH?>images/img2.jpg">
		<? endif; ?>

		<div class="article-body">
			<?= $arResult['DETAIL_TEXT'] ?>
		</div>
	</section>
	<div class="one-news-slider">
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
	</div>
 <? /*if (count($arResult['PROPERTIES']['photogallery']['VALUE']) > 0):?>
 	<div class="one-news-slider">
 		<div class="owl-carousel" data-pagination="false">
			<?foreach ($arResult['PROPERTIES']['photogallery']['VALUE'] as $key => $photo):
				$photoBig = CFile::GetPath($photo);
				$photo = CFile::ResizeImageGet($photo, array('width'=>320, 'height'=>320), BX_RESIZE_IMAGE_PROPORTIONAL, true);
				if  (!$key) {
					$share_img_src = $photo['src'];
				}?>
				 <div class="item">
				 	<img src="<?= $photo['src'] ?>" alt=""/>
				 </div>
			<?endforeach;?>
		</div>
	</div>
<?endif;*/?>
</div>
<a href="/company/news/" class="btn arrow_btn">ПОКАЗАТЬ ЕЩЕ новости</a>
<?$APPLICATION->IncludeFile(
	INCLUDE_AREAS."block-news-bottom-menu.php",
	Array(),
	Array("MODE"=>"html", "NAME"=>"Right Panel")
);?>