<?php
/**
 * Plugin Name: WP Developers Facebook Comments
 * Plugin URI: http://wpdevelopers.com
 * Description: Facebook comments by WP Developers.
 * Version: 1.5.1
 * Author: Tyler Johnson
 * Author URI: http://tylerjohnsondesign.com
 * License: GPL2
 */

/**
Check for Plugin Updates on GitHub
**/
// Require Plugin Update Files
require 'plugin-update-checker-3.0/plugin-update-checker.php';
$wpdevClassName = PucFactory::getLatestClassVersion('PucGitHubChecker');
$wpdevUpdateChecker = new $wpdevClassName(
    'https://github.com/LibertyAllianceGit/wpdev-fb-comments',
    __FILE__,
    'master'
);

/**
Enqueue Plugin Files
**/
function wpdev_facebook_comments_admin_cssjs() {
  wp_enqueue_style('wpdev-fb-admin-css', plugin_dir_url(__FILE__) . 'inc/wpdev-fb-comments-admin-css.css');
  wp_enqueue_script('wpdev-fb-admin-js', plugin_dir_url(__FILE__) . 'inc/wpdev-fb-comments-admin-js.js', array('jquery'), '1.0');
}
add_action('admin_enqueue_scripts', 'wpdev_facebook_comments_admin_cssjs');

/**
Plugin Options
**/
class WPDevelopersFacebookComments {
	private $wpdevelopers_facebook_comments_options;

    // Create Menu Page
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'wpdevelopers_facebook_comments_add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'wpdevelopers_facebook_comments_page_init' ) );
	}

    // Page Options
	public function wpdevelopers_facebook_comments_add_plugin_page() {
		add_options_page(
			'WPDevelopers Facebook Comments', // page_title
			'WPDevelopers Facebook Comments', // menu_title
			'manage_options', // capability
			'wpdevelopers-facebook-comments', // menu_slug
			array( $this, 'wpdevelopers_facebook_comments_create_admin_page' ) // function
		);
	}

    // Page Template
	public function wpdevelopers_facebook_comments_create_admin_page() {
		$this->wpdevelopers_facebook_comments_options = get_option( 'wpdevelopers_facebook_comments_option_name' ); ?>

		<div class="wrap wpdev-fb-wrap">
			<h2 class="wpdev-fb-logo-head"><img src="<?php echo plugin_dir_url(__FILE__) . 'inc/wpdev-fb-comments-logo.png'; ?>" alt="WPDevelopers Facebook Comments"/></h2>
			<p>To display the comments, please use either <code>echo do_shortcode('[wpdevfb]');</code> or <code>do_action('wpdevfb');</code>.</p>
			<?php settings_errors(); ?>
			<form method="post" action="options.php">
				<?php
					settings_fields( 'wpdevelopers_facebook_comments_option_group' );
					do_settings_sections( 'wpdevelopers-facebook-comments-admin' );
					submit_button(); ?>
			</form>
		</div>
	<?php }

    // Setup Settings
	public function wpdevelopers_facebook_comments_page_init() {
		register_setting(
			'wpdevelopers_facebook_comments_option_group', // option_group
			'wpdevelopers_facebook_comments_option_name', // option_name
			array( $this, 'wpdevelopers_facebook_comments_sanitize' ) // sanitize_callback
		);
		add_settings_section(
			'wpdevelopers_facebook_comments_setting_section', // id
			'Comment Settings', // title
			array( $this, 'wpdevelopers_facebook_comments_section_info' ), // callback
			'wpdevelopers-facebook-comments-admin' // page
		);
		add_settings_field(
			'enable_comments_0', // id
			'Enable Comments<span class="wpdev-fb-label">Enable/Disable Facebook comments across the side.</span>', // title
			array( $this, 'enable_comments_0_callback' ), // callback
			'wpdevelopers-facebook-comments-admin', // page
			'wpdevelopers_facebook_comments_setting_section' // section
		);
		add_settings_field(
			'add_facebook_sdk_to_head_1', // id
			'Add Facebook SDK JS<span class="wpdev-fb-label">Optionally add the Facebook SDK.js file, for loading the comments box. Only needed if you aren\'t loading the SDK.js already.</span>', // title
			array( $this, 'add_facebook_sdk_to_head_1_callback' ), // callback
			'wpdevelopers-facebook-comments-admin', // page
			'wpdevelopers_facebook_comments_setting_section' // section
		);
		add_settings_field(
			'facebook_app_id_2', // id
			'Facebook APP ID<span class="wpdev-fb-label">Get your Facebook APP ID <a href="//developers.facebook.com" target="_blank">here</a>.</span>', // title
			array( $this, 'facebook_app_id_2_callback' ), // callback
			'wpdevelopers-facebook-comments-admin', // page
			'wpdevelopers_facebook_comments_setting_section' // section
		);
		add_settings_field(
			'color_scheme_3', // id
			'Color Scheme<span class="wpdev-fb-label">Select a color scheme. Light is for comments being displayed on a lighter background, and dark is vice versa.</span>', // title
			array( $this, 'color_scheme_3_callback' ), // callback
			'wpdevelopers-facebook-comments-admin', // page
			'wpdevelopers_facebook_comments_setting_section' // section
		);
		add_settings_field(
			'width_default_100_4', // id
			'Width<span class="wpdev-fb-label">Output width of the comment box. Default is 100%.</span>', // title
			array( $this, 'width_default_100_4_callback' ), // callback
			'wpdevelopers-facebook-comments-admin', // page
			'wpdevelopers_facebook_comments_setting_section' // section
		);
		add_settings_field(
			'number_of_comments_5', // id
			'Number of Comments<span class="wpdev-fb-label">Amount of displayed comments before "Load more comments" button.</span>', // title
			array( $this, 'number_of_comments_5_callback' ), // callback
			'wpdevelopers-facebook-comments-admin', // page
			'wpdevelopers_facebook_comments_setting_section' // section
		);
	}

    // Sanitize & Clean Options
	public function wpdevelopers_facebook_comments_sanitize($input) {
		$sanitary_values = array();
		if ( isset( $input['enable_comments_0'] ) ) {
			$sanitary_values['enable_comments_0'] = $input['enable_comments_0'];
		}
		if ( isset( $input['add_facebook_sdk_to_head_1'] ) ) {
			$sanitary_values['add_facebook_sdk_to_head_1'] = $input['add_facebook_sdk_to_head_1'];
		}
		if ( isset( $input['facebook_app_id_2'] ) ) {
			$sanitary_values['facebook_app_id_2'] = sanitize_text_field( $input['facebook_app_id_2'] );
		}
		if ( isset( $input['color_scheme_3'] ) ) {
			$sanitary_values['color_scheme_3'] = $input['color_scheme_3'];
		}
		if ( isset( $input['width_default_100_4'] ) ) {
			$sanitary_values['width_default_100_4'] = sanitize_text_field( $input['width_default_100_4'] );
		}
		if ( isset( $input['number_of_comments_5'] ) ) {
			$sanitary_values['number_of_comments_5'] = sanitize_text_field( $input['number_of_comments_5'] );
		}
		return $sanitary_values;
	}

    // Callbacks
    public function wpdevelopers_facebook_comments_section_info() {
	}

	public function enable_comments_0_callback() {
		printf(
			'<input type="checkbox" name="wpdevelopers_facebook_comments_option_name[enable_comments_0]" id="enable_comments_0" value="enable_comments_0" %s>',
			( isset( $this->wpdevelopers_facebook_comments_options['enable_comments_0'] ) && $this->wpdevelopers_facebook_comments_options['enable_comments_0'] === 'enable_comments_0' ) ? 'checked' : ''
		);
	}
	public function add_facebook_sdk_to_head_1_callback() {
		printf(
			'<input type="checkbox" name="wpdevelopers_facebook_comments_option_name[add_facebook_sdk_to_head_1]" id="add_facebook_sdk_to_head_1" value="add_facebook_sdk_to_head_1" %s>',
			( isset( $this->wpdevelopers_facebook_comments_options['add_facebook_sdk_to_head_1'] ) && $this->wpdevelopers_facebook_comments_options['add_facebook_sdk_to_head_1'] === 'add_facebook_sdk_to_head_1' ) ? 'checked' : ''
		);
	}
	public function facebook_app_id_2_callback() {
		printf(
			'<input class="regular-text" type="text" name="wpdevelopers_facebook_comments_option_name[facebook_app_id_2]" id="facebook_app_id_2" value="%s">',
			isset( $this->wpdevelopers_facebook_comments_options['facebook_app_id_2'] ) ? esc_attr( $this->wpdevelopers_facebook_comments_options['facebook_app_id_2']) : ''
		);
	}
	public function color_scheme_3_callback() {
		?> <select name="wpdevelopers_facebook_comments_option_name[color_scheme_3]" id="color_scheme_3">
			<?php $selected = (isset( $this->wpdevelopers_facebook_comments_options['color_scheme_3'] ) && $this->wpdevelopers_facebook_comments_options['color_scheme_3'] === 'light') ? 'selected' : '' ; ?>
			<option value="light" <?php echo $selected; ?>>Light</option>
			<?php $selected = (isset( $this->wpdevelopers_facebook_comments_options['color_scheme_3'] ) && $this->wpdevelopers_facebook_comments_options['color_scheme_3'] === 'dark') ? 'selected' : '' ; ?>
			<option value="dark" <?php echo $selected; ?>>Dark</option>
		</select> <?php
	}
	public function width_default_100_4_callback() {
		printf(
			'<input class="regular-text" type="text" name="wpdevelopers_facebook_comments_option_name[width_default_100_4]" id="width_default_100_4" value="%s">',
			isset( $this->wpdevelopers_facebook_comments_options['width_default_100_4'] ) ? esc_attr( $this->wpdevelopers_facebook_comments_options['width_default_100_4']) : ''
		);
	}
	public function number_of_comments_5_callback() {
		printf(
			'<input class="regular-text" type="text" name="wpdevelopers_facebook_comments_option_name[number_of_comments_5]" id="number_of_comments_5" value="%s">',
			isset( $this->wpdevelopers_facebook_comments_options['number_of_comments_5'] ) ? esc_attr( $this->wpdevelopers_facebook_comments_options['number_of_comments_5']) : ''
		);
	}
}

// Enable Comment Options
if ( is_admin() )
	$wpdevelopers_facebook_comments = new WPDevelopersFacebookComments();


/**
Setup Comment Option Variables
**/
// Get Options
$wpdevfb = get_option('wpdevelopers_facebook_comments_option_name');

// Divide Options
$wpdevfbenable = $wpdevfb['enable_comments_0'];
$wpdevfbsdk = $wpdevfb['add_facebook_sdk_to_head_1'];
$wpdevfbid = $wpdevfb['facebook_app_id_2'];
$wpdevfbcolor = $wpdevfb['color_scheme_3'];
$wpdevfbwidth = $wpdevfb['width_default_100_4'];
$wpdevfbnum = $wpdevfb['number_of_comments_5'];

/**
App ID Output
**/
function wpdev_fbcomments_appid() {
    global $wpdevfbid;

    echo '<meta property="fb:app_id" content="' . $wpdevfbid . '"/>';
}
add_action('wp_head', 'wpdev_fbcomments_appid', 10);

/**
SDK Output
**/
function wpdev_fbcomments_footer() {
    global $wpdevfbsdk;
    global $wpdevfbid;
    global $wpdevfbenable;

    if(!empty($wpdevfbsdk) && !empty($wpdevfbid) && !empty($wpdevfbenable)) {
        echo '
        <div id="fb-root"></div>
        <script>(function(d, s, id) {
          var js, fjs = d.getElementsByTagName(s)[0];
          if (d.getElementById(id)) return;
          js = d.createElement(s); js.id = id;
          js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.8&appId=' . $wpdevfbid . '";
          fjs.parentNode.insertBefore(js, fjs);
        }(document, \'script\', \'facebook-jssdk\'));</script>';
    } else {
        // Nothing to see here.
    }
}
add_action('wp_footer', 'wpdev_fbcomments_footer');


/**
Comments Output
**/
function wpdev_fbcomments() {
    global $wpdevfbenable;
    global $wpdevfbcolor;
    global $wpdevfbwidth;
    global $wpdevfbnum;

    if(!empty($wpdevfbenable)) {
        // Get Width
        if(!empty($wpdevwidth)) {
            $width = 'data-width="' . $wpdevwidth . '" ';
        } else {
            $width = 'data-width="auto" ';
        }
        // Get Post Count
        if(!empty($wpdevfbnum)) {
            $number = 'data-numposts="' . $wpdevfbnum . '" ';
        } else {
            $number = 'data-numposts="10" ';
        }
        // Output Comments
        $output = '<div class="fb-comments" data-href="' . get_permalink() . '" ' . $width . ' data-colorscheme="' . $wpdevfbcolor . '" ' . $number . '></div>';

        return $output;
    } else {
        // Nothing to see here.
    }
}
add_shortcode('wpdevfb', 'wpdev_fbcomments');
add_action('wpdevfb', 'wpdev_fbcomments');
