(function( $ ) {
	'use strict';
	$('.delete_addr').on("click", function (e) {
		if (!confirm("Are you sure!"))
			e.preventDefault();
	});

	// Send message for payment
	$('.sendpaymentalert').on("click", function () {
		let driver_id = $(this).attr('data-driver');
		let amount = $(this).attr('data-amount');
		if (confirm("Are you sure!")) {
			$.ajax({
				type: "post",
				url: admin_ajaxurl.ajax_url,
				data: {
					action: 'send_sms_forpayment',
					driver_id: driver_id,
					amount: amount
				},
				cache: false,
				success: function (response) {
					alert(response)
				}
			});
		}
	});
})( jQuery );
