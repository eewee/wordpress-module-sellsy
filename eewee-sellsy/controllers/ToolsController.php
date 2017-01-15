<?php
//namespace FrEeweePluginSellsyTools;
if( !class_exists('ToolsControllers')){
	class ToolsControllers{

		function __construct(){}

        /**
         * Display : success
         * @param bool $r
         */
        public static function success( $message ){
            return '
            <div id="setting-error-settings_updated" class="updated settings-error"> 
                <p><strong>'.$message.'</strong></p>
            </div>';
        }

        /**
         * Display : error
         * @param bool $r
         */
        public static function error( $message ){
            return '
            <div class="error">
                <p><strong>'.$message.'</strong></p>
            </div>';
        }

		/**
		 * Verif des majs
		 * @param bool $r
		 */
		public function verifMaj( $r ){
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

	}//fin class
	
}//fin if
