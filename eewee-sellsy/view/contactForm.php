<?php
use fr\eewee\eewee_sellsy\controllers;
use fr\eewee\eewee_sellsy\models;
use fr\eewee\eewee_sellsy\forms;

if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $wpdb;
?>

<div class="wrap" >
    <div id="icon-options-general" class="icon32"><br></div>
    <h2><?php _e('Contact', PLUGIN_NOM_LANG); ?> <?php /*<a href="<?php echo $_SERVER["REQUEST_URI"]."&type=add"; ?>" class="add-new-h2"><?php _e('Add', PLUGIN_NOM_LANG); ?></a>*/ ?></h2>
</div>

<?php
// UPDATE : status
if( isset($_GET['type']) && $_GET['type'] == "status" ) {
    $t_contact_form = new models\TContactForm();
    $r = $t_contact_form->updateStatus($_GET);

    $tools = new controllers\ToolsControllers();
    $display = $tools->verifMaj( $r );
    echo $display;
}//if

// REQ
$reqSuite   = " WHERE contact_form_id > %d ";    // just for notice wordpress wpdb::prepare
$reqSuite   .=" ORDER BY contact_form_id DESC";
$tbl_params = array('0');                       // just for notice wordpress wpdb::prepare

// req
$t_contact  = new models\TContactForm();
$r          = $t_contact->getContactsForm( $reqSuite, $tbl_params );

// display
$render = "
<table class='eewee-table'>
	<tr>
		<th>
			".__('id', PLUGIN_NOM_LANG)."
		</th>
		<th>
			".__('Name', PLUGIN_NOM_LANG)."
		</th>
        <th>
			".__('Shortcode', PLUGIN_NOM_LANG)."
		</th>
        <th>
			".__('State', PLUGIN_NOM_LANG)."
		</th>
		<th>
			".__('Edit', PLUGIN_NOM_LANG)."
		</th>
	</tr>";

foreach($r as $v){
    $render .= "
		<tr>
			<td class='c'>
				".$v->contact_form_id."
			</td>
			<td>
				".$v->contact_form_setting_name."
			</td>
			<td class='c'>
				[contactSellsy id=".$v->contact_form_id."]
			</td>
			<td class='c'>";

    if( $v->contact_form_status ){
        $render .= "
        <a href='".EEWEE_SELLSY_URL_SOUS_MENU_2."&type=status&status=0&contact_form_id=".$v->contact_form_id."'>
            <img src='".EEWEE_SELLSY_PLUGIN_URL."/images/icones/disabled.gif' />
        </a>";
    }else{
        $render .= "
        <a href='".EEWEE_SELLSY_URL_SOUS_MENU_2."&type=status&status=1&contact_form_id=".$v->contact_form_id."'>
            <img src='".EEWEE_SELLSY_PLUGIN_URL."/images/icones/enabled.gif' />
        </a>";
    }

    $render .= "
            </td>
            <td class='c'>
                <a href='".EEWEE_SELLSY_URL_SOUS_MENU_2."&type=edit&contact_form_id=".$v->contact_form_id."'>".__('Edit', PLUGIN_NOM_LANG)."</a>
            </td>
        </tr>";
}//fin foreach

$render .= "
</table>";
echo $render;
