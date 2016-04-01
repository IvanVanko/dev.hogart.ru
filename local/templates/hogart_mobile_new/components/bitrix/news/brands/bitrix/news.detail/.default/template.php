<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<div class="main-filter">
	<form action="" class="main-filter-form">
		<div class="filter-block">
			<select name="select1" id="select1" class="links-filter">
				<option value="somepage.html">Oventrop</option>
				<option value="somepage.html">HAIER</option>
			</select>
		</div>
	</form>             
</div>

<div class="brand-info">
	<div class="logo-info">
		<?if (strlen($arResult['PREVIEW_PICTURE']['SRC']) > 0):?>
		<div class="img-wrap">
			<img src="<?=$arResult['PREVIEW_PICTURE']['SRC']?>" />
		</div>
		<?endif;?>
		<?if (strlen($arResult['PREVIEW_PICTURE']['SRC']) > 0):?>
		 <a target="_blank" href="<?= $arResult['PROPERTIES']['site']['VALUE'] ?>"><?= $arResult['PROPERTIES']['site']['VALUE'] ?></a>
		<!--<a href="<?=$arResult['DETAIL_PAGE_URL']?>" target="_blank"><?= $arResult['NAME'] ?></a>/-->
		<?endif;?>
	</div>

	<div class="main-brand-info">
		<?=$arResult['PREVIEW_TEXT'] ?>
	</div>
</div>
<a href="#" class="btn link-btn arrow-icon">Читать далее</a>
<div class="brand-carousel-wrap">
	<div class="owl-carousel center-controls" data-pagination="false">
		<div class="detail-slide">
			<img src="<?=MOBILE_PATH?>images/brand-photo.jpg">
		</div>
		<div class="detail-slide">
			<img src="<?=MOBILE_PATH?>images/brand-photo.jpg">
		</div>
		<div class="detail-slide">
			<img src="<?=MOBILE_PATH?>images/brand-photo.jpg">
		</div>
		<div class="detail-slide">
			<img src="<?=MOBILE_PATH?>images/brand-photo.jpg">
		</div>
		<div class="detail-slide">
			<img src="<?=MOBILE_PATH?>images/brand-photo.jpg">
		</div>
		<div class="detail-slide">
			<img src="<?=MOBILE_PATH?>images/brand-photo.jpg">
		</div>
		<div class="detail-slide">
			<img src="<?=MOBILE_PATH?>images/brand-photo.jpg">
		</div>
		<div class="detail-slide">
			<img src="<?=MOBILE_PATH?>images/brand-photo.jpg">
		</div>
	</div>					
</div>
 <?/* 
# Открыть когда карточка бренда будет заполнена, иначе не видно верстки
 if (count($arResult['PROPERTIES']['photogallery']['VALUE']) > 0):?>
 	<div class="brand-carousel-wrap">
 		<div class="owl-carousel center-controls" data-pagination="false">
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

<a href="#" class="btn link-btn arrow-icon">Прайс листы бренда</a>

<div class="brands-catalog-links">
	<h2 class="main-title">КАТАЛОГ ПРОДУКЦИИ <?=ToUpper($arResult["NAME"])?></h2>

	<?if (count($arResult['PARENT_SECTIONS']) > 0 && is_array($arResult['PARENT_SECTIONS'])):?>
	<div class="accordion">
		<?foreach ($arResult['PARENT_SECTIONS'] as $k=>$arV):?>
		<div class="btn dark-gray-btn accordion-head arrow_after"><?= $arV['NAME'] ?></div>
			<div class="accordion-body">
				<ul class="inner_menu big-menu">
					<? foreach ($arResult['PRODUCT_SECTION_GROUPS'][$arV['ID']] as $arChildSection):
					$ch_id = $arChildSection['ID']?>
					<li><a href="<?= $arChildSection['SECTION_PAGE_URL'] ?>"><?= $arChildSection['NAME'] ?></a></li>
					<? endforeach; ?>
				</ul>
			</div>
		<?endforeach;?>
	</div>				
	<?endif;?>
	<div class="btn dark-gray-btn show-all-catalog">весь каталог</div>
</div> 