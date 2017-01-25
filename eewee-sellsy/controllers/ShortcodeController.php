<?php
namespace fr\eewee\eewee_sellsy\controllers;
use fr\eewee\eewee_sellsy\models;
use fr\eewee\eewee_sellsy\libs;

if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( !class_exists('ShortcodeController')){
	class ShortcodeController{

		function __construct(){
			// SHORTCODE :
            add_shortcode( 'ticketSellsy', array($this, 'ticketSellsy') );
            add_shortcode( 'contactSellsy', array($this, 'contactSellsy') );
		}

		/**
		 * Ticket Sellsy
		 * @param array $atts
		 */
		public function ticketSellsy( $atts='' )
        {
            // INIT
            $id         = '';
            $render     = '';
            $error      = array();
            $styleError = 'style="border:1px solid red;"'; // :(
            $form_ticket_support_subject    = '';
            $form_ticket_support_email      = '';
            $form_ticket_support_lastname   = '';
            $form_ticket_support_message    = '';
            $class_ticket_support_email     = '';
            $class_ticket_support_name      = '';
            $class_ticket_support_message   = '';
            extract( shortcode_atts(array('id'=>''), $atts ));

            // MODEL
            $t_ticketForm = new models\TTicketForm();
            $ticket = $t_ticketForm->getTicketForm($id);

            // VALIDATE FORM
            if (isset($_POST) && !empty($_POST) && isset($_POST['btn_ticket_support'])) {

                check_admin_referer('form_nonce_shortcode_ticket_add');

                //if (isset($_POST['form_ticket_support_subject'])) {
                    $form_ticket_support_subject    = sanitize_text_field($_POST['form_ticket_support_subject']);
                //}
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

                    // INSERT TO WORDPRESS : table
                    $t_ticket = new models\TTicket();
                    $t_ticket->add(array(
                        'form_ticket_subject'   => $ticket[0]->ticket_form_subject_prefix.' '.$form_ticket_support_subject,
                        'form_ticket_email'     => $form_ticket_support_email,
                        'form_ticket_name'      => $form_ticket_support_lastname,
                        'form_ticket_message'   => "<h2>".__('Consumer', PLUGIN_NOM_LANG)." :</h2>".$form_ticket_support_lastname."<h2>".__('Message', PLUGIN_NOM_LANG)." :</h2>".$form_ticket_support_message,
                        'form_ticket_linkedid'  => $ticket[0]->ticket_form_linkedid
                    ));

                    // INSERT TO SELLSY : support
                    $tbl_ticket = array();
                    $tbl_ticket['subject']          = $ticket[0]->ticket_form_subject_prefix.' '.$form_ticket_support_subject;
                    $tbl_ticket['message']          = "<h2>".__('Consumer', PLUGIN_NOM_LANG)." :</h2>".$form_ticket_support_lastname."<h2>".__('Message', PLUGIN_NOM_LANG)." :</h2>".$form_ticket_support_message;
                    $tbl_ticket['source']           = 'email';
                    $tbl_ticket['sender']           = get_option( 'admin_email' );
                    $tbl_ticket['requesterEmail']   = $form_ticket_support_email;
                    if ($ticket[0]->ticket_form_linkedid != 0) {
                        $tbl_ticket['staffid']      = $ticket[0]->ticket_form_linkedid;
                    }

                    $request = array(
                        'method' => 'Support.create',
                        'params' => array(
                            'ticket' => $tbl_ticket
                        )
                    );
                    $response = libs\sellsyConnect_curl::load()->requestApi($request);

                    // API : success
                    if ($response->status == 'success') {
                        unset($_POST['form_ticket_support_subject']);
                        unset($_POST['form_ticket_support_email']);
                        unset($_POST['form_ticket_support_lastname']);
                        unset($_POST['form_ticket_support_message']);
                        $form_ticket_support_subject    = '';
                        $form_ticket_support_email      = '';
                        $form_ticket_support_lastname   = '';
                        $form_ticket_support_message    = '';
                        echo __('Successful registration.', PLUGIN_NOM_LANG);

                    // API : error
                    } elseif($response->status == 'error') {

                        $tbl_errors = array(
                            'form_error_categ'   => 'ticket',
                            'form_error_status'  => $response->status,
                            'form_error_code'    => $response->error->code,
                            'form_error_message' => $response->error->message,
                            'form_error_more'    => $response->error->more,
                            'form_error_inerro'  => $response->error->inerror,
                        );
                        $t_error	= new models\TError();
                        $t_error->add($tbl_errors);
                        echo __('Error registration.', PLUGIN_NOM_LANG);

                    }

                // ERROR : required field(s)
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
                    '.wp_nonce_field("form_nonce_shortcode_ticket_add").'

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




        /**
         * Contact Sellsy
         * @param array $atts
         */
        public function contactSellsy( $atts='' )  {
            // INIT
            $id         = '';
            $render     = '';
            $error      = array();
            $styleError = 'style="border:1px solid red;"'; // :(
            // form
            $api_third = array(
                'name'      => '',
                'siren'     => '',
                'siret'     => '',
                'rcs'       => '',
                'web'       => '',
                'stickyNote'=> ''
            );
            $api_contact = array(
                'name'      => '',
                'forename'  => '',
                'email'     => '',
                'tel'       => '',
                'mobile'    => '',
                'position'  => ''
            );
            // class
            $tbl_class = '';
            $tbl_class['class_contact_form_contact_name']      = '';
            $tbl_class['class_contact_form_contact_siren']     = '';
            $tbl_class['class_contact_form_contact_siret']     = '';
            $tbl_class['class_contact_form_contact_rcs']       = '';
            $tbl_class['class_contact_form_contact_lastname']  = '';
            $tbl_class['class_contact_form_contact_firstname'] = '';
            $tbl_class['class_contact_form_contact_email']     = '';
            $tbl_class['class_contact_form_contact_phone_1']   = '';
            $tbl_class['class_contact_form_contact_phone_2']   = '';
            $tbl_class['class_contact_form_contact_function']  = '';
            $tbl_class['class_contact_form_website']           = '';
            $tbl_class['class_contact_form_note']              = '';
            extract( shortcode_atts(array('id'=>''), $atts ));

            // MODEL
            $t_contactForm  = new models\TContactForm();
            $contact        = $t_contactForm->getContactForm($id);

            // VALIDATE FORM
            if (isset($_POST) && !empty($_POST) && isset($_POST['btn_contact'])) {

                check_admin_referer('form_nonce_shortcode_contact_add');

                // third
                if (isset($_POST['contact_form_company_name'])) {
                    $api_third['type'] = 'corporation'; // corporation/person
                    $api_third['name'] = sanitize_text_field($_POST['contact_form_company_name']);

                    if (isset($_POST['contact_form_company_siren'])) {
                        $api_third['siren'] = sanitize_text_field($_POST['contact_form_company_siren']);
                    }
                    if (isset($_POST['contact_form_company_siret'])) {
                        $api_third['siret'] = sanitize_text_field($_POST['contact_form_company_siret']);
                    }
                    if (isset($_POST['contact_form_company_rcs'])) {
                        $api_third['rcs'] = sanitize_text_field($_POST['contact_form_company_rcs']);
                    }
                    if (isset($_POST['contact_form_website'])) {
                        $api_third['web'] = esc_url($_POST['contact_form_website']);
                    }

                } else {
                    $api_third['type'] = 'person'; // corporation/person

                    if (isset($_POST['contact_form_contact_lastname'])) {
                        $api_third['name'] = sanitize_text_field($_POST['contact_form_contact_lastname']);
                    }
                }
                if (isset($_POST['contact_form_note'])) {
                    $api_third['stickyNote'] = esc_textarea($_POST['contact_form_note']);
                }
                $api_third['tags'] = 'wordpress';

                // contact
                if (isset($_POST['contact_form_contact_lastname'])) {
                    $api_contact['name'] = sanitize_text_field($_POST['contact_form_contact_lastname']);
                }
                if (isset($_POST['contact_form_contact_firstname'])) {
                    $api_contact['forename'] = sanitize_text_field($_POST['contact_form_contact_firstname']);
                }
                if (isset($_POST['contact_form_contact_email'])) {
                    $api_contact['email'] = sanitize_email($_POST['contact_form_contact_email']);
                }
                if (isset($_POST['contact_form_contact_phone_1'])) {
                    $api_contact['tel'] = sanitize_text_field($_POST['contact_form_contact_phone_1']);
                }
                if (isset($_POST['contact_form_contact_phone_2'])) {
                    $api_contact['mobile'] = sanitize_text_field($_POST['contact_form_contact_phone_2']);
                }
                if (isset($_POST['contact_form_contact_function'])) {
                    $api_contact['position'] = sanitize_text_field($_POST['contact_form_contact_function']);
                }




                // REQUIRED
                if (empty($api_contact['name'])) {
                    $error[] = __('lastname', PLUGIN_NOM_LANG);
                    $tbl_class['class_contact_form_contact_lastname'] = $styleError.' required';
                }
                if (empty($api_contact['email'])) {
                    $error[] = __('email', PLUGIN_NOM_LANG);
                    $tbl_class['class_contact_form_contact_email'] = $styleError.' required';
                }




                // OK
                if (empty($error)) {

                    // INSERT TO WORDPRESS : table
                    $t_contact = new models\TContact();
                    $t_contact->add(array(
                        'contact_dt_create' => current_time('mysql'),
                        'contact_log'       => json_encode($_POST),
                    ));

                    // INSERT TO SELLSY : prospect
                    $request = array(
                        'method' => 'Prospects.create',
                        'params' => array(
                            'third'     => $api_third,
                            'contact'   => $api_contact
                        )
                    );
                    $response = libs\sellsyConnect_curl::load()->requestApi($request);

                    // API : success
                    if ($response->status == 'success') {

                        // NOTIFICATION EMAIL
                        wp_mail(
                            $contact[0]->contact_form_setting_notification_email,
                            __('[PROSPECT] - sellsy', PLUGIN_NOM_LANG),
                            __('Request for a new prospect :', PLUGIN_NOM_LANG).'
https://www.sellsy.fr/?_f=third&thirdid='.$response->response.'&thirdtype=prospect
'.__('Email', PLUGIN_NOM_LANG).' : '.$api_contact['email'].'
'.__('Name', PLUGIN_NOM_LANG).' : '.$api_contact['name']
                        );

                        unset($_POST);
                        $api_third = array(
                            'name'      => '',
                            'siren'     => '',
                            'siret'     => '',
                            'rcs'       => '',
                            'web'       => '',
                            'stickyNote'=> ''
                        );
                        $api_contact = array(
                            'name'      => '',
                            'forename'  => '',
                            'email'     => '',
                            'tel'       => '',
                            'mobile'    => '',
                            'position'  => ''
                        );
                        echo __('Successful registration.', PLUGIN_NOM_LANG);

                    // API : error
                    } elseif($response->status == 'error') {

                        $tbl_errors = array(
                            'form_error_categ'   => 'contact',
                            'form_error_status'  => $response->status,
                            'form_error_code'    => $response->error->code,
                            'form_error_message' => $response->error->message,
                            'form_error_more'    => $response->error->more,
                            'form_error_inerro'  => $response->error->inerror,
                        );
                        $t_error	= new models\TError();
                        $t_error->add($tbl_errors);
                        echo __('Error registration.', PLUGIN_NOM_LANG);

                    }

                // ERROR : required field(s)
                } else {
                    $render .= '
                    <div class="eewee-contact eewee-contact-'.$contact[0]->contact_form_id.'">
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
            if( $contact[0]->contact_form_status == 0 && !empty($id) ) {
                $render .= '
                <form method="post" action="" id="form_contact">
                    '.wp_nonce_field("form_nonce_shortcode_contact_add");

                    // COMPANY
                    if ($contact[0]->contact_form_company_name == 0) {
                        $render .= '
                        <label>'.__('Company name', PLUGIN_NOM_LANG).'</label>
                        <input type="text" name="contact_form_company_name" value="'.$api_third['name'].'" id="contact_form_company_name">';
                    }
                    if ($contact[0]->contact_form_company_siren == 0) {
                        $render .= '
                        <label>'.__('Siren', PLUGIN_NOM_LANG).'</label>
                        <input type="text" name="contact_form_company_siren" value="'.$api_third['siren'].'" id="contact_form_company_siren">';
                    }
                    if ($contact[0]->contact_form_company_siret == 0) {
                        $render .= '
                        <label>'.__('Siret', PLUGIN_NOM_LANG).'</label>
                        <input type="text" name="contact_form_company_siret" value="'.$api_third['siret'].'" id="contact_form_company_siret">';
                    }
                    if ($contact[0]->contact_form_company_rcs == 0) {
                        $render .= '
                        <label>'.__('RCS', PLUGIN_NOM_LANG).'</label>
                        <input type="text" name="contact_form_company_rcs" value="'.$api_third['rcs'].'" id="contact_form_company_rcs">';
                    }

                    // CONTACT
                    if ($contact[0]->contact_form_contact_lastname == 0) {
                        $render .= '
                        <label>'.__('Lastname', PLUGIN_NOM_LANG).' *</label>
                        <input type="text" name="contact_form_contact_lastname" value="'.$api_contact['name'].'" id="contact_form_contact_lastname" '.$tbl_class['class_contact_form_contact_lastname'].'>';
                    }
                    if ($contact[0]->contact_form_contact_firstname == 0) {
                        $render .= '
                        <label>'.__('Firstname', PLUGIN_NOM_LANG).'</label>
                        <input type="text" name="contact_form_contact_firstname" value="'.$api_contact['forename'].'" id="contact_form_contact_firstname">';
                    }
                    if ($contact[0]->contact_form_contact_email == 0) {
                        $render .= '
                        <label>'.__('Email', PLUGIN_NOM_LANG).' *</label>
                        <input type="email" name="contact_form_contact_email" value="'.$api_contact['email'].'" id="contact_form_contact_email" '.$tbl_class['class_contact_form_contact_email'].'>';
                    }
                    if ($contact[0]->contact_form_contact_phone_1 == 0) {
                        $render .= '
                        <label>'.__('Phone', PLUGIN_NOM_LANG).'</label>
                        <input type="text" name="contact_form_contact_phone_1" value="'.$api_contact['tel'].'" id="contact_form_contact_phone_1">';
                    }
                    if ($contact[0]->contact_form_contact_phone_2 == 0) {
                        $render .= '
                        <label>'.__('Mobile', PLUGIN_NOM_LANG).'</label>
                        <input type="text" name="contact_form_contact_phone_2" value="'.$api_contact['mobile'].'" id="contact_form_contact_phone_2">';
                    }
                    if ($contact[0]->contact_form_contact_function == 0) {
                        $render .= '
                        <label>'.__('Function', PLUGIN_NOM_LANG).'</label>
                        <input type="text" name="contact_form_contact_function" value="'.$api_contact['position'].'" id="contact_form_contact_function">';
                    }

                    // OTHER
                    if ($contact[0]->contact_form_website == 0) {
                        $render .= '
                        <label>'.__('website', PLUGIN_NOM_LANG).'</label>
                        <input type="text" name="contact_form_website" value="'.$api_third['web'].'" id="contact_form_website">';
                    }
                    if ($contact[0]->contact_form_note == 0) {
                        $render .= '
                        <label>'.__('Message', PLUGIN_NOM_LANG).'</label>
                        <textarea name="contact_form_note" id="contact_form_note">'.$api_third['stickyNote'].'</textarea>';
                    }

                    $render .= '           
                    <input type="submit" name="btn_contact">
                </form>';
            }
            return $render;
        }


    }//class
}//if
