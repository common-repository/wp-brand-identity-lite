<?php
/**
 * Represents the view for the administration dashboard.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 * @package   WP_Brand_Identity
 * @author    Circlewaves Team <support@circlewaves.com>
 * @license   GPL-2.0+
 * @link      http://circlewaves.com
 * @copyright 2014 Circlewaves Team <support@circlewaves.com>
 */
?>

<?php
$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'wpbi_settings_tab_1'
?>

<div class="wrap wpbi-settings-page">

	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
	
	<h2 class="nav-tab-wrapper">
	<?php foreach(WP_Brand_Identity_Lite::$pluginSettingsTabs as $k=>$v){ ?>
		<a href="?page=<?php echo $this->plugin_slug;?>&tab=<?php echo $k;?>" class="nav-tab <?php echo $active_tab == $k ? 'nav-tab-active' : ''; ?>"><?php _e($v,$this->plugin_slug) ?></a>
	<?php }?>
	</h2>	
	
	<div id="poststuff">
		<div id="post-body" class="metabox-holder columns-2">
	
			<!-- main content -->
			<div id="post-body-content">
		
				<div class="meta-box-sortables1 ui-sortable1">
					<div class="postbox1">
						<div class="inside1">
        <?php settings_errors(); ?>
         
					
							<form action="options.php" method="POST">
								<?php 
									settings_fields( $active_tab );
									do_settings_sections( $active_tab );
									submit_button();
								 ?>
							</form>									
						</div>	
					</div>
				</div>
			</div>
			<!-- end main content -->
			
			<!-- sidebar -->
			<?php include_once( 'sidebar-right.php' );?>
			<!-- end sidebar -->
			
		</div> 
		<!-- end post-body-->
		
		<br class="clear">
	</div>
	<!-- end poststuff -->

</div>
