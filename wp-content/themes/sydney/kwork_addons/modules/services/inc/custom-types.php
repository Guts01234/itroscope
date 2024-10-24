<?php

add_action('init', 'register_services_type');

function register_services_type()
{
	register_post_type('services', [
		'label'  => 'service',
		'labels' => [
			'name'               => 'услуги', // основноёе название для типа записи
			'singular_name'      => 'услуга', // название для одной записи этого типа
			'add_new'            => 'Добавить услугу', // для добавления новой записи
			'add_new_item'       => 'Добавление услуги', // заголовка у вновь создаваемой записи в админ-панели.
			'edit_item'          => 'Редактирование услуги', // для редактирования типа записи
			'new_item'           => 'Новая услуга', // текст новой записи
			'view_item'          => 'Смотреть услугу', // для просмотра записи этого типа.
			'search_items'       => 'Искать услуги', // для поиска по этим типам записи
			'not_found'          => 'Не найдено', // если в результате поиска ничего не было найдено
			'not_found_in_trash' => 'Не найдено в корзине', // если не было найдено в корзине
			'menu_name'          => 'Услуги', // название меню
		],
		'description'            => 'услуга',
		'public'                 => true,
		'show_in_menu'           => true, // показывать ли в меню админки
		'show_in_rest'        => true, // добавить в REST API. C WP 4.7
		'menu_position'       => 6,
		'menu_icon'           => 'dashicons-welcome-add-page',
		'hierarchical'        => false,
		'supports'            => ['title', 'editor', 'author', 'custom-fields', 'thumbnail'], // 'title','editor','author','thumbnail','excerpt','trackbacks','custom-fields','comments','revisions','page-attributes','post-formats'
		'taxonomies'          => ['sfera', 'navik', 'problem'],

	]);
}
