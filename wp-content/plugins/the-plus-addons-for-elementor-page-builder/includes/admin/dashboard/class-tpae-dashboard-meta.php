<?php
/**
 * The file store Database Default Entry
 *
 * @link       https://posimyth.com/
 * @since      6.0.0
 *
 * @package    the-plus-addons-for-elementor-page-builder
 */

/**Exit if accessed directly.*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Tpae_Dashboard_Meta' ) ) {

	/**
	 * Tpae_Dashboard_Meta
	 *
	 * @since 6.0.0
	 */
	class Tpae_Dashboard_Meta {

		/**
		 * Member Variable
		 *
		 * @var instance
		 */
		private static $instance;

		/**
		 * Option key, and option page slug
		 *
		 * @var string
		 */
		private $key = 'theplus_options';

		/**
		 *  Initiator
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Define the core functionality of the plugin.
		 *
		 * @since 6.0.0
		 */
		public function __construct() {
			if ( current_user_can( 'manage_options' ) ) {
				add_action( 'admin_menu', array( $this, 'tpae_add_dashboard_menu' ) );
				add_action( 'admin_enqueue_scripts', array( $this, 'tpae_enqueue_scripts' ) );
			}

			if ( is_admin() ) {
				add_filter(
					'admin_body_class',
					function ( $classes ) {
						if ( isset( $_GET['page'] ) && $_GET['page'] === 'nxt_builder' ) {
							$classes .= ' post-type-nxt_builder nxt-page-nexter-builder ';
						}
						return $classes;
					},
					11
				);
			}
		}

		/**
		 * Dashboard Build File loaded.
		 *
		 * @since 6.0.0
		 * @version 6.4.2
		 *
		 * @param string $page use for check page type.
		 */
		public function tpae_enqueue_scripts( $page ) {

			wp_enqueue_style( 'tpae-db-icons-library', L_THEPLUS_ASSETS_URL . 'fonts/style.css', array(), L_THEPLUS_VERSION, false );

			$et_plugin_status    = apply_filters( 'tpae_get_plugin_status', 'template-kit-import/template-kit-import.php' );
			$wdkit_plugin_status = apply_filters( 'tpae_get_plugin_status', 'wdesignkit/wdesignkit.php' );

			$onbording_get = get_option( 'tpae_onbording_end' );

			$onbording_set = '';

			if ( $onbording_get || 'active' === $et_plugin_status || 'inactive' === $et_plugin_status ) {
				$onbording_set = 'hide';
			}

			$nexter_et_plugin_status = apply_filters( 'tpae_get_plugin_status', 'nexter-extension/nexter-extension.php' );

			$next_et_status = '';
			$extensionactivate = '';

			if ( 'active' === $nexter_et_plugin_status ) {
				$next_et_status = true;
				$extensionactivate = 'active';
			} elseif ('inactive' === $nexter_et_plugin_status) {
				$next_et_status = false;
				$extensionactivate = 'inactive';
			} else {
				$next_et_status = false;
				$extensionactivate = '';
			}

			$wdesign_plugin_status = apply_filters( 'tpae_get_plugin_status', 'wdesignkit/wdesignkit.php' );

			$wdkit_status = '';
			$wdkactive = '';
			if ( 'active' === $wdesign_plugin_status ) {
				$wdkit_status = true;
				$wdkactive = 'active';
			} elseif('inactive' === $wdesign_plugin_status) {
				$wdkit_status = false;
				$wdkactive = 'inactive';
			} else {
				$wdkit_status = false;
				$wdkactive = '';
			}

			$get_whats_new_notification = get_option( 'tpae_whats_new_notification' );

			$show_whats_new = '';

			if ( $get_whats_new_notification !== TPAE_WHATS_NEW_NOTIFICETIONS ) {
				$show_whats_new = true;
			} else{
				$show_whats_new = false;
			}

			if ( isset( $_GET['page'] ) && $_GET['page'] === 'nxt_builder' && ! defined( 'NEXTER_EXT' ) ) {
					wp_enqueue_style( 'nexter-theme-builder', L_THEPLUS_URL . 'includes/nxt-ext/build/index.css', array(), L_THEPLUS_VERSION, 'all' );

					wp_enqueue_script( 'nexter-theme-builder', L_THEPLUS_URL . 'includes/nxt-ext/build/index.js', array( 'react', 'react-dom', 'wp-dom-ready', 'wp-i18n' ), L_THEPLUS_VERSION, true );

					$nexter_theme_builder_config = array(
						'adminUrl'          => admin_url(),
						'ajaxurl'           => admin_url( 'admin-ajax.php' ),
						'ajax_nonce'        => wp_create_nonce( 'nexter_admin_nonce' ),
						'assets'            => L_THEPLUS_URL . 'includes/nxt-ext/assets/',
						'is_pro'            => ( defined( 'NXT_PRO_EXT' ) ) ? true : false,
						'dashboard_url'     => admin_url( 'admin.php?page=theplus_welcome_page' ),
						'version'           => L_THEPLUS_VERSION,
						'import_temp_nonce' => wp_create_nonce( 'nxt_ajax' ),
						'wdkPlugin'         => $wdkit_status,
						'wdkactive' => $wdkactive,
						'extensioninstall'  => $next_et_status,
						'extensionactivate' => $extensionactivate,
						'tpae'              => true,
						'dashboard_url' => admin_url( 'admin.php?page=theplus_welcome_page'),
						'tpae_nonce'        => wp_create_nonce( 'tpae-db-nonce' ),
					);

					wp_localize_script( 'nexter-theme-builder', 'nexter_theme_builder_config', $nexter_theme_builder_config );
			}

			if ( 'toplevel_page_theplus_welcome_page' === $page ) {

				wp_enqueue_script( 'tpae-db-build', L_THEPLUS_URL . 'build/index.js', array( 'wp-i18n', 'wp-element', 'wp-components' ), L_THEPLUS_VERSION . time(), true );
				wp_localize_script(
					'tpae-db-build',
					'tpae_db_object',
					array(
						'ajax_url'             => admin_url( 'admin-ajax.php' ),
						'nonce'                => wp_create_nonce( 'tpae-db-nonce' ),
						'tpae_nonce_old'       => wp_create_nonce( 'theplus-addons' ),
						'tpae_url'             => L_THEPLUS_URL,
						'tpae_version'         => defined( 'THEPLUS_VERSION' ) ? THEPLUS_VERSION : L_THEPLUS_VERSION,
						'tpae_wdkit_url'       => L_THEPLUS_WDKIT_URL,
						'tpae_wp_version'      => get_bloginfo( 'version' ),
						'tpae_pro'             => defined( 'THEPLUS_VERSION' ) ? 1 : 0,
						'tpae_whitelabel'      => get_option( 'theplus_white_label' ),
						'onboarding_setup'     => $onbording_set,
						'envato_plugin_status' => $et_plugin_status,
						'wdkit_plugin_status'  => $wdkit_plugin_status,
						'show_whats_new'    => $show_whats_new
					)
				);

				wp_set_script_translations( 'tpae-db-build', 'tpebl' );

				wp_enqueue_style( 'tpae-db-build', L_THEPLUS_URL . 'build/index.css', array(), L_THEPLUS_VERSION . time(), 'all' );

			}
		}

		/**
		 * Dashboard Build File loaded.
		 *
		 * @since 6.1.0
		 */
		public function tpae_add_dashboard_menu() {

			$setting_name = esc_html__( 'The Plus Addons', 'tpebl' );
			if ( defined( 'THEPLUS_VERSION' ) ) {
				$options = get_option( 'theplus_white_label' );

				$setting_name = ! empty( $options['tp_plugin_name'] ) ? $options['tp_plugin_name'] : __( 'The Plus Addons', 'tpebl' );
			}

			$et_plugin_status    = apply_filters( 'tpae_get_plugin_status', 'template-kit-import/template-kit-import.php' );
			$wdkit_plugin_status = apply_filters( 'tpae_get_plugin_status', 'wdesignkit/wdesignkit.php' );

			add_menu_page( $setting_name, $setting_name, 'manage_options', 'theplus_welcome_page', array( $this, 'tpae_admin_page_display' ), 'dashicons-plus-settings', 67.1 );

			add_submenu_page( 'theplus_welcome_page', esc_html__( 'Widgets', 'tpebl' ), esc_html__( 'Widgets', 'tpebl' ), 'manage_options', 'theplus_welcome_page#/widgets', array( $this, 'tpae_admin_page_display' ), );
			add_submenu_page( 'theplus_welcome_page', esc_html__( 'Extensions', 'tpebl' ), esc_html__( 'Extensions', 'tpebl' ), 'manage_options', 'theplus_welcome_page#/extension', array( $this, 'tpae_admin_page_display' ) );
			add_submenu_page(
				'theplus_welcome_page',
				esc_html__( 'Theme Builder', 'tpebl' ),
				esc_html__( 'Theme Builder', 'tpebl' ),
				'manage_options',
				'nxt_builder',
				array( $this, 'nexter_theme_builder_display' )
			);

			if ( 'not_installed' === $et_plugin_status || 'active' === $wdkit_plugin_status ) {
				add_submenu_page( 'theplus_welcome_page', esc_html__( 'Starter Templates', 'tpebl' ), esc_html__( 'Starter Templates', 'tpebl' ), 'manage_options', 'theplus_welcome_page#/elementor_templates', array( $this, 'tpae_admin_page_display' ) );
			}

			add_submenu_page( 'theplus_welcome_page', esc_html__( 'Settings', 'tpebl' ), esc_html__( 'Settings', 'tpebl' ), 'manage_options', 'theplus_welcome_page#/settings', array( $this, 'tpae_admin_page_display' ) );

			if ( ! defined( 'THEPLUS_VERSION' ) ) {
				add_submenu_page( 'theplus_welcome_page', esc_html__( 'Upgrade Now', 'tpebl' ), esc_html__( 'Upgrade Now', 'tpebl' ) . '<i class="theplus-i-crown path1 path2"></i>', 'manage_options', esc_url( 'https://theplusaddons.com/pricing?utm_source=wpbackend&utm_medium=dashboard&utm_campaign=plussettings' ) );
			}

			if ( defined( 'THEPLUS_VERSION' ) ) {

				$license_data = get_option( 'tpaep_licence_data' );

				if ( empty( $license_data ) && empty( $license_data['license_key'] ) ) {
					add_submenu_page( 'theplus_welcome_page', esc_html__( 'Activate', 'tpebl' ), esc_html__( 'Activate', 'tpebl' ) . '<i class="theplus-i-info-fill activate"></i>', 'manage_options', 'theplus_welcome_page#/activate_pro', array( $this, 'tpae_admin_page_display' ) );
				}
			}

			add_action( 'admin_footer', array( $this, 'tpae_link_in_new_tab' ) );

			// Hook to modify the submenu head title.
			add_action( 'admin_menu', array( $this, 'tpae_submenu_head_title' ), 101 );

			global $pagenow;

			if ( $pagenow === 'admin.php' && isset( $_GET['page'] ) && 'theplus_welcome_page' === $_GET['page'] ) {
				add_action( 'admin_footer', array( $this, 'tpae_highlight_submenu_js' ) );
			}
		}

		public function tpae_highlight_submenu_js() {
			?>
			<script type="text/javascript">
				document.addEventListener('DOMContentLoaded', function() {
					function setActiveSubmenu() {
						var hash = window.location.hash;
						var menuLinks = document.querySelectorAll('#toplevel_page_theplus_welcome_page .wp-submenu a');

						menuLinks.forEach(function(link) {
							link.parentElement.classList.remove('current');

							if ( !hash ) {
								if (link.getAttribute('href') === 'admin.php?page=theplus_welcome_page' || link.getAttribute('href') === 'admin.php?page=theplus_welcome_page#/'){
									link.parentElement.classList.add('current');
								}
							} else if ( '#/' === hash ) {
								if (link.getAttribute('href') === 'admin.php?page=theplus_welcome_page' || link.getAttribute('href') === 'admin.php?page=theplus_welcome_page#/'){
									link.parentElement.classList.add('current');
								}
							} else if ('#/upgrade_now' === hash) {
								if (link.getAttribute('href') === 'https://theplusaddons.com/pricing?utm_source=wpbackend&utm_medium=dashboard&utm_campaign=plussettings'){
									link.parentElement.classList.add('current');
								}
							}else {
								let getLink = link.getAttribute('href').includes(hash);
								if (hash.includes('?')) {
									hash = hash.split('?')[0];
								}

								if (hash && link.getAttribute('href').includes(hash)) {
									link.parentElement.classList.add('current');
								}
							}
						});
					}

					setActiveSubmenu();
					window.addEventListener('hashchange', setActiveSubmenu);
				});
			</script>
			<?php
		}

		/**
		 * Parent Page Rename in Sub menu.
		 *
		 * @since 6.0.0
		 */
		public function tpae_submenu_head_title() {
			global $submenu;

			if ( isset( $submenu['theplus_welcome_page'] ) ) {
				$submenu['theplus_welcome_page'][0][0] = esc_html__( 'Dashboard', 'tpebl' );
				$submenu['theplus_welcome_page'][0][2] = 'admin.php?page=theplus_welcome_page#/';
			}
		}

		public function tpae_render_settings_page() {
			echo '<div class="wrap"><h1>' . esc_html__( 'Plugin Settings', 'tpebl' ) . '</h1></div>';
		}

		/**
		 * Open Link in New Tab WordPress Menu
		 *
		 * @since 6.0.0
		 */
		public function tpae_link_in_new_tab() {
			?>
			<script type="text/javascript">
				document.addEventListener('DOMContentLoaded', function() {
					var upgradeLink = document.querySelector('a[href*="https://theplusaddons.com/pricing"]');
					if ( upgradeLink ) {
						upgradeLink.setAttribute('target', '_blank');
						upgradeLink.setAttribute('rel', 'noopener noreferrer');
					}
				});
			</script>
			<?php
		}

		/**
		 * Theme Builder Render html
		 *
		 * @since 6.4.2
		 */
		public function nexter_theme_builder_display() {
			echo '<div id="nexter-theme-builder"></div>';
		}

		/**
		 * Add Dashboard HTML with js
		 *
		 * @since 6.0
		 */
		public function tpae_admin_page_display() {
			echo '<div id="theplus-app"></div>';
		}
	}

	Tpae_Dashboard_Meta::get_instance();
}