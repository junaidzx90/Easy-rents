(function ($) {
	'use strict';

	if (typeof jQuery === 'undefined') {
		throw new Error('Easy rents requires jQuery');
    }

    $('#location_1').select2({
        placeholder: "Select a location",
        theme: "classic"
    });
    $('#location_2').select2({
        placeholder: "Select a location",
        theme: "classic"
    });
    $('#location_3').select2({
        placeholder: "Select a location",
        theme: "classic"
    });
    $('#unload_location').select2({
        placeholder: "Select a location",
        theme: "classic"
    });

    // Add job form validation
    $('input[name="addjob"]').on('click', function (e) {
        $('.erform_items').find('.required').each(function (ind, elm) {
            let inputdata = $(this).children('input,select').val();

            if (inputdata == "") {
                e.preventDefault();
                $(this).children('select').next('.select2').css('border', '1px solid red');
                $(this).children('input,select').css('border', '1px solid red');
            } else {
                setTimeout(() => {
                    $('.addjob').css('cursor', 'not-allowed');
                    $('.addjob').attr("disabled",true);
                }, 5);
            }
        });
    });

    $('.erform_items').find('.input-group').each(function (ind, elm) {
        
        $(this).children('select').on('change', function () {
            var loc1 = $('#location_1').val();
            var unload = $('#unload_location').val();
            var loading_time = $('#loading_time').val();
            var loading_date = $('#loading_date').val();
            var truck_type = $('#truck_type').val();
            var goods_type = $('#goods_type').val();
            var goods_weight = $('#goods_weight').val();


            if ($(this).val() != '') {
                $(this).next('.select2').css('border', 'none');
                $(this).css('border', 'none');
            }

            if (loc1 != "" && unload != "" && loading_time != "" && loading_date != "" && truck_type != "" && goods_type != "" && goods_weight != "") {
                $('.addjob').css('cursor', 'pointer');
                $('.addjob').removeAttr("disabled");
            } else {
                $('.addjob').css('cursor', 'not-allowed');
                $('.addjob').attr("disabled",true);
            }
        });

        $(this).children('input').on('keyup', function () {
            if ($(this).val() != '') {
                $(this).css('border', 'none');
            }
        });
        
        $('#loading_time').change(function () {
            var loc1 = $('#location_1').val();
            var unload = $('#unload_location').val();
            var loading_time = $('#loading_time').val();
            var loading_date = $('#loading_date').val();
            var truck_type = $('#truck_type').val();
            var goods_type = $('#goods_type').val();
            var goods_weight = $('#goods_weight').val();
            

            if (loc1 != "" && unload != "" && loading_time != "" && loading_date != "" && truck_type != "" && goods_type != "" && goods_weight != "") {
                $('.addjob').css('cursor', 'pointer');
                $('.addjob').removeAttr("disabled");
            } else {
                $('.addjob').css('cursor', 'not-allowed');
                $('.addjob').attr("disabled",true);
            }
        })
        $('#loading_date').change(function () {
            var loc1 = $('#location_1').val();
            var unload = $('#unload_location').val();
            var loading_time = $('#loading_time').val();
            var loading_date = $('#loading_date').val();
            var truck_type = $('#truck_type').val();
            var goods_type = $('#goods_type').val();
            var goods_weight = $('#goods_weight').val();
            

            if (loc1 != "" && unload != "" && loading_time != "" && loading_date != "" && truck_type != "" && goods_type != "" && goods_weight != "") {
                $('.addjob').css('cursor', 'pointer');
                $('.addjob').removeAttr("disabled");
            } else {
                $('.addjob').css('cursor', 'not-allowed');
                $('.addjob').attr("disabled",true);
            }
        })

        $(this).children('input').change(function () {
            var loc1 = $('#location_1').val();
            var unload = $('#unload_location').val();
            var loading_time = $('#loading_time').val();
            var loading_date = $('#loading_date').val();
            var truck_type = $('#truck_type').val();
            var goods_type = $('#goods_type').val();
            var goods_weight = $('#goods_weight').val();
            

            if (loc1 != "" && unload != "" && loading_time != "" && loading_date != "" && truck_type != "" && goods_type != "" && goods_weight != "") {
                $('.addjob').css('cursor', 'pointer');
                $('.addjob').removeAttr("disabled");
            } else {
                $('.addjob').css('cursor', 'not-allowed');
                $('.addjob').attr("disabled",true);
            }
        });
    });
    

})( jQuery );