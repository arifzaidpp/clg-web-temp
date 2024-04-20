jQuery(document).ready(function($) {
  "use strict";

  $('.kungfu-ace-editor').each(function() {
    var el = $(this),
      pre = el.children('pre'),
      textarea = el.children('textarea');
    var editor = ace.edit(pre.attr('id'));
    editor.setTheme("ace/theme/" + pre.data('theme'));
    editor.getSession().setMode("ace/mode/" + pre.data('mode'));
    editor.setAutoScrollEditorIntoView(true);
    editor.setOption("maxLines", 30);
    editor.setOption("minLines", 8);
    editor.getSession().setTabSize(2);

    editor.getSession().on('change', function() {
      textarea.val(editor.getValue());
    });
  });
});
