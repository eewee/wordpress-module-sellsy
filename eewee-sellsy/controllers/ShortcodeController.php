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
            $messageForm = '';
            $form_ticket_support_subject    = '';
            $form_ticket_support_email      = '';
            $form_ticket_support_lastname   = '';
            $form_ticket_support_message    = '';
            $class_ticket_support_email     = '';
            $class_ticket_support_name      = '';
            $class_ticket_support_message   = '';
            extract( shortcode_atts(array('id'=>''), $atts ));
            $class_ticket_support_recaptcha                    = '';

            // MODEL
            $t_ticketForm   = new models\TTicketForm();
            $ticket         = $t_ticketForm->getTicketForm($id);
            $t_setting      = new models\TSetting();
            $setting        = $t_setting->getSetting(1);

            // reCaptcha
            if (isset($_POST['g-recaptcha-response']) && $_POST['g-recaptcha-response'] != NULL) {
                $reCaptchaOkOrNot       = false;
                $reCaptcha['secret']    = $setting[0]->setting_recaptcha_key_secret;
                $reCaptcha['response']  = $_POST['g-recaptcha-response'];
                $reCaptcha['remoteip']  = $_SERVER['REMOTE_ADDR'];

                $api_url = "https://www.google.com/recaptcha/api/siteverify?secret=".$reCaptcha['secret']."&response=".$reCaptcha['response']."&remoteip=".$reCaptcha['remoteip'];
                $decode = json_decode(file_get_contents($api_url), true);
            }

            // ok
            if ($decode['success'] == true) {
                $reCaptchaOkOrNot = true;

            // nok (robot or incorrect code) - https://developers.google.com/recaptcha/docs/verify
            } else {
                $reCaptchaOkOrNot = false;
            }

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
                if ($reCaptchaOkOrNot === false && $setting[0]->setting_recaptcha_key_status == 0) {
                    $error[] = __('reCAPTCHA', PLUGIN_NOM_LANG);
                    $class_ticket_support_recaptcha = $styleError;
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
                        $messageForm = '<div class="eewee-success-message">'.__('Thank you for your message, it has been sent.', PLUGIN_NOM_LANG).'</div>';

                    // API : error
                    } elseif($response->status == 'error') {

                        $t_error	= new models\TError();
                        $t_error->add(array(
                            'categ'     => 'ticket',
                            'response'  => $response,
                        ));
                        $messageForm = '<div class="eewee-success-message">'.__('Error registration.', PLUGIN_NOM_LANG).'</div>';

                    }

                // ERROR : required field(s)
                } else {
                    $render .= '
                    <div class="eewee-error-message ticket_support ticket_support_'.$ticket[0]->ticket_form_id.'">
                        <strong>';
                            if (sizeof($error) == 1) {
                                $render .= __('A field contains an error.', PLUGIN_NOM_LANG).'<br>';
                                $render .= __('Please check and try again', PLUGIN_NOM_LANG);
                            } else {
                                $render .= __('Several fields contain an error', PLUGIN_NOM_LANG).'<br>';
                                $render .= __('Please check and try again', PLUGIN_NOM_LANG);
                            }
                        $render .= '
                         : </strong><br>'.implode(', ', $error).'.
                     </div>';
                }
            }

            // FORM (setting = online)
            if( $ticket[0]->ticket_form_status == 0 && !empty($id) ) {

                if (!empty($messageForm)) {
                    $render .= $messageForm;
                }

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
                    <textarea name="form_ticket_support_message" id="form_ticket_support_message" '.$class_ticket_support_message.'>'.$form_ticket_support_message.'</textarea>';

                    // reCaptcha : cle du site
                    if (
                        $setting[0]->setting_recaptcha_key_status == 0      &&
                        !empty($setting[0]->setting_recaptcha_key_website)  &&
                        !empty($setting[0]->setting_recaptcha_key_secret)
                    ) {
                        $render .= '
                        <div class="g-recaptcha" data-sitekey="'.$setting[0]->setting_recaptcha_key_website.'" '.$class_ticket_support_recaptcha.'></div>';
                    }

                    $render .= '
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
            $messageForm = '';
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
                'position'  => '',
                'stickyNote'=> ''
            );
            $api_opportunity = array(
                'stickyNote' => ''
            );
            // reCaptcha
            $decode['success'] = false;
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
            $class_ticket_support_recaptcha                    = '';

            // MODEL
            $t_contactForm  = new models\TContactForm();
            $contact        = $t_contactForm->getContactForm($id);
            $t_setting      = new models\TSetting();
            $setting        = $t_setting->getSetting(1);

            // reCaptcha
            if (isset($_POST['g-recaptcha-response']) && $_POST['g-recaptcha-response'] != NULL) {
                $reCaptchaOkOrNot       = false;
                $reCaptcha['secret']    = $setting[0]->setting_recaptcha_key_secret;
                $reCaptcha['response']  = $_POST['g-recaptcha-response'];
                $reCaptcha['remoteip']  = $_SERVER['REMOTE_ADDR'];

                $api_url = "https://www.google.com/recaptcha/api/siteverify?secret=".$reCaptcha['secret']."&response=".$reCaptcha['response']."&remoteip=".$reCaptcha['remoteip'];
                $decode = json_decode(file_get_contents($api_url), true);
            }

            // ok
            if ($decode['success'] == true) {
                $reCaptchaOkOrNot = true;

                // nok (robot or incorrect code) - https://developers.google.com/recaptcha/docs/verify
            } else {
                $reCaptchaOkOrNot = false;
            }

            // VALIDATE FORM
            if (
                isset($_POST) &&
                !empty($_POST) &&
                isset($_POST['btn_contact']) &&
                ($contact[0]->contact_form_status == 0 || $contact[0]->contact_form_status == 1) &&
                !empty($id)
            ) {
                check_admin_referer('form_nonce_shortcode_contact_add');

                // third
                if (isset($_POST['contact_form_company_name']) && !empty($_POST['contact_form_company_name'])) {
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

                    // Message on third + setting "prospect"
                    if (isset($_POST['contact_form_note']) && $contact[0]->contact_form_setting_add_what == 0) {
                        $api_third['stickyNote'] = esc_textarea($_POST['contact_form_note']);
                    }

                } else {
                    $api_third['type'] = 'person'; // corporation/person
                    
                    if (isset($_POST['contact_form_contact_lastname'])) {
                        $api_third['name'] = sanitize_text_field($_POST['contact_form_contact_lastname']);
                    }

                    // Message on contact + setting "prospect"
                    if (isset($_POST['contact_form_note']) && $contact[0]->contact_form_setting_add_what == 0) {
                        $api_contact['stickyNote'] = esc_textarea($_POST['contact_form_note']);
                    }
                }

                // Message on opportunity + setting "prospect & opportunity"
                if (isset($_POST['contact_form_note']) && $contact[0]->contact_form_setting_add_what == 1) {
                    $api_opportunity['stickyNote'] = esc_textarea($_POST['contact_form_note']);
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
                if ($reCaptchaOkOrNot === false && $setting[0]->setting_recaptcha_key_status == 0) {
                    $error[] = __('reCAPTCHA', PLUGIN_NOM_LANG);
                    $class_ticket_support_recaptcha = $styleError;
                }





                // OK
                if (empty($error)) {

                    // INIT
                    $tbl_contact = array();

                    // @todo : create is good, update is better (if exist prospect)

                    // INSERT TO SELLSY : prospect
                    $request = array(
                        'method' => 'Prospects.create',
                        'params' => array(
                            'third'     => $api_third,
                            'contact'   => $api_contact
                        )
                    );
                    $response = libs\sellsyConnect_curl::load()->requestApi($request);
                    $linkedid = $response->response;
                    if (isset($linkedid) && !empty($linkedid)) { $tbl_contact['linkedid'] = $linkedid; }
                    
                    // INSERT TO WORDPRESS : table
                    $t_contact = new models\TContact();
                    $tbl_contact['contact_dt_create']   = current_time('mysql');
                    $tbl_contact['contact_log']         = json_encode($_POST);
                    $t_contact->add($tbl_contact);

                    // API : success
                    if ($response->status == 'success') {

                        // NOTIFICATION EMAIL
                        if (isset($contact[0]->contact_form_setting_notification_email)) {
                            wp_mail(
                                $contact[0]->contact_form_setting_notification_email,
                                __('[PROSPECT] - sellsy', PLUGIN_NOM_LANG),
                                __('Request for a new prospect :', PLUGIN_NOM_LANG).'
https://www.sellsy.fr/?_f=third&thirdid='.$response->response.'&thirdtype=prospect
'.__('Email', PLUGIN_NOM_LANG).' : '.$api_contact['email'].'
'.__('Name', PLUGIN_NOM_LANG).' : '.$api_contact['name']
                            );
                        }




                        // OPTION SELECTED : prospect and opportunity
                        if ($contact[0]->contact_form_setting_add_what == 1 && isset($tbl_contact['linkedid'])) {
                            // CREATE OPPORTUNITY
                            $t_sellsyOpportunities = new models\TSellsyOpportunities();
                            $responseOpp = $t_sellsyOpportunities->create(array(
                                'linkedid'  => $tbl_contact['linkedid'],
                                'sourceid'  => $contact[0]->contact_form_setting_opportunity_source,
                                'name'      => $contact[0]->contact_form_setting_name_opportunity,
                                'funnelid'  => $contact[0]->contact_form_setting_opportunity_pipeline,
                                'stepid'    => $contact[0]->contact_form_setting_opportunity_step,
                                'stickyNote'=> $api_opportunity['stickyNote']
                            ));
                            // API : success
                            if ($responseOpp->status == 'success') {

                                // ...

                            // API : error
                            } elseif($responseOpp->status == 'error') {

                                $t_error	= new models\TError();
                                $t_error->add(array(
                                    'categ'     => 'opportunities',
                                    'response'  => $responseOpp,
                                ));
                                echo __('Error registration.', PLUGIN_NOM_LANG);

                            }
                        }

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
                        $messageForm = '<div class="eewee-success-message">'.__('Thank you for your message, it has been sent.', PLUGIN_NOM_LANG).'</div>';

                    // API : error
                    } elseif($response->status == 'error') {

                        $t_error	= new models\TError();
                        $t_error->add(array(
                            'categ'     => 'contact',
                            'response'  => $response,
                        ));
                        $messageForm = '<div class="eewee-error-message">'.__('Error registration.', PLUGIN_NOM_LANG).'</div>';

                    }

                // ERROR : required field(s)
                } else {
                    $render .= '
                    <div class="eewee-error-message eewee-contact eewee-contact-'.$contact[0]->contact_form_id.'">
                        <strong>';
                    if (sizeof($error) == 1) {
                        $render .= __('A field contains an error.', PLUGIN_NOM_LANG).'<br>';
                        $render .= __('Please check and try again', PLUGIN_NOM_LANG);
                    } else {
                        $render .= __('Several fields contain an error', PLUGIN_NOM_LANG).'<br>';
                        $render .= __('Please check and try again', PLUGIN_NOM_LANG);
                    }
                    $render .= '
                         : </strong><br>'.implode(', ', $error).'.
                     </div>';
                }
            }

            // FORM (setting = online)
            if( $contact[0]->contact_form_status == 0 && !empty($id) ) {

                if (!empty($messageForm)) {
                    $render .= $messageForm;
                }

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

                    // reCaptcha : cle du site
                    if (
                        $setting[0]->setting_recaptcha_key_status == 0      &&
                        !empty($setting[0]->setting_recaptcha_key_website)  &&
                        !empty($setting[0]->setting_recaptcha_key_secret)
                    ) {
                        $render .= '
                            <div class="g-recaptcha" data-sitekey="'.$setting[0]->setting_recaptcha_key_website.'" '.$class_ticket_support_recaptcha.'></div>';
                    }

                    $render .= '           
                    <input type="submit" name="btn_contact">
                </form>';

            }
            return $render;
        }


    }//class
}//if
