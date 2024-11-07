<?php
add_action('wp_ajax_edit_meta_field_action', 'edit_meta_field_callback');
function edit_meta_field_callback()
{
	check_ajax_referer('edit_meta_field_nonce', 'security');

	if (isset($_POST['meta_field_value'])) {
		$value = sanitize_text_field($_POST['meta_field_value']);
		// Обновление мета-поля
		update_user_meta(get_current_user_id(), 'meta_field_key', $value);
		wp_send_json_success();
	} else {
		wp_send_json_error('Ошибка: Не удалось обновить мета-поле.');
	}

	wp_die();
}

?>
<form id="update_user_number_card">
	<input type="hidden" id="post_id" value="POST_ID_HERE">
	<input type="text" id="custom_field_input" placeholder="Введите свой номер карты">
	<button id="update_custom_field_button">Сохранить</button>
</form>

<script>
	jQuery(document).ready(function($) {
		$('#update_custom_field_button').on('click', function(e) {
			e.preventDefault();

			var fieldData = $('#custom_field_input').val();

			var data = {
				action: 'edit_meta_field_action',
				security: ajax_object.edit_meta_field_nonce,
				meta_field_value: fieldData
			};

			$.post(ajax_object.ajax_url, data, function(response) {
				if (response.success) {
					alert('Мета-поле успешно обновлено!');
				} else {
					alert('Ошибка: ' + response.data);
				}
			});
		});
	});
</script>