<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Adds the path to plugin templates to BuddyPress 1.7 BP Theme Compat stack
 * 
 * @param array $templates the different template stacks available
 * @uses bp_is_current_component() to check for BuddyDrive Component
 * @uses buddydrive_is_group() to check for BuddyDrive component in groups
 * @uses buddydrive_get_plugin_dir() the path to plugin dir
 * @return array $templates the same array with a new value for BuddyDrive path
 */
function buddydrive_get_template_stack( $templates ) {
	
	if ( bp_is_current_component( 'buddydrive' ) || buddydrive_is_group()  ) {
		
		$templates[] = trailingslashit( buddydrive_get_plugin_dir() . 'templates' );
	}
	
	return $templates;
}

add_filter( 'bp_get_template_stack', 'buddydrive_get_template_stack', 10, 1 );


/**
 * Filters bp_located_template() to eventually add the path to our template (bp-default)
 * 
 * @param string $found_template
 * @param array $templates
 * @uses buddydrive_is_bp_default() to check for BP Default or BuddyPress standalone themes
 * @uses bp_is_current_component() to check for BuddyDrive component
 * @uses buddydrive_get_plugin_dir() the path to plugin dir
 * @return string the found template
 */
function buddydrive_load_template_filter( $found_template, $templates ) {
	global $bp, $bp_deactivated;
	
	if ( !buddydrive_is_bp_default() )
		return $found_template;

	//Only filter the template location when we're on the example component pages.
	if ( !bp_is_current_component( 'buddydrive' ) )
		return $found_template;

	foreach ( (array) $templates as $template ) {
		if ( file_exists( STYLESHEETPATH . '/' . $template ) )
			$filtered_templates[] = STYLESHEETPATH . '/' . $template;
		else
			$filtered_templates[] = buddydrive_get_plugin_dir() . '/templates/' . $template;
	}

	$found_template = $filtered_templates[0];

	return apply_filters( 'buddydrive_load_template_filter', $found_template );
}

add_filter( 'bp_located_template', 'buddydrive_load_template_filter', 10, 2 );


/**
 * Checks if the active theme is  BP Default or a child or a standalone
 *
 * @uses get_stylesheet() to check for BP Default
 * @uses get_template() to check for a Child Theme of BP Default
 * @uses current_theme_supports() to check for a standalone BuddyPress theme
 * @return boolean true or false
 */
function buddydrive_is_bp_default() {
	if( in_array( 'bp-default', array( get_stylesheet(), get_template() ) ) )
        return true;

    if( current_theme_supports( 'buddypress') )
    	return true;

    else
        return false;
}


/**
 * Chooses the best way to load BuddyDrive templates
 * 
 * @param string $template the template needed
 * @param boolean $require_once if we need to load it only once or more
 * @uses buddydrive_is_bp_default() to check for BP Default
 * @uses load_template()
 * @uses bp_get_template_part()
 */
function buddydrive_get_template( $template = false, $require_once = true ) {
	if( empty( $template ) )
		return false;
	
	if( buddydrive_is_bp_default() ) {

		$template = $template . '.php';

		if ( file_exists( STYLESHEETPATH . '/' . $template ) )
			$filtered_templates = STYLESHEETPATH . '/' . $template;
		else
			$filtered_templates = buddydrive_get_plugin_dir() . '/templates/' . $template;
		
		load_template( apply_filters( 'buddydrive_get_template', $filtered_templates ),  $require_once);
		
	} else {
		bp_get_template_part( $template );
	}
}


/**
 * Filters bp_get_template_part() to use our template file
 * 
 * @param array $templates
 * @param string $slug
 * @param string $name
 * @return array our template
 */
function buddydrive_filter_template_part( $templates, $slug, $name ) {
	if( $slug != 'members/single/plugins' )
		return $templates;
	
	return array( 'buddydrive-explorer.php' );
}


/**
 * Loads the BuddyDrive Explorer for user's screen
 *
 * @uses bp_core_load_template to load the template
 * @uses buddydrive_is_bp_default() to check for BP Default
 */
function buddydrive_user_files(){
	
	bp_core_load_template( apply_filters( 'buddydrive_user_files', 'buddydrive-explorer' ) );
	
	if( !buddydrive_is_bp_default() )
		add_filter( 'bp_get_template_part', 'buddydrive_filter_template_part', 10, 3 );
}


/**
 * Loads the BuddyDrive Explorer for shared by friends screen
 *
 * @uses bp_core_load_template to load the template
 * @uses buddydrive_is_bp_default() to check for BP Default
 */
function buddydrive_friends_files(){
	
	bp_core_load_template( apply_filters( 'buddydrive_friends_files', 'buddydrive-explorer' ) );
	
	if( !buddydrive_is_bp_default() )
		add_filter( 'bp_get_template_part', 'buddydrive_filter_template_part', 10, 3 );
}


/**
 * Loads the Main BuddyDrive template
 * 
 * @uses bp_displayed_user_id() to check we're not on a user's page
 * @uses bp_is_current_component() to check we're on BuddyDrive component
 * @uses bp_update_is_directory() to indicates we're on BuddyDrive main directory
 * @uses bp_core_load_template() to finally load the template.
 */
function buddydrive_screen_index() {
	
	if ( !bp_displayed_user_id() && bp_is_current_component( 'buddydrive' ) ) {
		bp_update_is_directory( true, 'buddydrive' );

		do_action( 'buddydrive_screen_index' );

		bp_core_load_template( apply_filters( 'buddydrive_screen_index', 'buddydrive' ) );
	}
}

add_action( 'buddydrive_screens', 'buddydrive_screen_index' );



/** Theme Compatability *******************************************************/

/**
 * The main theme compat class for BuddyDrive
 *
 * This class sets up the necessary theme compatability actions to safely output
 * BuddyDrive template parts to the_title and the_content areas of a theme.
 *
 * @since BuddyDrive (1.0)
 */
class BuddyDrive_Theme_Compat {

	/**
	 * Setup the BuddyDrive component theme compatibility
	 *
	 * @since BuddyDrive (1.0)
	 */
	public function __construct() {
		
		add_action( 'bp_setup_theme_compat', array( $this, 'is_buddydrive' ) );
	}

	/**
	 * Are we looking at something that needs BuddyDrive theme compatability?
	 *
	 * @since BuddyDrive (1.0)
	 * 
	 * @uses bp_displayed_user_id() to check we're not on a user's page
 	 * @uses bp_is_current_component() to check we're on BuddyDrive component
	 */
	public function is_buddydrive() {
		
		if ( !bp_displayed_user_id() && bp_is_current_component( 'buddydrive' ) ) {

			add_action( 'bp_template_include_reset_dummy_post_data', array( $this, 'directory_dummy_post' ) );
			add_filter( 'bp_replace_the_content',                    array( $this, 'directory_content'    ) );

		}
		
	}

	/** Directory *************************************************************/

	/**
	 * Update the global $post with directory data
	 *
	 * @since BuddyDrive (1.0)
	 *
	 * @uses bp_theme_compat_reset_post() to reset the post data
	 */
	public function directory_dummy_post() {

		bp_theme_compat_reset_post( array(
			'ID'             => 0,
			'post_title'     => buddydrive_get_name(),
			'post_author'    => 0,
			'post_date'      => 0,
			'post_content'   => '',
			'post_type'      => 'buddydrive_dir',
			'post_status'    => 'publish',
			'is_archive'     => true,
			'comment_status' => 'closed'
		) );
	}

	/**
	 * Filter the_content with the groups index template part
	 *
	 * @since BuddyDrive (1.0)
	 *
	 * @uses bp_buffer_template_part()
	 */
	public function directory_content() {
		
		bp_buffer_template_part( 'buddydrive' );
	}
	
}

new BuddyDrive_Theme_Compat();
