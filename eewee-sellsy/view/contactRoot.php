<?php
if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if (isset($_GET['type'])) {
    if ($_GET['type'] == "edit") {
        include(EEWEE_SELLSY_PLUGIN_DIR . '/view/contactFormEdit.php');
    } elseif ($_GET['type'] == "delete") {
        include(EEWEE_SELLSY_PLUGIN_DIR.'/view/contactFormDelete.php');
    } elseif ($_GET['type'] == "add") {
        include(EEWEE_SELLSY_PLUGIN_DIR . '/view/contactFormAdd.php');
    } else {
        include(EEWEE_SELLSY_PLUGIN_DIR.'/view/contactForm.php');
    }
} else {
    include(EEWEE_SELLSY_PLUGIN_DIR.'/view/contactForm.php');
}
