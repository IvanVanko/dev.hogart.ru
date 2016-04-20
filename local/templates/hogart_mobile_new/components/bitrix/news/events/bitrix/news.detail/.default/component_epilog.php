<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
// заменяем $arResult эпилога значением, сохраненным в шаблоне
if(isset($arResult['arResult'])) {
   $arResult =& $arResult['arResult'];
} else {
   return;
}
$date_from = FormatDate("d F Y", MakeTimeStamp($arResult["PROPERTIES"]["DATE"]["VALUE"]));
$share_img_src = false; ?>
<? //pr($arResult);?>
<div class="inner">
    <h1><? $APPLICATION->ShowTitle() ?></h1>

    <div class="news-one-cnt">
        <div class="padding-news">
            <div class="date">
                <sub><?=$date_from?></sub>
            </div>

            <? if (isset($arResult["PROPERTIES"]["ADDRESS"]["VALUE"])): ?>
                <div class="address">
                    <sub><?=$arResult["PROPERTIES"]["ADDRESS"]["VALUE"]?></sub>
                </div>
            <? endif; ?>

            <? if(!empty($arResult['PREVIEW_TEXT'])): ?><p><?=$arResult['PREVIEW_TEXT']?></p><? endif; ?>
        </div>

        <? if(!empty($arResult['PREVIEW_PICTURE']['SRC'])):
            $share_img_src = $arResult['PREVIEW_PICTURE']['SRC'];
            $pic = CFile::ResizeImageGet($arResult['PREVIEW_PICTURE']['ID'],
                array('width' => 500, 'height' => 500), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true, array());
            ?>
            <div class="news-detail-pic">
                <div class="img-wrap">
                    <img class="js-popup-open-img" title="<?=$arResult['NAME']?>" src="<?=$pic['src']; ?>" alt=""/>
                </div>
            </div>
        <? endif; ?>

        <div class="padding-news">
            <?=$arResult['DETAIL_TEXT']?>
        </div>
        <? if(!empty($arResult['DETAIL_PICTURE']['SRC'])):
            $share_img_src = $arResult['DETAIL_PICTURE']['SRC']; ?>
            <div class="news-detail-pic">
                <div class="img-wrap">
                    <img class="js-popup-open-img" title="<?=$arResult['NAME']?>"
                         src="<?
                         $pic = CFile::ResizeImageGet($arResult['DETAIL_PICTURE']['ID'],
                             array('width' => 500, 'height' => 500), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true, array());
                         echo $pic['src']; ?>" alt=""/>
                </div>
            </div>
        <? endif; ?>
    </div>
</div>
<aside class="sidebar js-fh js-fixed-block js-paralax-height" data-fixed="top">
    <div class="inner js-paralax-item">
        <div class="padding">
            <? if($arResult['PROPERTIES']['REG_OPEN']['VALUE'] == 'Y'): ?>
                <h2 class="nomargin">Регистрация на мероприятие</h2>

                <form method="post" class="eventRegistrationForm" data-slide-enable="true" action="/ajax/eventRegister.php" data-ajax-form data-parsley-validate>
                    <div data-form-message>
                        <div data-text-holder>
                            <div class="" data-place-text>

                            </div>
                        </div>
                    </div>
                    <div data-fields>

                        <div class="field custom_label">
                            <label class="">Фамилия*</label>
                            <input type="text" name="fields[last_name]" value=""
                                   required>
                        </div>
                        <div class="field custom_label">
                            <label class="">Имя*</label>
                            <input type="text" name="fields[name]" value=""
                                   required>
                        </div>
                        <div class="field custom_label">
                            <label class="">Отчество</label>
                            <input type="text" name="fields[surname]" value="" />
                        </div>
                        <div class="field custom_label">
                            <label class="">Компания*</label>
                            <input type="text" name="fields[company]" value=""
                                   required>
                        </div>
                        <div class="field custom_label">
                            <label class="">Телефон*</label>
                            <input data-phone-mask type="text" name="fields[phone]" value=""
                                   required>
                        </div>
                        <div class="field custom_label">
                            <label class="">Адрес электронной почты*</label>
                            <input type="email" name="fields[email]" value=""
                                   required>
                        </div>
                        <input type="hidden" name="fields[event_name]" value="<?=$arResult['NAME']?>"/>
                        <input type="hidden" name="fields[event]" value="<?=$arResult['ID']?>"/>
                        <br>
                        <small>Поля, отмеченные * обязательны для заполнения.</small>
                        <br>
                        <input type="submit" class="empty-btn" value="Отправить">
                    </div>
                </form>
            <? endif; ?>
            <? if(!empty($arResult['ORGS'])): ?>
                <h2>Контактные лица</h2>
                <? foreach($arResult['ORGS'] as $arItem) {?>
                    <div class="info-creator">
                        <div class="photo">
                            <img src="<?
                            $pic = CFile::ResizeImageGet($arResult['ORGS']['PREVIEW_PICTURE'],
                                array('width' => 250, 'height' => 320), BX_RESIZE_IMAGE_PROPORTIONAL, true, array());
                            echo $pic['src'];
                            ?>" alt=""/>
                            <h3><?=$arItem['NAME'];?></h3>
                        </div>
                        <div class="head"><?=$arItem['props']['status']['~VALUE'];?>
                            <?if(strlen($arItem['props']['company']['VALUE']) > 0){?>
                                / <?=$arItem['props']['company']['VALUE'];?>
                            <?}?>
                        </div>
                        <ul class="contact">
                            <?if(strlen($arItem['props']['phone']['VALUE']) > 0){?>
                                <li class="phone"><?=$arItem['props']['phone']['VALUE'];?></li>
                            <?}?>
                            <?if(strlen($arItem['props']['mail']['VALUE']) > 0){?>
                                <li class="email"><a
                                        href="mailto:<?=$arItem['props']['mail']['VALUE'];?>">
                                        <?=$arItem['props']['mail']['VALUE'];?></a>
                                </li>
                            <?}?>
                        </ul>
                    </div>
                <?}?>
            <? endif; ?>
        </div>
    </div>
</aside>