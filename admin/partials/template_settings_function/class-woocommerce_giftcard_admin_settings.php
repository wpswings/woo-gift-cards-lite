<?php 
if (!defined('ABSPATH')) {
	exit();
}
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Woocommerce_gift_cards_lite
 * @subpackage Woocommerce_gift_cards_lite/admin
 */

/**This class is for generating the html for the settings.
 *
 * 
 * This file use to display the function fot the html
 *
 * @package    Woocommerce_gift_cards_lite
 * @subpackage Woocommerce_gift_cards_lite/admin
 * @author     makewebbetter <webmaster@makewebbetter.com>
 */
class Woocommerce_Giftcard_Admin_settings {

	/**
	*This function is for generating for the checkbox for the Settings
	*@name mwb_wgm_generate_checkbox_html
	*@param $value
	*@since 1.0.0 
	*/
	public function mwb_wgm_generate_checkbox_html($value,$general_settings) {
		if( (isset($general_settings[$value['id']]) && ($general_settings[$value['id']] == 'on')) || (isset($general_settings[$value['id']]) && ($general_settings[$value['id']] == 'yes')) ){
			$enable_mwb_wgm = 1;
		}
		else{
			$enable_mwb_wgm = 0;
		}
		
		?>
		<label for="<?php echo (array_key_exists('id', $value))?$value['id']:''; ?>">
			<input type="checkbox" name="<?php echo (array_key_exists('id', $value))?$value['id']:''; ?>" <?php checked($enable_mwb_wgm,1);?> id="<?php echo (array_key_exists('id', $value))?$value['id']:''; ?>" class="<?php echo (array_key_exists('class', $value))?$value['class']:'';?>"> <?php echo (array_key_exists('desc', $value))?$value['desc']:'';?>
		</label>
		<?php
	}

	/**
	*This function is for generating for the radio buttons for the Settings
	*@name mwb_wgm_generate_radiobuttons_html
	*@param $value
	*@since 1.0.0 
	*/
	public function mwb_wgm_generate_radiobuttons_html($value,$general_settings) {
		if( !empty( $general_settings[$value['name']] ) ) {
			$enable_mwb_wgm = (isset( $general_settings[$value['name']] ) && ( $general_settings[$value['name']] ==  $value['value'] ) ) ? 1 : 0;
		}
		else{
			if(array_key_exists('default_value', $value) && $value['default_value'] == 1){
				$enable_mwb_wgm = 1;
			}
			else{
				$enable_mwb_wgm = 0;
			}
		}
		?>
		<label for="<?php echo (array_key_exists('id', $value))?$value['id']:''; ?>">
			<input value = "<?php echo (array_key_exists('value', $value))?$value['value']:''; ?>" type="radio" name="<?php echo (array_key_exists('name', $value))?$value['name']:''; ?>" <?php checked($enable_mwb_wgm,1);?> id="<?php echo (array_key_exists('id', $value))?$value['id']:''; ?>" class="<?php echo (array_key_exists('class', $value))?$value['class']:'';?>"> <?php echo (array_key_exists('desc', $value))?$value['desc']:'';?>
		</label>
		<?php
	}

	/**
	*This function is for generating for the number for the Settings
	*@name mwb_wgm_generate_number_html
	*@param $value
	*@since 1.0.0 
	*/
	public function mwb_wgm_generate_number_html($value,$general_settings) {
		$mwb_wgm_value = isset( $general_settings[ $value ['id'] ] ) ? intval($general_settings[$value['id']]) : '';
		if( array_key_exists('default', $value ) ){
			$mwb_wgm_value = $value['default'];
		}
		?>
		<label for="<?php echo (array_key_exists('id', $value))?$value['id']:''; ?>">
			<input type="number" <?php if (array_key_exists('custom_attribute', $value)) {

				foreach ($value['custom_attribute'] as $attribute_name => $attribute_val) {
					echo  $attribute_name.'='.$attribute_val ;

				}
			}?> value="<?php echo $mwb_wgm_value;?>" name="<?php echo (array_key_exists('id', $value))?$value['id']:''; ?>" id="<?php echo (array_key_exists('id', $value))?$value['id']:''; ?>"
			class="<?php echo (array_key_exists('class', $value))?$value['class']:'';?>"><?php echo (array_key_exists('desc', $value))?$value['desc']:'';?>
		</label>
		<?php
	}

	/**
	*This function is for generating for the wp_editor for the Settings
	*@name mwb_wgm_generate_label
	*@param $value
	*@since 1.0.0 
	*/
	public function mwb_wgm_generate_wp_editor($value,$notification_settings) {
		if(isset($value['id']) && !empty($value['id'])) {
			if( array_key_exists('content', $value)) {
				$mwb_wgm_content = isset( $value['content'] ) ? $value['content'] : '';
			}
			else{
				$mwb_wgm_content = isset($notification_settings[$value['id']]) ?$notification_settings[$value['id']] : '';
			}			
			$value_id = (array_key_exists('id', $value))?$value['id']:'';
			?>
			<label for="<?php echo $value_id; ?>">
				<?php 
				$content=stripcslashes($mwb_wgm_content);
				$editor_id= $value_id;
				$settings = array(
					'media_buttons'    => false,
					'drag_drop_upload' => true,
					'dfw'              => true,
					'teeny'            => true,
					'editor_height'    => 200,
					'editor_class'       => 'mwb_wgm_new_woo_ver_style_textarea',
					'textarea_name'    => $value_id,
				);
				wp_editor($content,$editor_id,$settings); ?>
			</label>	
			<?php
		}
	}

	/**
	*This function is for generating for the Label for the Settings
	*@name mwb_wgm_generate_label
	*@param $value
	*@since 1.0.0 
	*/
	public function mwb_wgm_generate_label($value) {
		?>
		<label for="<?php echo (array_key_exists('id', $value))?$value['id']:'';?>"><?php echo (array_key_exists('title', $value))?$value['title']:''; ?></label>		
		<?php
	}


	/**
	*This function is for generating for the heading for the Settings
	*@name mwb_wgm_generate_heading
	*@param $value
	*@since 1.0.0 
	*/
	public function mwb_wgm_generate_heading($value) {
		if(array_key_exists('title',$value)) {?>
			<div class="mwb_wpr_general_sign_title">
				<?php echo $value['title'];?>
			</div>
			<?php 
		}
	}

	/**
	*This function is for generating for the Tool tip for the Settings
	*@name mwb_wgm_generate_tool_tip
	*@param $value
	*@since 1.0.0 
	*/
	public function mwb_wgm_generate_tool_tip($value) {
		if(array_key_exists('desc_tip',$value)) {
			echo wc_help_tip($value['desc_tip']);
		}
		if(array_key_exists('additional_info',$value)) {
			?>
			<span class="description"><?php _e( $value['additional_info'] , MWB_WGM_DOMAIN) ;?></span>
			<?php
		}
	}

	/**
	*This function is for generating for the text html
	*@name mwb_wgm_generate_textarea_html
	*@param $value
	*@since 1.0.0 
	*/
	public function mwb_wgm_generate_textarea_html($value,$general_settings) {
		$mwb_wgm_value = isset($general_settings[$value['id']]) ? ($general_settings[$value['id']]) : $value['default'];
		?>
		<span class="description"><?php echo array_key_exists('desc', $value)?$value['desc']:'';?></span>	
		<label for="mwb_wpr_general_text_points" class="mwb_wpr_label">
			<textarea 
			<?php if (array_key_exists('custom_attribute', $value)) {
				foreach ($value['custom_attribute'] as $attribute_name => $attribute_val) {
					echo  $attribute_name.'='.$attribute_val ; 

				}
			}?>  name="<?php echo (array_key_exists('id', $value))?$value['id']:''; ?>" id="<?php echo (array_key_exists('id', $value))?$value['id']:''; ?>"
			class="<?php echo (array_key_exists('class', $value))?$value['class']:'';?>"><?php echo (array_key_exists('desc', $value))?$value['desc']:'';?><?php echo $mwb_wgm_value;?>
		</textarea>
	</label>
	<p class="description"><?php echo (array_key_exists('desc2', $value))?$value['desc2']:'';?></p>
	<?php
}

	/**
	*This function is for generating the notice of the save settings
	*@name mwb_wgm_generate_textarea_html
	*@param $value
	*@since 1.0.0 
	*/
	public function mwb_wgm_settings_saved() {
		?>
		<div class="notice notice-success is-dismissible">
			<p><strong><?php _e('Settings saved.', MWB_WGM_DOMAIN); ?></strong></p>
			<button type="button" class="notice-dismiss">
				<span class="screen-reader-text"><?php _e('Dismiss this notices.', MWB_WGM_DOMAIN); ?></span>
			</button>
		</div>
		<?php 
	}
	/**
	 * Generate save button html for setting page
	 * @since 1.0.0
	 * @name mwb_wgm_save_button_html()
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function mwb_wgm_save_button_html($name){
		?>
		<p class="submit">
			<input type="submit" value="<?php _e('Save changes', MWB_WGM_DOMAIN ); ?>" class="button-primary woocommerce-save-button" name="<?php echo $name;?>" id="<?php echo $name;?>" >
			</p><?php
		}

	/**
	*This function is for generating for the text html
	*@name mwb_wgm_generate_text_html
	*@param $value
	*@since 1.0.0 
	*/
	public function mwb_wgm_generate_text_html($value,$general_settings) {
		$mwb_wgm_value = isset($general_settings[$value['id']]) ? ($general_settings[$value['id']]) : '';
		?>
		<label for="
		<?php echo (array_key_exists('id', $value))?$value['id']:''; ?>">
		<input type="text" <?php 
		if (array_key_exists('custom_attribute', $value)) {
			foreach ($value['custom_attribute'] as $attribute_name => $attribute_val) {
				echo  $attribute_name.'='.$attribute_val ; 
			}
		}?> 
		style ="<?php echo (array_key_exists('style', $value))?$value['style']:''; ?>"
		value="<?php echo $mwb_wgm_value;?>" name="<?php echo (array_key_exists('id', $value))?$value['id']:''; ?>" id="<?php echo (array_key_exists('id', $value))?$value['id']:''; ?>"
		class="<?php echo (array_key_exists('class', $value))?$value['class']:'';?>"><?php echo (array_key_exists('desc', $value))?$value['desc']:'';?>
	</label>
	<?php
}

	/**
	 * Generate Drop down menu fields
	 * @since 1.0.0
	 * @name mwb_wgm_generate_searchSelect_html()
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 */

	public function mwb_wgm_generate_searchSelect_html( $value,$general_settings )
	{
		$selectedvalue = isset($general_settings[$value['id']]) ? ($general_settings[$value['id']]) : array();
		if($selectedvalue == ''){
			$selectedvalue = '';
		}

		?>
		<select name="<?php echo (array_key_exists('id', $value))?$value['id']:''; ?>[]" id="<?php echo (array_key_exists('id', $value))?$value['id']:''; ?>" multiple = "<?php echo (array_key_exists('multiple', $value))? $value['multiple']:''; ?>"
			<?php
			if (array_key_exists('custom_attribute', $value)) {
				foreach ($value['custom_attribute'] as $attribute_name => $attribute_val) {
					echo  $attribute_name.'='.$attribute_val ;					
				}
			}
			if(is_array($value['options']) && !empty($value['options'])){
				foreach($value['options'] as $option){
					$select = 0;
					if( is_array($selectedvalue) && in_array($option['id'], $selectedvalue) && !empty( $selectedvalue )){
						$select = 1;
					}
					?>
					><option value="<?php echo $option['id'];?>" <?php echo selected(1, $select);?> ><?php echo $option['name']; ?></option>
					<?php
				}
			}	
			?>
		</select>
	</label>
	<?php	
	}

	/**
	 * Get the entire category in store
	 * @since 1.0.0
	 * @name mwb_wgm_get_category()
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function mwb_wgm_get_category()
	{
		$args = array('taxonomy'=>'product_cat');
		$categories = get_terms($args);
		$category_data = $this->mwb_wgm_show_category($categories);
		return $category_data;	
	}
	/**
	 * Returns the category id and name
	 * @since 1.0.0
	 * @name mwb_wgm_show_category()
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function mwb_wgm_show_category($categories){
		if(isset($categories) && !empty($categories))
		{
			$category = array();
			foreach($categories as $cat)
			{
				$category[]=array(
					'id' => $cat->term_id,
					'name' => $cat->name
				);
			}
			return $category;
		}
	}
	/**
	 * Returns globally excluded products 
	 * @since 1.0.0
	 * @name mwb_wgm_get_product()
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function mwb_wgm_get_product($id, $tag){
		$mwb_wgm_exclude_product = get_option( $tag, false );		
		if( is_array($mwb_wgm_exclude_product) && isset( $mwb_wgm_exclude_product[$id] ) && !empty( $mwb_wgm_exclude_product[$id] ) && is_array( $mwb_wgm_exclude_product[$id] )){
			$mwb_wgm_get_product = array();
			foreach($mwb_wgm_exclude_product[$id] as $pro_id){
				$product      = wc_get_product( $pro_id ); 
				$mwb_wgm_get_product[] = array(
					'id' => $pro_id,
					'name' => $product->get_formatted_name()
				);
			}
			return $mwb_wgm_get_product;
		}else
		{
			$mwb_wgm_exclude_product = array();
			return $mwb_wgm_exclude_product;
		}
	}

	/**
	 * Generates input text with button 
	 * @since 1.0.0
	 * @name mwb_wgm_generate_input_text_with_button_html()
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function mwb_wgm_generate_input_text_with_button_html(  $value, $general_settings ) {
		if( isset( $value['custom_attribute'] ) && !empty( $value['custom_attribute'] ) && is_array( $value['custom_attribute'] ) ) {
			foreach( $value['custom_attribute'] as $key => $val ) {
				if($val['type'] == 'text'){
					$this->mwb_wgm_generate_text_html( $val,$general_settings );
				}
				elseif($val['type'] == 'button'){
					$this->mwb_wgm_generate_button_html( $val );
				}
				elseif($val['type'] == 'paragraph'){
					$this->mwb_wgm_generate_showbox( $val );
				}
			}
		}
		$this->mwb_wgm_generate_bottom_description_field( $value);
	}
	/**
	 * Generates button 
	 * @since 1.0.0
	 * @name mwb_wgm_generate_input_text_with_button_html()
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function mwb_wgm_generate_button_html( $val ) {
		?>
		<input class = "<?php echo (array_key_exists('class', $val))?$val['class']:'';?>"  type = "button" value = "<?php echo (array_key_exists('value', $val))?$val['value']:'';?>" />
		<?php
	}
	/**
	 * Generates paragraph to show picture
	 * @since 1.0.0
	 * @name mwb_wgm_generate_showbox()
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function mwb_wgm_generate_showbox( $val ) {
		?>
		<p id="<?php echo (array_key_exists('id', $val))?$val['id']:'';?>">
			<span class="<?php echo (array_key_exists('id', $val))?$val['id']:'';?>">
				<img src="" width="150px" height="150px" id="<?php echo (array_key_exists('imgId', $val))?$val['imgId']:'';?>">
				<span class="<?php echo (array_key_exists('spanX', $val))?$val['spanX']:'';?>">X</span>
			</span>
		</p>
		<?php
	}

	/**
	*This function is for generating common settings html
	*@name mwb_wgm_sanitize_settings_data
	*@param $value
	*@since 1.0.0 
	*/
	public function mwb_wgm_generate_common_settings( $setting_html_array, $saved_settings ) {
		if(isset( $setting_html_array ) && is_array( $setting_html_array ) && !empty( $setting_html_array ) ) {
			foreach( $setting_html_array  as $key=> $value){
				?>
				<tr valign="top">			
					<th scope="row" class="titledesc">
						<?php $this->mwb_wgm_generate_label( $value ); ?>
					</th>
					<td class="forminp forminp-text">
						<?php
						$this->mwb_wgm_generate_tool_tip( $value );
						if($value['type'] == 'checkbox'){
							$this->mwb_wgm_generate_checkbox_html( $value,$saved_settings );
						}
						elseif($value['type'] == 'number'){
							$this->mwb_wgm_generate_number_html( $value,$saved_settings );
						}
						elseif($value['type'] == 'text'){
							$this->mwb_wgm_generate_text_html( $value,$saved_settings );
						}
						elseif($value['type'] == 'search&select'){
							$this->mwb_wgm_generate_searchSelect_html( $value, $saved_settings );
						}
						elseif($value['type'] == 'radio'){
							$this->mwb_wgm_generate_radiobuttons_html( $value, $saved_settings );
						}
						elseif($value['type'] == 'textWithButton'){
							$this->mwb_wgm_generate_input_text_with_button_html( $value,$saved_settings );
						}
						elseif($value['type'] == 'wp_editor'){
							$this->mwb_wgm_generate_wp_editor( $value,$saved_settings );
						}
						elseif($value['type'] == 'textWithDesc'){
							$this->mwb_wgm_generate_text_with_description( $value,$saved_settings );
						}
						elseif($value['type'] == 'textarea'){
							$this->mwb_wgm_generate_textarea_html( $value,$saved_settings );
						}
						do_action('mwb_wgm_admin_setting_fields_html',$value,$saved_settings);
						?>												
					</td>
				</tr>
				<?php
			}
		}
	}

	/**
	*This function is used to generate text with description 
	*@name mwb_wgm_generate_text_with_description
	*@param $value
	*@since 1.0.0 
	*/
	public function mwb_wgm_generate_text_with_description( $setting_html_array, $saved_settings  ) {
		$this->mwb_wgm_generate_text_html($setting_html_array,$saved_settings);
		$this->mwb_wgm_generate_bottom_description_field( $setting_html_array);
	}

	/**
	*This function is used to generate bottom description field
	*@name mwb_wgm_generate_bottom_description_field
	*@param $value
	*@since 1.0.0 
	*/

	public function mwb_wgm_generate_bottom_description_field( $setting_html_array ){
		?>
		<p class="<?php echo (array_key_exists('class', $setting_html_array))?$setting_html_array['class']:'';?>"><?php echo (array_key_exists('bottom_desc', $setting_html_array))?$setting_html_array['bottom_desc']:'';?></p>
		<?php
	}

	/**
	*This function is used to sanitize email settings data
	*@name mwb_wgm_sanitize_email_settings_data
	*@param $value
	*@since 1.0.0 
	*/

	public function mwb_wgm_sanitize_email_settings_data($posted_data, $setting_html_array){
		if(is_array($setting_html_array) && !empty($setting_html_array) && is_array($posted_data)){
			
			if(isset($setting_html_array['top']) && is_array($setting_html_array['top'])){
				foreach($setting_html_array['top'] as $top_section_setting){
					if(isset($top_section_setting['id']) && array_key_exists($top_section_setting['id'], $posted_data)){
						if( isset($top_section_setting['type']) && ( $top_section_setting['type'] === 'text' || $top_section_setting['type'] === 'textWithDesc' ) && $top_section_setting['type'] !== 'wp_editor' ){

							$posted_values = preg_replace('/\\\\/', '', $posted_data[$top_section_setting['id']]);
							$posted_data[$top_section_setting['id']] = sanitize_text_field($posted_values);

						}
					}
				}
			}
			if(isset($setting_html_array['middle']) && is_array($setting_html_array['middle'])){
				foreach($setting_html_array['middle'] as $mid_section_setting){
					if(isset($mid_section_setting['id']) && array_key_exists($mid_section_setting['id'], $posted_data)){
						if( isset($mid_section_setting['type']) && ( $mid_section_setting['type'] === 'text' || $mid_section_setting['type'] === 'textWithDesc' ) && $mid_section_setting['type'] !== 'wp_editor' ){

							$posted_values = preg_replace('/\\\\/', '', $posted_data[$mid_section_setting['id']]);
							$posted_data[$mid_section_setting['id']] = sanitize_text_field($posted_values);

						}
					}
				}
			}
			if(isset($setting_html_array['bottom']) && is_array($setting_html_array['bottom'])){
				foreach($setting_html_array['bottom'] as $bottom_section_setting){
					if(isset($bottom_section_setting['id']) && array_key_exists($bottom_section_setting['id'], $posted_data)){
						if( isset($bottom_section_setting['type']) && ( $bottom_section_setting['type'] === 'text' || $bottom_section_setting['type'] === 'textWithDesc' ) && $bottom_section_setting['type'] !== 'wp_editor' ){
							$posted_values = preg_replace('/\\\\/', '', $posted_data[$bottom_section_setting['id']]);
							$posted_data[$bottom_section_setting['id']] = sanitize_text_field($posted_values);

						}
					}
				}
			}
		}
		return $posted_data;
	}

	/**
	*This function is used to sanitize data 
	*@name mwb_wgm_sanitize_settings_data
	*@param $value
	*@since 1.0.0 
	*/
	
	public function mwb_wgm_sanitize_settings_data( $posted_data, $setting_html_array ) {
		if( isset( $posted_data ) && is_array( $posted_data ) && !empty( $posted_data ) ) {
			foreach ( $posted_data as $posted_key => $posted_values ) {
				foreach ( $setting_html_array as $htmlkey => $htmlvalue ) {
					if( is_array($setting_html_array) && in_array($posted_key, $htmlvalue) ){
						if( $htmlvalue['type'] == 'text' || $htmlvalue['type'] == 'textarea' ) {
							$posted_values = preg_replace('/\\\\/', '', $posted_values);
							$posted_data[$posted_key] = sanitize_text_field($posted_values);
						}
					}
				}	
			}
		}
		return $posted_data;		
	}
}
