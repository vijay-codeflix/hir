/*
 *  Document   : tablesDatatables.js
 *  Author     : pixelcave
 *  Description: Custom javascript code used in Tables Datatables page
 */

// var TablesDatatables = function() {
//
//     return {
//         init: function() {
//             /* Initialize Bootstrap Datatables Integration */
//             App.datatables();
//
//             /* Initialize Datatables */
//             $('#example-datatable').dataTable({
//                 columnDefs: [ { orderable: false, targets: [ 7 ] }],
//                 pageLength: 10,
//                 lengthMenu: [[10, 20, 30, -1], [10, 20, 30, 'All']]
//             });
//
//             /* Add placeholder attribute to the search input */
//             $('.dataTables_filter input').attr('placeholder', 'Search');
//         }
//     };
// }();

// $(function(){ TablesDatatables.init(); });
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
            url: base_url + "admin/states/getCurrentEnquiry",
            method: 'POST',
            data: {id: cid},
            success: function (data) {
                var resultData = JSON.parse(data);
                var createUserHtml = '<div class="table-responsive">\n' +
                    '            <table id="example-datatable" class="table table-vcenter table-condensed table-bordered">' +
                    '                <thead>\n' +
                    '                    <tr>\n' +
                    '                        <th class="text-center">Product</th>\n' +
                    '                        <th class="text-center">Category</th>\n' +
                    '                    </tr>\n' +
                    '                </thead>' +
                    '                <tbody>\n';


                if (resultData) {
                    for (var x in resultData) {
                        //         for(var y in keyArray){
                        var valS = '<tr>\n' +
                            '                        <td class="text-center">'+resultData[x]["product_name"]+'</td>\n' +
                            '                        <td class="text-center">'+resultData[x]["category_name"]+'</td>\n' +
                            '                    </tr>\n';
                        createUserHtml += valS;

                    }
                    createUserHtml +='</tbody>' +
                        '                </table>' +
                        '               </div>';
                    $('#normalmodel').html(createUserHtml);
                    $('#viewTitle strong').text('View Product Order details')
                    $('#view-users').modal('show');
                }
                console.log(resultData);

            }
        });
    } else{
      var formAction = base_url + "admin/states/delete/" + cid;
      $('#modal-delete form').attr('action',formAction);
      $('#modal-delete').modal('show',cid);
  }
}