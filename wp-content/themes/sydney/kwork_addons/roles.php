<?php

//создать роль при активации темы
add_action('after_switch_theme', 'activate_my_theme');
function activate_my_theme()
{

	//роль наставник
	add_role(
		'teacher',
		'Наставник',
		[
			'read'         => true,  // true разрешает эту возможность
			'edit_posts'   => true,  // true разрешает редактировать посты
			'upload_files' => true,  // может загружать файлы
			'edit_published_posts' => true //редактировать опубликованные посты
		]
	);

	//роль для компании
	add_role('company', 'Компания', [
		'read'         => true,  // true разрешает эту возможность
		'edit_posts'   => true,  // true разрешает редактировать посты
		'upload_files' => true,  // может загружать файлы
		'edit_published_posts' => true //редактировать опубликованные посты
	]);
}
