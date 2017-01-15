<?php
if( !class_exists('TTicketForm')){
    class TTicketForm extends WP_Query{

        private $_table;

        function __construct()
        {
            $this->_table = EEWEE_SELLSY_PREFIXE_BDD."ticket_form";
        }

        /**
         * retourn rows
         */
        public function getTicketsForm( $req="", $params="" )
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
        public function getTicketForm( $id )
        {
            global $wpdb;
            $sql	= $wpdb->prepare("SELECT * FROM ".$this->_table." WHERE ticket_form_id=%d", $id);
            $r		= $wpdb->get_results($sql);
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
                    'ticket_form_dt_create'         => current_time('mysql'),
                    'ticket_form_dt_update'         => current_time('mysql'),
                    'ticket_form_name'              => $p['ticket_form_name'],
                    'ticket_form_subject_prefix'    => $p['ticket_form_subject_prefix'],
                    'ticket_form_linkedid'          => $p['ticket_form_linkedid'],
                    'ticket_form_status'            => $p['form_status']
                ),
                array(
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%d',
                    '%d'
                )
            );
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
                    'ticket_form_status' => $p['status']
                ),
                // WHERE (valeur)
                array(
                    'ticket_form_id' => $p['ticket_form_id']
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

            $r = $wpdb->update(
                $this->_table,
                // SET (valeur)
                array(
                    'ticket_form_dt_update'         => current_time('mysql'),
                    'ticket_form_name'              => $p['ticket_form_name'],
                    'ticket_form_subject_prefix'    => $p['ticket_form_subject_prefix'],
                    'ticket_form_linkedid'          => $p['ticket_form_linkedid'],
                    'ticket_form_status'            => $p['form_status']
                ),
                // WHERE (valeur)
                array(
                    'ticket_form_id' => $p['form_id']
                ),
                // SET (type)
                array(
                    '%s',
                    '%s',
                    '%s',
                    '%d',
                    '%d'
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