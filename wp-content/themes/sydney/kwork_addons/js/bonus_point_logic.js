(function ($) {
    //проверка надоли платить
    if (bonus_need_pay) {
        $.ajax({
            url: site_url_header + '/wp-admin/admin-ajax.php',
            type: 'POST',
            data: {
                action: 'homework_payment',
                user_id: bonus_user_id,
                post_id: bonus_post_id,
                is_practice: bonus_this_practice,
                is_crypt: true,
            },
            success: function (data) {
                $('#place_for_link').html('<a href="' + data + '">Оплатить</a>');
            },
            error: function (data) {
                data = JSON.stringify(data);
                console.log('error' + data);
                $('#place_for_link').html(
                    'Не получилось получить доступ к серверу. Пожалуйста, повторите позже',
                );
            },
        });
    } else {
        $.ajax({
            url: site_url_header + '/wp-admin/admin-ajax.php',
            data: {
                action: 'get_free',
                user_id: bonus_user_id,
                post_id: bonus_post_id,
                is_practice: bonus_this_practice,
                is_crypt: true,
            },
            type: 'POST',
            success: function (data) {
                $('#success_block').fadeIn();
            },
            error: function (data) {
                console.log(data);
                $('#message_free').html(
                    'Не получилось получить доступ к серверу. Пожалуйста, повторите позже',
                );
            },
        });
    }
})(jQuery);
