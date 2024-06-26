<?php if( ! defined( "ABSPATH" ) ) exit() ?>

    <input type="hidden" name="wpfaction" value="legal_settings_save">

    <div class="wpf-opt-row">
        <div class="wpf-opt-intro">
            <svg xmlns="http://www.w3.org/2000/svg" height="60px" shape-rendering="geometricPrecision" text-rendering="geometricPrecision" image-rendering="optimizeQuality"
                 fill-rule="evenodd" clip-rule="evenodd" viewBox="0 0 498 512.17">
                <path d="M232.21 0c86.9 55.08 165.4 81.14 232.78 74.98 3.67 74.22-2.36 138.96-17.12 194.7-10-4.08-20.68-6.52-31.7-7 11.57-46.07 16.23-99.25 13.23-159.92-57.04 5.22-123.5-16.84-197.06-63.48C168.68 88.73 103 103.21 36.04 99.7c-2.97 113.09 16.9 198.24 55.29 260.18 28.38-23.73 76.71-20.15 99.6-51.62 1.65-2.43 2.41-3.74 2.39-4.81-.01-.56-24.83-31-27.06-34.55-5.85-9.3-16.8-21.93-16.8-32.82 0-6.15 4.85-14.17 11.8-15.96-.54-9.22-.91-18.57-.91-27.84 0-5.47.1-11.01.3-16.43.3-3.44.94-4.95 1.85-8.27a58.537 58.537 0 0 1 26.13-33.18c4.43-2.8 9.25-4.98 14.19-6.77 8.96-3.27 4.62-17.43 14.46-17.65 22.99-.59 60.81 19.51 75.54 35.48 9.39 10.38 14.75 21.92 15.07 35.92l-.93 40.27c4.08 1 8.66 4.19 9.66 8.28 3.15 12.71-10.04 28.53-16.18 38.64-5.65 9.33-27.26 34.79-27.28 35-.1 1.09.46 2.47 1.94 4.69 10.53 14.48 26.44 21.54 43.3 27.25-1.87 6.71-3.07 13.64-3.53 20.74-1.76 1.23-3.4 2.6-4.91 4.11l-.1.1c-6.73 6.75-10.93 16.04-10.93 26.26v93.19c-20.32 12.65-42.28 23.4-65.81 32.26C82.71 457.27-6.26 322.77.34 71.37 79.43 75.51 157.03 58.41 232.21 0zm105.67 375.54h3.88v-11.95c0-19.96 7.87-38.16 20.55-51.39 12.79-13.33 30.44-21.6 49.88-21.6s37.11 8.27 49.88 21.6c12.69 13.23 20.56 31.42 20.56 51.39v11.95h3.88c6.32 0 11.49 5.18 11.49 11.5v113.63c0 6.33-5.17 11.5-11.49 11.5H337.88c-6.33 0-11.49-5.17-11.49-11.5V387.04c-.01-6.32 5.16-11.5 11.49-11.5zm65.2 73.48-11.96 31.34h42.13l-11.08-31.77c7.04-3.62 11.85-10.95 11.85-19.41 0-12.06-9.77-21.82-21.84-21.82-12.05 0-21.82 9.76-21.82 21.82 0 8.8 5.21 16.38 12.72 19.84zm-39.57-73.48h97.35v-11.95c0-14.2-5.53-27.06-14.43-36.34-8.81-9.19-20.93-14.9-34.24-14.9-13.31 0-25.44 5.71-34.24 14.9-8.91 9.28-14.44 22.14-14.44 36.34v11.95z"></path>
            </svg>
            <div>
                <h3 style="font-weight:600; padding:0 0 5px 0; margin:0; color:#666666; font-size: 18px;">
					<?php _e( 'Forum Privacy Policy and GDPR compliant', 'wpforo' ) ?> &nbsp;|&nbsp; <a href="https://wpforo.com/docs/wpforo-v2/gdpr/" rel="noreferrer"
                                                                                                        style="text-decoration: none; font-weight: normal;" target="_blank"><?php _e(
							'Documentation',
							'wpforo'
						); ?></a>
                </h3>
                <p class="wpf-info">
					<?php _e(
						'The General Data Protection Regulation (GDPR) (Regulation (EU) 2016/679) is a regulation by which the European Parliament, the Council of the European Union and the European Commission intend to strengthen and unify data protection for all individuals within the European Union (EU). After four years of preparation and debate the GDPR was finally approved by the EU Parliament on 14 April 2016. Enforcement date: 25 May 2018 - at which time those organizations in non-compliance may face heavy fines. More info at',
						'wpforo'
					); ?>
                    <a href="https://www.eugdpr.org/key-changes.html" title="<?php _e( 'GDPR Key Changes', 'wpforo' ) ?>" target="_blank" rel="noreferrer">GDPR Portal</a>
                </p>
            </div>
        </div>
        <div class="wpf-opt-doc">&nbsp;</div>
    </div>
<?php

WPF()->settings->form_field( 'legal', 'contact_page_url' );
WPF()->settings->form_field( 'legal', 'checkbox_email_password' );
WPF()->settings->form_field( 'legal', 'checkbox_terms_privacy' );
WPF()->settings->form_field( 'legal', 'page_terms' );
WPF()->settings->form_field( 'legal', 'page_privacy' );
WPF()->settings->form_field( 'legal', 'checkbox_forum_privacy' );
WPF()->settings->form_field( 'legal', 'forum_privacy_text' );
WPF()->settings->form_field( 'legal', 'checkbox_fb_login' );
WPF()->settings->form_field( 'legal', 'cookies' );
?>

    <div class="wpf-subtitle">
        <span class="dashicons dashicons-admin-users"></span> <?php _e( 'Forum Rules', 'wpforo' ) ?>
    </div>
<?php
WPF()->settings->form_field( 'legal', 'rules_checkbox' );
WPF()->settings->form_field( 'legal', 'rules_text' );
