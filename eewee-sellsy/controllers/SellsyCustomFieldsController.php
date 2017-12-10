<?php
namespace fr\eewee\eewee_sellsy\controllers;
use fr\eewee\eewee_sellsy\models;

if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( !class_exists('SellsyCustomFieldsController')){
    class SellsyCustomFieldsController{

        function __construct() {}

        /**
         * Data Processing for post (form)
         * @param $post (only $_POST for CustomField)
         * @param $cfType array((int)id, (string)type). Type = prospect or opportunity
         * @return bool
         */
        public function dataProcessing($post, $cfType)
        {
            // INIT
            $error      = array();
            $success    = array();
            $linkedid   = (int)$cfType['id'];
            $linkedtype = $cfType['type'];

            if (isset($post['form_cf'])) {
                foreach ($post['form_cf'] as $k=>$v) {

                    // CF : SIMPLETEXT
                    if (isset($v['simpletext'])){
                        // function pour traiter le $_POST
                        //echo "traitement : simpletext.<br>";
                        //echo 'linkedid : '.$linkedid.'<br>';
                        //echo 'linkedtype : '.$linkedtype.'<br>';
                        //echo '<pre>'.var_export($k, true).'</pre>';
                        //echo '<pre>'.var_export($v, true).'</pre>';

                        // DATA
                        $d['api']['id']                 = $k;
                        $d['api']['label']              = $v['simpletext']['name'];
                        $d['api']['default']            = $v['simpletext']['default'];
                        $d['api']['min']                = $v['simpletext']['min'];
                        $d['api']['max']                = $v['simpletext']['max'];
                        $d['api']['useOne_prospect']    = $v['simpletext']['useOn_prospect'];
                        $d['api']['useOne_opportunity'] = $v['simpletext']['useOn_opportunity'];
                        $d['form']['value']             = $v['simpletext']['value'];

                        // PROCESSING
                        $check = $this->checkSimpleText($d);

                        // SAVE
                        if (isset($check[0]) && $check[0] == "success") {

                            // STOCK : opportunity or prospect
                            switch ($linkedtype ) {
                                case 'prospect' :
                                    if ($d['api']['useOne_prospect'] == "Y") {
                                        $success[] = array(
                                            'cfid'  => $d['api']['id'],
                                            'value' => $d['form']['value']
                                        );
                                    }
                                    break;

                                case 'opportunity' :
                                    if ($d['api']['useOne_opportunity'] == "Y") {
                                        $success[] = array(
                                            'cfid'  => $d['api']['id'],
                                            'value' => $d['form']['value']
                                        );
                                    }
                                    break;
                            }

                        } elseif (isset($check[0]) && $check[0] == "error") {
                            $error[$d['api']['id']] = $d['form']['value'];
                        }
                    }

                }

                if (isset($error) && !empty($error)) {
                    return $error;
                } elseif (isset($success) && !empty($success)) {

                    // SAVE
                    $tCf = new models\TSellsyCustomFields();
                    $tCf->recordValues(array(
                        "linkedtype"    => $linkedtype,
                        "linkedid"      => $linkedid,
                        "datas"         => $success
                    ));

                }

            } else {
                return false;
            }
        }

        /**
         * Generator input, select, radio, checkbox, ...
         * @param $d, $d->type
         * @return bool, field
         */
        public function getGenerator($d, $tbl_class)
        {
            $type = $d->type;
            
            // Dispatcher
            switch ($type) {
                case 'simpletext' :
                    $res = $this->getTypeSimpleText($d, $tbl_class);
                    break;
                    
                default :
                    return false;
            }

            return $res;
        }

        /**
         * simpletext
         * Note : string with min/max
         * @param $d
         */
        public function getTypeSimpleText($d, $tbl_class)
        {
            // init
            $isRequiredLabel = '';
            $isRequiredField = '';
            $class           = '';
            $valueField      = '';

            // isRequired
            if ($d->preferences->isRequired == 'Y') {
                $isRequiredLabel = '<span>*</span>';
                $isRequiredField = 'required';

                if (isset($_POST['form_cf_'.$d->id]) && empty($_POST['form_cf_'.$d->id])) {
                    $class = 'border-error';
                }
            }

            // populate
            if (isset($_POST['form_cf'][$d->id]['simpletext']['value']) && !empty($_POST['form_cf'][$d->id]['simpletext']['value'])) {
                $valueField = $_POST['form_cf'][$d->id]['simpletext']['value'];
            }
            if (isset($tbl_class[$d->id]) && !empty($tbl_class[$d->id])) {
                $class = $tbl_class[$d->id];
            }

            // field
            $field = '
            <label>'.$d->name.' '.$isRequiredLabel.'</label>
            <input type="text" name="form_cf['.$d->id.'][simpletext][value]" value="'.$valueField.'" class="'.$class.'" '.$isRequiredField.' pattern="{'.$d->preferences->min.','.$d->preferences->max.'}">
            
            <input type="hidden" name="form_cf['.$d->id.'][simpletext][name]" value="'.$d->name.'">
            <input type="hidden" name="form_cf['.$d->id.'][simpletext][default]" value="'.$d->preferences->defaultValue.'">
            <input type="hidden" name="form_cf['.$d->id.'][simpletext][min]" value="'.$d->preferences->min.'">
            <input type="hidden" name="form_cf['.$d->id.'][simpletext][max]" value="'.$d->preferences->max.'">
            <input type="hidden" name="form_cf['.$d->id.'][simpletext][useOn_prospect]" value="'.$d->useOn_prospect.'">
            <input type="hidden" name="form_cf['.$d->id.'][simpletext][useOn_opportunity]" value="'.$d->useOn_opportunity.'">
            ';

            return $field;
        }

        /**
         * simpletext : check
         * @params $d array
         */
        public function checkSimpleText($d)
        {
            // INIT : default by api
            $name       = $d['api']['label'];       // name field
            $default    = $d['api']['default'];     // default value
            $min        = (int)$d['api']['min'];    // min
            $max        = (int)$d['api']['max'];    // max

            // INIT : form value
            $f_value        = $d['form']['value'];  // form value
            $f_value_size   = strlen($f_value);     // form value size

            // CHECK
            if (empty($min) && empty($max)) {
                if (empty($f_value)) {
                    return array("success", $default);
                } else {
                    return array("success", $f_value);
                }

            } elseif ($min > 0 && $max > 0) {
                if ($f_value_size >= $min && $f_value_size <= $max) {
                    return array("success", $f_value);
                } elseif ($f_value_size <= $min) {
                    return array("error", "Your value for ".$name." is too small.");
                } elseif ($f_value_size >= $max) {
                    return array("error", "Your value for " . $name . " is too big.");
                }

            } elseif ($min > 0) {
                if ($f_value_size >= $min) {
                    return array("success", $f_value);
                } elseif ($f_value_size < $min) {
                    return array("error", "Your value for ".$name." is too small.");
                }

            } elseif ($min > 0) {
                if ($f_value_size <= $max) {
                    return array("success", $f_value);
                } elseif ($f_value_size > $max) {
                    return array("error", "Your value for ".$name." is too big.");
                }

            }

        }


    }//fin class
}//fin if