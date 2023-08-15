<!-- Page content -->
<div id="page-content">
	<div class="row">
	        <div class="col-md-7 col-md-offset-2-5">
	            <!-- Basic Form Elements Block -->
	            <div class="block">
	                <!-- Basic Form Elements Title -->
	                <div class="block-title">
	                    <div class="block-options pull-right">
	                        <a title="" data-toggle="tooltip" class="btn btn-alt btn-sm btn-default" href="<?php echo BASE_URL.'admin/cities/view'; ?>" data-original-title="Close"><i class="fa fa-times"></i></a>
	                    </div>
	                    <h2><strong>Edit City Grade</strong></h2>
	                </div>
	                <!-- END Form Elements Title -->

	                <!-- Basic Form Elements Content -->
	                <form id = "form-validation" class="form-horizontal form-bordered" enctype="multipart/form-data" method="post" action="<?php echo BASE_URL.'admin/cities/update'; ?>">
	                	<input type="hidden" name="id" id="id" value="<?php echo $cities[0]->id; ?>" >
	                	<div class="form-group">
	                        <label for="country_id" class="col-md-4 control-label">Country Name<span class="text-danger">*</span></label>
	                        <div class="col-md-7">
	                        	<select size="1" name="country_id" class="form-control" onchange="getZones(this)">
	                        		<option value = ''>Select Country</option>
	                        		<?php 
	                        		if($countrylist){
	                        			for($i = 0; $i < count($countrylist); $i++){
	                        				if($cities[0]->country_id == $countrylist[$i]->country_id){
	                        					$selected = 'selected';
	                        				}else{
	                        					$selected = 'false';
	                        				}
	                        				echo "<option value='".$countrylist[$i]->country_id."' tag='".str_replace('/', '_',rtrim(base64_encode($countrylist[$i]->country_id), '='))."' ".$selected.">".$countrylist[$i]->country_name."</option>";	
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
	                        	<select size="1" name="zone_id" class="form-control" id="zone_id" onchange="getStates(this)">
	                        		<option value = ''>Select Zone</option>
	                        		<?php 
	                        		if($zonelist){
	                        			for($i = 0; $i < count($zonelist); $i++){
	                        				if($cities[0]->zone_id == $zonelist[$i]->zone_id){
	                        					$selected = 'selected';
	                        				}else{
	                        					$selected = '';
	                        				}
	                        				echo "<option value='".$zonelist[$i]->zone_id."' selected=".$selected.">".$zonelist[$i]->zone_name."</option>";	
                        				}
                        			}
	                        		?>
	                        	</select>
	                            <?php if(form_error('zone_id')){ ?>
			                        <div id="zone_id-2-error" class="help-block animation-slideDown falseForm">
		                            	<?php echo form_error('zone_id'); ?>
		                            </div>
	                            <?php } ?>
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <label for="state_id" class="col-md-4 control-label">State Name<span class="text-danger">*</span></label>
	                        <div class="col-md-7">
	                        	<select size="1" name="state_id" class="form-control" id="state_id">
	                        		<option value = ''>Select State</option>
	                        		<?php 
	                        		if($stateslist){
	                        			for($i = 0; $i < count($stateslist); $i++){
	                        				if($cities[0]->state_id == $stateslist[$i]->state_id){
	                        					$selected = 'selected';
	                        				}else{
	                        					$selected = '';
	                        				}
	                        				echo "<option value='".$stateslist[$i]->state_id."' selected=".$selected.">".$stateslist[$i]->state_name."</option>";	
                        				}
                        			}
	                        		?>
	                        	</select>
	                            <?php if(form_error('state_id')){ ?>
			                        <div id="state_id-2-error" class="help-block animation-slideDown falseForm">
		                            	<?php echo form_error('state_id'); ?>
		                            </div>
	                            <?php } ?>
	                        </div>
	                    </div>
                        <div class="form-group">
	                        <label for="city_name" class="col-md-4 control-label">City<span class="text-danger">*</span></label>
	                        <div class="col-md-7">
	                            <input type="text" placeholder="City name" class="form-control" name="city_name" id="city_name" value="<?php if(set_value('city_name')){ echo set_value('city_name'); }else{ echo $cities[0]->city_name; } ?>">
                                <?php if(form_error('city_name')){ ?>
			                        <div id="city_name-2-error" class="help-block animation-slideDown falseForm">
		                            	<?php echo form_error('city_name'); ?>
		                            </div>
	                            <?php } ?>
	                        </div>
	                    </div>
						<div class="form-group">
	                        <label for="grade" class="col-md-4 control-label">City Grade<span class="text-danger">*</span></label>
	                        <div class="col-md-7">
	                            <select size="1" class="form-control" name="grade" id="grade">
	                                <option value="">Please select</option>
	                                <option <?php if(set_value('grade') == 'A' || $cities[0]->grade == 'A'){ echo 'selected="true'; } ?> value="A">A</option>
	                                <option <?php if(set_value('grade') == 'B' || $cities[0]->grade == 'B'){ echo 'selected="true'; } ?> value="B">B</option>
	                                <option <?php if(set_value('grade') == 'C' || $cities[0]->grade == 'C'){ echo 'selected="true'; } ?> value="C">C</option>
	                                <option <?php if(set_value('grade') == 'D' || $cities[0]->grade == 'D'){ echo 'selected="true'; } ?> value="D">D</option>
	                            </select>
                                <?php if(form_error('grade')){ ?>
			                        <div id="grade-2-error" class="help-block animation-slideDown falseForm">
		                            	<?php echo form_error('grade'); ?>
		                            </div>
	                            <?php } ?>
	                        </div>
	                    </div>

	                    <div class="form-group form-actions">
	                        <div class="col-md-9 col-md-offset-4">
	                            <button class="btn btn-sm btn-primary" type="submit"><i class="fa fa-angle-right"></i> Update City Grade</button>
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

function getStates(event)
{	
	var id = $('option:selected', event).val(); //$(event).attr('tag');
	// console.log('id',id);
	// console.log('event',event);
	var  base_url = "<?php echo BASE_URL; ?>";
	$.ajax({
        url: base_url + "admin/states/getStates/"+id,
        method: 'GET',
        success:function(data) {
        	var new_data = jQuery.parseJSON(data);
        	var i;
        	var html = "<option value=''>Select States</option>";
			for (i = 0; i < new_data.length; i++) {
			  //console.log(new_data[i].zone_name);
			  html +="<option value='"+new_data[i].state_id+"'>"+new_data[i].state_name+"</option>";
			}
			//$('#zone_id').html('');
			$('#state_id').html(html);
        }
    });
}
</script>