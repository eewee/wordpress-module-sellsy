<?php
namespace fr\eewee\eewee_sellsy\models;
use fr\eewee\eewee_sellsy\libs;

if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( !class_exists('TSellsyCustomFields')){
    class TSellsyCustomFields extends \WP_Query {
        function __construct() {}

        /**
         * Get custom fields
         * retourn rows
         */
        public function getCustomFields()
        {
            $request = array(
                'method' => 'CustomFields.getList',
                'params' => array(
                    'order'     => array(
                        'direction'     => 'cf_type',
                        //'order'     => 'ASC',
                    ),
                    'pagination'    => array(
                        'nbperpage' => 5000,
                        //'pagenum'   => '',
                    ),
                    //'search'    => array(
                    //    'useOn_prospect'     => 'Y',
                    //    'useOn_opportunity'  => 'Y',
                    //)
                )
            );
            
            $response = libs\sellsyconnect_curl::load()->requestApi($request);
            if ($response->error) {
                $t_error = new TError();
                $t_error->add(array(
                    'categ'     => 'customfields',
                    'response'  => $response,
                ));
                return false;
            }
            return $response;
        }

        /**
         * Get one
         * @param $d, $d['id']
         */
        public function getOne($d)
        {
            $id = (int)$d['id'];

            $request = array(
                'method' => 'CustomFields.getOne',
                'params' => array(
                    'id' => $id,
                )
            );

            $response = libs\sellsyconnect_curl::load()->requestApi($request);
            if ($response->error) {
                $t_error = new TError();
                $t_error->add(array(
                    'categ'     => 'customfields',
                    'response'  => $response,
                ));
                return false;
            }
            return $response;
        }

    }
}
