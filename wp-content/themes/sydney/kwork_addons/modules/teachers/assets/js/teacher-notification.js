$(document).ready(function () {
    $('.invation_teacher_cancel').on('click', function () {
        $parent = $(this).parent();

        let user_id = $parent.attr('data-user-id');
        let post_id = $parent.attr('data-post-id');
        let notification_id = $parent.attr('data-notification-id');
        let author_id = $parent.attr('data-author-id');

        $.ajax({
            url: site_url_header + '/wp-admin/admin-ajax.php',
            method: 'POST',
            data: {
                action: 'sent_invation_to_teacher_сancel',
                author_id: author_id,
                notification_id: notification_id,
                invaiton_user_id: user_id,
                post_id: post_id,
            },
            success: function (data) {
                console.log(data);
                $parent.html('<p>Вы отказались</p>');
            },
            error: function (data) {
                console.log(data);
            },
        });
    });
    $('.invation_teacher_accept').on('click', function () {
        $parent = $(this).parent();

        let user_id = $parent.attr('data-user-id');
        let post_id = $parent.attr('data-post-id');
        let notification_id = $parent.attr('data-notification-id');
        let author_id = $parent.attr('data-author-id');

        $.ajax({
            url: site_url_header + '/wp-admin/admin-ajax.php',
            method: 'POST',
            data: {
                action: 'sent_invation_to_teacher_сonfirm',
                author_id: author_id,
                notification_id: notification_id,
                invaiton_user_id: user_id,
                post_id: post_id,
            },
            success: function (data) {
                console.log(data);
                $parent.html('<p>Вы Согласились</p>');
            },
            error: function (data) {
                console.log(data);
            },
        });
    });

    //взять или отказаться от домашенго задания

    $('.nastavnik_add_lesson__cansel').on('click', function () {
        $parent = $(this).parent();

        let homework_id = $parent.attr('data-homework-id');

        $.ajax({
            url: site_url_header + '/wp-admin/admin-ajax.php',
            method: 'POST',
            data: {
                action: 'nastavnik_add_lesson__cansel',
                homework_id: homework_id,
                user_id: current_user_id,
            },
            success: function (data) {
                console.log(data);
                $parent.html(`<p>${data}</p>`);
            },
            error: function (data) {
                console.log(data);
            },
        });
    });

    $('.nastavnik_add_lesson__confirm').on('click', function () {
        $parent = $(this).parent();

        let homework_id = $parent.attr('data-homework-id');

        $.ajax({
            url: site_url_header + '/wp-admin/admin-ajax.php',
            method: 'POST',
            data: {
                action: 'nastavnik_add_lesson__confirm',
                homework_id: homework_id,
                user_id: current_user_id,
            },
            success: function (data) {
                console.log(data);
                $parent.html(`<p>${data}</p>`);
            },
            error: function (data) {
                console.log(data);
            },
        });
    });
});
