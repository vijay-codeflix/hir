<!-- Page content -->
<div id="page-content">
  <?php
    if($this->session->flashdata('invalid_per')!= '')
    { ?>
      <div class="alert text-center">    
           <?php echo $this->session->flashdata('invalid_per'); ?>
      </div>
      <?php
    }else{ 
      //flash messages
      $msg = '';

      $style = 'display:none;';
      if($this->session->flashdata('flash_message'))
      {
        if($this->session->flashdata('flash_message') == 'updated')
        {
          $msg = "<strong>Well done!</strong> password updated with success.";
          $style = 'display: block;';
        }
        // else
        // {
        //   $msg = '<strong>Oh snap!</strong> Your Action is Not Perform.';
        //   $style = 'display: block;';
        // }
      }
      $permisnMsg = '';
      $css = 'display: none;';
      if($this->session->flashdata('flash_message') == 'new_match')
      {
          $permisnMsg = "<strong>Well done!</strong> Confirm password does not match new password.";
          $css = 'display: block;';
      }else if($this->session->flashdata('flash_message') == 'not_match')
      {
        $permisnMsg = "<strong>Well done!</strong> Old password does not match.";
        $css = 'display: block;';
      }
    ?>
    <div class="alert alert-success" style="<?php echo $style; ?>">
      <a class="close" data-dismiss="alert">&times;</a>
      <?php echo $msg; ?>
    </div>
    <div class="alert alert-danger" style="<?php echo $css; ?>">
      <a class="close" data-dismiss="alert">&times;</a>
      <?php echo $permisnMsg; ?>
    </div>	
    <div class="alert alert-danger alert-dismissable" id="notdelete" style="display: none;">
      <button aria-hidden="true" data-dismiss="alert" class="close" type="button">&times;</button>
      <h4><i class="fa fa-times-circle"></i> ACCESS DENIED</h4> 
      You can not delete <a href="javascript: void(0);" class="alert-link"><span></span></a> there are already some data related to this!
    </div>
    <!-- User details -->
    <div class="row">
      <div class="col-md-12">
        <!-- Basic Form Elements Block -->
        <div class="block">
          <!-- Basic Form Elements Title -->
          <div class="block-title">
            <h2><strong>Profile</strong></h2>
          </div>
          <table class="table table-vcenter table-condensed table-bordered">
            <tbody>
              <tr><td>First Name</td><td><?= $user_details->first_name; ?></td></tr>
              <tr><td>Last Name</td><td><?= $user_details->last_name; ?></td></tr>
              <tr><td>Email</td><td><?= $user_details->email; ?></td></tr>
              <tr><td>Phone</td><td><?= $user_details->phone; ?></td></tr>
              <tr><td>Employee ID</td><td><?= $user_details->emp_id; ?></td></tr>
              <tr><td>Type</td><td><?= (($user_details->user_type ==1) ? 'Super Admin' : ($user_details->user_type == 2) ? 'Admin' :($user_details->user_type == 3 ? "Sub Admin" : 'Employee')  ); ?></td></tr>
              <tr><td>Sub Admin</td><td><?= $user_details->parent_name; ?></td></tr>
            </tbody>
          </table>                                       
        </div>
        <!-- END Basic Form Elements Block -->
      </div>
      <div class="col-md-12">
        <!-- Basic Form Elements Block -->
        <div class="block">
          <!-- Basic Form Elements Title -->
          <div class="block-title">
            <h2><strong>Changes Password</strong></h2>
          </div>
          <form id = "userAddForm" class="form-horizontal form-bordered" enctype="multipart/form-data" method="post" action="<?php echo BASE_URL.'admin/users/change_password'; ?>">
            <div class="form-group">
              <label for="old_password" class="col-md-4 control-label">Old Password<span class="text-danger">*</span></label>
              <div class="col-md-7">
                <input type="password" placeholder="Old Password" class="form-control" name="old_password" id="old_password">
                <?php if(form_error('old_password')){ ?>
                <div id="old_password-2-error" class="help-block animation-slideDown falseForm">
                  <?php echo form_error('old_password'); ?>
                </div>
                <?php } ?>
              </div>
            </div>  
            <div class="form-group">
              <label for="password" class="col-md-4 control-label">New Password<span class="text-danger">*</span></label>
              <div class="col-md-7">
                  <input type="password" placeholder="New Password" class="form-control" name="password" id="password">
                  <?php if(form_error('password')){ ?>
                  <div id="password-2-error" class="help-block animation-slideDown falseForm">
                    <?php echo form_error('password'); ?>
                  </div>
                  <?php } ?>
              </div>              
            </div>
            <div class="form-group">
              <label for="password_confirm " class="col-md-4 control-label">Confirm Password<span class="text-danger">*</span></label>
              <div class="col-md-7">
                  <input type="password" placeholder="Confirm Password" class="form-control" name="password_confirm" id="password_confirm ">
                  <?php if(form_error('password_confirm')){ ?>
                  <div id="password_confirm-2-error" class="help-block animation-slideDown falseForm">
                    <?php echo form_error('password_confirm'); ?>
                  </div>
                  <?php } ?>
              </div>
            </div>
            <div class="form-group form-actions">
              <div class="col-md-9 col-md-offset-4">
                  <button class="btn btn-sm btn-primary" type="submit"><i class="fa fa-angle-right"></i>Change Password</button>
              </div>
            </div>                                     
          </form>
        </div>
        <!-- END Basic Form Elements Block -->
      </div>
    </div>
    <?php } ?>
<!-- END Page Content -->
<?php 
  $this->session->set_flashdata('permission','');
  $this->session->set_flashdata('invalid_per','');
?>
