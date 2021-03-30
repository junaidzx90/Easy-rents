(function ($) {
	'use strict';
	/**
	 * Locations page
	 * ==============
	 */
	// Cities
	var cities = [];
	// Union
	var unions = [];

	districtdata();
	function districtdata() {
		// Districs
		var districs = [];
		$('.filterItems').autocomplete({
			source: districs,
		});

		$('#districtlocation').autocomplete({
			source: districs,
		});
		$('#districtlists').children().each(function () {
			districs.push($(this).val());
		});
		
		
		$('#districtlocation').on('keyup', function () {
			$(this).css('border', '1px solid transparent');
			if ($(this).val() != "") {
				$('#citylocation').show();
			} else {
				$('#citylocation').hide();
			}
		});

		// Get cities under disrtrict
		$('#districtlocation').blur(function () {
			$.ajax({
				type: "post",
				url: locations_ajaxurl.ajax_url,
				data: {
					action: "get_cities_under_district",
					district: $('#districtlocation').val(),
					nonce: locations_ajaxurl.nonce
				},
				dataType: 'json',
				success: function (response) {
					if (response) {
						$.each(response, function (ind, elm) {
							cities.push(elm.city);
						});
					}
				}
			});
		});
	}
	

	citiesdata();
	function citiesdata() {
		$('#citylocation').autocomplete({
			source: cities,
		});

		$('#citylocation').on('keyup', function () {
			$(this).css('border', '1px solid transparent');
			if ($(this).val() != "") {
				$('#unionlocation').show();
			} else {
				$('#unionlocation').hide();
			}
		});

		// Get cities under disrtrict
		$('#citylocation').blur(function () {
			$.ajax({
				type: "post",
				url: locations_ajaxurl.ajax_url,
				data: {
					action: "get_unions_under_cities",
					city: $('#citylocation').val(),
					nonce: locations_ajaxurl.nonce
				},
				dataType: 'json',
				success: function (response) {
					if (response) {
						$.each(response, function (ind, elm) {
							unions.push(elm.union);
						})
					}
				}
			});
		});
	}

	uniondata();
	function uniondata() {
		$('#unionlocation').autocomplete({
			source: unions,
		});

		$('#unionlocation').on('keyup', function () {
			$(this).css('border', '1px solid transparent');
		});
	}

	function getAllTableDataForRefresh() {
		$.ajax({
			type: "post",
			url: locations_ajaxurl.ajax_url,
			data: {
				action: 'get_all_table_data_for_refresh'
			},
			success: function (response) {
				$('.locations table tbody').html(response);
			}
		});
	}

	var ernumbers = 0;
	$('input[name="addlocation"]').on("click", function (e) {
		e.preventDefault();
		let btn = $(this);

		let distric = $('#districtlocation').val();
		let city = $('#citylocation').val();
		let union = $('#unionlocation').val();

		let slnum = parseInt($('.locations table tbody tr').last().children('.slnum').text());

		
		if (isNaN(slnum)) {
			ernumbers = ernumbers + 1;
		} else {
			ernumbers = slnum + 1;
		}

		if (distric != '') {
			if (city != '') {
				if (union != '') {
					// Ajax call for store data in database
					$.ajax({
						type: "post",
						url: locations_ajaxurl.ajax_url,
						data: {
							action: 'addNewLocation',
							distric: distric,
							city: city,
							union: union,
							nonce: locations_ajaxurl.nonce
						},
						dataType: "json",
						cache: false,
						beforeSend: () => {
							btn.val('Adding...').prop('disabled', true);
						},
						success: function (response) {
							if (response.success) {
								btn.val('Add').removeAttr('disabled');
								// Refresh data
								getAllTableDataForRefresh();
								
								$('#districtlocation').val('').css('border', '1px solid #7e8993');
								$('#citylocation').val('').css('border', '1px solid #7e8993').hide();
								$('#unionlocation').val('').css('border', '1px solid #7e8993').hide();
							}
							if (response.faild) {
								alert(response.faild);
								btn.val('Add').removeAttr('disabled');

								$('#districtlocation').val('').css('border', '1px solid #7e8993');
								$('#citylocation').val('').css('border', '1px solid #7e8993').hide();
								$('#unionlocation').val('').css('border', '1px solid #7e8993').hide();
								cities = [];
								unions = [];
							}
						}
					});
				} else {
					$('#unionlocation').css('border', '1px solid red');
				}
			} else {
				$('#citylocation').css('border', '1px solid red');
			}
		} else {
			$('#districtlocation').css('border', '1px solid red');
		}
	});

	// Delete Location
	$(document).on("click",'.delete_addr', function (e) {
		if (confirm("Are you sure!")) {
			let btn = $(this);
			let addrId = $(this).attr('data-id');
			$.ajax({
				type: "post",
				url: locations_ajaxurl.ajax_url,
				data: {
					action:  'delete_easy_rents_location',
					addId: addrId,
					nonce: locations_ajaxurl.nonce
				},
				beforeSend: () => {
					btn.parent('td').parent('tr').css('background', '#ff000047');
				},
				success: function (response) {
					getAllTableDataForRefresh();
				}
			});
		}
		e.preventDefault();
	});

	/**
	 * Locations List End
	 * ==================
	 */

})(jQuery);
