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

<div class="admin-menu-setting-wacr">
      <div class="tabset">
        <input type="radio" name="tabset" id="tab1" aria-controls="1" checked>
        <label class="wacrlabel" for="tab1">
          <?php esc_html_e('Compatibility', 'wacr'); ?>
        </label>       
       
        <div class="tab-panels">
          <section id="1" class="tab-panel">
            <form action="options.php" method="post" id="location_setting_form">
            <?php
            settings_fields('wacr_compatibility');
            do_settings_sections('wacr_compatibility');
            submit_button();
            ?>
            </form>
          </section>
          
         
        </div>
      </div>
    </div>
