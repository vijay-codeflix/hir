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
                   
                    old_password: {
                        required: true,
                        minlength: 4
                    },
                    password: {
                        required: true,
                        minlength: 4
                    },
                    password_confirm: {
                        required: true,
                        minlength: 4,
                        //equalTo : "#password"
                        
                    },
                    
                },
                messages: {
                    old_password: {
                        required: 'Please provide old password',
                        minlength: 'Old Password must consist of at least 4 characters'
                    },
                    password: {
                        required: 'Please provide password',
                        minlength: 'New Password must consist of at least 4 characters'
                    },
                    password_confirm: {
                        required: 'Please provide confirm password',
                        equalTo: 'Confirm password does not match new password',
                        minlength: 'Confirm Password must consist of at least 4 characters'
                    }
                    
                }
            });
        }

    };
}();


/************Required function call below*************/


$(function() { 
    FormsValidation.init(); 
    //loadValidation();
});

// function loadValidation(){
//     var errorClass = [];
//      errorClass[0] = 'emp_id-2-error';
//      errorClass[1] = 'email-2-error';
//      errorClass[2] = 'phone-2-error';
 
//      // initialze error class here 
//      createErrorHtml(errorClass);
// }

