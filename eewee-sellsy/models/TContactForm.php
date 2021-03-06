<?php
namespace fr\eewee\eewee_sellsy\models;

if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( !class_exists('TContactForm')){
    class TContactForm extends \WP_Query {

        private $_table;

        function __construct()
        {
            $this->_table = EEWEE_SELLSY_PREFIXE_BDD."contact_form";
        }

        /**
         * retourn rows
         */
        public function getContactsForm( $req="", $params="" )
        {
            global $wpdb;
            $sql	= $wpdb->prepare("SELECT * FROM ".$this->_table." ".$req, $params);
            $r	    = $wpdb->get_results($sql);
            return $r;
        }

        /**
         * retourn row
         * @param int $id
         */
        public function getContactForm( $id )
        {
            global $wpdb;
            $sql	= $wpdb->prepare("SELECT * FROM ".$this->_table." WHERE contact_form_id=%d", $id);
            $r		= $wpdb->get_results($sql);
            return $r;
        }

        /**
         * update status
         * @param $_POST $p
         */
        public function updateStatus( $p )
        {
            global $wpdb;
            $r = $wpdb->update(
                $this->_table,
                // SET (valeur)
                array(
                    'contact_form_status' => $p['status']
                ),
                // WHERE (valeur)
                array(
                    'contact_form_id' => $p['contact_form_id']
                ),
                // SET (type)
                array(
                    '%d'
                ),
                // WHERE (type)
                array(
                    '%d'
                )
            );
            return $r;
        }

        /**
         * update
         * @param $_POST $p
         */
        public function update( $p )
        {
            global $wpdb;

            // INIT
            $contact_form_setting_opportunity_source    = (int)$p['contact_form_setting_opportunity_source'];
            $contact_form_setting_opportunity_pipeline  = (int)$p['contact_form_setting_opportunity_pipeline'];
            $contact_form_setting_opportunity_step      = (int)$p['contact_form_setting_opportunity_step'];
            $contact_form_custom_fields_quantity        = 0;
            $contact_form_custom_fields_value_json      = "";

            if (isset($p['contact_form_custom_fields_quantity']) && !empty($p['contact_form_custom_fields_quantity'])) {
                $contact_form_custom_fields_quantity = (int)$p['contact_form_custom_fields_quantity'];
                $contact_form_custom_fields_value = array();
                for ($i=0; $i<$contact_form_custom_fields_quantity; $i++) {
                    if (isset($p['contact_form_custom_fields_value_'.$i])) {
                        $contact_form_custom_fields_value[] = (int)$p['contact_form_custom_fields_value_'.$i];
                    }
                }
                $contact_form_custom_fields_value_json = json_encode($contact_form_custom_fields_value, JSON_FORCE_OBJECT);
            }

            $r = $wpdb->update(
                $this->_table,
                // SET (valeur)
                array(
                    'contact_form_dt_update'                    => current_time('mysql'),

                    'contact_form_setting_name'                 => $p['contact_form_setting_name'],
                    'contact_form_setting_add_what'             => $p['contact_form_setting_add_what'],
                    'contact_form_setting_name_opportunity'     => $p['contact_form_setting_name_opportunity'],
                    'contact_form_setting_opportunity_source'   => $contact_form_setting_opportunity_source,
                    'contact_form_setting_opportunity_pipeline' => $contact_form_setting_opportunity_pipeline,
                    'contact_form_setting_opportunity_step'     => $contact_form_setting_opportunity_step,
                    'contact_form_setting_notification_email'   => $p['contact_form_setting_notification_email'],
                    'contact_form_setting_deadline'             => $p['contact_form_setting_deadline'],
                    'contact_form_setting_linkedid'             => $p['contact_form_setting_linkedid'],
                    'contact_form_setting_probability'          => $p['contact_form_setting_probability'],

                    'contact_form_company_name'                 => $p['contact_form_company_name'],
                    'contact_form_company_siren'                => $p['contact_form_company_siren'],
                    'contact_form_company_siret'                => $p['contact_form_company_siret'],
                    'contact_form_company_rcs'                  => $p['contact_form_company_rcs'],

                    'contact_form_contact_lastname'             => $p['contact_form_contact_lastname'],
                    'contact_form_contact_firstname'            => $p['contact_form_contact_firstname'],
                    'contact_form_contact_email'                => $p['contact_form_contact_email'],
                    'contact_form_contact_phone_1'              => $p['contact_form_contact_phone_1'],
                    'contact_form_contact_phone_2'              => $p['contact_form_contact_phone_2'],
                    'contact_form_contact_function'             => $p['contact_form_contact_function'],

                    'contact_form_website'                      => $p['contact_form_website'],
                    'contact_form_note'                         => $p['contact_form_note'],
                    'contact_form_status'                       => $p['form_status'],

                    'contact_form_custom_fields_quantity'       => $contact_form_custom_fields_quantity,
                    'contact_form_custom_fields_value'          => $contact_form_custom_fields_value_json,
                ),
                // WHERE (valeur)
                array(
                    'contact_form_id'                           => $p['form_id']
                ),
                // SET (type)
                array(
                    '%s',

                    '%s',
                    '%d',
                    '%s',
                    '%d',
                    '%d',
                    '%d',
                    '%s',
	                '%d',
	                '%d',
	                '%d',

	                '%d',
                    '%d',
                    '%d',
                    '%d',

                    '%d',
                    '%d',
                    '%d',
                    '%d',
                    '%d',
                    '%d',

                    '%d',
                    '%d',
                    '%d',

                    '%d',
                    '%s'
                ),
                // WHERE (type)
                array(
                    '%d'
                )
            );
            return $r;
        }

    }
}