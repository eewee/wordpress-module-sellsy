<?php
use fr\eewee\eewee_sellsy\controllers;
use fr\eewee\eewee_sellsy\models;
use fr\eewee\eewee_sellsy\forms;

if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $wpdb;

// INSERT
if (isset($_POST['add']) && $_POST['add']) {

    // ERROR
    $errors = array();
    if (empty($_POST['contact_form_name'])) {
        $errors[] = __('Name', PLUGIN_NOM_LANG);
    }
    if (empty($_POST['contact_form_subject_prefix'])) {
        $errors[] = __('Subject prefix', PLUGIN_NOM_LANG);
    }

    // INSERT
    if (empty($errors)) {
        $t_contact_form = new models\TContactForm();
        $r = $t_contact_form->add($_POST);

        $tools = new controllers\ToolsControllers();
        $display = $tools->verifMaj($r);
        echo $display;

        // DISPLAY ERROR
    } else {
        $mess = '<strong>';
        if (sizeof($errors) == 1) {
            $mess .= __('Required field : ', PLUGIN_NOM_LANG);
        } else {
            $mess .= __('Required fields : ', PLUGIN_NOM_LANG);
        }
        $mess .= '</strong>'.implode(', ', $errors).'.';
        echo controllers\ToolsControllers::error($mess);
    }
}//if
?>

<div class="wrap">
    <div id="icon-options-general" class="icon32"><br></div>
    <h2><?php _e('Contact', PLUGIN_NOM_LANG); ?></h2>

    <?php
    $f_contactFormAdd = new forms\Form_ContactFormAdd();
    $f_contactFormAdd->contactFormAdd( $_POST );
    ?>
</div><!-- .wrap -->
