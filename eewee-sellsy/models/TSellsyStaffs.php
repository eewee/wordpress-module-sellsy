<?php
namespace fr\eewee\eewee_sellsy\models;
use fr\eewee\eewee_sellsy\libs;

if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( !class_exists('TSellsyStaffs')){
    class TSellsyStaffs extends \WP_Query {
        function __construct() {}
    
        /**
         * Staffs : Get list
         * retourn rows array('linkedid' => 'forename name', ...)
         */
        public function getStaffsList()
        {
            // INIT
            $d = array();

            // GET LIST
            $request = array(
                'method' => 'Staffs.getList',
                'params' => array()
            );
            $response = libs\sellsyConnect_curl::load()->requestApi($request);
            foreach ($response->response->result as $resultStaff) {
                $d[$resultStaff->linkedid] = ucfirst(strtolower($resultStaff->forename)) . ' ' . strtoupper($resultStaff->name);
            }
            if (empty($d)) { return false; }
            return $d;
        }

    }
}
