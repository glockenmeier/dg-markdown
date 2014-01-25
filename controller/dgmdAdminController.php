<?php

/*
 * Copyright 2013, Darius Glockenmeier.
 * 
 * Description of dgmdAdminController
 *
 * @author Darius Glockenmeier <darius@glockenmeier.com>
 * @package core
 * 
 */
class dgmdAdminController extends DopeController {
    
    public function __construct(DopePlugin $plugin) {
        parent::__construct($plugin);
        
        //add_action( 'load-post.php', array( $this, 'load' ) );
        //add_action( 'load-post-new.php', array( $this, 'load' ) );
        add_action('admin_init', array($this, 'admin_init'));
        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'), 1);
        add_action('wp_ajax_dgmd_process_shortcode', array($this, 'ajaxHandler'));
    }
    
    public function ajaxHandler($action) {
        $action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);
        
        if ($action == "dgmd_process_shortcode") {
            $content = $_POST['content'];
            $response = do_shortcode($content);
            echo $response;
            die();
        }
        die();
    }
    
    public function admin_init() {
        remove_post_type_support('post', 'editor');
        remove_post_type_support('post', 'excerpt');
        add_post_type_support('post', 'dg-markdown');
        add_post_type_support('page', 'dg-markdown');
        $this->init_markdown_editor_metabox();
    }
    
    private function init_markdown_editor_metabox() {
        $meta = new dgmdEditorMetabox($this->plugin, 'post');
        $meta->add();
        //$metaPage = new dgmdEditorMetabox($this->plugin, 'page');
        //$metaPage->add();
    }
    
    public function admin_enqueue_scripts() {
        $this->plugin->enqueueScript('epiceditor');
        $this->plugin->enqueueScript('jquery');
    }
}