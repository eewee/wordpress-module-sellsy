<?php
if (isset($_GET['type'])) {
    if ($_GET['type'] == "edit") {
        include(EEWEE_SELLSY_PLUGIN_DIR . '/view/ticketFormEdit.php');
    } elseif ($_GET['type'] == "delete") {
        include(EEWEE_SELLSY_PLUGIN_DIR.'/view/ticketFormDelete.php');
    } elseif ($_GET['type'] == "add") {
        include(EEWEE_SELLSY_PLUGIN_DIR . '/view/ticketFormAdd.php');
    } else {
        include(EEWEE_SELLSY_PLUGIN_DIR.'/view/ticketForm.php');
    }
} else {
    include(EEWEE_SELLSY_PLUGIN_DIR.'/view/ticketForm.php');
}
