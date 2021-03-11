// profile tabs
function er_transform(elem,target) {
	var element = document.getElementById(elem);
	var old = document.querySelectorAll('.tabelem');
	var btns = document.querySelectorAll('.tabs button');

	btns.forEach(elem => {
		elem.classList.remove('erbtnactive');
	});
	target.classList.add('erbtnactive');

	old.forEach(element => {
		element.style.display = 'none';
	});
	element.style.display = 'block';
}

(function ($) {
	'use strict';

	if (typeof jQuery === 'undefined') {
		throw new Error('Easy rents requires jQuery');
	}

	$('.removejob').on('click', function (e) {
		if (confirm("Are you sure! You really want to remove?")) {
			let btn = $(this);
			e.preventDefault();
			let customer_id = $(this).parent().children('input[name="erc"]').val();
			let post_id = $(this).parent().children('input[name="erp"]').val();;
			$.ajax({
				type: "POST",
				url: er_profile_ajax.ajax_url,
				data: {
					action: 'remove_jobfromcart',
					customer_id: customer_id,
					post_id: post_id,
					security: er_profile_ajax.security,
				},
				beforeSend: function () {
					btn.text('Removing');
				},
				success: function (response) {
					location.reload();
				}
			});
		} else {
			return false;
		}
	});

})( jQuery );
