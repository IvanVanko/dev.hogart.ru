<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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
	<h1><?=$arResult["NAME"]?></h1>
	<!--Если пользователь не авторизован-->
	<small class="green-bg">
	    В каталоге представлены рекомендуемые розничные цены
	</small>
	<!---->
	<div class="view-filter">
	    <div class="left">
	        <span>Выводить:</span>
	        <a class="icon-list js-trigger-perechen active" href="#list">Списком</a>
	        <a class="icon-grid js-trigger-perechen" href="#grid">Плиткой</a>
	    </div>
	    <div class="right">
	        <span>Сортировать по:</span>
	        <a 
		        href="<?=$APPLICATION->GetCurPageParam("sort=shows&order=desc", array("sort", "order"));?>"
		        <?if ($_REQUEST['sort'] == 'shows' || !isset($_REQUEST['sort'])):?>class="active"<?endif;?>
	        >
	        	Популярности
	        </a>
	        
	        <a 
	        	href="<?=$APPLICATION->GetCurPageParam("sort=catalog_PRICE_1&order=asc", array("sort", "order"));?>"
	        	<?if ($_REQUEST['sort'] == 'catalog_PRICE_1'):?>class="active"<?endif;?>
	        >
	        	Цене
	        </a>

	        <a 
	        	href="<?=$APPLICATION->GetCurPageParam("sort=created_date&order=desc", array("sort", "order"));?>"
	        	<?if ($_REQUEST['sort'] == 'created_date'):?>class="active"<?endif;?>
	        >
	        	Новизне
	        </a>
	    </div>
	</div>

	<ul class="perechen-produts js-target-perechen">
	    <?foreach ($arResult["ITEMS"] as $arItem):?>
		    <li>
		        <div>
		            <span class="perechen-img"><a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><img src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" alt=""/></a></span>
		            <div class="col3 param-cnt">
		                <?if (!empty($arItem["PROPERTIES"]["sku"]["VALUE"])):?>
		                	<div class="art">Артикул: <span><?=$arItem["PROPERTIES"]["sku"]["VALUE"]?></span></div>
		                <?endif;?>
		                <a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><h3><?=$arItem["NAME"]?></h3></a>
		                <ul class="param">
		                    <?foreach ($arItem["PROPERTIES"] as $propertyName => $arProperty):?>
		                   		<?if (substr($propertyName, 0,3) == "pr_" && !empty($arProperty["VALUE"])):?>
		                   			<li><span><?=$arProperty["NAME"]?></span><span class="pr"><?=$arProperty["VALUE"]?></span></li>
		                   		<?endif;?>
		                    <?endforeach;?>
		                </ul>
		            </div>
		            <div class="col3 price-cnt <?if ($USER->IsAuthorized()):?> auth-block<?endif;?>">
		                <div class="row">
		                    <div class="col2">
		                        <div class="price">
		                            <?if ($USER->IsAuthorized() && !empty($arItem["PRICES"]["BASE"]["DISCOUNT_DIFF_PERCENT"])):?>
		                            	<?=number_format($arItem["PRICES"]["BASE"]["DISCOUNT_VALUE"], 0, ',', ' ');?> &#8381;
		                            <?else:?>
		                            	<?=number_format($arItem["PRICES"]["BASE"]["VALUE"], 0, ',', ' ');?> &#8381;
		                            <?endif;?>
		                        </div>
		                        <!--Только для авторизованных-->
		                        <?if ($USER->IsAuthorized() && !empty($arItem["PRICES"]["BASE"]["DISCOUNT_DIFF_PERCENT"])):?>
			                        <div class="grid-hide discount">
			                            <?=$arItem["PRICES"]["BASE"]["DISCOUNT_DIFF_PERCENT"]?>%
			                        </div>
			                    <?endif;?>
		                        <!---->
		                    </div>
		                    <div class="col2 text-right">
		                    	<a id="<? echo $arItem['BUY_LINK']; ?>" class="empty-btn black grid-hide" href="javascript:void(0)" rel="nofollow">
		                       		<i class="icon-cart"></i> Купить
		                       	</a>
		                    </div>
		                </div>

		                <!--Только для авторизованных-->
						<?if ($USER->IsAuthorized() && !empty($arItem["PRICES"]["BASE"]["DISCOUNT_DIFF_PERCENT"])):?>
			                <div class="info-block grid-hide">
			                    <div class="old"><?=number_format($arItem["PRICES"]["BASE"]["VALUE"], 0, ',', ' ');?> Р</div>
			                    <p>Цена указана с учетом скидки клиента</p>
			                </div>
			            <?endif;?>
		                <!---->
		                <hr class="grid-hide"/>
		                <div class="icon-carTon grid-hide">
		                    <?if ($arItem["CATALOG_QUANTITY"] > 0):?>
		                    	<div class="line <?if ($USER->IsAuthorized()):?> line2<?endif;?>">В наличии<?if ($USER->IsAuthorized()):?> <span><?=$arItem["CATALOG_QUANTITY"];?> шт.</span><?endif;?></div>
		                    <?else:?>
		                    	Под заказ
		                    	<?if (!empty($arItem["PROPERTIES"]["delivery_period"]["VALUE"])):?>
			                    	<br>
			                    	Срок поставки <span><?=$arItem["PROPERTIES"]["delivery_period"]["VALUE"]?> <?=number($arItem["PROPERTIES"]["delivery_period"]["VALUE"], array('день', 'дня', 'дней'))?></span>
			                    <?endif;?>
		                    <?endif;?> 
		                </div>
		            </div>
		        </div>
		    </li>
	    <?endforeach;?>
	</ul>
	<div class="text-center">
		<? echo $arResult["NAV_STRING"]; ?>
	</div>

	<?=$arResult["DESCRIPTION"]?>
</div>