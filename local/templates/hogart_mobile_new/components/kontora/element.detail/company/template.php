<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<div class="news-items">
	<section class="news-one">
		<div class="article-meta">
			<h2><?=$arResult["NAME"]?></h2>
		</div>
		<div class="article-body">
			<?= $arResult['DETAIL_TEXT'] ?>
			<?if (count($arResult['PROPERTIES']['points']['VALUE']) > 0):?>
				<ol>
				<?foreach ($arResult['PROPERTIES']['points']['VALUE'] as $key => $points):?>
					<li><?=$points?></li>
				<?endforeach;?>
				</ol>
			<?endif;?>
			<?/*if (strlen($arResult['PROPERTIES']['activities']['~VALUE']["TEXT"]) > 0):?>
				<h2><?=$arResult['PROPERTIES']['activities']["NAME"]?></h2>
				<?=$arResult['PROPERTIES']['activities']['~VALUE']["TEXT"]?>
			<?endif;*/?>
			<?if (strlen($arResult['PROPERTIES']['partners']['~VALUE']["TEXT"]) > 0):?>
				<h2><?=$arResult['PROPERTIES']['partners']["NAME"]?></h2>
				<?=$arResult['PROPERTIES']['partners']['~VALUE']["TEXT"]?>
			<?endif;?>
		</div>
	</section>
	<h2 style="text-align:center;">Хогарт сегодня</h2>
	<?$APPLICATION->IncludeComponent("kontora:element.list", "hogart_today_mobile", array(
		"IBLOCK_ID" => "21",
		"PROPS"     => "Y",
		'ORDER'     => array('sort' => 'asc'),
		), $component
	);?>
	 <?if (count($arResult['PROPERTIES']['honors']['VALUE']) > 0):?>
	 	<h2 style="text-align:center;">Достижения и награды</h2>
	 	<div class="one-news-slider">
	 		<div class="owl-carousel" data-pagination="false">
				<?foreach ($arResult['PROPERTIES']['honors']['VALUE'] as $key => $photo):
					#$photoBig = CFile::GetPath($photo);
					#DebugMessage($photoBig);
					$photo = CFile::ResizeImageGet($photo, array('width'=>320, 'height'=>320), BX_RESIZE_IMAGE_PROPORTIONAL, true);
					#DebugMessage($photo);
					?>
					 <div class="item" style="text-align: center;">
					 	<img src="<?= $photo['src'] ?>" alt=""/>
					 </div>
				<?endforeach;?>
			</div>
		</div>
	<?endif;?>	
</div>
<?
#DebugMessage($arResult['PROPERTIES']);
?>



<?/*?>

<h2><?=$arResult["NAME"]?></h2>

    <p><?=$arResult["DETAIL_TEXT"]?></p>

    <?if (!empty($arResult['PROPERTIES']['points']['VALUE'])):?>
    	<ul class="counter-company">
            <?foreach ($arResult['PROPERTIES']['points']['VALUE'] as $key => $value):?>
                <li>
                    <span><?=$key+1?></span>

                    <p><?=$value?></p>
                </li>
                <?if ($key % 2 != 0):?>
                	<li class="clearfix"></li>
                <?endif;?>
            <?endforeach;?>
        </ul>
    <?endif;?>

    <?if (!empty($arResult["PROPERTIES"]["partners"]["VALUE"])):?>
	    <h2>Наши партнеры</h2>
	    <p><?=$arResult["PROPERTIES"]["partners"]["~VALUE"]["TEXT"]?></p>
	<?endif;?>
	<h2>Хогарт сегодня</h2>
</div>

<?$APPLICATION->IncludeComponent("kontora:element.list", "hogart_today", array(
	"IBLOCK_ID" => "21",
	"PROPS"     => "Y",
	'ORDER'     => array('sort' => 'asc'),
));?>

<?if (!empty($arResult["PROPERTIES"]["honors"]["VALUE"])):?>
	<div class="inner">
	    <h2>Достижения и награды</h2>
	    <ul class="sert-slider-cnt js-company-slider">
<!--	    <ul class="sert-slider-cnt js-itegr-slider">-->
	        <?foreach ($arResult["PROPERTIES"]["honors"]["VALUE"] as $value):
//	        	$file = CFile::ResizeImageGet($value, array('width'=>100, 'height'=>142), BX_RESIZE_IMAGE_EXACT, true);
	        	$file = CFile::ResizeImageGet($value, array('width'=>126, 'height'=>179), BX_RESIZE_IMAGE_EXACT, true);
	        	$fileBig = CFile::GetPath($value);

                ?>
	        	<li><img src="<?=$file['src']?>" data-group="gallG" data-big-img="<?=$fileBig?>" class="js-popup-open-img" alt=""/></li>
	     	<?endforeach?>
	    </ul>
	    <?if (count($arResult["PROPERTIES"]["honors"]["VALUE"]) > 6):?>
		    <div id="js-control-company" class="control">
		        <span class="prev black"></span>
		        <span class="next black"></span>
		    </div>
            <?else:?>
            <br/>
		<?endif;?>
	</div>
	<?//var_dump($arResult["PROPERTIES"]["activities"]);?>
<?endif;?>
<?if (!empty($arResult["PROPERTIES"]["activities"]["VALUE"])):?>
	<aside class="sidebar js-fh js-fixed-block js-paralax-height" data-fixed="top">
	    <div class="inner js-paralax-item">

	        <div class="company-side-cnt padding">
		        <h2>Основные направления деятельности</h2>
			<?
			$GetCurDir = explode("/", $APPLICATION->GetCurDir());

			$GetCurDir = array_filter(
				$GetCurDir,
				function($el){ return !empty($el);}
			);
			$GLOBALS['myFilter'] = array("PROPERTY_show_where"=> $GetCurDir);
			$APPLICATION->IncludeComponent(
				"bitrix:news.list",
				"advantages",
				Array(
					"COMPONENT_TEMPLATE" => ".default",
					"IBLOCK_TYPE" => "advantages",
					"IBLOCK_ID" => 19,
					"NEWS_COUNT" => "3",
					"SORT_BY1" => "SORT",
					"SORT_ORDER1" => "ASC",
					"SORT_BY2" => "ACTIVE_FROM",
					"SORT_ORDER2" => "DESC",
					"FILTER_NAME" => "myFilter",
					"FIELD_CODE" => array("",""),
					"PROPERTY_CODE" => array("link"),
					"CHECK_DATES" => "Y",
					"DETAIL_URL" => "",
					"AJAX_MODE" => "N",
					"AJAX_OPTION_JUMP" => "N",
					"AJAX_OPTION_STYLE" => "Y",
					"AJAX_OPTION_HISTORY" => "N",
					"CACHE_TYPE" => "A",
					"CACHE_TIME" => "36000000",
					"CACHE_FILTER" => "N",
					"CACHE_GROUPS" => "Y",
					"PREVIEW_TRUNCATE_LEN" => "",
					"ACTIVE_DATE_FORMAT" => "d.m.Y",
					"SET_TITLE" => "N",
					"SET_BROWSER_TITLE" => "N",
					"SET_META_KEYWORDS" => "N",
					"SET_META_DESCRIPTION" => "N",
					"SET_STATUS_404" => "N",
					"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
					"ADD_SECTIONS_CHAIN" => "N",
					"HIDE_LINK_WHEN_NO_DETAIL" => "N",
					"PARENT_SECTION" => "",
					"PARENT_SECTION_CODE" => "",
					"INCLUDE_SUBSECTIONS" => "Y",
					"DISPLAY_DATE" => "N",
					"DISPLAY_NAME" => "Y",
					"DISPLAY_PICTURE" => "Y",
					"DISPLAY_PREVIEW_TEXT" => "N",
					"PAGER_TEMPLATE" => ".default",
					"DISPLAY_TOP_PAGER" => "N",
					"DISPLAY_BOTTOM_PAGER" => "N",
					"PAGER_TITLE" => "Новости",
					"PAGER_SHOW_ALWAYS" => "N",
					"PAGER_DESC_NUMBERING" => "N",
					"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
					"PAGER_SHOW_ALL" => "N"
				)
			);
			?>
	    </div>
	</aside>
<?endif;?>
<?/*$APPLICATION->IncludeFile(
    "/local/include/share.php",
    array(
        "TITLE" => $arResult["NAME"],
        "DESCRIPTION" => !empty($arResult["PREVIEW_TEXT"]) ? $arResult["PREVIEW_TEXT"]:$arResult["DETAIL_TEXT"],
        "LINK" => $APPLICATION->GetCurPage(),
    )
);*/?>