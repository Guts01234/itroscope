/**
 * Тут находится логика по работе с автокомлитами
 */

(function ($) {
    $(document).ready(function () {
        if (!$('.gift_city').length) return;

        $('.gift_city').find('input').attr('id', 'gift_city_input');
        $('.gift_address').find('input').attr('id', 'gift_address_input');

        //готовим опции модуля

        var options = {
            id: 'gift_city_input',
            levels: ['District', 'City', 'Place'],
            limit: 5,
            empty_msg: 'Населённый пункт не найден',
        };
        var options2 = {
            id: 'gift_address_input',
            levels: ['Street', 'House', 'Building', 'Structure'],
            limit: 5,
            empty_msg: 'Адрес не найден',
        };

        //запускаем модуль
        AhunterSuggest.Address.Solid(options);
        AhunterSuggest.Address.Solid(options2);
    });
})(jQuery);
