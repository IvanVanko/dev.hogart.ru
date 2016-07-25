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

<div class="row">
    <div class="col-md-9">
        <h3><?= $APPLICATION->GetTitle() ?></h3>
        <div class="news-one-cnt">
            <div class="padding-news">
                <div class="date">
                    <?=$date_from?>
                </div>

                <? if (isset($arResult["PROPERTIES"]["ADDRESS"]["VALUE"])): ?>
                    <div class="address">
                        <?=$arResult["PROPERTIES"]["ADDRESS"]["VALUE"]?>
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
    <div class="col-md-3 aside">
        <div class="row">
            <div class="col-md-12">
                <? if($arResult['PROPERTIES']['REG_OPEN']['VALUE'] == 'Y'): ?>
                    <h3 class="nomargin">Регистрация на мероприятие</h3>

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
                            <input type="submit" class="btn btn-primary" value="Отправить">
                        </div>
                    </form>
                <? endif; ?>
            </div>
        </div>
        <div class="row" style="margin-top: 20px;">
            <div class="col-md-12">
                <? if(!empty($arResult['ORGS'])): ?>
                    <h3>Контактные лица</h3>
                    <? foreach($arResult['ORGS'] as $arItem) {?>
                        <div class="info-creator">
                            <div class="photo">
                                <img src="<?
                                $pic = CFile::ResizeImageGet($arResult['ORGS']['PREVIEW_PICTURE'],
                                    array('width' => 250, 'height' => 320), BX_RESIZE_IMAGE_PROPORTIONAL, true, array());
                                echo $pic['src'];
                                ?>" alt=""/>
                                <h4><?=$arItem['NAME'];?></h4>
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
    </div>
</div>
