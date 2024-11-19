(function( $ ) {
	'use strict';
  jQuery(document).ready(function($){
    jQuery(document).on('click', ".wacr_close",function(){  
      $(".wacr_reg_btn").show();
      $(".wacr_reg_user_mobile").hide();
      $(".wacr_reg_btn_submit").hide();
      $(".wacr_close").hide();

    });
  });

  jQuery(document).ready(function($){
     $(" button.register").attr("disabled", true);
     $(".wacr_reg_user_mobile").hide();
     $("button.register ").hide();
     $(".wacr_reg_btn_submit").hide();
     $(".wacr_close").hide();
     $(".wacr_verified_success").hide();



      if(jQuery(".wacr_reg_btn").length > 0)
      {
        jQuery(".woocommerce-form-register__submit").prop('disabled', true);
        jQuery(".woocommerce-form-register__submit").hide;
      }
    jQuery(document).on('click', ".wacr_reg_btn",function(){   // 1st way
        var userMb = $("#wacr_reg_user_mobile").val();
        Swal.showLoading();
        if(userMb != '')
        {
                $.ajax( {
                    type: "POST",
                    url: wacr_public_js_data.ajax_url,
                    data: {
                      action: "wacr_validate_and_send_otp",
                      mobilE: userMb,
                        },
                    success ( res )
                    {  
                        if(res == 'msgSent'){

                            Swal.fire({
                              title: 'Success!',
                              text: 'OTP sent to your mobile number!',
                              icon: 'success'
                            });
                            $(".wacr_reg_btn").hide();
                            $(".wacr_reg_user_mobile").show();
                            $(".wacr_reg_btn_submit").show();
                            $(".wacr_close").show();

                        }else{
                          Swal.fire({
                            title: 'Error!',
                            text: 'Please enter correct number with country code',
                            icon: 'error'
                          });
                        }
                      },
                  });
                }
                else
                        {
                          Swal.fire({
                            title: 'Error!',
                            text: 'Please enter mobile number',
                            icon: 'error'
                          });
                        }
               
			});

        
        jQuery(document).on('click', ".wacr_reg_btn_submit",function(){   // 1st way
        var otp = $(".wacr_reg_user_mobile").val();
        var userMb = $("#wacr_reg_user_mobile").val();
        Swal.showLoading();

                $.ajax( {
                    type: "POST",
                    url: wacr_public_js_data.ajax_url,
                    data: {
                      action: "wacr_check_otp",
                      mobilE: userMb,
                      otp: otp,
                        },
                    success ( res )
                    {  
                        if(res == "verified"){
                            
                            Swal.fire({
                                title: 'Success!',
                                text: 'OTP Verified',
                                icon: 'success'
                              });
                            $(".wacr_reg_btn").hide();
                            $(".wacr_reg_user_mobile").hide();
                            $(".wacr_reg_btn_submit").hide();
                            $(".wacr_close").hide();
                            $(".wacr_verified_success").show();
                            $(".woocommerce-form-register__submit").show();
                            jQuery(".woocommerce-form-register__submit").prop('disabled', false);

                        }else
                        {
                          Swal.fire({
                            title: 'Error!',
                            text: 'OTP Not Verified',
                            icon: 'error'
                          });
                        }

                    },
                  } );
               
			});



});
    


})( jQuery );
