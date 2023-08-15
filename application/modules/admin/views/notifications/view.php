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
        $msg = '<strong>Oh snap!</strong> Your Action is Not Perform.';
        $style = 'display: block;';
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
            <h2><strong>Manage Notifications</strong></h2>
        </div>
        <div class="row">
          <div class="col-md-12">
            <?php if($this->session->userdata['logged_in']['usertype'] !== 'Sub Admin'){ ?>
            <div class="block pull-right no-border">
                 <?php echo form_open('admin/notifications/send'); ?>
                  <div class="block-section text-right">
                      <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Send Notifications</button>
                  </div>
                  <?php echo form_close(); ?>
            </div>
            <?php } ?>     
          </div>
        </div>
        <div class="table-responsive">
            <!-- <input id="search-user" type="text" placeholder="Search.."> -->
            <table id="example-datatable" class="table table-vcenter table-condensed table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">Title</th>
                        <th class="text-center">Message</th>
                        <th class="text-center">Type</th>
                        <th class="text-center">File Link</th>
                        <th class="text-center">User</th>
                        <th class="text-center">Status</th>
                        <!-- <th class="text-center">Action</th> -->
                    </tr>
                </thead>
                
                <tbody id="userlist-table">
                    <?php
                    if($notifications){
                         for($i = 0; $i < count($notifications); $i++){ ?>
                        <tr>
                            <td class="text-center"><?php echo str_pad($i + 1, 2,"0", STR_PAD_LEFT); ?></td>
                            <td class="text-center"><?php echo $notifications[$i]->title; ?></td>
                            <td class="text-center"><?php echo ucfirst($notifications[$i]->message); ?></td>
                            <td class="text-center"><?php echo ($notifications[$i]->type == 1) ? 'Normal' : 'Admin Send'; ?></td>
                            <td class="text-center"><?php echo (!empty($notifications[$i]->file_link) ? "<a href='".$notifications[$i]->file_link."' target='_blank'> ".$notifications[$i]->file_name." </a>": ''); ?></td>
                            <td class="text-center"><?php echo $notifications[$i]->first_name." ".$notifications[$i]->last_name; ?></td>
                            <td class="text-center"><?php echo ($notifications[$i]->read_status == 0) ? 'Send' : 'Read'; ?></td>
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