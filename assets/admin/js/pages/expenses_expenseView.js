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
                columnDefs: [ { orderable: false, targets: [ 10 ] },{ width: "50%", "targets": [1,2,3,4,5] } ],
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
$('#select_category, #select_status, #select_employee, #start_date, #end_date').change(function(){    
    var action = $('#viewBycity').attr('action') ;
    $('#viewBycity').attr('action', action);
    $('#viewBycity').submit();
});

function viewApprovalStatus(base_url, cid){
    $.ajax({
        url: base_url + "admin/expenses/viewApprovalStatus/" + cid,
        method: 'GET',
        success:function(data) {
            var resultData = JSON.parse(data);
            if(typeof(resultData.error) != "undefined"){
                alert(resultData.message);return;
            }else{
                var createUserHtml = '';
                resultData.forEach(element => {
                    createUserHtml += '<div class="form-group"><label for="first_name" class="col-md-4 control-label">';
                    createUserHtml += element.name;
                    createUserHtml += '<span class="text-danger"></span></label><div class="col-md-7">';
                    
                    if(element.status == "pending"){
                        status = '<span class="label label-warning">Pending</span>';
                        amount = '';
                        reason = '';
                    }else if(element.status == "approved"){
                        status = '<span class="label label-success">Approved</span>';
                        amount = '';
                        reason = '';
                    }else if(element.status == "rejected"){
                        status = '<span class="label label-danger">Rejected</span>';
                        amount = '';
                        reason = '';
                    }else if(element.status == "partial-approved" || element.status == "partial-approve"){
                        status = '<span class="label label-info">Partial approved</span>';
                        amount = '<span class="">'+element.amount+'</span>';
                        reason = '<span class="">'+element.reason+'</span>';
                    }
                    if(reason){
                        createUserHtml += '<p class="form-control-static">' + status + ', <b>Amount</b> - '+ amount +', <b>Reason</b> - '+reason+'</p>';    
                    }else{
                        createUserHtml += '<p class="form-control-static">' + status + '</p>';
                    }
                    
                    createUserHtml += '</div></div>';
                });
                createUserHtml += '<div class="form-group" style="border: none;"><label for="first_name" class="col-md-4 control-label"><span class="text-danger"></span></label></div>';
                $('#normalmodel').html(createUserHtml);
                $('#viewTitle strong').text('View Child Approval Status')
                $('#view-users').modal('show');
            }
            
        }
    });
}

function show_confirm(base_url, act, cid)
{
    $.ajax({
        url: base_url + "admin/expenses/getExpenseDetails",
        method: 'POST',
        data: { id: cid },
        success:function(data) {
            var resultData = JSON.parse(data);
            var createUserHtml = '';
            var keyArray = ['empName','phone','catName','expense_details','expThumbImg','reqAmount','approveAmount','status','reqDate'];
            var secArray = ['Employee Name','Employee Phone','Category','Expense Details','Expense Photo','Requested Amount','Approved Amount','Status','Request Date'];

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
                            if(keY == 'Status'){ 
                                var status = '';
                                var st = resultData[x][keyArray[y]];
                                if(st == "pending"){
                                    status = '<span class="label label-warning">Pending</span>';
                                }else if(st == "approved"){
                                    status = '<span class="label label-success">Approved</span>';
                                }else if(st == "rejected"){
                                    status = '<span class="label label-danger">Rejected</span>';
                                }else if(st == "partial-approved"){
                                    status = '<span class="label label-info">Partial approved</span>';
                                }
                                createUserHtml += '<p class="form-control-static">' + status + '</p>';
                            }else if( keY == 'Expense Photo'){
                                var thumb = resultData[x][keyArray[y]];
                                var large = resultData[x]['expLargeImg'];
                                createUserHtml += '<a href="'+ large + '" target="_blank"><img ng-src="' + thumb + '" src="' + thumb + '" alt="" width="100" height="100"></a>';
                            }else if( keY == 'Requested Amount'){
                                createUserHtml += '<p class="form-control-static">' + resultData[x]['currency_symbol'] +' '+ resultData[x][keyArray[y]] + '</p>'; 
                            }else if( keY == 'Approved Amount'){
                                createUserHtml += '<p class="form-control-static">' + resultData[x]['currency_symbol'] +' '+ resultData[x][keyArray[y]] + '</p>'; 
                            }else{ 
                                createUserHtml += '<p class="form-control-static">' + resultData[x][keyArray[y]] + '</p>';   
                            }                    
                            createUserHtml += '</div></div>';
                        }
                    }
                }
                createUserHtml += '<div class="form-group" style="border: none;"><label for="first_name" class="col-md-4 control-label"><span class="text-danger"></span></label></div>';
                $('#normalmodel').html(createUserHtml);
                $('#viewTitle strong').text('View expense details')
                $('#view-users').modal('show');
            }
        }
    });
}

function expenseAction(type, base_url, id){
    var formAction = base_url + "admin/expenses/expenseAction/" + id + '/' + type;
    var htmlForm = '';
    if(type == "approved"){
        $('#modal-user-settings .modal-title').text('Approve expense');
        htmlForm = '<div class="form-group"><label class="col-md-4 control-label" for="reason"></label><div class="col-md-8"><p class="form-control-static">Are you sure to approve?</p></div></div>';
    }else if(type == "partial-approved"){
        $('#modal-user-settings .modal-title').text('Approve expense partially');
        htmlForm = '<div class="form-group amount"><label class="col-md-4 control-label" for="amount">Amount</label><div class="col-md-8"><input type="text" id="amount" name="amount" class="form-control" placeholder="Enter amount"></div></div><div class="form-group"><label class="col-md-4 control-label" for="reason">Reason</label><div class="col-md-8"><input type="text" id="reason" name="reason" class="form-control" placeholder="Enter reason"></div></div>';
    }else if(type == "partial-approved_by_super_admin"){
        $('#modal-user-settings .modal-title').text('Super Admin Approve expense partially');

        $.ajax({
            url: base_url + "admin/expenses/viewApprovalStatus/" + id,
            method: 'GET',
            success:function(data) {
                var resultData = JSON.parse(data);
                if(typeof(resultData.error) != "undefined"){
                    alert(resultData.message);return;
                }else{
                    var createUserHtml = '';
                    resultData.forEach(element => {
                        htmlForm += '<div class="form-group"><label for="first_name" class="col-md-4 control-label">';
                        htmlForm += element.name;
                        htmlForm += '<span class="text-danger"></span></label><div class="col-md-7">';

                        if(element.status == "pending"){
                            status = '<span class="label label-warning">Pending</span>';
                            amount = '';
                            reason = '';
                        }else if(element.status == "approved"){
                            status = '<span class="label label-success">Approved</span>';
                            amount = '';
                            reason = '';
                        }else if(element.status == "rejected"){
                            status = '<span class="label label-danger">Rejected</span>';
                            amount = '';
                            reason = '';
                        }else if(element.status == "partial-approved" || element.status == "partial-approve"){
                            status = '<span class="label label-info">Partial approved</span>';
                            amount = '<span class="">'+element.amount+'</span>';
                            reason = '<span class="">'+element.reason+'</span>';
                        }
                        if(reason){
                            htmlForm += '<p class="form-control-static">' + status + ', <b>Amount</b> - '+ amount +', <b>Reason</b> - '+reason+'</p>';
                        }else{
                            htmlForm += '<p class="form-control-static">' + status + '</p>';
                        }

                        htmlForm += '</div></div>';
                    });
                    htmlForm += '<div class="form-group" style="border: none;"><label for="first_name" class="col-md-4 control-label"><span class="text-danger"></span></label></div>';

                    htmlForm += '<div class="form-group amount"><label class="col-md-4 control-label" for="amount">Amount</label><div class="col-md-8"><input type="text" id="amount" name="amount" class="form-control" placeholder="Enter amount"></div></div><div class="form-group"><label class="col-md-4 control-label" for="reason">Reason</label><div class="col-md-8"><input type="text" id="reason" name="reason" class="form-control" placeholder="Enter reason"></div></div>';
                    htmlForm += '<div class="form-group form-actions"><div class="col-xs-12 text-right"><button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button><button type="submit" class="btn btn-sm btn-primary iconbg">Submit</button></div></div>';

                    $('#modal-user-settings form').html(htmlForm);
                    $('#modal-user-settings form').attr('action',formAction);
                    $('#modal-user-settings').modal('show', id);

                }

            }
        });

    }else if(type == "partial-approved_by_admin"){
        $('#modal-user-settings .modal-title').text('Admin Approve expense partially');

        $.ajax({
            url: base_url + "admin/expenses/viewApprovalStatus/" + id,
            method: 'GET',
            success:function(data) {
                var resultData = JSON.parse(data);
                if(typeof(resultData.error) != "undefined"){
                    alert(resultData.message);return;
                }else{
                    var createUserHtml = '';
                    resultData.forEach(element => {
                        htmlForm += '<div class="form-group"><label for="first_name" class="col-md-4 control-label">';
                        htmlForm += element.name;
                        htmlForm += '<span class="text-danger"></span></label><div class="col-md-7">';

                        if(element.status == "pending"){
                            status = '<span class="label label-warning">Pending</span>';
                            amount = '';
                            reason = '';
                        }else if(element.status == "approved"){
                            status = '<span class="label label-success">Approved</span>';
                            amount = '';
                            reason = '';
                        }else if(element.status == "rejected"){
                            status = '<span class="label label-danger">Rejected</span>';
                            amount = '';
                            reason = '';
                        }else if(element.status == "partial-approved" || element.status == "partial-approve"){
                            status = '<span class="label label-info">Partial approved</span>';
                            amount = '<span class="">'+element.amount+'</span>';
                            reason = '<span class="">'+element.reason+'</span>';
                        }
                        if(reason){
                            htmlForm += '<p class="form-control-static">' + status + ', <b>Amount</b> - '+ amount +', <b>Reason</b> - '+reason+'</p>';
                        }else{
                            htmlForm += '<p class="form-control-static">' + status + '</p>';
                        }

                        htmlForm += '</div></div>';
                    });
                    htmlForm += '<div class="form-group" style="border: none;"><label for="first_name" class="col-md-4 control-label"><span class="text-danger"></span></label></div>';

                    htmlForm += '<div class="form-group amount"><label class="col-md-4 control-label" for="amount">Amount</label><div class="col-md-8"><input type="text" id="amount" name="amount" class="form-control" placeholder="Enter amount"></div></div><div class="form-group"><label class="col-md-4 control-label" for="reason">Reason</label><div class="col-md-8"><input type="text" id="reason" name="reason" class="form-control" placeholder="Enter reason"></div></div>';
                    htmlForm += '<div class="form-group form-actions"><div class="col-xs-12 text-right"><button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button><button type="submit" class="btn btn-sm btn-primary iconbg">Submit</button></div></div>';

                    $('#modal-user-settings form').html(htmlForm);
                    $('#modal-user-settings form').attr('action',formAction);
                    $('#modal-user-settings').modal('show', id);

                }

            }
        });

    }else if(type == "approved_by_super_admin"){
        $('#modal-user-settings .modal-title').text('Super Admin Approve expense');

        $.ajax({
            url: base_url + "admin/expenses/viewApprovalStatus/" + id,
            method: 'GET',
            success:function(data) {
                var resultData = JSON.parse(data);
                if(typeof(resultData.error) != "undefined"){
                    alert(resultData.message);return;
                }else{
                    var createUserHtml = '';
                    resultData.forEach(element => {
                        htmlForm += '<div class="form-group"><label for="first_name" class="col-md-4 control-label">';
                        htmlForm += element.name;
                        htmlForm += '<span class="text-danger"></span></label><div class="col-md-7">';

                        if(element.status == "pending"){
                            status = '<span class="label label-warning">Pending</span>';
                            amount = '';
                            reason = '';
                        }else if(element.status == "approved"){
                            status = '<span class="label label-success">Approved</span>';
                            amount = '';
                            reason = '';
                        }else if(element.status == "rejected"){
                            status = '<span class="label label-danger">Rejected</span>';
                            amount = '';
                            reason = '';
                        }else if(element.status == "partial-approved" || element.status == "partial-approve"){
                            status = '<span class="label label-info">Partial approved</span>';
                            amount = '<span class="">'+element.amount+'</span>';
                            reason = '<span class="">'+element.reason+'</span>';
                        }
                        if(reason){
                            htmlForm += '<p class="form-control-static">' + status + ', <b>Amount</b> - '+ amount +', <b>Reason</b> - '+reason+'</p>';
                        }else{
                            htmlForm += '<p class="form-control-static">' + status + '</p>';
                        }

                        htmlForm += '</div></div>';
                    });
                    htmlForm += '<div class="form-group" style="border: none;"><label for="first_name" class="col-md-4 control-label"><span class="text-danger"></span></label></div>';

                    htmlForm += '<div class="form-group"><label class="col-md-4 control-label" for="reason"></label><div class="col-md-8"><p class="form-control-static">Are you sure to approve?</p></div></div>';
                    htmlForm += '<div class="form-group form-actions"><div class="col-xs-12 text-right"><button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button><button type="submit" class="btn btn-sm btn-primary iconbg">Submit</button></div></div>';

                    $('#modal-user-settings form').html(htmlForm);
                    $('#modal-user-settings form').attr('action',formAction);
                    $('#modal-user-settings').modal('show', id);

                }

            }
        });
    }else if(type == "approved_by_admin"){
        $('#modal-user-settings .modal-title').text('Admin Approve expense');

        $.ajax({
            url: base_url + "admin/expenses/viewApprovalStatus/" + id,
            method: 'GET',
            success:function(data) {
                var resultData = JSON.parse(data);
                if(typeof(resultData.error) != "undefined"){
                    alert(resultData.message);return;
                }else{
                    var createUserHtml = '';
                    resultData.forEach(element => {
                        htmlForm += '<div class="form-group"><label for="first_name" class="col-md-4 control-label">';
                        htmlForm += element.name;
                        htmlForm += '<span class="text-danger"></span></label><div class="col-md-7">';

                        if(element.status == "pending"){
                            status = '<span class="label label-warning">Pending</span>';
                            amount = '';
                            reason = '';
                        }else if(element.status == "approved"){
                            status = '<span class="label label-success">Approved</span>';
                            amount = '';
                            reason = '';
                        }else if(element.status == "rejected"){
                            status = '<span class="label label-danger">Rejected</span>';
                            amount = '';
                            reason = '';
                        }else if(element.status == "partial-approved" || element.status == "partial-approve"){
                            status = '<span class="label label-info">Partial approved</span>';
                            amount = '<span class="">'+element.amount+'</span>';
                            reason = '<span class="">'+element.reason+'</span>';
                        }
                        if(reason){
                            htmlForm += '<p class="form-control-static">' + status + ', <b>Amount</b> - '+ amount +', <b>Reason</b> - '+reason+'</p>';
                        }else{
                            htmlForm += '<p class="form-control-static">' + status + '</p>';
                        }

                        htmlForm += '</div></div>';
                    });
                    htmlForm += '<div class="form-group" style="border: none;"><label for="first_name" class="col-md-4 control-label"><span class="text-danger"></span></label></div>';

                    htmlForm += '<div class="form-group"><label class="col-md-4 control-label" for="reason"></label><div class="col-md-8"><p class="form-control-static">Are you sure to approve?</p></div></div>';
                    htmlForm += '<div class="form-group form-actions"><div class="col-xs-12 text-right"><button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button><button type="submit" class="btn btn-sm btn-primary iconbg">Submit</button></div></div>';

                    $('#modal-user-settings form').html(htmlForm);
                    $('#modal-user-settings form').attr('action',formAction);
                    $('#modal-user-settings').modal('show', id);

                }

            }
        });

    }else{
        $('#modal-user-settings .modal-title').text('Reject expense');
        htmlForm = '<div class="form-group"><label class="col-md-4 control-label" for="reason">Reason</label><div class="col-md-8"><input type="text" id="reason" name="reason" class="form-control" placeholder="Enter reason"></div></div>';
        htmlForm += '<div class="form-group form-actions"><div class="col-xs-12 text-right"><button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button><button type="submit" class="btn btn-sm btn-primary iconbg">Submit</button></div></div>';

        $('#modal-user-settings form').html(htmlForm);
        $('#modal-user-settings form').attr('action',formAction);
        $('#modal-user-settings').modal('show', id);
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