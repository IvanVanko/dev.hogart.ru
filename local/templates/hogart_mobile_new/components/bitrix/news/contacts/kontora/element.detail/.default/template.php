<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<div class="inner-wrap contacts-content">
	<h1><?=$arResult['NAME']?></h1>       
	<dl>	
		<?if (!empty($arResult['PROPERTIES']['address']['VALUE'])):?>
		<dt>Адрес</dt><dd><?=$arResult['PROPERTIES']['address']['VALUE']?></dd>
		<?endif;?>
		<!--<dt>Часы работы</dt>	<dd>00:00-00:00</dd>/-->
		<?if (!empty($arResult['PROPERTIES']['phone']['VALUE'])):?>
		<dt>Телефон</dt>
			<dd>
				<a href="tel:<?=$arResult['PROPERTIES']['phone']['VALUE']?>"><?=implode(', ', $arResult['PROPERTIES']['phone']['VALUE'])?></a><br>
			</dd>
		<?endif;?>
		<?if (!empty($arResult['PROPERTIES']['mail']['VALUE'])):?>
		<dt>E-mail</dt>
			<dd>
				<a href="mailto:<?=$arResult['PROPERTIES']['mail']['VALUE']?>"><?=implode(', ', $arResult['PROPERTIES']['mail']['VALUE'])?></a><br>
				<!--<?$email_html = array();
				foreach ($arResult['PROPERTIES']['mail']['VALUE'] as $email) {
					$email_html[] = "<a href=\"mailto:".$email."\">$email</a>";
				}
				?>
				<?=implode(', ', $email_html)?><br>/-->
			</dd>
		<?endif;?>
		<?#DebugMessage($arResult['PROPERTIES']['map']['VALUE']);?>
		<?if (!empty($arResult['PROPERTIES']['map']['VALUE'])):?>
		<dt>Расположение на карте</dt>
		<dd>
			<div class="map-wrap">
				<a href="http://maps.google.com/maps?daddr=<?=$arResult['PROPERTIES']['map']['VALUE']?>" class="icon_1"></a>
				<a href="http://maps.google.com/maps?daddr=<?=$arResult['PROPERTIES']['map']['VALUE']?>" class="icon_2"></a>
				<div class="contacts-map-main" id="map-contacts-main"  data-val="<?=$arResult['PROPERTIES']['map']['VALUE']?>"></div>
			</div>
		</dd>
		<?endif;?>
		<!-- схема - изображение -->
		<?if (!empty($arResult['PROPERTIES']['by_car_scheme']['VALUE'])):?>
		<dt>Как проехать на машине</dt>
		<dd>
			<img src="<?= CFile::GetPath($arResult['PROPERTIES']['by_car_scheme']['VALUE']) ?>" alt=""/>
		</dd>
		<?endif;?>
		

		<!-- или схема-видео-->
		<?if (strlen($arResult['PROPERTIES']['by_car_video']['VALUE']) > 0):?>
		<dt>Как проехать на машине-видео</dt>
		<dd>
			<div class="video_wrap">
				
				<iframe width="100%" height="400px" src="https://www.youtube.com/embed/<?=$arResult['PROPERTIES']['by_car_video']['VALUE']?>?rel=0&autoplay=0&showinfo=0&controls=0"
					frameborder="0" allowfullscreen></iframe>
			</div>

		</dd>
		<?endif;?>
	</dl>       
</div>
<a href="/contacts/" class="btn arrow_btn">ПОКАЗАТЬ все адреса</a>

<a href="#" class="btn link-btn arrow-icon"><?= GetMessage("Отправить на e-mail") ?></a>
<a href="#" class="btn link-btn arrow-icon">Отправить по SMS</a>