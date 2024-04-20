jQuery(document).ready(function ($) {

    $('#insight_popup_active').on('change', function () {

        if (!$(this).prop('checked')) {
            $('.cmb-row').addClass('inactive');
            $(this).closest('.cmb-row').removeClass('inactive');
        } else {
            $('.cmb2-id-insight-popup-appearance').removeClass('inactive');
            $('.cmb2-id-insight-popup-max-width').removeClass('inactive');
            $('.cmb2-id-insight-popup-display-animation').removeClass('inactive');
            $('.cmb2-id-insight-popup-closing-animation').removeClass('inactive');
            $('.cmb2-id-insight-popup-display-title').removeClass('inactive');

            $('.cmb2-id-insight-popup-show-post-type').removeClass('inactive');
            $('.cmb2-id-insight-popup-display').removeClass('inactive');
            $('.cmb2-id-insight-popup-display-role').removeClass('inactive');
            $('.cmb2-id-insight-popup-display-mode').removeClass('inactive');

            $('.cmb2-id-insight-popup-hide-title').removeClass('inactive');
            $('.cmb2-id-insight-popup-can-hide').removeClass('inactive');
            $('.cmb2-id-insight-popup-close-hide').removeClass('inactive');

            $('#insight_popup_show_post_type').trigger('change');
            $('.cmb2-id-insight-popup-display input[type="radio"]').trigger('change');
            $('#insight_popup_can_hide').trigger('change');
            $('#insight_popup_close_hides').trigger('change');

            $('.cmb2-id-insight-popup-display span').each(function () {
                if ($(this).parent().find('input[type="radio"]').prop('checked')) {
                    $(this).removeClass('inactive');
                }

            });
        }
    });

    $('#insight_popup_show_post_type').on('change', function () {

        var $select = $(this);
        var $postsRow = $('.cmb2-id-insight-popup-posts');
        var $excludeArchive = $('.cmb2-id-insight-popup-exclude-archive-page');

        if ($select.val() != 'custom') {
            $postsRow.addClass('inactive');

            if ($select.val() != 'page') {
                $excludeArchive.removeClass('inactive');
            } else {
                $excludeArchive.addClass('inactive');
            }
        } else {
            $postsRow.removeClass('inactive');
            $excludeArchive.addClass('inactive');
        }
    });

    $('.cmb2-id-insight-popup-display input[type="radio"]').on('change', function () {

        var $radio = $(this);
        var toggle = $radio.data('toggle');
        var $span = $('.cmb2-id-insight-popup-display span');

        $span.addClass('inactive');

        if ($radio.prop('checked')) {
            $(toggle).removeClass('inactive');
        }
    });

    $('#insight_popup_can_hide').on('change', function () {

        var $popupExpire = $('.cmb2-id-insight-popup-expire');

        if (!$('#insight_popup_close_hide').prop('checked') && !$(this).prop('checked')) {
            $popupExpire.addClass('inactive');
        } else {
            $popupExpire.removeClass('inactive');
        }
    });

    $('#insight_popup_close_hide').on('change', function () {

        var $popupExpire = $('.cmb2-id-insight-popup-expire');

        if (!$('#insight_popup_can_hide').prop('checked') && !$(this).prop('checked')) {
            $popupExpire.addClass('inactive');
        } else {
            $popupExpire.removeClass('inactive');
        }
    });

    $('#insight_popup_active').trigger('change');
    $('#insight_popup_show_post_type').trigger('change');
    $('.cmb2-id-insight-popup-display input[type="radio"]').trigger('change');
    $('#insight_popup_can_hide').trigger('change');
    $('#insight_popup_close_hide').trigger('change');

    $('.cmb2-id-insight-popup-display span').each(function () {
        if ($(this).parent().find('input[type="radio"]').prop('checked')) {
            $(this).removeClass('inactive');
        }

    });
});