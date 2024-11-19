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
require_once plugin_dir_path( __FILE__ ) . '/wacr-api-functions.php';
?>  

          <table id="wacr_manage_templates" class="datatable table table-striped">
                <thead>
                    <tr>
                        <th><?php esc_html_e('Name', 'wacr'); ?></th>
                        <th><?php esc_html_e('Components', 'wacr'); ?></th>
                        <th><?php esc_html_e('Language', 'wacr'); ?></th>
                        <th><?php esc_html_e('Status', 'wacr'); ?></th>
                        <th><?php esc_html_e('Update', 'wacr'); ?></th>
                      
                    </tr>
                    
                </thead>
                <tbody>
                <?php
                  
                    $response = Wacr_API_functions::wacr_get_wp_templates();
                    $res_arr = json_decode($response);
                    foreach($res_arr->data as $dkey => $dval )
                    {
                      $table_class_name = ($dval->status == 'APPROVED') ? 'wacr_template_success':'wacr_template_error';
                      ?>
                    <tr>
                        <td><?php esc_html_e($dval->name);?></td>
                        <td><?php 
                        foreach($dval->components as $bdkey => $bdval)
                        {
                          if($bdval->type == "BODY")
                          {
                            ?> <span class="wacr_collapse"> <?php esc_html_e($bdval->text);?> </span>
                            <?php
                          }
                          
                        }
                        ?></td>
                        <td><?php esc_html_e($dval->language);?></td>
                        <td><span class="<?php esc_html_e($table_class_name);?>"><?php esc_html_e($dval->status);?></span></td>
                        <td>
                        <form method="POST" action="?page=wacr_edit_templates">
                          <input type="hidden" name="buss_id" value="<?php echo esc_attr($dval->id); ?>"/>
                          <input class="check_<?php esc_html_e($table_class_name);?>" type="submit" value="Map Template"/>
                        </form> 
                      </td>
                       


                    </tr>
                    
                    <?php
                    }
                    
                    ?>
                    </tbody>
                    <tfoot>
                    <tr>
                    <th>
                      <?php esc_html_e('Name', 'wacr'); ?></th>
                        <th><?php esc_html_e('Components', 'wacr'); ?></th>
                        <th><?php esc_html_e('Language', 'wacr'); ?></th>
                        <th><?php esc_html_e('Status', 'wacr'); ?></th>
                        <th><?php esc_html_e('Update', 'wacr'); ?></th>
                    </tr>                    
                </tfoot>
            </table>  
