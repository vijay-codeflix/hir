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
            $('#userAddForm').validate({
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
                    grade: {
                        required: true
                    }
                },
                messages: {
                    grade: {
                        required: 'This field is required.'
                    }
                }
            });
        }

    };
}();


/************Required function call below*************/


$(function() { 
    FormsValidation.init();
    var formElements = $('input[type=text]');
    if(formElements.length > 0)
    {
        for (let index = 0; index < formElements.length; index++) {
            const element = $('input[type=text]').eq(index).attr('id');
            if(element == 'grade'){
                continue;
            }
            $('#' + element).rules('add', {
                required: true,
                number: true,
                messages: {
                    required: "Please enter category amount.",
                    number: "Amount should be number only."
                }
            });
        }
    } 
    $('#grade-2-error').parent().parent().addClass('has-error');
    $('#grade-2-error').siblings('input').val('');
    $('#grade-2-error').fadeOut(10000, function(){ $(this).parent().parent().removeClass('has-error');$(this).remove(); });
   
});