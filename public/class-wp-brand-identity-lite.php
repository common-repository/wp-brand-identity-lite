<?php
/**
 * WP Brand Identity Lite.
 *
 * @package   WP_Brand_Identity_Lite
 * @author    Circlewaves Team <support@circlewaves.com>
 * @license   GPL-2.0+
 * @link      http://circlewaves.com
 * @copyright 2014 Circlewaves Team <support@circlewaves.com>
 */

/**
 * Plugin class. This class should ideally be used to work with the
 * public-facing side of the WordPress site.
 */
class WP_Brand_Identity_Lite {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   1.0.0
	 *
	 * @var     string
	 */
	const VERSION = '1.0.0';

	/**
	 * Unique identifier for your plugin.
	 *
	 *
	 * The variable name is used as the text domain when internationalizing strings
	 * of text. Its value should match the Text Domain file header in the main
	 * plugin file.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_slug = 'wp-brand-identity-lite';

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;
	
	/**
	 * Plugin Settings Tabs, used on Plugin Settings page
	 *
	 * @since    1.0.0
	 *
	 * @var      array
	 */
	public static $pluginSettingsTabs=array(
		'wpbi_settings_tab_1'=>'Form Style',
		'wpbi_settings_tab_2'=>'Background',
		'wpbi_settings_tab_3'=>'Logo',
		'wpbi_settings_tab_6'=>'Custom Options'
	);
		 
	/**
	 * Plugin Settings, used on Plugin Settings page
	 *
	 * @since    1.0.0
	 *
	 * @var      array
	 */
	public static $pluginSettings=array(
		array(
			'name'=>'wpbi-plugin-version',
			'hidden'=>1
		),			
		/* General Section */
		array(
			'name'=>'wpbi-form-style', 
			'title'=>'Form style',
			'section'=>'form-section',
			'tab'=>'wpbi_settings_tab_1',
			'field'=>array(
				'type'=>'radio-image',
				'options'=>array(
					'wpbi-form-style-1'=>array('Default','wpbi-form-style-1.png'),
					'wpbi-form-style-2'=>array('Circle','wpbi-form-style-2.png')
				)
			)					
		),
		array(
			'name'=>'wpbi-form-position', 
			'title'=>'Form position',
			'section'=>'form-section',
			'tab'=>'wpbi_settings_tab_1',
			'field'=>array(
				'type'=>'radio-image',
				'options'=>array(
					'wpbi-form-left'=>array('Left','wpbi-form-align-left.png'),
					'wpbi-form-center'=>array('Center','wpbi-form-align-center.png'),
					'wpbi-form-right'=>array('Right','wpbi-form-align-right.png'),
				)
			)			
		),	
		array(
			'name'=>'wpbi-form-background-color', 
			'title'=>'Background color',
			'section'=>'form-customization-section',
			'tab'=>'wpbi_settings_tab_1',
			'field'=>array(
				'type'=>'colorpicker',
				'default-color'=>'#ffffff'
			)	
		),			
		array(
			'name'=>'wpbi-form-label-color', 
			'title'=>'Label color',
			'section'=>'form-customization-section',
			'tab'=>'wpbi_settings_tab_1',
			'field'=>array(
				'type'=>'colorpicker',
				'default-color'=>'#777777'
			)	
		),			
		/* Background Section */
		array(
			'name'=>'wpbi-background-color', 
			'title'=>'Background color',
			'section'=>'background-section',
			'tab'=>'wpbi_settings_tab_2',
			'field'=>array(
				'type'=>'colorpicker',
				'default-color'=>'#f1f1f1'
			)	
		),
		/* Logo Section */
		array(
			'name'=>'wpbi-logo-image', 
			'title'=>'Logo image',
			'section'=>'logo-section',
			'tab'=>'wpbi_settings_tab_3',
			'field'=>array(
				'type'=>'text-upload-image',
				'description'=>'Recommended size - up to 320px in width'
			)	
		),
		array(
			'name'=>'wpbi-logo-width', 
			'title'=>'Logo Width',
			'section'=>'logo-section',
			'tab'=>'wpbi_settings_tab_3',
			'field'=>array(
				'type'=>'text',
				'class'=>'small-text',
				'description'=>'px'
			)				
		),		
		array(
			'name'=>'wpbi-logo-height', 
			'title'=>'Logo Height',
			'section'=>'logo-section',
			'tab'=>'wpbi_settings_tab_3',
			'field'=>array(
				'type'=>'text',
				'class'=>'small-text',
				'description'=>'px'
			)				
		),				
		/* Custom CSS Section*/
		array(
			'name'=>'wpbi-custom-css',
			'title'=>'Custom CSS',
			'section'=>'custom-css-section',
			'tab'=>'wpbi_settings_tab_6',
			'field'=>array(
				'type'=>'textarea',
				'class'=>'large-text custom-css'
			)
		)		
	);		
	
	/**
	 * Plugin Settings Values
	 *
	 * @since    1.0.0
	 *
	 * @var      array
	 */
	public static $pluginDefaultSettings=array(
		'plugin-version'=>array(
			'name'=>'wpbi-plugin-version',
			'value'=>'1.0.0'
		), // FORM STYLE
		'form-style'=>array(
			'name'=>'wpbi-form-style',
			'value'=>'wpbi-form-style-1'
		),
		'form-position'=>array(
			'name'=>'wpbi-form-position',
			'value'=>'wpbi-form-center'
		),
		'form-background-color'=>array(
			'name'=>'wpbi-form-background-color',
			'value'=>'#ffffff'
		),		
		'form-label-color'=>array(
			'name'=>'wpbi-form-label-color',
			'value'=>'#777777'
		),	// BACKGROUND	
		'background-color'=>array(
			'name'=>'wpbi-background-color',
			'value'=>'#f1f1f1'
		)
	);	

	/**
	 * Initialize the plugin by setting localization and loading public scripts
	 * and styles.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {

		// Load plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// Activate plugin when new blog is added
		add_action( 'wpmu_new_blog', array( $this, 'activate_new_site' ) );

		// Load public-facing style sheet and JavaScript.
		//add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles_and_scripts' ) );
		add_action( 'login_enqueue_scripts', array( $this, 'enqueue_styles_and_scripts' ) );
		
		/* Add class name to body on login screen */
		add_filter('login_body_class', array($this,'add_body_classname'));
		
	}

	/**
	 * Return the plugin slug.
	 *
	 * @since    1.0.0
	 *
	 * @return    Plugin slug variable.
	 */
	public function get_plugin_slug() {
		return $this->plugin_slug;
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Fired when the plugin is activated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses
	 *                                       "Network Activate" action, false if
	 *                                       WPMU is disabled or plugin is
	 *                                       activated on an individual blog.
	 */
	public static function activate( $network_wide ) {

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if ( $network_wide  ) {

				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {

					switch_to_blog( $blog_id );
					self::single_activate();
				}

				restore_current_blog();

			} else {
				self::single_activate();
			}

		} else {
			self::single_activate();
		}

	}

	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses
	 *                                       "Network Deactivate" action, false if
	 *                                       WPMU is disabled or plugin is
	 *                                       deactivated on an individual blog.
	 */
	public static function deactivate( $network_wide ) {

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if ( $network_wide ) {

				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {

					switch_to_blog( $blog_id );
					self::single_deactivate();

				}

				restore_current_blog();

			} else {
				self::single_deactivate();
			}

		} else {
			self::single_deactivate();
		}

	}

	/**
	 * Fired when a new site is activated with a WPMU environment.
	 *
	 * @since    1.0.0
	 *
	 * @param    int    $blog_id    ID of the new blog.
	 */
	public function activate_new_site( $blog_id ) {

		if ( 1 !== did_action( 'wpmu_new_blog' ) ) {
			return;
		}

		switch_to_blog( $blog_id );
		self::single_activate();
		restore_current_blog();

	}

	/**
	 * Get all blog ids of blogs in the current network that are:
	 * - not archived
	 * - not spam
	 * - not deleted
	 *
	 * @since    1.0.0
	 *
	 * @return   array|false    The blog ids, false if no matches.
	 */
	private static function get_blog_ids() {

		global $wpdb;

		// get an array of blog ids
		$sql = "SELECT blog_id FROM $wpdb->blogs
			WHERE archived = '0' AND spam = '0'
			AND deleted = '0'";

		return $wpdb->get_col( $sql );

	}

	/**
	 * Fired for each blog when the plugin is activated.
	 *
	 * @since    1.0.0
	 */
	private static function single_activate() {
			//Add plugin options (it does nothing if option already exists)
			foreach(self::$pluginDefaultSettings as $k=>$v){
				add_option( self::$pluginDefaultSettings[$k]['name'], self::$pluginDefaultSettings[$k]['value'] );	
			}		
			
			//Always update plugin version
			update_option( self::$pluginDefaultSettings['plugin-version']['name'], self::$pluginDefaultSettings['plugin-version']['value'] );
	}

	/**
	 * Fired for each blog when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 */
	private static function single_deactivate() {
		// @TODO: Define deactivation functionality here
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		$domain = $this->plugin_slug;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, FALSE, basename( plugin_dir_path( dirname( __FILE__ ) ) ) . '/languages/' );

	}

	/**
	 * Register and enqueue public-facing style sheet and js files.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles_and_scripts() {
		$plugin_options = self::plugin_get_settings();
		?>
		<style type="text/css">
			<?php /* Form Customization */ ?>
			body.wp-brand-identity #login form{background-color:<?php echo $plugin_options['wpbi-form-background-color']; ?>;}
			body.wp-brand-identity #login form label{color:<?php echo $plugin_options['wpbi-form-label-color']; ?>;}
			<?php /* Background */ ?>
			body.wp-brand-identity{background-color:<?php echo $plugin_options['wpbi-background-color']; ?>;}
			<?php /* Logo */ ?>
			<?php if(isset($plugin_options['wpbi-logo-image'])&&($plugin_options['wpbi-logo-image'])){?>
			body.wp-brand-identity #login h1 a{background-image:url('<?php echo $plugin_options['wpbi-logo-image'];?>');width:<?php echo isset($plugin_options['wpbi-logo-width'])?$plugin_options['wpbi-logo-width']:'320';?>px;height:<?php echo isset($plugin_options['wpbi-logo-height'])?$plugin_options['wpbi-logo-height']:'80';?>px;background-size: cover;}
			<?php } ?>
			<?php /* Custom CSS */ ?>
			<?php 
			if(isset($plugin_options['wpbi-custom-css'])&&($plugin_options['wpbi-custom-css'])){
				echo $plugin_options['wpbi-custom-css'];
			}
			?>
		</style>
		<?php
		wp_enqueue_style( $this->plugin_slug . '-plugin-styles', plugins_url( 'assets/css/public.css', __FILE__ ), array(), self::VERSION );
	}
	
	/**
	 * Get plugin settings
	 *
	 * @since    1.0.0
	 */	
 	public function plugin_get_settings($is_js_friendly=0) {
		$amplayer_options=array();
		foreach(self::$pluginSettings as $setting){
			$option_key=$is_js_friendly?(str_replace('-','_',$setting['name'])):$setting['name']; // Replace "-" to "_" in array key to make array js friendly (to use it in localize_script)
			$amplayer_options[$option_key] = get_option( $setting['name'] );
		}
		return $amplayer_options;
	}	 	
	
	/**
	 * Add class name to body on login screen.
	 *
	 * @since    1.0.0
	 */
	public function add_body_classname($classes) {
		$plugin_options = self::plugin_get_settings();
		
		$classes[] = 'wp-brand-identity';	
		
		if(isset($plugin_options['wpbi-form-style'])&&($plugin_options['wpbi-form-style'])){ // Form style class
			$classes[] = $plugin_options['wpbi-form-style'];	
		}
		if(isset($plugin_options['wpbi-form-position'])&&($plugin_options['wpbi-form-position'])){ // Form position class
			$classes[] = $plugin_options['wpbi-form-position'];	
		}		
		
		return $classes;
	}	
	
}
