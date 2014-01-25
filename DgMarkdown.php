<?php
/*
 * Copyright 2014, Darius Glockenmeier.
 */

/*
 * Description of DgSyntaxHighlighter
 *
 * @author Darius Glockenmeier <darius@glockenmeier.com>
 * @package core
 * 
 */
class DgMarkdown extends DopePlugin {

    private static $instance = null;

    public function __construct($bootstrapFile) {
        parent::__construct($bootstrapFile);
        
        if (is_admin()) {
            new dgmdAdminController($this);
        } else {
            //new dgshController($this);
        }
    }
    
    /**
     * Gets the instance of {@see DgSyntaxHighlighter}.
     * @param type $bootstrapFile
     * @return DgShop
     */
    public static function getInstance($bootstrapFile) {
        if (self::$instance === null) {
            self::$instance = new self($bootstrapFile);
        }
        return self::$instance;
    }

    /**
     * Plugin bootstrap
     * @param string $plugin_bootstrap the plugin file path, usually __FILE__
     */
    public static function bootstrap($plugin_bootstrap = __FILE__) {

        /*
         * Get an instance of the plugin-manager.
         * DopePluginManager is implemented as a singleton. Means that there is only
         * one instance of the manager. getInstance() give's you access to that instance.
         * 
         */
        $pluginManager = DopePluginManager::getInstance();

        /**
         * Note that DgShop is also implemented as singleton. This is not mandatory
         * but is a good practice to keep and refer to a single instance of the plugin.
         */
        $dgmd = DgMarkdown::getInstance($plugin_bootstrap);

        /* Registers our plugin with dope's plugin-manager.
         * Right now this does nothing aside from registering events. Might be used in future
         * to manage dope based plug-ins, dependencies, etc.
         */
        $pluginManager->register($dgmd);
        
        /*
         * Enable dope's exception handler for debugging.
         */
        $dope = DGOOPlugin::getInstance();
        $dope->enableExceptionHandler();

        /**
         * Attach debugging event listener
         */
        //$dg_shop->getEventHandler()->addListener(new DopePluginEventDebugger());
    }

}