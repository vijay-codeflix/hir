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
                columnDefs: [ { orderable: false, targets: [ 7 ] }],
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
});

function show_confirm(base_url, act, cid)
{
    if(act == 'view'){
        $.ajax({
            url: base_url + "admin/payments/getPaymentDetails",
            method: 'POST',
            data: { id: cid },
            success:function(data) {
                var resultData = JSON.parse(data);
                var createUserHtml = '';
                var keyArray = ['dealer_name','empName', 'amount','payment_method','collection_of','payment_details','extra','pmtThumbImg'];
                var secArray = ['Dealer Name','Employee Name','Amount','Method','Collection Of','Payment details','Extra','Photo'];

                if(resultData){
                    for(var x in resultData){
                        for(var y in keyArray){
                            var valS = resultData[x][keyArray[y]];
                            var keY = secArray[y];
                            if( valS == null){
                                // no html add here 
                            }else{
                                createUserHtml += '<div class="form-group"><label for="first_name" class="col-md-4 control-label">';
                                createUserHtml += secArray[y];
                                createUserHtml += '<span class="text-danger"></span></label><div class="col-md-7">';
                                
                                if( keY == 'Photo'){
                                    var thumb = resultData[x][keyArray[y]];
                                    var large = resultData[x]['pmtLargeImg'];
                                    createUserHtml += '<a href="'+ large + '" target="_blank"><img ng-src="' + thumb + '" src="' + thumb + '" alt="" width="100" height="100"></a>';
                                }else{ 
                                    createUserHtml += '<p class="form-control-static">' + resultData[x][keyArray[y]] + '</p>';   
                                }                    
                                createUserHtml += '</div></div>';
                            }
                        }
                    }
                    createUserHtml += '<div class="form-group" style="border: none;"><label for="first_name" class="col-md-4 control-label"><span class="text-danger"></span></label></div>';
                    $('#normalmodel').html(createUserHtml);
                    $('#viewTitle strong').text('View payment details')
                    $('#view-users').modal('show');
                }
            }
        });
    }else if(act == 'approve'){
        var formAction = base_url + "admin/payments/getAction/" + cid  + '/approved';
        $('#modal-delete form').attr('action',formAction);
        $('#modal-delete button[type=submit]').text('Submit');
        
        $('#modal-delete .modal-title').text('Do you realy want to APPROVE this payment?');
        $('#modal-delete').modal('show',cid);
    }else if(act == 'delete'){
        var formAction = base_url + "admin/payments/getAction/" + cid  + '/rejected';
        $('#modal-delete .modal-title').text('Do you realy want to REJECT this payment?');
        $('#modal-delete form').attr('action',formAction);
        $('#modal-delete button[type=submit]').text('Submit');
        $('#modal-delete').modal('show',cid);
    }
}

$('#downloadCSV').click(function(){    
    var filterValues = $('#viewBycity').serializeArray();
    filterValues.forEach(element => {
        if(element.value != ""){
            $('#downloadCSV').append('<input type="hidden" name="'+ element.name +'" value="'+ element.value +'" /> ');
        }
    });
    $('#exportCSV').submit();
})