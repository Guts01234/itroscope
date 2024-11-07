<?php
/* ПОДКЛЮЧЕНИЕ ДЕКОМОЗИРОВАННЫХ ЧАСТЕЙ ЛОГИКИ */

//все роли
require get_template_directory() . '/kwork_addons/roles.php';

//шотркоды
require get_template_directory() . '/kwork_addons/shortcodes.php';

//ajax
require get_template_directory() . '/kwork_addons/ajax.php';

//константы
require get_template_directory() . '/kwork_addons/_constants.php';

/* ПОДКЛЮЧЕНИЕ ДЕКОМОЗИРОВАННЫХ ЧАСТЕЙ ЛОГИКИ КОНЕЦ*/



/*ПОДКЛЮЧЕНИЕ МОДУЛЕЙ*/

//уведомления
require get_template_directory() . '/kwork_addons/modules/notifications/notifications.php';

//компании
require get_template_directory() . '/kwork_addons/modules/company/index.php';

//наставники
require get_template_directory() . '/kwork_addons/modules/teachers/index.php';

//чаты
require get_template_directory() . '/kwork_addons/modules/chat/index.php';

//вспомогалка для уроков
require get_template_directory() . '/kwork_addons/modules/lesson/index.php';

//время
require get_template_directory() . '/kwork_addons/modules/time/index.php';

//Подарки
require get_template_directory() . '/kwork_addons/modules/gift/index.php';

// Услуги
require get_template_directory() . '/kwork_addons/modules/services/index.php';

// Баланс
require get_template_directory() . '/kwork_addons/modules/balance/index.php';

/*ПОДКЛЮЧЕНИЕ МОДУЛЕЙ КОНЕЦ*/

add_action('init', 'reg_tax_sfera');
add_action('init', 'reg_tax_problem');
add_action('init', 'reg_tax_navik');
add_action('init', 'reg_tax_sex');
add_action('init', 'reg_tax_age');
add_action('init', 'register_itroscope_types');

// system futction

function get_readed_arr($user_id)
{
  $arr = get_user_meta($user_id, $key = 'post_read_arr', true);
  if (!$arr) {
    $arr = [];
  }
  return $arr;
}
function get_post_homework_arr($user_id)
{
  global $wpdb;
  $results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}homeworks WHERE user_id = {$user_id} AND status_homework = 'Проверено'", OBJECT);
  return $results;
}


//regitser taxonomy sfera

function reg_tax_sfera()
{
  register_taxonomy('sfera', ['post', 'gift', 'service'],  [

    'label' => 'Сферы',
    'description' => 'Таксономия сферы',
    'labels' => [
      'name'              => 'Сферы',
      'singular_name'     => 'Сфера',
      'search_items'      => 'Найти сферы',
      'all_items'         => 'Все сферы',
      'view_item '        => 'Посмотреть сферы',
      'parent_item'       => 'Родитель сферы',
      'parent_item_colon' => 'Родитель сферы:',
      'edit_item'         => 'Редактировать сферу',
      'update_item'       => 'Обновить сферу',
      'add_new_item'      => 'Добавить новую сферу',
      'new_item_name'     => 'Добавить новую',
      'menu_name'         => 'Сферы',
      'back_to_items'     => '← Вернуться к сферам',
    ],
    'public' => true,
    'show_in_rest' => true
  ]);
}
//regitser taxonomy sfera

function reg_tax_problem()
{
  register_taxonomy('problem', ['post', 'service'], [

    'label' => 'Проблемы',
    'description' => 'Таксономия проблемы',
    'labels' => [
      'name'              => 'Проблемы',
      'singular_name'     => 'Проблема',
      'search_items'      => 'Найти проблемы',
      'all_items'         => 'Все проблемы',
      'view_item '        => 'Посмотреть проблемы',
      'parent_item'       => 'Родитель проблемы',
      'parent_item_colon' => 'Родитель проблемы:',
      'edit_item'         => 'Редактировать проблему',
      'update_item'       => 'Обновить проблему',
      'add_new_item'      => 'Добавить новую проблему',
      'new_item_name'     => 'Добавить новую',
      'menu_name'         => 'Проблемы',
      'back_to_items'     => '← Вернуться к проблемам',
    ],
    'public' => true,
    'show_in_rest' => true
  ]);
}
//regitser taxonomy navik

function reg_tax_navik()
{
  register_taxonomy('navik', ['post', 'service'], [

    'label' => 'Навыки',
    'description' => 'Таксономия навыки',
    'labels' => [
      'name'              => 'Навыки',
      'singular_name'     => 'Навык',
      'search_items'      => 'Найти навыки',
      'all_items'         => 'Все навыки',
      'view_item '        => 'Посмотреть навыки',
      'parent_item'       => 'Родитель навыка',
      'parent_item_colon' => 'Родитель навыки:',
      'edit_item'         => 'Редактировать навык',
      'update_item'       => 'Обновить навык',
      'add_new_item'      => 'Добавить новый навык',
      'new_item_name'     => 'Добавить новый',
      'menu_name'         => 'Навыки',
      'back_to_items'     => '← Вернуться к навыкам',
    ],
    'public' => true,
    'show_in_rest' => true,
    'hierarchical' => true
  ]);
}
//regitser taxonomy пол

function reg_tax_sex()
{
  register_taxonomy('sex', ['post', 'service'], [

    'label' => 'Пол',
    'description' => 'Таксономия пол',
    'labels' => [
      'name'              => 'Пол',
      'singular_name'     => 'Пол',
      'search_items'      => 'Найти пол',
      'all_items'         => 'Все пол',
      'view_item '        => 'Посмотреть пол',
      'parent_item'       => 'Родитель пол',
      'parent_item_colon' => 'Родитель пол:',
      'edit_item'         => 'Редактировать пол',
      'update_item'       => 'Обновить пол',
      'add_new_item'      => 'Добавить новый пол',
      'new_item_name'     => 'Добавить новый',
      'menu_name'         => 'Пол',
      'back_to_items'     => '← Вернуться к пол',
    ],
    'public' => true,
    'show_in_rest' => true
  ]);
}
//regitser taxonomy age

function reg_tax_age()
{
  register_taxonomy('age', ['post', 'service'], [

    'label' => 'Возраст',
    'description' => 'Таксономия возраст',
    'labels' => [
      'name'              => 'Возраст',
      'singular_name'     => 'Возраст',
      'search_items'      => 'Найти пол',
      'all_items'         => 'Все возраст',
      'view_item '        => 'Посмотреть возраст',
      'parent_item'       => 'Родитель возраст',
      'parent_item_colon' => 'Родитель возраст:',
      'edit_item'         => 'Редактировать возраст',
      'update_item'       => 'Обновить возраст',
      'add_new_item'      => 'Добавить новый возраст',
      'new_item_name'     => 'Добавить новый',
      'menu_name'         => 'Возраст',
      'back_to_items'     => '← Вернуться к возрастам',
    ],
    'public' => true,
    'show_in_rest' => true
  ]);
}


function register_itroscope_types()
{
  register_post_type('gift', [
    'label'  => 'gift',
    'labels' => [
      'name'               => 'Подарок', // основное название для типа записи
      'singular_name'      => 'Подарок', // название для одной записи этого типа
      'add_new'            => 'Добавить подарок', // для добавления новой записи
      'add_new_item'       => 'Добавление подарка', // заголовка у вновь создаваемой записи в админ-панели.
      'edit_item'          => 'Редактирование подарка', // для редактирования типа записи
      'new_item'           => 'Новый подарок', // текст новой записи
      'view_item'          => 'Смотреть подарки', // для просмотра записи этого типа.
      'search_items'       => 'Искать подарки', // для поиска по этим типам записи
      'not_found'          => 'Не найдено', // если в результате поиска ничего не было найдено
      'not_found_in_trash' => 'Не найдено в корзине', // если не было найдено в корзине
      'menu_name'          => 'Подарки', // название меню
    ],
    'description'            => 'Подарки',
    'public'                 => true,
    'show_in_menu'           => true, // показывать ли в меню админки
    'show_in_rest'        => true, // добавить в REST API. C WP 4.7
    'menu_position'       => 5,
    'menu_icon'           => 'data:image/svg+xml;base64,' . base64_encode('<?xml version="1.0" encoding="utf-8"?>
    <svg width="1792" height="1792" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg"><path fill="black" d="M1056 1356v-716h-320v716q0 25 18 38.5t46 13.5h192q28 0 46-13.5t18-38.5zm-456-844h195l-126-161q-26-31-69-31-40 0-68 28t-28 68 28 68 68 28zm688-96q0-40-28-68t-68-28q-43 0-69 31l-125 161h194q40 0 68-28t28-68zm376 256v320q0 14-9 23t-23 9h-96v416q0 40-28 68t-68 28h-1088q-40 0-68-28t-28-68v-416h-96q-14 0-23-9t-9-23v-320q0-14 9-23t23-9h440q-93 0-158.5-65.5t-65.5-158.5 65.5-158.5 158.5-65.5q107 0 168 77l128 165 128-165q61-77 168-77 93 0 158.5 65.5t65.5 158.5-65.5 158.5-158.5 65.5h440q14 0 23 9t9 23z"/></svg>'),
    'hierarchical'        => false,
    'supports'            => ['title', 'editor', 'author', 'custom-fields'], // 'title','editor','author','thumbnail','excerpt','trackbacks','custom-fields','comments','revisions','page-attributes','post-formats'
    'taxonomies'          => ['sfera', 'navik', 'problem'],
  ]);


  register_post_type('time', [
    'label'  => 'time',
    'labels' => [
      'name'               => 'Время', // основное название для типа записи
      'singular_name'      => 'Время', // название для одной записи этого типа
      'add_new'            => 'Добавить время', // для добавления новой записи
      'add_new_item'       => 'Добавление времени', // заголовка у вновь создаваемой записи в админ-панели.
      'edit_item'          => 'Редактирование времени', // для редактирования типа записи
      'new_item'           => 'Новое время', // текст новой записи
      'view_item'          => 'Смотреть время', // для просмотра записи этого типа.
      'search_items'       => 'Искать время', // для поиска по этим типам записи
      'not_found'          => 'Не найдено', // если в результате поиска ничего не было найдено
      'not_found_in_trash' => 'Не найдено в корзине', // если не было найдено в корзине
      'menu_name'          => 'Время', // название меню
    ],
    'description'            => 'Время',
    'public'                 => true,
    'show_in_menu'           => true, // показывать ли в меню админки
    'show_in_rest'        => true, // добавить в REST API. C WP 4.7
    'menu_position'       => 6,
    'menu_icon'           => 'data:image/svg+xml;base64,' . base64_encode('<?xml version="1.0" encoding="utf-8"?>
    <svg width="1792" height="1792" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg"><path fill="black" d="M1024 544v448q0 14-9 23t-23 9h-320q-14 0-23-9t-9-23v-64q0-14 9-23t23-9h224v-352q0-14 9-23t23-9h64q14 0 23 9t9 23zm416 352q0-148-73-273t-198-198-273-73-273 73-198 198-73 273 73 273 198 198 273 73 273-73 198-198 73-273zm224 0q0 209-103 385.5t-279.5 279.5-385.5 103-385.5-103-279.5-279.5-103-385.5 103-385.5 279.5-279.5 385.5-103 385.5 103 279.5 279.5 103 385.5z"/></svg>'),
    'hierarchical'        => false,
    'supports'            => ['title', 'editor', 'author', 'custom-fields'], // 'title','editor','author','thumbnail','excerpt','trackbacks','custom-fields','comments','revisions','page-attributes','post-formats'
    'taxonomies'          => ['sfera', 'navik', 'problem'],
  ]);
}


add_action('btn_read', 'btn_read');

function btn_read()
{

  if (!function_exists('btn_read')) {
    return;
  }
  $has_teory = get_post_meta(get_the_ID(), $key = 'has_this_post_teory', $single = true);
  $has_practice = get_post_meta(get_the_ID(), $key = 'has_this_post_homework', $single = true);

  $post_id = get_the_ID();

  //проверяем показывать как теорию или как практику
  $this_practice = 0;
  if ($has_teory === '0' && $has_practice == 1) {
    $this_practice = 1;
  }
  if (is_user_logged_in()) {
    $post_id_read = get_the_ID();
    $user_id_read = get_current_user_id();
    $posts_arr_pay = get_user_meta($user_id_read, 'posts_arr_pay')[0];
    //получаем пост чтобы получить id автора
    $my_post = get_post($id);
    $author_id = $my_post->post_author;
    $price = get_post_meta($post_id_read, 'post_homework_price')[0];
    $cart_state = get_post_meta($post_id_read, 'cart_state', true);

    //проверка если текущий полользователь автор - выводим что он автор
    if ($author_id == $user_id_read) {
      $edit_page = (int) wpuf_get_option('edit_page_id', 'wpuf_frontend_posting');
      $url = add_query_arg(['pid' => $post_id_read], get_permalink($edit_page));
      echo '<hr />';
      echo '<p>Вы автор текущего урока</p>';
      echo '<p><a href="' .  esc_url(wp_nonce_url($url, 'wpuf_edit')) . '">Редактировать урок</a></p>';

      if ($cart_state != 'take') {
        $bonus_url = get_site_url() . "/stranica-dlya-polucheniya-ochkov-bonusa?post_id=" . simple_encode(get_the_ID());
        echo "<p>Обучайте вживую и начисляйте навыки по ссылке!</p>";
        echo "<button id='copy_bonus_link' data-link='$bonus_url' class='cute_blue_button'>Скопировать ссылку</button>";
      }


      return;
    }

    // табличка с выводом цены и навыков
?>
    <hr>
    <div class="cart_text">
      <p><?php
          if ($has_practice == 1) {
            echo "Оплатите урок и установите контакт с экспертом. Автор подтвердит теорию, пришлёт Вам задание, проверит и после подтверждения в ваш паспорт добавится:";
          } else {
            echo "Оплатите урок и установите контакт с экспертом. Автор урока пришлёт задание, проверит и даст обратную связь в течении двух дней. После подтверждения в ваш паспорт добавится:";
          }
          ?></p>
      <p><b>Вы станете лучше, в ваш паспорт будет добавлено</b></p>
    </div>
    <div class="terms_single_post">
      <?php

      $terms;
      $has_teory = get_post_meta(get_the_ID(), $key = 'has_this_post_teory', $single = true);
      $has_practice = get_post_meta(get_the_ID(), $key = 'has_this_post_homework', $single = true);

      $post_id = get_the_ID();

      //проверяем показывать как теорию или как практику
      //проверка на практику
      $this_practice = 0;
      if ($has_teory === '0' && $has_practice == 1) {
        $this_practice = 1;
      }
      if ($has_teory !== '0') {
      ?>
        <div class="terms_teory data-plus-info">
          <p>Теория:</p>
          <?php
          $terms  = wp_get_post_terms(get_the_ID(), 'sfera');
          $c = count($terms);
          if ($c): ?>
            <ul class='sfer'>
              <li>
                <p class='name_sfer'>Сферы:</p>
              </li>

              <?php foreach ($terms as $term): ?>
                <li><?php echo_cute_sfer($term->name, "+1 " . $term->name); ?></li>
              <?php endforeach; ?>

            </ul>
          <?php endif;

          $terms  = wp_get_post_terms(get_the_ID(), 'problem');
          $c = count($terms);
          if ($c) {
          ?>
            <ul class='sfer'>
              <li>
                <p class='name_sfer'>Проблемы:</p>
              </li>

              <?php

              foreach ($terms as $term) {
              ?>
                <li>
                  <p class='cute_problem'><?php echo "+1 " . $term->name; ?></p>
                </li>

              <?php
              }
              ?>
            </ul>
          <?php
          }

          $terms  = wp_get_post_terms(get_the_ID(), 'navik');
          $c = count($terms);
          if ($c) {
          ?>
            <ul class='sfer'>
              <li>
                <p class='name_sfer'>Навыки:</p>
              </li>

              <?php

              foreach ($terms as $term) {
              ?>
                <li>
                  <p class='cute_navik'><?php echo "+1 " . $term->name; ?></p>
                </li>

              <?php
              }
              ?>
            </ul>
          <?php
          }
          ?>
        </div>
      <?php
      }

      if ($has_practice == 1) {
      ?>
        <div class="terms_practice data-plus-info">
          <p>Практика:</p>
          <?php
          $terms = get_post_meta($post_id, $key = 'sfer_practice', $single = true);
          $terms = explode(',', $terms);
          $c = count($terms);
          if ($c): ?>
            <ul class='sfer'>
              <li>
                <p class='name_sfer'>Сферы:</p>
              </li>

              <?php foreach ($terms as $term): ?>
                <li><?php echo_cute_sfer($term->name, "+1 " . $term->name); ?></li>
              <?php endforeach; ?>

            </ul>
          <?php endif;

          $terms = get_post_meta($post_id, $key = 'problem_practice', $single = true);
          $terms = explode(',', $terms);
          $c = count($terms);
          if ($c) {
          ?>
            <ul class='sfer'>
              <li>
                <p class='name_sfer'>Проблемы:</p>
              </li>

              <?php

              foreach ($terms as $term) {
              ?>
                <li>
                  <p class='cute_problem'><?php echo "+1 " . $term; ?></p>
                </li>

              <?php
              }
              ?>
            </ul>
          <?php
          }

          $terms = get_post_meta($post_id, $key = 'navik_practice', $single = true);
          $terms = explode(',', $terms);
          $c = count($terms);
          if ($c) {
          ?>
            <ul class='sfer'>
              <li>
                <p class='name_sfer'>Навыки:</p>
              </li>

              <?php

              foreach ($terms as $term) {
              ?>
                <li>
                  <p class='cute_navik'><?php echo "+1 " . $term; ?></p>
                </li>

              <?php
              }
              ?>
            </ul>
          <?php
          }
          ?>
        </div>
      <?php
      }
      ?>


    </div>
    <table class='cart_table'>
      <tr>
        <td class='b_none'></td>
        <td class='text_r b_none'>
          <p>Цена урока</p>
        </td>
      </tr>
      <tr>
        <td class='stat_read_post'>
          <span><img src="<?php echo get_template_directory_uri(); ?>/images/person.svg" title='Прочитали' alt="Просмотрено"></span>
          <span><?php
                $user_scroll = get_post_meta($post_id_read, $key = 'users_scroll')[0];
                if (!isset($user_scroll)) {
                  $user_scroll = 0;
                }
                echo $user_scroll;

                ?></span>
          <span><img src="<?php echo get_template_directory_uri(); ?>/images/money.svg" title='Купили' alt="Добавлено в паспорт"></span>
          <span><?php
                $users_buy = get_post_meta($post_id_read, $key = 'users_buy')[0];
                if (!isset($users_buy)) {
                  $users_buy = 0;
                }
                echo $users_buy;
                ?></span>
        </td>
        <td class='text_r b_none'>
          <p>

            <?php
            if ($price == '0') {
              echo '<b>БЕСПЛАТНО</b>';
            } else {
              echo '<b>' . $price . 'р</b>';
            }
            ?>
          </p>
        </td>
      </tr>
    </table>

    <div class="done_btns" id='end_of_post'><?php
                                            if (!is_array($posts_arr_pay) or !in_array($post_id_read, $posts_arr_pay) or ($this_practice == 1)) {
                                              if ($price  == '0') {
                                                if ($this_practice) {
                                                  //отдельная кнопка для практики
                                            ?><div id='read_done_con'><button id='get_free_practice'>Получить практику бесплатно</button></div><?php
                                                                                                                                              } else {
                                                                                                                                                ?><div id='read_done_con'><button id='get_free'>Получить бесплатно</button></div><?php
                                                                                                                                                                                                                                }
                                                                                                                                                                                                                              } else {
                                                                                                                                                                                                                                // отдлельная кнопка для практики
                                                                                                                                                                                                                                if ($this_practice) {
                                                                                                                                                                                                                                  ?>
            <div id='read_done_con'><button id='pay_for_post_practice'>Оплатить практику</button></div>
          <?php
                                                                                                                                                                                                                                } else {
          ?>
            <div id='read_done_con'><button id='pay_for_post'>Оплатить</button></div>
        <?php
                                                                                                                                                                                                                                }
                                                                                                                                                                                                                              }
                                                                                                                                                                                                                            } else {
        ?><p class="readed">Добавлено в <a href='<?php echo get_site_url() . '/user'; ?>'>паспорт</a></p><?php
                                                                                                                                                                                                                            }
                                                                                                          ?>
    </div><?php
        } else {
          ?>
    <hr class='hr_to_log'>
    <p class='text_to_log'><?php
                            if ($has_practice == 1) {
                              echo "Войдите в систему или зарегистрируйтесь. Автор урока даст задание, проверит его и добавит в Ваш паспорт навыков. ";
                            } else {
                              echo "Войдите в систему или зарегистрируйтесь. Получите задание от автора урока и возможность добавить полученные знания  в паспорт навыков.";
                            }
                            ?></p>
    <a href="https://itroscope.com/login/" id='call_to_reg'>ВОЙТИ ИЛИ ЗАРЕГИСТРИРОВАТЬСЯ</a>
  <?php
        }
      }
      add_action('btn_read_time', 'btn_read_time');

      function btn_read_time()
      {

        if (!function_exists('btn_read_time')) {
          return;
        }

        $post_id = get_the_ID();

        if (is_user_logged_in()) {
          $post_id_read = get_the_ID();
          $user_id_read = get_current_user_id();
          $time_arr = get_user_meta($user_id_read, 'time_arr', true);
          //получаем пост чтобы получить id автора
          $my_post = get_post($id);
          $author_id = $my_post->post_author;

          //проверка если текущий полользователь автор - выводим что он автор
          if ($author_id == $user_id_read) {
            $edit_page = (int) wpuf_get_option('edit_page_id', 'wpuf_frontend_posting');
            $url = add_query_arg(['pid' => $post_id_read], get_permalink($edit_page));
            echo '<hr />';
            echo '<p>Вы автор текущего объявления</p>';
            echo '<p><a href="' .  esc_url(wp_nonce_url($url, 'wpuf_edit')) . '">Редактировать объявление</a></p>';

            return;
          }

          // табличка с выводом цены и навыков
  ?>
    <hr>
    <div class="cart_text">
      <p>Свяжитесь с автором объявления, чтобы обсудить дальнейшие детали</p>
      <p><b>Вы станете лучше, в ваш паспорт будет добавлено</b></p>
    </div>
    <div class="terms_single_post">
      <?php

          $terms;

          $post_id = get_the_ID();

          //проверяем показывать как теорию или как практику
          //проверка на практику
      ?>
      <div class="terms_teory data-plus-info">
        <?php
          $terms  = wp_get_post_terms(get_the_ID(), 'sfera');
          $c = count($terms);
          if ($c): ?>
          <ul class='sfer'>
            <li>
              <p class='name_sfer'>Сферы:</p>
            </li>

            <?php foreach ($terms as $term): ?>
              <li><?php echo_cute_sfer($term->name, "+1 " . $term->name); ?></li>
            <?php endforeach; ?>

          </ul>
        <?php endif;

          $terms  = wp_get_post_terms(get_the_ID(), 'problem');
          $c = count($terms);
          if ($c) {
        ?>
          <ul class='sfer'>
            <li>
              <p class='name_sfer'>Проблемы:</p>
            </li>

            <?php

            foreach ($terms as $term) {
            ?>
              <li>
                <p class='cute_problem'><?php echo "+1 " . $term->name; ?></p>
              </li>

            <?php
            }
            ?>
          </ul>
        <?php
          }

          $terms  = wp_get_post_terms(get_the_ID(), 'navik');
          $c = count($terms);
          if ($c) {
        ?>
          <ul class='sfer'>
            <li>
              <p class='name_sfer'>Навыки:</p>
            </li>

            <?php

            foreach ($terms as $term) {
            ?>
              <li>
                <p class='cute_navik'><?php echo "+1 " . $term->name; ?></p>
              </li>

            <?php
            }
            ?>
          </ul>
        <?php
          }
        ?>
      </div>
    </div>
    </div>
    <table class='cart_table'>
      <tr>
        <td class='stat_read_post'>
          <span><img src="<?php echo get_template_directory_uri(); ?>/images/person.svg" title='Прочитали' alt="Просмотрено"></span>
          <span><?php
                $user_scroll = get_post_meta($post_id_read, $key = 'users_scroll')[0];
                if (!isset($user_scroll)) {
                  $user_scroll = 0;
                }
                echo $user_scroll;

                ?></span>
          <span><img src="<?php echo get_template_directory_uri(); ?>/images/money.svg" title='Купили' alt="Добавлено в паспорт"></span>
          <span><?php
                $users_buy = get_post_meta($post_id_read, $key = 'users_buy')[0];
                if (!isset($users_buy)) {
                  $users_buy = 0;
                }
                echo $users_buy;
                ?></span>
        </td>
      </tr>
    </table>

    <div class="done_btns" id='end_of_post'><?php
                                            //если такое время существует, значит он уже подал заявку
                                            if (Time::is_created_time($post_id, $user_id_read)) {
                                            ?><p class="readed">Вы уже откликнулись на это объявление</p><?php
                                                                                                        } else {
                                                                                                          ?><div id='read_done_con'><button id='get_time' data-custumer="<?php echo get_current_user_id() ?>"
            data-post-id="<?php echo $post_id ?>">Связаться</button></div><?php
                                                                                                        }
                                                                          ?> </div>
  <?php
        } else {
  ?>
    <hr class='hr_to_log'>
    <p class='text_to_log'>Войдите в систему или зарегистрируйтесь, чтобы связаться по поводу объявления</p>
    <a href="https://itroscope.com/login/" id='call_to_reg'>ВОЙТИ ИЛИ ЗАРЕГИСТРИРОВАТЬСЯ</a>
  <?php
        }
      }

      add_action('btn_read_lesson_take', 'btn_read_lesson_take');

      function btn_read_lesson_take()
      {

        if (!function_exists('btn_read_lesson_take')) {
          return;
        }

        $post_id = get_the_ID();
        $price = get_post_meta($post_id, 'post_price_take', true);
        if (!$price || $price < 1) {
          $price = 'Ничего';
        } else {
          $price .= ' ₽';
        }

        if (is_user_logged_in()) {
          $post_id_read = get_the_ID();
          $user_id_read = get_current_user_id();
          $time_arr = get_user_meta($user_id_read, 'time_arr', true);
          //получаем пост чтобы получить id автора
          $my_post = get_post($id);
          $author_id = $my_post->post_author;

          //проверка если текущий полользователь автор - выводим что он автор
          if ($author_id == $user_id_read) {
            $edit_page = (int) wpuf_get_option('edit_page_id', 'wpuf_frontend_posting');
            $url = add_query_arg(['pid' => $post_id_read], get_permalink($edit_page));
            echo '<hr />';
            echo '<p>Вы автор текущего объявления</p>';
            echo '<p><a href="' .  esc_url(wp_nonce_url($url, 'wpuf_edit')) . '">Редактировать объявление</a></p>';

            return;
          }

          // табличка с выводом цены и навыков
  ?>
    <hr>
    <div class="cart_text">
      <p>Свяжитесь с автором объявления, чтобы обсудить дальнейшие детали</p>
      <p>Обучаемый готов заплатить <?php echo $price ?></p>
    </div>
    </div>

    <div class="done_btns" id='end_of_post'><?php
                                            //если такое время существует, значит он уже подал заявку
                                            if (Lesson::is_created_lesson($post_id, $user_id_read)) {
                                            ?><p class="readed">Вы уже откликнулись на это объявление</p><?php
                                                                                                        } else {
                                                                                                          ?><div id='read_done_con'><button id='get_lesson' data-custumer="<?php echo get_current_user_id() ?>"
            data-post-id="<?php echo $post_id ?>">Связаться</button></div><?php
                                                                                                        }
                                                                          ?> </div>
  <?php
        } else {
  ?>
    <hr class='hr_to_log'>
    <p class='text_to_log'>Войдите в систему или зарегистрируйтесь, чтобы связаться по поводу объявления</p>
    <a href="https://itroscope.com/login/" id='call_to_reg'>ВОЙТИ ИЛИ ЗАРЕГИСТРИРОВАТЬСЯ</a>
  <?php
        }
      }

      add_action('btn_read_gift', 'btn_read_gift');

      function btn_read_gift()
      {

        if (!function_exists('btn_read_gift')) {
          return;
        }

        $post_id = get_the_ID();

        if (is_user_logged_in()) {
          $post_id_read = get_the_ID();
          $user_id_read = get_current_user_id();
          $gift_arr = get_user_meta($user_id_read, 'gift_arr', true);
          //получаем пост чтобы получить id автора
          $my_post = get_post($id);
          $author_id = $my_post->post_author;

          //проверка если текущий полользователь автор - выводим что он автор
          if ($author_id == $user_id_read) {
            $edit_page = (int) wpuf_get_option('edit_page_id', 'wpuf_frontend_posting');
            $url = add_query_arg(['pid' => $post_id_read], get_permalink($edit_page));
            echo '<hr />';
            echo '<p>Вы автор текущего объявления</p>';
            echo '<p><a href="' .  esc_url(wp_nonce_url($url, 'wpuf_edit')) . '">Редактировать объявление</a></p>';

            return;
          }

          if (get_post_meta($post_id_read, 'is_archive_post', true)) {
            echo '<div class="cart_text"><p>Эта запись находится в архиве</p></div>';
            return;
          }

          // табличка с выводом цены и навыков
  ?>
    <hr>
    <div class="cart_text">
      <p>Свяжитесь с автором объявления, чтобы обсудить дальнейшие детали</p>
    </div>
    </div>
    <table class='cart_table'>
      <tr>
        <td class='stat_read_post'>
          <span><img src="<?php echo get_template_directory_uri(); ?>/images/person.svg" title='Прочитали' alt="Просмотрено"></span>
          <span><?php
                $user_scroll = get_post_meta($post_id_read, $key = 'users_scroll')[0];
                if (!isset($user_scroll)) {
                  $user_scroll = 0;
                }
                echo $user_scroll;

                ?></span>
          <span><img src="<?php echo get_template_directory_uri(); ?>/images/money.svg" title='Купили' alt="Добавлено в паспорт"></span>
          <span><?php
                $users_buy = get_post_meta($post_id_read, $key = 'users_buy')[0];
                if (!isset($users_buy)) {
                  $users_buy = 0;
                }
                echo $users_buy;
                ?></span>
        </td>
      </tr>
    </table>

    <div class="done_btns" id='end_of_post'><?php
                                            //если такое время существует, значит он уже подал заявку
                                            if (Gift::is_created_gift($post_id, $user_id_read)) {
                                            ?><p class="readed">Вы уже откликнулись на это объявление</p><?php
                                                                                                        } else {
                                                                                                          ?><div id='read_done_con'><button id='get_gift' data-custumer="<?php echo get_current_user_id() ?>"
            data-post-id="<?php echo $post_id ?>">Связаться</button></div><?php
                                                                                                        }
                                                                          ?> </div>
  <?php
        } else {
  ?>
    <hr class='hr_to_log'>
    <p class='text_to_log'>Войдите в систему или зарегистрируйтесь, чтобы связаться по поводу объявления</p>
    <a href="https://itroscope.com/login/" id='call_to_reg'>ВОЙТИ ИЛИ ЗАРЕГИСТРИРОВАТЬСЯ</a>
  <?php
        }
      }



      add_action('wp_enqueue_scripts', 'reg_user_script');

      //add sctipt_ajax
      function reg_user_script()
      {

        $cur_url = get_permalink();
        $cur_url = str_replace(get_site_url(), '', $cur_url);
        $cur_url = str_replace('/', '', $cur_url);


        wp_enqueue_script('jquery');

        wp_register_script('ahunter_suggest', 'https://ahunter.ru/js/min/ahunter_suggest.js', array('jquery'), '1.0.2', true);

        wp_enqueue_script(
          'ajax_bd_script',
          get_template_directory_uri() . '/kwork_addons/js/ajax_script.js',
          array('jquery'),
          filemtime(get_theme_file_path('/kwork_addons/js/ajax_script.js')),
          'in_footer'
        );

        // Если находимся в профиле - то только тогда подключать скрипт
        if (str_contains($cur_url, 'user')) {
          wp_enqueue_script('jquery-suggestions');

          wp_enqueue_script(
            'autocomplete',
            get_template_directory_uri() . '/kwork_addons/js/town-autocomplete.js',
            array('ahunter_suggest'),
            filemtime(get_theme_file_path('/kwork_addons/js/town-autocomplete.js')),
            'in_footer'
          );
        }




        //скрипт должен работать только на этой странице
        if ($cur_url == "stranica-dlya-polucheniya-ochkov-bonusa") {
          wp_enqueue_script(
            'bonus_script',
            get_template_directory_uri() . '/kwork_addons/js/bonus_point_logic.js',
            array('jquery'),
            filemtime(get_theme_file_path('/kwork_addons/js/bonus_point_logic.js')),
            'in_footer'
          );
        }

        if (
          $_GET['profiletab'] == 'mycustomtab'
          || $cur_url == "redaktirovanie-zapisi"
        ) {
          wp_enqueue_script(
            'form_validate',
            get_template_directory_uri() . '/kwork_addons/js/form_validate.js',
            array('jquery'),
            filemtime(get_theme_file_path('/kwork_addons/js/form_validate.js')),
            'in_footer'
          );
        }
      }


      add_action('post_before_cont_kw', 'ech_term_kwork');

      function ech_term_kwork()
      {
  ?>
  <div>
    <div class="terms_single_post">
      <?php

        $terms;
        $has_teory = get_post_meta(get_the_ID(), $key = 'has_this_post_teory', $single = true);
        $has_practice = get_post_meta(get_the_ID(), $key = 'has_this_post_homework', $single = true);

        $post_id = get_the_ID();
        $cart_state = get_post_meta($post_id, 'cart_state', true);

        //проверяем показывать как теорию или как практику
        //проверка на практику
        $this_practice = 0;
        if ($has_teory === '0' && $has_practice == 1) {
          $this_practice = 1;
        }
        if ($has_teory !== '0') {
      ?>
        <div class="terms_teory data-plus-info">
          <?php if ($cart_state != 'take'): ?>
            <p>Теория:</p>
          <?php endif; ?>
          <?php
          $terms  = wp_get_post_terms(get_the_ID(), 'sfera');
          $c = count($terms);
          if ($c): ?>
            <ul class='sfer'>
              <li>
                <p class='name_sfer'>Сферы:</p>
              </li>

              <?php foreach ($terms as $term): ?>
                <li><?php echo_cute_sfer($term->name); ?></li>
              <?php endforeach; ?>

            </ul>
          <?php endif;

          $terms  = wp_get_post_terms(get_the_ID(), 'problem');
          $c = count($terms);
          if ($c) {
          ?>
            <ul class='sfer'>
              <li>
                <p class='name_sfer'>Проблемы:</p>
              </li>

              <?php

              foreach ($terms as $term) {
              ?>
                <li>
                  <p class='ploblem_stat'><?php echo $term->name; ?></p>
                </li>

              <?php
              }
              ?>
            </ul>
          <?php
          }

          $terms  = wp_get_post_terms(get_the_ID(), 'navik');
          $c = count($terms);
          if ($c) {
          ?>
            <ul class='sfer'>
              <li>
                <p class='name_sfer'>Навыки:</p>
              </li>

              <?php

              foreach ($terms as $term) {
              ?>
                <li>
                  <p class='navik_stat'><?php echo $term->name; ?></p>
                </li>

              <?php
              }
              ?>
            </ul>
          <?php
          }
          ?>
        </div>
      <?php
        }

        if ($has_practice == 1) {
      ?>
        <div class="terms_practice data-plus-info">
          <p>Практика:</p>
          <?php
          $terms = get_post_meta($post_id, $key = 'sfer_practice', $single = true);
          $terms = explode(',', $terms);
          $c = count($terms);
          if ($c): ?>
            <ul class='sfer'>
              <li>
                <p class='name_sfer'>Сферы:</p>
              </li>

              <?php foreach ($terms as $term): ?>
                <li><?php echo_cute_sfer($term, $term); ?></li>
              <?php endforeach; ?>

            </ul>
          <?php endif;

          $terms = get_post_meta($post_id, $key = 'problem_practice', $single = true);
          $terms = explode(',', $terms);
          $c = count($terms);
          if ($c) {
          ?>
            <ul class='sfer'>
              <li>
                <p class='name_sfer'>Проблемы:</p>
              </li>

              <?php

              foreach ($terms as $term) {
              ?>
                <li>
                  <p class='ploblem_stat'><?php echo $term; ?></p>
                </li>

              <?php
              }
              ?>
            </ul>
          <?php
          }

          $terms = get_post_meta($post_id, $key = 'navik_practice', $single = true);
          $terms = explode(',', $terms);
          $c = count($terms);
          if ($c) {
          ?>
            <ul class='sfer'>
              <li>
                <p class='name_sfer'>Навыки:</p>
              </li>

              <?php

              foreach ($terms as $term) {
              ?>
                <li>
                  <p class='navik_stat'><?php echo $term; ?></p>
                </li>

              <?php
              }
              ?>
            </ul>
          <?php
          }
          ?>
        </div>
      <?php
        }
      ?>


    </div>
  <?php
      }





      //ajax функция для сохранения решения к домашнему заданию
      add_action('wp_ajax_add_done_homework', 'add_done_homework_ajax');
      add_action('wp_ajax_nopriv_add_done_homework', 'add_done_homework_ajax');
      function add_done_homework_ajax()
      {
        $meta_id = $_POST['meta_id'];
        $homework_text = $_POST['homework_text'];

        $arr_img_urls = [];
        //проверка есть ли вложения
        if (isset($_FILES['file'])) {
          echo 'eee';
          $arr_img_ext = array('image/png', 'image/jpeg', 'image/jpg', 'image/gif');

          for ($i = 0; $i < count($_FILES['file']['name']); $i++) {

            if (in_array($_FILES['file']['type'][$i], $arr_img_ext)) {

              $upload = wp_upload_bits($_FILES['file']['name'][$i], null, file_get_contents($_FILES['file']['tmp_name'][$i]));
              array_push($arr_img_urls, $upload['url']);
            }
          }
        }

        $arr_imgs_str = serialize($arr_img_urls);

        global $wpdb;
        $wpdb->update(
          $wpdb->prefix . "homeworks",
          ['homework_text' => $homework_text, 'status_homework' => 'На проверке', 'homework_imgs' => $arr_imgs_str],
          ['id' => $meta_id]
        );

        $results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}homeworks WHERE id = {$meta_id}", OBJECT)[0];
        $user_id = $results->user_id;
        //сохраняем время добавление в урок
        update_user_meta($user_id, 'last_learn', time());

        $user = get_user_by('ID', $results->teacher_id);

        $link = 'https://' . $_SERVER['SERVER_NAME'] . '/user/' . $user->user_login . "?profiletab=posts";

        NotificationDB::add_notification($results->teacher_id, 'Задания', '<p>Кто-то выполнил ваше задание. <a href="' . $link . '">Проверьте его</a> и дайте обратную связь ученику.</p>');

        //отправлка уведомления наставникам
        $post_id = $results->post_id;

        $notification_content = '<p>Кто-то выполнил задание к уроку <a href="' . get_permalink($post_id) . '">
  “' . get_the_title($post_id) . '”</a></p>' .
          '<div class="nastavnik_add_lesson__btns" data-homework-id="' . $meta_id . '">' .
          '<button class="nastavnik_add_lesson__confirm">Принять</button>' .
          '<button class="nastavnik_add_lesson__cansel">Отказаться</button>' .
          '</div>';



        $teachers_arr = get_post_meta($post_id, 'post_teachers_arr', true);

        if (!$teachers_arr) {
          $teachers_arr = array();
        }

        $notification__array = array();

        foreach ($teachers_arr as $key => $teacher_id) {
          $n = NotificationDB::add_notification($teacher_id, 'Задания', $notification_content);
          array_push($notification__array, $n->get_id());
        }

        update_post_meta($post_id, 'notification_homework_' . $meta_id,  $notification__array);

        die;
      }

      //функции проверки для учителя
      add_action('wp_ajax_teacher_homework_done', 'teacher_homework_done');
      add_action('wp_ajax_nopriv_teacher_homework_done', 'teacher_homework_done');

      function teacher_homework_done()
      {
        $meta_id = $_POST['meta_id'];
        $comment = $_POST['comment'];

        global $wpdb;
        $wpdb->update(
          $wpdb->prefix . "homeworks",
          ['comments' => $comment, 'status_homework' => 'Проверено'],
          ['id' => $meta_id],
          ['%s', '%s'],
          ['%d']
        );

        $results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}homeworks WHERE id = {$meta_id}", OBJECT)[0];
        $post_id = $results->post_id;
        $user_id = $results->user_id;
        $teacher_id = $results->teacher_id;

        $man_last_action = 'author';

        //блок наставника
        $nastavnik_id = $results->nastavnik_id;
        //если подтвердил наставник, то все плюшки ему
        if ($nastavnik_id && get_current_user_id() == $nastavnik_id) {
          $teacher_id = $nastavnik_id;
          $man_last_action = 'nastavnik';
          $user_practice_navik = get_user_meta($nastavnik_id, 'practice_navik')[0];

          if (!$user_practice_navik) {
            $user_practice_navik = array();
          }

          $c = 1;
          $term = "Наставничество";

          if (array_key_exists($term, $user_practice_navik)) {
            $user_practice_navik[$term] = $user_practice_navik[$term] + $c;
          } else {
            $user_practice_navik[$term] = $c;
          }

          update_user_meta($nastavnik_id, 'practice_navik', $user_practice_navik);
        }

        $wpdb->update(
          $wpdb->prefix . "homeworks",
          ['man_last_action' => $man_last_action],
          ['id' => $meta_id],
          ['%s'],
          ['%d']
        );


        $sfers = explode(',', get_post_meta($post_id, $key = 'sfer_practice', $single = true));
        $naviks = explode(',', get_post_meta($post_id, $key = 'navik_practice', $single = true));
        $problems = explode(',', get_post_meta($post_id, $key = 'problem_practice', $single = true));

        $user_practice_sfera = get_user_meta($user_id, 'practice_sfera')[0];
        $user_practice_navik = get_user_meta($user_id, 'practice_navik')[0];
        $user_practice_problem = get_user_meta($user_id, 'practice_problem')[0];


        $sfers_add = get_post_meta($post_id, 'sfer_add_count', true);
        $new_user_practice_sfers = terms_to_arr_to_db($sfers, $user_practice_sfera, $sfers_add);

        $navik_add = get_post_meta($post_id, 'navik_add_count', true);
        $new_user_practice_naviks = terms_to_arr_to_db($naviks, $user_practice_navik, $navik_add);

        $problem_add = get_post_meta($post_id, 'problem_add_count', true);
        $new_user_practice_problems = terms_to_arr_to_db($problems, $user_practice_problem, $problem_add);

        update_user_meta($user_id, 'practice_sfera', $new_user_practice_sfers);
        update_user_meta($user_id, 'practice_navik', $new_user_practice_naviks);
        update_user_meta($user_id, 'practice_problem', $new_user_practice_problems);

        $user_practice_done_posts_arr = get_user_meta($user_id, 'user_practice_done_posts_arr')[0];

        if (!is_array($user_practice_done_posts_arr)) {
          $user_practice_done_posts_arr = array();
        }
        array_push($user_practice_done_posts_arr, $post_id);

        update_user_meta($user_id, 'user_practice_done_posts_arr', $user_practice_done_posts_arr);

        update_user_meta($teacher_id, 'last_teach', time());

        //добавление статистки в "пользователь прошёл дз"
        $users_done_practice = get_post_meta($post_id, 'users_done_practice', true);

        if (!$users_done_practice) {
          $users_done_practice = 0;
        }

        $users_done_practice += 1;

        update_post_meta($post_id, 'users_done_practice', $users_done_practice);



        NotificationDB::add_notification($user_id, 'Задания', '<p>Ваше задание проверено и принято. Результат зачтён в ваш паспорт навыков</p>');

        die;
      }

      //вспомогательная функция для добавления практики в бд
      // Проверяет и засовывает или плюсует в массив значения
      function terms_to_arr_to_db($terms, $arr, $c, $is_tax = false)
      {
        if (!is_array($arr)) {
          $arr = array();
        }
        if ($c == '') {
          $c = '1';
        }
        $c = floatval($c);
        foreach ($terms as $term) {
          $term_name = $term;

          if ($is_tax) {
            $term_name = $term->name;
          }

          $term_name = trim($term_name);

          if (array_key_exists($term_name, $arr)) {
            $arr[$term_name] = $arr[$term_name] + $c;
          } else {
            $arr[$term_name] = $c;
          }
        }
        return $arr;
      }

      add_action('wp_ajax_teacher_homework_redoing', 'teacher_homework_redoing');
      add_action('wp_ajax_nopriv_teacher_homework_redoing', 'teacher_homework_redoing');

      function teacher_homework_redoing()
      {
        $meta_id = $_POST['meta_id'];
        $comment = $_POST['comment'];

        global $wpdb;
        $wpdb->update(
          $wpdb->prefix . "homeworks",
          ['comments' => $comment, 'status_homework' => 'Отправлено на доработку'],
          ['id' => $meta_id],
          ['%s', '%s'],
          ['%d']
        );


        $results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}homeworks WHERE id = {$meta_id}", OBJECT)[0];
        $teacher_id = $results->teacher_id;
        $man_last_action = 'author';
        //блок наставника
        $nastavnik_id = $results->nastavnik_id;
        //если подтвердил наставник, то все плюшки ему
        if ($nastavnik_id && get_current_user_id() == $nastavnik_id) {
          $teacher_id = $nastavnik_id;
          $man_last_action = 'nastavnik';
        }

        $wpdb->update(
          $wpdb->prefix . "homeworks",
          ['man_last_action' => $man_last_action],
          ['id' => $meta_id],
          ['%s'],
          ['%d']
        );

        update_user_meta($teacher_id, 'last_teach', time());

        NotificationDB::add_notification($results->user_id, 'Задания', '<p>Вам нужно доработать задание по уроку <a href="'
          . get_permalink($results->post_id) . '">“'
          . get_the_title($results->post_id) . '”</a> </p>');


        die;
      }

      add_action('wp_ajax_redoing_user_homework', 'user_homework_redoing');
      add_action('wp_ajax_nopriv_redoing_user_homework', 'user_homework_redoing');

      function user_homework_redoing()
      {
        $meta_id = $_POST['meta_id'];
        $homework_text = $_POST['homework_text'];

        $arr_img_urls = [];
        //проверка есть ли вложения
        if (isset($_FILES['file'])) {
          echo 'eee';
          $arr_img_ext = array('image/png', 'image/jpeg', 'image/jpg', 'image/gif');

          for ($i = 0; $i < count($_FILES['file']['name']); $i++) {

            if (in_array($_FILES['file']['type'][$i], $arr_img_ext)) {

              $upload = wp_upload_bits($_FILES['file']['name'][$i], null, file_get_contents($_FILES['file']['tmp_name'][$i]));
              array_push($arr_img_urls, $upload['url']);
            }
          }
        }

        $arr_imgs_str = serialize($arr_img_urls);


        global $wpdb;
        $wpdb->update(
          $wpdb->prefix . "homeworks",
          ['homework_text' => $homework_text, 'status_homework' => 'На проверке', 'homework_imgs' => $arr_imgs_str],
          ['id' => $meta_id]
        );


        $results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}homeworks WHERE id = {$meta_id}", OBJECT)[0];
        $user_id = $results->user_id;
        $last_man = $results->man_last_action;
        //сохраняем время добавление в урок
        update_user_meta($user_id, 'last_learn', time());

        $teacher_id = $results->teacher_id;
        //блок наставника
        $nastavnik_id = $results->nastavnik_id;
        //если подтвердил наставник, то все плюшки ему
        if ($last_man == 'nastavnik') {
          $teacher_id = $nastavnik_id;
        }


        NotificationDB::add_notification($teacher_id, 'Задания', '<p>Ученик отправил задание по уроку <a href="'
          . get_permalink($results->post_id) . '">“'
          . get_the_title($results->post_id) . '”</a>на проверку после доработки</p>');

        die;
      }


      add_action('filter_mainpage', 'filter_mainpage');

      function filter_mainpage()
      {
        if (!is_front_page()) {
          return;
        }
  ?>

    <div id="modal_filter__wrapper" class="modal_filter__wrapper">
      <div class="modal_filter">
        <div id="close_modal_filter" class="close"></div>
        <p class="modal_filter__title">Фильтр карточек</p>
        <div class="modal_filter__list">

          <div class="modal_filter__list__item modal_filter__list__item__sfer">
            <p class="modal_filter__item__title">Сферы:</p>
            <?php $terms = get_terms([
              'taxonomy' => ['sfera'],
              'hide_empty' => false,
            ]);

            foreach ($terms as $term): ?>
              <label class="container js_on">
                <input type="checkbox" name='filter_sfera[]' value=<?php echo $term->term_id; ?>>
                <span class="checkmark"></span>
                <p><?php echo $term->name ?></p>
              </label>
            <?php endforeach ?>
          </div>

          <div class="modal_filter__list__item modal_filter__list__item__state">
            <p class="modal_filter__item__title">Я хочу:</p>
            <div class="modal_filter__list__item__state__row">
              <label class="container js_on">
                <input type="checkbox" name='filter_state[]' value="give">
                <span class="checkmark"></span>
                <p>Получить</p>
              </label>

              <label class="container js_on">
                <input type="checkbox" name='filter_state[]' value="take">
                <span class="checkmark"></span>
                <p>Отдать</p>
              </label>
            </div>
          </div>

          <div class="modal_filter__list__item modal_filter__list__item__type">
            <p class="modal_filter__item__title">Тип карточек:</p>

            <label class="container js_on">
              <input type="checkbox" name='filter_type[]' value='post'>
              <span class="checkmark"></span>
              <p>Знания</p>
            </label>

            <label class="container js_on">
              <input type="checkbox" name='filter_type[]' value='gift'>
              <span class="checkmark"></span>
              <p>Подарки</p>
            </label>

            <label class="container js_on">
              <input type="checkbox" name='filter_type[]' value='time'>
              <span class="checkmark"></span>
              <p>Время</p>
            </label>

            <label class="container js_on">
              <input type="checkbox" name='filter_type[]' value='service'>
              <span class="checkmark"></span>
              <p>Услуги</p>
            </label>

          </div>

          <div class="modal_filter__list__item modal_filter__list__item__navik">
            <p class="modal_filter__item__title">Навыки:</p>
            <div class="modal_filter__list__item__navik__input_container has_search_icon">
              <input type="text" name='filter_navik_search' id='search_navik_filter' placeholder="Начните вводить интересующие Вас навыки...">
            </div>

            <div class="modal_filter__list__item__navik__list">
              <?php $terms = get_terms([
                'taxonomy' => ['navik'],
                'hide_empty' => false,
              ]);
              $c = 0;
              foreach ($terms as $term):
                $c += 1;
                if ($c > 5) break;
              ?>
                <label class="container js_on">
                  <input type="checkbox" class='js_navik' name='filter_navik[]' value=<?php echo $term->term_id; ?>>
                  <span class="checkmark"></span>
                  <p><?php echo $term->name ?></p>
                </label>
              <?php endforeach; ?>
            </div>

          </div>

          <div class="modal_filter__list__item modal_filter__list__item__problem">
            <p class="modal_filter__item__title">Проблемы:</p>
            <div class="modal_filter__list__item__problem__input_container has_search_icon">
              <input type="text" name='filter_problem_search' id='search_problem_filter' placeholder="Начните вводить интересующие Вас проблемы...">
            </div>

            <div class="modal_filter__list__item__problem__list">
              <?php $terms = get_terms([
                'taxonomy' => ['problem'],
                'hide_empty' => false,
              ]);
              $c = 0;
              foreach ($terms as $term):
                $c += 1;
                if ($c > 5) break;
              ?>
                <label class="container js_on">
                  <input type="checkbox" class='js_problem' name='filter_problem[]' value=<?php echo $term->term_id; ?>>
                  <span class="checkmark"></span>
                  <p><?php echo $term->name ?></p>
                </label>
              <?php endforeach; ?>
            </div>

          </div>

          <div class="modal_filter__list__item modal_filter__list__item__gift_type">
            <p class="modal_filter__item__title">Вид подарка:</p>

            <label class="container js_on">
              <input type="checkbox" name='gift_type[]' value='money'>
              <span class="checkmark"></span>
              <p>Деньги</p>
            </label>

            <label class="container js_on">
              <input type="checkbox" name='gift_type[]' value='product'>
              <span class="checkmark"></span>
              <p>Вещь</p>
            </label>

          </div>

          <div class="modal_filter__list__item modal_filter__list__item__time_meeting">
            <p class="modal_filter__item__title">Готов к встрече:</p>

            <label class="container js_on">
              <input type="checkbox" name='filter_time_meeting[]' value='online'>
              <span class="checkmark"></span>
              <p>Виртуально</p>
            </label>

            <label class="container js_on">
              <input type="checkbox" name='filter_time_meeting[]' value='offline'>
              <span class="checkmark"></span>
              <p>Лично</p>
            </label>

          </div>

          <div class="modal_filter__list__item modal_filter__list__item__town">
            <p class="modal_filter__item__title">Город:</p>

            <div class="modal_filter__list__item__find_town">
              <input type="text" name='filter_town' id='filter_town' placeholder="Воронеж...">
            </div>

          </div>


        </div>

      </div>
    </div>

    <div class="filter_p">
      <div class="default_up_filter cute_filters">
        <div class="default_up_filter__state">
          <label class="js_on">
            <input type="checkbox" name='filter_state[]' value="give">
            <p>Получить</p>
          </label>

          <label class="js_on">
            <input type="checkbox" name='filter_state[]' value="take">
            <p>Отдать</p>
          </label>
        </div>
        <div class="default_up_filter__type">
          <label class="js_on">
            <input type="checkbox" name='filter_type[]' value='post'>
            <p>Знания</p>
          </label>

          <label class="js_on">
            <input type="checkbox" name='filter_type[]' value='gift'>
            <p>Подарки</p>
          </label>

          <label class="js_on">
            <input type="checkbox" name='filter_type[]' value='time'>
            <p>Время</p>
          </label>
          <label class="js_on">
            <input type="checkbox" name='filter_type[]' value='service'>
            <p>Услуги</p>
          </label>

        </div>

        <div class="default_up_filter__gift__wrapper" id='default-filter--gift-type'>
          <div class="default_up_filter__gift">

            <label class="js_on">
              <input type="checkbox" name='gift_type[]' value='money'>
              <p>Деньги</p>
            </label>

            <label class="js_on">
              <input type="checkbox" name='gift_type[]' value='product'>
              <p>Вещь</p>
            </label>
          </div>

        </div>
      </div>

      <div id='show_filter'>
        <p>Расширенный фильтр</p>
        <div class="filter_icon"></div>
      </div>

    </div>

    <?php
      }


      function filter_empty_tax($var)
      {
        $var = trim($var);
        return mb_strlen($var) > 1 ? true : false;
      }


      //создаём шорткод для первой статьи
      function shc_firts_post($atts)
      {

        $ups_post = get_option('sticky_posts');
        if (!isset($ups_post[0])) {
          return;
        }
        foreach ($ups_post as $post_id) {
          ob_start();
          $has_teory = get_post_meta($post_id, $key = 'has_this_post_teory', $single = true);
          $has_practice = get_post_meta($post_id, $key = 'has_this_post_homework', $single = true);


          //проверяем показывать как теорию или как практику
          $this_practice = 0;
          if ($has_teory === '0' && $has_practice == 1) {
            $this_practice = 1;
          }
          $post_now = get_post($post_id, ARRAY_A);
    ?><article id="main_post2 post-<?php echo $post_id; ?>" <?php post_class(); ?>>

        <div class="container_l cart_stat main_stat">
          <a href="<?php the_permalink(); ?>">
            <?php
            $sfer;
            if ($this_practice == 1) {
              $sfer = get_post_meta($post_id, $key = 'sfer_practice', $single = true);
              $sfer = explode(',', $sfer);
            } else {
              $sfer  = wp_get_post_terms(get_the_ID(), 'sfera', array('fields' => 'names'));
            }
            echo_cute_sfer($sfer[0], $sfer[0], 'sfer--up'); ?>
            <div class='content_cart'>

              <div class="content_cart_left">
                <div class="cont_img">
                  <?php
                  $author_avatar =  get_avatar_url(get_the_author_meta('ID'));
                  if ($author_avatar && !strripos($author_avatar, 'default')) {
                    echo "<img src='{$author_avatar}' alt='author'>";
                  } else {
                    the_post_thumbnail();
                  }
                  ?>
                </div>
              </div>

              <div class="content_cart_right">
                <div class="content_cart__up__text">
                  <header class="entry-header">
                    <h2 class="title-post entry-title"><?php
                                                        if ($this_practice) {
                                                          echo 'Практика. ';
                                                        }
                                                        if (get_the_author() != 'admin') {
                                                          echo get_the_author() . '. ' .  get_the_title();
                                                        } else {
                                                          echo get_the_title();
                                                        }

                                                        ?></h2>
                  </header>
                </div>

                <div class="naviks_and_problems">
                  <div class="naviks">
                    <?php
                    $navik_list;
                    if ($this_practice) {
                      $navik_list = get_post_meta($post_id, $key = 'navik_practice', $single = true);
                      $navik_list = explode(',', $navik_list);
                    } else {
                      $navik_list = wp_get_post_terms(get_the_ID(), 'navik', array('fields' => 'names'));
                    }
                    if (count($navik_list)) {
                    ?>
                      <div class="naviks_lent">
                        <?php foreach ($navik_list as $key => $name) {
                        ?>

                          <div class="navik">
                            <p><?php echo $name; ?></p>
                          </div>

                        <?php
                        } ?>
                      </div>
                      <?php
                    }
                    if (!$this_practice) {
                      $navik_list_practice = get_post_meta($post_id, $key = 'navik_practice', $single = true);
                      $navik_list_practice = explode(',', $navik_list_practice);
                      if (count($navik_list_practice) && $navik_list_practice[0] != '') {
                      ?>
                        <div class="naviks_lent">
                          <?php foreach ($navik_list_practice as $key => $name) {
                          ?>

                            <div class="navik">
                              <p><?php echo $name; ?></p>
                            </div>

                          <?php
                          } ?>
                        </div>
                    <?php
                      }
                    }
                    ?>
                  </div>
                  <div class="propblems">
                    <?php
                    $problem_list;
                    if ($this_practice) {
                      $problem_list = get_post_meta($post_id, $key = 'problem_practice', $single = true);
                      $problem_list = explode(',', $problem_list);
                    } else {
                      $problem_list = wp_get_post_terms(get_the_ID(), 'problem', array('fields' => 'names'));
                    }
                    if (count($problem_list)) {
                    ?>
                      <div class="naviks_lent">
                        <?php foreach ($problem_list as $key => $name) {
                        ?>

                          <div class="navik ploblem_stat">
                            <p><?php echo $name; ?></p>
                          </div>

                        <?php
                        } ?>
                      </div>
                      <?php

                    }
                    if (!$this_practice) {
                      $problem_list_practice = get_post_meta($post_id, $key = 'problem_practice', $single = true);
                      $problem_list_practice = explode(',', $problem_list_practice);
                      if (count($problem_list_practice) && $problem_list_practice[0] != '') {
                      ?>
                        <div class="naviks_lent">
                          <?php foreach ($problem_list_practice as $key => $name) {
                          ?>

                            <div class="navik ploblem_stat">
                              <p><?php echo $name; ?></p>
                            </div>

                          <?php
                          } ?>
                        </div>
                    <?php
                      }
                    }
                    ?>
                  </div>
                </div>

              </div>


            </div>
          </a>
          <div class='stat_read_post_cart'>
            <div>
              <span><img src="<?php echo get_template_directory_uri(); ?>/images/person.svg" title='Прочитали' alt="Просмотрено"></span>
              <span><?php
                    $user_scroll = get_post_meta(get_the_ID(), $key = 'users_scroll')[0];
                    if (!isset($user_scroll)) {
                      $user_scroll = 0;
                    }
                    echo $user_scroll;

                    ?></span>
              <span><img src="<?php echo get_template_directory_uri(); ?>/images/money.svg" title='Купили' alt="Добавлено в паспорт"></span>
              <span><?php
                    $users_buy = get_post_meta(get_the_ID(), $key = 'users_buy')[0];
                    if (!isset($users_buy)) {
                      $users_buy = 0;
                    }
                    echo $users_buy;
                    ?></span>
              <?php
              $video_dur = get_post_meta($post_id, $key = 'youtube_status_vidio', $single = true);
              if ($video_dur) {
                if ($video_dur == 'long') {
              ?>
                  <span><img src="<?php echo get_template_directory_uri(); ?>/images/youtube_long.svg" title='Длинное видео' alt="Длинное видео"></span>
                <?php
                } else if ($video_dur == 'short') {
                ?>
                  <span><img src="<?php echo get_template_directory_uri(); ?>/images/youtube_short.svg" title='Короткое видео' alt="Короткое видео"></span>
              <?php
                }
              }
              ?>
            </div>
            <p class='price_cart'><?php
                                  if (get_post_meta(get_the_id(), 'post_homework_price')[0] == '0') {
                                    echo 'БЕСПЛАТНО';
                                  } else {
                                    echo get_post_meta(get_the_id(), 'post_homework_price')[0] . 'P';
                                  }
                                  ?></p>
          </div>
        </div>

      </article><!-- #post-## --><?php
                                  $content .= ob_get_contents();
                                  ob_end_clean();
                                }
                                return $content;
                              }

                              add_action('see_also3', 'see_also3');

                              function see_also3()
                              {
                                  ?>
    <p class='see_more_p'>Изучите ещё больше:</p>
    <?php
                                $post_id = get_the_ID();
                                $naviks = wp_get_post_terms($post_id, 'navik', array('fields' => 'ids'));
                                wp_reset_postdata();

                                $arr_in_see_also = array();
                                query_posts([
                                  'tax_query' => [
                                    [
                                      'taxonomy' => 'navik',
                                      'field'    => 'id',
                                      'terms'    => $naviks,
                                    ],
                                  ],
                                ]);

                                if (have_posts()) :
                                  while (have_posts()): the_post();
                                    if (get_the_id() == $post_id) {
                                      continue;
                                    }



                                    $author_id = get_post_field('post_author', get_the_id());
                                    if (is_company($author_id)) {

                                      $is_open = get_post_meta(get_the_id(), 'post_open_status', true);

                                      $company = new Company($author_id);

                                      if ((!is_user_logged_in() || (!$company->check_user_in_members(get_current_user_id()) && $author_id != get_current_user_id()))
                                        && $is_open !== 'open'
                                      ) {
                                        continue;
                                      }
                                    }

                                    array_push($arr_in_see_also, get_the_id());
                                    get_template_part('contant_rul', get_post_format());
                                    if (count($arr_in_see_also) == 3) {
                                      break;
                                    }


                                  endwhile;

                                endif;
                                wp_reset_query();

                                if (3 - count($arr_in_see_also) > 0) {
                                  wp_reset_postdata();
                                  query_posts(array(
                                    'posts_per_page' => -1,
                                    'orderby' => 'rand(' . rand() . ')',
                                  ));

                                  if (have_posts()) :

                                    while (have_posts()): the_post();
                                      if (get_the_id() == $post_id) {
                                        continue;
                                      }

                                      $author_id = get_post_field('post_author', get_the_id());
                                      if (is_company($author_id)) {

                                        $is_open = get_post_meta(get_the_id(), 'post_open_status', true);

                                        $company = new Company($author_id);

                                        if ((!is_user_logged_in() || (!$company->check_user_in_members(get_current_user_id()) && $author_id != get_current_user_id()))
                                          && $is_open !== 'open'
                                        ) {
                                          continue;
                                        }
                                      }

                                      if (in_array(get_the_id(), $arr_in_see_also)) {
                                        continue;
                                      }
                                      array_push($arr_in_see_also, get_the_id());
                                      get_template_part('contant_rul', get_post_format());
                                      if (count($arr_in_see_also) == 3) {
                                        break;
                                      }


                                    endwhile;

                                  endif;


                                  wp_reset_query();
                                }
                              }

                              add_action('sydney_before_header', 'modal_reg_teacher');

                              function modal_reg_teacher()
                              {
                                if (um_profile_id() == get_current_user_id()) {

                                  $terms = get_terms([
                                    'taxonomy' => ['sfera'],
                                    'hide_empty' => false,
                                  ]);
    ?>
      <div class="modal_reg" id='modal_reg_as_teacher'>
        <div class="bg"></div>
        <div class="modal_reg_main">
          <div class="" id="error_modal"></div>
          <p>Пожалуйста, выберете в какой сфере будете учить:</p>
          <div class="modal_sfers">
            <?php
                                  foreach ($terms as $sfer) {
            ?>
              <label for="">
                <p><?php echo $sfer->name; ?></p>
                <input type="radio" name='sfer' id=<?php echo 'sfer_' . $sfer->name; ?> value=<?php echo $sfer->name; ?>>
              </label>
            <?php
                                  }
            ?>
          </div>
          <div id="btn_end_teacher_reg">Завершить регестрацию</div>
        </div>
      </div>
    <?php
                                }
                              }

                              //ajax для добавления роли наставника
                              add_action('wp_ajax_reg_as_teacher', 'ajax_add_role_teacher');
                              add_action('wp_ajax_nopriv_reg_as_teacher', 'ajax_add_role_teacher');

                              function ajax_add_role_teacher()
                              {
                                $sfer = $_POST['sfer'];
                                $u_id = $_POST['user_id'];

                                $addMemberToGroup = new WP_User($u_id);
                                $addMemberToGroup->add_role('teacher');

                                if (is_wp_error($addMemberToGroup)) {
                                  echo 'произошла ошибка';
                                  echo $user_id;
                                } else {
                                  echo 'Теперь Вы наставник!';
                                  update_user_meta($u_id, 'sfer_work', $sfer);
                                }
                                wp_die();
                              }


                              add_action('modal_error_add_post', 'modal_error_add_post');
                              function modal_error_add_post()
                              {
    ?>
    <div id="modal_error_add_post">
      <div class="bg"></div>
      <div class="content"></div>
    </div>
  <?php
                              }

                              function get_user_status($user_id)
                              {
                                $learn = get_user_meta($user_id, 'last_learn', true);
                                $teach = get_user_meta($user_id, 'last_teach', true);


                                $status = '';

                                if ($teach && time() - $teach < 60 * 60 * 24 * 30) {
                                  $status .= 'Наставник ';
                                }
                                if ($learn && time() - $learn < 60 * 60 * 24 * 30) {
                                  $status .= 'Учится ';
                                }

                                if ($status == '') {
                                  $status = 'Не активен';
                                }

                                return $status;
                              }

                              function get_post_all_navik($post, $count)
                              {
                                $ans_arr = array();

                                $naviks_practice = explode(",", get_post_meta($post->ID, "navik_practice", true));
                                $naviks_teor = wp_get_post_terms($post->ID, 'navik');

                                //навыки
                                foreach ($naviks_practice as $navik) {
                                  if (!$navik) {
                                    continue;
                                  }

                                  if (isset($ans_arr[mb_strtolower(trim($navik))])) {
                                    $ans_arr[mb_strtolower(trim($navik))] += 1;
                                  } else {
                                    $ans_arr[mb_strtolower(trim($navik))] = 1;
                                  }
                                }

                                foreach ($naviks_teor as $navik) {
                                  if (!$navik) {
                                    continue;
                                  }
                                  if (isset($ans_arr[mb_strtolower(trim($navik->name))])) {
                                    $ans_arr[mb_strtolower(trim($navik->name))] += 1;
                                  } else {
                                    $ans_arr[mb_strtolower(trim($navik->name))] = 1;
                                  }
                                }

                                arsort($ans_arr);

                                return array_slice($ans_arr, 0, $count);
                              }

                              function get_post_all_problems($post, $count)
                              {
                                $ans_arr = array();

                                $problems_practice = explode(",", get_post_meta($post->ID, "problem_practice", true));
                                $problems_teor = wp_get_post_terms($post->ID, 'problem');


                                //проблемы
                                foreach ($problems_practice as $problem) {
                                  if (!$problem) {
                                    continue;
                                  }
                                  if (isset($ans_arr[mb_strtolower(trim($problem))])) {
                                    $ans_arr[mb_strtolower(trim($problem))] += 1;
                                  } else {
                                    $ans_arr[mb_strtolower(trim($problem))] = 1;
                                  }
                                }

                                foreach ($problems_teor as $problem) {
                                  if (!$problem) {
                                    continue;
                                  }
                                  if (isset($ans_arr[mb_strtolower(trim($problem->name))])) {
                                    $ans_arr[mb_strtolower(trim($problem->name))] += 1;
                                  } else {
                                    $ans_arr[mb_strtolower(trim($problem->name))] = 1;
                                  }
                                }

                                arsort($ans_arr);

                                return array_slice($ans_arr, 0, $count);
                              }

                              function get_user_link_name($user_id)
                              {
                                $user = get_user_by('id', $user_id);
                                $link = 'http://' . $_SERVER['SERVER_NAME'] . '/user/' . $user->user_login;
                                $name;

                                if ($user->first_name && $user->last_name) {
                                  $name = $user->first_name . " " . $user->last_name;
                                } else if ($user->display_name) {
                                  $name = $user->display_name;
                                } else if ($user->user_nicename) {
                                  $name = $user->user_nicename;
                                } else {
                                  $name = $user->login;
                                }

                                return "<a href='" . $link . "'>" . $name . "</a>";
                              }

                              function get_post_link_title($post_id)
                              {
                                $title = get_the_title($post_id);
                                $link = get_the_permalink($post_id);
                                return "<a href='" . $link . "'>" . $title . "</a>";
                              }


                              function simple_encode(string $data): string
                              {
                                $ivlen = openssl_cipher_iv_length($cipher = "AES-128-CBC");
                                $iv = openssl_random_pseudo_bytes($ivlen);
                                $ciphertext_raw = openssl_encrypt($data, $cipher, SEKRET_KEY, $options = OPENSSL_RAW_DATA, $iv);
                                $hmac = hash_hmac('sha256', $ciphertext_raw, SEKRET_KEY, $as_binary = true);
                                $ciphertext = base64_encode($iv . $hmac . $ciphertext_raw);
                                return $ciphertext;
                              }

                              function simple_decode(string $encode)
                              {
                                $encode = str_replace(" ", "+", $encode);
                                $c = base64_decode($encode);
                                $ivlen = openssl_cipher_iv_length($cipher = "AES-128-CBC");
                                $iv = substr($c, 0, $ivlen);
                                $hmac = substr($c, $ivlen, $sha2len = 32);
                                $ciphertext_raw = substr($c, $ivlen + $sha2len);
                                $plaintext = openssl_decrypt($ciphertext_raw, $cipher, SEKRET_KEY, $options = OPENSSL_RAW_DATA, $iv);
                                $calcmac = hash_hmac('sha256', $ciphertext_raw, SEKRET_KEY, $as_binary = true);
                                if (hash_equals($hmac, $calcmac)) {
                                  return $plaintext;
                                }
                                return false;
                              }



                              /**
                               * Replace login page URL to UM login page
                               * @param string $login_url
                               * @param string $redirect
                               * @return string
                               */
                              function um_login_url($login_url, $redirect)
                              {
                                $page_id = UM()->options()->get('core_login');
                                if (get_post($page_id)) {
                                  $login_url = add_query_arg('redirect_to', urlencode($redirect), get_permalink($page_id));
                                }
                                return $login_url;
                              }
                              add_filter('login_url', 'um_login_url', 10, 2);


                              //тут в зависимости есть ли что-то в сессии для редиректа будет редиректить
                              add_action('um_registration_complete', 'redirect_to_bonus_page_after_registration', 1);
                              function redirect_to_bonus_page_after_registration($user_id)
                              {
                                um_fetch_user($user_id);

                                //объявляем переменную редиректа
                                $redirect;
                                if (isset($_SESSION['bonus_redirect'])) {
                                  $redirect = $_SESSION['bonus_redirect'];
                                }

                                UM()->user()->auto_login($user_id);
                                if ($redirect) {
                                  wp_redirect($redirect);
                                }
                                exit;
                              }


                              //для работы с сессиями
                              add_action('init', 'myStartSession', 1);
                              function myStartSession()
                              {
                                if (!session_id()) {
                                  session_start();
                                }
                              }
                              add_action('wp_logout', 'myEndSession');
                              add_action('wp_login', 'myEndSession');
                              function myEndSession()
                              {
                                session_destroy();
                              }


                              //тут реализуем проверку, что если посльзователь начал ходить по обынчым страницам после посещения бонусной секретной, то 
                              //убираем из сессии редирект
                              add_action('sydney_before_site', 'check_bonus_session');

                              function check_bonus_session()
                              {

                                if (get_current_user_id() > 0) {
                                  return;
                                }

                                //массив страниц которые не должны сбрасывать сессию
                                $white_list_url = ["stranica-dlya-polucheniya-ochkov-bonusa", "login", "register"];

                                $cur_url = get_permalink();
                                $cur_url = str_replace(get_site_url(), '', $cur_url);
                                $cur_url = str_replace('/', '', $cur_url);

                                if (!in_array($cur_url, $white_list_url)) {
                                  unset($_SESSION['bonus_redirect']);
                                }
                              }

                              //вспомогательная функция, которая добавляет в метополе для юзера или поста значение в массив

                              function add_in_meta_array($id, $meta_key, $value, $type)
                              {
                                $arr;

                                if ($type == 'post') {
                                  $arr = get_post_meta($id, $meta_key, true);
                                } elseif ($type == 'user') {
                                  $arr = get_user_meta($id, $meta_key, true);
                                }

                                if (!is_array($arr)) {
                                  $arr = array();
                                }

                                array_push($arr, $value);

                                if ($type == 'post') {
                                  update_post_meta($id, $meta_key, $arr);
                                } elseif ($type == 'user') {
                                  update_user_meta($id, $meta_key, $arr);
                                }
                              }

                              function input_take_or_give()
                              {
  ?>
    <div class="take_or_give">
      <label for="lesson_cart_state_give">
        <input type="radio" name="lesson_cart_state" id="lesson_cart_state_give" value='give'>
        <p>Отдать</p>
      </label>
      <label for="lesson_cart_state_take">
        <input type="radio" name="lesson_cart_state" id="lesson_cart_state_take" value='take'>
        <p>Получить</p>
      </label>
    </div>
  <?php
                              }


                              /**
                               * Красиво выводит сферу
                               * 
                               * @param $sfer - сама сфера
                               * @param $text - текст который будет показываться
                               * @param $classes - дополнительные классы
                               */
                              function echo_cute_sfer($sfer, $text = '', $classes = '')
                              {
                                if (!$text) {
                                  $text = $sfer;
                                }

                                $sfer = mb_strtolower($sfer);

                                $sfer_classes = [
                                  'материальное' => 'sfer--green',
                                  'духовное'     => 'sfer--blue',
                                  'отношения'    => 'sfer--red',
                                  'здоровье'     => 'sfer--yellow',
                                ];

                                $final_classes = implode(' ', array_filter(['label-sfera', $classes, $sfer_classes[$sfer]]));

                                echo "<div class='$final_classes'><p>$text</p></div>";
                              }
  ?>