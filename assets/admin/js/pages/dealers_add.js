/*
 *  Document   : formsValidation.js
 *  Author     : pixelcave
 *  Description: Custom javascript code used in Forms Validation page
 */

var FormsValidation = function() {

    return {
        init: function() {
            /*
             *  Jquery Validation, Check out more examples and documentation at https://github.com/jzaefferer/jquery-validation
             */

            /* Initialize Form Validation */
            $('#form-validation').validate({
                errorClass: 'help-block animation-slideDown', // You can change the animation class for a different entrance animation - check animations page
                errorElement: 'div',
                errorPlacement: function(error, e) {
                    e.parents('.form-group > div').append(error);
                },
                highlight: function(e) {
                    $(e).closest('.form-group').removeClass('has-success has-error').addClass('has-error');
                    $(e).closest('.help-block').remove();
                },
                success: function(e) {
                    // You can use the following if you would like to highlight with green color the input after successful validation!
                    e.closest('.form-group').removeClass('has-success has-error'); // e.closest('.form-group').removeClass('has-success has-error').addClass('has-success');
                    e.closest('.help-block').remove();
                },
                rules: {
                    // gst_number: {
                    //     required: true
                    // },
                    address: {
                        required: true
                    },
                    firm_name: {
                        required: true
                    },
                    dealer_phone: {
                        required: true
                    },
                    dealer_name: {
                        required: true
                    },
                    city_or_town: {
                        required: true
                    }
                },
                messages: {
                    // gst_number: {
                    //     required: 'Please provide GST number'
                    // },
                    address: {
                        required: 'Please provide address'
                    },
                    firm_name: {
                        required: 'Please provide firm name'
                    },
                    dealer_phone: {
                        required: 'Please provide dealer phone'
                    },
                    dealer_name: {
                        required: 'Please provide dealer name'
                    },
                    city_or_town: {
                        required: 'Please provide city/town'
                    }
                }
            });
        }

    };
}();

$(function() { 
    FormsValidation.init(); 
    $('#gst_number-2-error').parent().parent().addClass('has-error');
    $('#gst_number-2-error').siblings('input').val('');
    $('#gst_number-2-error').fadeOut(10000, function(){ $(this).parent().parent().removeClass('has-error');$(this).remove(); });
  
    $('#address-2-error').parent().parent().addClass('has-error');
    $('#address-2-error').siblings('input').val('');
    $('#address-2-error').fadeOut(10000, function(){ $(this).parent().parent().removeClass('has-error');$(this).remove(); });
  
    $('#firm_name-2-error').parent().parent().addClass('has-error');
    $('#firm_name-2-error').siblings('input').val('');
    $('#firm_name-2-error').fadeOut(10000, function(){ $(this).parent().parent().removeClass('has-error');$(this).remove(); });
  
    $('#dealer_phone-2-error').parent().parent().addClass('has-error');
    $('#dealer_phone-2-error').siblings('input').val('');
    $('#dealer_phone-2-error').fadeOut(10000, function(){ $(this).parent().parent().removeClass('has-error');$(this).remove(); });
  
    $('#dealer_name-2-error').parent().parent().addClass('has-error');
    $('#dealer_name-2-error').siblings('input').val('');
    $('#dealer_name-2-error').fadeOut(10000, function(){ $(this).parent().parent().removeClass('has-error');$(this).remove(); });
  
    $('#city_or_town-2-error').parent().parent().addClass('has-error');
    $('#city_or_town-2-error').siblings('input').val('');
    $('#city_or_town-2-error').fadeOut(10000, function(){ $(this).parent().parent().removeClass('has-error');$(this).remove(); });
});

$('#form-validation').submit(function(){
    var employee_id = $('#employee_id').val();
    if(employee_id == ''){
        alert("Please select employee");
        return;
    }
});