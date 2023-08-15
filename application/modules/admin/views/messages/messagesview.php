<!-- Page content -->
<div id="page-content">
	<div class="row">
	        <div class="col-md-7 col-md-offset-2-5">
	            <!-- Basic Form Elements Block -->
	            <div class="block">
	                <!-- Basic Form Elements Title -->
	                <div class="block-title">
	                    <div class="block-options pull-right">
	                        <a title="" data-toggle="tooltip" class="btn btn-alt btn-sm btn-default" href="<?php echo BASE_URL.'admin/users'; ?>" data-original-title="Close"><i class="fa fa-times"></i></a>
	                    </div>
	                    <h2><strong>Add</strong> Message</h2>
	                </div>
	                <!-- END Form Elements Title -->

	                <!-- Basic Form Elements Content -->
	                <form id = "userAddForm" class="form-horizontal form-bordered" enctype="multipart/form-data" method="post" action="<?php echo BASE_URL.'admin/messages/update'; ?>">
						<div class="form-group">
							<label for="user_type" class="col-md-4 control-label">Punch In Date<span class="text-danger">*</span></label>
	                        <div class="col-md-7">
				                <div class='input-group date' id='datetimepicker1'>
				                    <input type='text' class="form-control" placeholder="Punch In Date" class="form-control" name="punch_in_date" id="punch_in_date" value="<?php echo $messages->punch_in_date; ?>" />
				                    <!-- data-date-format="dd/mm/yyyy" -->
				                    <span class="input-group-addon">
				                        <span class="glyphicon glyphicon-calendar"></span>
				                    </span>
				                </div>
				                <?php if(form_error('punch_in_date')){ ?>
			                        <div id="punch_in_date-2-error" class="help-block animation-slideDown falseForm">
		                            	<?php echo form_error('punch_in_date'); ?>
		                            </div>
	                            <?php } ?>
				            </div>
			            </div>

	                    <div class="form-group">
	                        <label for="user_type" class="col-md-4 control-label">Punch In Message<span class="text-danger">*</span></label>
	                        <div class="col-md-7">
	                            <textarea class="form-control" name="punch_in_message" id="punch_in_message"><?php echo $messages->punch_in_message; ?></textarea> 
	                            <?php if(form_error('punch_in_message')){ ?>
			                        <div id="punch_in_message-2-error" class="help-block animation-slideDown falseForm">
		                            	<?php echo form_error('punch_in_message'); ?>
		                            </div>
	                            <?php } ?>
	                        </div>
	                    </div>

	                    <div class="form-group">
							<label for="user_type" class="col-md-4 control-label">Punch Out Date<span class="text-danger">*</span></label>
	                        <div class="col-md-7">
				                <div class='input-group date' id='datetimepicker2'>
				                    <input type='text' class="form-control" placeholder="Punch Out Date" class="form-control" name="punch_out_date" id="punch_out_date" value="<?php echo $messages->punch_out_date; ?>" />
				                    <!-- data-date-format="dd/mm/yyyy" -->
				                    <span class="input-group-addon">
				                        <span class="glyphicon glyphicon-calendar"></span>
				                    </span>
				                </div>
				                <?php if(form_error('punch_out_date')){ ?>
			                        <div id="punch_out_date-2-error" class="help-block animation-slideDown falseForm">
		                            	<?php echo form_error('punch_out_date'); ?>
		                            </div>
	                            <?php } ?>
				            </div>
			            </div>

	                    <div class="form-group">
	                        <label for="user_type" class="col-md-4 control-label">Punch Out Message<span class="text-danger">*</span></label>
	                        <div class="col-md-7">
	                            <textarea  class="form-control" name="punch_out_message" id="punch_out_message"><?php echo $messages->punch_in_message; ?></textarea> 
	                             <?php if(form_error('punch_out_message')){ ?>
			                        <div id="punch_out_message-2-error" class="help-block animation-slideDown falseForm">
		                            	<?php echo form_error('punch_out_message'); ?>
		                            </div>
	                            <?php } ?>
	                        </div>
	                    </div>  
						
	                    <div class="form-group form-actions">
	                        <div class="col-md-9 col-md-offset-4">
	                            <button class="btn btn-sm btn-primary" type="submit"><i class="fa fa-angle-right"></i> Scheduler Message </button>
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
