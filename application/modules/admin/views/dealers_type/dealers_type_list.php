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
      //flash messages
      $msg = '';

      $style = 'display:none;';
      if($this->session->flashdata('flash_message'))
      {
        if($this->session->flashdata('flash_message') == 'inserted')
        {
          $msg="<strong>Well done!</strong> dealer type detail inserted with success.";
           $style = 'display: block;';           
        }
        else if($this->session->flashdata('flash_message') == 'updated')
        {
          $msg = "<strong>Well done!</strong> dealer type detail updated with success.";
          $style = 'display: block;';
        }else if($this->session->flashdata('flash_message') == 'Deleted')
        {
          $msg = "<strong>Well done!</strong> dealer type detail deleted with success.";
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
            <h2><strong>Dealer Types List</strong></h2>
        </div>

        <!-- Uncomment below code for add dealertypes -->
        <div class="row">
          <div class="col-md-12">
            <div class="block pull-right no-border">
              <?php echo form_open('admin/dealertypes/type_add'); ?>
                  <div class="block-section text-right">
                      <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Add Dealer Type</button>
                  </div>
                  <?php //echo form_close(); ?>
            </div>     
          </div>  
        </div>


        <div class="table-responsive">
            <table id="example-datatable" class="table table-vcenter table-condensed table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">Dealer Type</th>
                        <th class="text-center">Created</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if($dealer_typesview){
                        for($i = 0; $i < count($dealer_typesview); $i++){
                          $id = str_replace('/', '_',rtrim(base64_encode($dealer_typesview[$i]->id), '=')); ?>
                          <tr>
                              <td class="text-center"><?php echo str_pad($i + 1, 2,"0", STR_PAD_LEFT);; ?></td>
                              <td class="text-center"><?php echo ucfirst($dealer_typesview[$i]->name); ?></td>
                              <td class="text-center"><?php echo $dealer_typesview[$i]->created_at; ?></td>
                              <td class="text-center">
                                  <div class="btn-group">
                                      <a href="<?php echo CURRENT_MODULE.'dealertypes/type_edit/'.$id;?>" data-toggle="tooltip" title="Edit" class="btn btn-xs btn-default"><i class="fa fa-pencil"></i></a>
                                      <!-- Uncomment below code for delete dealer type -->                                     
                                       <a href="javascript: show_confirm('<?php echo BASE_URL; ?>', 'Delete','<?php echo $id; ?>')" data-toggle="tooltip" title="Delete" class="btn btn-xs btn-danger"><i class="fa fa-times"></i></a> 
                                  </div>
                              </td>
                          </tr>
                    <?php } ?>

                <?php }else{
                    echo '<tr><td class="text-center" colspan="4"> No Data Found</td></tr>';
                } ?>

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