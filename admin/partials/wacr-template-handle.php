<?php

/**
 * Cron update product stock and bulk update all products
 *
 * @link       http://www.techspawn.com
 * @since      1.2.10
 *
 * @package    Wacr
 * @subpackage Wacr/admin
 */


class Wacr_Templates
{
    

    public function wacr_prepare_templates($get_template_name){
        require_once plugin_dir_path( __FILE__ ) . '/wacr-api-functions.php';
        $response = new Wacr_API_functions();
        $response = $response->wacr_get_wp_templates();
                    $res_arr = json_decode($response);
                    foreach($res_arr->data as $dkey => $dval )
                    {   
                        if($dval->name == "$get_template_name"){
                            $head_text = wp_unslash($dval->components[0]->text);
                            $body_text = wp_unslash($dval->components[1]->text);
                            $foot_text = wp_unslash($dval->components[2]->text);

                            if(empty($foot_text)){
                              $button_text = wp_unslash($dval->components[2]->buttons[0]->url);
                              
                            }
                          
                            $head_param_count = substr_count($head_text,"{{");
                            $body_param_count = substr_count($body_text,"{{");
                            $foot_param_count = substr_count($foot_text,"{{");
						              	$button_param_count = substr_count($button_text,"{{");
						
                            $params_array = [$head_param_count, $body_param_count, $foot_param_count, $button_param_count];
                            return $params_array;
                      
                        
                    }
                 }
  }
    public function wacr_template_process($template){
        global $woocommerce;
        global $wpdb;
        //get template name

        switch ($template) {
            case "1":
                $template_name = get_option('wacr_first_timing_template');
                return $template_name;

              break;
            case "2":
                $template_name = get_option('wacr_second_timing_template');
                return $template_name;
                
              break;
            case "3":
                $template_name = get_option('wacr_third_timing_template');
                return $template_name;
                
              break;
            case "4":
                $template_name = get_option('wacr_fourth_timing_template');
                return $template_name;
                
              break;
            case "5":
                $template_name = get_option('wacr_fifth_timing_template');
                return $template_name;
                
              break;
            default:
              echo "";
          }

          


        


        



    }
} 

new Wacr_Templates();
