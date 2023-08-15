
$(function () {
    $('#datetimepicker1').datepicker();
});

$('#select_employee, #date, #end_date').change(function(){    
    var action = $('#viewLocation').attr('action') ;
    $('#viewLocation').attr('action', action);
    $('#viewLocation').submit();
})

$('#reset-btn').click(function(){    
    $('#search-user').val('');
    $('#parent_id').val('');
    $('#userSearchForm').submit();
})