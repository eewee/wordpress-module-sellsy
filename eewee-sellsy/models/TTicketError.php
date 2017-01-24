<?php
namespace fr\eewee\eewee_sellsy\models;

if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( !class_exists('TTicketError')){
    class TTicketError extends \WP_Query {

        private $_table;

        function __construct()
        {
            $this->_table = EEWEE_SELLSY_PREFIXE_BDD."ticket_error";
        }

        /**
         * retourn rows
         */
        /*
        public function getTicketErrors( $req="", $params="" )
        {
            global $wpdb;
            $sql    = $wpdb->prepare("SELECT * FROM ".$this->_table." ".$req, $params);
            $r      = $wpdb->get_results($sql);
            return $r;
        }
        */

        /**
         * retourn row
         * @param int $id
         */
        /*
        public function getTicketError( $id )
        {
            global $wpdb;
            $sql    = $wpdb->prepare("SELECT * FROM ".$this->_table." WHERE ticket_error_id=%d", $id);
            $r      = $wpdb->get_results($sql);
            return $r;
        }
        */

        /**
         * insert
         * @param $_POST $p
         */
        public function add( $p )
        {
            global $wpdb;
            $r = $wpdb->insert(
                $this->_table,
                array(
                    'ticket_error_dt_create'    => current_time('mysql'),
                    'ticket_error_status'       => $p['form_ticket_error_status'],
                    'ticket_error_code'         => $p['form_ticket_error_code'],
                    'ticket_error_message'      => $p['form_ticket_error_message'],
                    'ticket_error_more'         => $p['form_ticket_error_more'],
                    'ticket_error_inerro'       => $p['form_ticket_error_inerro']
                ),
                array(
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s'
                )
            );
            return $r;
        }

    }
}