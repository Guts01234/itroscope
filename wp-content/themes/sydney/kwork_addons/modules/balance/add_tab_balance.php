<?php

/**
 * Добавление нового таба для работы с балансом
 *
 * @param array $tabs
 * @return array

 */
function um_balance_add_tab($tabs)
{
	// Добавляем новую вкладку

	$tabs['balance_tab'] = array(
		'name'   => 'Баланс',
		'icon'   => 'um-faicon-money',
		'custom' => true,
	);

	UM()->options()->options['profile_tab_' . 'balance_tab'] = true;

	return $tabs;
}


add_filter('um_profile_tabs', 'um_balance_add_tab', 10000);

add_action('um_profile_content_balance_tab', 'um_custom_tab_content_function');

function um_custom_tab_content_function($args)
{
	// Ваш контент для вкладки
	echo '<h4>Текущий баланс: ' . get_user_balance(get_current_user_id()) . ' руб</h4>';

	if (!get_user_number_card(get_current_user_id()) != '') {
		echo '<h4>Номер Вашей карты: ' . get_user_number_card(get_current_user_id()) . '</h4>';
	} else {
		get_template_part('kwork_addons/modules/balance/form', 'card');
	}
	var_dump(get_user_number_card(get_current_user_id()));

	echo '<h3>Транзакции</h3>';
	echo '<p>Здесь будут все получения / выводы средств </p>';
}
