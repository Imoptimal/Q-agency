<?php

if (!class_exists('Q_Agency_Theme')) {
    class Q_Agency_Theme
    {
        // Init function separated from default construct func
        public function init()
        {
            add_action('wp_enqueue_scripts', array($this, 'enqueue_theme_assets'));
        }

        /**
         * Adding css and js files
         *
         * @since    1.0.0
         * */
        public function enqueue_theme_assets() {
        // Default style.css
        wp_enqueue_style('qagency-theme-style', get_stylesheet_uri(), [], '1.0.0', 'all');
        // Default script.js
        wp_enqueue_script('qagency-theme-script', get_stylesheet_directory_uri() . '/script.js', [], '1.0.0', true);
        
    }

    }
    $wp_q_agency_theme = new Q_Agency_Theme();
}