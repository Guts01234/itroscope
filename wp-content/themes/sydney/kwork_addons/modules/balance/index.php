<?php

/**
 * Модуль баланса
 */
require_once __DIR__ . '/inc/user_meta.php';
require_once __DIR__ . '/inc/add_tab_balance.php';

function my_enqueue_scripts()
{
	wp_enqueue_script('my_custom_script', get_template_directory_uri() . '/js/custom-script.js', array('jquery'), '1.0', true);
	wp_localize_script('my_custom_script', 'ajax_object', array(
		'ajax_url' => admin_url('admin-ajax.php'),
		'edit_meta_field_nonce' => wp_create_nonce('edit_meta_field_nonce')
	));
}
add_action('wp_enqueue_scripts', 'my_enqueue_scripts');
