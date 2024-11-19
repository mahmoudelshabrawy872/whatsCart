(function( $ ) {
	'use strict';
	$(document).ready(function() {
		
		jQuery(document).on('click', '.wacr-trigger-delete',function(){
			
			var wacr_abcartID = jQuery(this).val();
			$.ajax( {
				type: "POST",
				url: wacr_ajax.ajaxurl,
				data: {
				  action: "delete_abcarts",
				  wacr_abcartID: wacr_abcartID,
				},
				success ( res )
				{
                    Swal.fire( {
                        icon: "success",
                        html: "Abandoned Cart Deleted Successfully! ",
                        showConfirmButton: true,
                        timer: 1500,  
                      } ).then((result) => {
                            location.reload();
                        });
				},
			  } );
		  });
	});

})( jQuery );