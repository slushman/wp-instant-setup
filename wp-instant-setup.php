<?php
/*
Plugin Name: WP Instant Setup
Plugin URI: http://slushman.com/plugins/wp-instant-setup
Description: Configure all the WordPress site settings in one click.
Version: 0.1
Author: Slushman
Author URI: http://www.slushman.com
License: GPL2

**************************************************************************

  Copyright (C) 2013 Slushman

  This program is free software: you can redistribute it and/or modify
  it under the terms of the GNU General License as published by
  the Free Software Foundation, either version 3 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General License for more details.

  You should have received a copy of the GNU General License
  along with this program.  If not, see <http://www.gnu.org/licenses/>.

**************************************************************************

TODO:

*/

if ( !class_exists( 'WP_Instant_Setup' ) ) { //Start Class

	class WP_Instant_Setup {
	
		public static $instance;
		const PLUGIN_NAME 		= 'WP Instant Setup';
		const PLUGIN_SLUG		= 'wp-instant-setup';
		const SETS_NAME			= 'wp_instant_setup_settings';
		const SETS_SLUG			= 'wp-instant-setup-settings';


/**
 * Constructor
 */
		function __construct() {
		
			self::$instance = $this;
			
			// Runs when plugin is activated
			register_activation_hook( __FILE__, array( $this, 'install' ) );
			
			// Adds the ArtistDataPress option menu to the Settings menu
			add_action( 'admin_menu', array( $this, 'add_menu' ) );
			
			//	Add "Settings" link to plugin page
			add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ) , array( $this, 'settings_link' ) );
			
			$this->tabs = array( 'Instant', 'WordPress' );
			
			$this->settings['admin_email'] 					= array( NULL, 'text' );
			$this->settings['avatar_default'] 				= array( 'mystery', 'radio' );
			$this->settings['avatar_rating'] 				= array( 'G', 'radio' );
			$this->settings['blacklist_keys'] 				= array( NULL, 'textarea' );
			$this->settings['blogdescription'] 				= array( __( 'Just another WordPress weblog' ), 'text' );
			$this->settings['blogname'] 					= array( __( 'My Blog' ), 'text' );
			$this->settings['blog_public'] 					= array( 1, 'checkbox' );
			$this->settings['category_base'] 				= array( NULL, 'text' );
			$this->settings['close_comments_days_old'] 		= array( 14, 'number' );
			$this->settings['close_comments_for_old_posts'] = array( 0, 'checkbox' );
			$this->settings['comment_max_links'] 			= array( 2, 'number' );
			$this->settings['comment_moderation'] 			= array( 0, 'checkbox' );
			$this->settings['comments_notify'] 				= array( 1, 'checkbox' );
			$this->settings['comment_order'] 				= array( 'asc', 'select' );
			$this->settings['comment_registration'] 		= array( 0, 'checkbox' );
			$this->settings['comment_whitelist'] 			= array( 1, 'textarea' );
			$this->settings['comments_per_page'] 			= array( 50, 'number' );
			$this->settings['date_format'] 					= array( __( 'F j, Y' ), 'text' );
			$this->settings['default_category'] 			= array( 1, 'select' );
			$this->settings['default_comment_status'] 		= array( 'open', 'select' );
			$this->settings['default_comments_page'] 		= array( 'newest', 'select' );
			$this->settings['default_email_category'] 		= array( 1, 'select' );
			$this->settings['default_link_category'] 		= array( NULL, 'select' );
			$this->settings['default_ping_status'] 			= array( 1, 'checkbox' );
			$this->settings['default_pingback_flag'] 		= array( 1, 'checkbox' );
			$this->settings['default_post_format'] 			= array( FALSE, 'select' );
			$this->settings['default_role'] 				= array( 'subscriber', 'select' );			
			$this->settings['home'] 						= array( wp_guess_url(), 'text' );
			$this->settings['large_size_h'] 				= array( 1024, 'number' );
			$this->settings['large_size_w'] 				= array( 1024, 'number' );
			$this->settings['mailserver_login'] 			= array( 'login@example.com', 'email' );
			$this->settings['mailserver_pass'] 				= array( 'password', 'text' );
			$this->settings['mailserver_port'] 				= array( 110, 'number' );
			$this->settings['mailserver_url'] 				= array( 'mail.example.com',  );
			$this->settings['medium_size_h'] 				= array( 300, 'number' );
			$this->settings['medium_size_w'] 				= array( 300, 'number' );
			$this->settings['moderation_keys'] 				= array( NULL, 'textarea' );
			$this->settings['moderation_notify'] 			= array( 1, 'checkbox' );
			$this->settings['page_comments'] 				= array( 0, 'checkbox' );
			$this->settings['permalink_structure'] 			= array( NULL, 'text' );
			$this->settings['ping_sites'] 					= array( 'http://rpc.pingomatic.com/',  );
			$this->settings['posts_per_page'] 				= array( 10, 'number' );
			$this->settings['posts_per_rss'] 				= array( 10, 'number' );
			$this->settings['require_name_email'] 			= array( 1, 'checkbox' );
			$this->settings['rss_use_excerpt'] 				= array( 0, 'checkbox' );
			$this->settings['show_on_front'] 				= array( 'posts',  );
			$this->settings['show_avatars'] 				= array( 1, 'checkbox' );
			$this->settings['siteurl'] 						= array( wp_guess_url(), 'text' );
			$this->settings['start_of_week'] 				= array( 1, 'select' );
			$this->settings['tag_base'] 					= array( NULL, 'text' );
			$this->settings['thread_comments'] 				= array( 1, 'checkbox' );
			$this->settings['thread_comments_depth'] 		= array( 5, 'number' );
			$this->settings['thumbnail_crop'] 				= array( 1, 'checkbox' );
			$this->settings['thumbnail_size_h'] 			= array( 150, 'number' );
			$this->settings['thumbnail_size_w'] 			= array( 150, 'number' );
			$this->settings['time_format'] 					= array( __( 'g:i a' ), 'text' );
			$this->settings['timezone_string'] 				= array( NULL, 'select' );
			$this->settings['uploads_use_yearmonth_folders']= array( 1, 'checkbox' );
			$this->settings['use_balanceTags'] 				= array( 0, 'checkbox' );
			$this->settings['use_smilies'] 					= array( 1, 'checkbox' );
			$this->settings['users_can_register'] 			= array( 0, 'checkbox' );
			
		} // End of __construct()
		
/**
 * Creates the plugin options
 *
 * @since	0.1
 *
 * @uses	settings_init
 */	
		function install() {

			
			
		} // End of install()		
		


/* ==========================================================================
   Plugin Settings
   ========================================================================== */

/**
 * Adds the plugin settings page to the appropriate admin menu
 *
 * @since	0.1
 * 
 * @uses	add_submenu_page
 */				
		function add_menu() {
		
			add_options_page( 
				self::PLUGIN_NAME . 'Options', 
				self::PLUGIN_NAME, 
				'manage_options', 
				self::SETS_SLUG, 
				array( $this, 'settings_page' ) 
			);
			
		} // End of add_menu()

/**
 * Adds a link to the plugin settings page to the plugin's listing on the plugin page
 *
 * @since	0.1
 * 
 * @uses	admin_url
 */			
		function settings_link( $links ) {
					
			$settings_link = sprintf( '<a href="%s">%s</a>', admin_url( 'options-general.php?page=' . self::SETS_SLUG ), __( 'Settings' ) );
		
			array_unshift( $links, $settings_link );
		
			return $links;
			
		} // End of settings_link()
		
/**
 * Creates the settings page
 *
 * @since	0.1
 *
 * @uses	check_admin_referer
 * @uses	sanitize_text_field
 * @uses	sanitize_email
 * @uses	set_options
 * @uses	delete_defaults
 * @uses	reset_options
 * @uses	get_plugin_data
 * @uses	wp_nonce_field
 * @uses	hidden_field
 * @uses	input_field
 * @uses	submit_button
 */					
		function settings_page() {
		
			if ( isset( $_POST['mode'] ) && $_POST['mode'] == 'setup' && check_admin_referer( 'wpis_setup_nonce' ) && isset( $_POST['blogname'] ) && isset( $_POST['admin_email'] ) ) {
			
				$valid['blogname'] 		= sanitize_text_field( $_POST['blogname'] );
				$valid['admin_email'] 	= sanitize_email( $_POST['admin_email'] );
			
				$this->set_options( $valid );
				$this->delete_defaults();
			
			} elseif ( isset( $_POST['mode'] ) && $_POST['mode'] == 'reset' && check_admin_referer( 'wpis_reset_nonce' ) ) {
			
				$this->reset_options( $valid );
			
			} // End of submit check

			$plugin = get_plugin_data( __FILE__ ); ?>
			
			<div class="wrap">
			<div class="icon32"></div>
			<h2><?php echo $plugin['Name']; ?></h2>
			<h3>Instant Setup Settings</h3>
			<p>Also deletes the sample post, sample page, and sample comment.</p>
			<form method="post" action="admin.php?page=<?php echo self::SETS_SLUG; ?>"><?php
			
				wp_nonce_field( 'wpis_setup_nonce' );
				
				echo $this->hidden_field( array( 'name' => 'mode', 'value' => 'setup' ) );
			
				$i 						= 0;
				$fields[$i]['class'] 	= 'regular-text';
				$fields[$i]['id'] 		= $fields[$i]['name'] = 'blogname';
				$fields[$i]['desc'] 	= 'The title of your site.';
				$fields[$i]['type'] 	= 'text';
				$i++;
				
				$fields[$i]['class'] 	= 'regular-text';
				$fields[$i]['id'] 		= $fields[$i]['name'] = 'admin_email';
				$fields[$i]['desc'] 	= 'This address is used for admin purposes, like new user notification.';
				$fields[$i]['type'] 	= 'text';
				$i++;
				
				foreach ( $fields as $field ) {
				
					echo '<p>' . $this->input_field( $field ) . '</p>';
					
				} // End of $fields foreach
					
				submit_button( 'Setup WordPress' ); ?>
				
			</form>
			<br /><br />
			<h3>Reset WordPress Settings</h3>
			<p>Reset all WordPress settings back to the defaults (as if you've just installed WordPress).</p>
			<p>*Does not recreate the sample post, sample page, and sample comment.</p>
			
			<form method="post" action="admin.php?page=<?php echo self::SETS_SLUG; ?>"><?php
			
				wp_nonce_field( 'wpis_reset_nonce' );

				echo $this->hidden_field( array( 'name' => 'mode', 'value' => 'reset' ) );
			
				submit_button( 'Reset Settings' ); ?>
				
			</form>
			</div><?php
			
		} // End of settings_page()		
		
		
		
/* ==========================================================================
	Plugin Functions
========================================================================== */

/**
 * Deletes the sample post, sample page, and sample comment installed in WordPress by default
 *
 * @since	0.1
 *
 * @uses	wp_delete_post
 * @uses	wp_delete_comment
 */
		function delete_defaults() {
			
			wp_delete_post( 1, TRUE );
			wp_delete_post( 2, TRUE );
			wp_delete_comment( 1 );
			
		} // End of delete_defaults()
		
/**
 * Resets all WordPress options to the default values.
 *
 * @since	0.1
 *
 * @uses	update_option
 */
		function reset_options() {
					
			foreach ( $this->settings as $key => $value ) {

				update_option( $key, $value[0] );
			
			} // End of $settings foreach
			
		} // End of set_options()		
		
/**
 * Sets certain WordPress options to my preferred values. Also calls delete_defaults() to remove
 * sample post, sample page, and sample comment.
 *
 * @since	0.1
 *
 * @uses	update_option
 */		
		function set_options( $params ) {
		
			$newsets['admin_email'] 					= $params['admin_email'];
			$newsets['avatar_rating'] 					= 'G';
			$newsets['blogname'] 						= __( $params['blogname'] );
			$newsets['blogdescription'] 				= NULL;
			$newsets['close_comments_days_old'] 		= 30;
			$newsets['close_comments_for_old_posts'] 	= 1;
			$newsets['comment_registration'] 			= 1;
			$newsets['default_comments_page'] 			= 'newest';
			$newsets['default_pingback_flag'] 			= 1;
			$newsets['mailserver_login'] 				= NULL;
			$newsets['mailserver_pass'] 				= NULL;
			$newsets['mailserver_url'] 					= NULL;
			$newsets['permalink_structure'] 			= '/%category%/%postname%/';
			$newsets['start_of_week'] 					= 0;
			$newsets['timezone_string'] 				= 'America/Chicago';
			$newsets['users_can_register'] 				= 1;
			
			foreach ( $newsets as $key => $value ) {

				update_option( $key, $value );
			
			} // End of $settings foreach
			
		} // End of set_options()



/* ==========================================================================
   Slushman Toolkit Functions
   ========================================================================== */
   
/**
 * Builds a form based on the $params array
 *
 * Builds a form based on the $params array
 * 
 * The params array contains multiple arrays with the info for each field needed for the form
 * The details for those arrays can be found in each field's function below.
 * The only difference is each field will need a type (text, checkbox, etc) to make sure
 * the correct function gets called.
 * 
 * @since	0.1
 * 
 * @param	array	$params An array of the data for the form fields
 *
 * @return	mixed	A properly formatted HTML table with fields specified by the params
 */	
 	
	 	function build_form( $form, $fields ) {
	 	
	 		extract( $form );
	 	
		 	$output = ( !empty( $nonce ) ? wp_nonce_field( basename( __FILE__ ), $nonce ) : '' );
		 	$output .= '<table class="' . $tableclass . '">';
		 	
		 	foreach ( $fields as $field ) {
		 	
		 		extract( $field );
		 	
		 		if ( $type == 'price' || $type == 'time_fields' ) {
	
			 		$args = $field;
	
			 	} else {
			 	
			 		$checks = array( 'blank', 'check', 'class', 'desc', 'fieldtype', 'grouptype', 'id', 'type', 'selections' );
			 	
			 		foreach ( $checks as $check ) {
				 		
			 			$args[$check] = ( !empty( $field[$check] ) ? $field[$check] : '' );
				 		
			 		} // End of $param foreach
			 		
			 		if ( $type == 'post' ) {
		 		
				 		global $post;
				 		
				 		$args['value'] = get_post_meta( $post->ID, $id, TRUE );
				 		
			 		} elseif ( is_object( $type ) ) {
			 		
				 		$args['value'] = $type->$id;
				 		
			 		} else {
				 		
				 		$args['value'] = $value;
				 		
			 		} // End of ID check
			 	
				} // End of $type check
				
				$output .= '<tr><th><label for="' . $id . '">' . $label . '</label></th>';
			 	$output .= '<td>' . $this->$type( $args ) . '</td></tr>';
			 	
		 	} // End of $params foreach
		 	
		 	$output .= '</table>';
		 	
		 	return $output;
		 	 	
	 	} // End of build_form()
	 	
/**
 * Creates an hidden field based on the params
 *
 * @params are:
 *  name - (optional), can be a separate value from ID
 *	value - used for the value attribute
 * 
 * @since	0.1
 * 
 * @param	array	$params		An array of the data for the hidden field
 *
 * @return	mixed	$output		A properly formatted HTML hidden field
 */			
		function hidden_field( $params ) { 
		
			extract( $params );
						
			$showname 	= ( !empty( $name ) ? '" name="' . $name . '"' : '' );
			$output 	= '<input type="hidden"' . $showname . 'value="' . ( !empty( $value ) ? $value : '' ) . '"' . ' />';
			
			return $output;
			
		} // End of hidden_field() 	
		
/**
 * Creates an input field based on the params
 *
 * Creates an input field based on the params
 * 
 * @params are:
 * 	class - used for the class attribute
 * 	desc - description used for the description span
 * 	id - used for the id and name attributes
 *	label - the label to use in front of the field
 *  name - (optional), can be a separate value from ID
 *  placeholder - The that appears in th field before a value is entered.
 *  type - detemines the particular type of input field to be created
 *	value - used for the value attribute
 * 
 * Type options: 
 *  email - email address
 *  file - file upload
 *  number - number field
 *  text - standard text field
 *  search - search field
 *  tel - phone numbers
 *  url - url
 *
 * @since	0.6
 * 
 * @param	array	$params		An array of the data for the text field
 *
 * @return	mixed	$output		A properly formatted HTML input field with optional label and description
 */			
		function input_field( $params ) { 
		
			extract( $params );
						
			$showid 	= ( !empty( $id ) ? ' id="' . $id . '" name="' . ( !empty( $name ) ? $name : $id ) . '"' : '' );
			$showclass 	= ( !empty( $class ) ? ' class="' . $class . '"' : '' );
			$showtype	= ( !empty( $type ) ? ' type="' . $type . '"' : '' );
			$showvalue	= ( !empty( $value ) ? ' value="' . $value . '"' : 'value=""' );
			$showph		= ( !empty( $placeholder ) ? ' placeholder="' . $placeholder . '"' : '' );
			
			$output 	= ( !empty( $label ) ? '<label for="' . $id . '">' . $label . '</label>' : '' );
			$output 	.= '<input' . $showtype . $showid . $showvalue . $showclass . $showph . ' />';
			$output 	.= ( !empty( $desc ) ? '<br /><span class="description">' . $desc . '</span>' : '' );
			
			return $output;
			
		} // End of input_field()
	
/**
 * Creates a group of either checkboxes, a dropmenu, or radio buttons based on the params
 *
 * Creates a group of either checkboxes, a dropmenu, or radio buttons based on the params
 * 
 * @params are:
 *  blank - true or false, if you want a blank option, or enter text for the blank selector
 * 	class - used for the class attribute
 * 	desc - description used for the description span
 * 	id - used for the id and name attributes
 *  inputtype - determines if the output is radio buttons or checkbox group (checkgroup)
 *	label - the label to use in front of the field
 *	value - used in the checked / selected function
 *	selections - an array of data to use as the selections in the menu
 *
 * @since	0.1
 * 
 * @param	array	$params		An array of the data for the checkbox group
 *
 * @return	mixed	$output		A properly formatted HTML checkbox group with optional label and description
 */	
		function input_group( $params ) {
			
			extract( $params );
			
			$showid 	= ( !empty( $id ) ? ' id="' . $id . '"' : '' );
			$showname 	= ' name="' . ( !empty( $name ) ? $name : ( !empty( $id ) ? $id : '' ) ) . '"';
			$showclass 	= ( !empty( $class ) ? ' class="' . $class . '"' : '' );
			$showblank	= ( !empty( $blank ) ? $blank : '' );
			
			$output 	= ( !empty( $label ) ? '<label for="' . $id . '">' . $label . '</label>' : '' );
			
			if (  $inputtype == 'dropmenu'  ) {
				
				$output .= '<select' . $showid . $showname . $showclass .'>';
				
				if ( is_bool( $showblank ) && $showblank == TRUE ) {
					
					$output .= '<option></option>';
					
				} elseif ( !is_bool( $showblank ) ) {
				
					$output .= '<option>' . __( $showblank ) . '</option>';
				
				} // End of $blank empty check
				
				foreach ( $selections as $selection ) {
				
					extract( $selection, EXTR_PREFIX_ALL, 'sel' );
				
					$optvalue = ( !empty( $sel_value ) ? ' value="' . $sel_value . '"' : '' );				
					$selected = selected( $value, $sel_value, FALSE );
					
					$output .= '<option' . $optvalue . $selected . ' >' . $sel_label . '</option>';
					
				} // End of $selections foreach
				
				$output .= '</select>';
				
			} else {
				
				$output .= '<div id="' . $id . '">';
				
				foreach ( $selections as $selection ) {
				
					extract( $selection, EXTR_PREFIX_ALL, 'sel' );
				
					$optvalue 	= ( !empty( $sel_value ) ? ' value="' . $sel_value . '"' : '' );
					$checked	= checked( $value, $sel_value, FALSE );
					$opttype	= ' type="' . ( $inputtype == 'checkgroup' ? 'checkbox' : 'radio' ) . '"';	
					
					$output 	.= '<input' . $opttype . $showid . $showname . $optvalue . $showclass . $checked . ' />';
					$output 	.= '<label for="' . $sel_label . '">' . $sel_label . '</label><br />';
					
				} // End of $selections foreach
				
				$output .= '</div>';
				
			} // End of $inputtype check
			
			if ( !empty( $desc ) ) {
				
				$output .= '<br /><span class="description">' . $desc . '</span>';
				
			} // End of $desc empty check
			
			return $output;
						
		} // End of input_group()
		
/**
 * Display an array in a nice format
 *
 * @param	array	The array you wish to view
 */			
		function print_array( $array ) {

		  echo '<pre>';
		  
		  print_r( $array );
		  
		  echo '</pre>';
		
		} // End of print_array()
		
/**
 * Creates an HTML textarea
 *
 * @params are:
 * 	class - used for the class attribute
 * 	desc - description used for the description span
 *  name - used for the name span
 *	label - the label to use in front of the field
 *	value - used in the checked function
 *
 * @since	0.1
 * 
 * @param	array	$params		An array of the data for the textarea
 *
 * @return	mixed	$output		A properly formatted HTML textarea with optional label and description
 */
		function textarea( $params ) {
			
			extract( $params );
						
			$showclass 	= ( !empty( $class ) ? ' class="' . $class . '"' : '' );
			$showid 	= ( !empty( $id ) ? ' id="' . $id . '"' : '' );
			$showname 	= ' name="' . ( !empty( $name ) ? $name : ( !empty( $id ) ? $id : '' ) ) . '"';
			
			$output 	= ( !empty( $label ) ? '<label for="' . $id . '">' . $label . '</label>' : '' );
			$output 	.= '<textarea ' . $showname . $showclass . $showid . ' cols="50" rows="10" wrap="hard">' . esc_textarea( $value ) . '</textarea>';
			$output 	.= ( !empty( $desc ) ? '<br /><span class="description">' . $desc . '</span>' : '' );
			
			return $output;
			
		} // End of textarea()
		
	} // End of class
	
} // End of class check

new WP_Instant_Setup;