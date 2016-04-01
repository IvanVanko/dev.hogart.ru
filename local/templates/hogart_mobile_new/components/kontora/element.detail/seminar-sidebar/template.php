<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?
$date_from = FormatDate("d.m.Y", MakeTimeStamp($arResult['PROPERTIES']['sem_start_date']['VALUE']));
$now=date($DB->DateFormatToPHP(CSite::GetDateFormat("SHORT")), time());?>

    <aside class="sidebar js-fh js-fixed-block js-paralax-height" data-fixed="top">
        <div class="inner js-paralax-item">
            <div class="padding">

<?if (strtotime($date_from) < strtotime($now) && $date_from != '01.01.1970'):?>
                <?if (!empty($arResult["PROPERTIES"]["materials"]["VALUE"])) {?>
                    <h2><?=$arResult['PROPERTIES']['materials']['NAME'];?></h2>
                    <ul class="ul-file">
                        <?foreach ($arResult["PROPERTIES"]["materials"]["DESCRIPTION"] as $key => $value):
    //                        $file = CFile::GetPath($arResult["PROPERTIES"]["materials"]['VALUE'][$key]);
                            $fileDetail = CFile::GetFileArray($arResult["PROPERTIES"]["materials"]['VALUE'][$key]);
    //	                    var_dump($fileDetail);
                            $fileSize = $fileDetail['FILE_SIZE'];
                            $fileSize = $fileSize/1024/1024;
                            $fileType = $fileDetail['CONTENT_TYPE'];
                            $fileType = explode('/', $fileType);

    //	                    var_dump($fileDetail);
                            ?>
                            <li>
                                <a href="<?=$fileDetail['SRC'];?>"><?=$value?></a>
                                <span>— .<?=$fileType[1]?>, <?=round($fileSize, 2)?> mb</span>
                            </li>
                        <?endforeach?>
                    </ul>
                <?}?>
	            <?if (!empty($arResult['ORGS'])):?>
                    <h2>Об организаторе</h2>
                    <div class="info-creator">
                        <div class="photo">
                            <img src="<?=CFile::GetPath($arResult['ORGS']['PREVIEW_PICTURE']);?>" alt=""/>
                            <h3><?=$arResult['ORGS']['NAME'];?></h3>
                        </div>

                        <div class="head"><?=$arResult['ORGS']['props']['status']['~VALUE'];?> / <?=$arResult['ORGS'][0]['props']['company']['VALUE'];?></div>
                        <ul class="contact">
                            <li class="phone"><?=$arResult['ORGS']['props']['phone']['VALUE'];?></li>
                            <li class="email"><a href="mailto:<?=$arResult['ORGS']['props']['mail']['VALUE'];?>"><?=$arResult['ORGS']['props']['mail']['VALUE'];?></a></li>
                        </ul>
                    </div>
	            <?endif;?>
<?elseif(empty($date_from) || $date_from == '01.01.1970'):?>
    <script type="text/javascript">
        $(document).ready(function () {
            $('.js-validation-empty.seminar').hide();
            $('.js-validation-empty.seminar input').val('<?=$arResult['~NAME']?>');

            if ($('.to-add-box').parent().not('.add-box')) {
                $('.to-add-box').wrapAll('<div class="adding-box"></div>');
            }

            var form_text_23_arr = [],
                form_text_24_arr = [],
                form_text_25_arr = [],
                form_text_26_arr = [],
                form_text_23,
                form_text_24,
                form_text_25,
                form_text_26;
            $('#form-5 form').submit(function () {
                if ($('.add-box').length) {
                    $('.inputtext[name=form_text_23]').each(function () {
                        form_text_23_arr.push($(this).val());
                    });
                    $('.inputtext[name=form_text_24]').each(function () {
                        form_text_24_arr.push($(this).val());
                    });
                    $('.inputtext[name=form_text_25]').each(function () {
                        form_text_25_arr.push($(this).val());
                    });
                    $('.inputtext[name=form_text_26]').each(function () {
                        form_text_26_arr.push($(this).val());
                    });

                    form_text_23 = form_text_23_arr.join(', '),
                        form_text_24 = form_text_24_arr.join(', '),
                        form_text_25 = form_text_25_arr.join(', '),
                        form_text_26 = form_text_26_arr.join(', ');
                    $('.final-add-box input').eq(0).val(form_text_23);
                    $('.final-add-box input').eq(1).val(form_text_24);
                    $('.final-add-box input').eq(2).val(form_text_25);
                    $('.final-add-box input').eq(3).val(form_text_26);
                }
            });
        });
    </script>
    <?$form_id = 5;
    if (BXHelper::can_show_form($form_id)) {
        BXHelper::start_ajax_block();
        $APPLICATION->IncludeFile(
            "/local/include/seminar_form.php",
            array(
                "FORM_ID" => $form_id,
                "FORM_VALUES" => array(
                    "SEMINAR_ID" => $arResult["ID"],
                    "SEMINAR_EAN_CODE" => $arResult['PROPERTIES']["sem_ean_id"]["VALUE"],
                    "HIDE_INPUTS" => false
                ),
            ),
            array(
                "SHOW_BORDER" => false
            )
        );
        BXHelper::end_ajax_block(false, false, false, false);
    }?>
    <a class="append-form trigger-border-bottom" href="#" data-clone-form>Добавить участника</a>
    <button type="submit" class="empty-btn" data-submit-form="<?=CStorage::getVar("seminar_form_name");?>">Отправить</button>
<?else:?>
    <script type="text/javascript">
        $(document).ready(function () {
            $('.js-validation-empty.seminar').hide();
            $('.date-picker').hide();
            $('.date-picker input').val('<?=$date_from?>');
            $('.js-validation-empty.seminar input').val('<?=$arResult['~NAME']?>');

            if ($('.to-add-box').parent().not('.add-box')) {
                $('.to-add-box').wrapAll('<div class="adding-box"></div>');
            }


            var form_text_23_arr = [],
                form_text_24_arr = [],
                form_text_25_arr = [],
                form_text_26_arr = [],
                form_text_23,
                form_text_24,
                form_text_25,
                form_text_26;
            $('#form-5 form').submit(function () {
                if ($('.add-box').length) {
                    $('.inputtext[name=form_text_23]').each(function () {
                        form_text_23_arr.push($(this).val());
                    });
                    $('.inputtext[name=form_text_24]').each(function () {
                        form_text_24_arr.push($(this).val());
                    });
                    $('.inputtext[name=form_text_25]').each(function () {
                        form_text_25_arr.push($(this).val());
                    });
                    $('.inputtext[name=form_text_26]').each(function () {
                        form_text_26_arr.push($(this).val());
                    });

                    form_text_23 = form_text_23_arr.join(', '),
                    form_text_24 = form_text_24_arr.join(', '),
                    form_text_25 = form_text_25_arr.join(', '),
                    form_text_26 = form_text_26_arr.join(', ');
                    $('.final-add-box input').eq(0).val(form_text_23);
                    $('.final-add-box input').eq(1).val(form_text_24);
                    $('.final-add-box input').eq(2).val(form_text_25);
                    $('.final-add-box input').eq(3).val(form_text_26);
                }
            });
        });
    </script>
    <?/*$APPLICATION->IncludeComponent(
        "bitrix:form.result.new",
        "sem_quest",
        Array(
            "WEB_FORM_ID" => "5",
            "IGNORE_CUSTOM_TEMPLATE" => "N",
            "USE_EXTENDED_ERRORS" => "N",
            "SEF_MODE" => "N",
            "VARIABLE_ALIASES" => Array("WEB_FORM_ID"=>"WEB_FORM_ID","RESULT_ID"=>"RESULT_ID"),
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => "3600",
            "LIST_URL" => "",
            "EDIT_URL" => "",
            "SUCCESS_URL" => "/register/index_s.php",
            "CHAIN_ITEM_TEXT" => "",
            "CHAIN_ITEM_LINK" => ""
        ), $component
    );*/?>
                <?$form_id = 5;
                if (BXHelper::can_show_form($form_id)) {
                    BXHelper::start_ajax_block();
                    $APPLICATION->IncludeFile(
                        "/local/include/seminar_form.php",
                        array(
                            "FORM_ID" => $form_id,
                            "FORM_VALUES" => array(
                                "SEMINAR_ID" => $arResult["ID"],
                                "SEMINAR_EAN_CODE" => $arResult['PROPERTIES']["sem_ean_id"]["VALUE"],
                                "HIDE_INPUTS" => false
                            ),
                        ),
                        array(
                            "SHOW_BORDER" => false
                        )
                    );
                    BXHelper::end_ajax_block(false, false, false, false);
                }?>
                <a class="append-form trigger-border-bottom" href="#" data-clone-form>Добавить участника</a>
                <button type="submit" class="empty-btn" data-submit-form="<?=CStorage::getVar("seminar_form_name");?>">Отправить</button>
                <?endif;?>
            </div>
        </div>
    </aside>

