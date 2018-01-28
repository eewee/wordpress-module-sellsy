<?php
namespace fr\eewee\eewee_sellsy\models;

namespace fr\eewee\eewee_sellsy\helpers;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('FormHelpers')) {
    class FormHelpers
    {
        /**
         * BACK (ADMIN)
         * Render form : custom fields
         * @param $d
         * @return mixed
         */
        public static function getCustomFields($d)
        {
            if (!isset($d['form_name']) /*|| !isset($d['form_value'])*/ || !isset($d['responseCustomFields'])) {
                return false;
            }

            // INIT
            $options                = array();
            $disabled               = 'disabled';
            $selected               = '';
            $isRequired             = '';
            $echo                   = $d['echo'];
            $form_name              = $d['form_name'];
            $form_value             = $d['form_value'];
            $resultsCustomFields    = $d['responseCustomFields']->response->result;

            // CF ALL
            if (isset($resultsCustomFields) && !empty($resultsCustomFields)) {
                foreach ($resultsCustomFields as $resultCustomFields) {
                    if ($resultCustomFields->status == 'ok') {

                        // only "simpletext" or "richtext" for the moment
                        if ($resultCustomFields->type == 'simpletext' || $resultCustomFields->type == 'richtext') {
                            $disabled = '';
                        } else {
                            $disabled = 'disabled';
                        }

                        // required
                        if ($resultCustomFields->isRequired == 'Y') {
                            $isRequired = '*';
                        } else {
                            $isRequired = '';
                        }

                        // selected
                        if ($form_value == $resultCustomFields->cfid) {
                            $selected = 'selected';
                        } else {
                            $selected = '';
                        }

                        $options[] = '<option value="'.$resultCustomFields->cfid.'" '.$disabled.' '.$selected .'>'.$resultCustomFields->name.' ('.$resultCustomFields->type.') '.$isRequired.'</option>';
                    }
                }
            }

            // SELECT
            $r = '
            <select name="'.$form_name.'">
                '.implode("", $options).'
            </select>
            <br>';

            // RENDER
            if ($echo) {
                echo $r;
            } else {
                return $r;
            }
        }

        /**
         * FRONT
         * Render form : custom fields
         * @param $id (cfid)
         * @return mixed
         */
        public static function getCustomFieldsFront($id)
        {
        }

        /**
         * Render form : radio
         * @param $d
         * @return mixed
         */
        public static function radio($d)
        {
            if (!isset($d['form_name']) || !isset($d['form_value'])) {
                return false;
            }

            $echo       = $d['echo'];
            $form_name  = $d['form_name'];
            $on = $off = '';
            if ($d['form_value'] == 0) {
                $on = 'checked="checked"';
            } else {
                $off = 'checked="checked"';
            }

            $r = '
            <input type="radio" id="'.$form_name.'_on" name="'.$form_name.'" value="0" '.$on.' />
            <label for="'.$form_name.'_on">
                <img src="'.EEWEE_SELLSY_PLUGIN_URL.'/images/icones/enabled.gif" />
            </label>
            
            <input type="radio" id="'.$form_name.'_off" name="'.$form_name.'" value="1" '.$off.' />
            <label for="'.$form_name.'_off">
                <img src="'.EEWEE_SELLSY_PLUGIN_URL.'/images/icones/disabled.gif" />
            </label>';

            if ($echo) {
                echo $r;
            } else {
                return $r;
            }
        }
    }//fin class
}//fin if
