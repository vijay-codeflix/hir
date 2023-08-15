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
                    user_type: {
                        required: true,
                        min:1
                    },
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
                    da_amount: {
                        required: true,
                        number: true
                    },
                    grade_id: {
                        required: true,min:1
                    },
                    ping_interval:{
                        required: true,
                    }
                },
                messages: {
                    user_type: {
                        required: 'Please select user type'
                    },
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
                    },
                    ping_interval:{
                        required: "Please provide map ping interval time"
                    } 
                }
            });
        }

    };
}();


/************Required function call below*************/


$(function() { 
    FormsValidation.init(); 
    check_onload();
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

$('#userAddForm').submit(function(){
    var userType = $('#user_type').val();
    if(userType == ''){
        alert("Please select user type");
        return;
    }

    if(userType == '4'){ // employee
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
    if(valUser == '2'){
        $('#ping_interval').parent().parent().removeClass('hide');
        $('#ping_interval').rules('add')
    }else{
        $('#ping_interval').parent().parent().addClass('hide');
        $('#ping_interval').rules('remove')
    }
    
    if(valUser == '3'){ // sub admin
        $('#dangerP').addClass('hide');
        $('#phone').rules('remove')
        $('#emp_id').rules('remove')
        $('#email').rules('add', {
            required: true,
            email: true,
            messages: {
                required: 'Please provide email',
                email: 'Please enter a valid email address'
            }
        });
        $('#grade_id').removeAttr('value');
        $('#password').parent().parent().removeClass('hide');
        $('#grade_id').parent().parent().addClass('hide');
        $('#phone').parent().parent().addClass('hide');
        $('#emp_id').parent().parent().addClass('hide');
        $('#email').parent().parent().removeClass('hide');
        loadValidation();
        
    }else if(valUser == '4'){ // employee
        $('#dangerP').removeClass('hide');
        $('#password').parent().parent().addClass('hide');
        $('#grade_id').parent().parent().removeClass('hide');
        $('#phone').parent().parent().removeClass('hide');
        $('#emp_id').parent().parent().removeClass('hide');
        $('#email').parent().parent().addClass('hide');
        
        $('#email').rules('remove')
        $('#emp_id').rules('add')
        $('#password').val('');

        $('#phone').rules('add', {
            required: true,
            number: true,
            messages: {
                required: 'Please provide phone number',
                number: 'Only numbers are allowed'
            }
        });
        
        
        loadValidation();
    }
});


function check_onload() {

    $('.has-error .animation-slideDown').hide();
    $('.has-error').removeClass('has-error');
    
    var valUser = $('#user_type').val();
    //console.log('valUser',valUser);
       
    if(valUser == '3'){ // sub admin
        $('#dangerP').addClass('hide');
        $('#phone').rules('remove')
        $('#emp_id').rules('remove')
        $('#email').rules('add', {
            required: true,
            email: true,
            messages: {
                required: 'Please provide email',
                email: 'Please enter a valid email address'
            }
        });
        $('#grade_id').removeAttr('value');
        $('#password').parent().parent().removeClass('hide');
        $('#grade_id').parent().parent().addClass('hide');
        $('#phone').parent().parent().addClass('hide');
        $('#emp_id').parent().parent().addClass('hide');
        $('#email').parent().parent().removeClass('hide');
        loadValidation();
        
    }else if(valUser == '4'){ // employee
        $('#dangerP').removeClass('hide');
        $('#password').parent().parent().addClass('hide');
        $('#grade_id').parent().parent().removeClass('hide');
        $('#phone').parent().parent().removeClass('hide');
        $('#emp_id').parent().parent().removeClass('hide');
        $('#email').parent().parent().addClass('hide');
        
        $('#email').rules('remove')
        $('#emp_id').rules('add')
        $('#password').val('');

        $('#phone').rules('add', {
            required: true,
            number: true,
            messages: {
                required: 'Please provide phone number',
                number: 'Only numbers are allowed'
            }
        });      
        loadValidation();
    }
}