<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
//***********************************
//setting section
//***********************************
?>
<?
?>
<div class="main-filter news-filter">
	<div class="btn show-subscribe open-next">Подписка</div>
	<form action="<?=$arResult["FORM_ACTION"]?>" method="post" class="main-filter-form subs open-block hidden_block">
		<?echo bitrix_sessid_post();?>
		<?if (strlen($arResult["SUBSCRIPTION"]["ID"]) > 0):?>

		<?if (count($arResult["MESSAGE"]) > 0):?>
		<div class="filter-block">
			<?
			foreach($arResult["MESSAGE"] as $itemID=>$itemValue)
			echo ShowMessage(array("MESSAGE"=>$itemValue, "TYPE"=>"OK"));
			?>
		</div>
		<?endif;?>
		<?if (count($arResult["ERROR"]) > 0):?>
		<div class="filter-block">
			<?
			foreach($arResult["ERROR"] as $itemID=>$itemValue)
			echo ShowMessage(array("MESSAGE"=>$itemValue, "TYPE"=>"ERROR"));
			?>
		</div>
		<?endif;?>
		<div class="filter-block">
			<?
			$SUB_ID = $arResult["SUBSCRIPTION"]["ID"];
			$URL = $APPLICATION->GetCurPageParam("action=unsubscribe&ID=".$SUB_ID, array("ID","action"), false);
			?>
			<a href="<?=$URL?>" class="input-btn gray-btn unsubscribe">отписаться</a>
		</div>
		<?endif;?>
		
		<div class="filter-block" id="subs-box">
			<?$cnt=0;?>
			<?foreach($arResult["RUBRICS"] as $itemID => $itemValue):?>
			 <span class="field custom_checkbox <?=($itemValue["NAME"]=='Все')?'all':'' ?>" style="display:inline;">
						<input id="s_<?= $itemValue["ID"] ?>" type="checkbox" name="RUB_ID[]"
						   value="<?= $itemValue["ID"] ?>"<? if ($itemValue["CHECKED"]) echo " checked" ?> class="custom_checkbox" />
						<label for="s_<?= $itemValue["ID"] ?>"><?= $itemValue["NAME"] ?></label>
			   </span>
			<?$cnt++;?>
			<?endforeach;?>
		</div>
	
		<div class="filter-block">
			<label>sms уведомления</label>
			<input id="PHONE" type="text" name="entity[UF_SUBSCRIBER_PHONE]" value="<?=$arResult["SUBSCRIPTION"]["PHONE"]!=""?$arResult["SUBSCRIPTION"]["PHONE"]:$arResult["REQUEST"]["PHONE"];?>" />
			<input type="hidden" id="sms" value="Y" />
			<label>E-mail</label>
			<input placeholder="Введите ваш e-mail" type="text" name="EMAIL" value="<?=$arResult["SUBSCRIPTION"]["EMAIL"]!=""?$arResult["SUBSCRIPTION"]["EMAIL"]:$arResult["REQUEST"]["EMAIL"];?>" />
		</div>
	<input type="hidden" name="PostAction" value="<?echo ($arResult["ID"]>0? "Update":"Add")?>" />
	<input type="hidden" name="ID" value="<?echo $arResult["SUBSCRIPTION"]["ID"];?>" />
	<?if($_REQUEST["register"] == "YES"):?>
		<input type="hidden" name="register" value="YES" />
	<?endif;?>
	<?if($_REQUEST["authorize"]=="YES"):?>
		<input type="hidden" name="authorize" value="YES" />
	<?endif;?>        
		<!--<button type"button" name="Save" class="empty-btn input-btn gray-btn">Подписаться</button>/-->
		<input  name="Save" type="submit" class="empty-btn input-btn gray-btn" value="подписаться">
	</form>
</div>
<script type="text/javascript">
	$(document).ready(function(){


		<?
		if (count($arResult["MESSAGE"]) > 0 || count($arResult["ERROR"]) > 0)
		{
			?>
			$('.show-subscribe').addClass("opened");
			$('.subs').addClass("opened");
			<?

		}

		?>

$('#PHONE').inputmask("+7 (999)999-99-99");

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