<?php
global $wpdb;

// INSERT
if (isset($_POST['add']) && $_POST['add']) {

    // ERROR
    $errors = array();
    if (empty($_POST['ticket_form_name'])) {
        $errors[] = __('Name', PLUGIN_NOM_LANG);
    }
    if (empty($_POST['ticket_form_subject_prefix'])) {
        $errors[] = __('Subject prefix', PLUGIN_NOM_LANG);
    }

    // INSERT
    if (empty($errors)) {
        $t_ticket_form = new TTicketForm();
        $r = $t_ticket_form->add($_POST);

        $tools = new ToolsControllers();
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
        echo ToolsControllers::error($mess);
    }
}//if
?>

<div class="wrap">
    <div id="icon-options-general" class="icon32"><br></div>
    <h2><?php _e('Support ticket', PLUGIN_NOM_LANG); ?></h2>

    <?php
    $f_ticketFormAdd = new Form_TicketFormAdd();
    $f_ticketFormAdd->ticketFormAdd( $_POST );
    ?>
</div><!-- .wrap -->
