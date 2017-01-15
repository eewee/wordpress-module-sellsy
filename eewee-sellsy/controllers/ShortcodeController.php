<?php
//namespace FrEeweePluginSellsyShortcode;
//if( !class_exists('ShortcodeController')){
	class ShortcodeController{
		
		function __construct(){
			// SHORTCODE : 
			add_shortcode( 'ticketSellsy', array($this, 'ticketSellsy') );
		}//fin constructeur
		
		/**
		 * Ticket Sellsy
		 * @param array $atts
		 */
		public function ticketSellsy( $atts='' ){
            // INIT
            $id         = '';
            $render     = '';
            $error      = array();
            $styleError = 'style="border:1px solid red;"'; // :(
            $class_ticket_support_email     = '';
            $class_ticket_support_name      = '';
            $class_ticket_support_message   = '';
            extract( shortcode_atts(array('id'=>''), $atts ));

            // MODEL
            $t_ticketForm = new TTicketForm();
            $ticket = $t_ticketForm->getTicketForm($id);

            // VALIDATE FORM
            if (isset($_POST) && !empty($_POST)) {
                $form_ticket_support_subject    = sanitize_text_field($_POST['form_ticket_support_subject']);
                $form_ticket_support_email      = sanitize_email($_POST['form_ticket_support_email']);
                $form_ticket_support_lastname   = sanitize_text_field($_POST['form_ticket_support_lastname']);
                $form_ticket_support_message    = sanitize_text_field($_POST['form_ticket_support_message']);

                // REQUIRED
                if (empty($form_ticket_support_email)) {
                    $error[] = __('email', PLUGIN_NOM_LANG);
                    $class_ticket_support_email = $styleError;
                }
                if (empty($form_ticket_support_lastname)) {
                    $error[] = __('name', PLUGIN_NOM_LANG);
                    $class_ticket_support_name = $styleError;
                }
                if (empty($form_ticket_support_message)) {
                    $error[] = __('message', PLUGIN_NOM_LANG);
                    $class_ticket_support_message = $styleError;
                }

                // OK
                if (empty($error)) {

                    // @todo : INSERT TO WORDPRESS TABLE
                    // ...

                    // INSERT TO SELLSY : support
                    $tbl_ticket = array();
                    $tbl_ticket['subject'] = $ticket[0]->ticket_form_subject_prefix.' '.$form_ticket_support_subject;
                    $tbl_ticket['message'] = "<h2>".__('Consumer', PLUGIN_NOM_LANG)." :</h2>".$form_ticket_support_lastname."<h2>".__('Message', PLUGIN_NOM_LANG)." :</h2>".$form_ticket_support_message;
                    $tbl_ticket['sender'] = get_option( 'admin_email' );
                    $tbl_ticket['requesterEmail'] = $form_ticket_support_email;
                    if ($ticket[0]->ticket_form_linkedid != 0) {
                        $tbl_ticket['staffid'] = $ticket[0]->ticket_form_linkedid;
                    }

                    $request = array(
                        'method' => 'Support.create',
                        'params' => array(
                            'ticket' => $tbl_ticket
                        )
                    );
                    $response = sellsyConnect_curl::load()->requestApi($request);
                    
                    // API : success
                    if ($response->status == 'success') {
                        unset($_POST['form_ticket_support_subject']);
                        unset($_POST['form_ticket_support_email']);
                        unset($_POST['form_ticket_support_lastname']);
                        unset($_POST['form_ticket_support_message']);
                        echo __('Successful registration.', PLUGIN_NOM_LANG);

                    // API : error
                    } elseif($response->status == 'error') {

                        $tbl_errors = array(
                            'form_ticket_error_status' => $response->status,
                            'form_ticket_error_code' => $response->error->code,
                            'ticket_error_message' => $response->error->message,
                            'ticket_error_more' => $response->error->more,
                            'ticket_error_inerro' => $response->error->inerror,
                        );
                        $m_eeweeSellsyError	= new TTicketError();
                        $m_eeweeSellsyError->add($tbl_errors);
                        echo __('Error registration.', PLUGIN_NOM_LANG);
                    }

                // ERROR
                } else {
                    $render .= '
                    <div class="ticket_support ticket_support_'.$ticket[0]->ticket_form_id.'">
                        <strong>';
                            if (sizeof($error) == 1) {
                                $render .= __('Required field', PLUGIN_NOM_LANG);
                            } else {
                                $render .= __('Required fields', PLUGIN_NOM_LANG);
                            }
                        $render .= '
                         : </strong>'.implode(', ', $error).'.<hr>
                     </div>';
                }
            }

            // FORM (setting = online)
            if( $ticket[0]->ticket_form_status == 0 && !empty($id) ) {
                $render .= '
                <form method="post" action="" id="form_ticket_support">
                    <label>'.__('Subject', PLUGIN_NOM_LANG).'</label>
                    <input type="text" name="form_ticket_support_subject" value="'.$form_ticket_support_subject.'" id="form_ticket_support_subject">  
                    
                    <label>'.__('Email', PLUGIN_NOM_LANG).' *</label>
                    <input type="email" name="form_ticket_support_email" value="'.$form_ticket_support_email.'" id="form_ticket_support_email" '.$class_ticket_support_email.'>
                    
                    <label>'.__('Name', PLUGIN_NOM_LANG).' *</label>
                    <input type="text" name="form_ticket_support_lastname" value="'.$form_ticket_support_lastname.'" id="form_ticket_support_lastname" '.$class_ticket_support_name.'>  
                    
                    <label>'.__('Message', PLUGIN_NOM_LANG).' *</label>
                    <textarea name="form_ticket_support_message" id="form_ticket_support_message" '.$class_ticket_support_message.'>'.$form_ticket_support_message.'</textarea>  
                           
                    <input type="submit" name="btn_ticket_support">
                </form>';
            }
            return $render;
		}

	}//class
//}//if