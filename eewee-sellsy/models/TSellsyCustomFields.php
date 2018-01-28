<?php

namespace fr\eewee\eewee_sellsy\models;

use fr\eewee\eewee_sellsy\libs;

if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if (!class_exists('TSellsyCustomFields')) {
    class TSellsyCustomFields extends \WP_Query
    {
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
                    'search'    => array(
                        'useOn' => array('opportunity', 'prospect')
                    )
                )
            );
            
            $response = libs\sellsyconnect_curl::load()->requestApi($request);
            if (is_null($response)) { return false; }
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

        /**
         * Add CF
         * @param $d $d['linkedtype'], $d['linkedid'], $d['datas']
         */
        public function recordValues($d)
        {
            $request = array(
                'method' => 'CustomFields.recordValues',
                'params' => array(
                    'linkedtype'    => $d['linkedtype'],
                    'linkedid'      => $d['linkedid'],
                    'values'        => $d['datas']
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
         * Return nb required CF field by default.
         * Return array with name required CF field, if $d['cfByName']=true.
         *
         * @param $d
         *
         * @return bool false nothing required CF field|int nb required CF field|array name required CF field
         */
        public function countTotalRequiredField($d)
        {
            $resultsCustomFields    = $d['response'];
            $res                    = "";
            $iRequired              = 0;

            if (isset($resultsCustomFields) && !empty($resultsCustomFields)) {
                foreach ( $resultsCustomFields->response->result as $resultCustomFields ) {
                    if ( $resultCustomFields->status == 'ok' && $resultCustomFields->isRequired == 'Y' ) {
                        if ($d['cfByName'] === true) {
                            $res[] = $resultCustomFields->name;
                        } else {
                            $res = $iRequired++;
                        }
                    }
                }
                return $res;
            }
            return false;
        }

    }
}
