<?php
namespace fr\eewee\eewee_sellsy\controllers;

if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( !class_exists('ToolsControllers')){
	class ToolsControllers {

		function __construct(){}

        /**
         * Display : success
         * @param bool $r
         */
        public static function success( $message )
		{
            return '
            <div id="setting-error-settings_updated" class="updated settings-error"> 
                <p><strong>'.$message.'</strong></p>
            </div>';
        }

        /**
         * Display : error
         * @param bool $r
         */
        public static function error( $message )
		{
            return '
            <div class="error">
                <p><strong>'.$message.'</strong></p>
            </div>';
        }

		/**
		 * Verif des majs
		 * @param bool $r
		 */
		public function verifMaj( $r )
		{
			$display = '';
			if( $r > 0 || $r === 0 ){
				$display = '
				<div id="setting-error-settings_updated" class="updated settings-error"> 
					<p><strong>'.__("Options recorded.", PLUGIN_NOM_LANG).'</strong></p>
				</div>';
			}else{
				$display = '<div class="error"><p><strong>'.__("ERROR", PLUGIN_NOM_LANG).'</strong>&nbsp;: '.__("Update unrealized", PLUGIN_NOM_LANG).'.</p></div>';
			}
			return $display;
		}

        /**
         * Get current url
         * @return string current url
         */
        public static function getUrl() {
            $url  = @( $_SERVER["HTTPS"] != 'on' ) ? 'http://'.$_SERVER["SERVER_NAME"] :  'https://'.$_SERVER["SERVER_NAME"];
            $url .= ( $_SERVER["SERVER_PORT"] != 80 ) ? ":".$_SERVER["SERVER_PORT"] : "";
            $url .= $_SERVER["REQUEST_URI"];
            return $url;
        }

        /**
         * Check string is json
         * @param $string
         * @return bool
         */
        public static function isJson($string){
            return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
        }

	}//fin class
	
}//fin if
