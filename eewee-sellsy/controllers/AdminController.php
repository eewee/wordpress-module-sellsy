<?php
//namespace FrEeweePluginSellsyAdmin;
if( !class_exists('AdminController')){
	class AdminController{
		
		function __construct()
		{

		}

		function init()
		{
			//$this->getOptionsAdmin();
		}
		
		/**
		 * execute lors de l'activation du plugin
		 */
		function eewee_activate()
		{
			$i = new InstallController();
			$i->install();
		}
		
		/**
		 * execute lors de la désactivation du plugin
		 */
		function eewee_deactivate(){}
                
        /**
		 * execute lors de la désinstallation du plugin
		 */
		function eewee_uninstall()
		{
			$i = new InstallController();
			$i->delete();
		}
		
		/**
		 * Gestion des menus du site
		 */
		function eewee_adminMenu()
		{
			// menu principale
			add_menu_page( "Sellsy", "Sellsy", "manage_options", "idEeweeSellsy", array($this, "menu"), plugins_url("eewee-sellsy/images/icones/logo.png") );
			
            // sous menu dans le menu principale
			add_submenu_page( "idEeweeSellsy", "Support ticket", "Support ticket", "manage_options", "idSousMenu1", array($this, "sousMenu1"));
			add_submenu_page( "idEeweeSellsy", "Contact", "Contact", "manage_options", "idSousMenu2", array($this, "sousMenu2"));
			add_submenu_page( "idEeweeSellsy", "Setting", "Setting", "manage_options", "idSousMenu3", array($this, "sousMenu3"));

			// appel init
			add_action('admin_init', array($this, 'init'));
		}
		
		/**
		 * Page : menu principale
		 */
		function menu(){		include(EEWEE_SELLSY_PLUGIN_DIR.'/view/manuel.php');			}
		function sousMenu1(){	include(EEWEE_SELLSY_PLUGIN_DIR.'/view/ticketFormRoot.php');	}
		function sousMenu2(){	include(EEWEE_SELLSY_PLUGIN_DIR.'/view/contactRoot.php');		}
		function sousMenu3(){	include(EEWEE_SELLSY_PLUGIN_DIR.'/view/settingRoot.php');		}

	}//fin class
}//fin if