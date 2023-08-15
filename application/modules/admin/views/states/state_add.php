<!-- Page content -->
<div id="page-content">
	<div class="row">
	        <div class="col-md-7 col-md-offset-2-5">
	            <!-- Basic Form Elements Block -->
	            <div class="block">
	                <!-- Basic Form Elements Title -->
	                <div class="block-title">
	                    <div class="block-options pull-right">
	                        <a title="" data-toggle="tooltip" class="btn btn-alt btn-sm btn-default" href="<?php echo BASE_URL.'admin/states'; ?>" data-original-title="Close"><i class="fa fa-times"></i></a>
	                    </div>
	                    <h2><strong>Add State</strong></h2>
	                </div>
	                <!-- END Form Elements Title -->

	                <!-- Basic Form Elements Content -->
	                <form id = "form-validation" class="form-horizontal form-bordered" enctype="multipart/form-data" method="post" action="<?php echo BASE_URL.'admin/states/insert'; ?>">
	                	<div class="form-group">
	                        <label for="country_id" class="col-md-4 control-label">Country Name<span class="text-danger">*</span></label>
	                        <div class="col-md-7">
	                        	<select size="1" name="country_id" class="form-control select-chosen" onchange="getZones(this)">
	                        		<option value = ''>Select Country</option>
	                        		<?php 
	                        		if($countrylist){
	                        			for($i = 0; $i < count($countrylist); $i++){
	                        				echo "<option value='".$countrylist[$i]->country_id."' tag='".str_replace('/', '_',rtrim(base64_encode($countrylist[$i]->country_id), '='))."'>".$countrylist[$i]->country_name."</option>";	
                        				}
                        			}
	                        		?>
	                        	</select>
	                            <?php if(form_error('country_id')){ ?>
			                        <div id="country_id-2-error" class="help-block animation-slideDown falseForm">
		                            	<?php echo form_error('country_id'); ?>
		                            </div>
	                            <?php } ?>
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <label for="zone_id" class="col-md-4 control-label">Zone Name<span class="text-danger">*</span></label>
	                        <div class="col-md-7">
	                        	<select size="1" name="zone_id" class="form-control select-chosen" id="zone_id">
	                        		<option value = ''>Select Zone</option>
	                        		<!-- <?php 
	                        		if($countrylist){
	                        			for($i = 0; $i < count($countrylist); $i++){
	                        				echo "<option value='".$countrylist[$i]->zone_id."'>".$countrylist[$i]->country_name."</option>";	
                        				}
                        			}
	                        		?> -->
	                        	</select>
	                            <?php if(form_error('zone_id')){ ?>
			                        <div id="zone_id-2-error" class="help-block animation-slideDown falseForm">
		                            	<?php echo form_error('zone_id'); ?>
		                            </div>
	                            <?php } ?>
	                        </div>
	                    </div>
                        <div class="form-group">
	                        <label for="state_name" class="col-md-4 control-label">State<span class="text-danger">*</span></label>
	                        <div class="col-md-7">
	                            <input type="text" placeholder="State name" class="form-control" name="state_name" id="state_name" value="<?php if(set_value('state_name')){ echo set_value('state_name'); } ?>">
                                <?php if(form_error('state_name')){ ?>
			                        <div id="state_name-2-error" class="help-block animation-slideDown falseForm">
		                            	<?php echo form_error('state_name'); ?>
		                            </div>
	                            <?php } ?>
	                        </div>
	                    </div>
						<div class="form-group form-actions">
	                        <div class="col-md-9 col-md-offset-4">
	                            <button class="btn btn-sm btn-primary" type="submit"><i class="fa fa-angle-right"></i> Add State</button>
	                        </div>
	                    </div>
	                </form>
	                <!-- END Basic Form Elements Content -->
	            </div>
	            <!-- END Basic Form Elements Block -->
	        </div>
	</div>
<!-- </div> -->
<!-- END Page Content -->	
<script>
function getZones(event)
{	
	var id = $('option:selected', event).attr('tag'); //$(event).attr('tag');
	// console.log('id',id);
	// console.log('event',event);
	var  base_url = "<?php echo BASE_URL; ?>";
	$.ajax({
        url: base_url + "admin/states/getZones/"+id,
        method: 'GET',
        success:function(data) {
        	var new_data = jQuery.parseJSON(data);
        	var i;
        	var html = "<option value=''>Select Zone</option>";
			for (i = 0; i < new_data.length; i++) {
			  //console.log(new_data[i].zone_name);
			  html +="<option value='"+new_data[i].zone_id+"'>"+new_data[i].zone_name+"</option>";
			}
			//$('#zone_id').html('');
			$('#zone_id').html(html);
        }
    });
}
</script>