<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<h2><?=$arResult['NAME']?></h2>

<?if (!empty($arResult['PROPERTIES']['duties']['VALUE'])):?>
	<h3>Обязанности</h3>
	<?=$arResult['PROPERTIES']['duties']['~VALUE']['TEXT']?>
<?endif;?>

<?if (!empty($arResult['PROPERTIES']['demands']['VALUE'])):?>
	<h3>Требования</h3>
    <div class="demands-box">

        <?=$arResult['PROPERTIES']['demands']['~VALUE']['TEXT']?>
    </div>
<?endif;?>

<?if (!empty($arResult['PROPERTIES']['conditions']['VALUE'])):?>
	<h3>Условия работы</h3>
    <div class="demands-box">
        <?=$arResult['PROPERTIES']['conditions']['~VALUE']['TEXT']?>
    </div>
<?endif;?>            
            
<?if (!empty($arResult['PROPERTIES']['salary']['VALUE'])):?>            
    <div class="fixheight"></div>
    <h3>Уровень заработной платы от <span><?=$arResult['PROPERTIES']['salary']['VALUE']?></span> рублей</h3>
<?endif;?>
<?//var_dump($arResult['LECTORS'])?>

<?if(!empty($arResult['LECTORS'])):?>
	<h2>Адрес и контактная информация</h2>

	<div class="creator-big">
		<span>По всем вопросам вы можете обратиться к специалисту по кадрам:</span>

		<div class="creator-cont">
			<img src="<?=$arResult['LECTORS']['PREVIEW_PICTURE']?>" alt=""/>

			<h3><?=$arResult['LECTORS']['NAME']?></h3>
			<span class="head"><?=$arResult['LECTORS']['PROPERTY_STATUS_VALUE']?><? if (!empty($arResult['LECTORS']['PROPERTY_COMPANY_VALUE'])) echo " / ".$arResult['LECTORS']['PROPERTY_COMPANY_VALUE']?></span>
			<ul class="contact">
				<li class="phone"><?=$arResult['LECTORS']['PROPERTY_PHONE_VALUE']?></li>
				<li class="email"><a href="mailto:<?=$arResult['LECTORS']['PROPERTY_MAIL_VALUE']?>"><?=$arResult['LECTORS']['PROPERTY_MAIL_VALUE']?></a></li>
			</ul>
		</div>
	</div>
<?endif;?>
<div class="fixheight big"></div>
<?$APPLICATION->IncludeFile(
    "/local/include/share.php",
    array(
        "TITLE" => $arResult["NAME"],
        "DESCRIPTION" => !empty($arResult["PREVIEW_TEXT"]) ? $arResult["PREVIEW_TEXT"]:$arResult["DETAIL_TEXT"],
        "LINK" => $APPLICATION->GetCurPage(),
        "IMAGE"=> $share_img_src
    )
);?>