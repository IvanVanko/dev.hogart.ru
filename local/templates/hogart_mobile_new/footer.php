<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)  die(); ?>
<?#die();?>
<?#if(defined("ERROR_404") && ERROR_404 == "Y" && $APPLICATION->GetCurPage(true) !='/404.php') LocalRedirect('/404.php');?>
</section>
	<?$APPLICATION->IncludeFile(
		INCLUDE_AREAS."block-mainpage-bottom-menu.php",
		Array(),
		Array("MODE"=>"html", "NAME"=>"Mainpage MNU")
	);?>	
	<!-- окно логина -->
	<!-- <section class="slide hide" id="profile_slide"> 
		<div class="top_block">
			<form action="" class="main-form">
				<div class="field">
					<label>Логин</label>
					<input type="text" value="">
				</div>
				<div class="field">
					<label>Пароль</label>
					<input type="password" value="">
				</div>
				<input type="submit" value="Войти">
				<a href="#" class="forget_link">Забыли пароль?</a>
				<a href="#" class="input-btn">регистрация</a>

			</form>
		</div>
	</section> -->




	<!-- Профиль -->
	<?$APPLICATION->IncludeFile(
		INCLUDE_AREAS."block-profile.php",
		Array(),
		Array("MODE"=>"html", "NAME"=>"Profile")
	);?>		
	<!-- Поиск -->
	<?$APPLICATION->IncludeFile(
		INCLUDE_AREAS."block-search.php",
		Array(),
		Array("MODE"=>"html", "NAME"=>"Search")
	);?>		
	<!-- Послать сообщение -->
	<?$APPLICATION->IncludeFile(
		INCLUDE_AREAS."block-send-message.php",
		Array(),
		Array("MODE"=>"html", "NAME"=>"Send message")
	);?>		
	<!-- Контакты -->
	<?$APPLICATION->IncludeFile(
		INCLUDE_AREAS."block-contacts.php",
		Array(),
		Array("MODE"=>"html", "NAME"=>"Contacts")
	);?>
</div>
<?/*$APPLICATION->IncludeFile(
	INCLUDE_AREAS."block-yandex-metrika.php",
		Array(),
		Array("MODE"=>"html", "NAME"=>"Yandex Metrika")
);?>
<?$APPLICATION->IncludeFile(
	INCLUDE_AREAS."block-google-analytics.php",
		Array(),
		Array("MODE"=>"html", "NAME"=>"Google Analitics")
);*/?>
<?if ($APPLICATION->GetCurDir() == "/") $st = "main_footer"; else $st =""?>
<footer class="footer <?=$st?>">
	<small>© <?=date("Y")?>, ООО «Хогарт»</small>
	<div class="credits"></div>
</footer>
</body>
</html>