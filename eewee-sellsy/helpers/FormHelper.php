<?php
namespace fr\eewee\eewee_sellsy\helpers;

if( !class_exists('FormHelpers')){
    class FormHelpers {

        /**
         * Render form : radio
         * @param $d
         * @return mixed
         */
        public static function radio( $d )
        {
            if (!isset($d['form_name']) || !isset($d['form_value'])) { return false; }

            $echo       = $d['echo'];
            $form_name  = $d['form_name'];
            $on = $off = '';
            if( $d['form_value'] == 0 ){
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
