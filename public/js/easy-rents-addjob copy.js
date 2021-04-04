(function ($) {
    'use strict';

    if (typeof jQuery === 'undefined') {
        throw new Error('Easy rents requires jQuery');
    }

    $('#loading_date').datepicker();
    $('#loading_time').timepicker();

    var locationSelected = '';
    $('.erdivision').on('change', function () {
        locationSelected = '';
        let cthis = $(this);

        if ($(this).val() !== '-1') {
            locationSelected += $(this).val();

            $.ajax({
                type: "post",
                url: addjob_ajaxurl.ajax_url,
                data: {
                    action: "pbget_districts_under_division",
                    division: cthis.val(),
                    nonce: addjob_ajaxurl.nonce
                },
                dataType: 'json',
                beforeSend: () => {
                    cthis.attr('disabled', true);
                    cthis.css('background', '#ff000047');
                },
                success: function (response) {
                    if (response) {
                        $.each(response, function (ind, elm) {
                            cthis.removeAttr('disabled');
                            cthis.css('background', 'transparent');

                            cthis.next('.erdistrict').append('<option class="addeditem" value="' + elm.district + '">' + elm.district + '</option>');
                            cthis.hide().next('.erdistrict').show();
                        });
                    }
                }
            });
        } else {
            $(this).parent().children('.backbtn').hide();
        }

    });

    $('.erdistrict').on('change', function () {
        if ($(this).val() !== '-1') {
            let cthis = $(this);
            locationSelected += ', ' + $(this).val();

            $.ajax({
                type: "post",
                url: addjob_ajaxurl.ajax_url,
                data: {
                    action: "pbget_p_stations_under_districts",
                    district: cthis.val(),
                    nonce: addjob_ajaxurl.nonce
                },
                dataType: 'json',
                beforeSend: () => {
                    cthis.attr('disabled', true);
                    cthis.css('background', '#ff000047');
                },
                success: function (response) {
                    if (response) {
                        $.each(response, function (ind, elm) {
                            cthis.removeAttr('disabled');
                            cthis.css('background', 'transparent');

                            cthis.next('.erp_station').append('<option class="addeditem" value="' + elm.p_station + '">' + elm.p_station + '</option>');
                            cthis.hide().next('.erp_station').show();
                        });
                    }
                }
            });
        }
    });


    $('.erp_station').on('change', function () {
        if ($(this).val() !== '-1') {
            locationSelected += ', ' + $(this).val() + ', ';
            
            $(this).hide().parent().children('.showinpdata').text(locationSelected);
            $(this).next('.locationinput').show().next('.backbtn').show().on('click', function () {
                locationSelected = '';
                $(this).prev('.locationinput').val('');
                $(this).parent().children('select').each(function () {
                    $(this).children('option:selected').prop("selected", false);
                    $(this).children('.addeditem').remove();
                });
                $(this).parent().children().hide().first().show();
            });
        }
    });


    $('#addjob').on('click', function (e) {
        e.preventDefault();
    
        var loc1 = $('#location_1').val();
        var loc2 = $('#location_2').val();
        var loc3 = $('#location_3').val();
        var unload = $('#unload_location').val();
        var loading_time = $('#loading_time').val();
        var loading_date = $('#loading_date').val();
        var truck_type = $('#truck_type').val();
        var goods_type = $('#goods_type').val();
        var goods_weight = $('#goods_weight').val();
        var er_labore = $('#er_labore').val();



        if (loc1 != "") {
            $('#location_1').parent().children('select').each(function () {
                $(this).css('border', '1px solid #ddd');
            });
            
            if (unload != "") {
                $('#unload_location').parent().children('select').each(function () {
                    $(this).css('border', '1px solid #ddd');
                });
                
                if (loading_time != "") {
                    $('#loading_time').parent().children('select').each(function () {
                        $(this).css('border', '1px solid #ddd');
                    });
                    if (loading_date != "") {
                        $('#loading_date').parent().children('select').each(function () {
                            $(this).css('border', '1px solid #ddd');
                        });
                        if (truck_type != "") {
                            $('#truck_type').parent().children('select').each(function () {
                                $(this).css('border', '1px solid #ddd');
                            });
                            if (goods_type != "") {
                                $('#goods_type').parent().children('select').each(function () {
                                    $(this).css('border', '1px solid #ddd');
                                });
                                if (goods_weight != "") {
                                    $('#goods_weight').parent().children('select').each(function () {
                                        $(this).css('border', '1px solid #ddd');
                                    });

                                    $.ajax({
                                        type: "post",
                                        url: addjob_ajaxurl.ajax_url,
                                        data: {
                                            action: "er_create_job",
                                            loc1:           loc1,
                                            loc2:           loc2,
                                            loc3:           loc3,
                                            unload:         unload,
                                            loading_time:   loading_time,
                                            loading_date:   loading_date,
                                            truck_type:     truck_type,
                                            goods_type:     goods_type,
                                            goods_weight:   goods_weight,
                                            er_labore:      er_labore,
                                            nonce:          addjob_ajaxurl.nonce
                                        },
                                        dataType: 'json',
                                        beforeSend: () => {
                                            $('#addjob').val('Processing...');
                                        },
                                        success: function (response) {
                                            $('#addjob').val('Place');
                                            if (response.redirect) {
                                                window.location.href = response.redirect
                                            }
                                            if (response.faild) {
                                                alert(response.faild)
                                            }
                                        }
                                    });


                                } else {
                                    $('#goods_weight').parent().children('select').each(function () {
                                        $(this).css('border', '1px solid red');
                                    });
                                }
                            } else {
                                $('#goods_type').parent().children('select').each(function () {
                                    $(this).css('border', '1px solid red');
                                });
                            }
                        } else {
                            $('#truck_type').parent().children('select').each(function () {
                                $(this).css('border', '1px solid red');
                            });
                        }
                    } else {
                        $('#loading_date').parent().children('select').each(function () {
                            $(this).css('border', '1px solid red');
                        });
                    }
                } else {
                    $('#loading_time').parent().children('select').each(function () {
                        $(this).css('border', '1px solid red');
                    });
                }
            } else {
                $('#unload_location').parent().children('select').each(function () {
                    $(this).css('border', '1px solid red');
                });
            }
        } else {
            $('#location_1').parent().children('select').each(function () {
                $(this).css('border', '1px solid red');
            });
        }
        
    });

        
})(jQuery);