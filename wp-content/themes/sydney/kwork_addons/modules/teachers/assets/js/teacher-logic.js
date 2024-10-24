$(document).ready(function () {
    $('.add_teacher_to_lesson').each(function () {
        $(this).on('click', function () {
            let post_id = $(this).attr('data-post-id');
            let current_user_id = $(this).attr('data-current-user-id');

            $parent = $(this).parent();

            $messages = $parent.find('.message_invation');
            $input = $parent.find('input');

            $messages.html('');
            $messages.css('display', 'none');

            let invation_id = $input.attr('data-user-id');

            if (
                !current_user_id ||
                current_user_id == '' ||
                !post_id ||
                post_id == '' ||
                !invation_id ||
                invation_id == ''
            ) {
                $messages.html('Пользователь не выбран!');
                $messages.css('display', 'block');
                return;
            }

            $.ajax({
                url: site_url_header + '/wp-admin/admin-ajax.php',
                method: 'POST',
                data: {
                    action: 'sent_invation_to_teacher',
                    current_user_id: current_user_id,
                    invaiton_user_id: invation_id,
                    post_id: post_id,
                },
                success: function (data) {
                    console.log(data);
                    $input.val('');
                    $input.attr('data-user-id', '');
                    $('#itroscope_modal_info__content').text(data);
                    $('#itroscope_modal_info').fadeIn();
                    setTimeout(() => {
                        $('#itroscope_modal_info__content').html('');
                        $('#itroscope_modal_info').fadeOut();
                    }, 3000);
                },
                error: function (data) {
                    console.log(data);
                },
            });
        });
    });
});
