<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<div class="inner">
    <?if (count($arResult['ITEMS']) > 0):?>
	    <ul class="comments-list">
	        <?foreach ($arResult['ITEMS'] as $arItem):?>
		        <li>
		            <div class="inner">
		                <div class="photo">
		                    <div class="inner">
		                        <img src="<?=$arItem['PREVIEW_PICTURE']['SRC']?>" alt=""/>
		                    </div>
		                </div>
		                <div class="text">
			                <?$date = explode('.', $arItem['DATE_CREATE']);?>
			                <?$date_from = FormatDate("d F Y", MakeTimeStamp($arItem["DATE_CREATE"]));?>
<!--			                <p class="date">--><?//=$arItem['DATE_CREATE']?><!--</p>-->
			                <p class="date"><?=$date_from?></p>
		                    <p>
		                        <?=$arItem['PREVIEW_TEXT']?>
		                    </p>
		                    <div class="name"><?=$arItem['NAME']?></div>
		                    <div class="sign"><?=$arItem['PROPERTIES']['post']['VALUE']?></div>
		                </div>
                        <? if(!empty($arItem['PROPERTIES']['recom']['VALUE'])):?>
                        <ul class="comments-list" style="margin: 0 0 0 80px; border-bottom: none">
                                <li style="border: none; padding-bottom: 0">
                                    <div class="inner">
                                        <div class="text">
                                            <p>
<!--                                                --><?//=var_dump($arItem['PROPERTIES']['recom']['VALUE']);?>
                                                <?=$arItem['PROPERTIES']['recom']['VALUE']['TEXT']?>
                                            </p>
                                        </div>
                                    </div>
                                </li>
                        </ul>
                    <? endif; ?>
		            </div>
		        </li>
		    <?endforeach;?>
	    </ul>
	<?endif;?>
 	<div class="text-center">
 		<?=str_replace("новости", "Отзывы", $arResult["NAV_STRING"]);?>
 	</div>
</div>
<?
#DebugMessage($arResult["NAV_STRING"]);
?>