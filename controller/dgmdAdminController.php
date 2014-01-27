<?php

/*
 * Copyright 2013, Darius Glockenmeier.
 * 
 * Description of dgmdAdminController
 *
 * @author Darius Glockenmeier <darius@glockenmeier.com>
 * @package dg-markdown
 * @subpackage controller
 * 
 */
class dgmdAdminController extends DopeController {
    
    public function __construct(DopePlugin $plugin) {
        parent::__construct($plugin);
        
        add_action('admin_init', array($this, 'admin_init'));
        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'), 1);
        add_action('wp_ajax_dgmd_process_shortcode', array($this, 'ajaxHandler'));
        $this->init_settings();
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
        
        $this->init_markdown_editor_metabox();
    }
    
    private function init_markdown_editor_metabox() {
        $opt = new DopeOptions('dgmd_');
        $post = DopeUtil::checkbox_value($opt->get('post', "1"));
        $page = DopeUtil::checkbox_value($opt->get('page', ""));
        
        if ($post) {
            remove_post_type_support('post', 'editor');
            remove_post_type_support('post', 'excerpt');
            add_post_type_support('post', 'dg-markdown');
            
            $metaPost = new dgmdEditorMetabox($this->plugin, 'post');
            $metaPost->add();
        }
        if ($page) {
            remove_post_type_support('page', 'editor');
            add_post_type_support('page', 'dg-markdown');
            $metaPage = new dgmdEditorMetabox($this->plugin, 'page');
            $metaPage->add();
        }
    }
    
    public function admin_enqueue_scripts() {
        $this->plugin->enqueueScript('epiceditor');
        $this->plugin->enqueueScript('jquery');
    }
    
    public function init_settings() {
        $settings = new DopeSettings('dg-markdown');
        $settings->addOptionsPage("DG's Markdown", 'Markdown');
       
        /* Plugin Section */
        $section_plugin = new SimpleDopeSettingsSection("Plugin", "Plugin settings", "Control the way the plugin works.");
        $settings->addSection($section_plugin);
        
        $section_plugin->addCheckboxField('dgmd_post', true, "Enable on Post", "Enables markdown editor on posts.");
        $section_plugin->addCheckboxField('dgmd_page', false, "Enable on Page", "Enables markdown editor on pages.");
        
        $settings->register();
    }
}