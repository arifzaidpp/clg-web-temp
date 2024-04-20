jQuery(document).ready(function($) {
  "use strict";

  $('.kungfu-media-upload-wrap').each(function(index, value) {
    var $el = $(this),
      kungfu_media_frame,
      $mediaImage = $el.children('.kungfu-media-image'),
      $media_input = $el.find('.kungfu-media');
    $(this).children('.kungfu-media-open').on('click', function(e) {

      e.preventDefault();

      // If the frame already exists, re-open it.
      if (kungfu_media_frame) {
        kungfu_media_frame.open();
        return;
      }

      kungfu_media_frame = wp.media.frames.kungfu_media_frame = wp.media({
        title: 'Insert Media',
        button: {
          text: 'Select'
        },
        className: 'media-frame kungfu-media-frame',
        frame: 'select',
        multiple: false,
        library: {
          type: 'audio'
        },
      });

      kungfu_media_frame.on('select', function() {
        var attachment = kungfu_media_frame.state().get('selection').first().toJSON();
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
        console.log(attachment.url);
        $media_input.val(attachment.url);
      });

      // Finally, open up the frame, when everything has been set.
      kungfu_media_frame.open();
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
