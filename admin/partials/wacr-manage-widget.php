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
$imageWeb2 =  plugin_dir_url( __FILE__ );
$layout_selected = get_option('wacr_layout_option');
if(empty($layout_selected))
{
  $layout_selected = "layout1";
}

?>
<div class="manage-widget-container">
        <div class="manage-widget-grid-wrapper manage-widget-grid-col-auto">
          <label for="manage-widget-radio-card-1" class="manage-widget-radio-card">
            <input type="radio" name="manage-widget-radio-card" value="layout1" id="manage-widget-radio-card-1" 
            <?php echo ($layout_selected == "layout1") ? 'checked' : ''; ?> />
            <div class="manage-widget-card-content-wrapper">
              <span class="manage-widget-check-icon"></span>
              <div class="manage-widget-card-content">
                
                <img src="<?php echo $imageWeb2.'images/layout/layout1.png'; ?>" alt="" />
              </div>
            </div>
          </label>
          <!-- /.radio-card -->

          <label for="manage-widget-radio-card-2" class="manage-widget-radio-card">
            <input type="radio" name="manage-widget-radio-card" value="layout2" id="manage-widget-radio-card-2" 
            <?php echo ($layout_selected == "layout2") ? 'checked' : ''; ?>/>
            <div class="manage-widget-card-content-wrapper">
              <span class="manage-widget-check-icon"></span>
              <div class="manage-widget-card-content">
              <img src="<?php echo $imageWeb2.'images/layout/layout2.png'; ?>" alt="" />
              </div>
            </div>
          </label>
          <!-- /.radio-card -->
          <label for="manage-widget-radio-card-3" class="manage-widget-radio-card">
            <input type="radio" name="manage-widget-radio-card" value="layout3" id="manage-widget-radio-card-3" 
            <?php echo ($layout_selected == "layout3") ? 'checked' : ''; ?>/>
            <div class="manage-widget-card-content-wrapper">
              <span class="manage-widget-check-icon"></span>
              <div class="manage-widget-card-content">
              <img src="<?php echo $imageWeb2.'images/layout/layout3.png'; ?>" alt="" />
              </div>
            </div>
          </label>
          <!-- /.radio-card -->
          <label for="manage-widget-radio-card-4" class="manage-widget-radio-card">
            <input type="radio" name="manage-widget-radio-card" value="layout4" id="manage-widget-radio-card-4" 
            <?php echo ($layout_selected == "layout3") ? 'checked' : ''; ?>/>
            <div class="manage-widget-card-content-wrapper">
              <span class="manage-widget-check-icon"></span>
              <div class="manage-widget-card-content">
              <img src="<?php echo $imageWeb2.'images/layout/layout4.png'; ?>" alt="" />
              </div>
            </div>
          </label>
          <!-- /.radio-card -->
        </div>
        <!-- /.grid-wrapper -->
      </div>
      <div style="display: flow-root;">
      <button type="button" class="button button-primary save_widget_setting">Save Setting</button>
    </div>
      <!-- /.container -->
