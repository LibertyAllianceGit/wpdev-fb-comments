<?php
/**
Plugin Options
**/
class WPDevelopersFacebookComments {
	private $wpdevelopers_facebook_comments_options;

    // Create Menu Page
	public function __construct() {
		add_action('admin_menu', array($this, 'wpdevelopers_facebook_comments_add_plugin_page'));
		add_action('admin_init', array($this, 'wpdevelopers_facebook_comments_page_init'));
	}

    // Page Options
	public function wpdevelopers_facebook_comments_add_plugin_page() {
		add_options_page(
			'WPDevelopers Facebook Comments', // page_title
			'WPDevelopers Facebook Comments', // menu_title
			'manage_options', // capability
			'wpdevelopers-facebook-comments', // menu_slug
			array($this, 'wpdevelopers_facebook_comments_create_admin_page') // function
		);
	}

    // Page Template
	public function wpdevelopers_facebook_comments_create_admin_page() {
		$this->wpdevelopers_facebook_comments_options = get_option('wpdevelopers_facebook_comments_option_name'); ?>

		<div class="wrap wpdev-fb-wrap">
			<h2 class="wpdev-fb-logo-head"><img src="<?php echo WPDEVFB_BASE_URI.'assets/wpdev-fb-comments-logo.png'; ?>" alt="WPDevelopers Facebook Comments"/></h2>
			<p>To display the comments, please use either <code>echo do_shortcode('[wpdevfb]');</code> or <code>do_action('wpdevfb');</code>. You can also display Facebook comment counts by using <code>echo do_shortcode('[wpdevfbcount]');</code>.</p>
			<?php settings_errors(); ?>
			<form method="post" action="options.php">
				<?php
					settings_fields('wpdevelopers_facebook_comments_option_group');
					do_settings_sections('wpdevelopers-facebook-comments-admin');
					submit_button(); ?>
			</form>
		</div>
	<?php }

    // Setup Settings
	public function wpdevelopers_facebook_comments_page_init() {
		register_setting(
			'wpdevelopers_facebook_comments_option_group', // option_group
			'wpdevelopers_facebook_comments_option_name', // option_name
			array($this, 'wpdevelopers_facebook_comments_sanitize') // sanitize_callback
		);
		add_settings_section(
			'wpdevelopers_facebook_comments_setting_section', // id
			'Comment Settings', // title
			array($this, 'wpdevelopers_facebook_comments_section_info'), // callback
			'wpdevelopers-facebook-comments-admin' // page
		);
		add_settings_field(
			'enable_comments_0', // id
			'Enable Comments<span class="wpdev-fb-label">Enable/Disable Facebook comments across the side.</span>', // title
			array($this, 'enable_comments_0_callback'), // callback
			'wpdevelopers-facebook-comments-admin', // page
			'wpdevelopers_facebook_comments_setting_section' // section
		);
		add_settings_field(
			'add_facebook_sdk_to_head_1', // id
			'Add Facebook SDK JS<span class="wpdev-fb-label">Optionally add the Facebook SDK.js file, for loading the comments box. Only needed if you aren\'t loading the SDK.js already.</span>', // title
			array($this, 'add_facebook_sdk_to_head_1_callback'), // callback
			'wpdevelopers-facebook-comments-admin', // page
			'wpdevelopers_facebook_comments_setting_section' // section
		);
		add_settings_field(
			'facebook_app_id_2', // id
			'Facebook APP ID<span class="wpdev-fb-label">Get your Facebook APP ID <a href="//developers.facebook.com" target="_blank">here</a>.</span>', // title
			array($this, 'facebook_app_id_2_callback'), // callback
			'wpdevelopers-facebook-comments-admin', // page
			'wpdevelopers_facebook_comments_setting_section' // section
		);
		add_settings_field(
			'number_of_comments_5', // id
			'Number of Comments<span class="wpdev-fb-label">Amount of displayed comments before "Load more comments" button.</span>', // title
			array($this, 'number_of_comments_5_callback'), // callback
			'wpdevelopers-facebook-comments-admin', // page
			'wpdevelopers_facebook_comments_setting_section' // section
		);
	}

    // Sanitize & Clean Options
	public function wpdevelopers_facebook_comments_sanitize($input) {
		$sanitary_values = array();
		if (isset($input['enable_comments_0'])) {
			$sanitary_values['enable_comments_0'] = $input['enable_comments_0'];
		}
		if (isset($input['add_facebook_sdk_to_head_1'])) {
			$sanitary_values['add_facebook_sdk_to_head_1'] = $input['add_facebook_sdk_to_head_1'];
		}
		if (isset($input['facebook_app_id_2'])) {
			$sanitary_values['facebook_app_id_2'] = sanitize_text_field($input['facebook_app_id_2']);
		}
		if (isset($input['number_of_comments_5'])) {
			$sanitary_values['number_of_comments_5'] = sanitize_text_field($input['number_of_comments_5']);
		}
		return $sanitary_values;
	}

    // Callbacks
    public function wpdevelopers_facebook_comments_section_info() {
	}

	public function enable_comments_0_callback() {
		printf(
			'<input type="checkbox" name="wpdevelopers_facebook_comments_option_name[enable_comments_0]" id="enable_comments_0" value="enable_comments_0" %s>',
			(isset($this->wpdevelopers_facebook_comments_options['enable_comments_0']) && $this->wpdevelopers_facebook_comments_options['enable_comments_0'] === 'enable_comments_0') ? 'checked' : ''
		);
	}
	public function add_facebook_sdk_to_head_1_callback() {
		printf(
			'<input type="checkbox" name="wpdevelopers_facebook_comments_option_name[add_facebook_sdk_to_head_1]" id="add_facebook_sdk_to_head_1" value="add_facebook_sdk_to_head_1" %s>',
			(isset($this->wpdevelopers_facebook_comments_options['add_facebook_sdk_to_head_1']) && $this->wpdevelopers_facebook_comments_options['add_facebook_sdk_to_head_1'] === 'add_facebook_sdk_to_head_1') ? 'checked' : ''
		);
	}
	public function facebook_app_id_2_callback() {
		printf(
			'<input class="regular-text" type="text" name="wpdevelopers_facebook_comments_option_name[facebook_app_id_2]" id="facebook_app_id_2" value="%s">',
			isset($this->wpdevelopers_facebook_comments_options['facebook_app_id_2']) ? esc_attr($this->wpdevelopers_facebook_comments_options['facebook_app_id_2']) : ''
		);
	}
	public function number_of_comments_5_callback() {
		printf(
			'<input class="regular-text" type="text" name="wpdevelopers_facebook_comments_option_name[number_of_comments_5]" id="number_of_comments_5" value="%s">',
			isset($this->wpdevelopers_facebook_comments_options['number_of_comments_5']) ? esc_attr($this->wpdevelopers_facebook_comments_options['number_of_comments_5']) : ''
		);
	}
}

// Enable Comment Options
if (is_admin())
	$wpdevelopers_facebook_comments = new WPDevelopersFacebookComments();
