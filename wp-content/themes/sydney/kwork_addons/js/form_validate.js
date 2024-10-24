(function ($) {
    let $prevForm;
    let $curForm;

    function check_type_form() {
        const curType = $('input[name="type_form"]:checked').val();

        if (curType == 'lesson') {
            $curForm = $('#create_lesson_form');
        } else if (curType == 'gift') {
            $curForm = $('#create_gift_form');
        } else if (curType == 'time') {
            $curForm = $('#create_time_form');
        }

        if ($prevForm) {
            $prevForm.fadeOut();
        }

        $curForm.fadeIn();
        $prevForm = $curForm;
    }
    $('input[name="type_form"]').on('change', check_type_form);

    //начинается веселье
    // так как условная логика доступна только в про версии, а у нас обычная
    // то будем показывать/скрывать всё руками
    function check_gift_state() {
        const state = $('.gift_state input[name="cart_state"]:checked').val();
        const $textTypeGift = $('.type_gift .wpuf-label label');
        if (state == 'give') {
            $textTypeGift.text('Что хочу подарить?');
        } else {
            $textTypeGift.text('Что хочу получить в подарок?');
        }

        if (state) {
            $('.type_gift').fadeIn();
        }
    }
    $('.gift_state input[name="cart_state"]').on('change', check_gift_state);
    check_gift_state();

    function check_gift_type() {
        const curTypeGift = $('input[name="type_gift"]:checked').val();
        if (curTypeGift == 'money') {
            $('.gift_money_sum').fadeIn();

            $('.gift_product_type').fadeOut();
            $('.ozon_link').fadeOut();
            $('.gift_city').fadeOut();
            $('.gift_product_price').fadeOut();
        } else if (curTypeGift == 'product') {
            $('.gift_money_sum').fadeOut();

            $('.gift_product_type').fadeIn();

            const curTypeGift = $('input[name="gift_product_type"]:checked').val();

            $('.gift_product_price').fadeIn();

            if (curTypeGift == 'internet') {
                $('.ozon_link').fadeIn();

                $('.gift_city').fadeOut();
            } else if (curTypeGift == 'selftake') {
                $('.ozon_link').fadeOut();

                $('.gift_city').fadeIn();
            }
        }
    }
    $('input[name="type_gift"]').on('change', check_gift_type);
    check_gift_type();

    /**
     * Смена лейблов для названия
     */
    function change_gift_label() {
        let label = '';
        const state = $('.gift_state input[name="cart_state"]:checked').val();
        const curTypeGift = $('input[name="type_gift"]:checked').val();
        if (state == 'give' && curTypeGift == 'money') label = 'На что даю деньги?';
        if (state == 'give' && curTypeGift == 'product') label = 'Что хочу подарить?';
        if (state == 'take' && curTypeGift == 'money') label = 'На что нужны деньги?';
        if (state == 'take' && curTypeGift == 'product') label = 'Что хочу получить в подарок?';

        const $textTitleGift = $('.gift_desc  .wpuf-label label');
        if (label) {
            $textTitleGift.text(label);
            $('.gift_desc').fadeIn();
        }
    }

    change_gift_label();
    $('input[name="type_gift"]').on('change', change_gift_label);
    $('.gift_state input[name="cart_state"]').on('change', change_gift_label);

    /**
     * Показ карточки только для получения денег
     */
    function show_gift_transaction() {
        const state = $('.gift_state input[name="cart_state"]:checked').val();
        const curTypeGift = $('input[name="type_gift"]:checked').val();
        if (state == 'take' && curTypeGift == 'money') {
            $('.gift_money_transaction').fadeIn();
        } else {
            $('.gift_money_transaction').fadeOut();
        }
    }
    show_gift_transaction();
    $('input[name="type_gift"]').on('change', show_gift_transaction);
    $('.gift_state input[name="cart_state"]').on('change', show_gift_transaction);

    /**
     * Показ загрузки картинки только для отдачи подарка
     */
    function show_gift_photo() {
        const state = $('.gift_state input[name="cart_state"]:checked').val();
        const curTypeGift = $('input[name="type_gift"]:checked').val();
        if (state == 'give' && curTypeGift == 'product') {
            $('.gift_photo').fadeIn();
        } else {
            $('.gift_photo').fadeOut();
        }
    }
    show_gift_photo();
    $('input[name="type_gift"]').on('change', show_gift_photo);
    $('.gift_state input[name="cart_state"]').on('change', show_gift_photo);

    /**
     * Проверка товара подарка (интернет или самовывоз)
     */
    function check_gift_product_type() {
        const curTypeProduct = $('input[name="gift_product_type"]:checked').val();
        const state = $('.gift_state input[name="cart_state"]:checked').val();
        const type_gift = $('.type_gift  input[name="type_gift"]:checked').val();

        if (type_gift == 'money') {
            $('.ozon_link').fadeOut();
            $('.delivery_gift_take').fadeOut();
            $('.delivery_gift').fadeOut();
            $('.gift_city').fadeOut();
            $('.gift_address').fadeOut();
            return;
        }

        if (curTypeProduct == 'internet') {
            $('.ozon_link').fadeIn();
            $('.delivery_gift_take').fadeOut();
            $('.delivery_gift').fadeOut();
            $('.gift_city').fadeOut();
            $('.gift_address').fadeOut();
        } else if (curTypeProduct == 'selftake') {
            $('.ozon_link').fadeOut();

            if (state == 'give') {
                $('.delivery_gift').fadeIn();
                $('.delivery_gift_take').fadeOut();
                check_gift_give_type();
            } else if (state == 'take') {
                $('.delivery_gift_take').fadeIn();
                $('.delivery_gift').fadeOut();
                $('.gift_address').fadeOut();
            }
        }
    }
    $('input[name="gift_product_type"]').on('change', check_gift_product_type);
    $('.gift_state input[name="cart_state"]').on('change', check_gift_product_type);
    $('.type_gift input[name="type_gift"]').on('change', check_gift_product_type);
    check_gift_product_type();

    /**
     * Вывод улицы и города для отдачи подрака
     */
    function check_gift_give_type() {
        const giveType = $('input[name="delivery_gift"]:checked').val();

        if (giveType == 'town') {
            $('.gift_city').fadeIn();
            $('.gift_address').fadeOut();
        } else if (giveType == 'selftake') {
            $('.gift_city').fadeIn();
            $('.gift_address').fadeIn();
        } else {
            $('.gift_city').fadeOut();
            $('.gift_address').fadeOut();
        }
    }
    $('input[name="delivery_gift"]').on('change', check_gift_give_type);
    check_gift_give_type();

    /**
     * Вывод города для получения подарков
     */
    function check_gift_take_type() {
        const giveType = $('input[name="delivery_gift_take"]:checked').val();
        if (giveType == 'town') {
            $('.gift_city').fadeIn();
            $('.gift_address').fadeOut();
        } else {
            $('.gift_city').fadeOut();
            $('.gift_address').fadeOut();
        }
    }
    $('input[name="delivery_gift_take"]').on('change', check_gift_take_type);
    check_gift_take_type();

    function check_time_meeting() {
        var checkboxes = [];
        $('input[name="time_meeting[]"]:checked').each(function () {
            checkboxes.push($(this).val());
        });

        if (checkboxes.includes('offline')) {
            $('.time_town').fadeIn();
        } else {
            $('.time_town').fadeOut();
        }
    }
    $('input[name="time_meeting[]"]').on('change', check_time_meeting);
    check_time_meeting();

    function check_lesson_cart_state() {
        const curLessonState = $('input[name="lesson_cart_state"]:checked').val();

        if (curLessonState == 'give') {
            $('#create_lesson_form_give').fadeIn();
            $('#create_lesson_form_take').fadeOut();
        } else if (curLessonState == 'take') {
            $('#create_lesson_form_give').fadeOut();
            $('#create_lesson_form_take').fadeIn();
        }
    }

    $('input[name="lesson_cart_state"]').on('change', check_lesson_cart_state);
    check_lesson_cart_state();
})(jQuery);
