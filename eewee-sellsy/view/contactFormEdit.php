<?php
use \fr\eewee\eewee_sellsy\controllers;
use \fr\eewee\eewee_sellsy\models;
use \fr\eewee\eewee_sellsy\forms;

global $wpdb;

// UPDATE
if (isset($_POST['update']) && $_POST['update']) {

    // ERROR
    $errors         = array();
    $errors_mess    = array();
    // setting
    if (empty($_POST['contact_form_setting_name'])) { $errors['setting'][] = __('Name', PLUGIN_NOM_LANG); }
    if (empty($_POST['contact_form_setting_notification_email'])) { $errors['setting'][] = __('Notification', PLUGIN_NOM_LANG); }
    // company information
//    if (empty($_POST['contact_form_company_name'])) { $errors['company'][] = __('Name', PLUGIN_NOM_LANG); }
//    if (empty($_POST['contact_form_company_siren'])) { $errors['company'][] = __('Siren', PLUGIN_NOM_LANG); }
//    if (empty($_POST['contact_form_company_siret'])) { $errors['company'][] = __('Siret', PLUGIN_NOM_LANG); }
//    if (empty($_POST['contact_form_company_rcs'])) { $errors['company'][] = __('RCS', PLUGIN_NOM_LANG); }
    // contact information
//    if (empty($_POST['contact_form_contact_lastname'])) { $errors['contact'][] = __('Lastname', PLUGIN_NOM_LANG); }
//    if (empty($_POST['contact_form_contact_firstname'])) { $errors['contact'][] = __('Firstname', PLUGIN_NOM_LANG); }
//    if (empty($_POST['contact_form_contact_email'])) { $errors['contact'][] = __('Email', PLUGIN_NOM_LANG); }
//    if (empty($_POST['contact_form_contact_phone_1'])) { $errors['contact'][] = __('Phone', PLUGIN_NOM_LANG); }
//    if (empty($_POST['contact_form_contact_phone_2'])) { $errors['contact'][] = __('Mobile', PLUGIN_NOM_LANG); }
//    if (empty($_POST['contact_form_contact_function'])) { $errors['contact'][] = __('Function', PLUGIN_NOM_LANG); }
    // other
//    if (empty($_POST['contact_form_website'])) { $errors['other'][] = __('Website', PLUGIN_NOM_LANG); }
//    if (empty($_POST['contact_form_note'])) { $errors['other'][] = __('Note', PLUGIN_NOM_LANG); }

    // INSERT
    if (empty($errors)) {
        $t_contact_form = new models\TContactForm();
        $r = $t_contact_form->update($_POST);

        $tools = new controllers\ToolsControllers();
        $display = $tools->verifMaj($r);
        echo $display;

    // DISPLAY ERROR
    } else {
        // setting
        if (isset($errors['setting'])) {
            if (sizeof($errors['setting']) == 1) {
                $mess[] = '<strong>'.__('Required field for "setting" section : ', PLUGIN_NOM_LANG).'</strong>'.implode(', ', $errors['setting']).'.';
            } else {
                $mess[] = '<strong>'.__('Required fields for "setting" section : ', PLUGIN_NOM_LANG).'</strong>'.implode(', ', $errors['setting']).'.';
            }
        }

        // company information
        if (isset($errors['company'])) {
            if (sizeof($errors['company']) == 1) {
                $mess[] = '<strong>'.__('Required field for "company information" section : ', PLUGIN_NOM_LANG).'</strong>'.implode(', ', $errors['company']).'.';
            } else {
                $mess[] = '<strong>'.__('Required fields for "company information" section : ', PLUGIN_NOM_LANG).'</strong>'.implode(', ', $errors['company']).'.';
            }
        }

        // contact information
        if (isset($errors['contact'])) {
            if (sizeof($errors['contact']) == 1) {
                $mess[] = '<strong>'.__('Required field for "contact information" section : ', PLUGIN_NOM_LANG).'</strong>'.implode(', ', $errors['contact']).'.';
            } else {
                $mess[] = '<strong>'.__('Required fields for "contact information" section : ', PLUGIN_NOM_LANG).'</strong>'.implode(', ', $errors['contact']).'.';
            }
        }

        // other
        if (isset($errors['other'])) {
            if (sizeof($errors['other']) == 1) {
                $mess[] = '<strong>'.__('Required field for "other" section : ', PLUGIN_NOM_LANG).'</strong>'.implode(', ', $errors['other']).'.';
            } else {
                $mess[] = '<strong>'.__('Required fields for "other" section : ', PLUGIN_NOM_LANG).'</strong>'.implode(', ', $errors['other']).'.';
            }
        }
        
        $errors_render = implode('<br>', $mess);
        echo controllers\ToolsControllers::error($errors_render);
    }

}//if

// GET : contact_form
$t_contact_form = new models\TContactForm();
$r = $t_contact_form->getContactForm($_GET['contact_form_id']);
?>

<div class="wrap">
    <div id="icon-options-general" class="icon32"><br></div>
    <h2><?php _e('Contact', PLUGIN_NOM_LANG); ?></h2>

    <?php
    $f_contactFormEdit = new forms\Form_ContactFormEdit();
    $f_contactFormEdit->contactFormEdit( $r );
    ?>
</div><!-- .wrap -->