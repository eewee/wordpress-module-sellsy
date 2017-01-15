<?php
//namespace FrEeweePluginSellsyTools;
if( !class_exists('ToolsControllers')){
	class ToolsControllers{

		//const XXX = 5;

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
		
		static public function formatDate( $date, $type="dd/mm/yyyy" ){
			$tbl_dateHeure = explode(" ", $date );
			$tbl_date = explode( "-", $tbl_dateHeure[0]);
			$tbl_heure= explode(":", $tbl_dateHeure[1]);

			$d = "";
			switch( $type ){
				case "dd/mm/yyyy" :
					$d = $tbl_date[2]."/".$tbl_date[1]."/".$tbl_date[0];
					break;
					
				case "dd-mm-yyyy" :
					$d = $tbl_date[2]."-".$tbl_date[1]."-".$tbl_date[0];
					break;
					
				case "yyyy" :
					$d = $tbl_date[0];
					break;
					
				default :
					$d = $tbl_date[2]."/".$tbl_date[1]."/".$tbl_date[0];
			}
			
			return $d;
		}
		
		/**
		 * retourne un mdp
		 * @param int $taille
		 * @param string $type_cryptage md5, sha1, vide
		 */
		static public function getMdpAleatoire( $taille=8, $type_cryptage="" ){
			$md5 = $sha1 = "";
			
			$chaine ="mnoTUzS5678kVvwxy9WXYZRNCDEFrslq41GtuaHIJKpOPQA23LcdefghiBMbj0";
		    srand((double)microtime()*1000000);
		    for($i=0; $i<$taille; $i++){
		    	@$pass .= $chaine[rand()%strlen($chaine)];
		    }//fin for
			$tbl_pwd[] = $pass;
		    
		    if( $type_cryptage == "md5" ){
				$tbl_pwd[] = md5($pass);
		    }elseif( $type_cryptage == "sha1" ){
		    	$tbl_pwd[] = sha1($pass);
		    }//fin elseif
		    
		    return $tbl_pwd;
		       
		}//fin function
	
		
		/**
		 * validateur email
		 * @param string $adresse
		 */
		static public function emailValide( $adresse ){ 
			$reg = "^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]*[.][a-zA-Z]{2,4}$";
			$email = ereg( $reg, $adresse );
			if ( $email ){
				return true;
			}else{
				return false;
			}//fin else
		}//fin function

	}//fin class
	
}//fin if