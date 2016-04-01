<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$date_from = FormatDate("d F", MakeTimeStamp($arResult["ACTIVE_FROM"]));
$date_to = FormatDate("d F", MakeTimeStamp($arResult["ACTIVE_TO"]));?>
<div class="inner">
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
    <h1>Акции</h1>
    <ul class="action-list-one">
        <li>
            <div class="inner">
                <div class="date"><?=$date_from.' – '.$date_to?></div>
                <h2><?=$arResult['NAME']?></h2>

                <p>
                    <?=$arResult['PREVIEW_TEXT']?>
                </p>
            </div>
            <img src="<?=$arResult['DETAIL_PICTURE']['SRC']?>" alt=""/>

            <div class="inner">
                <?=$arResult['DETAIL_TEXT']?>
            </div>
        </li>
    </ul>
    <a class="back_page icon-news-back" href="<?=$arParams['SEF_FOLDER']?>">Назад к акциям</a>
</div>
<!--form name="email" style="diapley:none;" action="/ajax/send_to_email.php"-->
    <div class="strange-block hide-it">
        <input type="hidden" name="actionID" value="<?=$arResult['ID']?>" />
        <?if ($USER->IsAuthorized()):?>
            <input type="hidden" name="user_mail" value="<?=$USER->GetEmail();?>"/>
        <?else:?>
            <input type="text" name="user_mail" value=""/>
            <input type="submit" value="Отправить" />
        <?endif;?>
    </div>  
<!--/form-->