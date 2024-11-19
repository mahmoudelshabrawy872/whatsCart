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
if (get_option('wacr_license') == '' || get_option('wacr_license') == 'invalid') {
  ?>
      <script>
          window.location.href = "?page=cart-abadonment-notifier";
      </script>
  <?php
  }   
?>


<div class="admin-menu-setting-wacr">
      <div class="tabset">
        <input type="radio" name="tabset" id="tab1" aria-controls="1" checked>
        <label for="tab1">
          <?php esc_html_e('Order Notification', 'wacr'); ?>
        </label>        
       
        <div class="tab-panels">
          <section id="1" class="tab-panel">
            <form action="options.php" method="post" id="order_notification">
                <?php
                settings_fields('wacr_order_notification');
                do_settings_sections('wacr_order_notification');
                submit_button();
                ?>
            </form>


          </section>
          
          <section id="2" class="tab-panel">
           
          
          </section>
          

          
        </div>
      </div>
    </div>
