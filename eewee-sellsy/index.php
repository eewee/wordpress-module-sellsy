<?php
/*
Plugin Name: Eewee Sellsy
Plugin URI: http://www.eewee.fr
Description: Simple form for : add support ticket to Sellsy, add prospect to Sellsy.
Version: 1.12
Author: Michael DUMONTET
Author URI: http://www.eewee.fr/wordpress/
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Definitions
 *
 * @since 1.0
 */
global $wpdb;
define('EEWEE_VERSION', '1.12' );
define('EEWEE_SELLSY_PLUGIN_DIR', 		WP_PLUGIN_DIR . '/' . dirname( plugin_basename( __FILE__ ) ) );
define('EEWEE_SELLSY_PLUGIN_URL', 		WP_PLUGIN_URL . '/' . dirname( plugin_basename( __FILE__ ) ) );
define('EEWEE_SELLSY_PREFIXE_BDD',		$wpdb->prefix.'eewee_sellsy_');
define('PLUGIN_NOM_LANG',              "eewee-sellsy");
define('EEWEE_SELLSY_DEBUG',           false);
for( $i=1 ; $i<=3 ; $i++ ) {
    define( 'EEWEE_SELLSY_URL_SOUS_MENU_'.$i,      admin_url().'admin.php?page=idSousMenu'.$i);
    define( 'EEWEE_SELLSY_URL_BACK_SOUS_MENU_'.$i, admin_url().'admin.php?page=idSousMenu'.$i);
}

load_plugin_textdomain(PLUGIN_NOM_LANG, false, dirname( plugin_basename( __FILE__ ) ) . '/lang');
define('EEWEE_DEADLINE', 30);
define('EEWEE_PROBABILITY', 10);

/**
 * Required CSS / JS
 *
 * @since 1.0
 */
function ajouterScriptsEeweeSellsy(){
	// CSS
    wp_enqueue_style( PLUGIN_NOM_LANG.'-style', '/wp-content/plugins/eewee-sellsy/css/style.css' );

    // JS
    wp_enqueue_script( PLUGIN_NOM_LANG.'-recaptcha-js', 'https://www.google.com/recaptcha/api.js');

    // ONLY PAGE : contact edit
    if (isset($_GET['contact_form_id'])) {
        $contact_form_id = (int)$_GET['contact_form_id'];
        if (isset($contact_form_id) && $contact_form_id) {
            $tbl_data['ajax_url']           =  admin_url( 'admin-ajax.php' );
            $tbl_data['contact_form_id']    =  $contact_form_id;

            // JS
            wp_enqueue_script( PLUGIN_NOM_LANG.'-ajax-script-js', plugins_url('eewee-sellsy/js/main.js'), array('jquery'));
            // in JavaScript, object properties are accessed as ajax_object.ajax_url, ajax_object.we_value
            wp_localize_script( PLUGIN_NOM_LANG.'-ajax-script-js', 'ajax_object', $tbl_data );
        }
    }
    wp_enqueue_script( PLUGIN_NOM_LANG.'-js', plugins_url('eewee-sellsy/js/front.js'), array('jquery'));
}
add_action( 'init', 'ajouterScriptsEeweeSellsy' );

/**
 * Required Files
 *
 * @since 1.0
 */
// LIBS
require_once ( EEWEE_SELLSY_PLUGIN_DIR . '/libs/sellsy/sellsytools.php' );
require_once ( EEWEE_SELLSY_PLUGIN_DIR . '/libs/sellsy/sellsyconnect_curl.php' );

// MODELS
require_once ( EEWEE_SELLSY_PLUGIN_DIR . '/models/TSetting.php' );
require_once ( EEWEE_SELLSY_PLUGIN_DIR . '/models/TTicket.php' );
require_once ( EEWEE_SELLSY_PLUGIN_DIR . '/models/TTicketForm.php' );
require_once ( EEWEE_SELLSY_PLUGIN_DIR . '/models/TContact.php' );
require_once ( EEWEE_SELLSY_PLUGIN_DIR . '/models/TContactForm.php' );
require_once ( EEWEE_SELLSY_PLUGIN_DIR . '/models/TError.php' );
require_once ( EEWEE_SELLSY_PLUGIN_DIR . '/models/TSellsyStaffs.php' );
require_once ( EEWEE_SELLSY_PLUGIN_DIR . '/models/TSellsyOpportunities.php' );
require_once ( EEWEE_SELLSY_PLUGIN_DIR . '/models/TSellsyCustomFields.php' );
require_once ( EEWEE_SELLSY_PLUGIN_DIR . '/models/TSellsyTracking.php' );

// HELPERS
require_once ( EEWEE_SELLSY_PLUGIN_DIR . '/helpers/FormHelpers.php' );
require_once ( EEWEE_SELLSY_PLUGIN_DIR . '/helpers/dbUpdate.php' );

// FORMS
require_once ( EEWEE_SELLSY_PLUGIN_DIR . '/forms/FSettingEdit.php' );
require_once ( EEWEE_SELLSY_PLUGIN_DIR . '/forms/FTicketFormEdit.php' );
require_once ( EEWEE_SELLSY_PLUGIN_DIR . '/forms/FTicketFormAdd.php' );
require_once ( EEWEE_SELLSY_PLUGIN_DIR . '/forms/FContactFormEdit.php' );

// CONTROLLERS
require_once( EEWEE_SELLSY_PLUGIN_DIR . '/controllers/CookieControllers.php' );
require_once( EEWEE_SELLSY_PLUGIN_DIR . '/controllers/AjaxControllers.php' );
require_once( EEWEE_SELLSY_PLUGIN_DIR . '/controllers/InstallControllers.php' );
require_once( EEWEE_SELLSY_PLUGIN_DIR . '/controllers/ToolsControllers.php' );
require_once( EEWEE_SELLSY_PLUGIN_DIR . '/controllers/ShortcodeControllers.php' );
require_once( EEWEE_SELLSY_PLUGIN_DIR . '/controllers/AdminControllers.php' );
require_once( EEWEE_SELLSY_PLUGIN_DIR . '/controllers/SellsyCustomFieldsControllers.php' );

use fr\eewee\eewee_sellsy\controllers;
use fr\eewee\eewee_sellsy\helpers;

$s = new controllers\ShortcodeController();
$a = new controllers\AjaxController();

/**
 * Instantiate Class
 *
 * @since 1.0
 */
$adminController = new controllers\AdminController();

/**
 * Instantiate Class
 *
 * @since 1.0
 */
if (!is_admin()) {
    $cookieController = new controllers\CookieController();
    $cookieController->exec();
}

/**
 * Wordpress Activate/Deactivate
 *
 * @uses register_activation_hook()
 * @uses register_deactivation_hook()
 * @uses register_uninstall_hook()
 *
 * @since 1.0
 */
register_activation_hook( __FILE__, array( $adminController, 'eewee_activate' ) );
register_deactivation_hook( __FILE__, array( $adminController, 'eewee_deactivate' ) );
//register_uninstall_hook( __FILE__, array( $adminController, 'eewee_uninstall' ) );    // use methode 2 with "uninstall.php"

/**
 * Required action filters
 *
 * @uses add_action()
 *
 * @since 1.0
 */
add_action( 'admin_menu', array( $adminController, 'eewee_adminMenu' ) );

/**
 * UPDATE DB
 */
if (is_admin()) {
    $dbUpdate = new helpers\DbUpdate();
    $dbVersion = $dbUpdate->getVersion();
    if (EEWEE_VERSION > $dbVersion && $dbVersion !== false) { $dbUpdate->updateDb($dbVersion); die("coucou"); }
}

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
