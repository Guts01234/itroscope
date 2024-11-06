<?php
//Template name: main page
get_header(); ?>

<?php do_action('sydney_before_content'); ?>

<script>
  var isUserLogged = <?php echo get_current_user_id() > 0 ? 'true' : 'false' ?>
</script>
<div id="primary" class="content-area main_page">
  <main id="main" class="post-wrap" role="main">


    <?php if (have_posts()) :  //для вывода заголовка
    ?>
      <?php while (have_posts()) : the_post(); ?>
        <?php
        get_template_part('content', get_post_format());
        ?>
      <?php endwhile; ?>

      <?php the_content(); //сам контент
      ?>

    <?php else : ?>

      <?php get_template_part('content', 'none'); ?>

    <?php endif; ?>
    <?php
    do_action('filter_mainpage');
    echo do_shortcode('[first_post]');
    ?>
    <div class="loader_mainpage"></div>
    <div class='post_list_kw'>
      <?php
      //подсчёт навыков практики
      $res = $wpdb->get_results("SELECT `meta_value` FROM `{$wpdb->prefix}postmeta` WHERE `meta_key` = 'navik_practice'", 'ARRAY_A');
      $arr = array();
      foreach ($res as $key => $value) {
        $nav =  $value['meta_value'];
        if (!$nav) {
          continue;
        }
        if (stripos($nav, ',')) {
          $nav_arr = explode(',', $nav);

          foreach ($nav_arr as $nav_item) {
            array_push($arr, strtolower(trim($nav_item)));
          }
        } else {
          array_push($arr, strtolower(trim($nav)));
        }
      }
      $arr = array_unique($arr);
      ?>
      <script type="text/javascript">
        var count_lessons = <?php $count = wp_count_posts();
                            echo $count->publish;  ?>;
        var count_naviks = <?php echo wp_count_terms(['taxonomy' => 'navik', 'hide_empty' => true]); ?>;
        var naviks_practise = <?php echo count($arr) ?>;
        count_naviks += naviks_practise;
        $('.count_lessons').text(count_lessons);
        $('.count_naviks').text(count_naviks);
      </script>

      <?php //лента

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

      query_posts(
        array(
          'post_type' => array('post', 'time', 'gift', 'service'),
          'post__in' => $allTheIDs,
          'orderby' => 'rand(' . rand() . ')',
          'posts_per_page' => 12,
          'paged' => 1,
          'meta_query' => array(
            'relation' => 'AND',
            array(
              'key' => 'is_archive_post',
              'value' => 'true',
              'compare' => 'NOT EXISTS',
            ),
          )
        )
      );

      while (have_posts()) : the_post();
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
      global $wp_query;
      ?>


      <?php if ($wp_query->max_num_pages > 1) : ?>
        <script>
          var ajaxurl = '<?php echo site_url() ?>/wp-admin/admin-ajax.php';
          var true_posts = '<?php echo serialize($wp_query->query_vars); ?>';
          var current_page = <?php echo (get_query_var('paged')) ? get_query_var('paged') : 1; ?>;
          var max_pages = '<?php echo $wp_query->max_num_pages; ?>';
        </script>
      <?php endif; ?>
    </div>
    <?php if ($wp_query->max_num_pages > 1) : ?>
      <div id="true_loadmore">Загрузить ещё</div>
    <?php endif; ?>

  </main><!-- #main -->
</div><!-- #primary -->

<?php do_action('sydney_after_content'); ?>

<?php get_footer(); ?>