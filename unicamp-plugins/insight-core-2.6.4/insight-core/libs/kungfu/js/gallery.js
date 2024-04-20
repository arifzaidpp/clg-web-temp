jQuery(document).ready(function($) {
  "use strict";

  $('.kungfu-gallery-upload-wrap').each(function(index, value) {
    var $el = $(this),
      kungfu_gallery_frame,
      attachment_ids,
      mediaImages = $el.children('.kungfu-gallery-images'),
      image_gallery_input = $el.children('.kungfu-gallery');
    $(this).children('.kungfu-gallery-open').on('click', function(e) {

      e.preventDefault();

      // If the frame already exists, re-open it.
      if (kungfu_gallery_frame) {
        kungfu_gallery_frame.open();
        return;
      }

      kungfu_gallery_frame = wp.media.frames.kungfu_gallery_frame = wp.media({
        title: 'Insert Media',
        button: {
          text: 'Select'
        },
        className: 'media-frame kungfu-gallery-frame',
        frame: 'select',
        multiple: true,
        library: {
          type: 'image'
        },
      });

      kungfu_gallery_frame.on('select', function() {
        var selection = kungfu_gallery_frame.state().get('selection'),
          attachment_ids = image_gallery_input.val(),
          list = '';
        if (attachment_ids) {
          attachment_ids = JSON.parse(attachment_ids);
        } else {
          attachment_ids = [];
        }
        selection.map(function(attachment) {
          attachment = attachment.toJSON();
          if (attachment.id) {
            var thumbnail = attachment.sizes && attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url;
            var obj = {
              width: attachment.width,
              height: attachment.height,
              id: attachment.id,
              url: attachment.url,
              thumbnail: thumbnail,
            }
            attachment_ids.push(obj);
            list += '<li class="image" data-attachment-id="' + attachment.id + '"><img src="' + thumbnail + '" /><ul class="actions"><li><a href="#" class="kungfu-gallery-remove" title="Delete"><i class="fa fa-times-circle-o"></i></a></li></ul></li>';
          }
        });

        if (list != '') {
          mediaImages.append(list);
        }
        $el.find('.kungfu-gallery-clear').show();
        image_gallery_input.val(JSON.stringify(attachment_ids));
      });

      // Finally, open up the frame, when everything has been set.
      kungfu_gallery_frame.open();
    });

    // REMOVE SINGLE GALLERY
    $(this).on('click', '.kungfu-gallery-remove', function(e) {
      e.preventDefault();
      var li = $(this).closest('li.image');
      var id = li.data('attachment-id');
      li.remove();
      var attachments = JSON.parse(image_gallery_input.val());
      for (var i = attachments.length - 1; i >= 0; i--) {
        if (attachments[i].id == id) {
          attachments.splice(i, 1);
          break;
        }
      }
      var value = attachments.length > 0 ? JSON.stringify(attachments) : '';
      image_gallery_input.val(value);
    });

    // CLEAR GALLERIES
    $(this).on('click', '.kungfu-gallery-clear', function(e) {
      e.preventDefault();
      $(this).siblings('.kungfu-gallery-images').empty();
      $(this).siblings('.kungfu-gallery').val('');
      $(this).hide();
    });

    // GALLERIES ORDERING
    mediaImages.sortable({
      items: 'li.image',
      scrollSensitivity: 60,
      forcePlaceholderSize: true,
      forceHelperSize: false,
      helper: 'clone',
      opacity: 0.5,
      revert: 400,
      placeholder: 'kungfu-gallery-placeholder',
      update: function() {
        var sorted = [];
        var attachments = JSON.parse(image_gallery_input.val());
        mediaImages.find('.image').each(function() {
          var id = $(this).data('attachment-id');
          for (var i = attachments.length - 1; i >= 0; i--) {
            if(attachments[i].id == id) {
              sorted.push(attachments[i]);
              break;
            }
          }
        });
        image_gallery_input.val(JSON.stringify(sorted));
      }
    });
  });
});
