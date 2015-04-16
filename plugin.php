<?php
/*
  Plugin Name: Genesis Author Pro
  Plugin URI:
  Description: Adds default Book CPT to any Genesis HTML5 theme.
  Version: 0.9.0
  Author: copyblogger
  Author URI: http://www.copyblogger.com
  Text Domain: genesis-author-pro
  Domain Path /languages/

*/

if ( !defined( 'ABSPATH' ) ) {
	die( "Sorry, you are not allowed to access this page directly." );
}

/**
 * Registered autoload function.
 * Used to load class files automatically if they are in the provided array.
 *
 * @access public
 * @param string $class
 * @return void
 */
function gapro_autoloader($class) {

	$classes = array(
		'Genesis_Author_Pro'               => 'class.Genesis_Author_Pro.php',
		'Genesis_Author_Pro_Activation'    => 'class.Genesis_Author_Pro_Activation.php',
		'Genesis_Author_Pro_Book_Meta'     => 'class.Genesis_Author_Pro_Book_Meta.php',
		'Genesis_Author_Pro_CPT'           => 'class.Genesis_Author_Pro_CPT.php',
		'Genesis_Author_Pro_Save'          => 'class.Genesis_Author_Pro_Save.php',
		'Genesis_Author_Pro_Template'      => 'class.Genesis_Author_Pro_Template.php',
		'Genesis_Author_Pro_Widget'        => 'class.Genesis_Author_Pro_Widget.php',
		'Genesis_Author_Pro_Widget_Admin'  => 'class.Genesis_Author_Pro_Widget_Admin.php',
		'Genesis_Author_Pro_Widget_Output' => 'class.Genesis_Author_Pro_Widget_Output.php',
	);

	if( isset( $classes[$class] ) ) {
		require_once( GENESIS_AUTHOR_PRO_CLASSES_DIR . $classes[$class] );
	}

}
spl_autoload_register( 'gapro_autoloader' );

register_activation_hook( __FILE__, array( 'Genesis_Author_Pro_Activation', 'activate' ) );

define( 'GENESIS_AUTHOR_PRO_CLASSES_DIR'  , dirname(        __FILE__ ) . '/classes/'   );
define( 'GENESIS_AUTHOR_PRO_FUNCTIONS_DIR', dirname(        __FILE__ ) . '/functions/' );
define( 'GENESIS_AUTHOR_PRO_LANGUAGES_DIR', dirname(        __FILE__ ) . '/languages/' );
define( 'GENESIS_AUTHOR_PRO_TEMPLATES_DIR', dirname(        __FILE__ ) . '/templates/' );
define( 'GENESIS_AUTHOR_PRO_RESOURCES_URL', plugin_dir_url( __FILE__ ) . 'resources/'  );

add_action( 'after_setup_theme', array( 'Genesis_Author_Pro_CPT', 'init'            ), 1 );
add_action( 'plugins_loaded'   , array( 'Genesis_Author_Pro'    , 'load_textdomain' )    );

add_action( 'genesis_init', 'genesis_author_pro_init' );
/**
 * Action added on the genesis_init hook.
 * All actions except the init and activate hook are loaded through this function.
 * This ensures that Genesis is available for any Genesis functions that will be used.
 *
 * @access public
 * @return void
 */
function genesis_author_pro_init(){

	$archive_page_hook = sprintf( 'load-%1$s_page_genesis-cpt-archive-%1$s', 'books' );

	add_filter( 'template_include', array( 'Genesis_Author_Pro_Template', 'maybe_include_template' ) );

	add_action( 'after_setup_theme'         , array( 'Genesis_Author_Pro_CPT', 'maybe_add_image_size'  )        );
	add_action( 'load-post.php'             , array( 'Genesis_Author_Pro'    , 'maybe_do_book_meta'    )        );
	add_action( 'load-post-new.php'         , array( 'Genesis_Author_Pro'    , 'maybe_do_book_meta'    )        );
	add_action( 'load-edit-tags.php'        , array( 'Genesis_Author_Pro'    , 'maybe_enqueue_scripts' )        );
	add_action( $archive_page_hook          , array( 'Genesis_Author_Pro'    , 'maybe_enqueue_scripts' )        );
	add_filter( 'bulk_post_updated_messages', array( 'Genesis_Author_Pro'    , 'bulk_updated_messages' ), 10, 2 );
	add_action( 'save_post'                 , array( 'Genesis_Author_Pro'    , 'maybe_do_save'         ), 10, 2 );
	add_action( 'widgets_init'              , array( 'Genesis_Author_Pro'    , 'widgets_init'          )        );

}
