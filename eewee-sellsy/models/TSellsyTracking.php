<?php
namespace fr\eewee\eewee_sellsy\models;
use fr\eewee\eewee_sellsy\libs;

if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( !class_exists('TSellsyTracking')){
    class TSellsyTracking extends \WP_Query {
        function __construct() {}

        /**
         * Record tracking
         * @param array $d thirdid, thirdcontactid, datas
         */
        public function record($d=array())
        {
            // INIT
            $q              = array();
            $datas          = $d['datas'];

            // PARAMS
            if (isset($d['thirdid']) && !empty($d['thirdid'])) {
                $q['thirdid'] = (int)$d['thirdid'];
            }
            if (isset($d['thirdcontactid']) && !empty($d['thirdcontactid'])) {
                $q['thirdcontactid'] = (int)$d['thirdcontactid'];
            }
            $q['trackings'] = $datas;

            $request = array(
                'method' => 'Tracking.record',
                'params' => $q
            );

            $response = libs\sellsyconnect_curl::load()->requestApi($request);

            if ($response->error) {
                $t_error = new TError();
                $t_error->add(array(
                    'categ'     => 'tracking',
                    'response'  => $response,
                ));
                return false;
            }
            return $response;
        }

    }
}
