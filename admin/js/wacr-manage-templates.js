(function( $ ) {
	'use strict';
	$(document).ready(function() {

		if (window.location.href.indexOf("&value=deleted") > -1) {
			jQuery('#tab1').prop('checked', false); 
			jQuery('#tab3').prop('checked', true); 
		}
		$( "#tab1,#tab2,#tab3,#tab4,#tab5,#tab6" ).click(function() {
			var uri = window.location.toString();
			if (uri.indexOf("&value=deleted") > 0) {
				var clean_uri = uri.substring(0, uri.indexOf("&value=deleted"));
				window.history.replaceState({}, document.title, clean_uri);
			}
		});
		
		$("#wacr_manage_templates").DataTable();
		$("#wacr_abandoned_carts").DataTable();
		$("#wacr_temp_library").DataTable();
		var dataTable = $("#wacr_message_logs").DataTable({
			"columnDefs": [
				  {
					  "targets": [6],
					  "visible": false
				  }
			  ],
			  order: [[1, 'desc']],
		  });

		$('.wacr_status_dropdown').on('change', function(e){
		
			var status = $(this).val();
			$('.status-dropdown').val(status);
			console.log(status);
			dataTable.column(6).search(status).draw();
		  });
		  $('#wamc-select-logs, #wamc-select-logs2').on('click', function(e) {
			if($(this).is(':checked',true))  
			{
			  $(".sub_chk").prop('checked', true); 
			  $("#wamc-select-logs").prop('checked', true); 
			  $("#wamc-select-logs2").prop('checked', true); 
			}  
			else  
			{  
			  $(".sub_chk").prop('checked',false);
			  $("#wamc-select-logs").prop('checked',false); 
			  $("#wamc-select-logs2").prop('checked',false);   
			}  
		  });
		  jQuery('.delete_all').on('click', function(e) { 
			var allVals = [];  
				$(".sub_chk:checked").each(function() {  
				  allVals.push($(this).attr('data-id'));
				});  
				if(allVals.length ==0)  
				{  
				  Swal.fire({
					title: 'Error',
					text: 'Please select row.',
					icon: 'warning',
					confirmButtonText: 'Okay'
				  },)
				}  
				else {  
					jQuery.ajax( {
						url: wacr_ajax.ajaxurl,
						type: "POST",
						data: {
						  action: "wacr_delete_message_logs",
						  selected_logs:allVals,
						},
						success: function ( response )
						{
							// alert(response);
						  Swal.fire( {
							icon: "success",
							html: "Logs Deleted Successfully! ",
							showConfirmButton: false,
							timer: 1500,  
						  } ).then((result) => {
								var url = window.location.href;  
								url += '&value=deleted';
								window.location.href = url;
							});
						},
					} );
				 }  
			});
	});

})( jQuery );