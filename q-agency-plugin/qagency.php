<?php

/**
 * Plugin Name: Q-Agency - Test Project (Movies Plugin)
 * Description:
 * Author:      Ivan Maljukanovic
 * Author URI:  https://imoptimal.com
 * Version:     1.0.0
 * Requires at least: 4.9.8
 * Requires PHP: 5.6
 * License: GNU General Public License v3 or later
 * License URI: https://www.gnu.org/licenses/gpl-3.0.en.html
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('Q_Agency_Plugin')) {
    class Q_Agency_Plugin
    {
        // Plugin version number
        private $version = '1.0.0';
        // Database table version number
        private $db_version = '1.0';
        // Textdomain
        private $textdomain = 'qagencymovies';
        public function __construct()
        {
            $this->init();
        }

        // Init function separated from default construct func
        public function init()
        {
            add_action('wp_enqueue_scripts', array($this, 'frontend_assets'));
            add_action('init', array($this, 'add_custom_post_type'));
            add_action('add_meta_boxes', array($this, 'add_custom_metabox'));
            add_action('save_post', array($this, 'save_custom_metabox'));
            add_action('rest_api_init', array($this, 'qagencymovies_metabox_restapi'));
        }

        /**
         * Adding css and js files to frontend
         *
         * @since    1.0.0
         * */
        public function frontend_assets()
        {
            wp_enqueue_style(
                'wp-q-agency-style',
                plugin_dir_url(__FILE__) . 'style.css',
                [],
                $this->version
            );
            wp_enqueue_script(
                'wp-q-agency-script',
                plugin_dir_url(__FILE__) . 'script.js',
                [],
                $this->version,
                true
            );
        }

        /**
         * Add custom post type - Movies
         *
         * @since    1.0.0
         */
        public function add_custom_post_type()
        {
            register_post_type('qagencymovies',
                array(
                    'labels'      => array(
                        'name'          => __('Movies', $textdomain),
                        'singular_name' => __('Movie', $textdomain),
                    ),
                        'public'      => true,
                        'has_archive' => true,
                        'show_in_rest' => true,
                )
            );
        }

        /**
         * Add custom metabox (movie title) for custom post type - Movies
         *
         * @since    1.0.0
         */
        public function add_custom_metabox() {
            $screens = ['qagencymovies'];
            foreach ( $screens as $screen ) {
                add_meta_box(
                    'qagencymovies_box_id',
                    'Movies Title (metabox)',
                    [$this, 'display_qagencymovies_metabox'],
                    $screen
                );
            }
        }

        /**
	 * Save the metabox data
	 *
     * @since    1.0.0
	 * @param int $post_id  The post ID.
	 */
	public function save_custom_metabox( int $post_id ) {
		if ( array_key_exists( 'qagencymovies_field', $_POST ) ) {
			update_post_meta(
				$post_id,
				'_qagencymovies_meta_key',
				$_POST['qagencymovies_field']
			);
		}
	}

        /**
         * Custom metabox display in the post editor
         *
         * @since    1.0.0
         * @param \WP_Post $post   Post object.
         */
        public static function display_qagencymovies_metabox($post) {
$value = get_post_meta( $post->ID, '_qagencymovies_meta_key', true );
if ($value == '') { ?>
<input name="qagencymovies_field" type="text">
<?php } else { ?>
<input name="qagencymovies_field" type="text" value="<?php echo $value ?>">
<?php }
}

        /**
         * Make metabox data avaliable on the front-end (through REST API)
         *
         * @since    1.0.0
         */
        public function qagencymovies_metabox_restapi() {
            register_rest_field('qagencymovies', 'qagencymovies_meta_key', array(
                'get_callback' => [$this, 'get_qagencymovies_metabox'],
            ));
        }

        /**
         * Get the value of metabox to send through REST API
         *
         * @since    1.0.0
         */
        public static function get_qagencymovies_metabox($object) {
            $post_id = $object['id'];
            return get_post_meta($post_id);
        }

    }
    $wp_q_agency_object = new Q_Agency_Plugin();
}