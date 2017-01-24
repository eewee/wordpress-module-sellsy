<?php
use \fr\eewee\eewee_sellsy\models;
use \fr\eewee\eewee_sellsy\forms;

global $wpdb;

// UPDATE
if (isset($_POST['update']) && $_POST['update']) {
    $t_setting = new models\TSetting();
    $r = $t_setting->update( $_POST );

    $tools = new ToolsControllers();
    $display = $tools->verifMaj( $r );
    echo $display;
}//if

$t_setting = new models\TSetting();
$r = $t_setting->getSetting(1);
?>

<div class="wrap">
    <div id="icon-options-general" class="icon32"><br></div>
    <h2><?php _e('Setting', PLUGIN_NOM_LANG); ?></h2>

    <?php
    // FORM
    $f_settingEdit = new forms\Form_SettingEdit();
    $f_settingEdit->settingEdit( $r );
    ?>
</div><!-- .wrap -->
