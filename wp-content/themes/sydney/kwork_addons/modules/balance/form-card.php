<?php
// Обработчик для обновления метаполя
add_action('wp_ajax_update_custom_field', 'update_custom_field_callback');
add_action('wp_ajax_nopriv_update_custom_field', 'update_custom_field_callback');
function update_custom_field_callback()
{
	// Проверяем безопасность nonce
	check_ajax_referer('update_custom_field_nonce', 'security');

	$value = sanitize_text_field($_POST['custom_field_value']);
	$post_id = (isset($_POST['post_id'])) ? intval($_POST['post_id']) : 0;
	$user_id = get_current_user_id();

	if ($post_id > 0) {
		update_user_number_card($user_id, $value);
		wp_send_json_success('Метаполе успешно обновлено!');
	} else {
		wp_send_json_error('Ошибка при обновлении метаполя!');
	}

	wp_die();
}

?>
<form id="update_custom_field_form">
	<input type="hidden" id="post_id" value="POST_ID_HERE">
	<input type="text" id="custom_field_input" placeholder="Введите свой номер карты">
	<button id="update_custom_field_button">Сохранить</button>
</form>

<script>
	jQuery(document).ready(function($) {
		$('#update_custom_field_button').on('click', function(e) {
			e.preventDefault();

			var data = {
				action: 'update_custom_field',
				security: ajax_object.update_custom_field_nonce,
				custom_field_value: $('#custom_field_input').val(),
				// post_id: $('#post_id').val()
			};

			$.post(ajax_object.ajax_url, data, function(response) {
				if (response.success) {
					alert('Метаполе успешно обновлено!');
				} else {
					alert('Ошибка: ' + response.data);
				}
			});
		});
	});
</script>