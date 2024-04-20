jQuery(document).ready(function($) {
  "use strict";

  $('.kungfu-attach-wrap').each(function(index, value) {
    var $el = $(this),
      kungfu_attach_frame,
      $mediaImage = $el.children('.kungfu-media-image'),
      $media_input = $el.find('.kungfu-media');
    $(this).children('.kungfu-media-open').on('click', function(e) {

      e.preventDefault();

      // If the frame already exists, re-open it.
      if (kungfu_attach_frame) {
        kungfu_attach_frame.open();
        return;
      }

      kungfu_attach_frame = wp.media.frames.kungfu_attach_frame = wp.media({
        title: 'Insert Media',
        button: {
          text: 'Select'
        },
        className: 'media-frame kungfu-media-frame',
        frame: 'select',
        multiple: false,
        library: {
          type: 'image'
        },
      });

      kungfu_attach_frame.on('select', function() {
        var attachment = kungfu_attach_frame.state().get('selection').first().toJSON();
          /*thumbnail = attachment.sizes && attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url,
          obj = {
            width: attachment.width,
            height: attachment.height,
            id: attachment.id,
            url: attachment.url,
            thumbnail: thumbnail
          };*/
        $el.find('.kungfu-media-remove').show();
        $mediaImage.html('<img src="' + attachment.url + '" />');
        //$media_input.val(JSON.stringify(obj));
        $media_input.val(attachment.id);
      });

      // Finally, open up the frame, when everything has been set.
      kungfu_attach_frame.open();
    });

    // REMOVE MEDIA
    $(this).on('click', '.kungfu-media-remove', function(e) {
      e.preventDefault();

      $mediaImage.empty();
      $media_input.val('');
      $(this).hide();
    });
  });
});
