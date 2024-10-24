$(document).ready(function () {
    $company_form = $('.reg_form_company').parents('.um-register');
    $user_form = $('.reg_form_user').parents('.um-register');

    let params = new URL(document.location).searchParams;

    if (params.get('form') == 'company') {
        $company_form.fadeIn();
        $('body').append('<style>.um{opacity: 1}</style>');
    } else if (params.get('form') == 'user') {
        $user_form.fadeIn();
        $('body').append('<style>.um{opacity: 1}</style>');
    }
    console.log(params);

    $('input[type=radio][name=type_user]').change(function () {
        let value = this.value;

        if (value == 'user') {
            $user_form.fadeIn();
            $company_form.css('position', 'absolute');
            $company_form.fadeOut();

            setTimeout(() => {
                $company_form.css('position', 'relative');
            }, 401);
        } else if ((value = 'company')) {
            $company_form.fadeIn();
            $user_form.css('position', 'absolute');
            $user_form.fadeOut();

            setTimeout(() => {
                $user_form.css('position', 'relative');
            }, 401);
        }

        //добавляем гет параметр
        var baseUrl =
            window.location.protocol + '//' + window.location.host + window.location.pathname;
        var newUrl = baseUrl + '?form=' + value;
        history.pushState(null, null, newUrl);
    });
});
