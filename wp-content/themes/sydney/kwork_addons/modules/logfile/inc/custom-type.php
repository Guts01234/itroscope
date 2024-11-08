<?php

add_action('init', 'register_transaction_type');
add_action('init', 'register_zayavka_type');

// создание кастом пост транзакция 
function register_transaction_type()
{
	register_post_type('transaction', [
		'label'  => 'transaction',
		'labels' => [
			'name'               => 'транзакции', // основноёе название для типа записи
			'singular_name'      => 'транзакция', // название для одной записи этого типа
			'add_new'            => 'Добавить транзакцию', // для добавления новой записи
			'add_new_item'       => 'Добавление транзакции', // заголовка у вновь создаваемой записи в админ-панели.
			'edit_item'          => 'Редактирование транзакции', // для редактирования типа записи
			'new_item'           => 'Новая транзакция', // текст новой записи
			'view_item'          => 'Смотреть транзакцию', // для просмотра записи этого типа.
			'search_items'       => 'Искать транзакции', // для поиска по этим типам записи
			'not_found'          => 'Не найдено', // если в результате поиска ничего не было найдено
			'not_found_in_trash' => 'Не найдено в корзине', // если не было найдено в корзине
			'menu_name'          => 'транзакции', // название меню
		],
		'description'            => 'транзакция',
		'public'                 => true,
		'publicly_queryable'  => false,
		'exclude_from_search' => true,
		'show_ui'             => true,
		'show_in_nav_menus'   => false,
		'show_in_menu'           => true,
		'menu_position'       => 7,
		'menu_icon'           => 'dashicons-money-alt',
		'hierarchical'        => false,
		'has_archive'         => true,
		'supports'            => ['title', 'author'], // 'title','editor','author','thumbnail','excerpt','trackbacks','custom-fields','comments','revisions','page-attributes','post-formats'
		// 'taxonomies'          => ['sfera', 'navik', 'problem'],
	]);
}

// создание метабокса для транзакции 
function add_metabox_price()
{
	add_meta_box(
		'meta_transaction_price',
		'Метабокс для транзакций',
		'metabox_transaction_html',
		'transaction',
		'normal',
		'default',
	);
}

function metabox_transaction_html($post)
{
	$price = get_post_meta($post->ID, 'transaction_price', true);

	echo '
	<p>	
		<label for="transaction_price">Сумма</label>
			<input name= "transaction_price" id="transaction_price" type="text" value = " ' . esc_html($price) . ' " >
	</p>';
}

add_action('add_meta_boxes', 'add_metabox_price');
add_action('add_meta_boxes', 'add_metabox_zayavka');

// создание кастом пост вывод средств
function register_zayavka_type()
{
	register_post_type('zayavka', [
		'label'  => 'zayavka',
		'labels' => [
			'name'               => 'заявка на вывод', // основноёе название для типа записи
			'singular_name'      => 'заявка', // название для одной записи этого типа
			'add_new'            => 'Добавить заявку', // для добавления новой записи
			'add_new_item'       => 'Добавление заявки на вывод', // заголовка у вновь создаваемой записи в админ-панели.
			'edit_item'          => 'Редактирование заявки на вывод', // для редактирования типа записи
			'new_item'           => 'Новая заявка', // текст новой записи
			'view_item'          => 'Смотреть заявки', // для просмотра записи этого типа.
			'search_items'       => 'Искать заявки на вывод', // для поиска по этим типам записи
			'not_found'          => 'Не найдено', // если в результате поиска ничего не было найдено
			'not_found_in_trash' => 'Не найдено в корзине', // если не было найдено в корзине
			'menu_name'          => 'заявка на вывод', // название меню
		],
		'description'            => 'заявка на вывод средств от пользователя',
		'public'                 => true,
		'publicly_queryable'  => false,
		'exclude_from_search' => true,
		'show_ui'             => true,
		'show_in_nav_menus'   => false,
		'show_in_menu'           => true,
		'menu_position'       => 7,
		'menu_icon'           => 'dashicons-money-alt',
		'hierarchical'        => false,
		'has_archive'         => true,
		'supports'            => ['title', 'author'], // 'title','editor','author','thumbnail','excerpt','trackbacks','custom-fields','comments','revisions','page-attributes','post-formats'
	]);
}

// создание метабокса для вывода средств

function add_metabox_zayavka()
{
	add_meta_box(
		'meta_transaction_zayavka',
		'Метабокс для заявки на вывод',
		'metabox_zayavka_html',
		'zayavka',
		'normal',
		'default',
	);
}

function metabox_zayavka_html($post)
{
	$price = get_post_meta($post->ID, 'zayavka_price', true);
	$card = get_post_meta($post->ID, 'zayavka_card', true);

	echo '
	<p>	
		<label for="transaction_price">Сумма</label>
			<input name= "transaction_price" id="transaction_price" type="text" value = " ' . esc_html($price) . ' " >
	</p>';
	echo '
	<p>	
		<label for="transaction_price">Номер карты</label>
			<input name= "transaction_price" id="transaction_price" type="text" value = " ' . esc_html($card) . ' " >
	</p>';
}
