jQuery(document).ready(function(){
	jQuery('.add').click(function() {
		jQuery('.comment_to_comment').remove();
		jQuery(this).parent().after('<form class="comment_to_comment" name="add_comment" method="post"><textarea maxlength="300" id="textarea-2" name="comment"></textarea><input type="hidden" name="comment_id" value="'+jQuery(this).attr('id')+'"/><input type="submit" value="Отправить" name="add_comment" /><a href="#" class="cancel">Закрыть</a></form>');
		if (jQuery('input[name="wysiwyg"]').val() == 'y'){
			jQuery('#textarea-2').cleditor();
		}
		jQuery('.comment_to_comment .cancel').click(function(){
			jQuery(this).parent().remove();
			return false;
		});
		return false;
	})
	jQuery('.edit').click(function() {
		var comment = jQuery(this).parent().siblings('.comment_text').text();
		jQuery(this).parent().siblings('.comment_text').remove();
		jQuery(this).parent().siblings('.author').after('<form class="edit_form" name="edit" method="post"><textarea maxlength="300" id="textarea-2" name="comment">'+comment+'</textarea><input type="hidden" name="comment_id" value="'+jQuery(this).attr('id')+'"/><input type="submit" name="edit" value="Сохранить" /><a href="#" class="cancel">Отменить</a></form>');
		if (jQuery('input[name="wysiwyg"]').val() == 'y') {
			jQuery('#textarea-2').cleditor();
		}
		jQuery('.edit_form .cancel').click(function(){
			jQuery(this).parent().siblings('.author').after('<div class="comment_text">'+comment+'</div>');
			jQuery(this).parent().remove();
			return false;
		});
		return false;
	});
	jQuery('.bx-rating-absolute a').click(function(){
		jQuery.post(
			"/bitrix/components/kontora/comments.list/ajax_action.php",
			{'voting': jQuery('input[name="cacheId"]').val()}
		);
	});
});