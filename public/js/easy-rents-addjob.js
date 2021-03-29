(function ($) {
	'use strict';

	if (typeof jQuery === 'undefined') {
		throw new Error('Easy rents requires jQuery');
    }

    $('#loading_date').datepicker();
    $('#loading_time').timepicker();


    $('.erdistrict').on('change', function () {
        if ($(this).val() !== '-1') {
            $(this).hide().next('.ercity').show().on('change', function () {
                if ($(this).val() !== '-1') {
                    $(this).hide().next('.erunion').show().on('change', function () {
                        if ($(this).val() !== '-1') {
                            $(this).hide().next('.locationinput').show().next('.backbtn').show().on('click', function () {
                                $(this).prev('input').val('');
                                $(this).parent().children('select').each(function () {
                                    $(this).children('option:selected').prop("selected", false);
                                });
                                $(this).parent().children().hide().first().show();
                            });
                        }
                    });
                }
            });
        } else {
            $(this).parent().children('.backbtn').hide();
        }

        var availableTags = [
            "ActionScript",
            "AppleScript",
            "Asp",
            "BASIC",
            "C",
            "C++",
            "Clojure",
            "COBOL",
            "ColdFusion",
            "Erlang",
            "Fortran",
            "Groovy",
            "Haskell",
            "Java",
            "JavaScript",
            "Lisp",
            "Perl",
            "PHP",
            "Python",
            "Ruby",
            "Scala",
            "Scheme"
          ];

        $('.locationinput').autocomplete({
            source: availableTags,
        });
    });
    

    
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
})( jQuery );