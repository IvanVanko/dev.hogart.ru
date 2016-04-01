<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$date_from = FormatDate("d F", MakeTimeStamp($arResult["ACTIVE_FROM"]));
$date_to = FormatDate("d F", MakeTimeStamp($arResult["ACTIVE_TO"]));?>

<aside class="pages-nav">
	<a href="<?=$arResult['PREV']?>"  class="page-prev">назад</a></span>
	<a href="<?=$arResult['NEXT']?>" class="page-next">вперед</a></span>
</aside>
<div class="news-items">
	<section class="news-one">
		<div class="article-meta">
			<h2><a href="#"><?=$arResult['NAME']?></a></h2>

			<div class="article-info"> <span class="time">
				<?=$date_from.' – '.$date_to?>
					<?
					$dateFinish = FormatDate("d.m.Y", MakeTimeStamp($arResult["ACTIVE_TO"]));
					$now=date($DB->DateFormatToPHP(CSite::GetDateFormat("SHORT")), time());
					if ($arResult['ACTIVE']==Y && strtotime($now) > strtotime($dateFinish)):?>
						<br /><strong>(Акция завершена)</strong>
					<?endif;?>
			</span></div>                       
		</div>
		<img src="<?=$arResult['DETAIL_PICTURE']['SRC']?>" alt=""/>

		<div class="article-body">
			<?=$arResult['DETAIL_TEXT']?>
			<?/*$APPLICATION->IncludeComponent(
				"kontora:element.list",
				"stock_detail",
				Array(
					"IBLOCK_ID" => $arParams["IBLOCK_ID"],
					"PROPS" => "Y",
					"ELEMENT_COUNT" => "3",
					"FILTER" => array('!ID' => $arResult['ID']),
					'SEF_FOLDER' => $arParams['SEF_FOLDER'],
				)
			);*/?>

			 <? if(isset($arResult["this_goods"])): ?>
			<h3>В акции участвуют следующие товары</h3>
			 <? foreach($arResult["this_goods"] as $arProduct): ?>
			<div class="one_item">
				<? if(!empty($arProduct["PREVIEW_PICTURE"])) {
					$file = CFile::ResizeImageGet($arProduct["PREVIEW_PICTURE"],
					array('width' => 71, 'height' => 108), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true);
					$file = $file['src'];
					}
					elseif(!empty($arProduct["PROPERTY_PHOTOS_VALUE"])) {
					$file = CFile::ResizeImageGet($arProduct["PROPERTY_PHOTOS_VALUE"],
					array('width' => 71, 'height' => 108), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true);
					$file = $file['src'];
					}
					else {
					$file = '/images/project_no_img.jpg';
				} ?>
				<?if (strlen($file) > 0):?>
				<div class="item-img-wrap">
					<a href="<?=$arProduct["DETAIL_PAGE_URL"]?>"><img src="<?=$file?>" width="71" /></a>
				</div>
				<?endif;?>
				<div class="price"><?=number_format($arProduct["CATALOG_PRICE_1"], 0, "", " ")?> Р</div>
				<div class="item_description">
					<span class="item_title"><a href="<?=$arProduct["DETAIL_PAGE_URL"]?>"><?=$arProduct["NAME"]?></a></span>
					<div class="item_info">
						<?if (strlen($arProduct["HIDDEN_PROPS"][2]["VALUE"]) > 0):?>
						Артикул: <?=$arProduct["HIDDEN_PROPS"][2]["VALUE"]?>
						<?endif?>
						<!--Под заказ. Срок поставки 3 дн/-->
					</div>
					<?if (count($arProduct["SHOW_PROPS"]) > 0):?>
					<div class="item_body">
						<dl class="clearfix">
							<dt><span>Бренд</span></dt>
							<dd ><span><?=$arProduct['BRAND_NAME']?></span></dd>
						</dl>
						<?if(strlen($arProduct['COLLECTION_NAME']) > 0):?>
						<dl class="clearfix">
							<dt><span>Коллекция</span></dt>
							<dd><span><?=$arProduct['COLLECTION_NAME']?></span></dd>
						</dl>
						<?endif;?>
						<?foreach($arProduct["SHOW_PROPS"] as $k=>$arShowProps):?>
						<dl class="clearfix">
							<dt><span><?=$arShowProps["NAME"]?></span> </dt>
							<dd><span><?=$arShowProps["VALUE"]?></span></dd>
						</dl>
						<?endforeach;?>
						<?if (count($arProduct["HIDDEN_PROPS"]) > 0):?>
							<div class="param-item" style="display:none;">
								<?foreach($arProduct["HIDDEN_PROPS"] as $k=>$arShowProps):?>
								<?if ($arShowProps["CODE"] == "brand" || $arShowProps["CODE"] == "photos" || $arShowProps["CODE"] == "sku" || $arShowProps["CODE"] == "collection") continue;?>
								<dl class="clearfix">
									<dt><span><?=$arShowProps["NAME"]?></span> </dt>
									<dd><span><?=$arShowProps["VALUE"]?></span></dd>
								</dl>
								<?endforeach;?>
							</div>
							<a href="javascript:void(0);" class="btn arrow_btn show_all_params">ВСЕ ХАР-КИ</a>
						<?endif;?>
					</div>
					<?endif;?>
				</div>
			</div>
			<?endforeach;?>
			<?endif;?>
		</div>
	</section>
</div>
<a href="/stock/" class="btn arrow_btn">ПОКАЗАТЬ ЕЩЕ Акции</a>
<?
DebugMessage($arResult["this_goods"]);
?>
<?$APPLICATION->IncludeFile(
	INCLUDE_AREAS."block-news-bottom-menu.php",
	Array(),
	Array("MODE"=>"html", "NAME"=>"Right Panel")
);?>


<?/*?>
 <? if(isset($arResult["this_goods"])): ?>
        <div class="catalog_page">
            <div class="products-similar-tabs">
                <h1>Товары, участвующие в акции <?=$arResult["NAME"]?></h1>
                <div class="items-similar">
                    <div id="tab-1" class="item-similar active" style="display: block;">
                        <? if(count($arResult["this_goods"]) > 3): ?>
                            <div id="con-4" class="controls">
                                <div class="prev"></div>
                                <div class="next"></div>
                            </div>
                        <? endif; ?>
                        <ul data-control="#con-4" class="js-slider-similar">
                            <? foreach($arResult["this_goods"] as $arProduct): ?>
                                <li>
		        	<span class="preview-img">
                        <? if(!empty($arProduct["PREVIEW_PICTURE"])) {
                            $file = CFile::ResizeImageGet($arProduct["PREVIEW_PICTURE"],
                                array('width' => 406, 'height' => 142), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true);
                            $file = $file['src'];
                        }
                        elseif(!empty($arProduct["PROPERTY_PHOTOS_VALUE"])) {
                            $file = CFile::ResizeImageGet($arProduct["PROPERTY_PHOTOS_VALUE"],
                                array('width' => 406, 'height' => 142), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true);
                            $file = $file['src'];
                        }
                        else {
                            $file = '/images/project_no_img.jpg';
                        } ?>
                        <a href="<?=$arProduct["DETAIL_PAGE_URL"]?>" style="background-image: url(<?=$file?>)"></a>
		        	</span>
                                    <a href="<?=$arProduct["DETAIL_PAGE_URL"]?>"><h3><?=$arProduct["NAME"]?></h3></a>
                                    <? if(!empty($arProduct["PROPERTY_SKU_VALUE"])): ?>
                                        <span
                                            class="art">Артикул: <span><?=$arProduct["PROPERTY_SKU_VALUE"]?></span></span>
                                    <? endif; ?>
                                    <div class="param">
                                        <div>
                                            <dl>
                                                <dt>Бренд</dt>
                                                <dd class="pr"><?=$arProduct['BRAND_NAME']?></dd>
                                            </dl>
                                            <?if(strlen($arProduct['COLLECTION_NAME']) > 0){?>
                                            <dl>
                                                <dt>Коллекция</dt>
                                                <dd class="pr"><?=$arProduct['COLLECTION_NAME']?></dd>
                                            </dl>
                                            <?}?>
                                        </div>
                                        <?=HogartHelpers::getAdjacentProductPropertyHtml($arProduct['ID'], $arProduct["SHOW_PROPS"], $arProduct["HIDDEN_PROPS"], array('brand',
                                                                                                                                                                       'photos',
                                                                                                                                                                       'sku',
                                                                                                                                                                       'collection'));?>
                                    </div>
                                    <div class="price currency-<?=strtolower($arProduct['CATALOG_CURRENCY_1'])?>">
                                        <?=HogartHelpers::wPrice($arProduct['PRICE'])?>
                                    </div>
                                </li>
                            <? endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    <? endif; ?>


<?
*/
?>
