$(document).ready(function () {
    $('.user_cansel_invation').on('click', function () {
        let user_id = $(this).parent().attr('data-user-id');
        let company_id = $(this).parent().attr('data-company-id');
        let notification_id = $(this).parent().attr('data-notification-id');

        $.ajax({
            url: site_url_header + '/wp-admin/admin-ajax.php',
            method: 'POST',
            data: {
                action: 'user_cansel_invation',
                company_id: company_id,
                user_id: user_id,
                notification_id: notification_id,
            },
            success: (data) => {
                console.log(data);
                $(this).parent().html('<p>Вы отказались</p>');
            },
            error: (data) => {
                console.log(data);
            },
        });
    });

    $('.user_confirm_invation').on('click', function () {
        let user_id = $(this).parent().attr('data-user-id');
        let company_id = $(this).parent().attr('data-company-id');
        let notification_id = $(this).parent().attr('data-notification-id');

        $.ajax({
            url: site_url_header + '/wp-admin/admin-ajax.php',
            method: 'POST',
            data: {
                action: 'company_confirm_user',
                company_id: company_id,
                user_id: user_id,
                notification_id: notification_id,
                responsible: 'user',
            },
            success: (data) => {
                console.log(data);
                $(this).parent().html('<p>Вы приняли приглашение</p>');
            },
            error: (data) => {
                console.log(data);
            },
        });
    });
});
