<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?if (count($arResult['ITEMS']) > 0):?>
<ul class="inner_menu menu_animation big-menu height-auto">
		<?foreach ($arResult["ITEMS"] as $arItem): //Цикл по всем элементам?>
			<li><a href="#main_slide" class="slide-trigger" data-effect="mainMenuAnimation"><?=$arItem['NAME']?></a>
				<div class="menu_inner inner_block content-text inner_block_content">
					<?#DebugMessage($arItem["PROPERTIES"]["duties"]);?>
					<?if(strlen($arItem["PROPERTIES"]["duties"]["VALUE"]["TEXT"]) > 0):?>
					<h2><?=$arItem["PROPERTIES"]["duties"]["NAME"]?></h2>
					<?=$arItem["PROPERTIES"]["duties"]["~VALUE"]["TEXT"]?>
					<?endif;?>
					<?if(strlen($arItem["PROPERTIES"]["demands"]["VALUE"]["TEXT"]) > 0):?>
					<h2><?=$arItem["PROPERTIES"]["demands"]["NAME"]?></h2>
					<?=$arItem["PROPERTIES"]["demands"]["~VALUE"]["TEXT"]?>
					<?endif;?>
					<?if(strlen($arItem["PROPERTIES"]["conditions"]["VALUE"]["TEXT"]) > 0):?>
					<h2><?=$arItem["PROPERTIES"]["conditions"]["NAME"]?></h2>
					<?=$arItem["PROPERTIES"]["conditions"]["~VALUE"]["TEXT"]?>
					<?endif;?>
					<?if(strlen($arItem["PROPERTIES"]["salary"]["VALUE"]) > 0):?>
					<div class="salary"><?=$arItem["PROPERTIES"]["salary"]["VALUE"]?> рублей</div>
					<?endif;?>
					

					<div class="ajax-form-wrap">
						<a href="javascript:void(0);" class="btn link-btn arrow-icon open-next push-block-right ajax-hide">откликнуться</a>
						<div class="green-form-wrap open-block hidden_block push-block-right">
						<?$APPLICATION->IncludeComponent(
							"bitrix:form.result.new",
							"resume-mobile",
								Array(
								"WEB_FORM_ID" => "3",
								"IGNORE_CUSTOM_TEMPLATE" => "N",
								"USE_EXTENDED_ERRORS" => "Y",
								"SEF_MODE" => "N",
								"VARIABLE_ALIASES" => Array("WEB_FORM_ID" => "WEB_FORM_ID","RESULT_ID" => "RESULT_ID"),
								"CACHE_TYPE" => "N",
								"CACHE_TIME" => "3600",
								"LIST_URL" => "",
								"EDIT_URL" => "",
								"SUCCESS_URL" => "",
								"CHAIN_ITEM_TEXT" => "",
								"CHAIN_ITEM_LINK" => "",
								"SUCCESS_MESSAGE" => "Спасибо, что обратились в нашу компанию! Ваша заявка принята. В ближайшее время с вами свяжется специалист компании для уточнения деталей.",
								"AJAX_MODE" => "Y",  // режим AJAX
								"AJAX_OPTION_SHADOW" => "Y", // затемнять область
								"AJAX_OPTION_JUMP" => "N", // скроллить страницу до компонента
								"AJAX_OPTION_STYLE" => "N", // подключать стили
								"AJAX_OPTION_HISTORY" => "N",                        
								), $component 
							);?>
						</div>							
					</div>
					<!--
					
							<div class="confirm">
								<p>СПАСИБО ЗА ИНТЕРЕС К ВАКАНСИИ</p>
								<p>Мы свяжемся с вами, если ваше резюме нас заинтересует</p>
							</div>
							<form action="" class="job-form green-form ajax-hide simple_validate">
								<div class="field">
									<label for="type">Фамилия и имя</label>
									<input type="text" name="name" id="name" data-rule-required="true">
								</div>
								<div class="field">
									<label for="tel">Телефон</label>
									<input type="tel"  class="masked">
								</div>
								
								<div class="field">
									<label for="email">E-mail</label>
									<input type="email">
								</div>
								<div class="field">
									<label for="text">Сопроводительное письмо</label>
									<textarea name="text" id="text"></textarea>
								</div>
								<div class="field">
									<label for="add-file" class="input-btn">Приложить резюме</label>

									<input type="file" id="add-file" name="add-file" class="add-file hide" accept="application/pdf,application/msword,text/plain">
									<small class="place_file"></small>
									<small>Вы можете приложить pdf, doc, docx или txt размером не более 4Mb</small>
								</div>
								<input type="submit" value="Оставить заявку">
							</form>
						</div>							
					</div>
					/-->


				</div>				
			</li>
		<?endforeach;?>
	</ul>
<?endif; ?>
<?
#DebugMessage($arResult['ITEMS']);
?>