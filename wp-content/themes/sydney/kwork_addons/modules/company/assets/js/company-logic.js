$(document).ready(function () {
    //подтвердить вступление
    $('.confirm_user_company').on('click', function () {
        let user_id = $(this).attr('data-user-id');
        let company_id = $(this).attr('data-company-id');

        $.ajax({
            url: site_url_header + '/wp-admin/admin-ajax.php',
            method: 'POST',
            data: {
                action: 'company_confirm_user',
                company_id: company_id,
                user_id: user_id,
            },
            success: (data) => {
                console.log(data);
                $(this).parent().html('<button class="cute_blue_button" disabled>Принят</button>');
            },
            error: (data) => {
                console.log(data);
            },
        });
    });

    //отказать во вступлении
    $('.cansel_user_company').on('click', function () {
        let user_id = $(this).attr('data-user-id');
        let company_id = $(this).attr('data-company-id');

        $.ajax({
            url: site_url_header + '/wp-admin/admin-ajax.php',
            method: 'POST',
            data: {
                action: 'company_cancel_user',
                company_id: company_id,
                user_id: user_id,
            },
            success: (data) => {
                $(this)
                    .parent()
                    .html('<button class="cute_blue_button" disabled>Отказано</button>');
            },
            error: (data) => {
                console.log(data);
            },
        });
    });

    //удалить сотрудника из компании
    $('.delete_user_from_company').on('click', function () {
        let user_id = $(this).attr('data-user-id');
        let company_id = $(this).attr('data-company-id');

        //а точно ли уверен
        let shure = confirm('Вы уверенны?');

        if (!shure) {
            return;
        }

        $.ajax({
            url: site_url_header + '/wp-admin/admin-ajax.php',
            method: 'POST',
            data: {
                action: 'delete_user_from_company',
                company_id: company_id,
                user_id: user_id,
                responsible: 'company',
            },
            success: (data) => {
                console.log(data);
                $(this).html('<button class="cute_blue_button" disabled>Удалён</button>');
            },
            error: (data) => {
                console.log(data);
            },
        });
    });

    $user_invation_input = $('#user_invate_in_company');

    if ($user_invation_input.length) {
        $messages = $user_invation_input.parent().parent().parent().find('.message_invation');

        $('#send_user_invation').on('click', function () {
            let user_id = $user_invation_input.attr('data-user-id');
            let company_id = $(this).attr('data-company-id');

            $messages.html('');
            $messages.css('display', 'none');

            if (!user_id || user_id.length < 1) {
                $messages.html('Вы не выбрали пользователя!');
                $messages.css('display', 'block');
                return;
            }

            $.ajax({
                url: site_url_header + '/wp-admin/admin-ajax.php',
                method: 'POST',
                data: {
                    action: 'company_send_user_invation',
                    company_id: company_id,
                    user_id: user_id,
                },
                success: (data) => {
                    $messages.html(data);
                    $messages.css('display', 'block');
                },
            });
        });
    }
});
