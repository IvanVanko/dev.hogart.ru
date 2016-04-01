<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<aside class="sidebar js-fh js-fixed-block js-paralax-height" data-fixed="top">
    <div class="inner js-paralax-item">
        <div class="side_href">
            <a href="/documentation/?brands[]=<?=$arParams['FILTER']['PROPERTY_BRAND']?>&fbrand=y" class="icon_doc">Перейти к документации</a>
        </div>
        <?if (count($arResult["ITEMS"]) > 0):?>
	        <div class="padding">
	            <h2>Прайс - листы</h2>
	            <ul class="price-list">
	                <?foreach ($arResult["ITEMS"] as $arItem):
	                	$arFile = CFile::GetFileArray($arItem['PROPERTIES']['file']['VALUE']);
	                	$info = new SplFileInfo($arFile['FILE_NAME']);
						$extention = $info->getExtension();?>
		                <li>
		                    <a target="_blank" href="<?=$arFile['SRC']?>">
		                        <?=$arItem['NAME']?>
		                    </a>
		                    <span>— .<?=$extention?>, <?=round($arFile['FILE_SIZE']/1048576, 2)?> mb</span>
		                </li>
	                <?endforeach;?>
	            </ul>
	        </div>
	    <?endif; ?>
    </div>
</aside>