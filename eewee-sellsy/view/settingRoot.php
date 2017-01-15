<?php
if (isset($_GET['type'])) {
    if ($_GET['type'] == "edit") {
        include(EEWEE_SELLSY_PLUGIN_DIR . '/view/settingEdit.php');
//    } elseif ($_GET['type'] == "delete") {
//        include(EEWEE_SELLSY_PLUGIN_DIR.'/view/settingDelete.php');
//    } elseif ($_GET['type'] == "add") {
//        include(EEWEE_SELLSY_PLUGIN_DIR . '/view/settingAdd.php');
    }
} else {
    include(EEWEE_SELLSY_PLUGIN_DIR.'/view/settingEdit.php');
}
