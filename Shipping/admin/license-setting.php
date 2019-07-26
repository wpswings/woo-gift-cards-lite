<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Enhanced_Woocommerce_Recently_Viewed_And_Most_Viewed
 * @subpackage Enhanced_Woocommerce_Recently_Viewed_And_Most_Viewed/admin/partials
 */
?>

<h2><?php _e('License Activation','giftware');?></h2>
<div class="mwb_gw-license-sec">
	<h3><?php _e('Enter your License', 'giftware' ) ?></h3>

    <p>
    	<?php _e('This is the License Activation Panel. After purchasing extension from ', 'giftware' ); ?>
    	<span>
            <a href="https://makewebbetter.com/" target="_blank" ><?php _e('MakeWebBetter',  'giftware' ); ?></a>
        </span>&nbsp;

        <?php _e('you will get the purchase code of this extension. Please verify your purchase below so that you can use the features of this plugin.', 'giftware' ); ?>
    </p>

	<form id="mwb_gw-license-form">

	    <label><b><?php _e('Purchase Code : ', 'giftware' )?></b></label>

	    <input type="text" id="mwb_gw-license-key" placeholder="<?php _e('Enter your code here.', 'giftware' )?>" required="">

        <div id="mwb_gw-ajax-loading-gif"><img src="<?php echo 'images/spinner.gif'; ?>"></div> 
	    
	    <p id="mwb_gw-license-activation-status"></p>

	    <input type='button' class="button-primary"  id="mwb_gw-license-activate" value="<?php _e('Activate', 'giftware' )?>">
	    
	    <?php wp_nonce_field( 'mwb_gw-license-nonce-action', 'mwb_gw-license-nonce' ); ?>

	</form>
</div>
