<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<div class="control control-action">
        <? /* if (isset($arResult['PREV'])):?>
            <span class="prev black"><a href="<?=$arResult['PREV']?>"></a></span>
        <?endif;?>
        <?if (isset($arResult['NEXT'])):?>
            <span class="next black"><a href="<?=$arResult['NEXT']?>"></a></span>
        <?endif; */ ?>
        <span class="prev <?if (isset($arResult['PREV'])):?> black <?endif;?>"><a href="<?=$arResult['PREV']?>"></a></span>
        <span class="next <?if (isset($arResult['NEXT'])):?> black <?endif;?>"><a href="<?=$arResult['NEXT']?>"></a></span>
    </div>
<h1><?=$arResult["NAME"]?></h1>

<?=$arResult["DETAIL_TEXT"]?>
<?$share_img_src = $_SERVER['SERVER_NAME'].$arResult['DETAIL_PICTURE']['SRC'];?>
<?$APPLICATION->IncludeFile(
    "/local/include/share.php",
    array(
        "TITLE" => $arResult["NAME"],
        "DESCRIPTION" => !empty($arResult["PREVIEW_TEXT"]) ? $arResult["PREVIEW_TEXT"]:$arResult["DETAIL_TEXT"],
        "LINK" => $APPLICATION->GetCurPage(),
        "IMAGE"=> $share_img_src
    )
);?>
<a class="back_page icon-news-back" href="<?=$arParams['SEF_FOLDER']?>">Назад к полезной информации</a>
