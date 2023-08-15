<!-- Page content -->
<div id="page-content">
    <?php
    if($this->session->flashdata('invalid_per')!= '')
    {
    ?>
      <div class="alert text-center">    
           <?php echo $this->session->flashdata('invalid_per'); ?>
      </div>
    <?php

    }else{ 

    ?> 
    <?php

      //flash messages
      $msg = '';
      //echo $this->session->flashdata('flash_message');

      $style = 'display:none;';
      $error_msg = '';
      $style_error = 'display:none;';
      if($this->session->flashdata('flash_message'))
      {
        if($this->session->flashdata('flash_message') == 'inserted')
        {
          $msg="<strong>Well done!</strong> user's detail inserted with success.";
           $style = 'display: block;';           
        }
        else if($this->session->flashdata('flash_message') == 'updated')
        {
          $msg = "<strong>Well done!</strong> user's detail updated with success.";
          $style = 'display: block;';
        }else if($this->session->flashdata('flash_message') == 'Deleted')
        {
          $msg = "<strong>Well done!</strong> user's detail deleted with success.";
          $style = 'display: block;';
        }else if($this->session->flashdata('flash_message') == 'add_limit')
        {
          $error_msg = "<strong>Oh snap!</strong> Add User Limit is completed. Please contact to Admin.";
          $style_error = 'display: block;';
        }else if($this->session->flashdata('flash_message') == 'not_deleted_child')
        {
          $error_msg = "<strong>Oh snap!</strong> Are you not able delete this user. Please shift all down line subadmin and employee to other user.";
          $style_error = 'display: block;';
        }
        else if($this->session->flashdata('flash_message') == 'add_permission')
        {
          $error_msg = "<strong>Oh snap!</strong> You don't have permission to access.";
          $style_error = 'display: block;';
        }

        else
        {
          $msg = '<strong>Oh snap!</strong> Your Action is Not Perform.';
          $style = 'display: block;';
        }
      }
      $permisnMsg = '';
      $css = 'display: none;';
      if($this->session->flashdata('permission')!= '')
      {
          $permisnMsg = $this->session->flashdata('permission');
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
    
    <div class="alert alert-danger" style="<?php echo $style_error; ?>">
      <a class="close" data-dismiss="alert">&times;</a>
      <?php echo $error_msg; ?>
    </div>	
    <div class="alert alert-danger alert-dismissable" id="notdelete" style="display: none;">
      <button aria-hidden="true" data-dismiss="alert" class="close" type="button">&times;</button>
      <h4><i class="fa fa-times-circle"></i> ACCESS DENIED</h4> 
      You can not delete <a href="javascript: void(0);" class="alert-link"><span></span></a> there are already some data related to this!
    </div>
    <!-- Datatables Content -->
    <div class="block full">
        <div class="block-title">
            <h2><strong>Manage User</strong></h2>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="block pull-right no-border">
                 <?php echo form_open('admin/users/add'); ?>
                  <div class="block-section text-right">
                      <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Add User</button>
                  </div>
                  <?php echo form_close(); ?>
            </div>
          </div>
          <div class="col-md-12">
            <form id="userSearchForm" class="form-horizontal form-bordered" method="post" action="<?php echo BASE_URL.'admin/users/'; ?>">              
              <div class="block col-md-4 pull-left no-border" style="padding-bottom: 10px;">
                <label for="parent_id" class="control-label">Type Of User</label>
                <!--<input list="search-users" class="form-control" name="search-user" id="search-user" placeholder="Select User here...">-->
                    <!--<datalist id="search-users">-->
                <select id="search-user" class="form-control select-chosen" name="user_type">
                  <option value="0" <?php echo (set_value('user_type') == 0) ? 'selected="selected"' : '';?>>Select User</option>
                  <option value="4" <?php echo (set_value('user_type') == 4) ? 'selected="selected"' : '';?>>Employee</option>
                  <option value="3" <?php echo (set_value('user_type') == 3) ? 'selected="selected"' : '';?>>Sub Admin</option>
                </select>
                    <!--</datalist>-->
              </div>
              <div class="block col-md-4 pull-center no-border" style="padding-bottom: 10px;">
                <label for="parent_id" class="control-label">Parent User</label>
                <!--<input list="parent_ids" class="form-control" name="parent_id" id="parent_id" placeholder="Select parent user here...">-->
                    <!--<datalist id="parent_ids">-->
                <select size="1" class="form-control select-chosen" name="parent_id" id="parent_id">
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
              <div class="block col-md-2 pull-right no-border">.
                <div class="form-group">
                  <div class="col-md-12">
                    <button class="btn btn-sm btn-primary" type="submit"><i class="fa fa-angle-right"></i> Search</button>
                    <button class="btn btn-sm btn-primary" type="reset" id="reset-btn"><i class="fa fa-angle-right"></i> Reset</button>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
        </div>
        <div class="table-responsive">
            <!-- <input id="search-user" type="text" placeholder="Search.."> -->
            <table id="example-datatable" class="table table-vcenter table-condensed table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">Name</th>
                        <th class="text-center">Type of user</th>
                        <th class="text-center">Parent</th>
                        <th class="text-center">EID</th>
                        <th class="text-center">Email</th>
                        <th class="text-center">Phone</th>
                        <th class="text-center">Details</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                
                <tbody id="userlist-table">
                    <?php
                    if($users){
                         for($i = 0; $i < count($users); $i++){
                          $userId = str_replace('/', '_',rtrim(base64_encode($users[$i]->id), '=')); 
                          $nextUrl = (ucfirst($users[$i]->userType) == "Employee")? "javascript: void(0)" : CURRENT_MODULE.'users/index/'.$userId;
                    ?>
                        <tr>
                            <td class="text-center"><?php echo str_pad($i + 1, 2,"0", STR_PAD_LEFT);; ?></td>
                            <td class="text-center"><?php echo $users[$i]->first_name." ".$users[$i]->last_name; ?></td>
                            <td class="text-center"><?php echo ucfirst($users[$i]->userType); ?></td>
                            <td class="text-center"><?php echo $users[$i]->parent_name; ?></td>
                            <td class="text-center"><?php echo $users[$i]->emp_id; ?></td>
                            <td class="text-center"><?php echo $users[$i]->email; ?></td>
                            <td class="text-center"><?php echo $users[$i]->phone; ?></td>
                            <td class="text-center"><!-- <a href="">Routes</a> | <a href="">Visits</a> | <a href="">Payments</a> | <a href="">Parties</a> | <a href="">View</a> |  --><a href="javascript:reset_device('<?php echo BASE_URL; ?>','<?php echo $userId; ?>')">Reset Device</a></td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <!--<a href="javascript: show_confirm('<?php echo BASE_URL; ?>', 'view','<?php echo $userId; ?>')" onclick="$('#view-users').modal('show');" data-toggle="tooltip" title="View" class="btn btn-xs"><i class="fa fa-eye"></i></a>-->

                                    <a href="<?php echo CURRENT_MODULE.'users/viewEmployeeDetails/'.$userId; ?>" data-toggle="tooltip" title="View details" class="btn btn-xs"><i class="fa fa-eye"></i></a>
                                    
                                    <a href="<?php echo $nextUrl; ?>" data-toggle="tooltip" title="Go Inside" class="btn btn-xs"><i class="fa fa-chevron-right"></i></a>
                                    <?php if($this->session->userdata['logged_in']['usertype'] !== 'Sub Admin'){ ?>
                                    <a href="<?php echo CURRENT_MODULE.'users/edit/'.$userId;?>" data-toggle="tooltip" title="Edit" class="btn btn-xs btn-default"><i class="fa fa-pencil"></i></a>

                                    <a href="javascript: show_confirm('<?php echo BASE_URL; ?>', 'Delete','<?php echo $userId; ?>')" data-toggle="tooltip" title="Delete" class="btn btn-xs btn-danger"><i class="fa fa-times"></i></a>
                                    <?php } ?>          
                                </div>
                            </td>
                        </tr>
                    <?php } ?>

                <?php } ?>

                </tbody>
            </table>
        </div>
    </div>
    <!-- END Datatables Content -->	
    <?php } ?>
<!-- END Page Content -->
<?php 
  $this->session->set_flashdata('permission','');
  $this->session->set_flashdata('invalid_per','');
?>