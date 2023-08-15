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
                /* columnDefs: [ { orderable: false, targets: [ 3 ] } ], */
                pageLength: 10,
                lengthMenu: [[10, 20, 30, -1], [10, 20, 30, 'All']]
            });

            /* Add placeholder attribute to the search input */
            $('.dataTables_filter input').attr('placeholder', 'Search');
        }
    };
}();

$(function(){ TablesDatatables.init(); });

var validFunction = '';

$( document ).ready(function(){
  $('.alert-danger').fadeOut(5000);
  $('.alert-success').fadeOut(5000);
});

function show_confirm(base_url, act, cid)
{
  $.ajax({
    url: base_url + "admin/expenses/getCatCount/"+cid,
    method: 'GET',
    success:function(data) {
      var resultData = JSON.parse(data);
      console.log(resultData);
      if(resultData.length == 0){
        alert('Getting some error, please try again')
      }else{
        if(resultData.count){
          alert('You can not delete this category, already associate with others.')
        }else{
            var formAction = base_url + "admin/expenses/delete/" + cid;
            $('#modal-delete form').attr('action',formAction);
            $('#modal-delete').modal('show',cid);   
        }
      }
    }
  });
  
}