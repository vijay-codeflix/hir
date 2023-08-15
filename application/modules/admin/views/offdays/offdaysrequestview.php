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

      $style = 'display:none;';
      if($this->session->flashdata('flash_message'))
      {
        if($this->session->flashdata('flash_message') == 'inserted')
        {
          $msg="<strong>Well done!</strong> offday detail inserted with success.";
           $style = 'display: block;';           
        }
        else if($this->session->flashdata('flash_message') == 'updated')
        {
          $msg = "<strong>Well done!</strong> offday detail updated with success.";
          $style = 'display: block;';
        }else if($this->session->flashdata('flash_message') == 'Deleted')
        {
          $msg = "<strong>Well done!</strong> offday detail deleted with success.";
          $style = 'display: block;';
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
    <div class="alert alert-danger alert-dismissable" id="notdelete" style="display: none;">
      <button aria-hidden="true" data-dismiss="alert" class="close" type="button">&times;</button>
      <h4><i class="fa fa-times-circle"></i> ACCESS DENIED</h4> 
      You can not delete <a href="javascript: void(0);" class="alert-link"><span></span></a> there are already some data related to this!
    </div>
    <!-- Datatables Content -->
    <div class="block full">
        <div class="block-title">
            <h2><strong>Offdays Request List</strong></h2>
        </div>
        <div class="table-responsive">
            <table id="example-datatable" class="table table-vcenter table-condensed table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">Offdays Date</th>
                        <th class="text-center">Employee Name</th>
                        <th class="text-center">Phone</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if($offdaysrequest){
                      foreach ($offdaysrequest as $key => $value) { ?>
                        <tr>
                          <td class="text-center"><?php echo $key+1; ?></td>
                          <td class="text-center"><?php echo $value->date; ?></td>
                          <td class="text-center"><?php echo $value->first_name.' '.$value->last_name; ?></td>
                          <td class="text-center"><?php echo $value->phone; ?></td>
                          <td class="text-center"><?php 
                          switch ($value->status) {
                            case '0':
                              echo 'Pending';
                              break;
                            case '1':
                              echo 'Approved';
                              break;
                            case '2':
                              echo 'Cancel';
                              break;
                            default:
                              echo '--';
                              break;
                          }
                          ?></td>
                          <td class="text-center">
                              <div class="btn-group">
                                <?php if($value->status == 0){ ?>
                                  <a href="<?php echo CURRENT_MODULE.'offdays/offdaysrequest_action/'.$value->id.'/1';?>" data-toggle="tooltip" title="Approve" class="btn btn-xs btn-default"><i class="fa fa-check" aria-hidden="true"></i></a> &nbsp;&nbsp;&nbsp;&emsp;
                                  <a href="<?php echo CURRENT_MODULE.'offdays/offdaysrequest_action/'.$value->id.'/2';?>" data-toggle="tooltip" title="Reject" class="btn btn-xs btn-default"><i class="fa fa-times" aria-hidden="true"></i></a>
                                <?php } ?>
                                  <!-- <a href="<?php echo CURRENT_MODULE.'offdays/offdayedit/'.$value->id;?>" data-toggle="tooltip" title="Edit" class="btn btn-xs btn-default"><i class="fa fa-pencil"></i></a> -->
                                  <!-- Uncomment below code for delete offday -->
                                 
                                  <!-- <a href="javascript: show_confirm('<?php //echo BASE_URL; ?>', 'Delete','<?php //echo $id; ?>')" data-toggle="tooltip" title="Delete" class="btn btn-xs btn-danger"><i class="fa fa-times"></i></a> -->
                              </div>
                          </td>
                        </tr>
                      <?php }
                      ?>

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