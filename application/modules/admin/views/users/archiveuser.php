<!-- Page content -->
<div id="page-content">
  <?php
  if($this->session->flashdata('invalid_per')!= ''){?>
    <div class="alert text-center">    
      <?php echo $this->session->flashdata('invalid_per'); ?>
    </div>
  <?php }else{ 
    //flash messages
    $msg = '';
    //echo $this->session->flashdata('flash_message');
    $style = 'display:none;';
    $error_msg = '';
    $style_error = 'display:none;';
    if($this->session->flashdata('flash_message')){
      if($this->session->flashdata('flash_message') == 'inserted'){
        $msg="<strong>Well done!</strong> user's detail inserted with success.";
        $style = 'display: block;';           
      }else if($this->session->flashdata('flash_message') == 'updated'){
        $msg = "<strong>Well done!</strong> user's detail updated with success.";
        $style = 'display: block;';
      }else if($this->session->flashdata('flash_message') == 'Deleted'){
        $msg = "<strong>Well done!</strong> user's detail deleted with success.";
        $style = 'display: block;';
      }else if($this->session->flashdata('flash_message') == 'add_limit'){
        $error_msg = "<strong>Oh snap!</strong> Add User Limit is completed. Please contact to Admin.";
        $style_error = 'display: block;';
      }else if($this->session->flashdata('flash_message') == 'not_deleted_child'){
        $error_msg = "<strong>Oh snap!</strong> Are you not able delete this user. Please shift all down line subadmin and employee to other user.";
        $style_error = 'display: block;';
      }else{
        $msg = '<strong>Oh snap!</strong> Your Action is Not Perform.';
        $style = 'display: block;';
      }
    }
    $permisnMsg = '';
    $css = 'display: none;';
    if($this->session->flashdata('permission')!= ''){
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
          <h2><strong>Archive User</strong></h2>
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
                    <!-- <th class="text-center">Details</th>
                    <th class="text-center">Action</th> -->
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
                        <!-- <td class="text-center"><a href="javascript:reset_device('<?php echo BASE_URL; ?>','<?php echo $userId; ?>')">Reset Device</a></td> -->
                        <!-- <td class="text-center">
                            <div class="btn-group">
                                <a href="javascript: show_confirm('<?php echo BASE_URL; ?>', 'view','<?php echo $userId; ?>')" onclick="$('#view-users').modal('show');" data-toggle="tooltip" title="View" class="btn btn-xs"><i class="fa fa-eye"></i></a>

                                <a href="<?php echo CURRENT_MODULE.'users/viewEmployeeDetails/'.$userId; ?>" data-toggle="tooltip" title="View details" class="btn btn-xs"><i class="fa fa-eye"></i></a>
                                
                                <a href="<?php echo $nextUrl; ?>" data-toggle="tooltip" title="Go Inside" class="btn btn-xs"><i class="fa fa-chevron-right"></i></a>

                                <a href="<?php echo CURRENT_MODULE.'users/edit/'.$userId;?>" data-toggle="tooltip" title="Edit" class="btn btn-xs btn-default"><i class="fa fa-pencil"></i></a>

                                <a href="javascript: show_confirm('<?php echo BASE_URL; ?>', 'Delete','<?php echo $userId; ?>')" data-toggle="tooltip" title="Delete" class="btn btn-xs btn-danger"><i class="fa fa-times"></i></a>          
                            </div>
                        </td> -->
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