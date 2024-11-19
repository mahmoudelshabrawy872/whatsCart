(function( $ ) {
	'use strict';
	jQuery( document ).ready(function() {
		
		function func1(){
			var enableOtp = $("#wacr_enable_otp_login").prop('checked');
			if(enableOtp == true){
				$("#wacr_enable_otp_login").prop("checked", false);	
			}
		}
		function func2(){
			var enableOtp = $("#wacr_reg_without_pswd").prop('checked');
			if(enableOtp == true){
				$("#wacr_reg_without_pswd").prop("checked", false);	
			}
		}
		$("#wacr_reg_without_pswd").on('click', function(){
			func1();
		});

		$("#wacr_enable_otp_login").on('click', function(){
			func2();
		});

		function bkuppnotify(){
			var enableOtp = $("#wacr_booking_notify").prop('checked');
			if(enableOtp == true){
				$("#wacr_booking_notify").prop("checked", false);	
			}
		}
		function bkuppverify(){
			var enableOtp = $("#wacr_booking_verify").prop('checked');
			if(enableOtp == true){
				$("#wacr_booking_verify").prop("checked", false);	
			}
		}
		$("#wacr_booking_verify").on('click', function(){
			bkuppnotify();
		});

		$("#wacr_booking_notify").on('click', function(){
			bkuppverify();
		});

		$( ".check_wacr_template_error" ).prop( "disabled", true );
		order_page_template_setting();
		function order_page_template_setting(){
			var wacr_order_notification_status = $('#wacr_order_notification_status').prop('checked');
			var wacr_admin_order_notification_status = $('#wacr_admin_order_notification_status').prop('checked');
			var wacr_order_update_notification = $('#wacr_order_update_notification').prop('checked');
			var wacr_order_confirmation = $('#wacr_order_confirmation').prop('checked');
			var wacr_enable_clicktochat = $('#wacr_enable_clicktochat').prop('checked');
			var wacr_enable_cooldown = $('#wacr_enable_cooldown').prop('checked');
			var wacr_enable_otp_register = $('#wacr_enable_otp_register').prop('checked');
			var wacr_enable_otp_login = $('#wacr_enable_otp_login').prop('checked');
			var wacr_order_on_whatsapp = $('#wacr_order_on_whatsapp').prop('checked');


				if(wacr_order_notification_status === false){
					$("#wacr_order_notification_template").hide();
				}else{
					$("#wacr_order_notification_template").show();
				}

				if(wacr_admin_order_notification_status === false){
					$("#wacr_admin_order_notification_template").hide();
					$("#wacr_admin_order_notification_mobile").hide();
					$("#wacr_admin_order_notification_mobile").removeAttr('required');

				}else{
					$("#wacr_admin_order_notification_mobile").prop('required', true);
					$("#wacr_admin_order_notification_template").show();
					$("#wacr_admin_order_notification_mobile").show();

				}
		
				if(wacr_order_update_notification === false){
					$("#wacr_order_update_temp").hide();
				}else{
					$("#wacr_order_update_temp").show();
				}
		
				if(wacr_order_confirmation === false){
					$("#wacr_order_confirmation_template").hide();
				}else{
					$("#wacr_order_confirmation_template").show();
				}

				if(wacr_enable_clicktochat === false){
					$("#wacr_ctc_mobile_number").hide();
					$("#wacr_ctc_mobile_number").removeAttr("required");
				}else{
					$("#wacr_ctc_mobile_number").show();
					$("#wacr_ctc_mobile_number").prop('required', true);
				}

				if(wacr_enable_otp_register === false){
					$("#wacr_template_otp_register").hide();
					$("#wacr_template_otp_register").removeAttr("required");
				}else{
					$("#wacr_template_otp_register").show();
					$("#wacr_template_otp_register").prop('required', true);
				}

				if(wacr_enable_otp_login === false){
					$("#wacr_template_otp_login").hide();
					$("#login_without_otp").hide();
					$("#wacr_template_otp_login").removeAttr("required");
				}else{
					$("#wacr_template_otp_login").show();
					$("#login_without_otp").show();
					$("#wacr_template_otp_login").prop('required', true);
				}

				if(wacr_enable_cooldown === false){
					$(".wacr_dnd_time").hide();
					$("#wacr_enable_cooldown_from").removeAttr('required');
					$("#wacr_enable_cooldown_to").removeAttr('required');
				}else{
					$(".wacr_dnd_time").show();
					$("#wacr_enable_cooldown_from").prop('required', true);
					$("#wacr_enable_cooldown_to").prop('required', true);

				}

				if(wacr_order_on_whatsapp === false){
					$("#wacr_order_on_whatsapp_num").hide();
					$("#wacr_order_on_whatsapp_num").removeAttr("required");
				}else{
					$("#wacr_order_on_whatsapp_num").show();
					$("#wacr_order_on_whatsapp_num").prop('required', true);
				}
		}
		
		jQuery(document).on('click', '#wacr_order_on_whatsapp, #wacr_order_notification_status, #wacr_order_update_notification, #wacr_order_confirmation, #wacr_enable_clicktochat, #wacr_enable_cooldown, #wacr_admin_order_notification_status, #wacr_enable_otp_register, #wacr_enable_otp_login',function(){
			order_page_template_setting();
		});

		jQuery(document).on('click', '#wacr_repair_dbtables',function(){
			const swalWithBootstrapButtons = Swal.mixin({
				customClass: {
				  confirmButton: 'wacr_btn wacr_btn-success',
				  cancelButton: 'wacr_btn wacr_btn-danger'
				},
				buttonsStyling: false
			  })
			  
			  swalWithBootstrapButtons.fire({
				title: 'Repair database?',
				text: "You have to map templates again after this.",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonText: 'Yes, delete it!',
				cancelButtonText: 'No, cancel!',
				reverseButtons: true
			  }).then((result) => {
				if (result.isConfirmed) {

					$.ajax( {
						type: "POST",
						url: wacr_ajax.ajaxurl,
						data: {
						  action: "wacr_repair_database_tables",
						  
						},
						success ( res )
						{	
						  if(res == '1'){
							swalWithBootstrapButtons.fire(
								'Repaired!',
								'Please do not forget to map templates',
								'success'
							  )
		  
						  }
						  
						},
						} );

				  
				} else if (
				  /* Read more about handling dismissals below */
				  result.dismiss === Swal.DismissReason.cancel
				) {
				  swalWithBootstrapButtons.fire(
					'Cancelled',
					'',
					'error'
				  )
				}
			  })
		});


		jQuery(".chzn-select").chosen({
			width: "30%"
		});
		jQuery(document).on('click', '.save_widget_setting',function(){
			var checked_option = $('input[name=manage-widget-radio-card]:checked').val();
			$.ajax( {
				type: "POST",
				url: wacr_ajax.ajaxurl,
				data: {
				  action: "wacr_update_widget_option",
				  wacr_template_id: checked_option
				},
				success ( res )
				{	
				  if(res == '1'){
					  Swal.fire({
						  title: 'Updated!',
						  text: 'Successfully updated your layout',
						  icon: 'success',
						  confirmButtonText: 'Okay'
						},)
  
				  }
				  
				},
				} );

		});
		
		  var wacr_template_id = jQuery("#wacr_template_id").val();
		 

		  var wacr_template_name = jQuery("#wacr_template_name").val();
		  var wacr_head_param_count = jQuery("#wacr_head_param_count").val();
		  var wacr_body_param_count = jQuery("#wacr_body_param_count").val();
		  var wacr_button_param_count = jQuery("#wacr_button_param_count").val();
		  var wacr_language_params = jQuery("#wacr_language_params").val();
		  var wacr_head_text = jQuery("#wacr_head_text").val();
		  var wacr_body_text = jQuery("#wacr_body_text").val();
		
		 
		  jQuery(".wacr_body_param_count_class").chosen({
			width: "30%" });
				
			
			jQuery(document).on('click', '#wacr_update_templates',function(){
			var wacr_head_params = jQuery("#wacr_head_params").val();
			var wacr_body_params = $('select[name="wacr_body_params[]"]').map(function () {
				return this.value; 
			}).get();
			


			$.ajax( {
			  type: "POST",
			  url: wacr_ajax.ajaxurl,
			  data: {
				action: "wacr_update_template_options",
				wacr_template_id: wacr_template_id,
				wacr_template_name : wacr_template_name,
				wacr_head_param_count : wacr_head_param_count,
				wacr_body_param_count : wacr_body_param_count,
				wacr_button_param_count : wacr_button_param_count,
				wacr_language_params : wacr_language_params,
				wacr_head_text : wacr_head_text,
				wacr_body_text : wacr_body_text,
				wacr_head_params : wacr_head_params,
				wacr_body_params : wacr_body_params,
				
			  },
			  success ( res )
			  {	
				if(res == '1'){
					Swal.fire({
						title: 'Updated!',
						text: 'Successfully updated your template',
						icon: 'success',
						confirmButtonText: 'Okay'
					  },)

				}
				
			  },
			  } );
	  
			});



			///


			jQuery(document).on('click', '#wacr_order_update_template',function(){
				var name = jQuery("#wacr_order_update_template_name").val();
				var head = jQuery("#wacr_order_update_template_head").val();
				var body = jQuery("#wacr_order_update_template_body").val();
				wacr_create_tmp_cb(name, head, body);
			});
			jQuery(document).on('click', '#wc_order_confirmation_template',function(){
				var name = jQuery("#wc_order_confirmation_template_name").val();
				var head = jQuery("#wc_order_confirmation_template_head").val();
				var body = jQuery("#wc_order_confirmation_template_body").val();
				wacr_create_tmp_cb(name, head, body);
			});
			jQuery(document).on('click', '#wc_order_complete_template',function(){
				var name = jQuery("#wc_order_complete_template_name").val();
				var head = jQuery("#wc_order_complete_template_head").val();
				var body = jQuery("#wc_order_complete_template_body").val();
				wacr_create_tmp_cb(name, head, body);
			});

			function wacr_create_tmp_cb(name, head, body){
				$.ajax( {
					type: "POST",
					url: wacr_ajax.ajaxurl,
					data: {
					  action: "wacr_create_temp_cb",
					  name: name,
					  head: head,
					  body: body
					},
					beforeSend: function() {
						swal.fire({
							html: '<h5>Loading...</h5>',
							showConfirmButton: false,
						});
					},
					success ( res )
					{	

					  if(res == '1'){
						  Swal.fire({
							  title: 'Template Created!',
							  text: 'Check Manage Templates to manage them.',
							  icon: 'success',
							  confirmButtonText: 'Okay'
							},)
					  }else{
						Swal.fire({
							title: 'Error',
							text: res,
							icon: 'error',
							confirmButtonText: 'Okay'
						  },)
					  }
					  
					},
					} );


			}









			///

	});
	
})( jQuery );

