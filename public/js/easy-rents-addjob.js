(function( $ ) {
	'use strict';

	if (typeof jQuery === 'undefined') {
		throw new Error('Easy rents requires jQuery');
    }

    // Add job form validation
    $('input[name="addjob"]').on('click', function (e) {
        $('.erform_items').find('.required').each(function (ind, elm) {
            let inputdata = $(this).children('input,select').val();

            if (inputdata == "") {
                e.preventDefault();
                $(this).children('input,select').css('border', '1px solid red')
                $('.eraddjobformwarning').text('Please Fill all fields');
            }
        });
    });

    $('.erform_items').find('.input-group').each(function (ind, elm) {
        $(this).children('select').on('change', function () {
            if ($(this).val() != '') {
                $(this).css('border', 'none');
            } 
        });

        $(this).children('input').on('keyup', function () {
            if ($(this).val() != '') {
                $(this).css('border', 'none');
            } 
        });
    });

})( jQuery );