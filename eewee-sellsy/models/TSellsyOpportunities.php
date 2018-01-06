<?php
namespace fr\eewee\eewee_sellsy\models;
use fr\eewee\eewee_sellsy\libs;

if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( !class_exists('TSellsyOpportunities')){
    class TSellsyOpportunities extends \WP_Query {
        function __construct() {}

        /**
         * Get funnel pipeline
         * retourn rows
         */
        public function getFunnels()
        {
            $request = array(
                'method' => 'Opportunities.getFunnels',
                'params' => array()
            );
            $response = libs\sellsyconnect_curl::load()->requestApi($request);
            if (is_null($response)) { return false; }
            if ($response->error) {
                $t_error	= new TError();
                $t_error->add(array(
                    'categ'     => 'opportunities',
                    'response'  => $response,
                ));
                return false;
            }
            return $response;
        }

        /**
         * Get Steps pipeline
         * @param array $d
         */
        public function getStepsForFunnel($d=array())
        {
            $idPipeline = (int)$d['idPipeline'];
            if (isset($idPipeline) && !empty($idPipeline)) {
                $request = array(
                    'method' => 'Opportunities.getStepsForFunnel',
                    'params' => array(
                        'funnelid' => $idPipeline
                    )
                );
                $response = libs\sellsyconnect_curl::load()->requestApi($request);
                if (is_null($response)) { return false; }
                if ($response->error) {
                    $t_error	= new TError();
                    $t_error->add(array(
                        'categ'     => 'opportunities',
                        'response'  => $response,
                    ));
                    return false;
                }
                return $response;
            }
            return false;
        }

        /**
         * Current opp
         * @param array $d
         */
        public function getCurrentIdent($d=array())
        {
            $request = array(
                'method' => 'Opportunities.getCurrentIdent',
                'params' => array()
            );
            $response = libs\sellsyconnect_curl::load()->requestApi($request);
            if (is_null($response)) { return false; }
            if ($response->error) {
                $t_error	= new TError();
                $t_error->add(array(
                    'categ'     => 'opportunities',
                    'response'  => $response,
                ));
                return false;
            }
            return $response;
        }

        /**
         * Create opportunities
         * @param array $d
         */
        public function create($d=array())
        {
            // INIT
            $linkedid       = (int)$d['linkedid'];
            $sourceid       = (int)$d['sourceid'];
            $name           = (string)$d['name'];
            $funnelid       = (int)$d['funnelid'];
            $stepid         = (int)$d['stepid'];
            $deadline       = (int)$d['deadline'];
	        $probability    = (int)$d['probability'];
            $stickyNote     = (string)$d['stickyNote'];
            $currentIdent   = $this->getCurrentIdent();
	        $staffId        = (int)$d['staffId'];

            $request = array(
                'method' => 'Opportunities.create',
                'params' => array(
                    'opportunity'   => array(
                        'linkedtype'=> 'prospect',                  // @todo : BO custom
                        'linkedid'  => $linkedid,
                        'ident'     => $currentIdent->response,
                        'sourceid'  => $sourceid,
                        'dueDate'   => strtotime('+'.$deadline.' day'),
                        //'creationDate'  => {{creationDate}},
                        'name'      => $name,                       // @todo : BO custom
                        'funnelid'  => $funnelid,
                        'stepid'    => $stepid,
                        'proba'     => $probability,
                        //'brief'     => {{brief}},                 // @todo : BO custom
                        'stickyNote'=> $stickyNote,
                        'tags'      => 'wordpress',
                        'staffs'    => array($staffId),
                        //'contacts'  => {{contacts}}               // @todo : BO custom
                    )
                )
            );
            $response = libs\sellsyconnect_curl::load()->requestApi($request);
            if (is_null($response)) { return false; }
            if ($response->error) {
                $t_error	= new TError();
                $t_error->add(array(
                    'categ'     => 'opportunities',
                    'response'  => $response,
                ));
                return false;
            }
            return $response;
        }

        /**
         * Get sources
         * @param array $d
         */
        public function getSources($d=array())
        {
            $request = array(
                'method' => 'Opportunities.getSources',
                'params' => array()
            );
            $response = libs\sellsyconnect_curl::load()->requestApi($request);
            if (is_null($response)) { return false; }
            if ($response->error) {
                $t_error	= new TError();
                $t_error->add(array(
                    'categ'     => 'opportunities',
                    'response'  => $response,
                ));
                return false;
            }
            return $response;
        }

    }
}
