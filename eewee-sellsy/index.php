<?php 
/*
Plugin Name: Eewee Sellsy
Plugin URI: http://www.eewee.fr
Description: Simple form for : add support ticket to Sellsy, add prospect to Sellsy.
Version: 1.0.0
Author: Michael DUMONTET
Author URI: http://www.eewee.fr/wordpress/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

/**
 * Definitions
 *
 * @since 1.0.0
 */
global $wpdb;
define( 'EEWEE_VERSION', '1.0.0' );
define( 'EEWEE_SELLSY_PLUGIN_DIR', 		WP_PLUGIN_DIR . '/' . dirname( plugin_basename( __FILE__ ) ) );
define( 'EEWEE_SELLSY_PLUGIN_URL', 		WP_PLUGIN_URL . '/' . dirname( plugin_basename( __FILE__ ) ) );
define( 'EEWEE_SELLSY_PREFIXE_BDD',		$wpdb->prefix.'eewee_sellsy_');
define( 'PLUGIN_NOM_LANG',              "eewee-sellsy");
define( 'EEWEE_SELLSY_DEBUG',           false);
for( $i=1 ; $i<=3 ; $i++ ){
    define( 'EEWEE_SELLSY_URL_SOUS_MENU_'.$i,      admin_url( 'admin.php?page=idSousMenu'.$i, 'http' ) );
    define( 'EEWEE_SELLSY_URL_BACK_SOUS_MENU_'.$i, admin_url().'admin.php?page=idSousMenu'.$i);
}

load_plugin_textdomain(PLUGIN_NOM_LANG, false, dirname( plugin_basename( __FILE__ ) ) . '/lang');

/**
 * Required CSS
 *
 * @since 1.0.0
 */
function ajouterScriptsEeweeSellsy(){
	wp_enqueue_style( PLUGIN_NOM_LANG.'-style', '/wp-content/plugins/eewee-sellsy/css/style.css' );
}
add_action( 'init', 'ajouterScriptsEeweeSellsy' );

/**
 * Required Files
 *
 * @since 1.0.0
 */
// LIBS
require_once ( EEWEE_SELLSY_PLUGIN_DIR . '/libs/sellsy/sellsytools.php' );
require_once ( EEWEE_SELLSY_PLUGIN_DIR . '/libs/sellsy/sellsyconnect_curl.php' );

// MODELS
require_once ( EEWEE_SELLSY_PLUGIN_DIR . '/models/TSetting.php' );
require_once ( EEWEE_SELLSY_PLUGIN_DIR . '/models/TTicketError.php' );
require_once ( EEWEE_SELLSY_PLUGIN_DIR . '/models/TTicketForm.php' );
require_once ( EEWEE_SELLSY_PLUGIN_DIR . '/models/TSellsyStaffs.php' );

// FORMS
require_once ( EEWEE_SELLSY_PLUGIN_DIR . '/forms/FSettingEdit.php' );
require_once ( EEWEE_SELLSY_PLUGIN_DIR . '/forms/FTicketFormSearch.php' );
require_once ( EEWEE_SELLSY_PLUGIN_DIR . '/forms/FTicketFormEdit.php' );
require_once ( EEWEE_SELLSY_PLUGIN_DIR . '/forms/FTicketFormAdd.php' );

// CONTROLLERS
require_once( EEWEE_SELLSY_PLUGIN_DIR . '/controllers/InstallController.php' );
require_once( EEWEE_SELLSY_PLUGIN_DIR . '/controllers/ToolsController.php' );
require_once( EEWEE_SELLSY_PLUGIN_DIR . '/controllers/ShortcodeController.php' );
require_once( EEWEE_SELLSY_PLUGIN_DIR . '/controllers/AdminController.php' );
$s = new ShortcodeController();

/**
 * Instantiate Classe
 *
 * @since 1.0.0
 */
$adminController = new AdminController();

/**
 * Wordpress Activate/Deactivate
 *
 * @uses register_activation_hook()
 * @uses register_deactivation_hook()
 * @uses register_uninstall_hook()
 *
 * @since 1.0.0
 */
register_activation_hook( __FILE__, array( $adminController, 'eewee_activate' ) );
register_deactivation_hook( __FILE__, array( $adminController, 'eewee_deactivate' ) );
register_uninstall_hook( __FILE__, array( $adminController, 'eewee_uninstall' ) );

/**
 * Required action filters
 *
 * @uses add_action()
 *
 * @since 1.0.0
 */
add_action( 'admin_menu', array( $adminController, 'eewee_adminMenu' ) );

/**
 * Debug
 * 
 * Print session, post, get, files
 */
if( EEWEE_SELLSY_DEBUG ){
	echo "<pre>"; 
		var_dump( $_SESSION, $_POST, $_GET, $_FILES );
	echo "</pre>"; 
}