<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$date_from = FormatDate("d F Y", MakeTimeStamp($arResult["ACTIVE_FROM"]));?>
<div class="inner">
    <!-- <?$APPLICATION->IncludeComponent("bitrix:menu","section_menu",Array(
            "ROOT_MENU_TYPE" => "left", 
            "MAX_LEVEL" => "1", 
            "CHILD_MENU_TYPE" => "left", 
            "USE_EXT" => "Y",
            "DELAY" => "N",
            "ALLOW_MULTI_SELECT" => "Y",
            "MENU_CACHE_TYPE" => "N", 
            "MENU_CACHE_TIME" => "3600", 
            "MENU_CACHE_USE_GROUPS" => "Y", 
            "MENU_CACHE_GET_VARS" => "" 
        )
    );?> -->

    <div class="control control-action">
        <span class="prev black"><a href="<?=$arResult['PREV']?>"></a></span>
        <span class="next black"><a href="<?=$arResult['NEXT']?>"></a></span>
    </div>
    <h1>Новости</h1>
    <div class="news-one-cnt">
        <div class="padding-news">
            <div class="date">
                <sub><?=$date_from?></sub>
            </div>
            <h2><?=$arResult['NAME']?></h2>

            <?if (!empty($arResult['PREVIEW_TEXT'])):?><p><?=$arResult['PREVIEW_TEXT']?></p><?endif;?>
        </div>

        <?if (!empty($arResult['DETAIL_PICTURE']['SRC'])):?>
	        <div class="img-wrap">
	            <img class="js-popup-open-img" src="<?=$arResult['DETAIL_PICTURE']['SRC']?>" alt=""/>
	        </div>
	    <?endif;?>

        <div class="padding-news">
            <?=$arResult['DETAIL_TEXT']?>

            <?if(!empty($arResult['PROPERTIES']['photogallery']['VALUE'])):?><h2>Фотогалерея</h2><?endif;?>
        </div>

        <?if(!empty($arResult['PROPERTIES']['photogallery']['VALUE'])):?>
	        <div class="gall-news-one">
	            <?foreach ($arResult['PROPERTIES']['photogallery']['VALUE'] as $photo):
	            	$photo = CFile::GetPath($photo);?>
		            <div class="img-wrap">
		                <img class="js-popup-open-img" data-group="gallG" src="<?=$photo?>" alt=""/>
		            </div>
	           	<?endforeach;?>
	        </div>
        <?endif?>

    </div>
        <a class="back_page icon-news-back" href="/company/news/">Назад к новостям</a>
</div>
<?$APPLICATION->IncludeComponent(
	"kontora:element.list",
	"news_detail",
	Array(
		"IBLOCK_ID"	    => 3,
		'FILTER'        => array('!ID' => $arResult['ID'], 'PROPERTY_catalog_section' => $arResult['PROPERTIES']['catalog_section']['VALUE']),
		'ELEMENT_COUNT' => 4,
		'ORDER'         => array('active_from' => 'desc'),
	)
);?>