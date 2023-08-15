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
                    city_name: {
                        required: true
                    },
                    grade: {
                        required: true
                    }
                },
                messages: {
                    city_name: {
                        required: 'Please provide city name'
                    },
                    grade: {
                        required: 'Please select Grade'
                    }
                }
            });
        }

    };
}();


/************Required function call below*************/


$(function() { 
    FormsValidation.init(); 
    $('#grade-2-error').parent().parent().addClass('has-error');
    $('#grade-2-error').siblings('input').val('');
    $('#grade-2-error').fadeOut(10000, function(){ $(this).parent().parent().removeClass('has-error');$(this).remove(); });
  
    $('#city_name-2-error').parent().parent().addClass('has-error');
    $('#city_name-2-error').siblings('input').val('');
    $('#city_name-2-error').fadeOut(10000, function(){ $(this).parent().parent().removeClass('has-error');$(this).remove(); });
});

$('#form-validation').submit(function(){
    var userType = $('#grade').val();
    if(userType == ''){
        alert("Please select Grade");
        return;
    }
});