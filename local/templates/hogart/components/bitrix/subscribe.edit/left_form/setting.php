﻿<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?
//***********************************
//setting section
//***********************************
?>
<script type="text/javascript">
    $(document).ready(function () {
        $('#subs-box .all').click(function () {
            if ($(this).children('input').is(':checked')) {
                $('#subs-box .field.custom_checkbox').each(function () {
                    if (!$(this).hasClass('all')) {
                        $(this).children('input').prop({"checked": true});
                    }
                });
            } else {
                $('#subs-box .field.custom_checkbox').each(function () {
                    if (!$(this).hasClass('all')) {
                        $(this).children('input').prop({"checked": false});
                    }
                });
            }
        });
        $('#subs-box .field.custom_checkbox').click(function () {
            if (!$(this).hasClass('all')) {
                if ($('#subs-box .field.custom_checkbox.all input').is(':checked')) {
                    $('#subs-box .field.custom_checkbox.all input').prop({"checked": false});
                } else {
                }
                if ($('#subs-box .field.custom_checkbox').find('input').filter(':checked').length == ($('#subs-box .field.custom_checkbox').length - 1)) {
                    $('#subs-box .field.custom_checkbox.all input').prop({"checked": true});
                }
            }
        });
    });

	function form_subscribe() {
 	  var msg   = $('#form_subscribe').serialize();
        $.ajax({
          type: 'POST',
          url: '/ajax/send_form_subscribe.php',
		  dataType : "json",
          data: msg,
          success: function(data) {
			$("#message_err").html("");

			if (typeof data.error !== 'undefined') {
				if (data.error.length == 0) {
					$("#form_subscribe")[0].reset();
					setTimeout(function () {
						$("#message_suc").html("");
						$(".close").click();
					}, 500);                	
				}
			}
			
			$.each(data.error, function(index, value) {
				$("#message_err").append(value + "<br>");
			});

			$("#message_suc").append(data.success);

//			  console.log();

          }    
        });
 
    }
</script>
<div class="js-validation-form">

	<div style="color:red;" id="message_err"></div>
	<div style="color:green;" id="message_suc"></div>
    <form class="form__subscribe-news" id = "form_subscribe" action="javascript:void(null);" onsubmit="form_subscribe()" method="post">
        <? echo bitrix_sessid_post(); ?>
        <div class="form__group field js-validation-empty">
            <label for="EMAIL">E-mail <font color="red"><span
                        class="form-required starrequired">*</span></font></label>
            <input placeholder="<?= GetMessage("subscr_auth_email") ?>" type="text" name="EMAIL"
                   value=""/>
        </div>
        <div class="form__group form__group--right field custom_label phone js-validation-phone">
            <label for="PHONE"><?= GetMessage("Телефон") ?> <font color="red"><span
                        class="form-required starrequired">*</span></font></label>
            <input id="PHONE" placeholder="Введите ваш e-mail" type="text" name="entity[UF_SUBSCRIBER_PHONE]"
                   value=""/>
        </div>
        <div class="form__group field">
            <label for="subscribe-news-name">Имя:</label>
            <input placeholder="Ваше имя" type="text" name="subscribe-news-name" />
        </div>
        <div class="form__group form__group--right field custom_label">
            <label for="subscribe-news-subname">Фамилия:</label>
            <input id="subscribe-news-subname" placeholder="Введите вашу фамилию" type="text" name="subscribe-news-subname" />
        </div>
        <div class="form__title">Что бы получать необходимую информацию, укажите</div>
        <div class="form__title">Сфера деятельности</div>
        <div id="subs-box">
            <? 
			$RUBRICS = [
				["ID"=>1,"NAME"=>"торговля"],
				["ID"=>2,"NAME"=>"монтаж"],
				["ID"=>3,"NAME"=>"проектирование"],
				["ID"=>4,"NAME"=>"архитектура/дизайн"],
			];
			
			foreach ($RUBRICS as $itemID => $itemValue): ?>

                <div class="checkbox">
                    <label>
                        <input type="checkbox"
                               id="s_<?= $itemValue["ID"] ?>"
                               name="RUB_ID[]"
                               value="<?= $itemValue["NAME"] ?>"> <?= $itemValue["NAME"] ?>
                    </label>
                </div>
            <? endforeach; ?>
            <div class="checkbox checkbox--subscribe">
                <label>
                    <input class="form__checkbox-more" type="checkbox" id="subscribe-news-more" name="subscribe-news-more" value="5" /> Прочее
                    <input class="form__input-more" type="text" id="" name="other" value="" />
                </label>
            </div>
        </div>
        <div class="form__group-hide js-checkbox-hide" style="display: none;">
            <div class="form__title">Работаете с компанией Хогарт?</div>
            <div class="checkbox checkbox--subscribe">
                <label>
                    <input class="form__checkbox-more" type="checkbox" id="s_5 ?>" name="RUB_ID[]" value="5" /> Да
                    <div class="form__input-label">, мой менеджер
                        <input class="form__input-more form__input-more--another" type="text" id="" name="meneger_yes" value="" />
                    </div>
                </label>
            </div>
        </div>

        <input type="hidden" name="FORMAT" value="html"/>
        <br><br>
        <button name="Save" class="btn btn-primary"><?= GetMessage("subscr_add") ?></button>


        <input type="hidden" name="PostAction" value="<? echo($arResult["ID"] > 0 ? "Update" : "Add") ?>"/>
        <input type="hidden" name="ID" value="<? echo $arResult["SUBSCRIPTION"]["ID"]; ?>"/>
        <? if ($_REQUEST["register"] == "YES"): ?>
            <input type="hidden" name="register" value="YES"/>
        <? endif; ?>
        <? if ($_REQUEST["authorize"] == "YES"): ?>
            <input type="hidden" name="authorize" value="YES"/>
        <? endif; ?>
    </form>

</div>