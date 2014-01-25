<?php
/*
 * Copyright 2014, Darius Glockenmeier.
 * 
 * Description of markdown-editor 
 */
?>
<div id="epiceditor"></div>
<input type="hidden" id="dgmd_content" name="dgmd_content" value="<?php echo esc_attr($this->content) ?>" />
<input type="hidden" id="dgmd_content_md" name="dgmd_content_md" value="<?php echo esc_attr($this->content_md) ?>" />
<?php echo $this->nonce ?>
<script>
    var opts = {
        basePath: '../wp-content/plugins/dg-markdown',
        autogrow: false,
        useNativeFullscreen: true,
        clientSideStorage: false
    };
    var editor = new EpicEditor(opts).load();
    
    (function($) {
        var content = $('#dgmd_content').val();
        var content_md = $('#dgmd_content_md').val();
        // use markdown content when available
        var text = content_md.length > 0 ? content_md : content;
        editor.importFile(null, text);
        
        $('#publish').click(function(event) {
            var export_file_md = editor.exportFile();
            var export_file_html = null;
            // process shortcode
            var data = {
                action: 'dgmd_process_shortcode',
                content: export_file_md
            };
            export_file_html = editor.exportFile(null, 'html');
            
            $.post(ajaxurl, data, function(response) {
                //alert(response);
                // re-import
                //editor.importFile(null, response);
                // save altered html leaving md with shortcode intact.
            });
            $('#dgmd_content').val(export_file_html);
            $('#dgmd_content_md').val(export_file_md);
        });
    })(jQuery);
</script>