<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?//pr($arResult);?>
<?$arSeminars = $arResult['SEMINARS'];?>
<?if (!empty($arResult['arrResults'])) {?>
    <?$this->SetViewTarget('SEMINAR_PREVIEW_TEXT');?>
        <h1>Вы успешно зарегистрированы на семинар
            «<?=$arSeminars[$arResult['arrResults'][0]['SEMINAR_ID']]['NAME']?>»</h1>
        <small>Спасибо, что обратились в нашу компанию! Ваша заявка на семинар принята. В ближайшее время с вами свяжется специалист для уточнения деталей.
        </small>
    <?$this->EndViewTarget();?>
<?} else {?>
    <?$this->SetViewTarget('SEMINAR_PREVIEW_TEXT');?>
    <h1>Такой заявки не существует</h1>
    <?$this->EndViewTarget();?>
<?}?>
<?foreach ($arResult['arrResults'] as $arFormResult) {
    $s_id = $arFormResult['SEMINAR_ID'];?>
    <div class="green-line-registration">
        <a target="__blank" href="/learn/result_print.php?find_id=<?=$arFormResult['ID']?>" class="icon-print black nohover"><span>Распечатать приглашение</span></a>

        <div class="right">
            <a href="#" class="icon-phone black nohover js-popup-open" data-popup="#seminar-result-phone<?=$arFormResult['ID']?>"><span>Отправить по смс</span></a>
            <a href="#" class="icon-email black nohover js-popup-open" data-popup="#seminar-result-email<?=$arFormResult['ID']?>"><span><?= GetMessage("Отправить на e-mail") ?></span></a>
        </div>
    </div>
    <div class="reg-kupon">
        <div class="col1">
            <img src="<?=$arFormResult['BARCODE_BASE64'];?>" alt=""/>

            <?if (!empty($arSeminars[$s_id]['ORG_NAME'])) {?>
                <h3>Организатор</h3>
                <span><?=$arSeminars[$s_id]['ORG_NAME']?></span>
                <?if (!empty($arSeminars[$s_id]['ORG_PHONE'])) {?>
                    <span>Тел.: <?=$arSeminars[$s_id]['ORG_PHONE']?></span>
                <?}?>
                <?if (!empty($arSeminars[$s_id]['ORG_MAIL'])) {?>
                    <span>E-mail: <a href="mailto:<?=$arSeminars[$s_id]['ORG_MAIL']?>"><?=$arSeminars[$s_id]['ORG_MAIL']?></a></span>
                <?}?>
            <?}?>
        </div>
        <div class="col2">
            <h2>Приглашение на семинар<br><?=$arSeminars[$s_id]['NAME']?></h2>

            <h3><?=$arFormResult['USER_NAME']?></h3>

            <div class="big-text"><?=$arFormResult['USER_COMPANY']?></div>
            <div class="row">
                <div class="col2">
                    <h3>Дата и время</h3>
                    <span><?=$arSeminars[$s_id]['DISPLAY_BEGIN_DATE']?></span>
                </div>
                <div class="col2">
                    <h3>Адрес</h3>
                    <span><?=$arSeminars[$s_id]['ADDRESS']?></span>
                </div>
            </div>
            <h3>Лекторы семинара</h3>

            <p>
                <?$lectors_html_array = array();?>
                <?foreach ($arSeminars[$s_id]['LECTURERS'] as $arLecture) {
                    $lectors_html_array[] = $arLecture["NAME"]." / "."<span class=\"company-reg\">".$arLecture["COMPANY"].", ".$arLecture["POST"]."</span>";
                }?>
                <?print(implode(", <br>",$lectors_html_array));?>
            </p>
        </div>
        <i>* Приглашение дейстивительно только при предъявлении лицом, на которое оно выписано.</i>
    </div>
    <div class="popup-cnt">
        <div class="inner-cnt" id="seminar-result-phone<?=$arFormResult['ID']?>">

            <div class="head inner">
                <h2>Отправить по SMS</h2>
                <a href="#" class="close"></a>
            </div>
            <? //include_once "/ajax/smsc_api.php";?>
            <!--        <form action="/ajax/send_article.php" method="post">-->
            <!--            <form action="/ajax/send.php" method="post">-->
            <form action="/ajax/smsc_send_seminar_result.php" method="post" class="ajax-userform">
                <!--                send_sms_mail("79999999999", "Ваш пароль: 123");-->
                <div class="inner form-cont-box">
                    <div class="field custom_label phone">
                        <label for="sending_phone">телефон:<span class="form-required starrequired">*</span></label>
                        <input type="text" class="inputtext" name="sending_phone"
                               value="" size="0">
                    </div>
                    <input type="hidden" name="seminar_name" value="<?=$arSeminars[$s_id]['NAME']?>">
                    <input type="hidden" name="seminar_registration_number" value="">
                    <input type="hidden" name="page_href" value="<?=$_SERVER['SERVER_NAME']."/learn/result.php?find_id=".$arFormResult['ID']?>">
                </div>
                <hr>
                <div class="inner form-cont-box">
                    <input type="submit" name="sending_phone_form" class="empty-btn black" value="Отправить">
                    <small>Поля, отмеченные * обязательны для заполнения.</small>
                </div>
                <div class="inner success" style="display: none;">
                    Вы поделились ссылкой успешно!
                </div>
            </form>
        </div>
    </div>
    <div class="popup-cnt">
        <div class="inner-cnt" id="seminar-result-email<?=$arFormResult['ID']?>">

            <div class="head inner">
                <h2>Отправить по Email</h2>
                <a href="#" class="close"></a>
            </div>
            <form action="/ajax/mail_send_seminar_result.php" method="post" class="ajax-userform">
                <!--                send_sms_mail("79999999999", "Ваш пароль: 123");-->
                <div class="inner form-cont-box">
                    <div class="field custom_label email">
                        <label for="email">email:<span class="form-required starrequired">*</span></label>
                        <input type="text" class="inputtext" name="email"
                               value="" size="0">
                    </div>
                    <input type="hidden" name="seminar_name" value="<?=$arSeminars[$s_id]['NAME']?>">
                    <input type="hidden" name="seminar_registration_number" value="">
                    <input type="hidden" name="page_href" value="<?=$_SERVER['SERVER_NAME']."/learn/result.php?find_id=".$arFormResult['ID']?>">
                </div>
                <hr>
                <div class="inner form-cont-box">
                    <input type="submit" name="sending_phone_form" class="empty-btn black" value="Отправить">
                    <small>Поля, отмеченные * обязательны для заполнения.</small>
                </div>
                <div class="inner success" style="display: none;">
                    Вы поделились ссылкой успешно!
                </div>
            </form>
        </div>
    </div>
<?}?>