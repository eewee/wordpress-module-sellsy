<?php
//namespace FrEeweePluginSellsyAdmin;
if( !class_exists('AdminController')){
	class AdminController{
		
		function __construct(){}
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

		/**
		 * Définition des options
		 */
		/*
        function getOptionsAdmin(){
			//assigne les valeurs par défaut aux options d'administration
			$tbl_optionsAdmin = array(
				'enabled'		=> true,
				'exclude_ips'	=> ''
			);
			//recup les options stockées en bdd
			$options = get_option($this->adminOptionsName);
			//si les options existent dans la base de données, les valeurs par défaut sont écrasées par celles de la base			
			if( !empty($options) ){
				foreach( $options as $k=>$v ){
					$tbl_optionsAdmin[$k] = $v;
				}
			}
			//les options sont stockées dans la base
			update_option($this->adminOptionsName, $tbl_optionsAdmin);
			//les options sont renvoyées pour être utilisées
			return $tbl_optionsAdmin;
		}
        */
		
		/**
		 * Panneau d'admin
		 */
		/*
                function printAdminPage(){
			echo "printAdminPage";
			$options = $this->getOptionsAdmin();
			// si le post du bouton existe (update_eewee_settings = attribut name du bouton)
			if( isset($_POST['update_eewee_settings']) ){
				if(isset($_POST['enabled'])){
					$options['enabled'] = $_POST['enabled'];
				}
				if(isset($_POST['exclude_ips'])){
					$options['exclude_ips'] = $_POST['exclude_ips'];
				}
				// maj
				update_option($this->adminOptionsName, $options);
			}
			// include du formulaire HTML
			include(EEWEE_RESTAURANT_MENU_PLUGIN_DIR.'/view/admin_settings.php');
		}
        */
		
	}//fin class
}//fin if