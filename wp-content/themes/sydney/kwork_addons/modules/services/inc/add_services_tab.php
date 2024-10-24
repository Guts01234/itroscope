<?php

/**
 * Добавление нового таба услуги в меню профиля
 *
 * @param array $tabs
 * @return array


 */
function um_services_add_tab($tabs)
{
	// Добавляем новую вкладку

	$tabs['services_tab'] = array(
		'name'   => 'Услуги',
		'icon'   => 'um-faicon-pencil',
		'custom' => true,
	);

    UM()->options()->options[ 'profile_tab_' . 'services_tab' ] = true;

	// Перемещаем новую вкладку в начало массива
	$new_tabs_order = array('services_tab' => $tabs['services_tab']) + $tabs;

	return $new_tabs_order;
}


add_filter('um_profile_tabs', 'um_services_add_tab', 100);
/**
 * Render tab content
 *
 * @param array $args
 */
function um_services_tab_content($args)
{
	echo do_shortcode(
		'[wpuf_form id="1123"]'
	);
}
add_action('um_profile_content_services_tab_default', 'um_services_tab_content');