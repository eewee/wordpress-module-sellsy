<?php
namespace fr\eewee\eewee_sellsy\controllers;

use fr\eewee\eewee_sellsy\models;
use fr\eewee\eewee_sellsy\libs;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

class AjaxController
{
    public function __construct()
    {
        $this->hooks();
    }

    /**
     * Hooks
     */
    public function hooks()
    {
        // AJAX : https://codex.wordpress.org/AJAX_in_Plugins
        if (is_admin()) {
            add_action('wp_ajax_eewee_my_frontend_action', array($this, 'eewee_my_frontend_action_callback'));
            add_action('wp_ajax_nopriv_eewee_my_frontend_action', array($this, 'eewee_my_frontend_action_callback'));
            add_action('wp_ajax_eewee_my_backend_action', array($this, 'eewee_my_backend_action_callback'));
        }
    }

    /**
     * AJAX : steps pipeline
     */
    public function eewee_my_backend_action_callback()
    {
        // INIT
        //$contact_form_id = (int)$_POST['contact_form_id'];
        $id_pipeline = (int)$_POST['id_pipeline'];
        $tbl_steps = array();

        // SEARCH : steps pipeline
        $t_opportunities    = new models\TSellsyOpportunities();
        $response           = $t_opportunities->getStepsForFunnel(array(
            'idPipeline' => $id_pipeline
        ));

        // STOCK
        if (isset($response->response) && !empty($response->response)) {
            foreach ($response->response as $v) {
                if ('ok' == $v->status) {
                    $tbl_steps[$v->id] = $v->label;
                }
            }
        }

        // RETURN
        if ($tbl_steps) {
            echo json_encode($tbl_steps);
            wp_die();
        } else {
            echo 'error ajax';
        }
    }
}
