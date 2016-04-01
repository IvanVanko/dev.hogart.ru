<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<div class="inner">
    <h1><?$APPLICATION->ShowTitle()?></h1>

    <h2><?=$arResult['NAME']?></h2>
    <address>
        <?=$arResult['PROPRTIES']['adress']['VALUE']?><br>
		<?if (!empty($arResult['PROPERTIES']['address']['VALUE'])):?>
            Адрес: <?=$arResult['PROPERTIES']['address']['VALUE']?><br>
        <?endif;
        if (!empty($arResult['PROPERTIES']['phone']['VALUE'])):?>
            тел.: <?=implode(', ', $arResult['PROPERTIES']['phone']['VALUE'])?><br>
        <?endif;
        if (!empty($arResult['PROPERTIES']['mail']['VALUE'])):
            $email_html = array();
            foreach ($arResult['PROPERTIES']['mail']['VALUE'] as $email) {
                $email_html[] = "<a href=\"mailto:".$email."\">$email</a>";
            }
            ?>e-mail: <?=implode(', ', $email_html)?><br>
        <?endif;?>
    </address>

    <ul class="js-tabs-list contact-tab">
        <?if (!empty($arResult['PROPERTIES']['by_car']['VALUE'])):?>
            <li>
                <a class="js-tab-trigger icon-car hover" href="#oneTab"><span>Проезд на автомобиле</span></a>
            </li>
        <?endif;
        if (!empty($arResult['PROPERTIES']['by_public']['VALUE'])):?>
            <li>
                <a class="js-tab-trigger icon-bus hover" href="#twoTab"><span>Проезд общественным транспортом</span></a>
            </li>
        <?endif;?>
    </ul>
    <div class="js-tab-item" data-id="#oneTab">
        <?=$arResult['PROPERTIES']['by_car']['~VALUE']['TEXT']?>
    </div>
    <div class="js-tab-item" data-id="#twoTab">
       <?=$arResult['PROPERTIES']['by_public']['~VALUE']['TEXT']?>
    </div>

    <!-- ul class="contact-tab no-tab">
        <li>
            <a class="icon-email black" href="#">Отправить на e-mail</a>
        </li>
        <li>
            <a class="icon-print black" href="#">Распечатать</a>
        </li>
        <li>
            <a class="icon-phone black" href="#">Отправить SMS</a>
        </li>
    </ul -->
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

<!--TODO если видео нет - не показываем (в ТЗ не предусмотрено)-->
<br/>
<div class="inner no-padding js-tab-item" data-id="#oneTab">
    <?if (!empty($arResult['PROPERTIES']['by_car_video']['VALUE'])):?>
        <a href="#" class="video-way video-video-way background-green" data-video="<?=$arResult['PROPERTIES']['by_car_video']['VALUE']?>">
            Посмотрите видео о том, как добраться на автомобиле
            <i class="icon-bottom icon-full"></i>
        </a>
        <div class="video-way-file" style="display: none;">
            <iframe width="100%" height="400px" src="https://www.youtube.com/embed/<?=$arResult['PROPERTIES']['by_car_video']['VALUE']?>?rel=0"
                    frameborder="0" allowfullscreen></iframe>
        </div>
    <?endif;?>
</div>
<div class="inner no-padding js-tab-item" data-id="#oneTab">
    <?if (!empty($arResult['PROPERTIES']['by_car_scheme']['VALUE'])):?>
        <a href="#" class="video-way background-green">
            Схема проезда на автомобиле
            <i class="icon-bottom icon-full"></i>
        </a>
        <div class="way-scheme" style="display: none;">
            <img src="<?= CFile::GetPath($arResult['PROPERTIES']['by_car_scheme']['VALUE']) ?>" alt=""/>

        </div>
    <?endif;?>
</div>
<div class="inner no-padding js-tab-item" data-id="#twoTab">
    <?if (!empty($arResult['PROPERTIES']['by_public_video']['VALUE'])):?>
        <a href="#" class="video-way video-video-way background-green" data-video="<?=$arResult['PROPERTIES']['by_public_video']['VALUE']?>">
            Посмотрите видео о том, как добраться на общественном транспорте
            <i class="icon-bottom icon-full"></i>
        </a>
        <div class="video-way-file" style="display: none;">
            <iframe width="100%" height="400px" src="https://www.youtube.com/embed/<?=$arResult['PROPERTIES']['by_public_video']['VALUE']?>?rel=0"
                    frameborder="0" allowfullscreen></iframe>
        </div>
    <?endif;?>
</div>
<div class="inner no-padding js-tab-item" data-id="#twoTab">
    <?if (!empty($arResult['PROPERTIES']['by_public_scheme']['VALUE'])):?>
        <a href="#" class="video-way background-green">
            Схема проезда на общественном транспорте
            <i class="icon-bottom icon-full"></i>
        </a>
        <div class="way-scheme " style="display: none;">
            <img src="<?= CFile::GetPath($arResult['PROPERTIES']['by_public_scheme']['VALUE']) ?>" alt=""/>
        </div>
    <?endif;?>
</div>

<?if (!empty($arResult['PROPERTIES']['map']['VALUE'])):
    $coords = explode(',', $arResult['PROPERTIES']['map']['VALUE']);?>
    <div class="inner map-cnt" id="map" data-lat="<?=$coords[0]?>" data-long="<?=$coords[1]?>">

    </div>
<?endif;?>
<input type="hidden" name="contactID" value="<?=$arResult['ID']?>" />
<div class="hide-it">
<?if ($USER->IsAuthorized()):?>
    <input type="hidden" name="user_mail" value="<?=$USER->GetEmail();?>"/>
<?else:?>
    <input type="text" name="user_mail" value=""/>
    <input type="submit" value="Отправить" />
<?endif;?>
</div>