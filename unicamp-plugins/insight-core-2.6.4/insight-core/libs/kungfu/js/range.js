jQuery(document).ready(function($) {
  "use strict";

  $('.kungfu-range-field').each(function() {
    $(this).ionRangeSlider({
    	input_values_separator: ','
    });
  });

});
