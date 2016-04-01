<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<?$date_from = FormatDate("d F", MakeTimeStamp($arResult["ACTIVE_FROM"]));?>
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
			<h2><a href="<?= $arResult['DETAIL_PAGE_URL'] ?>"><?= $arResult['NAME'] ?></a></h2>
			<div class="article-info"> <time datetime="<?= $date_from ?>"><?= $date_from ?> </time> 
			<?if (count($arResult["PROPERTIES"]["tag"]["VALUE"]) > 0 ):?>
				<? foreach ($arResult["PROPERTIES"]["tag"]["VALUE"] as $key => $tag): ?>
					<a href="<?=$arParams["SEF_FOLDER"]."?"."tag[" . $arResult['PROPERTIES']['tag']['VALUE_ENUM_ID'][$key] . "]=" . $tag /*$APPLICATION->GetCurPageParam("tag[" . $arResult['PROPERTIES']['tag']['VALUE_ENUM_ID'][$key] . "]=" . $tag, array("tag"), false);*/ ?>"><?= $tag ?></a>
				<? endforeach; ?>
			<?endif;?>                
			</div>     
		</div>
		<?
		#DebugMessage($arResult['DETAIL_PICTURE']['SRC']);
		?>
		<? if (strlen($arResult['DETAIL_PICTURE']['SRC']) > 0):?>
			<?$photoBig = CFile::GetPath($arResult['DETAIL_PICTURE']);?>
			<?$photo = CFile::ResizeImageGet($arResult['DETAIL_PICTURE'], array('width'=>375, 'height'=>239), BX_RESIZE_IMAGE_PROPORTIONAL, true);?>
			<img src="<?=$photo["src"]?>" alt="<?=$arResult['DETAIL_PICTURE']['ALT']?>" />
			<!--<img src="<?=MOBILE_PATH?>images/img2.jpg">/-->
		<? endif; ?>

		<div class="article-body">
			<?= $arResult['DETAIL_TEXT'] ?>
		</div>
	</section>
	 <?if (count($arResult['PROPERTIES']['photogallery']['VALUE']) > 0):?>
	 	<div class="one-news-slider">
	 		<div class="owl-carousel" data-pagination="false">
				<?foreach ($arResult['PROPERTIES']['photogallery']['VALUE'] as $key => $photo):
					#DebugMessage($photo);
					#$photoBig = CFile::GetPath($photo);
					#DebugMessage($photoBig);
					$foto = CFile::ResizeImageGet($photo, array('width'=>375, 'height'=>239), BX_RESIZE_IMAGE_PROPORTIONAL, true);
					#DebugMessage($foto);
					#if  (!$key) {
						$share_img_src = $foto['src'];
					#}?>
					 <div class="item">
					 	<img src="<?= $foto['src'] ?>" alt=""/>
					 </div>
				<?endforeach;?>
			</div>
		</div>
	<?endif;?>
</div>
<?
#DebugMessage($arResult['PROPERTIES']['photogallery']);
?>
<a href="/company/news/" class="btn arrow_btn">ПОКАЗАТЬ ЕЩЕ новости</a>
<?$APPLICATION->IncludeFile(
	INCLUDE_AREAS."block-news-bottom-menu.php",
	Array(),
	Array("MODE"=>"html", "NAME"=>"Right Panel")
);?>