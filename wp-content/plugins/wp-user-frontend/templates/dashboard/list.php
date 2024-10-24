<div class="items-table-container">
    <?php 
    if(is_company(um_profile_id())){

        $company = new Company(um_profile_id());
    ?>
    <table class="items-table <?php echo implode( ' ', array_map('esc_attr', $post_type ) ); ?>">
        <thead>
            <tr class="items-list-header">

                <th>Урок</th>
                <th>Навыки урока</th>
                <th>Проблемы урока</th>
                <?php 
                    if(um_profile_id() == get_current_user_id()){
                        ?>
                            <th><?php esc_html_e( 'Опции', 'wp-user-frontend' ); ?></th>
                        <?php
                    }
                ?>

                <?php do_action( 'wpuf_account_posts_head_col', $args ); ?>

            </tr>
        </thead>
        <tbody>
            <?php
                global $post;

                while ( $dashboard_query->have_posts() ) {
                    $dashboard_query->the_post();
                    $show_link        = ! in_array( $post->post_status, ['draft', 'future', 'pending'] );
                    $payment_status   = get_post_meta( $post->ID, '_wpuf_payment_status', true );
            ?>
            <tr>
                <td data-label="<?php esc_attr_e( 'Title: ', 'wp-user-frontend' ); ?>" class="<?php echo 'on' === $featured_img ? 'data-column' : '' ; ?>">
                    <?php $is_open = get_post_meta(get_the_id(), 'post_open_status', true);?>
                    <?php if ( ! $company->check_user_in_members(get_current_user_id())  && get_current_user_id() != um_profile_id() && $is_open !== 'open') { ?>

                        <?php echo wp_trim_words( get_the_title(), 5 ); ?>

                    <?php } else { ?>

                        <a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'wp-user-frontend' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php echo wp_trim_words( get_the_title(), 5 ); ?></a>

                    <?php } ?>
                    <?php if ( 'on' !== $featured_img ) { ?>
                        <span class="post-edit-icon">
                            &#x25BE;
                        </span>
                    <?php } ?>
                </td>
                <td data-label="naviks" class="data-column">
                    <?php 
                        $naviks = get_post_all_navik($post, 3);

                        foreach ($naviks as $navik => $value) {
                            echo "<span class='cute_navik'>" . $navik . "</span> ";
                        }
                    ?>
                </td>
                <td data-label="problems" class="data-column">
                     <?php 
                        $problems = get_post_all_problems($post, 3);

                        foreach ($problems as $promlem => $value) {
                            echo "<span class='cute_problem'>" . $promlem . "</span> ";
                        }
                    ?>
                </td>

                <?php
                do_action( 'wpuf_account_posts_row_col', $args, $post );

                if ( 'on' === $enable_payment && 'off' != $payment_column ) {
                    echo '<td data-label="' . esc_attr( 'Payment: ' ) . '" class="data-column">';

                    if ( empty( $payment_status ) ) {
                        esc_html_e( 'Not Applicable', 'wp-user-frontend' );
                    } elseif ( $payment_status !== 'completed' ) {
                        echo '<a href="' . esc_attr( trailingslashit( get_permalink( wpuf_get_option( 'payment_page', 'wpuf_payment' ) ) ) ) . '?action=wpuf_pay&type=post&post_id=' . esc_attr( $post->ID ) . '">' . esc_html__( 'Pay Now', 'wp-user-frontend' ) . '</a>';
                    } elseif ( 'completed' === $payment_status ) {
                        esc_html_e( 'Completed', 'wp-user-frontend' );
                    }

                    echo '</td>';
                }
                ?>
                    <?php 
                    if(um_profile_id() == get_current_user_id()){
                        ?>
                            <td data-label="<?php esc_attr_e( 'Options: ', 'wp-user-frontend' ); ?>" class="data-column">
                            <?php
                            if ( wpuf_is_post_editable( $post ) ) {
                                $edit_page = (int) wpuf_get_option( 'edit_page_id', 'wpuf_frontend_posting' );
                                $url = add_query_arg( [ 'pid' => $post->ID ], get_permalink( $edit_page ) );
                                ?>
                                <a class="wpuf-posts-options wpuf-posts-edit" href="<?php echo esc_url( wp_nonce_url( $url, 'wpuf_edit' ) ); ?>">
                                    <img src="<?php echo WPUF_ASSET_URI . '/images/edit.svg'; ?>" alt="Edit">
                                </a>
                                <?php
                                }
                             ?>

                            <?php
                            if ( 'yes' === wpuf_get_option( 'enable_post_del', 'wpuf_dashboard', 'yes' ) ) {
                                $del_url = add_query_arg( ['action' => 'del', 'pid' => $post->ID] );
                                $message = __( 'Are you sure to delete?', 'wp-user-frontend' ); ?>
                                <a class="wpuf-posts-options wpuf-posts-delete" style="color: red;" href="<?php echo esc_url_raw( wp_nonce_url( $del_url, 'wpuf_del' ) ); ?>" onclick="return confirm('<?php echo esc_attr( $message ); ?>');">
                                    <img src="<?php echo WPUF_ASSET_URI . '/images/trash.svg'; ?>" alt="Delete">
                                </a>
                            <?php
                            } ?>
                        </td>
                        <?php
                    }
                ?>
                        
                    </tr>
                <?php
                }

            wp_reset_postdata();
        ?>
        </tbody>
    </table>
    <?php
    }else{
        ?>
        <table class="items-table <?php echo implode( ' ', array_map('esc_attr', $post_type ) ); ?>">
        <thead>
            <tr class="items-list-header">
                <?php
                if ( 'on' === $featured_img ) {
                    echo wp_kses_post( '<th>' . __( 'Featured Image', 'wp-user-frontend' ) . '</th>' );
                }
                ?>
                <th id='lesson_title'><?php esc_html_e( 'Урок', 'wp-user-frontend' ); ?></th>
                <th><?php esc_html_e( 'Статус', 'wp-user-frontend' ); ?></th>
                <th>Задание</th>
                <th>Сколько раз прошли теорию</th>
                <th>Сколько раз сделали задание</th>
                <th>Наставник</th>

                <?php do_action( 'wpuf_account_posts_head_col', $args ); ?>

                <?php if ( 'on' === $enable_payment && 'off' !== $payment_column ) { ?>
                    <th><?php esc_html_e( 'Payment', 'wp-user-frontend' ); ?></th>
                <?php } ?>

                <th><?php esc_html_e( 'Опции', 'wp-user-frontend' ); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php
                global $post;

                while ( $dashboard_query->have_posts() ) {
                    $dashboard_query->the_post();
                    $show_link        = ! in_array( $post->post_status, ['draft', 'future', 'pending'] );
                    $payment_status   = get_post_meta( $post->ID, '_wpuf_payment_status', true );
                    $cart_state = get_post_meta($post->ID, 'cart_state', true);
                    if($cart_state == 'take'){
                        continue;
                    }
            ?>
            <tr>
                <?php if ( 'on' === $featured_img ) { ?>
                    <td data-label="<?php esc_attr_e( 'Featured Image: ', 'wp-user-frontend' ); ?>">
                    <?php
                        echo $show_link ? wp_kses_post( '<a href="' . get_permalink( $post->ID ) . '">' ) : '';

                        if ( has_post_thumbnail() ) {
                            the_post_thumbnail( $featured_img_size );
                        } else {
                            printf( '<img src="%1$s" class="attachment-thumbnail wp-post-image" alt="%2$s" title="%2$s" />', esc_attr( apply_filters( 'wpuf_no_image', plugins_url( '../assets/images/no-image.png', __DIR__ ) ) ), esc_html( __( 'No Image', 'wp-user-frontend' ) ) );
                        }

                        echo $show_link ? '</a>' : '';
                    ?>
                        <span class="post-edit-icon">
                            &#x25BE;
                        </span>
                    </td>
                <?php } ?>
                <td data-label="<?php esc_attr_e( 'Title: ', 'wp-user-frontend' ); ?>" class="<?php echo 'on' === $featured_img ? 'data-column' : 'title_column' ; ?>">
                    <?php if ( ! $show_link ) { ?>

                        <!-- <?php //echo wp_trim_words( get_the_title(), 5 ); ?> -->
                        <?php echo get_the_title(); ?>

                    <?php } else { ?>

                        <a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'wp-user-frontend' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php echo get_the_title() ?></a>
                        <!-- <a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'wp-user-frontend' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php echo wp_trim_words( get_the_title(), 5 ); ?></a> -->

                    <?php } ?>
                    <?php if ( 'on' !== $featured_img ) { ?>
                        <span class="post-edit-icon">
                            &#x25BE;
                        </span>
                    <?php } ?>
                </td>
                <td data-label="<?php esc_attr_e( 'Status: ', 'wp-user-frontend' ); ?>" class="data-column">
                    <?php wpuf_show_post_status( $post->post_status ); ?>
                </td>
                <td data-label="<?php esc_attr_e( 'Практика: ', 'wp-user-frontend' ); ?>" class="data-column">
                  <?php
                    $has_practice = get_post_meta(get_the_ID(), $key = 'has_this_post_homework', $single = true);
                    if($has_practice == 1){
                      echo 'Есть';
                    }else{
                      echo 'Нет';
                    }
                  ?>
                </td>
                <td data-label="" class="data-column">
                    <?php 
                        $teor = get_post_meta( get_the_ID(), $key = 'users_buy', true);
                        if(!$teor){
                            $teor = 0;
                        }
                        echo $teor;
                    ?>
                </td>
                <td data-label="" class="data-column">
                    <?php 
                        $users_done_practice = get_post_meta( get_the_ID(), $key = 'users_done_practice', true);
                        if(!$users_done_practice){
                            $users_done_practice = 0;
                        }
                        echo $users_done_practice;
                    ?>
                </td>
                <td data-label="" class="data-column">
                    <?php 
                        do_action("post_teachers", get_the_ID())
                    ?>
                </td>

                <?php
                do_action( 'wpuf_account_posts_row_col', $args, $post );

                if ( 'on' === $enable_payment && 'off' != $payment_column ) {
                    echo '<td data-label="' . esc_attr( 'Payment: ' ) . '" class="data-column">';

                    if ( empty( $payment_status ) ) {
                        esc_html_e( 'Not Applicable', 'wp-user-frontend' );
                    } elseif ( $payment_status !== 'completed' ) {
                        echo '<a href="' . esc_attr( trailingslashit( get_permalink( wpuf_get_option( 'payment_page', 'wpuf_payment' ) ) ) ) . '?action=wpuf_pay&type=post&post_id=' . esc_attr( $post->ID ) . '">' . esc_html__( 'Pay Now', 'wp-user-frontend' ) . '</a>';
                    } elseif ( 'completed' === $payment_status ) {
                        esc_html_e( 'Completed', 'wp-user-frontend' );
                    }

                    echo '</td>';
                }
                ?>

                        <td data-label="<?php esc_attr_e( 'Options: ', 'wp-user-frontend' ); ?>" class="data-column">
                            <?php
                            if ( wpuf_is_post_editable( $post ) ) {
                                $edit_page = (int) wpuf_get_option( 'edit_page_id', 'wpuf_frontend_posting' );
                                $url = add_query_arg( [ 'pid' => $post->ID ], get_permalink( $edit_page ) );
                                ?>
                                <a class="wpuf-posts-options wpuf-posts-edit" href="<?php echo esc_url( wp_nonce_url( $url, 'wpuf_edit' ) ); ?>">
                                    <img src="<?php echo WPUF_ASSET_URI . '/images/edit.svg'; ?>" alt="Edit">
                                </a>
                                <?php
                                }
                             ?>

                            <?php
                            if ( 'yes' === wpuf_get_option( 'enable_post_del', 'wpuf_dashboard', 'yes' ) ) {
                                $del_url = add_query_arg( ['action' => 'del', 'pid' => $post->ID] );
                                $message = __( 'Are you sure to delete?', 'wp-user-frontend' ); ?>
                                <a class="wpuf-posts-options wpuf-posts-delete" style="color: red;" href="<?php echo esc_url_raw( wp_nonce_url( $del_url, 'wpuf_del' ) ); ?>" onclick="return confirm('<?php echo esc_attr( $message ); ?>');">
                                    <img src="<?php echo WPUF_ASSET_URI . '/images/trash.svg'; ?>" alt="Delete">
                                </a>
                            <?php
                            } ?>
                        </td>
                    </tr>
                <?php
                }

            wp_reset_postdata();
        ?>
        </tbody>
    </table>
    <style>
        #primary{
            width: 100%;
        }
        div.um-13.um .um-profile-body{
            max-width: none;
        }
        body div.wpuf-dashboard-container{
            max-width: none;
        }
        #lesson_title, .title_column{
            min-width: 280px;
        }
        .title_column, .title_column a{
            font-size: 14px;
        }
    </style>
        <?php
    }
    ?>
</div>
