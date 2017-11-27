<?php
namespace fr\eewee\eewee_sellsy\controllers;

if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( !class_exists('SellsyCustomFieldsController')){
    class SellsyCustomFieldsController{

        function __construct() {}

        /**
         * Generator input, select, radio, checkbox, ...
         * @param $d, $d->type
         * @return bool, field
         */
        public function getGenerator($d)
        {
            $type = $d->type;
            
            // Dispatcher
            switch ($type) {
                case 'simpletext' :
                    $res = $this->getTypeSimpleText($d);
                    break;
                    
                default :
                    return false;
            }

            return $res;
        }

        /**
         * simpletext
         * @param $d
         */
        public function getTypeSimpleText($d)
        {
            // init
            $isRequiredLabel = '';
            $isRequiredField = '';
            $class           = '';

            // isRequired
            if ($d->preferences->isRequired == 'Y') {
                $isRequiredLabel = '<span>*</span>';
                $isRequiredField = 'required';

                if (isset($_POST['form_cf_'.$d->id]) && empty($_POST['form_cf_'.$d->id])) {
                    $class = 'border-error';
                }
            }

            // field
            $field = '
            <label>'.$d->name.' '.$isRequiredLabel.'</label>
            <input type="text" name="form_cf_'.$d->id.'" class="'.$class.'" '.$isRequiredField.'>
            ';

            return $field;
        }

    }//fin class
}//fin if