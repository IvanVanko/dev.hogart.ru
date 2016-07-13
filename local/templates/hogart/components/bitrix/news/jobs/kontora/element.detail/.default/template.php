<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<h3><?=$arResult['NAME']?></h3>

<?if (!empty($arResult['PROPERTIES']['duties']['VALUE'])):?>
	<h4>Обязанности</h4>
	<div class="demands-box">
		<?=$arResult['PROPERTIES']['duties']['~VALUE']['TEXT']?>
	</div>
<?endif;?>

<?if (!empty($arResult['PROPERTIES']['demands']['VALUE'])):?>
	<h4>Требования</h4>
    <div class="demands-box">
        <?=$arResult['PROPERTIES']['demands']['~VALUE']['TEXT']?>
    </div>
<?endif;?>

<?if (!empty($arResult['PROPERTIES']['conditions']['VALUE'])):?>
	<h4>Условия работы</h4>
    <div class="demands-box">
        <?=$arResult['PROPERTIES']['conditions']['~VALUE']['TEXT']?>
    </div>
<?endif;?>            
            
<?if (!empty($arResult['PROPERTIES']['salary']['VALUE'])):?>            
    <h4>Уровень заработной платы от <span class="color-green"><?=$arResult['PROPERTIES']['salary']['VALUE']?></span> рублей</h4>
<?endif;?>

<?if(!empty($arResult['LECTORS'])):?>
	<div class="creator-big">
		<span class="h5">По всем вопросам вы можете обратиться к специалисту по кадрам:</span>

		<div class="creator-cont">
			<img src="<?=$arResult['LECTORS']['PREVIEW_PICTURE']?>" alt=""/>

			<h4><?=$arResult['LECTORS']['NAME']?></h4>
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