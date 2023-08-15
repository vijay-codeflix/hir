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
                    first_name: {
                        required: true
                    },
                    last_name: {
                        required: true
                    },
                    emp_id: {
                        required: true,
                        number: true
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    password: {
                        required: true,
                        minlength: 4
                    },
                    phone: {
                        required: true,
                        number: true
                    },
                    grade_id: {
                        required: true,min:1
                    }
                },
                messages: {
                    first_name: {
                        required: 'Please provide first name'
                    },
                    last_name: {
                        required: 'Please provide last name'
                    },
                    emp_id: {
                        required: 'Please provide employee id',
                        number: 'Only numbers are allowed'
                    },
                    email: {
                        required: 'Please provide email',
                        email: 'Please enter a valid email address'
                    },
                    password: {
                        required: 'Please provide password',
                        minlength: 'Password must consist of at least 4 characters'
                    },
                    phone: {
                        required: 'Please provide phone number',
                        number: 'Only numbers are allowed'
                    },
                    grade_id: {
                        required: "Please select grade"
                    } 
                }
            });
        }

    };
}();


/************Required function call below*************/


$(function() { 
    FormsValidation.init(); 
    var kepressValidate = false;
    $('#password').keypress(function(){
        if(!kepressValidate){
            $(this).val('');
            kepressValidate = true;
            $('#passwordValidate').val('true');
        }
    });
    loadValidation();
});

function loadValidation(){
    var errorClass = [];
     errorClass[0] = 'emp_id-2-error';
     errorClass[1] = 'email-2-error';
     errorClass[2] = 'phone-2-error';
 
     // initialze error class here 
     createErrorHtml(errorClass);
}
$('#form-validation').submit(function(){
    var userType = $('#user_type').val();
    if(userType == ''){
        alert("Please select user type");
        return;
    }

    if(userType == '4'){ // sub admin
        var gradeId = $('#grade_id').val();
        if(gradeId == ''){
            alert("Please select grade");
            return;
        }
        var parent = $('#parent_id').val();
        if(parent == ''){
            alert("Please select parent");
            return;
        }
    }
});

$('#user_type').change(function(){
    $('.has-error .animation-slideDown').hide();
    $('.has-error').removeClass('has-error');
    
    var valUser = $(this).val();
    if(valUser == '3'){ // sub admin
        $('#phone').rules('remove')
        $('#email').rules('add', {
            required: true,
            email: true,
            messages: {
                required: 'Please provide email',
                email: 'Please enter a valid email address'
            }
        });
        $('#grade_id').removeAttr('value');
        $('#password').parent().parent().show();
        $('#grade_id').parent().parent().hide();
        loadValidation();
        
    }else if(valUser == '4'){ // employee
        $('#email').rules('remove')
        $('#phone').rules('add', {
            required: true,
            number: true,
            messages: {
                required: 'Please provide phone number',
                number: 'Only numbers are allowed'
            }
        });
        $('#password').val('');
        $('#password').parent().parent().hide();
        $('#grade_id').parent().parent().show();
        loadValidation();
    }
});   

// Shorthand for $( document ).ready()
$(function() {
    var userType = $('#user_type').val();
    if(userType == '4'){ // sub admin
        $('#email').parent().parent().addClass('hide');
        $('#phone').parent().parent().removeClass('hide');
        $('#emp_id').parent().parent().removeClass('hide');
    }else if(userType == '3'){
        $('#email').parent().parent().removeClass('hide');
        $('#phone').parent().parent().addClass('hide');
        $('#emp_id').parent().parent().addClass('hide');
    }
});

