jQuery(document).ready(function () {
    jQuery('.um-profile.um-viewing .um-profile-body .um-row').each(function () {
        var e = jQuery(this);
        0 == e.find('.um-field').length && (e.prev('.um-row-heading').remove(), e.remove());
    }),
        jQuery('.um-profile.um-viewing .um-profile-body').length &&
            0 == jQuery('.um-profile.um-viewing .um-profile-body').find('.um-field').length &&
            (jQuery('.um-profile.um-viewing .um-profile-body')
                .find('.um-row-heading,.um-row')
                .remove(),
            jQuery('.um-profile-note').show()),
        jQuery(document.body).on('click', '.um-profile-save', function (e) {
            return (
                e.preventDefault(), jQuery(this).parents('.um').find('form').trigger('submit'), !1
            );
        }),
        jQuery('.um-profile-save')
            .parents('.um')
            .find('form')
            .on('submit', function (e) {
                let town = jQuery('#um-meta-towm').val();
                console.log(town);
                user_id = jQuery('.um-reset-profile-photo').attr('data-user_id');
                jQuery.ajax({
                    url: wp.ajax.settings.url,
                    type: 'post',
                    data: {
                        action: 'um_change_user_town',
                        town: town,
                        user_id: user_id,
                        nonce: um_scripts.nonce,
                    },
                });
            });

    jQuery(document.body).on('click', '.um-profile-edit-a', function (e) {
        jQuery(this).addClass('active');
    }),
        jQuery(document.body).on('click', '.um-cover a.um-cover-add, .um-photo a', function (e) {
            e.preventDefault();
        }),
        jQuery(document.body).on('click', '.um-photo-modal', function (e) {
            e.preventDefault();
            e = jQuery(this).attr('data-src');
            return um_new_modal('um_view_photo', 'fit', !0, e), !1;
        }),
        jQuery(document.body).on('click', '.um-reset-profile-photo', function (e) {
            return (
                jQuery('.um-profile-photo-img img').attr(
                    'src',
                    jQuery(this).attr('data-default_src'),
                ),
                (user_id = jQuery(this).attr('data-user_id')),
                (metakey = 'profile_photo'),
                UM.dropdown.hideAll(),
                jQuery.ajax({
                    url: wp.ajax.settings.url,
                    type: 'post',
                    data: {
                        action: 'um_delete_profile_photo',
                        metakey: metakey,
                        user_id: user_id,
                        nonce: um_scripts.nonce,
                    },
                }),
                jQuery(this).parents('li').hide(),
                !1
            );
        }),
        jQuery(document.body).on('click', '.um-reset-cover-photo', function (e) {
            var r = jQuery(this);
            return (
                jQuery('.um-cover-overlay').hide(),
                jQuery('.um-cover-e').html(
                    '<a href="javascript:void(0);" class="um-cover-add" style="height: 370px;"><span class="um-cover-add-i"><i class="um-icon-plus um-tip-n" original-title="Upload a cover photo"></i></span></a>',
                ),
                um_responsive(),
                (user_id = jQuery(this).attr('data-user_id')),
                (metakey = 'cover_photo'),
                jQuery.ajax({
                    url: wp.ajax.settings.url,
                    type: 'post',
                    data: {
                        action: 'um_delete_cover_photo',
                        metakey: metakey,
                        user_id: user_id,
                        nonce: um_scripts.nonce,
                    },
                    success: function (e) {
                        r.hide();
                    },
                }),
                UM.dropdown.hideAll(),
                !1
            );
        }),
        jQuery(document.body).on('change, keyup', 'textarea[id="um-meta-bio"]', function () {
            var e;
            void 0 !== jQuery(this).val() &&
                ((e = jQuery(this).attr('data-character-limit') - jQuery(this).val().length),
                jQuery('span.um-meta-bio-character span.um-bio-limit').text(e),
                e < 5
                    ? jQuery('span.um-meta-bio-character').css('color', 'red')
                    : jQuery('span.um-meta-bio-character').css('color', ''));
        }),
        jQuery('textarea[id="um-meta-bio"]').trigger('change'),
        jQuery('.um-profile-edit a.um_delete-item').on('click', function (e) {
            if (
                (e.preventDefault(),
                !confirm(
                    wp.i18n.__(
                        'Are you sure that you want to delete this user?',
                        'ultimate-member',
                    ),
                ))
            )
                return !1;
        }),
        jQuery('.um-profile-nav a').on('touchend', function (e) {
            jQuery(e.currentTarget).trigger('click');
        });
});
