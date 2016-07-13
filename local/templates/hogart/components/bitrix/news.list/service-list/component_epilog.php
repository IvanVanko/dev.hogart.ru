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
<?
// заменяем $arResult эпилога значением, сохраненным в шаблоне
if(isset($arResult['arResult'])) {
   $arResult =& $arResult['arResult'];
         // подключаем языковой файл
   global $MESS;
   include_once(GetLangFileName(dirname(__FILE__).'/lang/', '/template.php'));
} else {
   return;
}
?>
<div class="inner">
<?foreach($arResult["ITEMS"] as $count => $arItem):?>
    <?if ($count==0):?>

    <h3><?=$arItem['NAME']?></h3>
    <div class="service-item-box">
        <?=$arItem["PREVIEW_TEXT"]?>
    </div>
	<?
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
	?>
<div class="service-item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">


    <?if (!empty($arItem["PROPERTIES"]["fotos"]['VALUE']) || !empty($arItem["PROPERTIES"]["videos"]['VALUE']))?>
    <ul class="sert-slider-cnt js-service-slider-other-<?=$count;?>">
        <!--	    <ul class="sert-slider-cnt js-itegr-slider">-->
        <?
        //                foreach ($arElement["PROPERTIES"]["pictures"]["VALUE"] as $picId):
        foreach ($arItem["PROPERTIES"]["fotos"]["VALUE"] as $key => $picId):
//            $file = CFile::ResizeImageGet($picId, array('width'=>200, 'height'=>150), BX_RESIZE_IMAGE_EXACT, true);
//            $file = CFile::ResizeImageGet($picId, array('width'=>480, 'height'=>360), BX_RESIZE_IMAGE_EXACT, true);
            $file = CFile::ResizeImageGet($picId, array('width'=>320, 'height'=>180), BX_RESIZE_IMAGE_PROPORTIONAL, true);
//                    $fileBig = CFile::GetPath($picId);
            $fileBig = CFile::ResizeImageGet($picId, array('width'=>800, 'height'=>600), BX_RESIZE_IMAGE_PROPORTIONAL, true);

            ?>
            <!--                    <li><img src="--><?//=$file['src']?><!--" data-group="gallG" data-big-img="--><?//=$fileBig?><!--" class="js-popup-open-img" alt=""/></li>-->
            <li>
                <?if(!empty($arItem["PROPERTIES"]["videos"]['VALUE'][$key])):?>
                    <div class="img-wrap video-wrap">
                        <img class="js-popup-open-img" title="<?=$arResult['NAME']?>"  data-group="gallG-<?=$count?>" data-big-video="<?=$arItem["PROPERTIES"]["videos"]['VALUE'][$key]?>" src="http://img.youtube.com/vi/<?=$arItem["PROPERTIES"]["videos"]['VALUE'][$key]?>/mqdefault.jpg"  alt=""/>
                    </div>

                <?else:?>
                    <div class="img-wrap">
                        <img class="js-popup-open-img" title="<?=$arResult['NAME']?>" src="<?= $file['src']; ?>" data-group="gallG-<?=$count?>" data-big-img="<?= $fileBig['src']; ?>" alt=""/>
                    </div>
                <?endif;?>
            </li>


        <?endforeach?>
    </ul>
    <?if (count($arItem["PROPERTIES"]["fotos"]["VALUE"]) > 2 || count($arItem["PROPERTIES"]["videos"]["VALUE"]) > 2):?>
        <div id="js-service-slider-other-<?=$count;?>" class="control">
            <span class="prev black"></span>
            <span class="next black"></span>
        </div>
    <?else:?>
        <br/>
    <?endif;?>


</div>
<?else:?>
        <?
        $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
        $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
        ?>
        <div class="service-item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
            <h3 class="services-h3"><?=$arItem['NAME']?></h3>

            <?if (!empty($arItem["PROPERTIES"]["fotos"]['VALUE']) || !empty($arItem["PROPERTIES"]["videos"]['VALUE'])):?>
            <ul class="sert-slider-cnt js-service-slider-other-<?=$count;?>">
                <!--	    <ul class="sert-slider-cnt js-itegr-slider">-->
                <?
                //                foreach ($arElement["PROPERTIES"]["pictures"]["VALUE"] as $picId):
                foreach ($arItem["PROPERTIES"]["fotos"]["VALUE"] as $key => $picId):
                    $file = CFile::ResizeImageGet($picId, array('width'=>320, 'height'=>180), BX_RESIZE_IMAGE_EXACT, true);
                    $fileBig = CFile::ResizeImageGet($picId, array('width'=>800, 'height'=>600), BX_RESIZE_IMAGE_EXACT, true);;

                    ?>
                    <li>
                        <div class="img-wrap">
                            <img class="js-popup-open-img" title="<?=$arResult['NAME']?>"  data-group="gallG-<?=$count?>" src="<?= $file['src']; ?>" data-big-img="<?= $fileBig['src']; ?>" alt=""/>
                        </div>
                    </li>
                <?endforeach?>
                <?foreach ($arItem["PROPERTIES"]["videos"]["VALUE"] as $key => $picId) {?>
                    <li>
                        <div class="img-wrap video">
                            <img class="js-popup-open-img" title="<?=$arResult['NAME']?>"  data-big-video="<?=$arItem["PROPERTIES"]["videos"]['VALUE'][$key]?>" src="http://img.youtube.com/vi/<?=$arItem["PROPERTIES"]["videos"]['VALUE'][$key]?>/mqdefault.jpg"  alt=""/>
                        </div>
                    </li>
                <?}?>
            </ul>

                <?if (count($arItem["PROPERTIES"]["fotos"]["VALUE"]) + count($arItem["PROPERTIES"]["videos"]["VALUE"]) > 3):?>
                    <div id="js-service-slider-other-<?=$count;?>" class="control">
                        <span class="prev black"></span>
                        <span class="next black"></span>
                    </div>
                <?else:?>
                <br/>
            <?endif;?>

<!--            <br/>-->
            <hr/>
<!--            <br/>-->
            <?endif;?>
            <!--<pre>--><?//var_dump($arItem['PROPERTIES']['presentation']['VALUE']);?><!--</pre>-->
            <?if (!empty($arItem['PROPERTIES']['presentation']['VALUE'])):?>
                <div class="pres-material">
                    <h2><?=$arItem['PROPERTIES']['presentation']['NAME'];?></h2>
                    <ul>
                        <?foreach($arItem['PROPERTIES']['presentation']['VALUE'] as $key => $prez):?>
                            <?
                            $prez = CFile::GetFileArray($prez);
                            $fileType = explode('.',$prez['SRC']);
                            $fileSize = $prez['FILE_SIZE']/1024/1024;
                            ?>
                            <li><a href="<?= $prez['SRC'];?>"><?=$arItem['PROPERTIES']['presentation']['DESCRIPTION'][$key];?></a>&nbsp;
                                (<?= round($fileSize, 2);?> Mb, <?= $fileType[1];?>)
                            </li>
                        <?endforeach;?>
                    </ul>
                </div>
            <?endif;?>

            <div class="service-item-box">
                <?=$arItem["PREVIEW_TEXT"]?>
            </div>
        </div>
<?endif;?>
    <script type="text/javascript">
        if ($('.js-service-slider-other-<?=$count;?>').length) {
            setTimeout(function () {
//                $('.js-service-slider<?//=$count;?>//').each(function () {
//                    var width = $(this).width() / 3;
                    $('.js-service-slider-other-<?=$count;?>').bxSlider({
                        minSlides: 3,
                        maxSlides: 3,
                        slideMargin: 22,
                        slideWidth: $(this).width() / 3 - 22,
                        pager: false,
                        nextText: '',
                        prevText: '',
                        nextSelector: $('#js-service-slider-other-<?=$count;?>').find('.next'),
                        prevSelector: $('#js-service-slider-other-<?=$count;?>').find('.prev'),
                        infiniteLoop: false
//                        responsive:true
                    });
//                });
            }, 440);
        }
    </script>
<?endforeach;?>

<?$APPLICATION->IncludeFile(
    "/local/include/share.php",
    array(
        "TITLE" => $arResult["NAME"],
        "DESCRIPTION" => !empty($arResult["PREVIEW_TEXT"]) ? $arResult["PREVIEW_TEXT"]:$arResult["DETAIL_TEXT"],
        "LINK" => $APPLICATION->GetCurPage(),
        "IMAGE"=> $share_img_src
    )
);?>

</div>