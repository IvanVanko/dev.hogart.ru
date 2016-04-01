<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$frame = $this->createFrame("subscribe-form", false)->begin();?>
<?
global $USER;
$user_mail = $USER->GetEmail();
?>
<div class="main-filter news-filter">
	<div class="btn show-subscribe open-next">Подписка</div>
	<form action="<?=$arResult["FORM_ACTION"]?>" class="main-filter-form open-block hidden_block">
		<div class="filter-block">
			<a href="#" class="input-btn gray-btn unsubscribe">отписаться</a>
		</div>
		
		<div class="filter-block" id="subs-box">
			<?$cnt=0;?>
			<?foreach($arResult["RUBRICS"] as $itemID => $itemValue):?>
				<?
				if ($cnt == 0) $all = " all"; else $all = "";
				?>
				<input type="checkbox" id="s_<?=$itemValue["ID"]?>" name="RUB_ID[]" value="<?=$itemValue["ID"]?>"<?if($itemValue["CHECKED"]) echo " checked"?> class="custom_checkbox <?=$all?>" />
				<label for="s_<?=$itemValue["ID"]?>"><?=$itemValue["NAME"]?></label>

			<!--<input type="checkbox" id="checkbox_subs_1" name="checkbox_subs_1" class="custom_checkbox" checked>
			<label for="checkbox_subs_1">Новости о компании</label>
			<input type="checkbox" id="checkbox_subs_2" name="checkbox_subs_2" class="custom_checkbox">
			<label for="checkbox_subs_2">Акции</label>
			<input type="checkbox" id="checkbox_subs_3" name="checkbox_subs_3" class="custom_checkbox">
			<label for="checkbox_subs_3">Обучение</label>
			<input type="checkbox" id="checkbox_subs_4" name="checkbox_subs_4" class="custom_checkbox">
			<label for="checkbox_subs_4">Мероприятия</label>/-->
			<?$cnt++;?>
			<?endforeach;?>
		</div>
	
		<div class="filter-block">
			<!--<label>sms уведомления</label>
			<input type="tel" class="masked">/-->
			<label>E-mail</label>
			<input name="sf_EMAIL" value="<?=($USER->IsAuthorized())?$user_mail:$arResult["EMAIL"]?>" type="text" placeholder="Введите ваш e-mail">
			
		</div>
		<!--<input type="submit" class="input-btn gray-btn" value="подписаться">/-->
		<button name="OK" class="empty-btn input-btn gray-btn">Подписаться</button>
	</form>
</div>


<?$frame->beginStub();?>

<div class="main-filter news-filter">
	<div class="btn show-subscribe open-next">Подписка</div>
	<form action="<?=$arResult["FORM_ACTION"]?>" class="main-filter-form open-block hidden_block">
		<div class="filter-block">
			<a href="#" class="input-btn gray-btn unsubscribe">отписаться</a>
		</div>
		
		<div class="filter-block" id="subs-box">
			<?$cnt=0;?>
			<?foreach($arResult["RUBRICS"] as $itemID => $itemValue):?>
				<?
				if ($cnt == 0) $all = " .all"; else $all = "";
				?>
				<input type="checkbox" id="s_<?=$itemValue["ID"]?>" name="RUB_ID[]" value="<?=$itemValue["ID"]?>"<?if($itemValue["CHECKED"]) echo " checked"?> class="custom_checkbox <?=$all?>" />
				<label for="s_<?=$itemValue["ID"]?>"><?=$itemValue["NAME"]?></label>

			<!--<input type="checkbox" id="checkbox_subs_1" name="checkbox_subs_1" class="custom_checkbox" checked>
			<label for="checkbox_subs_1">Новости о компании</label>
			<input type="checkbox" id="checkbox_subs_2" name="checkbox_subs_2" class="custom_checkbox">
			<label for="checkbox_subs_2">Акции</label>
			<input type="checkbox" id="checkbox_subs_3" name="checkbox_subs_3" class="custom_checkbox">
			<label for="checkbox_subs_3">Обучение</label>
			<input type="checkbox" id="checkbox_subs_4" name="checkbox_subs_4" class="custom_checkbox">
			<label for="checkbox_subs_4">Мероприятия</label>/-->
			<?$cnt++;?>
			<?endforeach;?>
		</div>
	
		<div class="filter-block">
			<!--<label>sms уведомления</label>
			<input type="tel" class="masked">/-->
			<label>E-mail</label>
			<input name="sf_EMAIL" value="<?=($USER->IsAuthorized())?$user_mail:$arResult["EMAIL"]?>" type="text" placeholder="Введите ваш e-mail">
			
		</div>
		<!--<input type="submit" class="input-btn gray-btn" value="подписаться">/-->
		<button name="OK" class="empty-btn input-btn gray-btn">Подписаться</button>
	</form>
</div>

<?$frame->end();?>

<script type="text/javascript">

    $(document).ready(function(){
        $('#subs-box .all').click(function () {
            if ($(this).children('input').is(':checked')){
                $('#subs-box .field.custom_checkbox').each(function () {
                    if(!$(this).hasClass('all')){
                        $(this).children('input').prop({"checked": true});
                    }
                });
            } else {
                $('#subs-box .field.custom_checkbox').each(function () {
                    if(!$(this).hasClass('all')){
                        $(this).children('input').prop({"checked": false});
                    }
                });
            }
        });
        $('#subs-box .field.custom_checkbox').click(function () {
            if (!$(this).hasClass('all')) {
                if ($('#subs-box .field.custom_checkbox.all input').is(':checked')) {
                    console.log('+');
                    $('#subs-box .field.custom_checkbox.all input').prop({"checked": false});
                } else {
                    console.log('-');
                }
                console.clear();
                console.log($('#subs-box .field.custom_checkbox').find('input').filter(':checked').length);

                if ($('#subs-box .field.custom_checkbox').find('input').filter(':checked').length ==($('#subs-box .field.custom_checkbox').length-1)){
                    $('#subs-box .field.custom_checkbox.all input').prop({"checked": true});
                }
                /*$('#subs-box .field.custom_checkbox').each(function () {
                    if(!$(this).hasClass('all')){
                        $(this).children('input').filter()
                    }
                });*/
            }
        });
    });

</script>