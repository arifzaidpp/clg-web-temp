jQuery(document).ready(function($) {
  "use strict";

  $('.kungfu-tabpanel').each(function(index, element) {
    var $el = $(this),
      id = $(this).attr('id'),
      active = localStorage.getItem(id);
    if (!active) {
      active = 0;
    }
    $(this).children('.kungfu-nav-tabs').children().eq(active).addClass('active');
    $(this).children('.kungfu-tab-content').children().eq(active).addClass('active');

    $el.children('.kungfu-nav-tabs').on('click', 'a', function(e) {
      e.preventDefault();

      $(this).parent('li').siblings().removeClass('active');
      $(this).parent('li').addClass('active');

      var tabpanel = $(this).parents('.kungfu-tabpanel').first();
      var index = $(this).parent().index();
      localStorage.setItem(id, index);
      tabpanel.children('.kungfu-tab-content').children().removeClass('active');
      tabpanel.children('.kungfu-tab-content').children().eq(index).addClass('active');

    });
  });
});
