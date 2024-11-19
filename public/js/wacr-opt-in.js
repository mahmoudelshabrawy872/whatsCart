(function( $ ) {
	'use strict';
    jQuery(document).ready(function($){
      jQuery(document).on('click', ".wacr_close_log",function(){  
        jQuery("#wacr_send_otp").show();
        jQuery("#wacr_otp_validate").hide();
        jQuery(".wacr_log_btn_submit").hide();
        jQuery(".wacr_close_log").hide();

      });

      // loginWithOTP();
      function loginWithOTP(){
        jQuery("#password").hide();
        jQuery('[for="password"]').hide();
      }
      jQuery(document).on('click', ".tgl-btn",function(){ 
        
        var wacr_login_withOtp = jQuery('.wacr_login_withOtp').prop("checked");
        
        if(wacr_login_withOtp == true){
          jQuery("#password").hide();
          jQuery('[for="password"]').hide();
        }else{
          jQuery("#password").show();
          jQuery('[for="password"]').show();
        }
        
      });
    });

    jQuery(document).ready(function(e){
      jQuery(".woocommerce-form-login__submit").hide();
      jQuery("#wacr_otp_validate").hide();
      jQuery(".wacr_log_user_mobile").hide();
      jQuery(".wacr_log_btn_submit").hide();
      jQuery(".wacr_close_log").hide();
      

		if(jQuery("#wacr_send_otp").length > 0 || jQuery("#wacr_otp_validate").length > 0 )
		{
      jQuery(".woocommerce-form-login__submit").prop('disabled', true);
      
			jQuery(document).on('click', "#wacr_send_otp",function(){   // 1st way
        Swal.showLoading()
				var username = jQuery("#username").val();
                $.ajax( {
                    type: "POST",
                    url: wacr_public_js_data.ajax_url,
                    data: {
                      action: "wacr_get_user_info_login",
                      username: username
                    },
                    success ( res )
                    {
                      if(res == 0)
                      {
                        Swal.fire({
                          title: 'Error!',
                          text: 'Please enter valid username and try again',
                          icon: 'error'
                        });
                      }
                      else
                      {
                        Swal.fire({
                          title: 'Sent OTP!',
                          text: 'Sent OTP to registered mobile number, Kindly verify',
                          icon: 'success'
                        });                 
                        jQuery("#wacr_send_otp").hide();
                        jQuery("#wacr_otp_validate").show();
                        jQuery(".wacr_log_btn_submit").show();
                        jQuery(".wacr_close_log").show();
                      }
                     
                    },
                  } );
			});
      //verify otp
      jQuery(document).on('click', ".wacr_log_btn_submit",function(){   // 1st way
        Swal.showLoading();
				var username = jQuery("#username").val();
        var wacr_otp_validate = jQuery('#wacr_otp_validate').val();
        var wacr_login_withOtp = jQuery('.wacr_login_withOtp').prop("checked");
        if($("#wacr_otp_validate").val().length > 1) {
          $.ajax( {
            type: "POST",
            url: wacr_public_js_data.ajax_url,
            data: {
              action: "wacr_verify_otp_user_login",
              username: username,
              wacr_otp_validate: wacr_otp_validate,
              wacr_login_withOtp: wacr_login_withOtp,
            },
            success ( res )
            { 
              if(res == "verified")
              {
                Swal.fire({
                  title: 'Success!',
                  text: 'OTP Verified',
                  icon: 'success'
                });
                jQuery(".woocommerce-form-login__submit").show();
                jQuery(".woocommerce-form-login__submit").prop('disabled', false);
                jQuery("#wacr_otp_validate, .wacr_login_with_Otplabel, #wacr_login_withOtp, .wacr_close_log, .wacr_log_btn_submit").hide();
                $("<p class='wacr_verified_success'>Mobile verified successfully!</p>").insertAfter("#password");

              } else if(res == "expried"){
                                          
                Swal.fire({
                  title: 'Error!',
                  text: 'OTP has expired',
                  icon: 'error'
                });                       
             }else
              {
                Swal.fire({
                  title: 'Error!',
                  text: 'OTP Not Verified',
                  icon: 'error'
                });

                jQuery(".woocommerce-form-login__submit").prop('disabled', true);

              }
              if(res == "verifiedwithotp"){
                Swal.fire({
                  title: 'Success!',
                  text: 'OTP Verified',
                  icon: 'success'
                });
                location.reload();
              }
              
             
            },
          } );
     } else {
      jQuery(".woocommerce-form-login__submit").prop('disabled', true);

     }
                
			});
		}
	



	});


})( jQuery );
