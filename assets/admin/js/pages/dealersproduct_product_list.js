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
                    // { "targets":  [ 1 ], "visible": false, "searchable": false },
                ],
                pageLength: 10,
                lengthMenu: [[10, 20, 30, -1], [10, 20, 30, 'All']]
            });

            /* Add placeholder attribute to the search input */
            $('.dataTables_filter input').attr('placeholder', 'Search');
            
        }
    };
}();

// $("#example-datatable tr").css('cursor', 'pointer');

$(function(){ TablesDatatables.init(); });

// $("td.editable").click(function(){
//     console.log($(this).attr("contenteditable"));
//     if($(this).attr("contenteditable")){
//         $('td').attr("contenteditable","false");
//         $(this).attr("contenteditable", "true")
//         console.log('sbcjh');
//     } else {
//         $(this).attr("contenteditable","true");
//     }
// });

function saveData(e){
    if(event.keyCode == 13) {
        console.log($(e).text());
        console.log($(e).attr("data-productId"));
        $(e).attr('contenteditable', "false");
        e.blur(); 
    }
}

$('td.editable').dblclick(function(event) {
    $this = $(this);
    $this.attr('contenteditable', "true");
    // $this.blur();
    $this.focus();
});

// $('table td.editable').on('input', function () {
//   console.log($(this).text());
// });

$('table td.editable').on('blur', function () {
  $(this).attr('contenteditable', "false");
//   console.log("blur new value : "+$(this).text());
//   console.log($(this).attr("data-productId"));
});