<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);

?>
<div class="item-inner">
	<h1><?=$arResult["NAME"]?></h1>
 <?
 #DebugMessage($arResult["DISPLAY_PROPERTIES"]["photos"]);
 ?>
                <? if (count($arResult["DISPLAY_PROPERTIES"]["photos"]["VALUE"]) > 0): ?>
	<div class="detail-slider">
		<div class="owl-carousel center-controls" data-pagination="false">
			<? if (!empty($arResult['DETAIL_PICTURE']['SRC'])) : ?>
			<div class="detail-slide">
				<?$photo_small = CFile::ResizeImageGet($arResult['DETAIL_PICTURE'], array("width" => 242, "height" =>242 ), BX_RESIZE_IMAGE_EXACT, true);?>
				<a href="<?=$arResult['DETAIL_PICTURE']['SRC']?>" class="open-img"><img src="<?= $photo_small['src'] ?>"></a>
			</div>
			<?endif?>
			<? foreach ($arResult["DISPLAY_PROPERTIES"]["photos"]["VALUE"] as $key => $photo): ?>
			<div class="detail-slide">
				<?$photo_small = CFile::ResizeImageGet($photo, array("width" => 242, "height" => 242), BX_RESIZE_IMAGE_EXACT, true);?>
				<a href="<?=$arResult["DISPLAY_PROPERTIES"]["photos"]["FILE_VALUE"][$key]['SRC']?>" class="open-img"><img src="<?= $photo_small['src'] ?>"></a>
			</div>
			<?endforeach?>
		</div>
	</div>


	<?endif;?>
	<div class="detail-description">
		<div class="price"><?= number_format($arResult["CATALOG_PRICE_1"],0,'.'," ") ?> Р</div>
		<? if (!empty($arResult["DISPLAY_PROPERTIES"]["sku"]["VALUE"])): ?>
    			<div class="item_title">Артикул: <?= $arResult["DISPLAY_PROPERTIES"]["sku"]["VALUE"] ?></div>
		<? endif; ?>
		<div class="detail_info">
			<? if ($arResult["CATALOG_QUANTITY"] > 0): ?>
				В наличии<? if ($USER->IsAuthorized()): ?><span><?= $arResult["CATALOG_QUANTITY"]; ?> шт.</span><? endif; ?>
			<? else: ?>
				Под заказ<br />
				<? if (!empty($arResult["PROPERTIES"]["delivery_period"]["VALUE"])): ?>
				<br>
				Срок поставки <?= $arResult["PROPERTIES"]["delivery_period"]["VALUE"] ?> <?= number($arResult["PROPERTIES"]["delivery_period"]["VALUE"], array('день', 'дня', 'дней')) ?>
				<? endif; ?>
				<? if (!empty($arResult["PROPERTIES"]["supply"]["VALUE"])): ?>
					ожидаемое поступление <br>
					<?= FormatDate('d F', MakeTimeStamp($arResult["PROPERTIES"]["supply"]["VALUE"])); ?>
				<?endif; ?>
			<?endif; ?>
		</div>
		<?$arShownProperties = array();?>
		<?$arHiddenProperties = array();?>
		<?foreach ($arResult["PROPERTIES"] as $arProperty)
		{
			if (!empty($arProperty["VALUE"]) && $arProperty['CODE'] != 'brand' &&  $arProperty['CODE'] != 'collection')
			{
				if ($arProperty["DISPLAY_EXPANDED"] == 'Y') 
				{
					$arShownProperties[] = $arProperty;
				} 
				else
				{
					$arHiddenProperties[] = $arProperty;
				}
			}
		}?>
		<?
		#DebugMessage($arResult);
		?>
		<?if (!empty($arShownProperties)) {?>
		<div class="detail_content">
			<dl class="detail-content-list">
				<?if (strlen($arResult["PROPERTIES"]["CODE"]["brand"]) > 0):?>
				<dt>Бренд</dt>
				<dd><?=$arResult["PROPERTIES"]["CODE"]["brand"]["VALUE"]?></dd>
				<?endif;?>
				<?if (strlen($arResult["PROPERTIES"]["CODE"]["collection"]) > 0):?>
				<dt>Коллекция</dt>
				<dd><?= $arResult["PROPERTIES"]["CODE"]["collection"]["VALUE"] ?></dd>
				<?endif;?>
				<? foreach ($arShownProperties as $propertyName => $arProperty): ?>
				<dt><?= $arProperty["NAME"] ?> </dt>
				<dd><?= $arProperty["VALUE"] ?></dd>
				<? endforeach; ?>
			</dl>
		</div>
		<?}?>
	</div>
</div>



	<a href="#" class="btn link-btn list-icon">Прайс лист на продукцию</a>
	<a href="/documentation/" class="btn link-btn cat-icon">Документация</a>

	<div class="detail-text content-text">
	<?= $arResult["DETAIL_TEXT"] ?>
	</div>


	<? if (!empty($arResult["PROPERTIES"]['video_youtube']['~VALUE'])): ?>
		<div class="video_wrap">
			<iframe width="100%" height="196" src="https://www.youtube.com/embed/<?= $arResult["PROPERTIES"]['video_youtube']['VALUE'] ?>?rel=0"
	frameborder="0" allowfullscreen></iframe>
		</div>
	<? endif; ?>

	<select class="arrow-select title-with-arrow choose-block-trigger">
	<? if (isset($arResult["buy_with_this"])): ?>
	<option value="block_1">С этим товаром покупают</option>
	<?endif;?>
	<? if (isset($arResult["related"])): ?>
	<option value="block_2">Сопуствующие товары</option>
	<?endif;?>
	<? if (isset($arResult["this_collection"])): ?>
	<option value="block_3" selected>Еще из этой коллекции</option>
	<?endif;?>
	</select>
	<div class="similar-items choose-items block_1">
		<? if (isset($arResult["buy_with_this"])): ?>
			<? foreach ($arResult["buy_with_this"] as $key => $arProduct): ?>
			<div class="one_item">
				<?if (!empty($arProduct["PREVIEW_PICTURE"])) 
				{
					$file = CFile::ResizeImageGet($arProduct["PREVIEW_PICTURE"],
					array('width' =>108, 'height' => 108), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true);
					$file = $file['src'];
				} 
				elseif (!empty($arProduct["PROPERTY_PHOTOS_VALUE"])) 
				{
					$file = CFile::ResizeImageGet($arProduct["PROPERTY_PHOTOS_VALUE"],
					array('width' => 108, 'height' => 108), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true);
					$file = $file['src'];
				} 
				else 
				{
					$file = '/images/project_no_img.jpg';
				}?>
				<div class="item-img-wrap"><img src="<?=$file?>" height="108" width="71"></div>
				<div class="price"><?=number_format($arProduct["CATALOG_PRICE_1"],0,'.'," ") ?> Р</div>
				<div class="item_description">
                				 <a href="<?= $arProduct["DETAIL_PAGE_URL"] ?>"><span class="item_title"><?=$arProduct["NAME"]?></span></a>
					<div class="item_info">
						<? if (!empty($arProduct["PROPERTY_SKU_VALUE"])): ?>
                        					Артикул: <?= $arProduct["PROPERTY_SKU_VALUE"] ?>
                    					<? endif; ?>
						Под заказ. Срок поставки 3 дн
					</div>
					<div class="item_body">
						<dl class="clearfix">
							<dt>Бренд</dt>
                                					<dd class="pr"><?=$arProduct['BRAND_NAME']?></dd>
						</dl>
						<dl class="clearfix">
							<dt>Коллекция</dt>
							<dd class="pr"><?=$arProduct['COLLECTION_NAME']?></dd>
						</dl>
					</div>
				</div>
			</div>
			<?endforeach;?>
		<? endif; ?>
	</div>
	<div class="similar-items choose-items block_2">
	<? if (isset($arResult["related"])): ?>
	<? foreach ($arResult["related"] as $key => $arProduct): ?>
		<div class="one_item">
			<?if (!empty($arProduct["PREVIEW_PICTURE"])) 
			{
				$file = CFile::ResizeImageGet($arProduct["PREVIEW_PICTURE"],
				array('width' => 108, 'height' => 108), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true);
				$file = $file['src'];
			} 
			elseif (!empty($arProduct["PROPERTY_PHOTOS_VALUE"])) 
			{
				$file = CFile::ResizeImageGet($arProduct["PROPERTY_PHOTOS_VALUE"],
				array('width' => 108, 'height' => 108), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true);
				$file = $file['src'];
			} 
			else 
			{
				$file = '/images/project_no_img.jpg';
			}?>
			<div class="item-img-wrap"><img src="<?=$file?>" height="108" width="71"></div>
			<div class="price"><?=number_format($arProduct["CATALOG_PRICE_1"],0,'.'," ") ?> Р</div>
			<div class="item_description">
            				 <a href="<?= $arProduct["DETAIL_PAGE_URL"] ?>"><span class="item_title"><?=$arProduct["NAME"]?></span></a>
				<div class="item_info">
					<? if (!empty($arProduct["PROPERTY_SKU_VALUE"])): ?>
                    					Артикул: <?= $arProduct["PROPERTY_SKU_VALUE"] ?>
                					<? endif; ?>
					Под заказ. Срок поставки 3 дн
				</div>
				<div class="item_body">
					<dl class="clearfix">
						<dt>Бренд</dt>
                            					<dd class="pr"><?=$arProduct['BRAND_NAME']?></dd>
					</dl>
					<dl class="clearfix">
						<dt>Коллекция</dt>
						<dd class="pr"><?=$arProduct['COLLECTION_NAME']?></dd>
					</dl>
				</div>
			</div>
		</div>
	<?endforeach;?>
	<? endif; ?>
	</div>
	<?#DebugMessage($arResult["this_collection"]);?>

	<div class="similar-items choose-items block_3 opened">
	<? if (isset($arResult["this_collection"])): ?>
	<? foreach ($arResult["this_collection"] as $key => $arProduct): ?>
		<div class="one_item">
			<?if (!empty($arProduct["PREVIEW_PICTURE"])) 
			{
				$file = CFile::ResizeImageGet($arProduct["PREVIEW_PICTURE"],
				array('width' =>108, 'height' => 108), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true);
				$file = $file['src'];
			} 
			elseif (!empty($arProduct["PROPERTY_PHOTOS_VALUE"])) 
			{
				$file = CFile::ResizeImageGet($arProduct["PROPERTY_PHOTOS_VALUE"],
				array('width' => 108, 'height' => 108), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true);
				$file = $file['src'];
			} 
			else 
			{
				$file = '/images/project_no_img.jpg';
			}?>
			<div class="item-img-wrap"><img src="<?=$file?>" height="108" width="71"></div>
			<div class="price"><?=number_format($arProduct["CATALOG_PRICE_1"],0,'.'," ") ?> Р</div>
			<div class="item_description">
            				 <a href="<?= $arProduct["DETAIL_PAGE_URL"] ?>"><span class="item_title"><?=$arProduct["NAME"]?></span></a>
				<div class="item_info">
					<? if (!empty($arProduct["PROPERTY_SKU_VALUE"])): ?>
                    					Артикул: <?= $arProduct["PROPERTY_SKU_VALUE"] ?>
                					<? endif; ?>
					Под заказ. Срок поставки 3 дн
				</div>
				<div class="item_body">
					<dl class="clearfix">
						<dt>Бренд</dt>
                            					<dd class="pr"><?=$arProduct['BRAND_NAME']?></dd>
					</dl>
					<dl class="clearfix">
						<dt>Коллекция</dt>
						<dd class="pr"><?=$arProduct['COLLECTION_NAME']?></dd>
					</dl>
				</div>
			</div>
		</div>
	<?endforeach;?>
	<? endif; ?>
	</div>	
