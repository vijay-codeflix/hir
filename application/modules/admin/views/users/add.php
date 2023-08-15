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
	                    <h2><strong>Add</strong> User</h2>
	                </div>
	                <!-- END Form Elements Title -->

	                <!-- Basic Form Elements Content -->
	                <form id = "userAddForm" class="form-horizontal form-bordered" enctype="multipart/form-data" method="post" action="<?php echo BASE_URL.'admin/users/insert'; ?>">
						<div class="form-group">
	                        <label for="user_type" class="col-md-4 control-label">User Type<span class="text-danger">*</span></label>
	                        <div class="col-md-7">
	                            <!--<input list="user_types" size="1" class="form-control" placeholder="Select user here..." name="user_type" id="user_type">-->
                                    <!--<datalist id="user_types">-->
	                        	<select size="1" class="form-control select-chosen" name="user_type" id="user_type">
	                                <option value="">Please select</option>
	                                <?php 
	                                	foreach($usertype as $utrow){
	                                		$utid = $utrow->id;

	                                		// if($this->session->userdata['logged_in']['usertype'] == "Super Admin"){
	                                		// 	$check_array = array(1);
	                                		// }else{
	                                		// 	$check_array = array(1,2);
	                                		// }
	                                		if(!in_array($utrow->id, array(1,2))){
	                                		//if(!in_array($utrow->id, $check_array)){
												if(set_value('user_type') == $utrow->id){
													echo '<option selected="selected" value="'.$utid.'">'.ucfirst($utrow->type).'</option>';	
												}else{
													echo '<option value="'.$utid.'">'.ucwords($utrow->type).'</option>';		
												}
											}
	                                	}
	                                ?>
	                            </select>
	                                <!--</datalist>-->
	                        </div>
	                    </div>	  
						<div class="form-group">
	                        <label for="parent_id" class="col-md-4 control-label">Parent User<span class="text-danger  <?php if(set_value('user_type') == 3){ echo 'hide'; } ?>" id="dangerP">*</span></label>
	                        <div class="col-md-7">
	                            <!--<input list="parent_ids" size="1" class="form-control" placeholder="Select parent user here..." name="parent_id" id="parent_id">-->
                                    <!--<datalist id="parent_ids">-->
	                            <select size="1" class="form-control" name="parent_id" id="parent_id">
	                                <option value="">Please select</option>
									<?php 
										if($getSubAdmins){
											foreach($getSubAdmins as $subAdmin){
												if(set_value('parent_id') == $subAdmin->id){
													echo '<option selected="selected" value="'.$subAdmin->id.'">'.ucwords($subAdmin->first_name." ".$subAdmin->last_name).'</option>';		
												}else{
													echo '<option value="'.$subAdmin->id.'">'.ucwords($subAdmin->first_name." ".$subAdmin->last_name).'</option>';
												}
											}
										}
	                                ?>
	                            </select>
	                                <!--</datalist>-->
	                        </div>
	                    </div>	                    
	                    <div class="form-group">
	                        <label for="first_name" class="col-md-4 control-label">First Name<span class="text-danger">*</span></label>
	                        <div class="col-md-7">
	                            <input type="text" placeholder="First name" class="form-control" name="first_name" id="first_name" value="<?php echo set_value('first_name'); ?>">
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <label for="last_name" class="col-md-4 control-label">Last Name<span class="text-danger">*</span></label>
	                        <div class="col-md-7">
	                            <input type="text" placeholder="Last name" class="form-control" name="last_name" id="last_name" value="<?php echo set_value('last_name'); ?>">
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <label for="emp_id" class="col-md-4 control-label">Employee ID<span class="text-danger">*</span></label>
	                        <div class="col-md-7">
	                            <input type="text" placeholder="Employee ID" class="form-control" name="emp_id" id="emp_id" value="<?php echo set_value('emp_id'); ?>">
	                            <?php if(form_error('emp_id')){ ?>
			                        <div id="emp_id-2-error" class="help-block animation-slideDown falseForm">
		                            	<?php echo form_error('emp_id'); ?>
		                            </div>
	                            <?php } ?>
	                        </div>
	                    </div>	                    
	                    <div class="form-group">
	                        <label for="email" class="col-md-4 control-label">Email<span class="text-danger">*</span></label>
	                        <div class="col-md-7">
	                            <input type="text" placeholder="Email address" class="form-control" name="email" id="email" value="<?php echo set_value('email'); ?>">
	                            <?php if(form_error('email')){ ?>
			                        <div id="email-2-error" class="help-block animation-slideDown falseForm">
		                            	<?php echo form_error('email'); ?>
		                            </div>
	                            <?php } ?>
	                        </div>
	                    </div>
	                    <div class="form-group <?php if(set_value('user_type') == 4){ echo 'hide'; } ?>">
	                        <label for="password" class="col-md-4 control-label">Password<span class="text-danger">*</span></label>
	                        <div class="col-md-7">
	                            <input type="password" placeholder="Password" class="form-control" name="password" id="password" value="<?php echo set_value('password'); ?>">
	                        </div>
	                    </div>
						
	                    <div class="form-group">
	                        <label for="phone" class="col-md-4 control-label">Phone<span class="text-danger">*</span></label>
	                        <div class="col-md-7">
	                            <input type="text" maxlength="10" placeholder="XXX-XXXX-XXX" class="form-control" name="phone" id="phone" value="<?php echo set_value('phone'); ?>">               	                            
	                            <?php if(form_error('phone')){ ?>
	                            <div id="phone-2-error" class="help-block animation-slideDown falseForm">
	                            	<?php echo 'This number is already registered'; //form_error('phone'); ?>
	                            </div>
	                            <?php } ?>
	                        </div>
	                    </div>	 
						<div class="form-group">
	                        <label for="profile_image" class="col-md-4 control-label">Profile</label>
	                        <div class="col-md-7">
	                            <input type="file" class="form-control" name="profile_image" id="profile_image" value="<?php echo set_value('profile_image'); ?>">
	                        </div>
	                    </div>
	                    <div class="form-group">
                            <label for="address" class="col-md-4 control-label">Address</label>
                            <div class="col-md-7">
                                <textarea placeholder="Type address" class="form-control" rows="4" name="address" id="address"><?php echo set_value('address'); ?></textarea>
                            </div>
                        </div>
						<div class="form-group  <?php if(set_value('user_type') == 3){ echo 'hide'; } ?>">
	                        <label for="grade_id" class="col-md-4 control-label">Employee Grade<span class="text-danger">*</span></label>
	                        <div class="col-md-7">
	                            <!--<input list="grade_ids" size="1" class="form-control select-chosen" placeholder="Select user grade here..." name="grade_id" id="grade_id">-->
                                    <!--<datalist id="grade_ids">-->
	                            <select size="1" class="form-control" name="grade_id" id="grade_id">
	                                <option value="">Please select</option>
	                                <?php 
	                                	foreach($gradeList as $grade){
	                                		$utid = $grade->id;
											if(set_value('grade_id') == $grade->id){
												echo '<option selected="selected" value="'.$utid.'">'.ucfirst($grade->grade).'</option>';	
											}else{
												echo '<option value="'.$utid.'">'.ucwords($grade->grade).'</option>';	
											}
	                                	}
	                                ?>
	                            </select>
	                                <!--</datalist>-->
	                        </div>
	                    </div>
	                    <div class="form-group hide">
	                        <label for="ping_interval" class="col-md-4 control-label">Map Ping Interval (In Second)<span class="text-danger">*</span></label>
	                        <div class="col-md-7">
	                            <input type="text" maxlength="10" placeholder="Map Ping Interval Time" class="form-control" name="ping_interval" id="ping_interval" value="<?php echo set_value('ping_interval'); ?>">               	                            
	                            <?php if(form_error('ping_interval')){ ?>
	                            <div id="ping_interval-2-error" class="help-block animation-slideDown falseForm">
	                            	<?php echo 'Map Ping Interval is required'; //form_error('ping_interval'); ?>
	                            </div>
	                            <?php } ?>
	                        </div>
	                    </div>	 
	                    <div class="form-group">
	                        <label class="col-md-4 control-label">Status</label>
	                        <div class="col-md-7">
                                <label for="user_enable" class="radio-inline">
                                    <input type="radio" value="1" name="status" id="user_enable" checked="checked">Active
                                </label>
                                <label for="user_disable" class="radio-inline">
                                    <input type="radio" value="0" name="status" id="user_disable">Inactive
                                </label>
	                        </div>
	                    </div>
	                    <div class="form-group form-actions">
	                        <div class="col-md-9 col-md-offset-4">
	                            <button class="btn btn-sm btn-primary" type="submit"><i class="fa fa-angle-right"></i> Add User</button>
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