<?php
namespace fr\eewee\eewee_sellsy\models;

if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

//if( !class_exists('TSetting')){
    class TSetting extends \WP_Query {

        private $_table;

        function __construct()
        {
            $this->_table = EEWEE_SELLSY_PREFIXE_BDD."setting";
        }

        /**
         * retourn rows
         */
        public function getSettings( $req="", $params="" )
        {
            global $wpdb;
            $sql    = $wpdb->prepare("SELECT * FROM ".$this->_table." ".$req, $params);
            $r      = $wpdb->get_results($sql);
            return $r;
        }

        /**
         * retourn row
         * @param int $id
         */
        public function getSetting( $id )
        {
            global $wpdb;
            $sql    = $wpdb->prepare("SELECT * FROM ".$this->_table." WHERE setting_id=%d", $id);
            $r      = $wpdb->get_results($sql);
            return $r;
        }

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
                    'setting_dt_create'         => current_time('mysql'),
                    'setting_dt_update'         => current_time('mysql'),
                    'setting_consumer_token'    => $p['form_consumer_token'],
                    'setting_consumer_secret'   => $p['form_consumer_secret'],
                    'setting_utilisateur_token' => $p['form_utilisateur_token'],
                    'setting_utilisateur_secret'=> $p['form_utilisateur_secret'],
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

        /**
         * update
         * @param $_POST $p
         */
        public function update( $p )
        {
            global $wpdb;

            $r = $wpdb->update(
                $this->_table,
                // SET (valeur)
                array(
                    'setting_dt_update'         => current_time('mysql'),
                    'setting_consumer_token'    => $p['form_consumer_token'],
                    'setting_consumer_secret'   => $p['form_consumer_secret'],
                    'setting_utilisateur_token' => $p['form_utilisateur_token'],
                    'setting_utilisateur_secret'=> $p['form_utilisateur_secret'],
                ),
                // WHERE (valeur)
                array(
                    'setting_id' => $p['form_id']
                ),
                // SET (type)
                array(
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%s',
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
//}
