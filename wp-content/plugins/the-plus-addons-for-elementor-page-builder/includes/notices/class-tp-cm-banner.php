<?php
/**
 * Exit if accessed directly.
 *
 * @link       https://posimyth.com/
 * @since      6.4.3
 *
 * @package    Theplus
 * @subpackage ThePlus/Notices
 * */

namespace Tp\Notices\TPAECyberMondayBanner;

/**
 * Exit if accessed directly.
 * */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ! class_exists( 'Tpae_CyberMonday_Banner' ) ) {

	/**
	 * This class used for only elementor widget load
	 *
	 * @since 6.4.3
	 */
	class Tpae_CyberMonday_Banner {

		/**
		 * Instance
		 *
		 * @since 6.4.3
		 * @var instance of the class.
		 */
		private static $instance = null;

		/**
		 * Instance
		 *
		 * Ensures only one instance of the class is loaded or can be loaded.
		 *
		 * @since 6.4.3
		 * @return instance of the class.
		 */
		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Constructor
		 *
		 * Perform some compatibility checks to make sure basic requirements are meet.
		 *
		 * @since 6.4.3
		 * @access public
		 */
		public function __construct() {

			/** TPAE CM Banner*/
			if ( ! get_option( 'tpae_cmsale_notice_dismissed' ) ) {
				add_action( 'admin_notices', array( $this, 'tpae_cm_sale_banner' ) );
			}

			/** TPAE CM Banner Close*/
			add_action( 'wp_ajax_tpae_cybermonday_dismiss_notice', array( $this, 'tpae_cybermonday_dismiss_notice' ) );
		}

		/**
		 * Cyber Monday offer Banner
		 *
		 * @since 6.0.4
		 */
		public function tpae_cm_sale_banner() {
			$nonce  = wp_create_nonce( 'tpae-cybermonday-banner' );
			$screen = get_current_screen();
			if ( ! $screen ) {
				return;
			}

			$allowed_parents = array( 'index', 'elementor', 'themes', 'edit', 'plugins' );

			$et_plugin_status = apply_filters( 'tpae_get_plugin_status','template-kit-import/template-kit-import.php' );

			if ( get_option( 'tpae_onbording_end' ) || 'not_installed' !== $et_plugin_status ) {
				$allowed_parents[] = 'theplus_welcome_page';
			}

			$parent_base = ! empty( $screen->parent_base ) && in_array( $screen->parent_base, $allowed_parents, true );

			if ( ! $parent_base ) {
				return;
			}

			$notice_text = __( 'Skip multiple plugin subscriptions - Upgrade to The Plus Addons for Elementor Pro at 50% OFF.', 'tpebl');
			$desc_text   = __( 'Our Cyber Monday Sale is live! Upgrade this season and get upto 50% OFF on the pro version.', 'tpebl');

			$btn_text = __( 'Get Deal', 'tpebl');
			$btn_link = 'https://theplusaddons.com/pricing/?utm_source=wpbackend&utm_medium=admin&utm_campaign=pluginpage';

			if ( defined( 'THEPLUS_VERSION' ) ) {
				$license_data = get_option( 'tpaep_licence_data' );
				$license_key  = $license_data ? $license_data['license_key'] : '';
				
				$notice_text = __( 'Upgrade to Lifetime Unlimited Sites Plan and Save $120 Today! - Cyber Monday 50% OFF', 'tpebl');
				$desc_text   = __( 'Upgrade now to Lifetime Plan for Unlimited Sites, Continuous Plugin Updates, Lifetime Premium Support and much more at an unbeatable price.', 'tpebl');
				$btn_text    = __( 'Upgrade Now', 'tpebl');
				
				if( ! empty( $license_key ) ) {
					$btn_link = sprintf(
						'https://store.posimyth.com/checkout/?edd_action=sl_license_upgrade&license_id=%s&upgrade_id=5&discount=UPGRADEBF30',
						$license_key
					);
				}
			}

			echo '<div class="notice tpae-notice-show tpae-cm-banner is-dismissible" style="border-left: 4px solid #006ADF;">
				<div class="inline" style="display: flex;column-gap: 12px;align-items: center;padding: 15px 10px;position: relative;    margin-left: 0px;">
					<img style="max-width:136px;max-height:136px;" src="' . esc_url( L_THEPLUS_URL . '/assets/images/cm-banner.png' ) . '" />
					<div style="margin: 0 10px; color:#000;display:flex;flex-direction:column;gap:10px;">  
						<div style="font-size:16px;font-weight:600;letter-spacing:0.1px;">' . esc_html__( $notice_text ) . '</div>
						<div style="font-size:12px;color:#5D5D5D;"> ' . esc_html__( $desc_text ) . ' </div>
						<div style="display: flex;column-gap: 12px;">  <span> • ' . esc_html__( '1,000+ Elementor Templates', 'tpebl' ) . '</span>  <span> • ' . esc_html__( '120+ Elementor Widgets', 'tpebl' ) . '</span>  <span> • ' . esc_html__( 'Theme Builder for Elementor & Code Snippets', 'tpebl' ) . '</span>  <span> • ' . esc_html__( 'Trusted by 100K+ Users', 'tpebl' ) . '</span> </div>
						<div class="tpae-cm-btn" style="display:flex;column-gap:10px;flex-wrap:wrap;margin-top:3px;">
							<a href="' . esc_url( $btn_link ) . '" class="button tpae-deal-btn" target="_blank" rel="noopener noreferrer" style=" width:max-content;color:#fff;border-color:#DF241B;background:#DF241B;padding:3px 22px;border-radius:5px;font-weight:500;">' . esc_html__( $btn_text ) . '</a>
							<a href="https://store.posimyth.com/offers/?utm_source=wpbackend&utm_medium=admin&utm_campaign=pluginpage" class="button tpae-offer-btn" target="_blank" rel="noopener noreferrer" style=" width:max-content;color:#5e5e5e;border:1px solid #5e5e5e;background:#ffffff00;padding:3px 22px;border-radius:5px;font-weight:500;">' . esc_html__( 'View More Offers', 'tpebl' ) . '</a>';

						echo '</div>
					</div>
				</div>
			</div>';

			echo '<style> .notice.tpae-notice-show.tpae-cm-banner a.button.tpae-deal-btn:hover{background:#B91D15!important;}.notice.tpae-notice-show.tpae-cm-banner a.button.tpae-offer-btn:hover{background:#f3f3f3 !important;}</style>';
			?>
			<script>
                jQuery(document).on('click', '.tpae-cm-banner.tpae-notice-show .notice-dismiss', function(e) {
                    e.preventDefault();
					
                    jQuery.ajax({
                        url: ajaxurl,
                        type: 'POST',
                        data: {
                            action: 'tpae_cybermonday_dismiss_notice',
                            security: "<?php echo esc_attr( $nonce ); ?>",
                        },
                        success: function(response) {
                            jQuery('.tpae-cm-banner').hide();
                        }
                    });
                });
            </script>
			<?php
		}

		/**
		 * It's is use for Save key in database for the TPAE Cyber Monday Banner 
		 *
		 * @since 6.4.3
		 */
		public function tpae_cybermonday_dismiss_notice() {
			$get_security = ! empty( $_POST['security'] ) ? sanitize_text_field( wp_unslash( $_POST['security'] ) ) : '';
			
		
			if ( ! $get_security || ! wp_verify_nonce( $get_security, 'tpae-cybermonday-banner' ) ) {
				wp_send_json_error( 'Security check failed!' );
			}

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( __( 'You are not allowed to perform this action', 'tpebl' ) );
			}

			$get_type = ! empty( $_POST['type'] ) ? sanitize_text_field( wp_unslash( $_POST['type'] ) ) : '';

			update_option( 'tpae_cmsale_notice_dismissed', true );
		
			wp_send_json_success();
		}
		
	}

	Tpae_CyberMonday_Banner::instance();
}
