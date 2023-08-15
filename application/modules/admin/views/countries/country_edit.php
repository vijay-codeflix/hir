<!-- Page content -->
<div id="page-content">
	<div class="row">
	        <div class="col-md-7 col-md-offset-2-5">
	            <!-- Basic Form Elements Block -->
	            <div class="block">
	                <!-- Basic Form Elements Title -->
	                <div class="block-title">
	                    <div class="block-options pull-right">
	                        <a title="" data-toggle="tooltip" class="btn btn-alt btn-sm btn-default" href="<?php echo BASE_URL.'admin/countries'; ?>" data-original-title="Close"><i class="fa fa-times"></i></a>
	                    </div>
	                    <h2><strong>Edit Country</strong></h2>
	                </div>
	                <!-- END Form Elements Title -->

	                <!-- Basic Form Elements Content -->
	                <form id = "form-validation" class="form-horizontal form-bordered" method="post" action="<?php echo BASE_URL.'admin/countries/update'; ?>">
	                	<input type="hidden" name="country_id" id="country_id" value="<?php echo $country_Details[0]->country_id; ?>" >
                        <div class="form-group">
	                        <label for="Country" class="col-md-4 control-label">Country<span class="text-danger">*</span></label>
	                        <div class="col-md-7">
	                            <input type="text" placeholder="Country name" class="form-control" name="country_name" id="country_name" value="<?php if(set_value('country_name')){ echo set_value('country_name'); }else{ echo $country_Details[0]->country_name; } ?>">
                                <?php if(form_error('country_name')){ ?>
			                        <div id="country_name-2-error" class="help-block animation-slideDown falseForm">
		                            	<?php echo form_error('country_name'); ?>
		                            </div>
	                            <?php } ?>
	                        </div>
	                    </div>
						<div class="form-group form-actions">
	                        <div class="col-md-9 col-md-offset-4">
	                            <button class="btn btn-sm btn-primary" type="submit"><i class="fa fa-angle-right"></i> Update Country</button>
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