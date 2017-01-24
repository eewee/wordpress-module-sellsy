<?php
namespace fr\eewee\eewee_sellsy\forms;

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
                <input type='hidden' name='form_id' value='<?php echo $r[0]->setting_id; ?>' />

                <div class="submit">
                    <input type="submit" name="update" value="<?php _e('Update', PLUGIN_NOM_LANG); ?>" class="button button-primary" />
                    <a href='<?php echo $this->_returnUrl; ?>' class='button'><?php _e('Back', PLUGIN_NOM_LANG); ?></a>
                </div>

                <div class="postbox " id="postexcerpt">
                    <h3 class="hndle"><span><?php _e('Edit', PLUGIN_NOM_LANG); ?></span></h3>
                    <div class="inside">

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

                <div class="submit">
                    <input type="submit" name="update" value="<?php _e('Update', PLUGIN_NOM_LANG); ?>" class="button button-primary" />
                    <a href='<?php echo $this->_returnUrl; ?>' class='button'><?php _e('Back', PLUGIN_NOM_LANG); ?></a>
                </div>

            </form>

            <?php
        }

    }//class
//}//if
