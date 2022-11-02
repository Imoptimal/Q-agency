<?php
/** 
 * Plugin that add dynamic Gutenberg block 
 * 
 * @link              https://github.com/Imoptimal/Q-agency
 * @since             1.0.0
 * @package           Gutenberg_Dynamic_Block
 * 
 * @wordpress-plugin
 * Plugin Name:       Gutenberg Dynamic Block
 * Plugin URI:        https://github.com/Imoptimal/Q-agency
 * Description:       
 * Version:           0.8.0
 * Author:            Ivan Maljukanovic
 * Author URI:        https://imoptimal.com/
 * License: GNU General Public License v3 or later
 * License URI: https://www.gnu.org/licenses/gpl-3.0.en.html
 * Text Domain:       gutenberg-dynamic-block
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

const GDB_VERSION = '1.0.0';

class Gutenberg_Dynamic_Block {

	/**
	 * The unique identifier of this plugin (slug).
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
    protected $plugin_name;
    
	/**
	 * The array of actions registered with WordPress.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      array    $actions    The actions registered with WordPress to fire when the plugin loads.
	 */
	protected $actions;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
        
        $this->plugin_name = 'gutenberg-dynamic-block';

        $this->actions = [];
		$this->filters = [];

        // Register hooks
		$this->hooks(); 

    }

    /**
     * Getters
     */
	public function get_plugin_name ( ) { return $this->plugin_name; }

	/**
	 * Register all hooks
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function hooks ( ) {
        $this->add_action('init', $this, 'register_dynamic_block_action');
        
    }

    /**
     * Registers the dynamic server side block JS script and its styles
     *
     * @since    1.0.0
     * @return void
     */
    public function register_dynamic_block_action ( ) {

        $block_name = 'fav-movie-quotes';

        $block_namespace = 'gutenberg-dynamic-block/' . $block_name;

        $script_slug = $this->plugin_name . '-' . $block_name;
        $style_slug = $this->plugin_name . '-' . $block_name . '-style';
		$editor_script_slug = $this->plugin_name . '-' . $block_name . '-editor-script';
        $editor_style_slug = $this->plugin_name . '-' . $block_name . '-editor-style';

        // The JS block script
        wp_enqueue_script( 
            $editor_script_slug, 
            plugin_dir_url( __FILE__ ) . '/build/index.js', 
            ['wp-editor', 'wp-blocks', 'wp-i18n', 'wp-element'], // Required scripts for the block
            GDB_VERSION
        );

        // The block style
        // It will be loaded on the editor and on the site
        wp_register_style(
            $style_slug,
            plugin_dir_url( __FILE__ )  . '/build/index.css', 
            ['wp-blocks'], // General style
            GDB_VERSION
        );

        // The block style for the editor only
        wp_register_style(
            $editor_style_slug,
            plugin_dir_url( __FILE__ ) . '/build/index.css', 
            ['wp-edit-blocks'], // Style for the editor
            GDB_VERSION
        );     
        
        // Registering the block
        register_block_type(
            $block_namespace,  // Block name with namespace
            [
				'api_version' => 2,
                'style' => $style_slug, // General block style slug
                'editor_style' => $editor_style_slug, // Editor block style slug
                'editor_script' => $editor_script_slug,  // The block script slug
				'script' => $script_slug,
                'render_callback' => [$this, 'block_dynamic_render_cb'], // The render callback
            ]
        );

    }

    /**
     * CALLBACK
     * 
     * Render callback for the dynamic block.
     * 
     * Instead of rendering from the block's save(), this callback will render the front-end
     *
     * @since    1.0.0
     * @param $att Attributes from the JS block
     * @return string Rendered HTML
     */
    public function block_dynamic_render_cb ( $att ) {

        // Coming from RichText, each line is an array's element
        $Content = $att['favQuotes'];

        $html = "<div id='fav-movie-quotes-box'><h5>Favourite Movie Quotes:</h5><p>$Content</p></div>";

        return $html;

    }

	/**
	 * Add a new action to the collection to be registered with WordPress.
	 *
	 * @since    1.0.0
	 * @param    string               $hook             The name of the WordPress action that is being registered.
	 * @param    object               $component        A reference to the instance of the object on which the action is defined.
	 * @param    string               $callback         The name of the function definition on the $component.
	 * @param    int                  $priority         Optional. The priority at which the function should be fired. Default is 10.
	 * @param    int                  $accepted_args    Optional. The number of arguments that should be passed to the $callback. Default is 1.
	 */
	protected function add_action( $hook, $component, $callback, $priority = 10, $accepted_args = 1 ) {
		$this->actions = $this->add( $this->actions, $hook, $component, $callback, $priority, $accepted_args );
	}
   
	/**
	 * A utility function that is used to register the actions and hooks into a single
	 * collection.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @param    array                $hooks            The collection of hooks that is being registered (that is, actions or filters).
	 * @param    string               $hook             The name of the WordPress filter that is being registered.
	 * @param    object               $component        A reference to the instance of the object on which the filter is defined.
	 * @param    string               $callback         The name of the function definition on the $component.
	 * @param    int                  $priority         The priority at which the function should be fired.
	 * @param    int                  $accepted_args    The number of arguments that should be passed to the $callback.
	 * @return   array                                  The collection of actions and filters registered with WordPress.
	 */
	private function add( $hooks, $hook, $component, $callback, $priority, $accepted_args ) {

		$hooks[] = [
			'hook'          => $hook,
			'component'     => $component,
			'callback'      => $callback,
			'priority'      => $priority,
			'accepted_args' => $accepted_args
        ];

		return $hooks;

	}    

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->run_adds();
    }
    
	/**
	 * Register the filters and actions with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run_adds() {

		foreach ( $this->actions as $hook ) {
			add_action( $hook['hook'], array( $hook['component'], $hook['callback'] ), $hook['priority'], $hook['accepted_args'] );
		}

	}
}

/*
 * BEGIN
 */
$gsb = new Gutenberg_Dynamic_Block();
$gsb->run();