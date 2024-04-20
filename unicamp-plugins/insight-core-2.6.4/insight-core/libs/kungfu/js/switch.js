jQuery(document).ready(function($) {
  "use strict";

  $('.kungfu-switch-field').each(function() {
    var $el = $(this),
      wrapper = $(this).siblings('.kungfu-switch');
    wrapper.children(".option").on('click', function(e) {
      wrapper.find(".active").removeClass("active");
      $(this).addClass("active");
      $el.val($(this).data('value'));
    });
  });

});
