(function( $ ) {
	'use strict';

	if (typeof jQuery === 'undefined') {
		throw new Error('Easy rents requires jQuery');
    }
    
    // Price box ignore empty value
    $('.bidbtn').on('click', function (e) {
        if ($('#myprice').val() == "") {
            e.preventDefault();
            $('#myprice').css('border', '1px solid red');
        } else {
            setTimeout(() => {
                $(this).attr('disabled', true);
            }, 100);
        }
    });


    $('#myprice').on("input", function () {
        $(this).css('border', '1px solid transparent');
        
        // Update commission with price
        let parcent = parseFloat($('.parcents').text());
        $('.commrate').text(parseFloat($(this).val() * parcent / 100));
        let commission = parseFloat($('.commrate').text());


        // Update price with commission
        $('.myrate').text(parseFloat($(this).val()));
        let myrate = parseFloat($('.myrate').text());
        $('.sumwithcomm').text(myrate + commission);


        if ($(this).val() == '') {
            $('.myrate').text(0);
            $('.sumwithcomm').text(0);
        }
        
    });

})( jQuery );
