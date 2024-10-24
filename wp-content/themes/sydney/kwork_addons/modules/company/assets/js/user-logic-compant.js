$(document).ready(function () {
    let can_send = true;

    $('#go_in_company').on('click', function () {
        if (!can_send) {
            return;
        }

        can_send = false;
        let user_id = $(this).attr('data-user-id');
        let company_id = $(this).attr('data-company-id');

        $.ajax({
            url: site_url_header + '/wp-admin/admin-ajax.php',
            method: 'POST',
            data: {
                action: 'add_user_in_company_waiting',
                company_id: company_id,
                user_id: user_id,
            },
            success: (data) => {
                console.log(data);
                $(this).addClass('send');
                $(this).text('Отправлено');
                $(this).attr('disabled', 'disable');
                let name = $('.um-name a').text();

                $('#modal_error_add_post .content').html(
                    `<p class="modal_cute_msg">${name} рассмотрит вашу заявку</p>`,
                );
                $('#modal_error_add_post').fadeIn();

                setTimeout(() => {
                    $('#modal_error_add_post').fadeOut();
                }, 2000);
                setTimeout(() => {
                    $('#modal_error_add_post .content').html(``);
                }, 2410);
            },
            error: (data) => {
                console.log(data);
            },
        });
    });

    $('#go_from_company').on('click', function () {
        if (!can_send) {
            return;
        }

        can_send = false;
        let user_id = $(this).attr('data-user-id');
        let company_id = $(this).attr('data-company-id');

        $.ajax({
            url: site_url_header + '/wp-admin/admin-ajax.php',
            method: 'POST',
            data: {
                action: 'delete_user_from_company',
                company_id: company_id,
                user_id: user_id,
                responsible: 'user',
            },
            success: (data) => {
                console.log(data);
                $(this).addClass('send');
                $(this).text('Вы покинули компанию');
                $(this).attr('disabled', 'disable');

                let name = $('.um-name a').text();

                $('#modal_error_add_post .content').html(
                    `<p class="modal_cute_msg">Вы покинули ${name}</p>`,
                );
                $('#modal_error_add_post').fadeIn();

                setTimeout(() => {
                    $('#modal_error_add_post').fadeOut();
                }, 2000);
                setTimeout(() => {
                    $('#modal_error_add_post .content').html(``);
                }, 2410);
            },
            error: (data) => {
                console.log(data);
            },
        });
    });
});
