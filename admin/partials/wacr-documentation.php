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
          <?php esc_html_e('Debug', 'wacr'); ?>
        </label>
        <input type="radio" name="tabset" id="tab2" aria-controls="2">
        <label for="tab2">
        <?php esc_html_e('Help', 'wacr'); ?>
        </label>
        
       
        <div class="tab-panels">
          <section id="1" class="tab-panel">
        <div class="wacr_repair_db_tables_flex">  
      
          <span id="wacr_repair_dbtables"> <?php esc_html_e('Repair Database Tables', 'wacr'); ?></span>

        </div>

          </section>
          
          <section id="2" class="tab-panel">
           
          </section>
          

          
        </div>
      </div>
    </div>
