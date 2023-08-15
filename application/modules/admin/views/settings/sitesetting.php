<!-- Page content -->
<div id="page-content">
	<div class="row">
	        <div class="col-md-7 col-md-offset-2-5">
	            <!-- Basic Form Elements Block -->
	            <div class="block">
	                <!-- Basic Form Elements Title -->
	                <div class="block-title">
	                    <h2><strong>Set Super</strong> Settings</h2>
	                </div>
	                <!-- END Form Elements Title -->
	                <!-- Basic Form Elements Content -->
	                <form id = "site_settings" class="form-horizontal form-bordered" enctype="multipart/form-data" method="post" action="<?php echo BASE_URL.'admin/settings/site_settings'; ?>">
	                	<?php foreach ($setting as $key => $value) { ?>  		
	                		<div class="form-group">
		                        <label for="<?= $value['setting_name'] ?>" class="col-md-4 control-label"><?= $value['setting_name_lable'] ?><span class="text-danger">*</span></label>
								<div class="col-md-7">
		                            <input type="text" placeholder="<?= $value['setting_name_lable'] ?>" class="form-control" name="<?= $value['setting_name'] ?>" id="<?= $value['setting_name'] ?>" value="<?= $value['setting_value'] ?>">
		                            <?php if(form_error($value['setting_name'])){ ?>
			                        <div id="<?= $value['setting_name']?>-2-error" class="help-block animation-slideDown falseForm">
		                            	<?php echo form_error($value['setting_name']); ?>
		                            </div>
	                            <?php } ?>
		                        </div>
		                    </div>	  
		                <?php } ?>
						
	                    <div class="form-group form-actions">
	                        <div class="col-md-9 col-md-offset-4">
	                            <button class="btn btn-sm btn-primary" type="submit"><i class="fa fa-angle-right"></i> Set Setting</button>	                            
	                        </div>
	                    </div>
	                </form>
	                <!-- END Basic Form Elements Content -->
	           </div>
	       </div>
	       
	</div>
<!-- </div> -->
<!-- END Page Content -->