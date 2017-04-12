<?php
namespace fr\eewee\eewee_sellsy\forms;

if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

//if( !class_exists('Form_SettingEdit')){
    class Form_SettingEdit extends \WP_Query {

        private $_action;
        private $_returnUrl;

        function __construct()
        {
            $this->_action      = $_SERVER["REQUEST_URI"];
            $this->_returnUrl   = EEWEE_SELLSY_URL_BACK_SOUS_MENU_3;
        }

        /**
         * retourn form
         * @param array $r
         */
        public function settingEdit( $r )
        { ?>
            <form method="post" action="<?php echo $this->_action; ?>">
                <?php wp_nonce_field('form_nonce_setting_edit'); ?>
                <input type='hidden' name='form_id' value='<?php echo $r[0]->setting_id; ?>' />

                <div class="submit">
                    <input type="submit" name="update" value="<?php _e('Update', PLUGIN_NOM_LANG); ?>" class="button button-primary" />
                    <a href='<?php echo $this->_returnUrl; ?>' class='button'><?php _e('Back', PLUGIN_NOM_LANG); ?></a>
                </div>

                <div class="postbox " id="postexcerpt">
                    <h3 class="hndle"><span><?php _e('Edit Sellsy API', PLUGIN_NOM_LANG); ?></span></h3>
                    <div class="inside">

                        <a href="https://www.sellsy.fr/?_f=prefsApi" target="_blank">
                            https://www.sellsy.fr/?_f=prefsApi
                        </a>

                        <table class='table1'>
                            <tr>
                                <th>
                                    <?php _e('Consumer token', PLUGIN_NOM_LANG); ?> :
                                </th>
                                <td>
                                    <input type="text" name="form_consumer_token" value="<?php echo $r[0]->setting_consumer_token; ?>" />
                                    <p class="description"><?php _e('Get this information on Sellsy', PLUGIN_NOM_LANG); ?></p>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <?php _e('Consumer secret', PLUGIN_NOM_LANG); ?> :
                                </th>
                                <td>
                                    <input type="text" name="form_consumer_secret" value="<?php echo $r[0]->setting_consumer_secret; ?>" />
                                    <p class="description"><?php _e('Get this information on Sellsy', PLUGIN_NOM_LANG); ?></p>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <?php _e('Utilisateur token', PLUGIN_NOM_LANG); ?> :
                                </th>
                                <td>
                                    <input type="text" name="form_utilisateur_token" value="<?php echo $r[0]->setting_utilisateur_token; ?>" />
                                    <p class="description"><?php _e('Get this information on Sellsy', PLUGIN_NOM_LANG); ?></p>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <?php _e('Utilisateur token', PLUGIN_NOM_LANG); ?> :
                                </th>
                                <td>
                                    <input type="text" name="form_utilisateur_secret" value="<?php echo $r[0]->setting_utilisateur_secret; ?>" />
                                    <p class="description"><?php _e('Get this information on Sellsy', PLUGIN_NOM_LANG); ?></p>
                                </td>
                            </tr>
                        </table>

                    </div>
                </div><?php //postbox ?>




                <div class="postbox " id="postexcerpt">
                    <h3 class="hndle"><span><?php _e('Edit reCaptcha', PLUGIN_NOM_LANG); ?></span></h3>
                    <div class="inside">

                        <a href="https://www.google.com/recaptcha/admin#list" target="_blank">
                            https://www.google.com/recaptcha/admin#list
                        </a>

                        <table class='table1'>
                            <tr>
                                <th>
                                    <?php _e('Key website', PLUGIN_NOM_LANG); ?> :
                                </th>
                                <td>
                                    <input type="text" name="form_recaptcha_key_website" value="<?php echo $r[0]->setting_recaptcha_key_website; ?>" />
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <?php _e('Key secret', PLUGIN_NOM_LANG); ?> :
                                </th>
                                <td>
                                    <input type="text" name="form_recaptcha_key_secret" value="<?php echo $r[0]->setting_recaptcha_key_secret; ?>" />
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <?php _e('Status', PLUGIN_NOM_LANG); ?> :
                                </th>
                                <td>
                                    <?php
                                    $status_on = $status_off = "";
                                    if( $r[0]->setting_recaptcha_key_status == 0 ){	$status_on = "checked";
                                    }else{			                                $status_off = "checked";
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
//}//if
