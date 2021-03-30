(function ($) {
	'use strict';

	/**
	 * Payment Page
	 * ============
	 */
	// Send message for payment
	$('.sendpaymentalert').on("click", function () {
		let driver_id = $(this).attr('data-driver');
		let amount = $(this).attr('data-amount');
		if (confirm("Are you sure!")) {
			$.ajax({
				type: "post",
				url: payment_ajaxurl.ajax_url,
				data: {
					action: 'send_sms_forpayment',
					driver_id: driver_id,
					amount: amount,
					nonce: payment_ajaxurl.nonce
				},
				cache: false,
				success: function (response) {
					alert(response)
				}
			});
		}
	});

	/**
	 * End Payment Page
	 * ================
	 */

})(jQuery);
