<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if ($APPLICATION->GetCurDir() != "/") return;?>
	<!-- Меню на главной -->
	<ul class="main_menu menu_animation big-menu">
		<li><a href="#main_slide" class="slide-trigger" data-effect="mainMenuAnimation">компания</a>
			<ul class="level_2">
				<li><a href="/company/">О компании</a></li>
				<li><a href="/contacts/tsentralnyy-ofis-khogart-v-moskve-sklad-i-servisnaya-sluzhba/">склады и офисы</a></li>
				<li><a href="/company/comments/">отзывы</a></li>
			</ul>
		</li>
		<li><a href="#main_slide" class="slide-trigger" data-effect="mainMenuAnimation">Новости <br> и события</a>
			<?
				$date = new DateTime();
				date_sub($date, date_interval_create_from_date_string('2 month'));
				$APPLICATION->IncludeComponent("kontora:element.list", "main_news_mobile", array(
					'IBLOCK_ID' => '3',
					'FILTER'=>array(
						"PROPERTY_tag" => array(2,4),
						">=DATE_ACTIVE_FROM" => date_format($date, 'd-m-Y')." 00:00:00"
						),
					'ORDER' => array('property_priority' => 'asc,nulls', 'active_from' => 'desc'),
					'ELEMENT_COUNT' => 3,
				));
			?>	
		</li>
		<li><a href="#main_slide" class="slide-trigger" data-effect="mainMenuAnimation">каталог продукции</a>
			<ul class="level_2">
				<li><a href="/catalog/otoplenie/">Отопление</a></li>
				<li><a href="/catalog/ventilyatsiya/">Вентиляция</a></li>
				<li><a href="/catalog/santekhnika/">Сантехника</a></li>
			</ul>
		</li>
		<li><a href="#main_slide" class="slide-trigger" data-effect="mainMenuAnimation">обучение</a>
			<ul class="level_2">
				<li><a href="/learning/">семинары</a></li>
				<li><a href="/learning/archive-seminarov/">архив</a></li>
				<li><a href="/documentation/">информация</a></li>
			</ul>
		</li>
		<li><a href="/integrated-solutions/" class="">комплексные решения</a></li>
		<li><a href="/services/" class="">сервисное обслуживание</a></li>
	</ul>
