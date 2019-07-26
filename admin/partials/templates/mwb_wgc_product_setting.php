<?php 
/**
 * Exit if accessed directly
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
require_once(MWB_WGC_DIRPATH.'admin/partials/templates/mwb_wgm_settings/mwb_wgm_product_settings_array.php' );

if(isset($_POST['mwb_wgm_product_setting_save']))
{
	unset($_POST['mwb_wgm_product_setting_save']);
	if(isset($_POST['mwb_wgm_product_setting_exclude_product']))
	{
		$giftcard_exclude_products = $_POST['mwb_wgm_product_setting_exclude_product'];		
		if(isset($giftcard_exclude_products) && !empty($giftcard_exclude_products)){
			$giftcard_exclude_product_string = "";
			foreach($giftcard_exclude_products as $giftcard_exclude_product)
			{
				$giftcard_exclude_product_string .= $giftcard_exclude_product.',';
			}
			$giftcard_exclude_product_string = rtrim($giftcard_exclude_product_string, ",");
			update_option("mwb_wgm_product_setting_exclude_product_format", $giftcard_exclude_product_string);
		}		
	}
	else
	{
		$_POST['mwb_wgm_product_setting_exclude_product'] = "";
		update_option("mwb_wgm_product_setting_exclude_product_format", $_POST['mwb_wgm_product_setting_exclude_product']);			
	}
	if(isset($_POST['mwb_wgm_product_setting_exclude_category']))
	{		
	}
	else
	{
		$_POST['mwb_wgm_product_setting_exclude_category'] = "";
	}	
	if(!isset($_POST['mwb_wgm_product_setting_giftcard_ex_sale']))
	{
		$_POST['mwb_wgm_product_setting_giftcard_ex_sale'] = 'no';
	}
	
	do_action('mwb_wgm_product_setting_save');
	
	$postdata = $_POST;
	foreach($postdata as $key=>$data)
	{
		update_option($key, $data);
	}
	$this->mwb_wgm_success_notice_html();
}	
?>
<h2 class="mwb_wgm_overview_heading"><?php _e('Product Settings', 'woocommerce_gift_cards_lite')?></h2>
<div class="mwb_wgm_table_wrapper">
	<table class="form-table mwb_wgm_product_setting">
		<tbody>
		<?php foreach($mwb_wgm_product_settings as $key => $value ){?>
			<tr valign="top">
				<th scope="row" class="titledesc">
					<label for="<?php echo $value['id']?>" ><?php echo $value['label']?></label>
				</th>
				<td class="forminp forminp-text">
					<?php 
					echo wc_help_tip( $value['attribute_description'] );
					?>
					<label for= "<?php echo $value['id'];?>" >
						<?php if( $value['type'] == 'checkbox'){
							$this->mwb_wgm_generate_checkbox_html( $value);
						}
						elseif($value['type'] == 'search&select'){
							$this->mwb_wgm_generate_searchSelect_html( $value);
						}?>
					</label>
				</td>
			</tr>
		<?php
		}
		?>
		<?php 
		do_action('mwb_wgm_product_setting');
		?>
		</tbody>
	</table>
</div>
<?php
$this->mwb_wgm_save_button_html("mwb_wgm_product_setting_save");
?>
<div class="clear"></div>
	