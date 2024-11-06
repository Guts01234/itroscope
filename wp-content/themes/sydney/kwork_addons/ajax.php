<?php

//фильтрация на главной

add_action('wp_ajax_filt', 'filt_m');
add_action('wp_ajax_nopriv_filt', 'filt_m');

function filt_m()
{
  $arr_sfer =  $_POST['sfer'];
  $arr_navik =  $_POST['navik'];
  $arr_problem =  $_POST['problem'];
  $arr_age =  $_POST['age'];
  $arr_sex =  $_POST['sex'];
  $author = $_POST['author'];

  $args = [
    'posts_per_page' => -1,
    'post_type' => array('post', 'time', 'gift', 'service'),
    'post__not_in' => get_option('sticky_posts'),
    'post_status' => 'publish',
    'tax_query' => [
      'relation' => 'OR',
    ]
  ];

  if ($author != '') {
    $author = sanitize_text_field($author);
    $author_split = explode(' ', $author);
    $author_arr = [];
    //поиск по логину
    foreach ($author_split as $one_author) {
      $user = get_user_by('login', $one_author);
      $user_id = $user->ID;
      if (!in_array($user_id, $author_arr)) {
        array_push($author_arr, $user_id);
      }
    }
    //поиск по имени
    global $wpdb;
    foreach ($author_split as $one_author) {

      $users = $wpdb->get_results("SELECT user_id FROM $wpdb->usermeta WHERE meta_key = 'first_name' AND meta_value = " . "'" . $one_author . "'");
      if ($users) {
        foreach ($users as $user) {
          if (!in_array($user->user_id, $author_arr)) {
            array_push($author_arr, $user->user_id);
          }
        }
      }
    }
    // поиск по фамилии
    global $wpdb;
    foreach ($author_split as $one_author) {

      $users = $wpdb->get_results("SELECT user_id FROM $wpdb->usermeta WHERE meta_key = 'last_name' AND meta_value = " . "'" . $one_author . "'");
      if ($users) {
        foreach ($users as $user) {
          if (!in_array($user->user_id, $author_arr)) {
            array_push($author_arr, $user->user_id);
          }
        }
      }
    }
    $args['author__in'] = $author_arr;
  }
  if (is_array($arr_sfer)) {
    $add = [
      'taxonomy' => 'sfera',
      'field'    => 'id',
      'terms'    =>  $arr_sfer,
    ];
    array_push($args['tax_query'], $add);
  }

  if (is_array($arr_navik)) {
    $add = [
      'taxonomy' => 'navik',
      'field'    => 'id',
      'terms'    =>  $arr_navik,
    ];
    array_push($args['tax_query'], $add);
  }

  if (is_array($arr_problem)) {
    $add =   [
      'taxonomy' => 'problem',
      'field'    => 'id',
      'terms'    => $arr_problem,
    ];
    array_push($args['tax_query'], $add);
  }

  if (is_array($arr_age)) {
    $add =   [
      'taxonomy' => 'age',
      'field'    => 'id',
      'terms'    => $arr_age,
    ];
    array_push($args['tax_query'], $add);
  }

  if (is_array($arr_sex)) {
    $add =   [
      'taxonomy' => 'sex',
      'field'    => 'id',
      'terms'    => $arr_sex,
    ];
    array_push($args['tax_query'], $add);
  }

  //получаем id компаний
  $users_id = get_users(array(
    'role__in' => 'company',
    'fields' => 'ID',
  ));

  $users_company_in = array();

  if (is_user_logged_in()) {
    $users_company_in = get_user_meta(get_current_user_id(), 'company_arr', true);
    if (!$users_company_in) {
      $users_company_in = array();
    }
  }

  $authors_not = array_diff($users_id, $users_company_in);




  wp_reset_query();


  //комбинируем запрос

  //получаем открытые уроки
  $query1 = new WP_Query(
    array(
      'post_type' => array('post', 'time', 'gift', 'service'),
      'post__not_in' => get_option('sticky_posts'),
      'posts_per_page' => -1,
      'meta_query' => array(
        'relation' => 'OR',
        array(
          'key' => 'post_open_status',
          'value' => 'open'
        ),
      )
    )
  );

  $post_ids1 = wp_list_pluck($query1->posts, 'ID');

  //остальные записи
  $query2 = new WP_Query(
    array(
      'post_type' => array('post', 'time', 'gift', 'service'),
      'post__not_in' => get_option('sticky_posts'),
      'posts_per_page' => -1,
      'author__not_in' => $authors_not,
    )
  );

  $post_ids2 = wp_list_pluck($query2->posts, 'ID');


  $allTheIDs = array_merge($post_ids1, $post_ids2);

  $query3 = new WP_Query(
    $args
  );

  $post_ids3 = wp_list_pluck($query3->posts, 'ID');

  //если фильтры не отработали - дальше смысла нет
  if (count($post_ids3) < 1) {
    die();
  }

  $final_ids = array_uintersect($post_ids3, $allTheIDs, "strcasecmp");
  query_posts(
    array(
      'post_type' => array('post', 'time', 'gift', 'service'),
      'post__in' => $final_ids,
      'orderby' => 'rand(' . rand() . ')',
      'posts_per_page' => 12,
      'paged' => 1,
    )
  );
  if (have_posts()) :

    // запускаем цикл
    while (have_posts()): the_post();
      if (get_post_type() == 'marathon') {
        get_template_part('template-cards/contant_marathon', get_post_format());
      } else if (get_post_type() == 'gift') {
        get_template_part('template-cards/contant_gift', get_post_format());
      } else if (get_post_type() == 'time') {
        get_template_part('template-cards/contant_time', get_post_format());
      } else if (get_post_type() == 'service') {
        get_template_part('template-cards/contant_service', get_post_format());
      } else {
        get_template_part('template-cards/contant_main', get_post_format());
      }
    endwhile;

  endif;
  global $wp_query;
  if ($wp_query->max_num_pages > 1) {
?>
    <script>
      ajaxurl = '<?php echo site_url() ?>/wp-admin/admin-ajax.php';
      true_posts = '<?php echo serialize($wp_query->query_vars); ?>';
      current_page = <?php echo (get_query_var('paged')) ? get_query_var('paged') : 1; ?>;
      max_pages = '<?php echo $wp_query->max_num_pages; ?>';
    </script>
  <?php
  } else {
  ?>
    <style>
      #true_loadmore {
        display: none !important;
      }
    </style>
  <?php
  }
  die();
}



add_action('wp_ajax_filter_new', 'filter_new');
add_action('wp_ajax_nopriv_filter_new', 'filter_new');

function filter_new()
{

  $sfers        = $_POST['sfers'];
  $state        = $_POST['state'];
  $types        = $_POST['types'];
  $naviks       = $_POST['naviks'];
  $problems     = $_POST['problems'];
  $time_meeting = $_POST['time_meeting'];
  $town         = $_POST['town'];
  $gift_types    = $_POST['gift_type'];

  if (!$types) {
    $types = ['post', 'gift', 'lesson', 'service'];
  }

  //получаем id компаний
  $users_id = get_users(array(
    'role__in' => 'company',
    'fields' => 'ID',
  ));

  $users_company_in = array();

  if (is_user_logged_in()) {
    $users_company_in = get_user_meta(get_current_user_id(), 'company_arr', true);
    if (!$users_company_in) {
      $users_company_in = array();
    }
  }

  //айди авторов, кто является компанией
  $authors_not = array_diff($users_id, $users_company_in);


  //запрос для уроков
  $args_lesson = [
    'posts_per_page' => -1,
    'post_type' => array('post'),
    'post__not_in' => get_option('sticky_posts'),
    'post_status' => 'publish',
    'author__not_in' => $authors_not,
    'tax_query' => [
      'relation' => 'OR',
    ],
    'meta_query' => array(
      'relation' => 'AND',
      array(
        'key' => 'is_archive_post',
        'value' => true,
        'compare' => 'NOT EXISTS',
      ),
    )
  ];

  //запрос для времени
  $args_time = [
    'posts_per_page' => -1,
    'post_type' => array('time'),
    'post__not_in' => get_option('sticky_posts'),
    'post_status' => 'publish',
    'author__not_in' => $authors_not,
    'tax_query' => [
      'relation' => 'OR',
    ],
    'meta_query' => array(
      'relation' => 'AND',
      array(
        'key' => 'is_archive_post',
        'value' => true,
        'compare' => 'NOT EXISTS',
      ),
    )
  ];

  //запрос для подарков
  $args_gift = [
    'posts_per_page' => -1,
    'post_type' => array('gift'),
    'post__not_in' => get_option('sticky_posts'),
    'post_status' => 'publish',
    'author__not_in' => $authors_not,
    'tax_query' => [
      'relation' => 'OR',
    ],
    'meta_query' => array(
      'relation' => 'AND',
      array(
        'key' => 'is_archive_post',
        'value' => true,
        'compare' => 'NOT EXISTS',
      ),
    )
  ];

  //запрос для услуг
  $args_service = [
    'posts_per_page' => -1,
    'post_type' => array('service'),
    'post__not_in' => get_option('sticky_posts'),
    'post_status' => 'publish',
    'author__not_in' => $authors_not,
    'tax_query' => [
      'relation' => 'OR',
    ],
    'meta_query' => array(
      'relation' => 'AND',
      array(
        'key' => 'is_archive_post',
        'value' => true,
        'compare' => 'NOT EXISTS',
      ),
    )
  ];

  //запрос для услуг компании открытых
  $args_service__free = [
    'posts_per_page' => -1,
    'post_type' => array('service'),
    'post__not_in' => get_option('sticky_posts'),
    'post_status' => 'publish',
    'author__in' => $authors_not,
    'tax_query' => [
      'relation' => 'OR',
    ],
    'meta_query' => array(
      'relation' => 'AND',
      array(
        'key' => 'post_open_status',
        'value' => 'open'
      ),
      array(
        'key' => 'is_archive_post',
        'value' => true,
        'compare' => 'NOT EXISTS',
      ),
    )
  ];

  //запрос для уроков компании открытых
  $args_lesson__free = [
    'posts_per_page' => -1,
    'post_type' => array('post'),
    'post__not_in' => get_option('sticky_posts'),
    'post_status' => 'publish',
    'author__in' => $authors_not,
    'tax_query' => [
      'relation' => 'OR',
    ],
    'meta_query' => array(
      'relation' => 'AND',
      array(
        'key' => 'post_open_status',
        'value' => 'open'
      ),
      array(
        'key' => 'is_archive_post',
        'value' => true,
        'compare' => 'NOT EXISTS',
      ),
    )
  ];

  //запрос для времени компании открытых
  $args_time__free = [
    'posts_per_page' => -1,
    'post_type' => array('time'),
    'post__not_in' => get_option('sticky_posts'),
    'post_status' => 'publish',
    'author__in' => $authors_not,
    'tax_query' => [
      'relation' => 'AND',
      array(
        'key' => 'is_archive_post',
        'value' => true,
        'compare' => 'NOT EXISTS',
      ),
    ]
  ];

  //запрос для подарков компании открытых
  $args_gift__free = [
    'posts_per_page' => -1,
    'post_type' => array('gift'),
    'post__not_in' => get_option('sticky_posts'),
    'post_status' => 'publish',
    'author__in' => $authors_not,
    'tax_query' => [
      'relation' => 'OR',
    ],
    'meta_query' => array(
      'relation' => 'AND',
      array(
        'key' => 'post_open_status',
        'value' => 'open'
      ),
      array(
        'key' => 'is_archive_post',
        'value' => true,
        'compare' => 'NOT EXISTS',
      ),
    )
  ];

  // Тип для подарка
  if (is_array($gift_types)) {
    $add_meta_gift_types = array(
      'relation' => 'OR',
    );

    foreach ($gift_types as $gift_type) {
      $add =   [
        'key' => 'type_gift',
        'value' => $gift_type
      ];
      array_push($add_meta_gift_types, $add);
    }

    array_push($args_gift['meta_query'], $add_meta_gift_types);
    array_push($args_gift__free['meta_query'], $add_meta_gift_types);
  }


  //фильтруем по сферам
  if (is_array($sfers)) {
    $add = [
      'taxonomy' => 'sfera',
      'field'    => 'id',
      'terms'    =>  $sfers,
    ];
    array_push($args_lesson['tax_query'], $add);
    array_push($args_time['tax_query'], $add);
    array_push($args_service['tax_query'], $add);
    array_push($args_service__free['tax_query'], $add);
    array_push($args_time__free['tax_query'], $add);
  }

  //фильтруем по навыкам
  if (is_array($naviks)) {
    $add = [
      'taxonomy' => 'navik',
      'field'    => 'id',
      'terms'    =>  $naviks,
    ];
    array_push($args_lesson['tax_query'], $add);
    array_push($args_time['tax_query'], $add);
    array_push($args_service['tax_query'], $add);
    array_push($args_service__free['tax_query'], $add);
    array_push($args_lesson__free['tax_query'], $add);
    array_push($args_time__free['tax_query'], $add);
  }

  //фильтруем по проблемам
  if (is_array($problems)) {
    $add =   [
      'taxonomy' => 'problem',
      'field'    => 'id',
      'terms'    => $problems,
    ];
    array_push($args_lesson['tax_query'], $add);
    array_push($args_time['tax_query'], $add);
    array_push($args_service['tax_query'], $add);
    array_push($args_service__free['tax_query'], $add);
    array_push($args_lesson__free['tax_query'], $add);
    array_push($args_time__free['tax_query'], $add);
  }

  //фильтруем по статусу получить/отдать
  if (is_array($state)) {
    $arr_state = array(
      'relation' => 'OR',
    );

    foreach ($state as $state_el) {
      $add =   [
        'key' => 'cart_state',
        'value' => $state_el
      ];
      array_push($arr_state, $add);
    }

    array_push($args_gift['meta_query'], $arr_state);
    array_push($args_time['meta_query'], $arr_state);
    array_push($args_gift__free['meta_query'], $arr_state);
    array_push($args_time__free['meta_query'], $arr_state);

    //отдельный запрос с сохранением предыдущей логики для уроков
    $arr_state_lesson = array(
      'relation' => 'OR',
    );

    if (in_array('give', $state)) {
      $arr_take = array(
        'relation' => 'OR'
      );
      $add =   [
        'key' => 'cart_state',
        'value' => 'give'
      ];
      array_push($arr_take, $add);
      $add =   [
        'key' => 'cart_state',
        'value' => 'give',
        'compare' => 'NOT EXISTS',
      ];
      array_push($arr_take, $add);
      array_push($arr_state_lesson, $arr_take);
    }
    if (in_array('take', $state)) {
      $add =   [
        'key' => 'cart_state',
        'value' => 'take'
      ];
      array_push($arr_state_lesson, $add);
    }

    array_push($args_lesson['meta_query'], $arr_state_lesson);
    array_push($args_lesson__free['meta_query'], $arr_state_lesson);
  }

  //фильтруем по статусу встречи для времени
  if (is_array($time_meeting)) {
    $arr_time_meeting = array(
      'relation' => 'OR',
    );

    //если встреча оналйн есть
    if (in_array('online', $time_meeting)) {
      $add = [
        'key' => 'time_meeting',
        'value' => 'online'
      ];

      array_push($arr_time_meeting, $add);
    }

    //если встреча оффлайн
    if (in_array('offline', $time_meeting)) {

      //и если указан город
      if (mb_strlen($town) > 2) {

        $arr_time_meeting_offline = array(
          'relation' => 'AND',
          array(
            'key' => 'time_meeting',
            'value' => 'offline',
            'compare' => 'LIKE',
          ),
          array(
            'key' => 'time_town',
            'value' => $town,
            'compare' => 'LIKE',
          )
        );
        array_push($arr_time_meeting, $arr_time_meeting_offline);
      } else {
        $add = [
          'key' => 'time_meeting',
          'value' => 'offline',
          'compare' => 'LIKE',
        ];

        array_push($arr_time_meeting, $add);
      }
    }

    array_push($args_time['meta_query'], $arr_time_meeting);
    array_push($args_time__free['meta_query'], $arr_time_meeting);
  }

  wp_reset_query();

  $final_ids = array();

  //вливаем уроки
  if (in_array('post', $types) || !is_array($types)) {
    $lesson_query = new WP_Query($args_lesson);
    $lesson_ids = wp_list_pluck($lesson_query->posts, 'ID');

    $lesson_query__free = new WP_Query($args_lesson__free);
    $lesson__free_ids = wp_list_pluck($lesson_query__free->posts, 'ID');

    $final_ids = array_merge($final_ids, $lesson_ids, $lesson__free_ids);
  }

  //вливаем подарки
  if (in_array('gift', $types) || !is_array($types)) {
    $gift_query = new WP_Query($args_gift);
    $gift_ids = wp_list_pluck($gift_query->posts, 'ID');

    $gift_query__free = new WP_Query($args_gift__free);
    $gift__free_ids = wp_list_pluck($gift_query__free->posts, 'ID');

    $final_ids = array_merge($final_ids, $gift_ids, $gift__free_ids);
  }

  //вливаем время
  if (in_array('time', $types) || !is_array($types)) {
    $time_query = new WP_Query($args_time);
    $time_ids = wp_list_pluck($time_query->posts, 'ID');

    $time_query__free = new WP_Query($args_time__free);
    $time__free_ids = wp_list_pluck($time_query__free->posts, 'ID');

    $final_ids = array_merge($final_ids, $time_ids, $time__free_ids);
  }

  //вливаем услуги

  if (in_array('service', $types) || !is_array($types)) {
    $service_query = new WP_Query($args_service);
    $service_ids = wp_list_pluck($service_query->posts, 'ID');

    $service_query__free = new WP_Query($args_service__free);
    $service__free_ids = wp_list_pluck($service_query__free->posts, 'ID');

    $final_ids = array_merge($final_ids, $service_ids, $service__free_ids);
  }


  //если фильтры не отработали - дальше смысла нет
  if (count($final_ids) < 1) {
    echo "<p id='filter_not_found'>Ничего не найдено</p>";
    die();
  }

  query_posts(
    array(
      'post_type' => $types,
      'post__in' => $final_ids,
      'orderby' => 'rand(' . rand() . ')',
      'posts_per_page' => 12,
      'paged' => 1,
    )
  );
  if (have_posts()) :
    // запускаем цикл
    while (have_posts()): the_post();
      if (get_post_type() == 'marathon') {
        get_template_part('template-cards/contant_marathon', get_post_format());
      } else if (get_post_type() == 'gift') {
        get_template_part('template-cards/contant_gift', get_post_format());
      } else if (get_post_type() == 'time') {
        get_template_part('template-cards/contant_time', get_post_format());
      } else if (get_post_type() == 'service') {
        get_template_part('template-cards/contant_service', get_post_format());
      } else {
        get_template_part('template-cards/contant_main', get_post_format());
      }
    endwhile;

  endif;
  global $wp_query;

  if ($wp_query->max_num_pages > 1): ?>
    <script>
      ajaxurl = '<?php echo site_url() ?>/wp-admin/admin-ajax.php';
      true_posts = '<?php echo serialize($wp_query->query_vars); ?>';
      current_page = <?php echo (get_query_var('paged')) ? get_query_var('paged') : 1; ?>;
      max_pages = '<?php echo $wp_query->max_num_pages; ?>';
    </script>

  <?php else: ?>
    <style>
      #true_loadmore {
        display: none !important;
      }
    </style>
  <?php endif ?>

  <?php
  die();
}


//загрузить ещё на главной


add_action('wp_ajax_loadmore', 'loadmore_k');
add_action('wp_ajax_nopriv_loadmore', 'loadmore_k');

function loadmore_k()
{
  $args = unserialize(stripslashes($_POST['query']));
  $args['paged'] = $_POST['page'] + 1; // следующая страница
  $args['post_status'] = 'publish';

  // обычно лучше использовать WP_Query, но не здесь
  query_posts($args);
  // если посты есть
  if (have_posts()) :

    // запускаем цикл
    while (have_posts()): the_post();
      if (get_post_type() == 'marathon') {
        get_template_part('template-cards/contant_marathon', get_post_format());
      } else if (get_post_type() == 'gift') {
        get_template_part('template-cards/contant_gift', get_post_format());
      } else if (get_post_type() == 'time') {
        get_template_part('template-cards/contant_time', get_post_format());
      } else if (get_post_type() == 'service') {
        get_template_part('template-cards/contant_service', get_post_format());
      } else {
        get_template_part('template-cards/contant_main', get_post_format());
      }


    endwhile;

  endif;
  die();
}


//для отслеживания статистики на уроке

add_action('wp_ajax_post_scroll', 'post_scroll');
add_action('wp_ajax_nopriv_post_scroll', 'post_scroll');

function post_scroll()
{
  $user_id = $_POST['user_id'];
  $post_id = $_POST['post_id'];
  $user_scroll_posts = get_user_meta($user_id, 'user_scroll_posts')[0];

  if (!is_array($user_scroll_posts)) {
    $user_scroll_posts = array();
  }
  if (!in_array($post_id, $user_scroll_posts)) {
    array_push($user_scroll_posts, $post_id);
    //Блок для добавления статистики
    $users_scroll = get_post_meta($post_id, 'users_scroll')[0];
    if (!isset($users_scroll)) {
      $users_scroll = 0;
    }
    $users_scroll += 1;
    update_post_meta($post_id, 'users_scroll', $users_scroll);
    update_user_meta($user_id, 'user_scroll_posts', $user_scroll_posts);
    //Блок для добавления статистики конец
  }

  die;
}







add_action('wp_ajax_get_free', 'read_ajax');
add_action('wp_ajax_nopriv_get_free', 'read_ajax');



//функция для добавления в бд с помощью аякс
//срабатывает когда пользователь нажимает "прочитал"
function read_ajax()
{

  // принимаем данные из запроса

  $user_id = $_POST['user_id'];
  $post_id = $_POST['post_id'];

  $this_practice;
  if (isset($_POST['is_practice'])) {
    $this_practice = $_POST['is_practice'];
  }

  if (isset($_POST['is_crypt']) && $_POST['is_crypt']) {
    $post_id = simple_decode($_POST['post_id']);
  }
  if (!$this_practice) {
    //получаем термы
    $data_sfer = wp_get_post_terms($post_id, 'sfera');
    $data_navik = wp_get_post_terms($post_id, 'navik');
    $data_problem = wp_get_post_terms($post_id, 'problem');

    // принимаем данные из бд
    $now_post_arr = get_user_meta($user_id, 'post_read_arr')[0];
    $now_sfer_arr = get_user_meta($user_id, 'sfeta_read_arr')[0];
    $now_problem_arr = get_user_meta($user_id, 'problem_read_arr')[0];
    $now_navik_arr = get_user_meta($user_id, 'navik_read_arr')[0];


    // проверка есть ли такой массив
    if (gettype($now_post_arr) != 'array') {
      $now_post_arr = array();
    }
    //если нет такого элемента то добавляем
    if (!in_array($post_id, $now_post_arr)) {
      array_push($now_post_arr, $post_id);
    }


    if (gettype($now_sfer_arr) != 'array') {
      $now_sfer_arr = array();
    }

    if (gettype($now_problem_arr) != 'array') {
      $now_problem_arr = array();
    }

    if (gettype($now_navik_arr) != 'array') {
      $now_navik_arr = array();
    }

    //работа со сферами
    if ($data_sfer[0] != 'none') {
      $c = get_post_meta($post_id, 'sfer_add_count', true);
      if ($c == '') {
        $c = '1';
      }
      $c = intval($c);
      foreach ($data_sfer as $sfer) {
        if (array_key_exists($sfer->name, $now_sfer_arr)) {
          $now_sfer_arr[$sfer->name] =   $now_sfer_arr[$sfer->name] + $c;
        } else {
          $now_sfer_arr[$sfer->name] = $c;
        }
      }
    }

    //работа с навыками
    if ($data_navik[0] != 'none') {

      $c = get_post_meta($post_id, 'navik_add_count', true);
      if ($c == '') {
        $c = '1';
      }
      $c = intval($c);
      foreach ($data_navik as $sfer) {
        if (array_key_exists($sfer->name, $now_navik_arr)) {
          $now_navik_arr[$sfer->name] =   $now_navik_arr[$sfer->name] + $c;
        } else {
          $now_navik_arr[$sfer->name] = $c;
        }
      }
    }

    //работа с проблемами
    if ($data_problem[0] != 'none') {

      $c = get_post_meta($post_id, 'problem_add_count', true);
      if ($c == '') {
        $c = '1';
      }
      $c = intval($c);
      foreach ($data_problem as $sfer) {
        if (array_key_exists($sfer->name, $now_problem_arr)) {
          $now_problem_arr[$sfer->name] =   $now_problem_arr[$sfer->name] + $c;
        } else {
          $now_problem_arr[$sfer->name] = $c;
        }
      }
    }

    //data-sfer

    update_user_meta($user_id, 'post_read_arr', $now_post_arr);
    update_user_meta($user_id, 'sfeta_read_arr', $now_sfer_arr);
    update_user_meta($user_id, 'problem_read_arr', $now_problem_arr);
    update_user_meta($user_id, 'navik_read_arr', $now_navik_arr);
  }

  //добавляем в массив оплаченных
  $posts_arr_pay = get_user_meta($user_id, 'posts_arr_pay')[0];

  if (!is_array($posts_arr_pay)) {
    $posts_arr_pay = array();
  }
  if (!in_array($post_id, $posts_arr_pay)) {
    array_push($posts_arr_pay, $post_id);
  }

  //добавляем в массив оплаченных дз
  $homework_arr_paied  = get_user_meta($user_id, 'homework_arr_paied')[0];
  if (!is_array($homework_arr_paied)) {
    $homework_arr_paied = array();
  }
  if (!in_array($post_id, $homework_arr_paied)) {
    array_push($homework_arr_paied, $post_id);
  }
  update_user_meta($user_id, 'posts_arr_pay', $posts_arr_pay);
  update_user_meta($user_id, 'homework_arr_paied', $homework_arr_paied);



  // БЛОК ДЛЯ ДОБАВЛЕНИЯ ДЗ
  $homework_post_arr = get_user_meta($user_id, 'homework_post_arr')[0];
  $homework_arr = get_user_meta($user_id, 'homework_arr')[0];

  // проверка есть ли такой массив
  if (gettype($homework_post_arr) != 'array') {
    $homework_post_arr = array();
  }
  // проверка есть ли такой массив
  if (gettype($homework_arr) != 'array') {
    $homework_arr = array();
  }
  //если нет такого элемента то добавляем
  if (!in_array($post_id, $homework_post_arr)) {
    array_push($homework_post_arr, $post_id);
  }
  update_user_meta($user_id, 'homework_post_arr', $homework_post_arr);

  $teacher_id = get_post($post_id)->post_author;

  global $wpdb;
  $wpdb->query(
    $wpdb->prepare(
      "
        INSERT INTO " . $wpdb->prefix . "homeworks
        ( user_id, teacher_id, post_id, status_homework )
        VALUES ( %d, %d, %d, %s )
      ",
      $user_id,
      $teacher_id,
      $post_id,
      'Не сделано'
    )
  );
  $results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}homeworks WHERE user_id = {$user_id} AND post_id = {$post_id}", OBJECT);
  $results = $results[count($results) - 1];
  $hw_id = $results->id;
  //если нет такого элемента то добавляем
  if (!in_array($hw_id, $homework_arr)) {
    array_push($homework_arr, $hw_id);
  }
  echo $hw_id;
  update_user_meta($user_id, 'homework_arr', $homework_arr);
  // БЛОК ДЛЯ ДОБАВЛЕНИЯ ДЗ КОНЕЦ

  //Блок для добавления статистики
  $users_buy = get_post_meta($post_id, 'users_buy')[0];
  if (!isset($users_buy)) {
    $users_buy = 0;
  }
  $users_buy += 1;
  update_post_meta($post_id, 'users_buy', $users_buy);
  //Блок для добавления статистики конец
  echo 'added';

  //сохраняем время добавление в урок
  update_user_meta($user_id, 'last_learn', time());

  die;
}

//поиск по навыкам
add_action('wp_ajax_search_naviks', 'search_naviks');
add_action('wp_ajax_nopriv_search_naviks', 'search_naviks');

function search_naviks()
{
  $search = $_POST['search'];
  $count = $_POST['count'];
  $chechedTermsIds = $_POST['chechedTermsIds'];

  $search = trim($search);

  $terms = get_terms([
    'taxonomy' => ['navik'],
    'name__like' => $search,
    'exclude' => $chechedTermsIds,
  ]);

  if (count($terms) == 0) {
    echo '<p id="text_filter_navik_not_search">Таких навыков не найдено</p>';
    die();
  }

  $c = 0;
  foreach ($terms as $term):
    $c++;
    if ($c > $count) break;
  ?>
    <label class="container js_on">
      <input type="checkbox" class='js_navik' name='filter_navik[]' value=<?php echo $term->term_id; ?>>
      <span class="checkmark"></span>
      <p><?php echo $term->name ?></p>
    </label>
  <?php
  endforeach;


  die();
}


//поиск по проблемам
add_action('wp_ajax_search_problems', 'search_problems');
add_action('wp_ajax_nopriv_search_problems', 'search_problems');

function search_problems()
{
  $search = $_POST['search'];
  $count = $_POST['count'];
  $chechedTermsIds = $_POST['chechedTermsIds'];

  $search = trim($search);

  $terms = get_terms([
    'taxonomy' => ['problem'],
    'name__like' => $search,
    'exclude' => $chechedTermsIds,
  ]);

  if (count($terms) == 0) {
    echo '<p id="text_filter_problem_not_search">Таких навыков не найдено</p>';
    die();
  }

  $c = 0;
  foreach ($terms as $term):
    $c++;
    if ($c > $count) break;
  ?>
    <label class="container js_on">
      <input type="checkbox" class='js_problem' name='filter_problem[]' value=<?php echo $term->term_id; ?>>
      <span class="checkmark"></span>
      <p><?php echo $term->name ?></p>
    </label>
<?php
  endforeach;


  die();
}


?>