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
                columnDefs: [ { orderable: false, targets: [ 6 ] } ],
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

  // $("#search-user").on("change", function() {
  //   var value = $(this).val().toLowerCase();
  //   $('#example-datatable').DataTable().page.len(-1).draw();
  //   $("#userlist-table tr").filter(function() {
  //     $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
  //   });
  // });

  // $("#parent_id").on("change", function() {
  //   var value = $(this).val().toLowerCase();
  //   $('#example-datatable').DataTable().page.len(-1).draw();
  //   $("#userlist-table tr").filter(function() {
  //     $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
  //   });
  // });
  /*On page change value*/
  // $(".pagination").on("click", function(){
  //   var value = $("#search-user").val().toLowerCase();
  //   $("#userlist-table tr").filter(function() {
  //     $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
  //   });
  // })

  // $('select[name="example-datatable_length"]').on('change', function(){    
  //   $("#example-datatable_length select").val("-1"); 
  // });

});

function show_confirm(base_url, act, cid)
{
  if(act == 'view'){
      $.ajax({
        url: base_url + "admin/users/getCurrentUser",
        method: 'POST',
        data: { id: cid },
        success:function(data) {
          var resultData = JSON.parse(data);
          var createUserHtml = '';
          var keyArray = ['first_name','last_name','userType','parent_name','emp_id','email','phone','address','da_amount','status'];
          var secArray = ['First Name','Last Name','Type of user','Parent user','Employee ID','Email Address','Phone','Address','DA Amount','Status'];

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
          }

        }
      });
      /*var formAction = base_url + "admin/users/delete/" + cid;
      $('#modal-delete form').attr('action',formAction);
      $('#modal-delete').modal('show',cid);*/
  }else{
   /*  $('#notdelete span').html('USERS');
    $('#notdelete').show();
    $('#notdelete').fadeOut(7000); */
      var formAction = base_url + "admin/users/delete/" + cid;
      $('#modal-delete form').attr('action',formAction);
      $('#modal-delete').modal('show',cid);
  }
}

function reset_device($base_url, id){
  $.ajax({
      url: base_url + "admin/users/resetDevice",
      method: 'POST',
      data: { id: id },
      success:function(data) { 
        console.log('Hello');
      }
  })
}

$('#reset-btn').click(function(){    
    $('#search-user').val('');
    $('#parent_id').val('');
    $('#userSearchForm').submit();
})