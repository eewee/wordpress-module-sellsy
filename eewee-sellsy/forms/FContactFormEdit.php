<?php
namespace fr\eewee\eewee_sellsy\forms;
use fr\eewee\eewee_sellsy\helpers;

if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( !class_exists('Form_ContactFormEdit')){
    class Form_ContactFormEdit extends \WP_Query{

        private $_action;
        private $_returnUrl;

        function __construct()
        {
            $this->_action      = $_SERVER["REQUEST_URI"];
            $this->_returnUrl   = EEWEE_SELLSY_URL_BACK_SOUS_MENU_2;
        }

        /**
         * retourn form
         * @param array $r
         */
        public function contactFormEdit( $r )
        {
            // INIT
            $contact_form_status = '';
            if (isset($r[0]->contact_form_status)) { $contact_form_status = $r[0]->contact_form_status; }
            ?>

            <form method="post" action="<?php echo $this->_action; ?>">
                <?php wp_nonce_field('form_nonce_contact_edit'); ?>
                <input type='hidden' name='form_id' value='<?php echo $r[0]->contact_form_id; ?>' />

                <div class="submit">
                    <input type="submit" name="update" value="<?php _e('Save', PLUGIN_NOM_LANG); ?>" class="button button-primary" />
                    <a href='<?php echo $this->_returnUrl; ?>' class='button'><?php _e('Back', PLUGIN_NOM_LANG); ?></a>
                </div>

                <div class="postbox " id="postexcerpt">
                    <h3 class="hndle"><span>
                        <?php _e('Edit', PLUGIN_NOM_LANG); ?></span>
                        <a href="/wp-admin/admin.php?page=idEeweeSellsy"><?php _e('Help', PLUGIN_NOM_LANG); ?></a>
                    </h3>
                    <div class="inside">

                        <table class='table1'>

                            <?php
                            //------------------------------------------------------------------------------------------
                            // SETTING
                            //------------------------------------------------------------------------------------------
                            ?>
                            <tr>
                                <th colspan="2">
                                    <div class="title1"><?php _e('Setting', PLUGIN_NOM_LANG); ?></div>
                                </th>
                            </tr>
                            <tr>
                                <th>
                                    <?php _e('Shortcode', PLUGIN_NOM_LANG); ?> :
                                </th>
                                <td>
                                    <?php echo '[contactSellsy id="'.$r[0]->contact_form_id.'"]'; ?>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <?php _e('Name', PLUGIN_NOM_LANG); ?> :
                                </th>
                                <td>
                                    <input type="text" name="contact_form_setting_name" value="<?php echo $r[0]->contact_form_setting_name; ?>" />
                                    <p class="description"><?php _e('Name for your back-office', PLUGIN_NOM_LANG); ?></p>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <?php _e('Add on Sellsy', PLUGIN_NOM_LANG); ?> :
                                </th>
                                <td>
                                    <?php
                                    $contact_form_setting_add_what_selected_0 = $contact_form_setting_add_what_selected_1 = '';
                                    if ($r[0]->contact_form_setting_add_what == 0) {
                                        $contact_form_setting_add_what_selected_0 = 'selected';
                                    } else {
                                        $contact_form_setting_add_what_selected_1 = 'selected';
                                    }
                                    echo '
                                    <select name="contact_form_setting_add_what">
                                        <option value="0" '.$contact_form_setting_add_what_selected_0.'>'.__('Prospect', PLUGIN_NOM_LANG).'</option>
                                        <option value="1" '.$contact_form_setting_add_what_selected_1.'>'.__('Prospect and opportunity', PLUGIN_NOM_LANG).'</option>
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
                                    //$r[0]->contact_form_setting_opportunity_source
                                    echo '
                                    <select name="contact_form_setting_opportunity_source">
                                        <option value="0">xxx</option>
                                        <option value="1">yyy</option>
                                    </select>
                                    <p class="description">'.__('Only if you use the option "add prospect and opportunity"', PLUGIN_NOM_LANG).'</p>';
                                    ?>
                                </td>
                            </tr>




                            <?php
                            //------------------------------------------------------------------------------------------
                            // NOTIFICATION
                            //------------------------------------------------------------------------------------------
                            ?>
                            <tr>
                                <th>
                                    <?php _e('Notification', PLUGIN_NOM_LANG); ?> :
                                </th>
                                <td>
                                    <input type="text" name="contact_form_setting_notification_email" value="<?php echo $r[0]->contact_form_setting_notification_email; ?>" placeholder="<?php _e('your email', PLUGIN_NOM_LANG); ?>" />
                                    <p class="description"><?php _e('Receive an email with the form information submitted on your website', PLUGIN_NOM_LANG); ?></p>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <?php _e('Status', PLUGIN_NOM_LANG); ?> :
                                </th>
                                <td>
                                    <?php
                                    $status_on = $status_off = '';
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




                            <?php
                            //------------------------------------------------------------------------------------------
                            // COMPANY INFORMATION
                            //------------------------------------------------------------------------------------------
                            ?>
                            <tr>
                                <th colspan="2">
                                    <div class="title1"><?php _e('Company information', PLUGIN_NOM_LANG); ?></div>
                                </th>
                            </tr>
                            <tr>
                                <th>
                                    <?php _e('Name', PLUGIN_NOM_LANG); // raison sociale ?> :
                                </th>
                                <td>
                                    <?php
                                    helpers\FormHelpers::radio(array(
                                        'echo'      => true,
                                        'form_name' => 'contact_form_company_name',
                                        'form_value'=> $r[0]->contact_form_company_name,
                                    ));
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <?php _e('Siren', PLUGIN_NOM_LANG); ?> :
                                </th>
                                <td>
                                    <?php
                                    helpers\FormHelpers::radio(array(
                                        'echo'      => true,
                                        'form_name' => 'contact_form_company_siren',
                                        'form_value'=> $r[0]->contact_form_company_siren,
                                    ));
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <?php _e('Siret', PLUGIN_NOM_LANG); ?> :
                                </th>
                                <td>
                                    <?php
                                    helpers\FormHelpers::radio(array(
                                        'echo'      => true,
                                        'form_name' => 'contact_form_company_siret',
                                        'form_value'=> $r[0]->contact_form_company_siret,
                                    ));
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <?php _e('RCS', PLUGIN_NOM_LANG); ?> :
                                </th>
                                <td>
                                    <?php
                                    helpers\FormHelpers::radio(array(
                                        'echo'      => true,
                                        'form_name' => 'contact_form_company_rcs',
                                        'form_value'=> $r[0]->contact_form_company_rcs,
                                    ));
                                    ?>
                                </td>
                            </tr>




                            <?php
                            //------------------------------------------------------------------------------------------
                            // CONTACT INFORMATION
                            //------------------------------------------------------------------------------------------
                            ?>
                            <tr>
                                <th colspan="2">
                                    <div class="title1"><?php _e('Contact information', PLUGIN_NOM_LANG); ?></div>
                                </th>
                            </tr>
                            <tr>
                                <th>
                                    <?php _e('Lastname', PLUGIN_NOM_LANG); ?> <span class="eewee-required">*</span> :
                                </th>
                                <td>
                                    <input type="radio" id="contact_form_contact_lastname" name="contact_form_contact_lastname" value="0" checked="checked">
                                    <label for="contact_form_contact_lastname">
                                        <img src="<?php echo EEWEE_SELLSY_PLUGIN_URL; ?>/images/icones/enabled.gif" />
                                    </label>
                                    <?php
//                                    helpers\FormHelpers::radio(array(
//                                        'echo'      => true,
//                                        'form_name' => 'contact_form_contact_lastname',
//                                        'form_value'=> $r[0]->contact_form_contact_lastname,
//                                    ));
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <?php _e('Firstname', PLUGIN_NOM_LANG); ?> :
                                </th>
                                <td>
                                    <?php
                                    helpers\FormHelpers::radio(array(
                                        'echo'      => true,
                                        'form_name' => 'contact_form_contact_firstname',
                                        'form_value'=> $r[0]->contact_form_contact_firstname,
                                    ));
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <?php _e('Email', PLUGIN_NOM_LANG); ?> <span class="eewee-required">*</span> :
                                </th>
                                <td>
                                    <input type="radio" id="contact_form_contact_email" name="contact_form_contact_email" value="0" checked="checked">
                                    <label for="contact_form_contact_email">
                                        <img src="<?php echo EEWEE_SELLSY_PLUGIN_URL; ?>/images/icones/enabled.gif" />
                                    </label>
                                    <?php
//                                    helpers\FormHelpers::radio(array(
//                                        'echo'      => true,
//                                        'form_name' => 'contact_form_contact_email',
//                                        'form_value'=> $r[0]->contact_form_contact_email,
//                                    ));
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <?php _e('Phone', PLUGIN_NOM_LANG); ?> :
                                </th>
                                <td>
                                    <?php
                                    helpers\FormHelpers::radio(array(
                                        'echo'      => true,
                                        'form_name' => 'contact_form_contact_phone_1',
                                        'form_value'=> $r[0]->contact_form_contact_phone_1,
                                    ));
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <?php _e('Mobile', PLUGIN_NOM_LANG); ?> :
                                </th>
                                <td>
                                    <?php
                                    helpers\FormHelpers::radio(array(
                                        'echo'      => true,
                                        'form_name' => 'contact_form_contact_phone_2',
                                        'form_value'=> $r[0]->contact_form_contact_phone_2,
                                    ));
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <?php _e('Function', PLUGIN_NOM_LANG); ?> :
                                </th>
                                <td>
                                    <?php
                                    helpers\FormHelpers::radio(array(
                                        'echo'      => true,
                                        'form_name' => 'contact_form_contact_function',
                                        'form_value'=> $r[0]->contact_form_contact_function,
                                    ));
                                    ?>
                                </td>
                            </tr>




                            <?php
                            //------------------------------------------------------------------------------------------
                            // OTHER
                            //------------------------------------------------------------------------------------------
                            ?>
                            <tr>
                                <th colspan="2">
                                    <div class="title1"><?php _e('Other', PLUGIN_NOM_LANG); ?></div>
                                </th>
                            </tr>
                            <tr>
                                <th>
                                    <?php _e('Website', PLUGIN_NOM_LANG); ?> :
                                </th>
                                <td>
                                    <?php
                                    helpers\FormHelpers::radio(array(
                                        'echo'      => true,
                                        'form_name' => 'contact_form_website',
                                        'form_value'=> $r[0]->contact_form_website,
                                    ));
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <?php _e('Note', PLUGIN_NOM_LANG); ?> :
                                </th>
                                <td>
                                    <?php
                                    helpers\FormHelpers::radio(array(
                                        'echo'      => true,
                                        'form_name' => 'contact_form_note',
                                        'form_value'=> $r[0]->contact_form_note,
                                    ));
                                    ?>
                                </td>
                            </tr>

                        </table>

                        <p>* : <?php _e('required', PLUGIN_NOM_LANG); ?></p>

                    </div>
                </div><?php //postbox ?>

                <div class="submit">
                    <input type="submit" name="update" value="<?php _e('Save', PLUGIN_NOM_LANG); ?>" class="button button-primary" />
                    <a href='<?php echo $this->_returnUrl; ?>' class='button'><?php _e('Back', PLUGIN_NOM_LANG); ?></a>
                </div>

            </form>
            <?php
        }

    }
}
