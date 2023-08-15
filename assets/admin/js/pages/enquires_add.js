/*
 *  Document   : formsValidation.js
 *  Author     : pixelcave
 *  Description: Custom javascript code used in Forms Validation page
 */
var count =0;
var FormsValidation = function() {

    return {
        init: function() {
            /*
             *  Jquery Validation, Check out more examples and documentation at https://github.com/jzaefferer/jquery-validation
             */

            /* Initialize Form Validation */
            $('#form-validation').validate({
                errorClass: 'help-block animation-slideDown', // You can change the animation class for a different entrance animation - check animations page
                errorElement: 'div',
                errorPlacement: function(error, e) {
                    e.parents('.form-group > div').append(error);
                },
                highlight: function(e) {
                    $(e).closest('.form-group').removeClass('has-success has-error').addClass('has-error');
                    $(e).closest('.help-block').remove();
                },
                success: function(e) {
                    // You can use the following if you would like to highlight with green color the input after successful validation!
                    e.closest('.form-group').removeClass('has-success has-error'); // e.closest('.form-group').removeClass('has-success has-error').addClass('has-success');
                    e.closest('.help-block').remove();
                },
                rules: {
                    // gst_number: {
                    //     required: true
                    // },
                    enquiry_no: {
                        required: true
                    },
                    party_category_id: {
                        required: true
                    },
                    party_id: {
                        required: false
                    },
                    product_category_id: {
                        required: true
                    },
                    product_id: {
                        required: true
                    },
                    // dealer_name: {
                    //     required: true
                    // },
                    // city_or_town: {
                    //     required: true
                    // }
                },
                messages: {
                    // gst_number: {
                    //     required: 'Please provide GST number'
                    // },
                    enquiry_no: {
                        required: 'Please provide enquiry number'
                    },
                    party_category_id: {
                        required: 'Please provide party category'
                    },
                    party_id: {
                        required: 'Please provide party'
                    },
                    product_category_id: {
                        required: 'Please provide party category'
                    },
                    product_id: {
                        required: 'Please provide party'
                    },
                    // dealer_name: {
                    //     required: 'Please provide dealer name'
                    // },
                    // city_or_town: {
                    //     required: 'Please provide city/town'
                    // }
                }
            });
        }

    };
}();

$(function() { 
    FormsValidation.init(); 
    $('#gst_number-2-error').parent().parent().addClass('has-error');
    $('#gst_number-2-error').siblings('input').val('');
    $('#gst_number-2-error').fadeOut(10000, function(){ $(this).parent().parent().removeClass('has-error');$(this).remove(); });
  
    $('#enquiry_no-2-error').parent().parent().addClass('has-error');
    $('#enquiry_no-2-error').siblings('input').val('');
    $('#enquiry_no-2-error').fadeOut(10000, function(){ $(this).parent().parent().removeClass('has-error');$(this).remove(); });
  
    $('#party_category_id-2-error').parent().parent().addClass('has-error');
    $('#party_category_id-2-error').siblings('input').val('');
    $('#party_category_id-2-error').fadeOut(10000, function(){ $(this).parent().parent().removeClass('has-error');$(this).remove(); });
  
    $('#party_id-2-error').parent().parent().addClass('has-error');
    $('#party_id-2-error').siblings('input').val('');
    $('#party_id-2-error').fadeOut(10000, function(){ $(this).parent().parent().removeClass('has-error');$(this).remove(); });
    
    $('#product_category_id-2-error').parent().parent().addClass('has-error');
    $('#product_category_id-2-error').siblings('input').val('');
    $('#product_category_id-2-error').fadeOut(10000, function(){ $(this).parent().parent().removeClass('has-error');$(this).remove(); });
  
    $('#product_id-2-error').parent().parent().addClass('has-error');
    $('#product_id-2-error').siblings('input').val('');
    $('#product_id-2-error').fadeOut(10000, function(){ $(this).parent().parent().removeClass('has-error');$(this).remove(); });
  
    $('#dealer_name-2-error').parent().parent().addClass('has-error');
    $('#dealer_name-2-error').siblings('input').val('');
    $('#dealer_name-2-error').fadeOut(10000, function(){ $(this).parent().parent().removeClass('has-error');$(this).remove(); });
  
    $('#city_or_town-2-error').parent().parent().addClass('has-error');
    $('#city_or_town-2-error').siblings('input').val('');
    $('#city_or_town-2-error').fadeOut(10000, function(){ $(this).parent().parent().removeClass('has-error');$(this).remove(); });
});

$('#party_category_id').change(function(){

        var party_category_id = $('#party_category_id').val();

        if(party_category_id != '')
        {
            $.ajax({
                url:base_url + "admin/enquires/getPartyByCategory/"+party_category_id,
                method:"POST",
                dataType:"JSON",
                success:function(data)
                {
                    // console.log(data);
                    var html = '<option value="">Please select</option>';

                    for(var count = 0; count < data.length; count++)
                    {

                        html += '<option value="'+data[count].id+'">'+data[count].firm_name+'</option>';

                    }

                    $('#party_id').html(html);
                }
            });
        }
        else
        {
            $('#party_id').val('');
        }
    });

$('#product_category_id').change(function(){

        var product_category_id = $('#product_category_id').val();

        if(product_category_id != '')
        {
            $.ajax({
                url:base_url + "admin/enquires/getProductByCategory/"+product_category_id,
                method:"POST",
                dataType:"JSON",
                success:function(data)
                {
                    // console.log(data);
                    var html = '<option value="">Please select</option>';

                    for(var count = 0; count < data.length; count++)
                    {

                        html += '<option value="'+data[count].id+'">'+data[count].name+'</option>';

                    }

                    $('#product_id').html(html);
                }
            });
        }
        else
        {
            $('#product_id').val('');
        }
    });

// $clone_element="<div class='row'> <div class='col-md-12'><div class='block'><div class='block-title'>    <div class='block-options pull-right'>        <button type='button' class='btn btn-primary'>Add</button>    </div>    <h2><strong>Product</strong></h2></div><form action='page_forms_general.php' method='post' class='form-horizontal form-bordered' onsubmit='return false;'>    <div class='form-group'>        <label class='col-md-3 control-label' for='example-hf-email'>Product Category</label>        <div class='col-md-9'>            <select size='1' class='form-control select-chosen' name='product_category_id' id='product_category_id'>                <option value=''>Please select</option>                <?php                foreach ($product_category as $row) {                    echo '<option value='' . $row->id . ''>' . $row->name . '</option>';                }                ?>            </select>            <?php if (form_error('product_category_id')) { ?>                <div id='product_category_id-2-error' class='help-block animation-slideDown falseForm'>                    <?php echo form_error('product_category_id'); ?>                </div>            <?php } ?>        </div>    </div>    <div class='form-group'>        <label class='col-md-3 control-label' for='example-hf-password'>Product</label>        <div class='col-md-9'>            <select size='1' class='form-control select-chosen' name='product_id' id='product_id'>                <option value=''>Please select</option>            </select>            <?php if (form_error('product_id')) { ?>                <div id='product_id-2-error' class='help-block animation-slideDown falseForm'>                    <?php echo form_error('product_id'); ?>                </div>            <?php } ?>        </div>    </div></form></div></div></div>";

clone_element=`
<div class='row'>
							<div class='col-md-12'>
								<div class='block'>
									<div class='block-title'>
										<div class='block-options pull-right'>
											<button type='button' class='btn btn-danger' >Remove</button>
										</div>
										<h2><strong>Product</strong></h2>
									</div>
									<form action='page_forms_general.php' method='post' class='form-horizontal form-bordered' onsubmit='return false;'>
										<div class='form-group'>
											<label class='col-md-3 control-label' for='example-hf-email'>Product Category</label>
											<div class='col-md-9'>
												<select size='1' class='form-control select-chosen' name='product_category_id' id='product_category_id'>
													<option value=''>Please select</option>
													<?php
													foreach ($product_category as $row) {
														echo '<option value='' . $row->id . ''>' . $row->name . '</option>';
													}
													?>
												</select>
												<?php if (form_error('product_category_id')) { ?>
													<div id='product_category_id-2-error' class='help-block animation-slideDown falseForm'>
														<?php echo form_error('product_category_id'); ?>
													</div>
												<?php } ?>
											</div>
										</div>
										<div class='form-group'>
											<label class='col-md-3 control-label' for='example-hf-password'>Product</label>
											<div class='col-md-9'>
												<select size='1' class='form-control select-chosen' name='product_id' id='product_id'>
													<option value=''>Please select</option>
												</select>
												<?php if (form_error('product_id')) { ?>
													<div id='product_id-2-error' class='help-block animation-slideDown falseForm'>
														<?php echo form_error('product_id'); ?>
													</div>
												<?php } ?>
											</div>
										</div>

									</form>
								</div>
							</div>
						</div>
`

$('#clone-product').click(function(){
    addElement();
});

function addElement(){
    count = count + 1;
    clone_element=`
<div class='row' id="container${count}">
							<div class='col-md-12'>
								<div class='block'>
									<div class='block-title'>
										<div class='block-options pull-right'>
											<button type='button' class='btn btn-danger removebtn' id="${'removeClass'+count}" >Remove</button>
										</div>
										<h2><strong>Product</strong></h2>
									</div>
							 			<div class='form-group'>
											<label class='col-md-3 control-label' for='example-hf-email'>Product Category</label>
											<div class='col-md-9'>
												<select size='1' class='form-control select-chosen product_category_id' name='product[${count}][product_category_id]' id='product_category_id${count}'
                                                onchange="product(this,'product_id${count}')">
													<option value=''>Please select</option>
												
                                            
                                                        </select>
												<?php if (form_error('product[${count}][product_category_id]')) { ?>
													<div id='product_category_id${count}-2-error' class='help-block animation-slideDown falseForm'>
														<?php echo form_error('product[${count}][product_category_id]'); ?>
													</div>
												<?php } ?>
											</div>
										</div>
										<div class='form-group'>
											<label class='col-md-3 control-label' for='example-hf-password'>Product</label>
											<div class='col-md-9'>
												<select size='1' class='form-control select-chosen' name='product[${count}][product_id]' id='product_id${count}'>
													<option value=''>Please select</option>
												</select>
												<?php if (form_error('product[${count}][product_id]')) { ?>
													<div id='product_id-2-error' class='help-block animation-slideDown falseForm'>
														<?php echo form_error('product[${count}][product_id]'); ?>
													</div>
												<?php } ?>
											</div>
										</div> 
								</div>
							</div>
						</div>
`;

console.log(count);
$("#clone").append(clone_element);
addOptions(`#product_category_id${count}`,'#product_category_id0');
}
function addOptions(target,source){ 
   $(target).html($(source).html());
}
$(document).on('click','.removebtn', function(e){ 
    var item = this; 
    console.log(this);
    deleteELement(e, item)
 });

function deleteELement(e, item) {
   e.preventDefault(); 
   $(item).parents().eq(4).fadeOut('200', function() { 
     $(item).parents().eq(4).remove();
   });
}

 

function product(element,product_element_id){
 
    id=element.id;
    var product_category_id = $('#'+id).val();
    $('#'+product_element_id).empty();
    console.log('hi')
    if(product_category_id != '')
    {
        $.ajax({
            url:base_url + "admin/enquires/getProductByCategory/"+product_category_id,
            method:"POST",
            dataType:"JSON",
            success:function(data)
            {
                // console.log(data);
                var html = '<option value="">Please select</option>';

                for(var count = 0; count < data.length; count++)
                {

                    html += '<option value="'+data[count].id+'">'+data[count].name+'</option>';

                }

                $('#'+product_element_id).html(html);
            }
        });
    } 

}
 