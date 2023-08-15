  
function dealers(baseurl,e){
    $('#party_id').empty();
   $.ajax({
    url:baseurl+"admin/dealers/getDealersByEmp",
    data:{'emp_id':$("#user_id").val()},
    dataType: "json",
    success: function(response) {   
       if(response) {
        for (const data of response) { 
            $("#party_id").append($('<option>', {
                value: data.id,
                text: data.dealer_name
            })); 
         }
        }
         $('#party_id').trigger('chosen:updated');

    },
    error: function(xhr, status, error) {
        console.error("Error:", error);
    }
}) 
}
 
function isJsonString(str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}

 