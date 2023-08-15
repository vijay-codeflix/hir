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
	                    <h2><strong>Send </strong> Notifications</h2>
	                </div>
	                <!-- END Form Elements Title -->

	                <!-- Basic Form Elements Content -->
	                <form id = "userAddForm" class="form-horizontal form-bordered" enctype="multipart/form-data" method="post" action="<?php echo BASE_URL.'admin/notifications/add'; ?>">
						<div class="form-group">
							<label for="user_type" class="col-md-4 control-label">Select User<span class="text-danger">*</span></label>
	                        <div class="col-md-7">
				                <select multiple name="user[]">
			                    	<option value="all">Send to All</option>
			                    	<?php
			                    	foreach ($users as $value) {										 
										echo '<option value="'.$value->id.'">'.$value->first_name.' '.$value->last_name.'</option>';
			                    	} ?>
			                    </select>
				            </div>
			            </div>
			            <div class="form-group">
							<label for="user_type" class="col-md-4 control-label">Title<span class="text-danger">*</span></label>
	                        <div class="col-md-7">
				                <div class='input-group'>
				                    <input type='text' class="form-control" placeholder="Title" class="form-control" name="title" id="title"/>
				                </div>
				            </div>
			            </div>
			            <div class="form-group">
							<label for="notification_file" class="col-md-4 control-label">File<span class="text-danger"></span></label>
	                        <div class="col-md-7">
				                <div class='input-group'>
				                    <input type='file' class="form-control" placeholder="File" class="form-control" name="notification_file" id="notification_file"/>
				                </div>
				            </div>
			            </div>

	                    <div class="form-group">
	                        <label for="user_type" class="col-md-4 control-label">Notification<span class="text-danger">*</span></label>
	                        <div class="col-md-7">
	                            <textarea class="form-control" name="message"></textarea>
	                        </div>
	                    </div>
	                    <div class="form-group form-actions">
	                        <div class="col-md-9 col-md-offset-4">
	                            <button class="btn btn-sm btn-primary" type="submit"><i class="fa fa-angle-right"></i> Send Notification </button>
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
