<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?//pr($arResult);?>
<?$arSeminars = $arResult['SEMINARS'];?>
<?foreach ($arResult['arrResults'] as $arFormResult) {
    $s_id = $arFormResult['SEMINAR_ID'];?>
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
<?}?>