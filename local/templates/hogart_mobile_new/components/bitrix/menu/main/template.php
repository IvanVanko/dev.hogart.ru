<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?/*if (!empty($arResult)):
	$depth_level = 0;?>
	<ul class="presentation-main-page js-fh">
		<?foreach ($arResult as $arItem):
			if ($depth_level == 1 && $arItem["DEPTH_LEVEL"] == 1)
				$str = '</h1><hr></div></li><li><img src="'.$arItem['PARAMS']['picture'].'" alt="Hogart"/><div class="inner"><h1>';
		    elseif ($depth_level == 1 && $arItem["DEPTH_LEVEL"] == 2)
		    	$str = '</h1><ul><li>';
		    elseif ($depth_level == 2 && $arItem["DEPTH_LEVEL"] == 2)
		    	$str = '</li><li>';
		    elseif ($depth_level == 2 && $arItem["DEPTH_LEVEL"] == 1)
		    	$str = '</li></ul><hr></div></li><li><img src="'.$arItem['PARAMS']['picture'].'" alt="Hogart"/><div class="inner"><h1>';
			elseif ($depth_level == 0 && $arItem["DEPTH_LEVEL"] == 1)
				$str = '<li><img src="'.$arItem['PARAMS']['picture'].'" alt="Hogart"/><div class="inner"><h1>';?>

		    <?=$str?><a href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a>
			
			<?$depth_level = $arItem["DEPTH_LEVEL"];
		endforeach;?>
		
		<?if ($depth_level == 1)
			echo '</h1><hr></div></li>';
		else
			echo '</li></ul><hr></div></li>';?>
	</ul>
<?endif*/?>

<?#DebugMessage($arResult);?>

<!--
<section class="slide " id="main_slide">
	<ul class="main_menu menu_animation big-menu">
		<li><a href="#main_slide" class="slide-trigger" data-effect="mainMenuAnimation">компания</a>
			<ul class="level_2">
				<li><a href="#">О компании</a></li>
				<li><a href="#">склады и офисы</a></li>
				<li><a href="#">отзывы</a></li>
			</ul>

		</li>
		<li><a href="#main_slide" class="slide-trigger" data-effect="mainMenuAnimation">Новости <br> и события</a>

			
			<div class="news_wrap menu_inner">
				<div class="owl-carousel" data-pagination="true">
					<div class="news-item">
						<time>2015-06-03</time>
						<p>Oventrop снизил цены на гидравлическую стрелку/ гребенку HydroFixx DN 25</p>
					</div>
					<div class="news-item">
						<time>2015-06-03</time>
						<p>Oventrop снизил цены на гидравлическую стрелку/ гребенку HydroFixx DN 25</p>
					</div>
					<div class="news-item">
						<time>2015-06-03</time>
						<p>Oventrop снизил цены на гидравлическую стрелку/ гребенку HydroFixx DN 25</p>
					</div>
				</div>
			</div>


		</li>
		<li><a href="#main_slide" class="slide-trigger" data-effect="mainMenuAnimation">каталог продукции</a>
			<ul class="level_2">
				<li><a href="#">Отопление</a></li>
				<li><a href="#">Вентиляция</a></li>
				<li><a href="#">Сантехника</a></li>
			</ul>
		</li>
		<li><a href="#main_slide" class="slide-trigger" data-effect="mainMenuAnimation">обучение</a>

			<ul class="level_2">
				<li><a href="#">семинары</a></li>
				<li><a href="#">архив</a></li>
				<li><a href="#">информация</a></li>
			</ul>

		</li>
		<li><a href="#" class="">комплексные решения</a></li>
		<li><a href="#" class="">сервисное обслуживание</a></li>
	</ul>
</section>
/-->