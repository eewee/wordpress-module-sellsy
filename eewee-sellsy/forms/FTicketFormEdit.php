<?php
namespace fr\eewee\eewee_sellsy\forms;
use fr\eewee\eewee_sellsy\models;

if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( !class_exists('Form_TicketFormEdit')){
    class Form_TicketFormEdit extends \WP_Query {

        private $_action;
        private $_returnUrl;

        function __construct()
        {
            $this->_action      = $_SERVER["REQUEST_URI"];
            $this->_returnUrl   = EEWEE_SELLSY_URL_BACK_SOUS_MENU_1;
        }

        /**
         * retourn form
         * @param array $r
         */
        public function ticketFormEdit( $r )
        { ?>
            <form method="post" action="<?php echo $this->_action; ?>">
                <?php wp_nonce_field('form_nonce_ticket_edit'); ?>
                <input type='hidden' name='form_id' value='<?php echo $r[0]->ticket_form_id; ?>' />

                <div class="submit">
                    <input type="submit" name="update" value="<?php _e('Update', PLUGIN_NOM_LANG); ?>" class="button button-primary" />
                    <a href='<?php echo $this->_returnUrl; ?>' class='button'><?php _e('Back', PLUGIN_NOM_LANG); ?></a>
                </div>

                <div class="postbox " id="postexcerpt">
                    <h3 class="hndle">
                        <span><?php _e('Edit', PLUGIN_NOM_LANG); ?></span>
                        <a href="/wp-admin/admin.php?page=idEeweeSellsy"><?php _e('Help', PLUGIN_NOM_LANG); ?></a>
                    </h3>
                    <div class="inside">

                        <table class='table1'>
                            <tr>
                                <th>
                                    <?php _e('ID', PLUGIN_NOM_LANG); ?> :
                                </th>
                                <td>
                                    <?php echo $r[0]->ticket_form_id; ?>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <?php _e('Name', PLUGIN_NOM_LANG); ?> :
                                </th>
                                <td>
                                    <input type="text" name="ticket_form_name" value="<?php echo $r[0]->ticket_form_name; ?>" />
                                    <p class="description"><?php _e('Name for your back-office', PLUGIN_NOM_LANG); ?></p>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <?php _e('Subject prefix', PLUGIN_NOM_LANG); ?> :
                                </th>
                                <td>
                                    <input type="text" name="ticket_form_subject_prefix" value="<?php echo $r[0]->ticket_form_subject_prefix; ?>" />
                                    <p class="description"><?php _e('Ex : [TICKET SUPPORT]', PLUGIN_NOM_LANG); ?></p>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <?php _e('Assigned to', PLUGIN_NOM_LANG); ?> :
                                </th>
                                <td>
                                    <?php
                                    $t_sellsyStaffs = new models\TSellsyStaffs();
                                    $staffsList = $t_sellsyStaffs->getStaffsList();
                                    if ($staffsList) {
                                        echo '
                                        <select name="ticket_form_linkedid">
                                            <option value="0">---- '.__('Nobody', PLUGIN_NOM_LANG).' ----</option>';
                                        foreach ($staffsList as $k => $v) {
                                            $selected = '';
                                            if ($k == $r[0]->ticket_form_linkedid) { $selected = 'selected'; }
                                            echo '<option value="'.$k.'" '.$selected.'>'.$v.'</option>';
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
                                    $status_on = $status_off = "";
                                    if( $r[0]->ticket_form_status == 0 ){	$status_on = "checked";
                                    }else{			                        $status_off = "checked";
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
                    <input type="submit" name="update" value="<?php _e('Update', PLUGIN_NOM_LANG); ?>" class="button button-primary" />
                    <a href='<?php echo $this->_returnUrl; ?>' class='button'><?php _e('Back', PLUGIN_NOM_LANG); ?></a>
                </div>

            </form>

            <?php
        }

    }//class
}//if
