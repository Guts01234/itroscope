$(document).ready(function () {
    const notificationBlock = $('.notification__block');

    const closeNotification = () => {
        $('.notifications_row').fadeOut();
        $(document).off('click', handleNotificationOutside);
        if ($('.notification__count').hasClass('count--active')) {
            $('.notification__block--active .notification__not_readed').each(function (i) {
                $(this).addClass('notification__readed');
                $(this).removeClass('notification__not_readed');
            });
        }
        notificationBlock.removeClass('notification__block--active');
    };

    const handleNotificationOutside = (e) => {
        if (!notificationBlock.is(e.target) && notificationBlock.has(e.target).length === 0) {
            closeNotification();
        }
    };

    notificationBlock.on('click', function () {
        if ($(this).hasClass('notification__block--active')) {
            closeNotification();
        } else {
            $('.notifications_row').fadeIn();
            $(document).on('click', handleNotificationOutside);
            $(this).addClass('notification__block--active');

            const notificationIdArray = [];

            $('.notification__count').removeClass('count--active');
            $('.notification__count').fadeOut();

            $('.notification__block--active .notification__not_readed').each(function (i) {
                const notificaitonId = $(this).attr('data-id');
                notificationIdArray.push(notificaitonId);
            });

            $.ajax({
                url: site_url_header + '/wp-admin/admin-ajax.php',
                method: 'POST',
                data: {
                    action: 'change_status_notification',
                    notification_array: notificationIdArray,
                },
                success: (data) => {
                    console.log(data);
                },
                error: (data) => {
                    console.log(data);
                },
            });
        }
        $('.notifications_row').on('click', function (e) {
            e.stopPropagation();
        });
    });
});
