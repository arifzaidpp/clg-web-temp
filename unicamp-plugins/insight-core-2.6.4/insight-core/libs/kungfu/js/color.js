jQuery(document).ready(function($) {
  "use strict";

  $('.kungfu-form-color').each(function(i, o) {
    var $el = $(this);

    $el.spectrum({
      allowEmpty: true,
      showInput: true,
      className: "kungfu-spectrum-color",
      showInitial: true,
      showAlpha: true,
      showSelectionPalette: true,
      maxPaletteSize: 10,
      preferredFormat: "hex",

      change: function(color) {
        if(color) {
          if(color._a < 1) {
            $el.val(color.toRgbString());
          }
        }
      }
    });
  });
});
