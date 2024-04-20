jQuery(document).ready(function($) {
  "use strict";

  $('.kungfu-form-image-select img').on('click', function() {
    var value = $(this).data('value');
    var input = $(this).siblings('.image-select-input');
    input.val(value);
    $(this).siblings('img').removeClass('active');
    $(this).addClass('active');
  });
});
