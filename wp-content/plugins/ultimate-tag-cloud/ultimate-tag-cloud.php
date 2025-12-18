<?php
/*
* @wordpress-plugin
* Plugin Name:       Ultimate Tag Cloud Elementor Addon
* Plugin URI:        https://wordpress.org/plugins/ultimate-tag-cloud/
* Description:       Ultimate Tag Cloud created by RSTheme
* Version:           1.0.2
* Requires at least: 6.3
* Requires PHP:      7.4
* Author:            RSTheme
* Author URI:        https://rstheme.com/
* Text Domain:       ultimate-tag-cloud
* Domain Path:       /languages
* License:           GPLv2 or later
* License URI:       http://www.gnu.org/licenses/gpl-2.0.html
*/

if ( ! defined( 'ABSPATH' ) ) exit;

// Define constants
define( 'ULTIMATE_TAG_CLOUD_FILE', __FILE__);
define( 'ULTIMATE_TAG_CLOUD_DIR_PATH', plugin_dir_path(__FILE__));
define( 'ULTIMATE_TAG_CLOUD_DIR_URL', plugin_dir_url(__FILE__));
define( 'ULTIMATE_TAG_CLOUD_VERSION', '1.0.2' );

// Load translation files
function ultimate_tag_cloud_load_textdomain() {
    load_plugin_textdomain('ultimate-tag-cloud', false, dirname(plugin_basename(__FILE__)) . '/languages/');
}
add_action('plugins_loaded', 'ultimate_tag_cloud_load_textdomain');

// Check if Elementor is active
function ultimate_tag_cloud_is_elementor_active() {
    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    return is_plugin_active( 'elementor/elementor.php' );
}

// Enqueue scripts only if Elementor is active
function ultimate_tag_cloud_enqueue_scripts() {
    if ( ultimate_tag_cloud_is_elementor_active() ) {
        wp_enqueue_script('jquery');
    }
}
add_action("wp_enqueue_scripts", "ultimate_tag_cloud_enqueue_scripts");

// Include the core functionality only if Elementor is active
if ( ultimate_tag_cloud_is_elementor_active() ) {
    require_once ULTIMATE_TAG_CLOUD_DIR_PATH . 'base.php';
}

// Display an admin notice if Elementor is not active
function ultimate_tag_cloud_admin_notice() {
    if ( ! ultimate_tag_cloud_is_elementor_active() && current_user_can('activate_plugins') ) {
        ?>
        <div class="notice notice-warning">
            <p><?php echo esc_html__( 'Ultimate Tag Cloud plugin requires Elementor to be installed and active.', 'ultimate-tag-cloud' ); ?></p>
        </div>
        <?php
    }
}
add_action( 'admin_notices', 'ultimate_tag_cloud_admin_notice' );

// Get All Post Types
function ultimate_tag_cloud_get_posts_types() {
    $post_types = get_post_types(
        array(
            'public' => true,
        ),
        'objects'
    );
    $options = array();
    foreach ( $post_types as $post_type ) {
        if ( 'attachment' === $post_type->name ) {
            continue;
        }
        $options[ $post_type->name ] = $post_type->label;
    }
    return $options;
}
