<?php
/**
 * WP Brand Identity Lite.
 *
 * @package   WP_Brand_Identity_Lite_Admin
 * @author    Circlewaves Team <support@circlewaves.com>
 * @license   GPL-2.0+
 * @link      http://circlewaves.com
 * @copyright 2014 Circlewaves Team <support@circlewaves.com>
 */

/**
 * Plugin class. This class should ideally be used to work with the
 * administrative side of the WordPress site.
 *
 * If you're interested in introducing public-facing
 * functionality, then refer to `class-plugin-name.php`
 */
class WP_Brand_Identity_Lite_Admin {

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Slug of the plugin screen.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_screen_hook_suffix = null;

	/**
	 * Initialize the plugin by loading admin scripts & styles and adding a
	 * settings page and menu.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {

		/*
		 * @TODO :
		 *
		 * - Uncomment following lines if the admin class should only be available for super admins
		 */
		/* if( ! is_super_admin() ) {
			return;
		} */

		/*
		 * Call $plugin_slug from public plugin class.
		 */
		$plugin = WP_Brand_Identity_Lite::get_instance();
		$this->plugin_slug = $plugin->get_plugin_slug();

		// Load admin style sheet and JavaScript.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

		// Add the options page and menu item.
		add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );
		add_action( 'admin_init', array( $this, 'admin_options_init' ) );	

		// Add an action link pointing to the options page.
		$plugin_basename = plugin_basename( plugin_dir_path( realpath( dirname( __FILE__ ) ) ) . $this->plugin_slug . '.php' );
		add_filter( 'plugin_action_links_' . $plugin_basename, array( $this, 'add_action_links' ) );

	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		/*
		 * @TODO :
		 *
		 * - Uncomment following lines if the admin class should only be available for super admins
		 */
		/* if( ! is_super_admin() ) {
			return;
		} */

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Register and enqueue admin-specific style sheet.
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_styles() {

		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		$screen = get_current_screen();
		if ( $this->plugin_screen_hook_suffix == $screen->id ) {
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_style('thickbox');			
			wp_enqueue_style( $this->plugin_slug .'-admin-styles', plugins_url( 'assets/css/admin.css', __FILE__ ), array(), WP_Brand_Identity_Lite::VERSION );
		}


	}

	/**
	 * Register and enqueue admin-specific JavaScript.
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_scripts() {

		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		$screen = get_current_screen();
		if ( $this->plugin_screen_hook_suffix == $screen->id ) {
			wp_enqueue_script('jquery');
			wp_enqueue_script('thickbox');
			wp_enqueue_script( 'wp-color-picker' );		
			
			wp_enqueue_script( $this->plugin_slug . '-admin-script', plugins_url( 'assets/js/admin.js', __FILE__ ), array( 'jquery','media-upload','thickbox' ), WP_Brand_Identity_Lite::VERSION );
		
		//add custom variables to admin.js, use wpbi_plugin_vars.var_name 
			$plugin_script_vars = array(
				'plugin_slug'=>$this->plugin_slug
			);
			wp_localize_script( $this->plugin_slug . '-admin-script', 'wpbi_plugin_vars', $plugin_script_vars );				
		}

	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_admin_menu() {

		/*
		 * Add a settings page for this plugin to the Settings menu.
		 */
		$this->plugin_screen_hook_suffix = add_menu_page(
			__( 'Brand Identity Settings', $this->plugin_slug ),
			__( 'Brand Identity', $this->plugin_slug ),
			'manage_options',
			$this->plugin_slug,
			array( $this, 'display_plugin_admin_page' ),
			'dashicons-welcome-view-site'
		);

	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function display_plugin_admin_page() {
		include_once( 'views/admin.php' );
	}

	/**
	 * Add settings action link to the plugins page.
	 *
	 * @since    1.0.0
	 */
	public function add_action_links( $links ) {

		return array_merge(
			array(
				'settings' => '<a href="' . admin_url( 'admin.php?page=' . $this->plugin_slug ) . '">' . __( 'Settings', $this->plugin_slug ) . '</a>'
			),
			$links
		);

	}
	
	/**
	 * Init plugin options
	 *
	 * @since    1.0.0
	 */	
	public function admin_options_init() {
	

		// Sections
		add_settings_section( 'form-section', 'Form Options', array( $this, 'plugin_options_section_callback' ), 'wpbi_settings_tab_1' );
		add_settings_section( 'form-customization-section', 'Form Customization', array( $this, 'plugin_options_section_callback' ), 'wpbi_settings_tab_1' );
		add_settings_section( 'background-section', 'Background Options', array( $this, 'plugin_options_section_callback' ), 'wpbi_settings_tab_2' );
		add_settings_section( 'logo-section', 'Logo Options', array( $this, 'plugin_options_section_callback' ), 'wpbi_settings_tab_3' );
		add_settings_section( 'custom-css-section', 'Custom CSS', array( $this, 'plugin_options_section_callback' ), 'wpbi_settings_tab_6' );


		// Handle plugin options
		foreach(WP_Brand_Identity_Lite::$pluginSettings as $setting){
			if((isset($setting['hidden']))&&($setting['hidden']==1)){
				// Hidden option
			}else{
				// Register Settings
				register_setting( $setting['tab'], $setting['name'] );
				// Fields
				add_settings_field( $setting['name'], $setting['title'], array( $this, 'setting_field_callback' ), $setting['tab'], $setting['section'], array('name'=>$setting['name'],'field'=>$setting['field']) );
			}
		}

	 
	}	

	/**
	 * Main section callback
	 *
	 * @since    1.0.0
	 */	
	public function plugin_options_section_callback($args) {
		$section_id=$args['id'];
		switch($section_id){
			case 'form-section':?>
				<p>Choose form position and style</p>
			<?php
			break;
			case 'form-customization-section':?>
				<p>Customize form colors</p>
			<?php
			break;				
			case 'background-section':?>
				<p>Customize background color and add background images</p>
			<?php
			break;			
			case 'logo-section':?>
				<p>Add your own logo</p>
			<?php
			break;					
			case 'custom-css-section':?>
				<p>Add custom css rules</p>
			<?php
			break;				
		}
	}
	

 	/**
	 * Generate setting field
	 *
	 * @since    1.0.0
	 */	
	public function setting_field_callback($args) {

		$setting_value =  isset($args['value'])?$args['value']:get_option( $args['name'] ) ;
		$field=$args['field'];

		$field_class=isset($field['class'])?$field['class']:'';
		$field_descr=isset($field['description'])?('<span class="description">'.$field['description'].'</span>'):'';		
		$field_subtitle=isset($args['subtitle'])?('<label class="field-subtitle">'.$args['subtitle'].'</label>'):'';
	
		switch($field['type']){
			case 'checkbox':
			?>
				<?php echo $field_subtitle;?>
				<input class="<?php echo $field_class;?>" type="checkbox" name="<?php echo $args['name'];?>" value="1" <?php checked( $setting_value, '1', true);?> />
				<?php echo $field_descr;?>
			<?php
			break;
			case 'radio':
				if(is_array($field['options'])){
				?>
				<?php echo $field_subtitle;?>
				<?php echo $field_descr;?>
				<?php
					foreach($field['options'] as $k=>$v){
					?>
						<label><input class="<?php echo $field_class;?>" type="radio" name="<?php echo $args['name'];?>" value="<?php echo $k;?>" <?php checked( $setting_value, $k, true);?> /> <span><?php echo $v;?></span></label><br />
					<?php
					}
				}
			break;			
			case 'radio-image':
				if(is_array($field['options'])){
				?>
					<?php echo $field_subtitle;?>
					<?php echo $field_descr;?>
					<div class="radio-image-wrapper">
				<?php
					foreach($field['options'] as $k=>$v){
					?>
						<div class="radio-image-item">
							<label><input class="<?php echo $field_class;?>" type="radio" name="<?php echo $args['name'];?>" value="<?php echo $k;?>" <?php checked( $setting_value, $k, true);?> /><span><?php echo $v[0];?><br /><img src="<?php echo plugins_url( 'assets/img/'.$v[1], __FILE__ );?>" /></span><label>
						</div>
					<?php
					}
				?>
					</div>
				<?php
				}
			break;			
			case 'dropdown':
				if(is_array($field['options'])){
				?>
					<?php echo $field_subtitle;?>
					<select class="<?php echo $field_class;?>" name="<?php echo $args['name'];?>" id="<?php echo $args['name'];?>">
				<?php
					foreach($field['options'] as $k=>$v){
					?>
						<option value="<?php echo $k;?>" <?php selected( $setting_value, $k, true);?>><?php echo $v;?></option>
					<?php
					}				
				?>
					</select>
					<?php echo $field_descr;?>
				<?php
				}
			break;
			case 'text':
			?>
				<?php echo $field_subtitle;?>
				<input class="<?php echo $field_class;?>" type="text" name="<?php echo $args['name'];?>" id="<?php echo $args['name'];?>" value="<?php echo esc_attr($setting_value);?>" />
				<?php echo $field_descr;?>
			<?php	
			break;
			case 'textarea':
			?>
				<?php echo $field_subtitle;?>
				<textarea class="<?php echo $field_class;?>" name="<?php echo $args['name'];?>" id="<?php echo $args['name'];?>"><?php echo esc_attr($setting_value);?></textarea>
				<?php echo $field_descr;?>
			<?php	
			break;			
			case 'colorpicker':
				$default_color=$field['default-color']?('data-default-color="'.$field['default-color'].'"'):"";
			?>
				<?php echo $field_subtitle;?>
				<input class="field-colorpicker <?php echo $field_class;?>" type="text" name="<?php echo $args['name'];?>" value="<?php echo $setting_value;?>" <?php echo $default_color;?> />
				<?php echo $field_descr;?>
			<?php
			break;			
			case 'colorpicker-double':
				$default_color[0]=$field['default-color']?('data-default-color="'.$field['default-color'][0].'"'):"";
				$default_color[1]=$field['default-color']?('data-default-color="'.$field['default-color'][1].'"'):"";
			?>
				<?php echo $field_subtitle;?>
				<input class="field-colorpicker <?php echo $field_class;?>" type="text" name="<?php echo $args['name'];?>[0]" value="<?php echo isset($setting_value[0])?$setting_value[0]:'';?>" <?php echo $default_color[0];?> />
				<input class="field-colorpicker <?php echo $field_class;?>" type="text" name="<?php echo $args['name'];?>[1]" value="<?php echo isset($setting_value[1])?$setting_value[1]:'';?>" <?php echo $default_color[1];?> />
				<?php echo $field_descr;?>
			<?php
			break;					
			case 'text-upload-image':
			?>
				<div class="field-upload-image-wrapper">
					<?php echo $field_subtitle;?>
					<input class="field-upload-image <?php echo $field_class;?>" type="text" name="<?php echo $args['name'];?>" id="<?php echo $args['name'];?>" value="<?php echo $setting_value;?>" />
					<input type="button" class="button upload-image-button" value="Upload" />
					<?php echo $field_descr;?>
					<div class="field-upload-image-preview" style="min-height: 10px;">
						<img style="max-height:120px" src="<?php echo esc_url( $setting_value ); ?>" />
					</div>
				</div>
			<?php
			break;	;			
			default:			
			?>
				<?php echo $field_subtitle;?>
				<input class="regular-text" type="text" name="<?php echo $args['name'];?>" id="<?php echo $args['name'];?>" value="<?php echo esc_attr($setting_value);?>" />
				<?php echo $field_descr;?>
			<?php
			break;
		}
	}	
	

}
