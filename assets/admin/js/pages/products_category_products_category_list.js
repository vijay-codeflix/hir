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
                    { orderable: false, targets: [ 4 ] },
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
                $('<form action="products" method="post"><input type="hidden" name="category" value="'+ data[1] +'"></input></form>').appendTo('body').submit().remove();
            });
        }
    };
}();

$("#example-datatable tr").css('cursor', 'pointer');

$(function(){ TablesDatatables.init(); });