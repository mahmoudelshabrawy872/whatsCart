(function( $ ) {
	'use strict';
	$(document).ready(function() {
		
		jQuery(document).on('click', '#wacr_update_1',function(){
			
			var wacr_user_mail = jQuery("#wacr_template_1").val();
			var wacr_user_phone = jQuery("#wacr_time_1").val();
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
		
		







	});

})( jQuery );