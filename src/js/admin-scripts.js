(function($) {
    'use strict';

    /**
     * All of the code for your admin-facing JavaScript source
     * should reside in this file.
     *
     * Note: It has been assumed you will write jQuery code here, so the
     * $ function reference has been prepared for usage within the scope
     * of this function.
     *
     * This enables you to define handlers, for when the DOM is ready:
     *
     * $(function() {
     *
     * });
     *
     * When the window is loaded:
     *
     * $( window ).load(function() {
     *
     * });
     *
     * ...and/or other possibilities.
     *
     * Ideally, it is not considered best practise to attach more than a
     * single DOM-ready or window-load handler for a particular page.
     * Although scripts in the WordPress core, Plugins and Themes may be
     * practising this, we should strive to set a better example in our own work.
     */

    $(document).ready(function() {
        //Adding noconflict instance to avoid conflict with other plugins datepicker  
        if (!$.fn.bootstrapDatePicker && $.fn.datepicker && $.fn.datepicker.noConflict) {
            var datepicker = $.fn.datepicker.noConflict();
            $.fn.bootstrapDatePicker = datepicker;
        }

        $(".tablesorter").tablesorter();
        var loading_spinner = '<div class="lds-css ng-scope"><div class="lds-spinner" style="100%;height:100%"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div></div>';
        // $(".wpeb_tooltip").hover(function() {
        $(".wpeb_tooltip").on('mouseenter mouseleave', function() {
            var _this = $(this);
            _this.find('.wpeb_tooltipcontent').html(loading_spinner);
            var data = {
                'action': 'pull_attendees',
                'event_id': _this.attr('alt'),
            };


            // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
            jQuery.post(ajaxurl, data, function(response) {
                if (response != 'false') {
                    _this.find('.wpeb_tooltipcontent').html(response);
                }
            });
        });
        $(".documentation-accordion").accordion({
            heightStyle: "content",
            active: false,
            collapsible: true
        });

        $(function() {
            $('.colorPicker').wpColorPicker();
        });


        /* Initializing select2 for location list*/
        $(".sel_event_location").select2({
            placeholder: addNewLocationPlaceholder,
            allowClear: true,
        });
        /* Initializing select2 for country list */
        $(".sel_location_country").select2({
            placeholder: country_trans,
            allowClear: true,
        });
        /* To show add location fields in event meta */
        $('.show_location').on('click', function() {
            $(".div_event_location").slideDown('slow');
            /* Initializing select2 for location list*/
            $(".sel_event_region").select2({
                placeholder: region_trans,
                allowClear: true,
            });
            $(".sel_location_country").select2({
                placeholder: country_trans,
                allowClear: true,
            });
        });
        /* creates locations from event meta field
        Call back function can find in includes/functions.php
        */
        $('#btn_add_location').on('click', function() {
            var error_status = false;
            var txt_location_title = $('#txt_location_title').val();
            var txt_location_street = $('#txt_location_street').val();
            var txt_location_zip = $('#txt_location_zip').val();
            var txt_location_city = $('#txt_location_city').val();
            var sel_location_country = $('#sel_location_country').val();
            var sel_event_region = $('.sel_event_region').val();
            if (!txt_location_title) {
                $('#txt_location_title').addClass('txt-error');
                error_status = true;
            }
            if (error_status == false) {
                var data = {
                    'action': 'create_event_location',
                    'txt_location_title': txt_location_title,
                    'txt_location_street': txt_location_street,
                    'txt_location_zip': txt_location_zip,
                    'txt_location_city': txt_location_city,
                    'sel_location_country': sel_location_country,
                    'sel_event_region': sel_event_region
                };

                // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
                jQuery.post(ajaxurl, data, function(response) {
                    var data = JSON.parse(response);
                    $(".sel_event_location").append($('<option>', {
                        value: data.location_id,
                        text: data.location_title
                    }));
                    $(".sel_event_location").val(data.location_id);
                    $(".div_event_location input[type='text']").val('');
                    $(".sel_location_country").val('').trigger('change')
                    $(".div_event_location").slideUp('slow');
                });
            }
        });
        $('#btn_cancel_location').on('click', function() {
            $(".div_event_location input[type='text']").val('');
            $(".sel_location_country").val('').trigger('change')
            $(".div_event_location").slideUp('slow');
        });
        $('.update_event_customers').on('click', function() {
            //console.log($('.sel_event_customers').val());
            //alert(event_id);
            if ($('.sel_event_customers').val()) {
                var data = {
                    'action': 'assing_customer_to_event',
                    'customers': $('.sel_event_customers').val(),
                    'event_id': event_id,
                };
                jQuery.post(ajaxurl, data, function(response) {
                    alert(user_message);
                    //$(".sel_event_customers").select2("val", "");
                    $('.sel_event_customers').val('').trigger("change");
                    $(".sel_event_customers option").remove();
                    $('.update_event_customers').closest("form").submit();
                    //input#publish
                });
            }
        });

        $(document).on("click", "input[type='text'], input[type='email'], input[type='url']", function() {
            $(this).removeClass('txt-error');
        });

        /* Initializing select2 for location list*/
        $(".sel_event_manager").select2({
            placeholder: addNewManagerPlaceholder,
            allowClear: true,
        });
        /* To show add event_manager fields in event meta */
        $('.show_event_manager').on('click', function() {
            $(".div_event_manager").slideDown('slow');
        });
        $('#btn_cancel_event_manager').on('click', function() {
            $(".div_event_manager").slideUp('slow');
        });
        /* creates locations from event meta field
        Call back function can find in includes/functions.php
        */
        $('#btn_add_event_manager').on('click', function() {
            var error_status = false;
            var txt_event_manager_title = $('#txt_event_manager_title').val();
            var txt_event_manager_phone = $('#txt_event_manager_phone').val();
            var txt_event_manager_email = $('#txt_event_manager_email').val();
            var txt_event_manager_website = $('#txt_event_manager_website').val();
            if (!txt_event_manager_title) {
                $('#txt_event_manager_title').addClass('txt-error');
                error_status = true;
            }
            if (txt_event_manager_email) {
                if (!validateEmail(txt_event_manager_email)) {
                    $('#txt_event_manager_email').addClass('txt-error');
                    error_status = true;
                }
            }
            if (txt_event_manager_website) {
                if (!validateURL(txt_event_manager_website)) {
                    $('#txt_event_manager_website').addClass('txt-error');
                    error_status = true;
                }
            }
            if (error_status == false) {
                var data = {
                    'action': 'create_event_manager',
                    'txt_event_manager_title': txt_event_manager_title,
                    'txt_event_manager_phone': txt_event_manager_phone,
                    'txt_event_manager_email': txt_event_manager_email,
                    'txt_event_manager_website': txt_event_manager_website,
                };

                // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
                jQuery.post(ajaxurl, data, function(response) {
                    var data = JSON.parse(response);
                    $(".sel_event_manager").append($('<option>', {
                        value: data.event_manager_id,
                        text: data.event_manager_title
                    }));
                    $(".sel_event_manager").val(data.event_manager_id);
                    $(".div_event_manager input[type='text']").val('');
                    $(".div_event_manager").slideUp('slow');
                });
            }
        });

        /* Initializing select2 for customer selection*/
        // multiple select with AJAX search
        //$('.sel_event_customers').trigger('change');
        $('.sel_event_customers').select2({
            ajax: {
                url: ajaxurl, // AJAX URL is predefined in WordPress admin
                dataType: 'json',
                delay: 250, // delay in ms while typing when to perform a AJAX search
                data: function(params) {
                    return {
                        q: params.term, // search query
                        action: 'get_event_customers' // AJAX action for admin-ajax.php
                    };
                },
                processResults: function(data) {
                    var options = [];
                    if (data) {

                        // data is the array of arrays, and each of them contains ID and the Label of the option
                        $.each(data, function(index, text) { // do not forget that "index" is just auto incremented value
                            options.push({
                                id: text[0],
                                text: text[1]
                            });
                        });

                    }
                    return {
                        results: options
                    };
                },
                cache: true
            },
            minimumInputLength: 1, // the minimum of symbols to input before perform a search
            placeholder: addNewCustomerPlaceholder,
            allowClear: true,
        });

        /* Initializing select2 for event booking meta boax*/
        // multiple select with AJAX search
        $('.sel_booked_event').select2({
            ajax: {
                url: ajaxurl, // AJAX URL is predefined in WordPress admin
                dataType: 'json',
                delay: 250, // delay in ms while typing when to perform a AJAX search
                data: function(params) {
                    return {
                        q: params.term, // search query
                        action: 'get_events' // AJAX action for admin-ajax.php
                    };
                },
                processResults: function(data) {
                    var options = [];
                    if (data) {

                        // data is the array of arrays, and each of them contains ID and the Label of the option
                        $.each(data, function(index, text) { // do not forget that "index" is just auto incremented value
                            options.push({
                                id: text[0],
                                text: text[1]
                            });
                        });

                    }
                    return {
                        results: options
                    };
                },
                cache: true
            },
            minimumInputLength: 1, // the minimum of symbols to input before perform a search
            placeholder: selectEventPlaceholder,
            allowClear: true,
        });


        /* To show add event_manager fields in event meta */
        $('.show_customer').on('click', function() {
            $(".div_event_customer").slideDown('slow');
        });
        $('#btn_cancel_event_customer').on('click', function() {
            $(".div_event_customer").slideUp('slow');
        });


        /* Initializing select2 for location list*/
        $(".sel_event_region").select2({
            placeholder: addNewRegionPlaceholder,
            allowClear: true,
        });
        /* To show add event_manager fields in event meta */
        $('.show_location_region').on('click', function() {
            $(".div_location_region").slideDown('slow');
        });
        $('#btn_cancel_location_region').on('click', function() {
            $(".div_location_region").slideUp('slow');
        });
        /* creates locations from event meta field
        Call back function can find in includes/functions.php
        */
        $('#btn_add_location_region').on('click', function() {
            var error_status = false;
            var txt_location_region_title = $('#txt_location_region_title').val();
            var txt_location_region_description = $('#txt_location_region_description').val();
            if (!txt_location_region_title) {
                $('#txt_location_region_title').addClass('txt-error');
                error_status = true;
            }
            if (error_status == false) {
                var data = {
                    'action': 'create_location_region',
                    'txt_location_region_title': txt_location_region_title,
                    'txt_location_region_description': txt_location_region_description,
                };

                // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
                jQuery.post(ajaxurl, data, function(response) {
                    var data = JSON.parse(response);
                    $(".sel_event_region").append($('<option>', {
                        value: data.location_region_id,
                        text: data.location_region_title
                    }));
                    $(".sel_event_region").val(data.location_region_id);
                    $(".div_location_region input[type='text']").val('');
                    $(".div_location_region").slideUp('slow');
                });
            }
        });

        fnc_init_date_time_picker();

    });

    function validateEmail($email) {
        var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
        return emailReg.test($email);
    }

    function validateURL(url) {
        /*return /^(https?|s?ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(url);*/

        return url.match(/\(?(?:(http|https|ftp):\/\/)?(?:((?:[^\W\s]|\.|-|[:]{1})+)@{1})?((?:www.)?(?:[^\W\s]|\.|-)+[\.][^\W\s]{2,4}|localhost(?=\/)|\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3})(?::(\d*))?([\/]?[^\s\?]*[\/]{1})*(?:\/?([^\s\n\?\[\]\{\}\#]*(?:(?=\.)){1}|[^\s\n\?\[\]\{\}\.\#]*)?([\.]{1}[^\s\?\#]*)?)?(?:\?{1}([^\s\n\#\[\]]*))?([\#][^\s\n]*)?\)?/g)


    }
})(jQuery);

/* show / hide time field based on #chk_all_day_event check box status */
function show_hide_time_textbox() {
    if (jQuery('#chk_all_day_event').is(":checked")) {
        jQuery("#txt_start_time").hide();
        jQuery("#txt_end_time").hide();
    } else {
        jQuery("#txt_start_time").show();
        jQuery("#txt_end_time").show();
    }
}

function fnc_init_date_time_picker() {
    // initialize input widgets first

    jQuery('.div_date_time .time').timepicker({
        'showDuration': false,
        'timeFormat': timepickrformat
    });

    jQuery('.div_date_time .date').bootstrapDatePicker({
        'format': datepickerFormat,
        'autoclose': true,
        'startDate': new Date(),
    });
    /*jQuery('.div_date_time .date.start').click(function() {
        jQuery(this).next('.date.end').val('');
    });*/
    jQuery('.div_date_time .date.start').on('change', function() {
        jQuery(this).parent('.div_date_time').find('.date.end').val('');
    });

    // initialize datepair
    jQuery('.div_date_time').datepair();

    /* For dashboard search */

    jQuery('.date_time_picker .time').timepicker({
        'showDuration': false,
        'timeFormat': timepickrformat
    });

    jQuery('.date_time_picker .date').bootstrapDatePicker({
        'format': datepickerFormat,
        'autoclose': true,
        'startDate': new Date(),
    });
    /*jQuery('.div_date_time .date.start').click(function() {
        jQuery(this).next('.date.end').val('');
    });*/
    jQuery('.date_time_picker .date.start').on('change', function() {
        jQuery(this).parent('.new_filter_div_date').find('.date.end').val('');
    });

    // initialize datepair
    jQuery('.new_filter_div_date').datepair();
}
