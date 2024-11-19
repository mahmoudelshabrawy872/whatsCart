(function ($) {
	'use strict';
	jQuery(document).ready(function () {

		jQuery(document).on('click', '.wacr-trigger-delete', function () {
			var numItems = $('.wacr-trigger-delete').length;
			console.log(numItems);
			if(numItems>1){
				var drowID = $(this).attr('rowid');
				drowID = drowID -1;
				$("[rowId="+drowID+"]").show();
					var rowId = jQuery(this).attr("rowid");
					jQuery("table.wacr-trigger-dynamic .wacr-" + rowId).remove();		
			}
		});
		jQuery(document).on('click', '.wacr-trigger-add', function () {
			var rowId = jQuery(this).attr("rowid");	
			jQuery(".wacr-"+rowId+" .wacr-trigger-add").html('<span class="fa fa-spin fa-spinner" aria-hidden="true"></span>');		
			
			$(".wacr-trigger-add").hide();
			var drowID = $(this).attr('rowid');
			$(".wacr-trigger-delete").hide();
			$("[rowId="+drowID+"]").hide();
			var newrowId =  parseInt(rowId) + 1;
			var markup = '<tr id="wacrdynamictrggr" class="wacr-'+newrowId+'">';
				markup += '<td>';
				markup += '<select class="wacr-trigger-selectbox">';
				var myJSON = JSON.parse(wacr_ajax.wacr_op);
				$.each(myJSON, function(key, item) {
					markup += '<option value="'+ item +'">'+ item +'</option>';		
				});
				//load select box options
				markup += '</select>';	
				markup += '</td>';
				markup += '<td>';
				markup += '<input type="number" min="0" value=""/> Minutes.';
				markup += '</td>';
				markup += '<td>';
				markup += '<button rowid="'+newrowId+'" class="wacr-trigger-delete"><span class="fa fa-trash"></span></button>';
				markup += '</td>';
				markup += '<td>';
				markup += '<button rowid="'+newrowId+'" class="wacr-trigger-add"><span class="fa fa-plus"></b></button> ';
				markup += '</td>';
				markup += '</tr>';
			$(".wacr-"+rowId+" .wacr-trigger-add").html('<span class="fa fa-plus" aria-hidden="true"></span>');		

		var tableBody = jQuery( "table.wacr-trigger-dynamic .wacr-"+rowId );
		tableBody.after( markup );
		});
		jQuery(document).on('click', '.wacr_save_triggers', function () {
			var prepare_request = [];
			var validation_input_time = 1;
			jQuery('table.wacr-trigger-dynamic > tbody  > tr').each(function(index, tr) { 
				var trclassname = $(this).attr('class');
				var templateid = $('.'+trclassname+' select').val()
				var timedefined = $('.'+trclassname+' input').val()
				if(timedefined == '')
				{
					validation_input_time = 0;

				}
				prepare_request.push([templateid, timedefined]);
			});
			if(validation_input_time == 0)
			{
				Swal.fire( {
					icon: "error",
					html: "Please enter time",
					showConfirmButton: false,
					timer: 1500,
				} );
			}
			else
			{
				console.log(prepare_request);
				//load select box options
				jQuery.ajax( {
					url: wacr_ajax.ajaxurl,
					type: "POST",
					data: {
						action: "wacr_save_triggers_ajax",
						prepare_request:prepare_request
					},
					success: function ( response )
					{
						Swal.fire( {
							icon: "success",
							html: "Triggers Added Successfully! ",
							showConfirmButton: false,
							timer: 1500,	
						} ).then((result) => {
							// Reload the Page
							location.reload();
						  });
					},
				} );
			}

		});
	});
	
})(jQuery);

