<?php
namespace fr\eewee\eewee_sellsy\forms;

if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( !class_exists('Form_ContactFormAdd')){
    class Form_ContactFormAdd extends \WP_Query {

        private $_action;
        private $_returnUrl;

        function __construct()
        {
            $this->_action      = $_SERVER["REQUEST_URI"];
            $this->_returnUrl   = EEWEE_SELLSY_URL_BACK_SOUS_MENU_2;
        }

        /**
         * retourn form
         * @param $_POST $p
         */
        public function contactFormAdd( $p="" )
        {
            // INIT
            $status_on = '';
            $status_off = '';
            $contact_form_name = '';
            $contact_form_subject_prefix = '';
            $contact_form_status = '';
            if (isset($p['contact_form_name'])) {           $contact_form_name = $p['contact_form_name'];                     }
            if (isset($p['contact_form_subject_prefix'])) { $contact_form_subject_prefix = $p['contact_form_subject_prefix']; }
            if (isset($p['form_status'])) {                 $contact_form_status = $p['form_status'];                         }
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
                                <th colspan="2">
                                    <div class="title1"><?php _e('Setting', PLUGIN_NOM_LANG); ?></div>
                                </th>
                            </tr>
                            <tr>
                                <th>
                                    <?php _e('Name', PLUGIN_NOM_LANG); ?> :
                                </th>
                                <td>
                                    <input type="text" name="contact_form_setting_name" value="<?php echo $contact_form_setting_name; ?>" />
                                    <p class="description"><?php _e('Name for your back-office', PLUGIN_NOM_LANG); ?></p>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <?php _e('Add on Sellsy', PLUGIN_NOM_LANG); ?> :
                                </th>
                                <td>
                                    <?php
                                    echo '
                                    <select name="contact_form_setting_add_what">
                                        <option value="1">'.__('Prospect', PLUGIN_NOM_LANG).'</option>
                                        <option value="2">'.__('Prospect and opportunity', PLUGIN_NOM_LANG).'</option>
                                    </select>';
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <?php _e('Opportunity source', PLUGIN_NOM_LANG); ?> :
                                </th>
                                <td>
                                    <?php
                                    echo '
                                    <select name="contact_form_setting_opportunity_source">
                                        <option value="1">xxx</option>
                                        <option value="2">yyy</option>
                                    </select>
                                    <p class="description">'.__('Only if you use the option "add prospect and opportunity"', PLUGIN_NOM_LANG).'</p>';
                                    ?>
                                </td>
                            </tr>



                            <tr>
                                <th>
                                    <?php _e('Notification', PLUGIN_NOM_LANG); ?> :
                                </th>
                                <td>
                                    <input type="text" name="contact_form_setting_notification_email" value="<?php echo $contact_form_setting_notification_email; ?>" placeholder="<?php _e('your email', PLUGIN_NOM_LANG); ?>" />
                                    <p class="description"><?php _e('Receive an email with the form information submitted on your website', PLUGIN_NOM_LANG); ?></p>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <?php _e('Status', PLUGIN_NOM_LANG); ?> :
                                </th>
                                <td>
                                    <?php
                                    if( $contact_form_status == 0 ){ $status_on  = "checked";
                                    }else{			                 $status_off = "checked";
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



                            <tr>
                                <th colspan="2">
                                    <div class="title1"><?php _e('Company information', PLUGIN_NOM_LANG); ?></div>
                                </th>
                            </tr>
                            <tr>
                                <th>
                                    <?php _e('Name', PLUGIN_NOM_LANG); ?> :
                                </th>
                                <td>
                                    <?php
                                    $contact_form_company_name_on = $contact_form_company_name_off = '';
                                    if( $contact_form_company_name == 0 ){  $contact_form_company_name_on  = "checked";
                                    }else{			                        $contact_form_company_name_off = "checked";
                                    } ?>

                                    <input type="radio" id="status_on" name="contact_form_company_name" value="0" <?php echo $contact_form_company_name_on; ?> />
                                    <label for="status_on">
                                        <img src='<?php echo EEWEE_SELLSY_PLUGIN_URL; ?>/images/icones/enabled.gif' />
                                    </label>

                                    <input type="radio" id="status_off" name="contact_form_company_name" value="1" <?php echo $contact_form_company_name_off; ?> />
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
