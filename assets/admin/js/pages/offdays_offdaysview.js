function show_confirm(base_url, act, cid) {
    if (act == 'view') {
        $.ajax({
            url: base_url + "admin/offdays/getCurrentOffday",
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
                    
                    $('#normalmodel').html('Are you sure you want to delete this offday? ');
                    $('#viewTitle strong').text('Delete Offday')
                    $('#view-users').modal('show');
                }
                console.log(resultData);

            }
        });
    } else{
      var formAction = base_url + "admin/offdays/delete/" + cid;
      $('#modal-delete form').attr('action',formAction);
      $('#modal-delete').modal('show',cid);
  }
}