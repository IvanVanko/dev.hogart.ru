<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/**
 * @var array $arParams
 * @var array $arResult
 */
?>

<div class="bx-auth">

<?

if (!empty($arParams["~AUTH_RESULT"])) {
	switch ($arParams["~AUTH_RESULT"]['TYPE']) {
		case 'ERROR':
			new \Hogart\Lk\Helper\Template\FlashError($arParams["~AUTH_RESULT"]['MESSAGE']);
			break;
		case 'OK':
			new \Hogart\Lk\Helper\Template\FlashSuccess($arParams["~AUTH_RESULT"]['MESSAGE']);
			LocalRedirect("/account/");
			break;
	}
}

\Bitrix\Main\Page\Asset::getInstance()->addJs('//cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.11.5/validator.min.js', true);
\Bitrix\Main\Page\Asset::getInstance()->addCss('//cdnjs.cloudflare.com/ajax/libs/jasny-bootstrap/3.1.3/css/jasny-bootstrap.min.css', true);
\Bitrix\Main\Page\Asset::getInstance()->addJs('//cdnjs.cloudflare.com/ajax/libs/jasny-bootstrap/3.1.3/js/jasny-bootstrap.min.js', true);

?>

<div class="hogart-auth">
	<div class="auth-form">
		<div class="row">
			<div class="col-sm-12 text-center">
				<div class="bx-auth-note">
					<h3><?= GetMessage("AUTH_CHANGE_PASSWORD")?></h3>
				</div>
			</div>
			<div class="col-xs-12 col-sm-4 col-sm-offset-4">
				<form data-toggle="validator" name="form_auth" method="post" target="_top" action="<?=$arResult["AUTH_URL"]?>">
					<?if (strlen($arResult["BACKURL"]) > 0): ?>
						<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
					<? endif ?>
					<input type="hidden" name="AUTH_FORM" value="Y">
					<input type="hidden" name="TYPE" value="CHANGE_PWD">
					<input type="hidden" name="USER_CHECKWORD" maxlength="50" value="<?=$arResult["USER_CHECKWORD"]?>" />
					<input type="hidden" name="USER_LOGIN" maxlength="50" value="<?= $arResult["LAST_LOGIN"] ?>" />

					<div class="form-group">
						<label class="control-label">E-mail</label>
						<div class="input-group">
							<input disabled="disabled" name="USER_LOGIN_FAKE" maxlength="255" value="<?= $arResult["LAST_LOGIN"] ?>" type="text" class="form-control" placeholder="E-mail" data-error="Поле не должно быть пустым">
							<span class="input-group-addon"><i class="fa fa-user color-black"></i></span>
						</div>
						<div class="help-block with-errors"></div>
					</div>
					<div class="form-group">
						<label class="control-label"><?= GetMessage("AUTH_NEW_PASSWORD_REQ") ?></label>
						<div class="input-group">
							<input id="password" name="USER_PASSWORD" required="required" maxlength="255" autocomplete="off" type="password" class="form-control" value="<?=$arResult["USER_PASSWORD"]?>" placeholder="<?= GetMessage("AUTH_PASSWORD") ?>" data-error="Поле не должно быть пустым">
							<span class="input-group-addon"><i class="fa fa-lock color-black"></i></span>
						</div>
						<div class="help-block with-errors"></div>
					</div>
					<div class="form-group">
						<label class="control-label"><?= GetMessage("AUTH_NEW_PASSWORD_CONFIRM") ?></label>
						<div class="input-group">
							<input data-match="#password" type="password" name="USER_CONFIRM_PASSWORD" maxlength="50" value="<?=$arResult["USER_CONFIRM_PASSWORD"]?>" class="form-control" autocomplete="off" required data-error="Пароли должны совпадать" />
							<span class="input-group-addon"><i class="fa fa-lock color-black"></i></span>
						</div>
						<div class="help-block with-errors"></div>
					</div>

					<div class="form-group text-center">
						<button type="submit" name="change_pwd" class="btn btn-primary">
							<?=GetMessage("AUTH_CHANGE")?>
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
document.bform.USER_LOGIN.focus();
</script>
</div>