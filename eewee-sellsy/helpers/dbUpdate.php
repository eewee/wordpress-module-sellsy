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
			$result = $wpdb->get_results( 'SELECT * FROM '.EEWEE_SELLSY_PREFIXE_BDD.'version WHERE version_id=1', OBJECT );
			return $result[0]->version_value;
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
			if ( $dbVersion < 1.2 ) {
				if ($dbVersion==EEWEE_VERSION) { return EEWEE_VERSION; }
				$this->upgrade_1_2();
				$dbVersion=1.2;
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

			//$table_name = EEWEE_SELLSY_PREFIXE_BDD . 'version';
			//$sql = "ALTER TABLE `".$table_name."` ADD `test` VARCHAR(255) NOT NULL AFTER `version_value`";
			//$wpdb->query($sql);
		}

		function upgrade_1_2()
		{
			global $wpdb;

			$this->updateVersion("1.2");

			//$table_name = EEWEE_SELLSY_PREFIXE_BDD . 'version';
			//$sql = "ALTER TABLE `".$table_name."` DROP `test`";
			//$wpdb->query($sql);
		}

	}//fin class
}//fin if