<?php

/*
Plugin Name: Mobiplus Subscriber form
Plugin URI: http://wordpress.org/plugins/mobiplus-subscription/
Description: Enables subscription to EMAIL and SMS lists from WP web site
Version: 1.1
Author: Mobiplus
Author URI: http://www.mobiplus.se
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

class mobiplus_subscribeform {

	function __construct() {
		add_shortcode( 'MPSUBSCRIBEFORM', array( $this, 'wp_mv_subscribeform_display_form' ) );
		add_shortcode( 'mpsubcribeform', array( $this, 'wp_mv_subscribeform_display_form' ) );

		add_action( 'admin_menu', array( $this, 'wp_mv_subscribeform_admin_menu' ) );
		add_action( 'plugins_loaded', array( $this, 'mp_subscribeform_plugins_loaded' ) );
		add_action( 'wp_ajax_nopriv_mp_subscribeform_submit', array( $this, 'mp_subscribeform_submit' ) );
		add_action( 'wp_ajax_mp_subscribeform_submit', array( $this, 'mp_subscribeform_submit' ) );
		add_action( 'admin_init', array( $this, 'mp_register_settings' ) );
	}

	public function mp_register_settings() {
		$option_name = 'mp_subscribeform_options';
		$data        = get_option( $option_name );

		register_setting(
			'mp_subscribeform_fields',
			$option_name,
			'mp_subscribeform_validate_option'
		);

		add_settings_section(
			'section_1', // ID
			esc_html( __( "User Account", 'mp_subscribeform' ) ),
			'',
			'mp_subscribeform_slug'
		);

		add_settings_field(
			'section_1_field_1',
			esc_html( __( "Mobiplus User", 'mp_subscribeform' ) ),
			array( $this, 'mp_subscribeform_section_1_field_1' ),
			'mp_subscribeform_slug',
			'section_1',
			array(
				'name'        => 'mp_user',
				'value'       => esc_attr( $data['mp_user'] ),
				'option_name' => $option_name,
				'placeholder' => esc_html( __( "Mobiplus User", 'mp_subscribeform' ) )
			)
		);

		add_settings_field(
			'section_1_field_2',
			esc_html( __( "Mobiplus API Key", 'mp_subscribeform' ) ),
			array( $this, 'mp_subscribeform_section_1_field_1' ),
			'mp_subscribeform_slug',
			'section_1',
			array(
				'name'        => 'mp_api_key',
				'value'       => esc_attr( $data['mp_api_key'] ),
				'option_name' => $option_name,
				'placeholder' => esc_html( __( "Mobiplus API Key", 'mp_subscribeform' ) )
			)
		);

		add_settings_section(
			'section_2',
			esc_html( __( "SMS Info", 'mp_subscribeform' ) ),
			'',
			'mp_subscribeform_slug'
		);

		add_settings_field(
			'section_1_field_3',
			esc_html( __( "SMS List ID", 'mp_subscribeform' ) ),
			array( $this, 'mp_subscribeform_section_1_field_1' ),
			'mp_subscribeform_slug',
			'section_2',
			array(
				'name'        => 'mp_sms_id',
				'value'       => esc_attr( $data['mp_sms_id'] ),
				'option_name' => $option_name,
				'placeholder' => esc_html( __( "SMS List ID", 'mp_subscribeform' ) )
			)
		);

		add_settings_field(
			'section_1_field_4',
			esc_html( __( "SMS Placeholder", 'mp_subscribeform' ) ),
			array( $this, 'mp_subscribeform_section_1_field_1' ),
			'mp_subscribeform_slug',
			'section_2',
			array(
				'name'        => 'mp_sms_pholder',
				'value'       => esc_attr( $data['mp_sms_pholder'] ),
				'option_name' => $option_name,
				'placeholder' => esc_html( __( "SMS Placeholder", 'mp_subscribeform' ) )
			)
		);

		add_settings_section(
			'section_3',
			esc_html( __( "Email Info", 'mp_subscribeform' ) ),
			'',
			'mp_subscribeform_slug'
		);

		add_settings_field(
			'section_1_field_5',
			esc_html( __( "Email List ID", 'mp_subscribeform' ) ),
			array( $this, 'mp_subscribeform_section_1_field_1' ),
			'mp_subscribeform_slug',
			'section_3',
			array(
				'name'        => 'mp_email_id',
				'value'       => esc_attr( $data['mp_email_id'] ),
				'option_name' => $option_name,
				'placeholder' => esc_html( __( "Email List ID", 'mp_subscribeform' ) )
			)
		);

		add_settings_field(
			'section_1_field_6',
			esc_html( __( "Email Placeholder", 'mp_subscribeform' ) ),
			array( $this, 'mp_subscribeform_section_1_field_1' ),
			'mp_subscribeform_slug',
			'section_3',
			array(
				'name'        => 'mp_email_pholder',
				'value'       => esc_attr( $data['mp_email_pholder'] ),
				'option_name' => $option_name,
				'placeholder' => esc_html( __( "Email Placeholder", 'mp_subscribeform' ) )
			)
		);

		add_settings_section(
			'section_4',
			esc_html( __( "Text Info", 'mp_subscribeform' ) ),
			'',
			'mp_subscribeform_slug'
		);
		
		add_settings_field(
			'section_1_field_12',
			esc_html( __( "Label Text", 'mp_subscribeform' ) ),
			array( $this, 'mp_subscribeform_section_1_field_1' ),
			'mp_subscribeform_slug',
			'section_4',
			array(
				'name'        => 'mp_subscribe_label_text',
				'value'       => esc_attr( $data['mp_subscribe_label_text'] ),
				'option_name' => $option_name,
				'placeholder' => esc_html( __( "ex. Email or Sms", 'mp_subscribeform' ) )
			)
		);

		add_settings_field(
			'section_1_field_7',
			esc_html( __( "Subcribe button text", 'mp_subscribeform' ) ),
			array( $this, 'mp_subscribeform_section_1_field_1' ),
			'mp_subscribeform_slug',
			'section_4',
			array(
				'name'        => 'mp_subscribe_button_text',
				'value'       => esc_attr( $data['mp_subscribe_button_text'] ),
				'option_name' => $option_name,
				'placeholder' => esc_html( __( "Subcribe button text", 'mp_subscribeform' ) )
			)
		);

		add_settings_field(
			'section_1_field_8',
			esc_html( __( "Success response text", 'mp_subscribeform' ) ),
			array( $this, 'mp_subscribeform_section_1_field_1' ),
			'mp_subscribeform_slug',
			'section_4',
			array(
				'name'        => 'mp_subscribe_response_text',
				'value'       => esc_attr( $data['mp_subscribe_response_text'] ),
				'option_name' => $option_name,
				'placeholder' => esc_html( __( "Success response text", 'mp_subscribeform' ) )
			)
		);

		add_settings_field(
			'section_1_field_9',
			esc_html( __( "Failed response text", 'mp_subscribeform' ) ),
			array( $this, 'mp_subscribeform_section_1_field_1' ),
			'mp_subscribeform_slug',
			'section_4',
			array(
				'name'        => 'mp_subscribe_failed_text',
				'value'       => esc_attr( $data['mp_subscribe_failed_text'] ),
				'option_name' => $option_name,
				'placeholder' => esc_html( __( "Failed response text", 'mp_subscribeform' ) )
			)
		);
		
		add_settings_field(
			'section_1_field_13',
			esc_html( __( "Already Subcriber", 'mp_subscribeform' ) ),
			array( $this, 'mp_subscribeform_section_1_field_1' ),
			'mp_subscribeform_slug',
			'section_4',
			array(
				'name'        => 'mp_subscribe_already_subcriber',
				'value'       => esc_attr( $data['mp_subscribe_already_subcriber'] ),
				'option_name' => $option_name,
				'placeholder' => esc_html( __( "You are already a subcriber" ) )
			)
		);
		
		add_settings_field(
			'section_1_field_10',
			esc_html( __( "Additional Classes", 'mp_subscribeform' ) ),
			array( $this, 'mp_subscribeform_section_1_field_1' ),
			'mp_subscribeform_slug',
			'section_4',
			array(
				'name'        => 'mp_subscribe_additonal_class',
				'value'       => esc_attr( $data['mp_subscribe_additonal_class'] ),
				'option_name' => $option_name,
				'placeholder' => esc_html( __( "ex. is-outline secondary", 'mp_subscribeform' ) )
			)
		);

		add_settings_section(
			'section_5',
			esc_html( __( "Color Settings", 'mp_subscribeform' ) ),
			'',
			'mp_subscribeform_slug'
		);

		add_settings_field(
			'section_1_field_10',
			esc_html( __( "Background Color", 'mp_subscribeform' ) ),
			array( $this, 'mp_subscribeform_field_colorpicker' ),
			'mp_subscribeform_slug',
			'section_5',
			array(
				'name'        => 'mp_background_color',
				'value'       => esc_attr( $data['mp_background_color'] ),
				'option_name' => $option_name
			)
		);
	}

	public function mp_subscribeform_field_colorpicker( $args ) {
		printf(
			'<input name="%1$s[%2$s]"  value="%3$s" size="6" type="text" class="d2l-cp-field" >',
			$args['option_name'],
			$args['name'],
			$args['value']
		);
	}

	function mp_subscribeform_section_1_field_1( $args ) {
		printf(
			'<input name="%1$s[%2$s]"  value="%3$s" placeholder="%4$s" size="50" type="text">',
			$args['option_name'],
			$args['name'],
			$args['value'],
			$args['placeholder']
		);
	}

	public function wp_mv_subscribeform_admin_menu() {
		add_options_page( 'Mobiplus Subscription', ' Mobiplus Subscription', 'manage_options', 'mp_subscribeform',
			array( &$this, 'mp_subscribeform_menu_render' ) );
	}

	public function mp_subscribeform_menu_render() {
		?>
        <div class="wrap mp_subscribeform">
            <h1><?php esc_html_e( "Mobiplus Subscriber Form", 'mp_subscribeform' ); ?></h1>
            <h3><?php esc_html_e( "Plugin info", 'mp_subscribeform' ); ?></h3>
            <p class="mp_info"><?php esc_html_e( "Instructions how to use shortcode.", 'mp_subscribeform' ); ?></p>
            <h4><?php esc_html_e( "[MPSUBSCRIBEFORM]", 'mp_subscribeform' ); ?></h4>
            <p class="mp_info"><?php esc_html_e( "Available parameters", 'mp_subscribeform' ); ?></p>
            <p class="mp_info"><?php esc_html_e( "printForm=true/false (default true)", 'mp_subscribeform' ); ?></p>
            <p class="mp_info"><?php esc_html_e( "showtitle=true/false (default true)", 'mp_subscribeform' ); ?></p>
            <p class="mp_info"><?php esc_html_e( "identityType=email/sms (default email)", 'mp_subscribeform' ); ?></p>
            <p class="mp_info"><?php esc_html_e( "smsListId=XX (overide default in admin)", 'mp_subscribeform' ); ?></p>
            <p class="mp_info"><?php esc_html_e( "emailListId=XX (overide default in admin)",
					'mp_subscribeform' ); ?></p>
            <h4><?php esc_html_e( 'Example: [MPSUBSCRIBEFORM printForm=false showtitle=true identityType="sms" smsListId=10]',
					'mp_subscribeform' ); ?></h4>
            <hr>
            <form action="options.php" method="POST">
				<?php
				settings_fields( 'mp_subscribeform_fields' );
				do_settings_sections( 'mp_subscribeform_slug' );
				submit_button();
				?>
            </form>
        </div>
		<?php

		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_style( 'wp-colorpicker', plugins_url( 'assets/css/wp-color-picker-style.css', __FILE__ ),
			array( 'wp-color-picker' ), '1.0.0', 'all' );

		wp_enqueue_script( 'wp-color-picker' );
		wp_enqueue_script( 'wp-colorpicker', plugins_url( 'assets/js/wp-color-picker-script.js', __FILE__ ),
			array( 'wp-color-picker' ), false, true );
	}

	public function wp_mv_subscribeform_display_form( $atts ) {
		$mp_subscribeform_options = get_option( 'mp_subscribeform_options' );

		$mp_subscribe_button_text	= ! empty( $mp_subscribeform_options["mp_subscribe_button_text"] ) ? $mp_subscribeform_options["mp_subscribe_button_text"] : "Prenumerera";
		$mp_identity_type         	= ! empty( $atts["identitytype"] ) ? $atts["identitytype"] : "EMAIL";
		$mp_identity_type         	= strtoupper( $mp_identity_type );
		$mp_button_class		  	= "mp_sendButton". (! empty( $mp_subscribeform_options["mp_subscribe_additonal_class"] ) ? " ". $mp_subscribeform_options["mp_subscribe_additonal_class"] : "");

		$mp_show_title = ! empty( $atts["showtitle"] ) ? false : true;

		if ( $mp_identity_type == "EMAIL" ) {
			$list_id      = ! empty( $atts["emaillistid"] ) ? $atts["emaillistid"] : $mp_subscribeform_options["mp_email_id"];
			$place_holder = ! empty( $mp_subscribeform_options["mp_email_pholder"] ) ? $mp_subscribeform_options["mp_email_pholder"] : "Your email address";
		} else {
			$list_id      = ! empty( $atts["smslistid"] ) ? $atts["smslistid"] : $mp_subscribeform_options["mp_sms_id"];
			$place_holder = ! empty( $mp_subscribeform_options["mp_sms_pholder"] ) ? $mp_subscribeform_options["mp_sms_pholder"] : "Your phone number (46XXXXXXXX)";
		}

		$content = '<form id="mp_subscribeform_settings_form" class="mp_subscribeform" method="post" action="'.$_SERVER['PHP_SELF'].'">
						<div class="mp_ajaxload"></div>
						<div class="mp_subscribe">';
							if ( $mp_show_title && !empty($mp_subscribeform_options["mp_subscribe_label_text"])) {
								$content .= "<label>".esc_html( $mp_subscribeform_options["mp_subscribe_label_text"] )."</label>";
								$extra_class	= " mp_Identity_width";
							}
		$content .= "<input type='text' class='mp_Identity".$extra_class."' name='mp_Identity' id='mp_Identity' value='' placeholder='" . $place_holder . "' />";
		$content .= '<div class="mp_msg"></div>';
		$content .= '<div class="mp_submitButton">
						<input type="button" class="'.$mp_button_class.'" id="sendbutton" name="sendbutton" value="' . $mp_subscribe_button_text . '">
					</div></div>
					<input type="hidden" name="action" value="mp_subscribeform_submit" />
					<input type="hidden" name="mp_identityType" id="mp_identityType" value="' . $mp_identity_type . '" />
					<input type="hidden" name="mp_listID" value="' . $list_id . '" />'.
					wp_nonce_field( 'mp_subscribeform_nonce_name', "mp_subscribeform_nonce_name" ).'
        </form>';

		$content .= '<script>var ajaxurl 	= "' . admin_url( 'admin-ajax.php' ) . '";var ajaximage	= "' . plugin_dir_url( __FILE__ ) . 'assets/images/ajax.gif";</script>';
		
		$content .= '<style>';
		if ( ! empty( $mp_subscribeform_options["mp_background_color"] ) ) {
			$content .= '.mp_subscribe {background-color:' . $mp_subscribeform_options["mp_background_color"] . ';padding:10px;}';
		}
		$content .= '</style>';

		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'mb-subscriber-form-js', plugins_url( 'assets/js/custom-script.js', __FILE__ ) );
		
		wp_enqueue_style( 'mb-subscriber-form-css', plugins_url( 'assets/css/custom-style.css', __FILE__ ));

		if ( !empty($atts['printform']) && $atts['printform'] == "true" ) {
			echo $content;
		} else {
			return $content;
		}
	}

	private function HashXML( $XML, $hashedPassword ) {
		$match = preg_match( "/<HASH TYPE=\"(.+)\">.*<\/HASH>/", $XML, $hashType );

		if ( ! $match ) {
			throw new Exception( "No Hash Provided" );
		}

		$hashType  = $hashType[1];
		$xmlToHash = preg_replace( "/<HASH TYPE=\".+\">.*<\/HASH>/", "", $XML );

		if ( $hashType == "md5" || $hashType == "sha1" ) {
			return preg_replace( "/<HASH TYPE=\"(.+)\">.*<\/HASH>/",
				"<HASH TYPE=\"$1\">" . $hashType( $xmlToHash . $hashedPassword ) . "</HASH>", $XML );
		} else {
			throw new Exception( "Invalid Hash Type - $hashType" );
		}
	}

	public function mp_subscribeform_plugins_loaded() {
		load_plugin_textdomain( 'mp_subscribeform', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	public function mp_subscribeform_submit() {
		$nonce = $_POST['mp_subscribeform_nonce_name'];
		if ( ! wp_verify_nonce( $nonce, 'mp_subscribeform_nonce_name' ) ) {
			die( 'Invalid Request' );
		} else {
			$mp_subscribeform_options = get_option( 'mp_subscribeform_options' );
			$hashedPassword           = $mp_subscribeform_options["mp_api_key"];
			$MobiPlusUser             = $mp_subscribeform_options["mp_user"];
			$mp_Identity              = sanitize_text_field( $_POST["mp_Identity"] );
			$mp_identityType          = sanitize_text_field( $_POST["mp_identityType"] );
			$mp_listID                = sanitize_text_field( $_POST["mp_listID"] );

			if ( $mp_identityType == "EMAIL" ) {
				$mp_Identity = sanitize_email( $mp_Identity );
			}

			if ( empty( $mp_Identity ) ) {
				die( 'Invalid ' . ucfirst( strtolower( $mp_identityType ) ) . " " . $mp_subscribeform_options["mp_subscribe_failed_text"] );
			}

			$XML = '<MOBIPLUS VERSION="3.0" METHOD="SUBSCRIBE">
						<SECURITY>
							<HASH TYPE="md5">' . $hashedPassword . '</HASH>
							<USERNAME>' . $MobiPlusUser . '</USERNAME>
						</SECURITY>
						<REQUEST>
							<IDENTITY TYPE="' . $mp_identityType . '">' . $mp_Identity . '</IDENTITY>
							<PROPERTIES></PROPERTIES>';

			if ( ! empty( $mp_listID ) ) {
				$XML .= '<LISTS>
								<LIST TYPE="' . $mp_identityType . '">' . $mp_listID . '</LIST>
							</LISTS>';
			}
			$XML .= '</REQUEST>
					</MOBIPLUS>';

			$XML2     = self::HashXML( $XML, $hashedPassword );
			$response = wp_remote_get( "https://api.mobiplus.se/handler.php?XML=" . urlencode( $XML2 ) );

			$response_Data = simplexml_load_string( $response["body"] );

			$message = ! empty( $mp_subscribeform_options["mp_subscribe_response_text"] ) ? $mp_subscribeform_options["mp_subscribe_response_text"] : "User Subscribed Succesfully";
			if ( isset ( $response_Data->RESPONSE->ERROR ) ) {
				$message = ! empty( $mp_subscribeform_options["mp_subscribe_failed_text"] ) ? $mp_subscribeform_options["mp_subscribe_failed_text"] : "";
				$message .= $response_Data->RESPONSE->ERROR[0];
				
				if (!empty($mp_subscribeform_options["mp_subscribe_already_subcriber"]) && strpos($message, 'Subscriber already exists') !== false) {
					$message	= $mp_subscribeform_options["mp_subscribe_already_subcriber"];
				}

			}
			die( $message );
		}
	}
}

new mobiplus_subscribeform();