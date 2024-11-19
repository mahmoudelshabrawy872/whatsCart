<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://techspawn.com/
 * @since      1.0.0
 *
 * @package    Wacr
 * @subpackage Wacr/admin/partials
 */         
 if(!isset($_POST['buss_id'])){
    header("Location: ?page=wacr_manage_templates");
    die();
 }
 global $wpdb;
 $array = $wpdb->get_results($wpdb->prepare("SELECT wacr_head_params, wacr_body_params FROM ".$wpdb->prefix."wacr_templates WHERE wacr_template_id = %s", $_POST['buss_id']));

 if(isset($array[0])){	
	 $jsonData = stripslashes(html_entity_decode($array[0]->wacr_body_params));
	 $body_params = json_decode($jsonData,true);
	 $head1 = $array[0]->wacr_head_params;
 }else{
	$body_params = '';
	$head1 = '';
 }
?>

<div class="admin-menu-setting-wacr">
      <div class="tabset">
         
        <input type="radio" name="tabset" id="tab1" aria-controls="1" checked>
			<label for="tab1">
			<?php esc_html_e('Edit Templates', 'wacr'); ?>
			</label>
			<label for="tab1">
			<a href="?page=cart-abadonment-notifier"><?php esc_html_e('Back to Home', 'wacr'); ?> </a>
			</label>
        
       
        <div class="tab-panels">
          <section id="1" class="tab-panel">
          <table class="form-table" role="presentation"><tbody><tr><th scope="row"><label for="wacr_heading_for_cron_page"><div class="wacr-heading2"> <?php esc_html_e('Template Settings','wacr');?> </div></label></th><td></td></tr>    
        <?php
        global $wpdb;
		global $woocommerce;

		$buss_id = sanitize_text_field($_POST['buss_id']);


		$response = Wacr_API_functions::wacr_get_wp_templates();
                    $res_arr = json_decode($response);
						foreach($res_arr->data as $dkey => $dval )
							{ 
								if($buss_id == $dval->id)
								{ 
									$wacr_template_language = $dval->language;
								
								$template_name = $dval->name;
								foreach($dval->components as $bdkey => $bdval)
                       				{
										if($bdval->type == "BUTTONS")
										{
											$button_text = isset($bdval->buttons[0]->url) ? sanitize_text_field(wp_unslash($bdval->buttons[0]->url)) : '';
											$button_param_count = substr_count($button_text,"{{");
										}

										if($bdval->type == "HEADER")
										{
											$head_text = $bdval->text;
											$head_param_count = substr_count($head_text,"{{");
										}

										if($bdval->type == "BODY")
										{
											$body_text = $bdval->text;
											$body_param_count = substr_count($body_text,"{{");
										}
                          			}
								}
                    		}
							$wacr_template_id = $buss_id;
							
							$wacr_head_param_count = isset($head_param_count) ? sanitize_text_field(wp_unslash($head_param_count)) : ''; // head param count
							$wacr_body_param_count = isset($body_param_count) ? sanitize_text_field(wp_unslash($body_param_count)) : ''; //body param count
							$wacr_button_param_count = isset($button_param_count) ? sanitize_text_field(wp_unslash($button_param_count)) : '';
							$wacr_head_text = isset($head_text) ? sanitize_text_field(wp_unslash($head_text)) : ''; //head text
							$wacr_body_text = isset($body_text) ? sanitize_text_field(wp_unslash($body_text)) : ''; //body text
							$wacr_button_info = '';
							$wacr_language_params = $wacr_template_language;
							$wacr_updated_date_time = '';
							$option_array = ["customer_first_name", "customer_last_name", "customer_email","admin_email", "customer_phone", "payment_method", "customer_billing_address","customer_shipping_address", "cart_item_count", "admin_phone","cart_items", "cart_total", "order_id", "time", "date", "site_name"];
							?>

                            <tr><th scope="row"><?php esc_html_e("Template Name");?></th><td> <?php esc_html_e("$template_name");?></td></tr>
                            <tr><th scope="row"><?php esc_html_e("Head Text");?></th><td> <?php if(!empty($head_text)){esc_html_e("$head_text");}else{ esc_html_e("Header not available"); }?></td></tr>

                            <tr><th scope="row"><?php esc_html_e("Head Parameters");?></th><td> 
                             

							<?php if($wacr_head_param_count>0){ 
								echo'<b>'; esc_html_e("{1}"); echo'</b>';
							?>
							
                            <select class="chzn-select" id="wacr_head_params" name="wacr_head_params"> 
							<option value="none"><?php esc_html("Select Parameter", 'wacr'); ?></option>
							<?php
      						
							foreach($option_array as $dval )
								{ ?>		
								<option value="<?php esc_html_e($dval); ?>" <?php 
                                                          if ($dval == $head1) {
                                                            echo esc_html("selected",'wacr');
                                                          }
                                                                    
								?>><?php esc_html_e($dval); ?> </option>

							<?php } ?>
							</select>	<?php }else{
								esc_html_e("Header parameter is not available for selection", 'wacr');
							} ?> 
                            </td> </tr>


                            <tr><th scope="row"><?php esc_html_e("Body Text");?></th><td> <?php esc_html_e("$body_text");?></td></tr>
							
                            <tr><th scope="row"><?php esc_html_e("Body Parameters");?></th><td>
                            <?php
							if($wacr_body_param_count>0){
                            for ($x = 1; $x <= $wacr_body_param_count; $x++) {
                               echo'<b>'; esc_html_e(" {{$x}} "); echo'</b>'; 
                             $x2 = $x - 1;
							 if(isset($body_params[$x2])){
								$check_flag = $body_params[$x2];
							 } else{
								$check_flag = "none";
							 }
							?>
                            
    						<select class="wacr_body_param_count_class" id="wacr_body_params" name="wacr_body_params[]"> 
								<option value="none"><?php esc_html("Select Parameter", 'wacr'); ?></option>
								<?php  
									foreach($option_array as $dval )
										{ 
								?>	
										<option value="<?php esc_html_e($dval); ?>" <?php 
														if(isset($body_params) && is_array($body_params)):
                                                          if ($dval == $body_params[$x2]) {
                                                            echo esc_html("selected",'wacr');
                                                          }
														endif;
                                                                    
								?>><?php esc_html_e($dval); ?> </option>

										
								<?php } ?>
							</select>
                            
							

                            <p class="wacr_body_param_count_class_restrict"></p>
							<?php } }else{

								esc_html_e("Body parameter is not available for selection", 'wacr');
							}
						
							?>
                            
							<!-- multiple    -->
                           
							
							<input type="hidden" value="<?php echo esc_attr($wacr_template_id);?>" name="wacr_template_id" id="wacr_template_id"/>
                            <input type="hidden" value="<?php echo esc_attr($template_name);?>" name="wacr_template_name" id="wacr_template_name"/>
							<input type="hidden" value="<?php echo esc_attr($wacr_head_param_count);?>" name="wacr_head_param_count" id="wacr_head_param_count"/>
							<input type="hidden" value="<?php echo esc_attr($wacr_body_param_count);?>" name="wacr_body_param_count" id="wacr_body_param_count"/>
							<input type="hidden" value="<?php echo esc_attr($wacr_head_text);?>" name="wacr_head_text" id="wacr_head_text"/>
							<input type="hidden" value="<?php echo esc_attr($wacr_body_text);?>" name="wacr_body_text" id="wacr_body_text"/>							
							<input type="hidden" value="<?php echo esc_attr($wacr_button_param_count);?>" name="wacr_button_param_count" id="wacr_button_param_count"/>
							<input type="hidden" value="<?php echo esc_attr($wacr_language_params);?>" name="wacr_language_params" id="wacr_language_params"/>
							
                            </tbody></table>

                            <p class="submit"><input type="submit" name="submit" id="wacr_update_templates" class="button button-primary" value="Save Changes"></p>
          </section>
          
         
          

          
        </div>
      </div>
    </div>
