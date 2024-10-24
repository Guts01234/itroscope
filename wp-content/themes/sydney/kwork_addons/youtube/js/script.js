(function ($) {
    $(document).ready(function () {
        setTimeout(() => {
            let el = $('.editor-post-publish-button__button');
            const innput_ytHref = $('#acf-field_63ef6b6606661');
            const input_satus = $('#acf-field_63ef872d9e689');
            if (el.length) {
                const form = innput_ytHref;
                //получаем длинну видео
                async function getDur() {
                    const val = form.val();
                    if (val == '') {
                        el.attr('aria-disabled', 'false');
                        el.removeAttr('disabled');
                        input_satus.val('');
                        return;
                    }
                    const video_duration = await $.ajax({
                        url: '/wp-admin/admin-ajax.php',
                        data: {
                            action: 'ytD',
                            video_url: val,
                        },
                        type: 'POST',
                    });
                    if (!video_duration) {
                        alert('Неправильная ссылка на видео');
                        input_satus.val('');
                        return;
                    } else {
                        let video_time = video_duration;
                        video_time = video_time.replace('PT', '');
                        let video_sec = 0;
                        if (video_time.includes('H')) {
                            video_time = video_time.split('H');
                            video_sec += Number(video_time[0]) * 3600;
                            video_time = video_time[1];
                        }
                        if (video_time.includes('M')) {
                            video_time = video_time.split('M');
                            video_sec += Number(video_time[0]) * 60;
                            video_time = video_time[1];
                        }
                        if (video_time.includes('S')) {
                            video_time = video_time[1].split('S');
                            video_sec += Number(video_time[0]);
                        }
                        video_min = video_sec / 60;
                        if (video_min > 15) {
                            input_satus.val('long');
                        } else {
                            input_satus.val('short');
                        }
                        el.attr('aria-disabled', 'false');
                        el.removeAttr('disabled');
                    }
                }
                const f = debounce(() => getDur());

                form.on('keydown', f);
                form.on('keydown', () => {
                    el.attr('aria-disabled', 'true');
                    el.attr('disabled', 'true');
                });
            }
        }, 4000);
    });
})(jQuery);

function debounce(func, timeout = 1000) {
    let timer;
    return (...args) => {
        clearTimeout(timer);
        timer = setTimeout(() => {
            func.apply(this, args);
        }, timeout);
    };
}
