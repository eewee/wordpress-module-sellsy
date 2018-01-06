<?php
namespace fr\eewee\eewee_sellsy\models;
namespace fr\eewee\eewee_sellsy\helpers;

if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( !class_exists('DbUpdate')){
	class DbUpdate
	{

		/**
		 * Get data version
		 * @return float
		 */
		public function getVersion()
		{
			global $wpdb;

            // Table not in database. Create new table
            $table_name = EEWEE_SELLSY_PREFIXE_BDD."version";
            if($wpdb->get_var("SHOW TABLES LIKE '".$table_name."'") != $table_name) {
                //$charset_collate = $wpdb->get_charset_collate();

//                $sql = "CREATE TABLE  (
//                `version_id` int(11) NOT NULL AUTO_INCREMENT,
//                `version_value` float(11) NOT NULL,
//                PRIMARY KEY (`version_id`)
//                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
//
//                require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
//                dbDelta( $sql );

                return false;

            // Table in db
            } else {
                $result = $wpdb->get_results( 'SELECT * FROM '.EEWEE_SELLSY_PREFIXE_BDD.'version WHERE version_id=1', OBJECT );
                return $result[0]->version_value;
            }
		}

		/**
		 * DB UPDATE
		 * use during : update plugin AND refresh BO.
		 * @return current version updated
		 */
		function updateDb($dbVersion)
		{
			if ( $dbVersion < 1.0 ) {
				if ($dbVersion==EEWEE_VERSION) { return EEWEE_VERSION; }
				$this->upgrade_1_0();
				$dbVersion=1.0;
			}
			if ( $dbVersion < 1.1 ) {
				if ($dbVersion==EEWEE_VERSION) { return EEWEE_VERSION; }
				$this->upgrade_1_1();
				$dbVersion=1.1;
			}
			if ( $dbVersion < 1.11 ) {
				if ($dbVersion==EEWEE_VERSION) { return EEWEE_VERSION; }
				$this->upgrade_1_11();
				$dbVersion=1.11;
			}
			if ( $dbVersion < 1.12 ) {
				if ($dbVersion==EEWEE_VERSION) { return EEWEE_VERSION; }
				$this->upgrade_1_12();
				$dbVersion=1.12;
			}
		}

		/**
		 * Update version
		 * @param $version
		 */
		function updateVersion($version)
		{
			global $wpdb;
			$wpdb->update(
				EEWEE_SELLSY_PREFIXE_BDD."version",
				array("version_value"=>$version),
				array("version_id"=>1),
				array("%s"),
				array("%d")
			);
		}

		function upgrade_1_0()
		{
			global $wpdb;
			$this->updateVersion("1.0");
		}

		function upgrade_1_1()
		{
			global $wpdb;
			$this->updateVersion("1.1");
		}

		function upgrade_1_11()
		{
			global $wpdb;
            $this->updateVersion("1.11");

            $table_name = EEWEE_SELLSY_PREFIXE_BDD . 'contact_form';
            $sql = "ALTER TABLE `".$table_name."` ADD `contact_form_setting_deadline` INT(11) NOT NULL DEFAULT '".EEWEE_DEADLINE."' AFTER `contact_form_setting_notification_email`;";
            $wpdb->query($sql);
		}

		function upgrade_1_12()
		{
			global $wpdb;
			$this->updateVersion("1.12");

			$table_name = EEWEE_SELLSY_PREFIXE_BDD . 'contact_form';
			$sql = "
			ALTER TABLE `$table_name` ADD `contact_form_setting_linkedid` INT(11) NOT NULL AFTER `contact_form_setting_deadline`;
			ALTER TABLE `$table_name` ADD `contact_form_setting_probability` INT(11) NOT NULL AFTER `contact_form_setting_deadline`;
			";
			$wpdb->query($sql);
		}




	}//fin class
}//fin if
