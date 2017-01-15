<?php
global $wpdb;

// UPDATE
if( $_POST['update'] ){

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
        $r = $t_ticket_form->update($_POST);

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

// GET : ticket_form
$t_ticket_form = new TTicketForm();
$r = $t_ticket_form->getTicketForm($_GET['ticket_form_id']);
?>

<div class="wrap">
    <div id="icon-options-general" class="icon32"><br></div>
    <h2><?php _e('Support ticket', PLUGIN_NOM_LANG); ?></h2>

    <?php 
    $f_ticketFormEdit = new Form_TicketFormEdit();
    $f_ticketFormEdit->ticketFormEdit( $r );
    ?>
</div><!-- .wrap -->
 