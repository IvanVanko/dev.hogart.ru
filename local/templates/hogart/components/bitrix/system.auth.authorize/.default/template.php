<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$APPLICATION->SetPageProperty("NOT_SHOW_NAV_CHAIN", "Y");

ShowMessage($arParams["~AUTH_RESULT"]);
ShowMessage($arResult['ERROR_MESSAGE']);
\Bitrix\Main\Page\Asset::getInstance()->addJs('//cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.11.5/validator.min.js', true);
\Bitrix\Main\Page\Asset::getInstance()->addCss('//cdnjs.cloudflare.com/ajax/libs/jasny-bootstrap/3.1.3/css/jasny-bootstrap.min.css', true);
\Bitrix\Main\Page\Asset::getInstance()->addJs('//cdnjs.cloudflare.com/ajax/libs/jasny-bootstrap/3.1.3/js/jasny-bootstrap.min.js', true);

?>

<div class="hogart-auth">
	<div class="auth-form">
		<div class="row">
			<div class="col-sm-12 text-center">
				<div class="bx-auth-note">
					<h3><?= GetMessage("AUTH_PLEASE_AUTH")?></h3>
				</div>
			</div>
			<div class="col-xs-12 col-sm-4 col-sm-offset-4">
				<form data-toggle="validator" name="form_auth" method="post" target="_top" action="<?=$arResult["AUTH_URL"]?>">
					<input type="hidden" name="AUTH_FORM" value="Y" />
					<input type="hidden" name="TYPE" value="AUTH" />
					<?if (strlen($arResult["BACKURL"]) > 0):?>
						<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
					<?endif?>
					<?foreach ($arResult["POST"] as $key => $value):?>
						<input type="hidden" name="<?=$key?>" value="<?=$value?>" />
					<?endforeach?>


					<div class="form-group">
						<label class="control-label">E-mail</label>
						<div class="input-group">
							<input name="USER_LOGIN" maxlength="255" value="<?=$arResult["LAST_LOGIN"]?>" required="required" type="text" class="form-control" placeholder="E-mail" data-error="Поле не должно быть пустым">
							<span class="input-group-addon"><i class="fa fa-user color-black"></i></span>
						</div>
						<div class="help-block with-errors"></div>
					</div>
					<div class="form-group">
						<label class="control-label"><?= GetMessage("AUTH_PASSWORD") ?></label>
						<div class="input-group">
							<input name="USER_PASSWORD" required="required" maxlength="255" autocomplete="off" type="password" class="form-control" placeholder="<?= GetMessage("AUTH_PASSWORD") ?>" data-error="Поле не должно быть пустым">
							<span class="input-group-addon"><i class="fa fa-lock color-black"></i></span>
						</div>
						<div class="help-block with-errors"></div>
					</div>
					<? if($arResult["CAPTCHA_CODE"]): ?>
						<input type="hidden" name="captcha_sid" value="<?echo $arResult["CAPTCHA_CODE"]?>" />
						<img src="/bitrix/tools/captcha.php?captcha_sid=<?echo $arResult["CAPTCHA_CODE"]?>" width="180" height="40" alt="CAPTCHA" />
						<label class="control-label"><?= GetMessage("AUTH_CAPTCHA_PROMT") ?></label>
						<div class="input-group">
							<input class="bx-auth-input" type="text" name="captcha_word" maxlength="50" value="" size="15" />
						</div>
					<? endif; ?>
					<?if ($arResult["STORE_PASSWORD"] == "Y"):?>
						<div class="form-group">
							<div class="checkbox">
								<label>
									<input id="USER_REMEMBER" name="USER_REMEMBER" value="Y" type="checkbox"> <?=GetMessage("AUTH_REMEMBER_ME")?>
								</label>
							</div>
						</div>
					<? endif; ?>
					<div class="form-group text-center">
						<button type="submit" name="Login" class="btn btn-primary">
							<?=GetMessage("AUTH_AUTHORIZE")?>
						</button>
					</div>

					<ul class="list-inline text-center">
						<li>
							<?if ($arParams["NOT_SHOW_LINKS"] != "Y"):?>
								<noindex>
									<a href="<?=$arResult["AUTH_FORGOT_PASSWORD_URL"]?>" rel="nofollow"><?=GetMessage("AUTH_FORGOT_PASSWORD_2")?></a>
								</noindex>
							<?endif?>
						</li>
					</ul>
				</form>
			</div>
		</div>
	</div>
</div>


<script type="text/javascript">
<?if (strlen($arResult["LAST_LOGIN"])>0):?>
try{document.form_auth.USER_PASSWORD.focus();}catch(e){}
<?else:?>
try{document.form_auth.USER_LOGIN.focus();}catch(e){}
<?endif?>
</script>

<?if($arResult["AUTH_SERVICES"]):?>
<?
$APPLICATION->IncludeComponent("bitrix:socserv.auth.form", "",
	array(
		"AUTH_SERVICES" => $arResult["AUTH_SERVICES"],
		"CURRENT_SERVICE" => $arResult["CURRENT_SERVICE"],
		"AUTH_URL" => $arResult["AUTH_URL"],
		"POST" => $arResult["POST"],
		"SHOW_TITLES" => $arResult["FOR_INTRANET"]?'N':'Y',
		"FOR_SPLIT" => $arResult["FOR_INTRANET"]?'Y':'N',
		"AUTH_LINE" => $arResult["FOR_INTRANET"]?'N':'Y',
	),
	$component,
	array("HIDE_ICONS"=>"Y")
);
?>
<?endif?>
