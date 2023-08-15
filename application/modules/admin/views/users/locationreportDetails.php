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
          $msg="<strong>Well done!</strong> category detail inserted with success.";
           $style = 'display: block;';           
        }
        else if($this->session->flashdata('flash_message') == 'updated')
        {
          $msg = "<strong>Well done!</strong> details updated with success.";
          $style = 'display: block;';
        }else if($this->session->flashdata('flash_message') == 'Deleted')
        {
          $msg = "<strong>Well done!</strong> category detail deleted with success.";
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
      if($this->session->flashdata('validation')!= '')
      {
          $permisnMsg = $this->session->flashdata('validation');
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
            <h2><strong>Location Report Details - <?php echo ucfirst($employeedetails->first_name)." ".$employeedetails->last_name; ?></strong></h2> <h2 style="float: right;">  <strong>Date - <?php echo $date_of_list; ?></strong>  </h2> <h3 style="float: right;"><strong>Total KM - </strong> <?php echo round($total_distance,2).' KM'; ?> </h3>
        </div>
        <div class="row">
            <div class="table-responsive">
              <table id="example-datatable" class="table table-vcenter table-condensed table-bordered">
                  <thead>
                      <tr>
                          <th class="text-center">#</th>
                          <!-- <th class="text-center">Latitude </th>
                          <th class="text-center">Longitude</th> -->
                          <th class="text-center">Time</th>
                          <th class="text-center">Address</th>
                          <th class="text-center">Distance in KM</th>
                          <th class="text-center">Battery(%)</th>
                          <th class="text-center">GPS</th>
                          <th class="text-center">Network</th>
                      </tr>
                  </thead>
                  <tbody>
                      <?php 
                      if($report_details){
                          $catPrice = array();
                          for($i = 0; $i < count($report_details); $i++){
                            //$id = str_replace('/', '_',rtrim(base64_encode($report_details[$i]->id), '='));
                          ?>
                          <tr>
                              <td class="text-center"><?php echo str_pad($i + 1, 2,"0", STR_PAD_LEFT); ?></td>
                              <!-- <td class="text-center"><?php echo $report_details[$i]->lat; ?></td>
                              <td class="text-center"><?php echo $report_details[$i]->lng; ?></td> -->
                              <td class="text-center"><?php echo $report_details[$i]->time; ?></td>
                              <td class="text-center"><?php echo $report_details[$i]->address; ?></td>
                              <td class="text-center"><?php echo $report_details[$i]->distance; ?></td>
                              <td class="text-center"><?php echo $report_details[$i]->battery; ?></td>
                              <td class="text-center"><?php echo $report_details[$i]->isGpsOn; ?></td>
                              <td class="text-center"><?php echo $report_details[$i]->mobile_network; ?></td>
                            </tr>
                      <?php } ?>
                  <?php } ?>

                  </tbody>
              </table>
            </div>
        </div>
    <!-- </div> -->
    <!-- END Datatables Content -->	
    <?php } ?>
<!-- END Page Content -->
<?php 
  $this->session->set_flashdata('permission','');
  $this->session->set_flashdata('invalid_per','');
?>