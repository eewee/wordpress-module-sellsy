<?php
if( !class_exists('Form_TicketFormAdd')){
	class Form_TicketFormAdd extends WP_Query{
		
		private $_action;
		private $_returnUrl;
		
		function __construct(){
			$this->_action      = $_SERVER["REQUEST_URI"];
            $this->_returnUrl   = EEWEE_SELLSY_URL_BACK_SOUS_MENU_1;
		}
		
		/**
		 * retourn form
 		 * @param $_POST $p
 		 */ 
		public function ticketFormAdd( $p="" ){
            // INIT
            $status_on = '';
            $status_off = '';
            $ticket_form_name = '';
            $ticket_form_subject_prefix = '';
            $ticket_form_status = '';
            if (isset($p['ticket_form_name'])) {            $ticket_form_name = $p['ticket_form_name'];                     }
            if (isset($p['ticket_form_subject_prefix'])) {  $ticket_form_subject_prefix = $p['ticket_form_subject_prefix']; }
            if (isset($p['form_status'])) {                 $ticket_form_status = $p['form_status'];                        }
            ?>

			<form method="post" action="<?php echo $this->_action; ?>">
				<div class="submit">
		            <input type="submit" name="add" value="<?php _e('Save', PLUGIN_NOM_LANG); ?>" class="button button-primary" /> 
		            <a href='<?php echo $this->_returnUrl; ?>' class='button'><?php _e('Back', PLUGIN_NOM_LANG); ?></a>
		        </div>
		        
		        <div class="postbox " id="postexcerpt">
					<h3 class="hndle"><span>
                        <?php _e('Add', PLUGIN_NOM_LANG); ?></span>
                        <a href="/wp-admin/admin.php?page=idEeweeSellsy"><?php _e('Help', PLUGIN_NOM_LANG); ?></a>
                    </h3>
					<div class="inside">

                        <table class='table1'>
                            <tr>
                                <th>
                                    <?php _e('Name', PLUGIN_NOM_LANG); ?> :
                                </th>
                                <td>
                                    <input type="text" name="ticket_form_name" value="<?php echo $ticket_form_name; ?>" />
                                    <p class="description"><?php _e('Name for your back-office', PLUGIN_NOM_LANG); ?></p>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <?php _e('Subject prefix', PLUGIN_NOM_LANG); ?> :
                                </th>
                                <td>
                                    <input type="text" name="ticket_form_subject_prefix" value="<?php echo $ticket_form_subject_prefix; ?>" />
                                    <p class="description"><?php _e('Ex : [TICKET SUPPORT]', PLUGIN_NOM_LANG); ?></p>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <?php _e('Linked staff', PLUGIN_NOM_LANG); ?> :
                                </th>
                                <td>
                                    <?php
                                    $t_sellsyStaffs = new TSellsyStaffs();
                                    $staffsList = $t_sellsyStaffs->getStaffsList();
                                    if ($staffsList) {
                                        echo '
                                        <select name="ticket_form_linkedid">';
                                        foreach ($staffsList as $k => $v) {
                                            echo '<option value="'.$k.'">'.$v.'</option>';
                                        }
                                        echo '
                                        </select>';
                                    }
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <?php _e('Status', PLUGIN_NOM_LANG); ?> :
                                </th>
                                <td>
                                    <?php
                                    if( $ticket_form_status == 0 ){	$status_on = "checked";
                                    }else{			                $status_off = "checked";
                                    } ?>

                                    <input type="radio" id="status_on" name="form_status" value="0" <?php echo $status_on; ?> />
                                    <label for="status_on">
                                        <img src='<?php echo EEWEE_SELLSY_PLUGIN_URL; ?>/images/icones/enabled.gif' />
                                    </label>

                                    <input type="radio" id="status_off" name="form_status" value="1" <?php echo $status_off; ?> />
                                    <label for="status_off">
                                        <img src='<?php echo EEWEE_SELLSY_PLUGIN_URL; ?>/images/icones/disabled.gif' />
                                    </label>
                                </td>
                            </tr>
                        </table>
						
			       </div>
				</div><?php //postbox ?>
		       
		        <div class="submit">
		            <input type="submit" name="add" value="<?php _e('Save', PLUGIN_NOM_LANG); ?>" class="button button-primary" /> 
		            <a href='<?php echo $this->_returnUrl; ?>' class='button'><?php _e('Back', PLUGIN_NOM_LANG); ?></a>
		        </div>
		
			</form>
		<?php
		}
		
	}
}