<?php
namespace fr\eewee\eewee_sellsy\models;

if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( !class_exists('TError')){
    class TError extends \WP_Query {

        private $_table;

        function __construct()
        {
            $this->_table = EEWEE_SELLSY_PREFIXE_BDD."error";
        }

        /**
         * retourn rows
         */
        /*
        public function getErrors( $req="", $params="" )
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
        /*  public function getError( $id )
        {
            global $wpdb;
            $sql    = $wpdb->prepare("SELECT * FROM ".$this->_table." WHERE error_id=%d", $id);
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
                    'error_dt_create'    => current_time('mysql'),
                    'error_categ'        => $p['form_error_categ'],
                    'error_status'       => $p['form_error_status'],
                    'error_code'         => $p['form_error_code'],
                    'error_message'      => $p['form_error_message'],
                    'error_more'         => $p['form_error_more'],
                    'error_inerro'       => $p['form_error_inerro']
                ),
                array(
                    '%s',
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