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
            var table = $('#example-datatable').DataTable({
                columnDefs: [ { orderable: false, targets: [ 5 ] }],
                pageLength: 10,
                lengthMenu: [[10, 20, 30, -1], [10, 20, 30, 'All']]
            });

            /* Add placeholder attribute to the search input */
            $('.dataTables_filter input').attr('placeholder', 'Search');
            
            // $('#example-datatable').on('click', 'tr', function () {
            //     var data = table.row(this).data();
            //     alert('You clicked on ' + data[1] + "'s row");
            // });
        }
    };
}();


$(function(){ TablesDatatables.init(); });

// search by category
$('#category').change(function(){
    var action = $('#viewBycity').attr('action') ;
    $('#viewBycity').attr('action', action);
    $('#viewBycity').submit();
});

var fileName = "";
$('#csv').change(function(e){
    fileName = e. target. files[0]. name;
});

$('#importCsv').click(function(){
    if(fileName === ""){
        alert("Please select CSV file to import.");
        return;
    }else{
        var nameVal = fileName.split(".");
        var fileType = nameVal[nameVal.length-1];
        if(fileType.toLowerCase() != "csv"){
            alert("Please select valid CSV file to import dealers!!");
            return;
        }else{
            $('#importCsvForm').submit();
        }
    }
});

function show_confirm(base_url, act, cid)
{
    if(act == 'view'){

        $.ajax({
            url: base_url + "admin/products/getListView",
            method: 'POST',
            data: { id: cid },
            success:function(data) {
                console.log(data);
                var resultData = JSON.parse(data);
                var createUserHtml = '';
                var keyArray = ['name','item_code','unit','ah','mrp' ];
                var secArray = ['Product Name','Item Code','unit','AH','MRP'];

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
    }else{
        var formAction = base_url + "admin/products/delete/" + cid;
        $('#modal-delete form').attr('action',formAction);
        $('#modal-delete').modal('show',cid);
    }
}