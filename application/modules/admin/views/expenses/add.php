<!-- Page content -->
<div id="page-content">
	<div class="row">
	        <div class="col-md-7 col-md-offset-2-5">
	            <!-- Basic Form Elements Block -->
	            <div class="block">
	                <!-- Basic Form Elements Title -->
	                <div class="block-title">
	                    <div class="block-options pull-right">
	                        <a title="" data-toggle="tooltip" class="btn btn-alt btn-sm btn-default" href="<?php echo BASE_URL.'admin/expenses'; ?>" data-original-title="Close"><i class="fa fa-times"></i></a>
	                    </div>
	                    <h2><strong>Add category</strong></h2>
	                </div>
	                <!-- END Form Elements Title -->

	                <!-- Basic Form Elements Content -->
	                <form id = "form-validation" class="form-horizontal form-bordered" enctype="multipart/form-data" method="post" action="<?php echo BASE_URL.'admin/expenses/insert'; ?>">
                        <div class="form-group">
	                        <label for="name" class="col-md-4 control-label">Category<span class="text-danger">*</span></label>
	                        <div class="col-md-7">
	                            <input type="text" placeholder="Category name" class="form-control" name="name" id="name" value="<?php if(set_value('name')){ echo set_value('name'); } ?>">
                                <?php if(form_error('name')){ ?>
			                        <div id="name-2-error" class="help-block animation-slideDown falseForm">
		                            	<?php echo form_error('name'); ?>
		                            </div>
	                            <?php } ?>
	                        </div>
	                    </div>
						<div class="form-group">
	                        <label for="type" class="col-md-4 control-label">Category Type<span class="text-danger">*</span></label>
	                        <div class="col-md-7">
	                            <select size="1" class="form-control select-chosen" name="type" id="type">
	                                <option value="">Please select</option>
	                                <option <?php if(set_value('type') == 'price'){ echo 'selected="true'; } ?> value="price">Limited by price</option>
	                                <option <?php if(set_value('type') == 'non_price'){ echo 'selected="true'; } ?> value="non_price">No price limit</option>
	                            </select>
                                <?php if(form_error('type')){ ?>
			                        <div id="type-2-error" class="help-block animation-slideDown falseForm">
		                            	<?php echo form_error('type'); ?>
		                            </div>
	                            <?php } ?>
	                        </div>
	                    </div>

	                    <div class="form-group form-actions">
	                        <div class="col-md-9 col-md-offset-4">
	                            <button class="btn btn-sm btn-primary" type="submit"><i class="fa fa-angle-right"></i> Add category</button>
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