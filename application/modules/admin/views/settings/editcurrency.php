<!-- Page content -->
<div id="page-content">
	<div class="row">
	        <div class="col-md-7 col-md-offset-2-5">
	            <!-- Basic Form Elements Block -->
	            <div class="block">
	                <!-- Basic Form Elements Title -->
	                <div class="block-title">
	                    <div class="block-options pull-right">
	                        <a title="" data-toggle="tooltip" class="btn btn-alt btn-sm btn-default" href="<?php echo BASE_URL.'admin/settings/currency'; ?>" data-original-title="Close"><i class="fa fa-times"></i></a>
	                    </div>
	                    <h2><strong>Edit currency</strong></h2>
	                </div>
	                <!-- END Form Elements Title -->

	                <!-- Basic Form Elements Content -->
	                <form id = "form-validation" class="form-horizontal form-bordered" enctype="multipart/form-data" method="post" action="<?php echo BASE_URL.'admin/settings/updateCurrency'; ?>">
	                	<input type="hidden" name="id" id="id" value="<?php echo $currency[0]->id; ?>" >
                        <div class="form-group">
	                        <label for="name" class="col-md-4 control-label">Currency name<span class="text-danger">*</span></label>
	                        <div class="col-md-7">
	                            <input type="text" placeholder="Currency name" class="form-control" name="name" id="name" value="<?php if(set_value('name')){ echo set_value('name'); }else{ echo $currency[0]->name; } ?>">
                                <?php if(form_error('name')){ ?>
			                        <div id="name-2-error" class="help-block animation-slideDown falseForm">
		                            	<?php echo form_error('name'); ?>
		                            </div>
	                            <?php } ?>
	                        </div>
	                    </div>
                        <div class="form-group">
	                        <label for="symbol" class="col-md-4 control-label">Currency symbol<span class="text-danger">*</span></label>
	                        <div class="col-md-7">
	                            <input type="text" placeholder="Currency symbol" class="form-control" name="symbol" id="symbol" value="<?php if(set_value('symbol')){ echo set_value('symbol'); }else{ echo $currency[0]->symbol; } ?>">
                                <?php if(form_error('symbol')){ ?>
			                        <div id="symbol-2-error" class="help-block animation-slideDown falseForm">
		                            	<?php echo form_error('symbol'); ?>
		                            </div>
	                            <?php } ?>
	                        </div>
	                    </div>

	                    <div class="form-group form-actions">
	                        <div class="col-md-9 col-md-offset-4">
	                            <button class="btn btn-sm btn-primary" type="submit"><i class="fa fa-angle-right"></i> Update currency</button>
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