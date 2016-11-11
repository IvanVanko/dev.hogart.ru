<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?><?

if (!empty($arParams["~AUTH_RESULT"]) && $arParams["~AUTH_RESULT"]['TYPE'] == 'ERROR') {
	new \Hogart\Lk\Helper\Template\FlashError($arParams["~AUTH_RESULT"]['MESSAGE']);
}
if (!empty($arParams["~AUTH_RESULT"]) && $arParams["~AUTH_RESULT"]['TYPE'] == 'OK') {
	new \Hogart\Lk\Helper\Template\FlashSuccess($arParams["~AUTH_RESULT"]['MESSAGE'], null, 0);
}


?>


<div class="hogart-auth">
	<div class="auth-form">
		<div class="row">
			<div class="col-sm-12 text-center">
				<div class="bx-auth-note">
					<p>
						<?=GetMessage("AUTH_FORGOT_PASSWORD_1")?>
					</p>
				</div>
			</div>
			<div class="col-xs-12 col-sm-4 col-sm-offset-4">
				<form data-toggle="validator" name="form_auth" method="post" target="_top" action="<?=$arResult["AUTH_URL"]?>">
					<?if (strlen($arResult["BACKURL"]) > 0): ?>
						<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
					<? endif ?>
					<input type="hidden" name="AUTH_FORM" value="Y">
					<input type="hidden" name="TYPE" value="SEND_PWD">

					<div class="form-group">
						<label class="control-label"><?=GetMessage("AUTH_LOGIN")?> <?=GetMessage("AUTH_OR")?> <?=GetMessage("AUTH_EMAIL")?></label>
						<div class="input-group">
							<input required maxlength="255" name="USER_EMAIL" type="text" class="form-control" placeholder="<?=GetMessage("AUTH_LOGIN")?> <?=GetMessage("AUTH_OR")?> <?=GetMessage("AUTH_EMAIL")?>" data-error="Поле не должно быть пустым">
							<span class="input-group-addon"><i class="fa fa-user color-black"></i></span>
						</div>
						<div class="help-block with-errors"></div>
					</div>


					<div class="form-group text-center">
						<button type="submit" name="change_pwd" class="btn btn-primary">
							<?=GetMessage("AUTH_SEND")?>
						</button>
					</div>

					<ul class="list-inline text-center">
						<li>
							<?if ($arParams["NOT_SHOW_LINKS"] != "Y"):?>
								<noindex>
									<a href="<?=$arResult["AUTH_AUTH_URL"]?>" rel="nofollow"><?=GetMessage("AUTH_AUTH")?></a>
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
document.bform.USER_LOGIN.focus();
</script>
