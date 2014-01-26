<?php

/*
 * Copyright 2013, Darius Glockenmeier.
 * 
 * Description of dgmdEditorMetabox
 *
 * @author Darius Glockenmeier <darius@glockenmeier.com>
 * @package dg-markdown
 * @subpackage model
 * 
 */
class dgmdEditorMetabox extends dgmdMetabox {
    private $plugin = null;
    
    public function __construct(DopePlugin $plugin, $screen) {
        parent::__construct('dgmd_editor_metabox', 'Markdown Editor', $screen, 'normal', 'high');
        $this->plugin = $plugin;
    }
    
    public function renderMetabox($post) {
        $view = new SimpleDopeView($this->plugin->getDirectory());
        $dpost = DopePost::get($post);
        $content = $dpost->getContent();
        $content_md = $dpost->getMeta()->get('dgmd_content_md');
        
        $view->assign('post', $post)
                ->assign('plugin_dir', $this->plugin->getDirectory())
                ->assign('content', $content)
                ->assign('content_md', $content_md)
                ->assign('nonce', $this->getNonce())
                ->render('markdown-editor');
    }

    protected function onSave($post_id) {
        $content = $_POST['dgmd_content'];
        $content_md = $_POST['dgmd_content_md'];
        
        $dpost = DopePost::get($post_id);
        $dpost->getMeta()->update('dgmd_content_md', $content_md);
        
        remove_action('save_post', array($this, '_doSave')); // prevent recursion
        $postArr = array( 'ID' => $post_id, 'post_content' => $content);
        wp_update_post($postArr);
        add_action('save_post', array($this, '_doSave'));
    }
    
}