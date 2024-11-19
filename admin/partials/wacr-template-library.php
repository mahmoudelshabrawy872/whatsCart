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
          <?php esc_html_e('Abandnoned Cart', 'wacr'); ?>
        </label>
        <input type="radio" name="tabset" id="tab2" aria-controls="2">
        <label for="tab2">
        <?php esc_html_e('Order Notifications', 'wacr'); ?>
        </label>

        <input type="radio" name="tabset" id="tab3" aria-controls="3">
        <label for="tab3">
        <?php esc_html_e('OTP Messages', 'wacr'); ?>
        </label>
               
        <div class="tab-panels">
          <section id="1" class="tab-panel">
            
            <div class="wacr_template_key">
                <h2> <?php echo wp_kses('Abandoned Message 1', 'wacr'); ?> </h2>
                <p> <?php echo wp_kses('<b>Head =></b> Hi {{1}}', 'wacr'); ?></p>
                <p> <?php echo wp_kses('<b>Body =></b> We noticed that you haven’t completed your order. 🤔 Feel free to reply with any query or to request our assistance. We will be happy to help.‍ 😄 Your Items: {{1}} Shop now!🥳', 'wacr'); ?></p>
                <p> <?php echo wp_kses('<b>Footer =></b> Shopname', 'wacr'); ?></p>
                <p> <?php echo wp_kses('<b>Buttons =></b> Select "Call To Action", then Select "Visit Website", <br/> Enter "Button Text Name" then select URL type to dynamic, and URL of website', 'wacr'); ?></p>


            </div>
            <div class="wacr_template_key">
                <h2> <?php echo wp_kses('Abandoned Message 2', 'wacr'); ?> </h2>
                <p> <?php echo wp_kses('<b>Head =></b> Hello {{1}}', 'wacr'); ?></p>
                <p> <?php echo wp_kses('<b>Body =></b> We saw that your shopping cart was left unattended.😳 Don’t hesitate to let us know if you are having any trouble completing the order. 🤔 Your Items: {{1}} We will be happy to assist you.😊 Complete your order, click shop now!', 'wacr'); ?></p>
                <p> <?php echo wp_kses('<b>Footer =></b> Shopname', 'wacr'); ?></p>
                <p> <?php echo wp_kses('<b>Buttons =></b> Select "Call To Action", then Select "Visit Website", <br/> Enter "Button Text Name" then select URL type to dynamic, and URL of website', 'wacr'); ?></p>
                
            </div>
            <div class="wacr_template_key">
                <h2> <?php echo wp_kses('Abandoned Message 3', 'wacr'); ?> </h2>
                <p> <?php echo wp_kses('<b>Head =></b> Hey {{1}}', 'wacr'); ?></p>
                <p> <?php echo wp_kses('<b>Body =></b> You are just one step away.😉 Your cart items:  {{1}} 
                If you are facing any difficulty in placing the order or have any queries related to the product, we are here to help you.😊
                Thank you🤗', 'wacr'); ?></p>

                <p> <?php echo wp_kses('<b>Footer =></b> Shopname', 'wacr'); ?></p>
                <p> <?php echo wp_kses('<b>Buttons =></b> Select "Call To Action", then Select "Visit Website", <br/> Enter "Button Text Name" then select URL type to dynamic, and URL of website', 'wacr'); ?></p>
            </div>
            
          </section>
          
          <section id="2" class="tab-panel">
           

            <div class="wacr_template_key">
                <h2> <?php echo wp_kses('Order Success Message', 'wacr'); ?> </h2>
                <p> <?php echo wp_kses('<b>Head =></b> Hello {{1}}', 'wacr'); ?></p>
                <p> <?php echo wp_kses('<b>Body =></b> Thank you for ordering.😍 
                    Your order ID is: {{1}}
                    Your ordered items are: {{2}}
                    Your order total is: {{3}}
                    Order details are sent to: {{4}}
                    If you have any query you can contact us at support@techspawn.co
                    Keep ordering!
                    Thank you.☺️☺️', 'wacr'); ?></p>
                <p> <?php echo wp_kses('<b>Footer =></b> Shopname', 'wacr'); ?></p>
            
            </div>

            <div class="wacr_template_key">
                <h2> <?php echo wp_kses('Order Success Notification To Admin', 'wacr'); ?> </h2>
                <p> <?php echo wp_kses('<b>Head =></b> Hello {{1}}', 'wacr'); ?></p>
                <p> <?php echo wp_kses('<b>Body =></b> You received a new order.
                    Order Total: {{1}}
                    Ordered Items: {{2}}

                    Thank you', 'wacr'); ?></p>
                <p> <?php echo wp_kses('<b>Footer =></b> Shopname', 'wacr'); ?></p>
                
            </div>

            <div class="wacr_template_key">
                <h2> <?php echo wp_kses('Order Update Notification', 'wacr'); ?> </h2>
                <p> <?php echo wp_kses('<b>Head =></b> Hello {{1}}', 'wacr'); ?></p>
                <p> <?php echo wp_kses('<b>Body =></b> Your order status is changed from {{1}} to {{2}} for Order ID#{{3}}
                Thank you.', 'wacr'); ?></p>
                <p> <?php echo wp_kses('<b>Footer =></b> Shopname', 'wacr'); ?></p>
                
            </div>
            <div class="wacr_template_key">
                
                <h2> <?php echo wp_kses('Verify COD Orders', 'wacr'); ?> </h2>
                <p> <?php echo wp_kses('<b>Head =></b> Hello {{1}}', 'wacr'); ?></p>
                <p> <?php echo wp_kses('<b>Body =></b> **Thank you. Your order has been received. Please confirm your order below*
                Order id: {{1}}
                Date: {{2}}
                Email: {{3}}
                Total: {{4}}
                Payment Method: {{5}}

                *Order details*
                Items: {{6}}
                Total: {{7}}
                Billing address: {{8}}
                Shipping address: {{9}}
s
                Please click on the link to verify your order {{10}}

                Thanks for ordering.', 'wacr'); ?></p>

                <p> <?php echo wp_kses('<b>Footer =></b> Shopname', 'wacr'); ?></p>
            </div>


          </section>
          

          <section id="3" class="tab-panel">
            <div class="wacr_template_key">
                    <h2> <?php echo wp_kses('OTP Template', 'wacr'); ?> </h2>
                    <p> <?php echo wp_kses('<b>Head =></b> Hello', 'wacr'); ?></p>
                    <p> <?php echo wp_kses('<b>Body =></b> Your verification code is {{1}}
                    Thank you', 'wacr'); ?></p>
                    <p> <?php echo wp_kses('<b>Footer =></b> Shopname', 'wacr'); ?></p>
                
                </div>
          </section>

          
        </div>
      </div>
    </div>
