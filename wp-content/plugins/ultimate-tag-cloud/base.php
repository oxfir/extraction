<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
final class Ultimate_Tag_Cloud_Extension {

    private static $_instance = null;

    public function __construct() {
        
        add_action( 'plugins_loaded', [ $this, 'ultimate_tag_cloud_init' ] );
    }

    public static function init() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function ultimate_tag_cloud_init() {
        add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'ultimate_tag_cloud_elementor_editor_css' ] );
        add_action( 'elementor/elements/categories_registered', [ $this, 'ultimate_tag_cloud_register_elementor_category' ] );
        add_action( 'wp_enqueue_scripts', [ $this, 'ultimate_tag_cloud_enqueue_style' ] );
        add_action( 'elementor/widgets/widgets_registered', [ $this, 'ultimate_tag_cloud_register_elementor_widget' ] );
    }

    // Register a custom Elementor category
    public function ultimate_tag_cloud_register_elementor_category($elements_manager) {
        $elements_manager->add_category(
            'ultimate_tag_cloud_category',
            [
                'title' => esc_html__('Ultimate Tag Cloud', 'ultimate-tag-cloud'),
                'icon' => 'eicon-theme-builder',
            ]
        );
    }

    // Including editor CSS
    public function ultimate_tag_cloud_elementor_editor_css() {
        wp_enqueue_style(
            'ultimate-tag-cloud-elementor-editor-css',
            ULTIMATE_TAG_CLOUD_DIR_URL . 'admin/assets/css/el-ultimate-tag-cloud-editor.css', [], ULTIMATE_TAG_CLOUD_VERSION
        );
    }

    // Including widget CSS
    public function ultimate_tag_cloud_enqueue_style() {
        wp_enqueue_style(
            'ultimate-tag-cloud-style',
            ULTIMATE_TAG_CLOUD_DIR_URL . 'widget/css/el-ultimate-tag-cloud.css', [], ULTIMATE_TAG_CLOUD_VERSION
        );
    }

    // Register the Elementor widget
    public function ultimate_tag_cloud_register_elementor_widget() {
        include ULTIMATE_TAG_CLOUD_DIR_PATH . 'widget/el-ultimate-tag-cloud.php';
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Ultimate_Tag_Cloud_Register_Elementor_Widget());
    }
}

// Initialize the plugin
function ultimate_tag_cloud_addon() {
    return Ultimate_Tag_Cloud_Extension::init();
}
ultimate_tag_cloud_addon();
