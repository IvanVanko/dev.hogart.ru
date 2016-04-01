<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

    <form id="comment-form" name="add_comment" method="post">
        <div class="row">
            <!--<div class="col3">
                <div class="field custom_label">
                    <label>Имя</label>
                    <input type="text"/>
                </div>
            </div>
            <div class="col3">
                <div class="field custom_label">
                    <label>Компания</label>
                    <input type="text"/>
                </div>
            </div>
            <div class="col3">
                <div class="field custom_label">
                    <label>должность</label>
                    <input type="text"/>
                </div>
            </div>-->
        </div>
        <div class="field custom_label">
            <label>Отзыв</label>
            <textarea id="textarea-<?=$_REQUEST["CID"];?>"  name="comment"><?=$_REQUEST['comment']?></textarea>
        </div>
        <div class="text-right for-comm-button">
            <div class="b-box " data-popup="#comm-ok"></div>
            <div class="b js-popup-open " data-popup="#comm-ok"></div>
            <button class="empty-btn black" type="submit" value="<?=GetMessage('BUTTON_SEND')?>" name="add_comment">Отправить отзыв</button>
            <!--        <input class="empty-btn black js-popup-open" data-popup="#comm-ok" type="submit" value="Отправить отзыв" name="add_comment"/>-->
        </div>
        <!--<div>111</div>
	<textarea maxlength="300" id="textarea-1" name="comment"><?/*=$_REQUEST['comment']*/?></textarea>
	<div id="textareaFeedback"></div>
	<?/*if ($arResult['USE_CAPTHCA'] == 'Y'):*/?>
		<input type="hidden" name="captcha_sid" value="<?/*=$arResult["CAPTCHA_CODE"]*/?>" />
		<br /><img src="/bitrix/tools/captcha.php?captcha_sid=<?/*=$arResult["CAPTCHA_CODE"]*/?>" width="180" height="40" alt="CAPTCHA" />
		<br /><?/*=GetMessage('CAPTHCA');*/?>
		<input type="text" name="captcha_word" maxlength="50" value="">
	<?/*endif*/?>
	<br /><input type="submit" value="<?/*=GetMessage('BUTTON_SEND')*/?>" name="add_comment" />-->
    </form>

