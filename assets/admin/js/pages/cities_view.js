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
                columnDefs: [ { orderable: false, targets: [ 4 ] } ],
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
  if(act == 'view'){
      $.ajax({
        url: base_url + "admin/cities/getCurrentCity",
        method: 'POST',
        data: { id: cid },
        success:function(data) {
          var resultData = JSON.parse(data);
          var createUserHtml = '';
          var keyArray = ['city_name','grade'];
          var secArray = ['City Name','City Grade'];

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
                    createUserHtml += '<span class="text-danger"></span></label><div class="col-md-7"><p class="form-control-static">';
                    if(keY == 'Status'){ if(valS == '1'){ createUserHtml += 'Active'; }else{ createUserHtml += 'Inactive'; }  
                    }else{ createUserHtml += resultData[x][keyArray[y]];   }                    
                    createUserHtml += '</p></div></div>';
                }
              }
            }
            createUserHtml += '<div class="form-group" style="border: none;"><label for="first_name" class="col-md-4 control-label"><span class="text-danger"></span></label></div>';
            $('#normalmodel').html(createUserHtml);
            $('#view-users').modal('show');
          }
        }
      });
  }else{
      var formAction = base_url + "admin/cities/delete/" + cid;
      $('#modal-delete form').attr('action',formAction);
      $('#modal-delete').modal('show',cid);
  }
}