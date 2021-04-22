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
					btn.text('Removing..');
				},
				success: function (response) {
					location.reload();
				}
			});
		} else {
			return false;
		}
	});

	// Cancel request
	$('.ignorerequest').on('click', function (e) {
		if (confirm("Are you sure! You really want to cancel?")) {
			let btn = $(this);
			e.preventDefault();
			let driver_id = $(this).parent().children('input[name="erd"]').val();
			let post_id = $(this).parent().children('input[name="erp"]').val();;
			$.ajax({
				type: "POST",
				url: er_profile_ajax.ajax_url,
				data: {
					action: 'ignorerequest',
					driver_id: driver_id,
					post_id: post_id,
					security: er_profile_ajax.security,
				},
				beforeSend: function () {
					btn.text('Ignoring..');
				},
				success: function (response) {
					location.reload();
				}
			});
		} else {
			return false;
		}
	});

	// Accept Request
	$('.acceptrequest').on('click', function (e) {
		if (confirm("Are you sure?")) {
			let btn = $(this);
			e.preventDefault();
			let driver_id = $(this).parent().children('input[name="erd"]').val();
			let post_id = $(this).parent().children('input[name="erp"]').val();
			let offer_id = $(this).attr("data-id");
			$.ajax({
				type: "POST",
				url: er_profile_ajax.ajax_url,
				data: {
					action: 'acceptrequest',
					driver_id: driver_id,
					post_id: post_id,
					offer_id: offer_id,
					security: er_profile_ajax.security,
				},
				beforeSend: function () {
					btn.text('Accepting..');
				},
				success: function (response) {
					location.reload();
				}
			});
		} else {
			return false;
		}
	});

	//Request for Finishedjob
	$('.finishedjob').on('click', function (e) {
		if (confirm("Are you sure?")) {
			let btn = $(this);
			e.preventDefault();
			let customer_id = $(this).parent().children('input[name="erc"]').val();
			let post_id = $(this).parent().children('input[name="erp"]').val();
			let offer_id = $(this).attr("data-id");
			$.ajax({
				type: "POST",
				url: er_profile_ajax.ajax_url,
				data: {
					action: 'requestforfinishedjob',
					customer_id: customer_id,
					post_id: post_id,
					offer_id: offer_id,
					security: er_profile_ajax.security,
				},
				beforeSend: function () {
					btn.text('Requesting..');
				},
				success: function (response) {
					location.reload();
				}
			});
		} else {
			return false;
		}
	});

	//Finished Confirm
	$('.finishedconfirm').on('click', function (e) {
		if (confirm("Are you sure?")) {
			let btn = $(this);
			e.preventDefault();
			let driver_id = $(this).parent().children('input[name="erd"]').val();
			let post_id = $(this).parent().children('input[name="erp"]').val();
			let offer_id = $(this).attr("data-id");
			$.ajax({
				type: "POST",
				url: er_profile_ajax.ajax_url,
				data: {
					action: 'finishedconfirmed',
					driver_id: driver_id,
					post_id: post_id,
					offer_id: offer_id,
					security: er_profile_ajax.security,
				},
				beforeSend: function () {
					btn.text('Accepting..');
				},
				success: function (response) {
					location.reload();
				}
			});
		} else {
			return false;
		}
	});

	// Prepare payment method
	$('#selectpaymethod').on("change", function () {
		let method = $(this).val();
		$('.paymentform').children().each(function () {
			$(this).hide();
		});
		if ($(this).val() != "") {
			$('#' + method).show();
		}
		$('.backbtn').on("click", function () {
			$('.paymentform').children().each(function () {
				$(this).hide();
			});
			$('.paymethodselect').show();
		});
	});

	settingsPage();
	function settingsPage() {
	
		// Location select
		$('.erdivision').on('change', function (e) {
			let cthis = $(this);

			if ($(this).val() !== '-1') {
				$(this).css('border', '1px solid #ddd');

				$.ajax({
					type: "post",
					url: er_profile_ajax.ajax_url,
					data: {
						action: "pbget_districts_under_division",
						division: cthis.val(),
						nonce: er_profile_ajax.nonce
					},
					dataType: 'json',
					beforeSend: () => {
						cthis.attr('disabled', true);
						cthis.css('background', '#ff000047');
						cthis.next('.erdistrict').children('.addeditem').remove();
					},
					success: function (response) {
						if (response) {
							$.each(response, function (ind, elm) {
								cthis.removeAttr('disabled');
								cthis.css('background', 'transparent');

								cthis.next('.erdistrict').append('<option class="addeditem" value="' + elm.district + '">' + elm.district + '</option>');
							});
						}
					}
				});
			} else {
				$(this).css('border', '1px solid red');
				return false;
			}
		});

		$('.erdistrict').on('change', function () {
			if ($(this).val() !== '-1') {
				$(this).css('border', '1px solid #ddd');
				let cthis = $(this);

				$.ajax({
					type: "post",
					url: er_profile_ajax.ajax_url,
					data: {
						action: "pbget_p_stations_under_districts",
						district: cthis.val(),
						nonce: er_profile_ajax.nonce
					},
					dataType: 'json',
					beforeSend: () => {
						cthis.attr('disabled', true);
						cthis.css('background', '#ff000047');
						cthis.next('.erp_station').children('.addeditem').remove();
					},
					success: function (response) {
						if (response) {
							$.each(response, function (ind, elm) {
								cthis.removeAttr('disabled');
								cthis.css('background', 'transparent');

								cthis.next('.erp_station').append('<option class="addeditem" value="' + elm.p_station + '">' + elm.p_station + '</option>');
							});
						}
					}
				});
			} else {
				$(this).css('border', '1px solid red');
				return false;
			}
		});

		$('.erp_station').on('change', function () {

			if ($(this).val() !== '-1') {
				$(this).css('border', '1px solid #ddd');
				$(this).css('background', 'transparent');
			} else {
				$(this).css('border', '1px solid red');
				return false;
			}
		});

		// IMAGE UPLOAD
		// 
		function imagechange(elem, input, targetName) {
			let imgName = elem
				.val()
				.replace(/.*(\/|\\)/, "");
			let exten = imgName.substring(imgName.lastIndexOf(".") + 1);
			let expects = ["jpg", "jpeg", "png", "PNG"];
	
			if (expects.indexOf(exten) == -1) {
				return false;
			}
	
			imgURL(input, targetName);
		}

		let imgURL = function (input, targetName) {
			
			if (input.files && input.files[0]) {
				let reader = new FileReader();
				reader.onload = function (e) {
					targetName.css(
						"background-image",
						"linear-gradient(0deg, rgb(27 27 27 / 38%), rgb(27 27 27 / 38%)),url(" + e.target.result + ")"
					);
					targetName.children('label').css('background', '#00bfff2e');
				};
	
				reader.readAsDataURL(input.files[0]);
			}
		}

		// PROFILE IMAGE
		$('#avatar').on('change', function () {
			imagechange($(this), this, $('.avatarImg'));
			if ($(this).val() == "") {
				$('.avatarImg').css(
					"background-image",
					"url(https://lh3.googleusercontent.com/proxy/4OgnjMEbQelGekOQCuy2Glqeh65ZaOiIDupxoeKHczvUHzsUhWirm2e4osYxODAjJH8L_UIZF9fDR26jIOwg8OuJZJNIe5p_UcWXcgofJ_2yNLvbAialnPkfDl_RE2VujxwSIM6_14d3-PPgdAylHw)"
				);
			}
		});

		// FRON SIDE NID
		$('#nidfront').on('change', function () {
			imagechange($(this), this, $('.frontImgShow'));
			if ($(this).val() == "") {
				$('.frontImgShow').css(
					"background-image", ""
				);
			}
		});

		// BACK SIDE NID
		$('#nidback').on('change', function () {
			imagechange($(this), this, $('.backImgShow'));
			if ($(this).val() == "") {
				$('.backImgShow').css(
					"background-image", ""
				);
			}
		});

		// TRUCK IMAGE
		$('#truckImg').on('change', function () {
			imagechange($(this), this, $('.truckshow'));
			if ($(this).val() == "") {
				$('.truckshow').css(
					"background-image", ""
				);
			}
		});


		$('.hasrefercode').on('click', function (e) {
			e.preventDefault();
			$(this).next('.refer_inp').toggle(() => {
				if ($('.hasrefercode').text() == "I have Refer Code") {
					$('.hasrefercode').text("No Code");
				} else {
					$('#reffer').val("");
					$('.hasrefercode').text("I have Refer Code");
				}
			});
		});

		// Remove -1 default value from all select inputs
		$('select').each(function () {
			$(this).children('option').first().val("");
		});

		// NOTICEBOARD TEXTS
		noticableinfo();
		function noticableinfo() {
			$('.username').mouseover(function () {
				$('.noticeboard').html('Type your valid Name, <br> <span class="info"> Example: Md Junayed </span>');
			});
	
			$('.emailaddr').mouseover(function () {
				$('.noticeboard').html('Type your valid email address, <br> <span class="info">Example: ( example@gmail.com )</span>');
			});
	
			$('.phonenum').mouseover(function () {
				$('.noticeboard').html('<span class="dangerInfo">You can\'t change your primary phone number,</span> <br> If you need to change, <span class="info">Please contact <br>( contact@bahak.com.bd )</span>');
			});
	
			$('.bkashnom').mouseover(function () {
				$('.noticeboard').html('Make sure this number is valid, Also try to give <span class="info">personal bkash number</span>, <br> <small>Your all information is secure in our server!</small>');
			});
	
			$('.nidcardadd').mouseover(function () {
				$('.noticeboard').html('Our team will confirmed you if this document is valid, Try to upload clear photo of your NID card, Otherwise submision will be <span class="dangerInfo">rejected!</span> <br> <small>Your all information is secure in our server!</small>');
			});
	
			$('.presentAddrs').mouseover(function () {
				$('.noticeboard').html('Type your valid present address, It will help you for any risking moment!');
			});
	
			$('.permanentAddrs').mouseover(function () {
				$('.noticeboard').html('Make Sure permanent address is valid, It will help you for any risking moment!');
			});
	
			$('.billingAddrs').mouseover(function () {
				$('.noticeboard').html('We need your valid billing address, <span class="dangerInfo">It can be effect your payment if you type wrong address!</span>');
			});
	
			$('.truckAdd').mouseover(function () {
				$('.noticeboard').html('Make sure truck number shown clearly with your photo, Clear and naturul image expected!');
			});

			$('.referCode').mouseover(function () {
				$('.noticeboard').html('If you have any referral code, then type here!');
			});

			$('.submit-button').mouseover(function () {
				$('.noticeboard').html('Once submitted it can be held for a maximum of 24 hours, If valid-- submission will be accepted!');
			});
		}
	}

	
})(jQuery);
