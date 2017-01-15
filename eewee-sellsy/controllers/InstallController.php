<?php
//namespace FrEeweePluginSellsyInstall;
//if( !class_exists('InstallController')){
	class InstallController{
		
		function __construct(){}
                
		/**
		 * install
		 */
		public function install(){
            global $wpdb;

			// CURRENT TIME :
			// - https://codex.wordpress.org/Function_Reference/current_time
			// - echo current_time( 'mysql', 1 ).'<br>'; // 0: GMT+1, 1: GMT+0

            // BO - SETTING
			$sql[] = "
            CREATE TABLE `".EEWEE_SELLSY_PREFIXE_BDD."setting` (
              `setting_id` int(11) NOT NULL AUTO_INCREMENT,
              `setting_dt_create` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
              `setting_dt_update` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
              `setting_consumer_token` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
              `setting_consumer_secret` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
              `setting_utilisateur_token` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
              `setting_utilisateur_secret` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
              PRIMARY KEY (`setting_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
            ";

            // FO - TICKET : stock data form ticket
            $sql[] = "
            CREATE TABLE `".EEWEE_SELLSY_PREFIXE_BDD."ticket` (
              `ticket_id` int(11) NOT NULL AUTO_INCREMENT,
              `ticket_dt_create` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
              `ticket_email` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
              `ticket_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
              `ticket_subject` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
              `ticket_message` text NOT NULL,
			  `ticket_form_linkedid` int(11) NOT NULL,
              PRIMARY KEY (`ticket_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
            ";

            // BO - TICKET : form
			$sql[] = "
            CREATE TABLE `".EEWEE_SELLSY_PREFIXE_BDD."ticket_form` (
              `ticket_form_id` int(11) NOT NULL AUTO_INCREMENT,
              `ticket_form_dt_create` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
              `ticket_form_dt_update` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
              `ticket_form_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
              `ticket_form_subject_prefix` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
              `ticket_form_linkedid` int(11) NOT NULL,
              `ticket_form_status` tinyint(1) NOT NULL,
              PRIMARY KEY (`ticket_form_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
            ";

            // FO - TICKET : error form
			$sql[] = "
            CREATE TABLE `".EEWEE_SELLSY_PREFIXE_BDD."ticket_error` (
              `ticket_error_id` int(11) NOT NULL AUTO_INCREMENT,
              `ticket_error_dt_create` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
              `ticket_error_status` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
              `ticket_error_code` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
              `ticket_error_message` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
              `ticket_error_more` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
              `ticket_error_inerro` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
              PRIMARY KEY (`ticket_error_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
            ";

			$sql[] = "
			INSERT INTO `".EEWEE_SELLSY_PREFIXE_BDD."setting` VALUES (1, '".current_time('mysql')."', '".current_time('mysql')."', '', '', '', '');
			";

			$sql[] = "
			INSERT INTO `".EEWEE_SELLSY_PREFIXE_BDD."ticket_form` VALUES (1, '".current_time('mysql')."', '".current_time('mysql')."', 'Ticket support', '[TICKET SUPPORT]', '0', '0');
			";

            foreach( $sql as $v ){ $wpdb->query($v); }
		}

        /**
		 * delete
		 */
		public function delete(){
            echo "DELETE PLUGIN<br>";
            global $wpdb;
            $sql[] = "DROP TABLE  `".EEWEE_SELLSY_PREFIXE_BDD."api`";
			$sql[] = "DROP TABLE  `".EEWEE_SELLSY_PREFIXE_BDD."ticket_form`";
			$sql[] = "DROP TABLE  `".EEWEE_SELLSY_PREFIXE_BDD."ticket`";
            foreach( $sql as $v ){ $wpdb->query($v); }
		}

	}//class
//}//if