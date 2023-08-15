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
                columnDefs: [ 
                    { orderable: false, targets: [ 3 ] },
                    { "targets":  [ 1 ], "visible": false, "searchable": false },
                ],
                pageLength: 10,
                lengthMenu: [[10, 20, 30, -1], [10, 20, 30, 'All']]
            });

            /* Add placeholder attribute to the search input */
            $('.dataTables_filter input').attr('placeholder', 'Search');
            
            $('#example-datatable').on('click', 'tr', function () {
                var data = table.row(this).data();
                // window.location.href = "products";
                $('<form action="dealersproduct/product_list" method="post"><input type="hidden" name="dealer_id" value="'+ data[1] +'"></input></form>').appendTo('body').submit().remove();
            });
        }
    };
}();

$("#example-datatable tr").css('cursor', 'pointer');

$(function(){ TablesDatatables.init(); });

function show_confirm(base_url, act, cid)
{
    // if(act == 'Delete'){
        var formAction = base_url + "admin/dealersproduct/delete/" + cid;
        $('#modal-delete form').attr('action',formAction);
        $('#modal-delete').modal('show',cid);
    // }
}