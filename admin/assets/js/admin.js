(function ( $ ) {
	"use strict";

	$(function () {

	/* Colorpicker setting field handler	*/
	$('.field-colorpicker').wpColorPicker();
	$('.wp-picker-holder').click(function( event ) { event.preventDefault();})
	/* END Colorpicker setting field handler	*/
	
	/* Image upload setting field handler	*/
	$(document).on('click','.upload-image-button',function() {
		var target_field=$(this).closest('.field-upload-image-wrapper').children('.field-upload-image');
		var target_field_preview=$(this).closest('.field-upload-image-wrapper').children('.field-upload-image-preview');
		window.send_to_editor = function(html) {	
			var image_url = $('img',html).attr('src');
			$(target_field).val(image_url);
			$('img',target_field_preview).attr('src',image_url);
			window.send_to_editor=window.original_send_to_editor;
			tb_remove();
		}	
		tb_show('Image Upload', 'media-upload.php?referer='+wpbi_plugin_vars.plugin_slug+'-settings-image&amp;type=image&amp;TB_iframe=true&amp;post_id=0', false);
		return false;
	});		
	/* END Image upload setting field handler	*/
	
	});

}(jQuery));