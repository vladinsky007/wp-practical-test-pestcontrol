<?php
/**
 * Configure Email
 *
 * @package  UR_Settings_Registration_Approved_Email
 * @extends  UR_Settings_Email
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'UR_Settings_Registration_Approved_Email', false ) ) :

	/**
	 * UR_Settings_Registration_Approved_Email Class.
	 */
	class UR_Settings_Registration_Approved_Email {
		/**
		 * UR_Settings_Registration_Approved_Email Id.
		 *
		 * @var string
		 */
		public $id;

		/**
		 * UR_Settings_Registration_Approved_Email Title.
		 *
		 * @var string
		 */
		public $title;

		/**
		 * UR_Settings_Registration_Approved_Email Description.
		 *
		 * @var string
		 */
		public $description;

		/**
		 * Constructor.
		 */
		public function __construct() {
			$this->id          = 'registration_approved_email';
			$this->title       = __( 'Registration Approved Email', 'user-registration' );
			$this->description = __( 'Email sent to the user notifying the registration is approved by site admin', 'user-registration' );
		}

		/**
		 * Get settings
		 *
		 * @return array
		 */
		public function get_settings() {

			/**
			 * Filter to add the options on settings.
			 *
			 * @param array Options to be enlisted.
			 */
			$settings = apply_filters(
				'user_registration_registration_approved_email',
				array(
					'title'    => __( 'Emails', 'user-registration' ),
					'sections' => array(
						'registration_approved_email' => array(
							'title'        => __( 'Registration Approved Email', 'user-registration' ),
							'type'         => 'card',
							'desc'         => '',
							'back_link'    => ur_back_link( __( 'Return to emails', 'user-registration' ), admin_url( 'admin.php?page=user-registration-settings&tab=email' ) ),
							'preview_link' => ur_email_preview_link(
								__( 'Preview', 'user-registration' ),
								$this->id
							),
							'settings'     => array(
								array(
									'title'    => __( 'Enable this email', 'user-registration' ),
									'desc'     => __( 'Enable this email sent to the user notifying the registration is approved by site admin.', 'user-registration' ),
									'id'       => 'user_registration_enable_registration_approved_email',
									'default'  => 'yes',
									'type'     => 'toggle',
									'autoload' => false,
								),
								array(
									'title'    => __( 'Email Subject', 'user-registration' ),
									'desc'     => __( 'The email subject you want to customize.', 'user-registration' ),
									'id'       => 'user_registration_registration_approved_email_subject',
									'type'     => 'text',
									'default'  => __( 'Congratulations! Registration approved on {{blog_info}}', 'user-registration' ),
									'css'      => 'min-width: 350px;',
									'desc_tip' => true,
								),
								array(
									'title'    => __( 'Email Content', 'user-registration' ),
									'desc'     => __( 'The email content you want to customize.', 'user-registration' ),
									'id'       => 'user_registration_registration_approved_email',
									'type'     => 'tinymce',
									'default'  => $this->ur_get_registration_approved_email(),
									'css'      => 'min-width: 350px;',
									'desc_tip' => true,
								),
							),
						),
					),
				)
			);

			/**
			 * Filter to get the settings.
			 *
			 * @param array $settings Setting options to be enlisted.
			 */
			return apply_filters( 'user_registration_get_settings_' . $this->id, $settings );
		}

		/**
		 * Email Format.
		 *
		 * @return string $message Message content for registration approved email.
		 */
		public function ur_get_registration_approved_email() {

			/**
			 * Filter to modify the message content for registration approved email.
			 *
			 * @param string $message Message content for registration approved email to be overridden.
			 */
			$message = apply_filters(
				'user_registration_get_registration_approved_email',
				sprintf(
					__(
						'Hi {{username}}, <br/>

Your registration on <a href="{{home_url}}">{{blog_info}}</a>  has been approved. <br/>

Please visit \'<b>My Account</b>\' page to edit your account details and create your user profile on <a href="{{home_url}}">{{blog_info}}</a>. <br/>

Thank You!',
						'user-registration'
					)
				)
			);

			return $message;
		}
	}
endif;

return new UR_Settings_Registration_Approved_Email();
