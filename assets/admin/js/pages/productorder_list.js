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
                columnDefs: [ { orderable: false, targets: [ 5 ] }],
                pageLength: 10,
                lengthMenu: [[10, 20, 30, -1], [10, 20, 30, 'All']]
            });

            /* Add placeholder attribute to the search input */
            $('.dataTables_filter input').attr('placeholder', 'Search');
        }
    };
}();

$(function(){ TablesDatatables.init(); });
//
// // search by category
// $('#select_employee, #start_date, #end_date').change(function(){
//     var action = $('#viewBycity').attr('action') ;
//     $('#viewBycity').attr('action', action);
//     $('#viewBycity').submit();
// });

function show_confirm(base_url, act, cid) {
    if (act == 'view') {
        $.ajax({
            url: base_url + "admin/productorder/getCurrentPo",
            method: 'POST',
            data: {id: cid},
            success: function (data) {
                var resultData = JSON.parse(data);
                var createUserHtml = '<div class="table-responsive">\n' +
                    '            <table id="example-datatable" class="table table-vcenter table-condensed table-bordered">' +
                    '                <thead>\n' +
                    '                    <tr>\n' +
                    '                        <th class="text-center">Product</th>\n' +
                    '                        <th class="text-center">Nos</th>\n' +
                    '                        <th class="text-center">Rate</th>\n' +
                    '                        <th class="text-center">Unit Price</th>\n' +
                    '                        <th class="text-center">Weight</th>\n' +
                    '                    </tr>\n' +
                    '                </thead>' +
                    '                <tbody>\n';


                if (resultData) {
                    for (var x in resultData) {
                        //         for(var y in keyArray){
                        var valS = '<tr>\n' +
                        '                        <td class="text-center">'+resultData[x]["name"]+'</td>\n' +
                        '                        <td class="text-center">'+resultData[x]["nos"]+'</td>\n' +
                        '                        <td class="text-center">'+resultData[x]["rate"]+'</td>\n' +
                        '                        <td class="text-center">'+resultData[x]["unit_price"]+'</td>\n' +
                        '                        <td class="text-center">'+resultData[x]["weight"]+'</td>\n' +
                        '                    </tr>\n';
                        // var valS = resultData[x]['id'];
                        //             var keY = secArray[y];
                        //             if( valS == null){
                        //                 // no html add here
                        //             }else{
                        //                 createUserHtml += '<div class="form-group"><label for="first_name" class="col-md-4 control-label">';
                        createUserHtml += valS;
                        //                 createUserHtml += '<span class="text-danger"></span></label><div class="col-md-7">';
                        //
                        //                 if( keY == 'Photo'){
                        //                     var thumb = resultData[x][keyArray[y]];
                        //                     var large = resultData[x]['pmtLargeImg'];
                        //                     createUserHtml += '<a href="'+ large + '" target="_blank"><img ng-src="' + thumb + '" src="' + thumb + '" alt="" width="100" height="100"></a>';
                        //                 }else{
                        //                     createUserHtml += '<p class="form-control-static">' + resultData[x][keyArray[y]] + '</p>';
                        //                 }
                        //                 createUserHtml += '</div></div>';
                        //             }
                        //         }
                    }
                    createUserHtml +='</tbody>' +
                    '                </table>' +
                    '               </div>';
                    //     createUserHtml += '<div class="form-group" style="border: none;"><label for="first_name" class="col-md-4 control-label"><span class="text-danger"></span></label></div>';
                    $('#normalmodel').html(createUserHtml);
                    $('#viewTitle strong').text('View Product Order details')
                    $('#view-users').modal('show');
                }
                console.log(resultData);

            }
        });
    }
}