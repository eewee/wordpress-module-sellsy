<?php
if (!defined('EEWEE_VERSION')) exit('No direct script access allowed');
if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

<div id="framework_wrap" class="wrap">

	<div id="header">
	    <h1><?php _e('Manual', PLUGIN_NOM_LANG); ?></h1>
	    <h2><a href='http://www.eewee.fr'>eewee.fr</a></h2>
	    <div class="version">
                <?php _e('Version', PLUGIN_NOM_LANG); ?> <?php echo EEWEE_VERSION; ?>
	    </div>
	</div>
  
  <div id="content_wrap">
  
    <div id="content">
      <div id="options_tabs" class="docs">

        <ul class="options_tabs">
          <li><a href="#general1"><?php _e('Ticket', PLUGIN_NOM_LANG); ?></a></li>
          <li><a href="#general2"><?php _e('Contact', PLUGIN_NOM_LANG); ?></a></li>
          <li><a href="#general3"><?php _e('reCaptcha', PLUGIN_NOM_LANG); ?></a></li>
        </ul>
        
        <hr>
        
        <section id="general1">
          <h2><?php _e('Ticket', PLUGIN_NOM_LANG); ?></h2>
          <p>
            <pre><code><strong>[ticketSellsy id=1]</strong></code></pre>
            <?php _e('Add support ticket form on Wordpress, for create a support ticket to Sellsy.', PLUGIN_NOM_LANG); ?>
          </p>
          <h3><?php _e('Examples', PLUGIN_NOM_LANG); ?> :</h3>
          <p>
            <strong><?php _e('Display the form', PLUGIN_NOM_LANG); ?></strong>
            <br>[ticketSellsy id=1]
            <br>[ticketSellsy id=2]
            <br>[ticketSellsy id=3]
          </p>
        </section><!-- general1 -->
         
        <hr>

        <section id="general2">
          <h2><?php _e('Contact', PLUGIN_NOM_LANG); ?></h2>
          <p>
            <pre><code><strong>[contactSellsy id=1]</strong></code></pre>
            <?php _e('Add contact form on Wordpress, for create a prospect to Sellsy.', PLUGIN_NOM_LANG); ?><br>
            <?php echo __('Email sent with', PLUGIN_NOM_LANG).' '.get_option( 'admin_email' ).' '.__('(Settings > General)', PLUGIN_NOM_LANG); ?>
          </p>
          <p>
            <?php
            echo '<u>'.__('Default setting', PLUGIN_NOM_LANG).' : </u><br>
            - '.__('Prospect', PLUGIN_NOM_LANG).'<br>
            - '.__('Deadline: +1 month', PLUGIN_NOM_LANG).'<br>
            - '.__('Name: website', PLUGIN_NOM_LANG).'<br>
            - '.__('Probability: 10%', PLUGIN_NOM_LANG).'<br>
            - '.__('Tag: wordpress', PLUGIN_NOM_LANG).'<br>
            ';
            ?>
          </p>
          <h3><?php _e('Examples', PLUGIN_NOM_LANG); ?> :</h3>
          <p>
            <strong><?php _e('Display the form', PLUGIN_NOM_LANG); ?></strong>
            <br>[contactSellsy id=1]
            <br>[contactSellsy id=2]
            <br>[contactSellsy id=3]
          </p>
        </section><!-- general2 -->

        <hr>

        <section id="general3">
          <h2><?php _e('reCaptcha', PLUGIN_NOM_LANG); ?></h2>
          <p>
            <?php _e('Create access', PLUGIN_NOM_LANG); ?> :
            <a href="https://www.google.com/recaptcha/admin" target="_blank">https://www.google.com/recaptcha/admin</a>
            <br>

            <?php _e('Information about captcha', PLUGIN_NOM_LANG); ?> :
            <a href="https://fr.wikipedia.org/wiki/ReCAPTCHA" target="_blank">https://fr.wikipedia.org/wiki/ReCAPTCHA</a>
          </p>
        </section><!-- general3 -->

        <br class="clear" />
      </div><!-- options_tabs -->
    </div><!-- content -->
    <!--<div class="info bottom"></div>-->   
  </div><!-- content_wrap -->

</div><!-- framework_wrap -->
<!-- [END] framework_wrap -->