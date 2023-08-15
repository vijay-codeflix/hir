<!-- Page content -->
<div id="page-content">
	<div class="row">
	        <div class="col-md-7 col-md-offset-2-5">
	            <!-- Basic Form Elements Block -->
	            <div class="block">
	                <!-- Basic Form Elements Title -->
	                <div class="block-title">
	                    <div class="block-options pull-right">
	                        <a title="" data-toggle="tooltip" class="btn btn-alt btn-sm btn-default" href="<?php echo BASE_URL.'admin/dealertypes'; ?>" data-original-title="Close"><i class="fa fa-times"></i></a>
	                    </div>
	                    <h2><strong>Edit Dealer Type</strong></h2>
	                </div>
	                <!-- END Form Elements Title -->

	                <!-- Basic Form Elements Content -->
	                <form id = "form-validation" class="form-horizontal form-bordered" method="post" action="<?php echo BASE_URL.'admin/dealertypes/update'; ?>">
	                	<input type="hidden" name="id" id="id" value="<?php echo $dealer_type_details[0]->id; ?>" >
                        <div class="form-group">
	                        <label for="Dealer" class="col-md-4 control-label">Dealer Type Name<span class="text-danger">*</span></label>
	                        <div class="col-md-7">
	                            <input type="text" placeholder="Dealer Type name" class="form-control" name="name" id="name" value="<?php if(set_value('name')){ echo set_value('name'); }else{ echo $dealer_type_details[0]->name; } ?>">
                                <?php if(form_error('name')){ ?>
			                        <div id="name-2-error" class="help-block animation-slideDown falseForm">
		                            	<?php echo form_error('name'); ?>
		                            </div>
	                            <?php } ?>
	                        </div>
	                    </div>
						<div class="form-group form-actions">
	                        <div class="col-md-9 col-md-offset-4">
	                            <button class="btn btn-sm btn-primary" type="submit"><i class="fa fa-angle-right"></i> Update Dealer Type</button>
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