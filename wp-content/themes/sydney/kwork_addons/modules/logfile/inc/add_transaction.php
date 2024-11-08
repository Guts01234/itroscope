<?php

/**
 * @param = $descr - название поста транзакции
 * @param = $price - сумма транзакции
 * @param = $user_id - айди пользователя, по умолчанию айди пользовател, который создал транзакцию
 */

function log_transaction($name, $price, $user_id = null)
{
	if (!$user_id) {
		$user_id = get_current_user_id();
	}
	$new_transaction = array(
		'post_title'    => $name,
		'post_status'   => 'publish',
		'post_author'   => $user_id,
		'post_type'     => 'transaction',
	);
	// получаем айди нашего новосозданной транзакции
	$new_transaction_id = wp_insert_post($new_transaction);

	update_post_meta($new_transaction_id, 'transaction_price', $price);
}
