<?php
if( !class_exists('TTicket')){
	class TTicket extends WP_Query{
		
		private $_table;
		
		function __construct(){
			$this->_table = EEWEE_SELLSY_PREFIXE_BDD."ticket";
		}
		
		/**
		 * retourn rows 
		 */
		public function getTickets( $req="", $params="" ){
			global $wpdb;
			$sql	= $wpdb->prepare("SELECT * FROM ".$this->_table." ".$req, $params);
			$r	= $wpdb->get_results($sql);
			return $r;
		}
		
		/**
		 * retourn row
		 * @param int $id
		 */
		public function getTicket( $id ){
			global $wpdb;
			$sql	= $wpdb->prepare("SELECT * FROM ".$this->_table." WHERE ticket_id=%d", $id);
			$r		= $wpdb->get_results($sql);
			return $r;
		}
		
		/**
		 * insert
		 * @param $_POST $p
		 */
		public function add( $p ){
			global $wpdb;
			$r = $wpdb->insert(
				$this->_table,
				array(
					'ID_PLAT_CATEGORIE' => $p['form_categorie'],
					'NOM' => stripslashes($p['form_nom']),
					'INGREDIENT' => stripslashes($p['form_ingredient']),
					'PRIX' => $p['form_prix'],
					'ORDER_PLAT' => $p['form_order'],
					'ETAT' => $p['form_etat']
				),
				array(
					'%d',
                    '%s',
                    '%s',
					'%f',
					'%d',
					'%d'
				)
			);
			return $r;
		}
                
                /**
		 * update etat
		 * @param $_POST $p
		 */
		public function updateStatus( $g ){
			global $wpdb;
			$r = $wpdb->update(
					$this->_table,
					// SET (valeur)
					array(
						'ticket_status' => $g['ticket_status']
					),
					// WHERE (valeur)
					array(
						'ticket_id' => $g['ticket_id']
					),
					// SET (type)
					array(
						'%d'
					),
					// WHERE (type)
					array(
						'%d'
					)
			);
			return $r;
		}
		
		/**
		 * update
		 * @param $_POST $p
		 */
		public function update( $p ){
			global $wpdb;
			
			$r = $wpdb->update(
				$this->_table,
				// SET (valeur)
				array(
					'ID_PLAT_CATEGORIE' => $p['form_categorie'],
					'NOM' => stripslashes($p['form_nom']),
					'INGREDIENT' => stripslashes($p['form_ingredient']),
					'PRIX' => $p['form_prix'],
					'ORDER_PLAT' => $p['form_order'],
					'ETAT' => $p['form_etat']
				),
				// WHERE (valeur)
				array(
					'ticket_id' => $p['form_id']
				),
				// SET (type)
				array(
					'%d',
					'%s',
					'%s',
					'%f',
					'%d',
					'%d'
				),
				// WHERE (type)
				array(
					'%d'
				)
			);
			return $r;
		}
		
	}
}