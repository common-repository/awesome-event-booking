(function($) {
    'use strict';

    /*$('#btn_checkout').click(function() {
      var email = document.getElementById("txt_email").value;
      var confemail = document.getElementById("txt_repeat_email").value;
      if (email != confemail) {
        document.getElementById('btn_checkout').disabled = true;
      } else {
        document.getElementById('btn_checkout').disabled = false;
      }
    });
    $("#event_checkout input[type='text'],#event_checkout input[type='email']").click(function(){
      var email = document.getElementById("txt_email").value;
      var confemail = document.getElementById("txt_repeat_email").value;
      if (email != confemail) {
        document.getElementById('btn_checkout').disabled = true;
      } else {
        document.getElementById('btn_checkout').disabled = false;
      }
    });*/

    $('#btn_checkout').on('click', function(e) {
        var valid = true;

        $(".participant_clone").each(function(e) {
            var validation = true;
            var error_message = '';
            //var txt_first_name = $(this).find(".txt_first_name").val();
            //var txt_last_name = $(this).find(".txt_last_name").val();
            //var txt_address = $(this).find(".txt_address").val();
            //var txt_zip = $(this).find(".txt_zip").val();
            //var txt_city = $(this).find(".txt_city").val();
            //var txt_phone_no = $(this).find(".txt_phone_no").val();
            //var email = $(this).find(".txt_email").val();
            var confemail = $(this).find(".txt_repeat_email").val();

            $($(this).find(".validate_signup_field")).each(function(index) {
                if ($(this).hasClass('txt_email') || $(this).hasClass('txt_repeat_email')) {
                    if ($(this).hasClass('txt_email')) {
                        if (!$(this).val()) {
                            error_message += '<p>' + $(this).attr('alt') + ' is missing</p>';
                            validation = false;
                        } else {
                            //var confemail = $(".txt_repeat_email").val();
                            if ($(this).val() != confemail) {
                                error_message += '<p>Email Mismatch</p>';
                                validation = false;
                            }

                        }
                    }
                } else {
                    if ($(this).attr('required') == 'required') {
                        if (!$(this).val()) {
                            error_message += '<p>' + $(this).attr('alt') + ' is missing</p>';
                            validation = false;
                        }
                    }
                }
            });
            if (validation == false) {
                valid = false;
                $(this).find(".validatoin_errors").html(error_message);
            } else {
                valid = true;
                $(this).find(".validatoin_errors").html(error_message);

            }
        });
        if (valid == false) {
            e.preventDefault();
        }
        /*var email = document.getElementById("txt_email").value;
        var confemail = document.getElementById("txt_repeat_email").value;
        if (!email) {
            e.preventDefault();
            alert('Email is required.');
        } else if (email != confemail) {
            e.preventDefault();
            alert('Email Mismatch');
        }*/
    });

    var icons = {
        header: "ui-icon-circle-arrow-e",
        activeHeader: "ui-icon-circle-arrow-s"
    };
    var temp_region_id = '';
    var event_template = '';
    $(".accordion").accordion({
        collapsible: true,
        active: false,
        icons: icons,
        heightStyle: "fill",
        activate: function(e, ui) {
            var _this = $(this);
            var event_template = _this.find('.ui-accordion-header-active').attr('data-template');
            if (temp_region_id) {
                 _this.find('.region_events_' + temp_region_id).html(''); //alert(temp_region_id);
            }
            var region_id =  _this.find('.ui-accordion-header-active').attr('alt');
            var sc_category_slug = _this.closest('.wpeb-events-shortcode').find('.sc_category_slug').val();
            var sc_event_region = _this.closest('.wpeb-events-shortcode').find('.sc_event_region').val();
            var sc_event_city = _this.closest('.wpeb-events-shortcode').find('.sc_event_city').val();
            var sc_order_by = _this.closest('.wpeb-events-shortcode').find('.sc_order_by').val();
            var sc_order = _this.closest('.wpeb-events-shortcode').find('.sc_order').val();
            
            temp_region_id = region_id;
            //var loading_text = 'Loading';
            _this.find('.region_events_' + region_id).html("<p class='ajax-loading'>"+loading_text+"<p>").css({ 'height': 'auto' });

            if (!event_template) {
                var data = {
                    'action': 'pull_events_from_region',
                    'region_id': region_id,
                    'category_slug': sc_category_slug,
                    'event_region': sc_event_region,
                    'event_city': sc_event_city,
                    'order_by': sc_order_by,
                    'order': sc_order,
                };
                // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
                jQuery.post(wpeb.ajax_url, data, function(response) {
                    _this.find('.region_events_' + region_id).html(response).slideDown('slow');
                    _this.find(".accordion div").css({ 'height': 'auto' });
                    $(".tablesorter").on('click', 'tbody tr .read-more', function() {
                        var link = $(this);
                        var row_id = $(this).attr('alt');
                        if (link.closest('tr').children().find('.more').is(':visible')) {
                            link.html(read_more_lang);
                            $('#more-desc-' + row_id).hide();
                        } else {
                            link.html(close_lang);
                            $('#more-desc-' + row_id).show();
                        }
                        $(this).closest('tr').children().find('.more').toggle();
                    }).tablesorter();
                });
            } else {
                var data = {
                    'action': 'pull_cities_from_region',
                    'region_id': region_id,
                    'category_slug': sc_category_slug,
                    'event_region': sc_event_region,
                    'event_city': sc_event_city,
                    'order_by': sc_order_by,
                    'order': sc_order,
                };
                // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
                jQuery.post(wpeb.ajax_url, data, function(response) {
                    _this.find('.region_events_' + region_id).html(response).slideDown('slow');
                    _this.find(".accordionCity").accordion({
                        collapsible: true,
                        active: false,
                        icons: icons,
                        heightStyle: "fill",
                        activate: function(e, ui) {
                            var data_city = $(this).find('.ui-accordion-header-active').attr('data-city');
                            var count = $(this).find('.ui-accordion-header-active').attr('alt');
                            var data = {
                                'action': 'pull_events_from_city',
                                'data_city': data_city,
                                'category_slug': sc_category_slug,
                                'event_region': sc_event_region,
                                'event_city': sc_event_city,
                                'order_by': sc_order_by,
                                'order': sc_order,
                            };
                            // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
                            jQuery.post(wpeb.ajax_url, data, function(response) {
                                _this.find('.region_city_events_' + count).html(response).slideDown('slow');
                                _this.find(".tablesorter").on('click', 'tbody tr .read-more', function() {
                                    var link = $(this);
                                    var row_id = $(this).attr('alt');
                                    if (link.closest('tr').children().find('.more').is(':visible')) {
                                        link.html(read_more_lang);
                                        $('#more-desc-' + row_id).hide();
                                    } else {
                                        link.html(close_lang);
                                        $('#more-desc-' + row_id).show();
                                    }
                                    $(this).closest('tr').children().find('.more').toggle();
                                }).tablesorter();
                            });
                        }
                    });
                    _this.find(".accordionCity div").css({ 'height': 'auto' });
                });

            }

        },
        beforeActivate: function(event, ui) {}
    });
    $(".tablesorter").on('click', 'tbody tr .read-more', function() {
        var link = $(this);
        var row_id = $(this).attr('alt');
        if (link.closest('tr').children().find('.more').is(':visible')) {
            link.html(read_more_lang);
            $('#more-desc-' + row_id).hide();
        } else {
            link.html(close_lang);
            $('#more-desc-' + row_id).show();
        }
        $(this).closest('tr').children().find('.more').toggle();
    }).tablesorter();
    /*var tmp_num = 1;
    $('.add_participants').click(function(e) {
        e.preventDefault();
        tmp_num++;
        $("#participant_1").clone().attr('id', 'participant_'+tmp_num).after("#participant_1").appendTo(".participant_details");
        $('#participant_'+tmp_num).find('.participant_count').html(tmp_num);
        $('#participant_'+tmp_num).find('input[type=text], input[type=email]').val('');
    });*/
    $('.remove_participants').on('click', function(e) {
        e.preventDefault();
        if (tmp_num > 1) {

            //console.log('#participant_' + tmp_num);
            $('#participant_' + tmp_num).remove();

            tmp_num--;
        }
    });
    $('.cancel-event-booking').on('click', function(e) {
        if (!confirm(cancelEventPlaceholder)) {
            e.preventDefault();
        }
    });
    if (available_spots) {
        if (available_spots < 0) {
            alert(noSeatsPlaceholder);
            $('#btn_checkout').prop('disabled', true);
        }
    }
    var tmp_num = 1;
    var num = 1;
    var prevNumber = 1;
    var eventPrice = 0;
    $(document).on('change', '#sel_participant', function() {
        num = parseInt($(this).val());
        eventPrice = parseInt($('.wpeb_event_price').attr('alt'));
        $('.wpeb_event_price').html(eventPrice * num);
        if (available_spots === '') {
            $('#btn_checkout').prop('disabled', false);
        } else if (available_spots === 0) {
            alert(noSeatsPlaceholder);
            $('#btn_checkout').prop('disabled', true);
        } else if (num > available_spots) {
            //fewSeatsPlaceholder
            //alert('Only '+available_spots+ ' seats available.');
            fewSeatsPlaceholder = fewSeatsPlaceholder.replace("%d%", available_spots);
            alert(fewSeatsPlaceholder);
            num = available_spots; // setting num value to available seats count
            $(this).val(available_spots);
            //$('#btn_checkout').prop('disabled', true);
        } else {
            $('#btn_checkout').prop('disabled', false);
        }
        if (num > prevNumber) {
            var i;
            for (i = prevNumber + 1; i <= num; i++) {
                var block = `<div id="participant_` + i + `" class="participant_clone">
                <p class="participant_title">` + participants_translation + ` <span class="participant_count">` + i + `</span><a class="btnRemoveParticipant"><svg version="1.1" id="remove_title" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"viewBox="0 0 496.158 496.158" style="enable-background:new 0 0 496.158 496.158;" xml:space="preserve"> <path style="fill:#E04F5F;" d="M0,248.085C0,111.063,111.069,0.003,248.075,0.003c137.013,0,248.083,111.061,248.083,248.082 c0,137.002-111.07,248.07-248.083,248.07C111.069,496.155,0,385.087,0,248.085z"/> <path style="fill:#FFFFFF;" d="M383.546,206.286H112.612c-3.976,0-7.199,3.225-7.199,7.2v69.187c0,3.976,3.224,7.199,7.199,7.199 h270.934c3.976,0,7.199-3.224,7.199-7.199v-69.187C390.745,209.511,387.521,206.286,383.546,206.286z"/> </svg></a></p>
                <div class="validatoin_errors">
                </div>
                ` + dynamic_fields + `
                </div>`;
                $('.participant_details').append(block);
            }
            prevNumber = num;
        } else {
            var i;
            for (i = num + 1; i <= prevNumber; i++) {
                $('.participant_details').find('#participant_' + i).remove();
            }
            prevNumber = num;
        }

        /*
        var num = $(this).val();
        if(num<tmp_num)
        {
            for (var i = parseInt(num)+1; i <= tmp_num; i++) {
                console.log('#participant_'+i);
                $('#participant_'+i).remove();
            }
        }
        else
        {
            console.log(tmp_num);
            for (var i = parseInt(tmp_num)+1; i <= num; i++) {
                $("#participant_1").clone().attr('id', 'participant_'+i).after("#participant_1").appendTo(".participant_details");
                $('#participant_'+i).find('.participant_count').html(i);
                $('#participant_'+i).find('input[type=text], input[type=email]').val('');
                console.log('#participant_'+i);
            }
        }
        tmp_num = num;
        */

    });
    $(document).on('click', '.btnRemoveParticipant', function(e) {
        var CurrentVal = $("#sel_participant").prop('selectedIndex');
        if (CurrentVal > 0) {
            $("#sel_participant").prop('selectedIndex', CurrentVal - 1);
            //$("#sel_participant").change();
            $(this).closest('.participant_clone').addClass('div_deleting').html('<p class="del_notice">' + del_notice_tranlsation + '</p>').
            delay(1000).
            fadeOut('slow').
            //delay(1000).
            queue(function() {
                //$(this).closest('.participant_clone').fadeOut('slow');
                //$(this).closest('.participant_clone').html('<p style="color:red;">Removed<p>');
                $(this).closest('.participant_clone').remove();
                prevNumber = CurrentVal;
                num = parseInt(CurrentVal - 1);
                var i = 1;
                $(".participant_clone").each(function(e) {
                    $(this).attr('id', 'participant_' + i);
                    $(this).find('.participant_count').html(i);
                    i++;
                });
                eventPrice = parseInt($('.wpeb_event_price').attr('alt'));
                $('.wpeb_event_price').html(eventPrice * prevNumber);
            });

        }
    });
    /*
        $(document).on('click', '.btnRemoveParticipant', function(e) {
        var CurrentVal = $("#sel_participant").prop('selectedIndex');
        if(CurrentVal>0)
        {
            $("#sel_participant").prop('selectedIndex',CurrentVal-1);
            //$("#sel_participant").change();
            $(this).closest('.participant_clone').addClass('div_deleting').fadeOut('slow')
            .queue(function() {
                $(this).closest('.participant_clone').remove();
                prevNumber = CurrentVal;
                num = parseInt(CurrentVal-1);
                var i  =1;
                $(".participant_clone").each(function(e) {
                    $(this).attr('id','participant_' + i);
                    //$(this).find('.participant_count').html(i);
                    i++;
                });
            });
        }
    });
    */
    $('.btnAddParticipant').on('click', function(e) {
        var CurrentVal = $("#sel_participant").prop('selectedIndex');
        $("#sel_participant").prop('selectedIndex', CurrentVal + 1);
        $("#sel_participant").change();
        eventPrice = parseInt($('.wpeb_event_price').attr('alt'));
        $('.wpeb_event_price').html(eventPrice * num);
        //$('#sel_participant').val('1').trigger('change');
        //$('#sel_participant').on('change', 1);

    });


})(jQuery);
/*function confirmEmail() {
  var email = document.getElementById("txt_email").value;
  var confemail = document.getElementById("txt_repeat_email").value;
  if (email != confemail) {
    document.getElementById('btn_checkout').disabled = true;
  } else {
    document.getElementById('btn_checkout').disabled = false;
  }
}*/