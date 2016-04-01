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
<!--<pre>--><?//var_dump($arResult["ITEMS"])?><!--</pre>-->
<?if (!empty($arResult["ITEMS"])):?>
  <ul class="perechen-produts">
      <?foreach ($arResult["ITEMS"] as $arItem):?>
  	    <li>
  	        <div>


<!--  	            <a href="--><?//=$arItem["DETAIL_PAGE_URL"]?><!--"><img src="--><?//=$arItem["PREVIEW_PICTURE"]["SRC"]?><!--" alt=""/></a>-->
  	            <a href="<?=$arItem["DETAIL_PAGE_URL"]?>">
                    <?if(!empty($arItem["PREVIEW_PICTURE"]['SRC'])):?>
                        <img src="<?= $arItem["PREVIEW_PICTURE"]['SRC']?>" alt=""/>
                    <? elseif (!empty($arItem['PROPERTIES']['photos']['VALUE'][0])): ?>
                        <?$file = CFile::ResizeImageGet($arItem['PROPERTIES']['photos']['VALUE'][0], array('width'=>320, 'height'=>320), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true); ?>
                        <img src="<?= $file['src'] ?>" alt=""/>
                    <?else: ?>
                        <img src="/images/project_no_img.jpg" alt=""/>
                    <?endif; ?>
                </a>
  	            <a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><h3><?=$arItem["NAME"]?></h3></a>
  	            <ul class="param">
                      <?foreach ($arItem["PROPERTIES"] as $propertyName => $arProperty):?>
                     		<?if (substr($propertyName, 0,3) == "pr_" && !empty($arProperty["VALUE"])):?>
                     			<li><span><?=$arProperty["NAME"]?></span><span class="pr"><?=$arProperty["VALUE"]?></span></li>
                     		<?endif;?>
                      <?endforeach;?>
                  </ul>
                  <div class="price currency-<?=strtolower($arItem['CATALOG_CURRENCY_1'])?>">
                      <?=HogartHelpers::wPrice($arItem['PRICE'])?>
                  </div>
  	        </div>
  	    </li>
      <?endforeach;?>
  </ul>
  <div class="text-center">
  	<? echo $arResult["NAV_STRING"]; ?>
  </div>
<?else:?>
  <p><font class="notetext">К сожалению, на ваш поисковый запрос ничего не найдено.</font></p>
<?endif;?>