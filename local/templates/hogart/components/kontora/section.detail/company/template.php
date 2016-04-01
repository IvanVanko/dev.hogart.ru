<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<div class="inner">
    <?$APPLICATION->IncludeComponent("bitrix:menu","section_menu",Array(
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
    );?>
    <h1>Компания</h1>
    <h2><?=$arResult["NAME"]?></h2>

    <?=$arResult["DETAIL_TEXT"]?>

    <?if (!empty($arResult["PROPERTIES"]["partners"]["VALUE"])):?>
	    <h2>Наши партнеры</h2>
	    <p><?=$arResult["PROPERTIES"]["partners"]["~VALUE"]["TEXT"]?></p>
	<?endif;?>

    <h2>Хогарт сегодня</h2>
</div>
<div class="inner no-padding">
    <div class="video-block">
        <div class="video-item big"><img src="/images/company_video_01.jpg" alt=""></div>
        <div class="video-item small"><img src="/images/company_video_02.jpg" alt=""></div>
        <div class="video-item small"><img src="/images/company_video_03.jpg" alt=""></div>
    </div>
</div>

<?if (!empty($arResult["PROPERTIES"]["honors"]["VALUE"])):?>
	<div class="inner">
	    <h2>Достижения и награды</h2>
	    <ul class="sert-slider-cnt js-company-slider">
	        <?foreach ($arResult["PROPERTIES"]["honors"]["VALUE"] as $value):
	        	$value = CFile::GetPath($value);?>
	        	<li><img src="<?=$value?>" alt=""/></li>
	     	<?endforeach?>
	    </ul>
	    <?if (count($arResult["PROPERTIES"]["honors"]["VALUE"]) > 6):?>
		    <div id="js-control-company" class="control">
		        <span class="prev black"></span>
		        <span class="next black"></span>
		    </div>
		<?endif;?>
	</div>
<?endif;?>
<?if (!empty($arResult["PROPERTIES"]["activities"]["VALUE"])):?>
	<aside class="sidebar js-fh js-fixed-block js-paralax-height" data-fixed="top">
	    <div class="inner js-paralax-item">
	        <div class="company-side-cnt padding">
		        <h2>Основные направления деятельности</h2>
		        <?=$arResult["PROPERTIES"]["activities"]["~VALUE"]["TEXT"]?>
	        </div>
	    </div>
	</aside>
<?endif;?>