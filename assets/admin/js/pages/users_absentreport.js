/*
 *  Document   : tablesDatatables.js
 *  Author     : pixelcave
 *  Description: Custom javascript code used in Tables Datatables page
 */

var TablesDatatables = function() {

    return {
        init: function() {
            /* Initialize Bootstrap Datatables Integration */
            App.datatables();

            /* Initialize Datatables */
            $('#example-datatable').dataTable({
                columnDefs: [ { orderable: false, targets: [ 10,9 ] }],
                pageLength: 10,
                lengthMenu: [[10, 20, 30, -1], [10, 20, 30, 'All']]
            });

            /* Add placeholder attribute to the search input */
            $('.dataTables_filter input').attr('placeholder', 'Search');
        }
    };
}();

$(function(){ TablesDatatables.init(); });

// search by category
$('#select_employee, #start_date, #end_date').change(function(){    
    var action = $('#viewBycity').attr('action') ;
    $('#viewBycity').attr('action', action);
    $('#viewBycity').submit();
})
$('#downloadCSV').click(function(){    
    var filterValues = $('#viewBycity').serializeArray();
    filterValues.forEach(element => {
        if(element.value != ""){
            $('#downloadCSV').append('<input type="hidden" name="'+ element.name +'" value="'+ element.value +'" /> ');
        }
    });
    $('#exportCSV').submit();
})