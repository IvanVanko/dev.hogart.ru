<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<form name="add_comment" method="post">
	<textarea maxlength="300" id="textarea-1" name="comment"><?=$_REQUEST['comment']?></textarea>
	<div id="textareaFeedback"></div>
	<?if ($arResult['USE_CAPTHCA'] == 'Y'):?>
		<input type="hidden" name="captcha_sid" value="<?=$arResult["CAPTCHA_CODE"]?>" />
		<br /><img src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult["CAPTCHA_CODE"]?>" width="180" height="40" alt="CAPTCHA" />
		<br /><?=GetMessage('CAPTHCA');?>
		<input type="text" name="captcha_word" maxlength="50" value="">
	<?endif?>
	<br /><input type="submit" value="<?=GetMessage('BUTTON_SEND')?>" name="add_comment" />
</form>