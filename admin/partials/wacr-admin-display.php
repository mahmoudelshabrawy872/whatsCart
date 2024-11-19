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
?>
<?php
global $wpdb;
global $woocommerce;
$show_reports = $wpdb->get_results($wpdb->prepare("SELECT id,wacr_msg_type,wacr_msg_status,wacr_template,wacr_orderdetails,wacr_updated_date_time FROM ".$wpdb->prefix."wacr_message_logs ORDER BY id DESC"));

$option_array = ["customer_first_name", "customer_last_name", "customer_email","admin_email", "customer_phone", "customer_billing_address","customer_shipping_address", "cart_item_count", "admin_phone","cart_items", "cart_total", "order_id", "time", "date", "site_name"];


$dailyDbCount = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM ".$wpdb->prefix."wacr_message_logs WHERE `wacr_msg_status` = 'sent' AND date(wacr_updated_date_time) = CURDATE() ORDER BY wacr_updated_date_time DESC"));

$userSetCount = get_option('wacr_daily_message_limit_whatsapp');

$remaining_count = intval($userSetCount) - intval($dailyDbCount);

?>

<div class="admin-menu-setting-wacr">
      <div class="tabset">
        <input type="radio" name="tabset" id="tab1" aria-controls="1" checked>
        <label class="wacrlabel" for="tab1">
          <?php esc_html_e('Admin Section', 'wacr'); ?>
        </label>

        <input type="radio" name="tabset" id="tab2" aria-controls="2">
        <label class="wacrlabel" for="tab2">
          <?php esc_html_e('Cron/Template Settings', 'wacr'); ?>
        </label>

        <input type="radio" name="tabset" id="tab3" aria-controls="3">
        <label class="wacrlabel" for="tab3">
          <?php esc_html_e('Message Logs', 'wacr'); ?>
        </label>

        <input type="radio" name="tabset" id="tab4" aria-controls="4">
        <label class="wacrlabel" for="tab4">
          <?php esc_html_e('Manage Templates', 'wacr'); ?>
        </label>
        <input type="radio" name="tabset" id="tab5" aria-controls="5">
        <label class="wacrlabel" for="tab5">
          <?php esc_html_e('Manage Widget', 'wacr'); ?>
        </label>

        <input type="radio" name="tabset" id="tab6" aria-controls="6">
        <label class="wacrlabel" for="tab6">
          <a href="?page=wacr_template_library"><?php esc_html_e('Template Library', 'wacr'); ?></a>
        </label>
        
       
        <div class="tab-panels">
          <section id="1" class="tab-panel">
            <form action="options.php" method="post" id="location_setting_form">
            <?php
            settings_fields('wacr_general');
            do_settings_sections('wacr_general');
            submit_button();
            ?>
            </form>
          </section>
          
          <section id="2" class="tab-panel">
          <!-- dynamic rules addition  -->
          
          <div class="wacr-trigger-list">
          <table class="wacr-trigger-dynamic">
          <?php
           global $wpdb;
           global $woocommerce;
           $current_time = current_time('mysql', false);
           $table = $wpdb->prefix . 'wacr_adandoned_order_list';
           $table1 = $wpdb->prefix . "posts";
           $all = $wpdb->prepare("SELECT * FROM ".$wpdb->prefix."wacr_dynamic_triggers");
           $test = $wpdb->get_results($all);
           $countTest = count($test);
           if(!empty($test))
           {
             $inc = 1;
             foreach ($test as $row) {
              $wacr_trigger_template = $row->wacr_trigger_template;
              $wacr_trigger_time = $row->wacr_trigger_time;
              ?>
              <tr id="wacrdynamictrggr" class="wacr-<?php echo esc_attr($inc);?>">
                <td>
                <select> 
                <?php
                  $response = Wacr_API_functions::wacr_get_wp_templates();
                  $encode_response = json_decode($response);
                    foreach($encode_response->data as $dkey => $dval )
                      { ?>		
                        <option value="<?php esc_html_e($dval->name); ?>" <?php if($dval->name == $wacr_trigger_template)
                        {
                          echo esc_html("selected",'wacr');
                        }
                        ?>><?php esc_html_e($dval->name); ?> </option>
  
                  <?php } ?>
                </select>	
                </td>
                <td>
                  <input type="number" min="0" value="<?php echo esc_attr($wacr_trigger_time);?>"/> <?php esc_html_e('Minutes', 'wacr');?>.
                </td>
                <td>
                
                  <button rowid='<?php echo esc_attr($inc);?>' <?php if($inc < $countTest)
                  {
                    echo "style='display:none';";
                  }
                  ?>
                  class="wacr-trigger-delete"><span class="fa fa-trash"></span></button> 
                 
                  
                </td>
                <td>
                <button rowid='<?php echo esc_attr($inc);?>' <?php if($inc < $countTest)
                  {
                    echo "style='display:none';";
                  }
                  ?>
                  class="wacr-trigger-add"><span class="fa fa-plus"></b></button> 
                </td>
              </tr>
              <?php
              $inc++;
             }
           }
           else
           {
            ?>
            <tr class="wacr-1">
              <td>
              <select> 
              <?php
                $response = Wacr_API_functions::wacr_get_wp_templates();
                $encode_response = json_decode($response);
                  foreach($encode_response->data as $dkey => $dval )
                    { ?>		
                      <option value="<?php esc_html_e($dval->name); ?>"><?php esc_html_e($dval->name); ?> </option>

                <?php } ?>
              </select>	
              </td>
              <td>
                <input type="number" min="0" value=""/> <?php esc_html_e('Minutes', 'wacr');?>.
              </td>
              <td>
                <button rowid='1' class="wacr-trigger-add"><span class="fa fa-plus"></b></button> 
              </td>
              <td>
                 
              </td>
            </tr>
            <?php
           }
             ?>
            </table>
            <hr />
            <button class="button button-primary wacr_save_triggers"><?php esc_html_e('Save Changes', 'wacr');?></button>
                      </div>
          </section>

          <section id="3" class="tab-panel">
          <div class="wacr-datacase-flex">
              <div class="wacr-datacase">
                  <div class="wacr-datacart">               
                      <div class="wacr-count-number">
                        <?php if($userSetCount != ''){esc_html_e( "$userSetCount" );}else{
                          esc_html_e("N/A", 'wacr');
                        }?>
                      </div>
                      <div class="wacr-count-text">
                      <?php esc_html_e('Daily Limit', 'wacr');?> </div>              
                </div>
              </div>

              <div class="wacr-datacase">
                  <div class="wacr-datacart">               
                    <div class="wacr-count-number">
                      <?php esc_html_e( "$dailyDbCount" )?>
                    </div>
                    <div class="wacr-count-text">
                    <?php esc_html_e('Sent Message', 'wacr');?></div>              
                </div>
              </div>
              
              <div class="wacr-datacase">
                <div class="wacr-datacart">               
                  <div class="wacr-count-number">
                    <?php esc_html_e( "$remaining_count" , 'wacr' )?>
                  </div>
                  <div class="wacr-count-text">
                  <?php esc_html_e( 'Remaining Message', 'wacr' )?>  </div>              
                  </div>
              </div>
          </div>
            
    
    <!-- Message Table Logs -->
  
              <div class="btn-group submitter-group float-right mb-1 ">
							<div class="input-group-prepend">
								<div class="input-group-text wacr_filter_button"><?php esc_html_e('Status', 'wacr');?></div>
							</div>
              <select class="form-control wacr_status_dropdown">
								<option value=""><?php esc_html_e('All', 'wacr');?></option>
								<option value="error"><?php esc_html_e('Error', 'wacr');?></option>
								<option value="sent"><?php esc_html_e('Sent', 'wacr');?></option>
								<option value="limit"><?php esc_html_e('Limit', 'wacr');?></option>
							</select>
						</div>
            <div class="btn-group submitter-group float-right mb-1 "style="margin-right:10px">
            <div class="input-group-prepend">
            <div class="input-group-text delete_all"><?php esc_html_e('Delete Selected', 'wacr');?></div>
							</div>
            </div>
    <table id="wacr_message_logs" class="datatable table-striped">
                <thead>
                    <tr>
                    <th><input type="checkbox" id="wamc-select-logs"></th>
                        <th><?php esc_html_e('ID', 'wacr');?></th>
                        <th><?php esc_html_e('Message Type', 'wacr');?></th>
                        <th><?php esc_html_e('Status', 'wacr');?></th>
                        <th><?php esc_html_e('Template Name', 'wacr');?></th>
                        <th><?php esc_html_e('Details', 'wacr');?></th>
                        <th><?php esc_html_e('Date & Time', 'wacr');?></th>
                        <th><?php esc_html_e('Hidden', 'wacr');?></th>
                    </tr>
                    
                </thead>
                <tbody>
                <?php
                  
                    
                    foreach($show_reports as $dkey => $dval )
                    {
                      ?>
                    <tr>
                    <td><input type="checkbox" class="sub_chk" data-id="<?php echo esc_attr($dval->id); ?>"></td>
                        <td><?php echo esc_attr($dval->id); ?></td>
                        <td><?php echo esc_attr($dval->wacr_msg_type); ?></td>
                        <td><?php echo esc_attr($dval->wacr_msg_status); ?></td>
                        <td><?php echo esc_attr($dval->wacr_template); ?></td>
                        <td><?php echo esc_attr($dval->wacr_orderdetails); ?></td>
                        <td><?php echo esc_attr($dval->wacr_updated_date_time); ?></td>
                        <td><?php echo esc_attr($dval->wacr_msg_status); ?></td>


                    </tr>
                    <?php
                    }
                    
                    ?>
                    </tbody>
                    <tfoot>
                    <tr>
                    <th><input type="checkbox" id="wamc-select-logs2"></th>
                        <th><?php esc_html_e('ID', 'wacr');?></th>
                        <th><?php esc_html_e('Message Type', 'wacr');?></th>
                        <th><?php esc_html_e('Status', 'wacr');?></th>
                        <th><?php esc_html_e('Template Name', 'wacr');?></th>
                        <th><?php esc_html_e('Details', 'wacr');?></th>
                        <th><?php esc_html_e('Date & Time', 'wacr');?></th>
                        <th><?php esc_html_e('Hidden', 'wacr');?></th>
                    </tr>
                    
                </tfoot>
            </table>  
          </section>
          <section id="4" class="tab-panel">
            <?php
            require_once plugin_dir_path( __FILE__ ) . 'wacr-manage-templates.php';
            ?>
          </section>
          <section id="5" class="tab-panel">
            <?php
            require_once plugin_dir_path( __FILE__ ) . 'wacr-manage-widget.php';
            ?>
          </section>
        </div>
      </div>
    </div>
