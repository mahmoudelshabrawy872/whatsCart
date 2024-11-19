(function( $ ) {
	'use strict';

    jQuery(document).ready(function(e){
		
		if(jQuery("#billing_email").length > 0 || jQuery("#billing_phone").length > 0 )
		{
			jQuery(document).on('change', "#billing_email, #billing_phone, #billing_first_name, #billing_last_name, #shipping_phone, #shipping_country, #billing_country",function(){   // 1st way
				var wacr_user_mail = jQuery("#billing_email").val();
				var wacr_user_phone = jQuery("#billing_phone").val();
				var wacr_user_first_name = jQuery("#billing_first_name").val();
				var wacr_user_last_name = jQuery("#billing_last_name").val();
				var wacr_country = jQuery("#billing_country").val();


                $.ajax( {
                    type: "POST",
                    url: wacr_public_js_data.ajax_url,
                    data: {
                      action: "get_user_data",
                      wacr_user_mail: wacr_user_mail,
                      wacr_user_phone: wacr_user_phone,
                      wacr_user_first_name: wacr_user_first_name,
                      wacr_user_last_name: wacr_user_last_name,
					            wacr_country: wacr_country,

                    },
                    success ( res )
                    {
                     
                    },
                  } );
               


			});
		}
	



	});


})( jQuery );
