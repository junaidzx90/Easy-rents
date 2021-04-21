(function ($) {
    'use strict';

    if (typeof jQuery === 'undefined') {
        throw new Error('Easy rents requires jQuery');
    }

    $('#loading_date').datepicker({
        onSelect: function (d, i) {
            $(this).css('border', '1px solid #ddd');
        }
    });
    $('#loading_time').timepicker({
        change: function () {
            $(this).css('border', '1px solid #ddd');
        }
    });

    $('.erdivision').on('change', function (e) {
        let cthis = $(this);

        if ($(this).val() !== '-1') {
            $(this).css('border', '1px solid #ddd');

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
        } else {
            $(this).css('border', '1px solid red');
            return false;
        }
    });

    $('.erform_items').children().find('input,select').on('change', function () {
        if ($(this).val() != "") {
            $(this).css('border', '1px solid #ddd');
        }
    });

    // CONTROL MORE LOCATIONS VALUES
    $('select.loc2').on('change', function () {
        $(this).nextAll('select').children('option:selected').removeAttr('selected');
        $(this).nextAll('select').children('.addeditem').remove();
        $(this).parent().children('input').val('');
    })
    $('select.loc3').on('change', function () {
        $(this).nextAll('select').children('option:selected').removeAttr('selected');
        $(this).nextAll('select').children('.addeditem').remove();
        $(this).parent().children('input').val('');
    })

    // SUBMIT DATA TO DATABASE
    $('#addjob').on('click', function (e) {
        e.preventDefault();

        $('.loc1').each(function () {
            if ($(this).val() == "" || $(this).val() == "-1") {
                $(this).css('border', '1px solid red');
                return false;
            } else {
                $(this).css('border', '1px solid #ddd');
                return true;
            }
        });


        let moreloc = true;
        $('.loc2').parent().children('select,input').each(function () {
            if ($(this).val() !== "-1") {
                if ($(this).next().val() == '-1' || $(this).next().val() == '') {
                    $(this).next().css('border', '1px solid red');
                    moreloc = false;
                }
            }
        });

        $('.loc3').parent().children('select,input').each(function () {
            if ($(this).val() !== "-1") {
                if ($(this).next().val() == '-1' || $(this).next().val() == '') {
                    $(this).next().css('border', '1px solid red');
                    moreloc = false;
                }
            }
        });


        $('.unload_loc').each(function () {
            if ($(this).val() == "" || $(this).val() == "-1") {
                $(this).css('border', '1px solid red');
                return false;
            } else {
                $(this).css('border', '1px solid #ddd');
                return true;
            }
        });

        // LOCATION ONE
        var loc1 = $('.erdivision.loc1').val() + ', ' + $('.erdistrict.loc1').val() + ', ' + $('.erp_station.loc1').val() + ', ' + $('.locationinput.loc1').val();

        // LOCATION TWO
        var loc2 = '';
        if ($('.erdivision.loc2').val() !== '-1' && $('.erdistrict.loc2').val() !== '-1' && $('.erp_station.loc2').val() !== '-1') {
            loc2 = $('.erdivision.loc2').val() + ', ' + $('.erdistrict.loc2').val() + ', ' + $('.erp_station.loc2').val() + ', ' + $('.locationinput.loc2').val();
        }

        //LOCATION THREE
        var loc3 = '';
        if ($('.erdivision.loc3').val() !== '-1' && $('.erdistrict.loc3').val() !== '-1' && $('.erp_station.loc3').val() !== '-1') {
            loc3 = $('.erdivision.loc3').val() + ', ' + $('.erdistrict.loc3').val() + ', ' + $('.erp_station.loc3').val() + ', ' + $('.locationinput.loc3').val();
        }

        // UNLOAD LOCATION
        var unload = $('.erdivision.unload_loc').val() + ', ' + $('.erdistrict.unload_loc').val() + ', ' + $('.erp_station.unload_loc').val();

        var loading_time = $('#loading_time').val();
        var loading_date = $('#loading_date').val();
        var truck_type = $('#truck_type').val();
        var goods_type = $('#goods_type').val();
        var goods_weight = $('#goods_weight').val();
        var er_labore = $('#er_labore').val();
        var er_goodssizes = $('#er_goodssizes').val();


        if ($('#loading_time').val() != '') {
            $('#loading_time').css('border', '1px solid #ddd');

            if ($('#loading_date').val() != '') {
                $('#loading_date').css('border', '1px solid #ddd');

                if ($('#truck_type').val() != '') {
                    $('#truck_type').css('border', '1px solid #ddd');

                    if ($('#goods_type').val() != '') {
                        $('#goods_type').css('border', '1px solid #ddd');

                        if ($('#goods_weight').val() != '') {
                            $('#goods_weight').css('border', '1px solid #ddd');

                            if ($('.erdivision.loc1').val() !== '-1' && $('.erdistrict.loc1').val() !== '-1' && $('.erp_station.loc1').val() !== '-1' && $('.erdivision.unload_loc').val() !== '-1' && $('.erdistrict.unload_loc').val() !== '-1' && $('.erp_station.unload_loc').val() !== '-1') {
                                if (moreloc == true) {
                                  
                                    $.ajax({
                                        type: "post",
                                        url: addjob_ajaxurl.ajax_url,
                                        data: {
                                            action: "er_create_job",
                                            loc1: loc1,
                                            loc2: loc2,
                                            loc3: loc3,
                                            unload: unload,
                                            loading_time: loading_time,
                                            loading_date: loading_date,
                                            truck_type: truck_type,
                                            goods_type: goods_type,
                                            goods_weight: goods_weight,
                                            er_labore: er_labore,
                                            er_goodssizes:  er_goodssizes,
                                            nonce: addjob_ajaxurl.nonce
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
                                     
                                }

                            }
                        } else {
                            $('#goods_weight').css('border', '1px solid red');
                        }
                    } else {
                        $('#goods_type').css('border', '1px solid red');
                    }
                } else {
                    $('#truck_type').css('border', '1px solid red');
                }
            } else {
                $('#loading_date').css('border', '1px solid red');
            }
        } else {
            $('#loading_time').css('border', '1px solid red');
        }
    });
})(jQuery);