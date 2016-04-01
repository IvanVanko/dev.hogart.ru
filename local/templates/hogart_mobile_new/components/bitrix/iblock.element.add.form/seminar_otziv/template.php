<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();
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
$this->setFrameMode(false);
global $USER;
?>
<!--form id="comment-form" name="add_comment" method="post"-->
<? ShowError() ?>

<!--<pre>--><? //var_dump($_REQUEST)?><!--</pre>-->

<? $arCommError = array();
if (!empty($arResult["ERRORS"])):
    ?>
    <?
    foreach ($arResult["ERRORS"] as $error) {
        $error = explode("'", $error);
        $arCommError[] = $error[1];
    }
    ?>
    <!--	--><? //ShowError(implode("<br />", $arResult["ERRORS"]))?>
    <p>Для того, что бы мы смогли опубликовать ваш отзыв, пожалуйста, заполните поля
        <?
        foreach ($arCommError as $key => $text) {

            if ($key != count($arCommError) - 1) {
                echo $text . ', ';
            } else {
                echo $text . '.';
            }
        }
        ?>
    </p>
<? endif;
if (strlen($arResult["MESSAGE"]) > 0):
    ?>
    <script>
        $('#comm-ok').each(function () {
            $(this).css({'margin-left': $(this).width() / -2, 'margin-top': $(this).height() / -2});
        })
    </script>
    <div class="popup-cnt" style="opacity: 1; display: block;">
        <div class="inner-cnt" id="comm-ok">
            <div class="head inner">
                <h2>Спасибо!</h2>
                <a href="#" class="close"></a>
            </div>
            <div class="inner">
                Ваш отзыв скоро появится.
                <div class="fixheight"></div>
            </div>

        </div>
    </div>
                <? endif; ?>


            <form name="iblock_add" id="comment-form" class="js-validation-form" action="<?= POST_FORM_ACTION_URI ?>" method="post" enctype="multipart/form-data">
<?= bitrix_sessid_post() ?>    
                <input type="hidden" value="<?= $arParams['SEMINAR_ID'] ?>" name="PROPERTY[354][0]" />
                <div class="row">
                    <div class="col3">
                        <div class="field custom_label js-validation-empty">
                            <label>Имя</label>
            <!--                <input type="text" name="PROPERTY[NAME][0]" value="--><? //=$USER->GetFullName() ?><!--">	-->
                            <input type="text" name="PROPERTY[NAME][0]" class="inputtext" value="<?= (!empty($_REQUEST['PROPERTY']['NAME'][0])) ? $_REQUEST['PROPERTY']['NAME'][0] : $USER->GetFullName() ?>">
                        </div>
                    </div>
                    <div class="col3">
                        <div class="field custom_label js-validation-empty">
                            <label>Компания</label>
                            <input type="text" name="PROPERTY[353][0]" class="inputtext" value="<?= (!empty($_REQUEST['PROPERTY']['353'][0])) ? $_REQUEST['PROPERTY']['353'][0] : $arResult['USER']['WORK_COMPANY'] ?>">
                        </div>
                    </div>
                    <div class="col3">
                        <div class="field custom_label js-validation-empty">
                            <label>должность</label>
                            <input type="text" name="PROPERTY[355][0]" class="inputtext" value="<?= (!empty($_REQUEST['PROPERTY']['355'][0])) ? $_REQUEST['PROPERTY']['355'][0] : $arResult['USER']['WORK_POSITION'] ?>">
                        </div>
                    </div>
                </div>
                <div class="field custom_label js-validation-empty">
                    <label>Отзыв</label>
                    <? /* textarea id="textarea-<?=$_REQUEST["CID"];?>"  name="comment"><?=$_REQUEST['comment']?></textarea */ ?>
                    <textarea name="PROPERTY[PREVIEW_TEXT][0]" class="inputtext" value="<?= (!empty($_REQUEST['PROPERTY']['PREVIEW_TEXT'][0])) ? $_REQUEST['PROPERTY']['PREVIEW_TEXT'][0] : '' ?> "></textarea>
                </div>
                <div class="text-right for-comm-button">
                    <!--div class="b-box " data-popup="#comm-ok"></div>
                    <div class="b js-popup-open " data-popup="#comm-ok"></div-->
                    <input type="submit" name="iblock_submit" class="empty-btn black" value="Отправить отзыв" />
<? /* button class="empty-btn black" type="submit" value="<?=GetMessage('BUTTON_SEND')?>" name="add_comment">Отправить отзыв</button */ ?>
                    <!--        <input class="empty-btn black js-popup-open" data-popup="#comm-ok" type="submit" value="Отправить отзыв" name="add_comment"/>-->
                </div>
                <!--<div>111</div>
                <textarea maxlength="300" id="textarea-1" name="comment"><? /* =$_REQUEST['comment'] */ ?></textarea>
                <div id="textareaFeedback"></div>
                <br /><input type="submit" value="<? /* =GetMessage('BUTTON_SEND') */ ?>" name="add_comment" />-->
            </form>
