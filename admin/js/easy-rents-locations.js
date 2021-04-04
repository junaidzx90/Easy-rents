(function ($) {
	'use strict';
	/**
	 * Locations page
	 * ==============
	 */
	// districts
	var districts = [];
	// p_station
	var p_stations = [];

	divisiondata();
	function divisiondata() {
		// divisions
		var divisions = [];
		$('.filterItems').autocomplete({
			source: divisions,
		});

		$('#divisionlocation').autocomplete({
			source: divisions,
		});
		$('#divisionlists').children().each(function () {
			divisions.push($(this).val());
		});
		
		
		$('#divisionlocation').on('keyup', function () {
			$(this).css('border', '1px solid transparent');
			if ($(this).val() != "") {
				$('#districtlocation').show();
			} else {
				$('#districtlocation').hide();
			}
		});

		// Get districts under disrtrict
		$('#divisionlocation').blur(function () {
			$.ajax({
				type: "post",
				url: locations_ajaxurl.ajax_url,
				data: {
					action: "get_districts_under_division",
					division: $('#divisionlocation').val(),
					nonce: locations_ajaxurl.nonce
				},
				dataType: 'json',
				success: function (response) {
					if (response) {
						$.each(response, function (ind, elm) {
							districts.push(elm.district);
						});
					}
				}
			});
		});
	}
	

	districtsdata();
	function districtsdata() {
		$('#districtlocation').autocomplete({
			source: districts,
		});

		$('#districtlocation').on('keyup', function () {
			$(this).css('border', '1px solid transparent');
			if ($(this).val() != "") {
				$('#p_stationlocation').show();
			} else {
				$('#p_stationlocation').hide();
			}
		});

		// Get districts under disrtrict
		$('#districtlocation').blur(function () {
			$.ajax({
				type: "post",
				url: locations_ajaxurl.ajax_url,
				data: {
					action: "get_p_stations_under_districts",
					district: $('#districtlocation').val(),
					nonce: locations_ajaxurl.nonce
				},
				dataType: 'json',
				success: function (response) {
					if (response) {
						$.each(response, function (ind, elm) {
							p_stations.push(elm.p_station);
						})
					}
				}
			});
		});
	}

	p_stationdata();
	function p_stationdata() {
		$('#p_stationlocation').autocomplete({
			source: p_stations,
		});

		$('#p_stationlocation').on('keyup', function () {
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

		let division = $('#divisionlocation').val();
		let district = $('#districtlocation').val();
		let p_station = $('#p_stationlocation').val();

		let slnum = parseInt($('.locations table tbody tr').last().children('.slnum').text());

		
		if (isNaN(slnum)) {
			ernumbers = ernumbers + 1;
		} else {
			ernumbers = slnum + 1;
		}

		if (division != '') {
			if (district != '') {
				if (p_station != '') {
					// Ajax call for store data in database
					$.ajax({
						type: "post",
						url: locations_ajaxurl.ajax_url,
						data: {
							action: 'addNewLocation',
							division: division,
							district: district,
							p_station: p_station,
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
								
								$('#divisionlocation').val('').css('border', '1px solid #7e8993');
								$('#districtlocation').val('').css('border', '1px solid #7e8993').hide();
								$('#p_stationlocation').val('').css('border', '1px solid #7e8993').hide();
							}
							if (response.faild) {
								alert(response.faild);
								btn.val('Add').removeAttr('disabled');

								$('#divisionlocation').val('').css('border', '1px solid #7e8993');
								$('#districtlocation').val('').css('border', '1px solid #7e8993').hide();
								$('#p_stationlocation').val('').css('border', '1px solid #7e8993').hide();
								districts = [];
								p_stations = [];
							}
						}
					});
				} else {
					$('#p_stationlocation').css('border', '1px solid red');
				}
			} else {
				$('#districtlocation').css('border', '1px solid red');
			}
		} else {
			$('#divisionlocation').css('border', '1px solid red');
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
