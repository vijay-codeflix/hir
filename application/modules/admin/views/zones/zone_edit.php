<!-- Page content -->
<div id="page-content">
	<div class="row">
	        <div class="col-md-7 col-md-offset-2-5">
	            <!-- Basic Form Elements Block -->
	            <div class="block">
	                <!-- Basic Form Elements Title -->
	                <div class="block-title">
	                    <div class="block-options pull-right">
	                        <a title="" data-toggle="tooltip" class="btn btn-alt btn-sm btn-default" href="<?php echo BASE_URL.'admin/zones'; ?>" data-original-title="Close"><i class="fa fa-times"></i></a>
	                    </div>
	                    <h2><strong>Edit Zone</strong></h2>
	                </div>
	                <!-- END Form Elements Title -->

	                <!-- Basic Form Elements Content -->
	                <form id = "form-validation" class="form-horizontal form-bordered" method="post" action="<?php echo BASE_URL.'admin/zones/update'; ?>">
	                	<input type="hidden" name="zone_id" id="zone_id" value="<?php echo $zone_Details[0]->zone_id; ?>" >
	                	<div class="form-group">
	                        <label for="country_id" class="col-md-4 control-label">Country Name<span class="text-danger">*</span></label>
	                        <div class="col-md-7">
	                        	<select size="1" name="country_id" class="form-control">
	                        		<option value = ''>Select Country</option>
	                        		<?php 
	                        		if($countrylist){
	                        			for($i = 0; $i < count($countrylist); $i++){
	                        				$select = ($countrylist[$i]->country_id == set_value('country_id') ? 'Selected' : ($zone_Details[0]->country_id == $countrylist[$i]->country_id ? 'Selected' : ''));
	                        			 	echo "<option value='".$countrylist[$i]->country_id."' Selected='".$select."'>".$countrylist[$i]->country_name."</option>";	
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
                        	<label for="zone" class="col-md-4 control-label">Zone<span class="text-danger">*</span></label>
	                        <div class="col-md-7">
	                            <input type="text" placeholder="zone name" class="form-control" name="zone_name" id="zone_name" value="<?php if(set_value('zone_name')){ echo set_value('zone_name'); }else{ echo $zone_Details[0]->zone_name; } ?>">
                                <?php if(form_error('zone_name')){ ?>
			                        <div id="zone_name-2-error" class="help-block animation-slideDown falseForm">
		                            	<?php echo form_error('zone_name'); ?>
		                            </div>
	                            <?php } ?>
	                        </div>
	                    </div>
						<div class="form-group form-actions">
	                        <div class="col-md-9 col-md-offset-4">
	                            <button class="btn btn-sm btn-primary" type="submit"><i class="fa fa-angle-right"></i> Update Zone</button>
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