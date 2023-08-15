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
                columnDefs: [ { orderable: false, targets: [ 6 ] }],
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
    $.ajax({
        url: base_url + "admin/visits/getListView",
        method: 'POST',
        data: { id: cid },
        success:function(data) {
            var resultData = JSON.parse(data);
            console.log(resultData);
            resultData['0']['name'] = resultData['0']['first_name']+' '+resultData['0']['last_name'];
            var createUserHtml = '';
            var keyArray = ['name','contact_firm','contact_person','visited_at','area_or_town','discuss_duration','discuss_point','remark'];
            var secArray = ['Employee Name','Contact Firm','Contact Person Details','Visited Date','Area','Discuss Duration','Discuss Point','Remark'];

            if(resultData){
                for(var x in resultData){
                    for(var y in keyArray){
                        var valS = resultData[x][keyArray[y]];
                        var keY = secArray[y];
                        
                        if(secArray[y] == 'Contact Person Details'){
                            createUserHtml += '<div class="form-group"><label for="first_name" class="col-md-4 control-label">';
                            createUserHtml += secArray[y];
                            createUserHtml += '<span class="text-danger"></span></label><div class="col-md-7">';
                            
                            // if empty add details from dealer_owner table
                            if(valS === null){
                                // console.log(resultData['0']['party']['contact_person_details']);
                                // console.log(secArray[y]);
                                createUserHtml += '<p class="form-control-static">' + resultData['0']['party']['contact_person_details'] + '</p>';  
                            }else{
                                createUserHtml += '<p class="form-control-static">' + resultData[x]['contact_person'] + '-' + resultData[x]['contact_person_phone'] + '</p>';
                            }
                            
                            createUserHtml += '</div></div>';
                        }else{
                            createUserHtml += '<div class="form-group"><label for="first_name" class="col-md-4 control-label">';
                            createUserHtml += secArray[y];
                            createUserHtml += '<span class="text-danger"></span></label><div class="col-md-7">';
                            createUserHtml += '<p class="form-control-static">' + resultData[x][keyArray[y]] + '</p>';  
                            createUserHtml += '</div></div>';
                            
                        }
                    }
                }
                createUserHtml += '<div class="form-group" style="border: none;"><label for="first_name" class="col-md-4 control-label"><span class="text-danger"></span></label></div>';
                $('#normalmodel').html(createUserHtml);
                $('#viewTitle strong').text('View details')
                $('#view-users').modal('show');
            }
        }
    });
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