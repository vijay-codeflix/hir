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
            <h2><strong>Location Report</strong></h2>
        </div>
        <div class="row">
            <div class="col-md-12">       
                <form class="form-horizontal" id="viewLocation" name="viewLocation" action = "<?php echo CURRENT_MODULE.'users/locationReport'; ?>" method="POST">
                  <div class="block col-md-6 pull-left no-border">
                    <div class="form-group">
                            <label class="col-md-4 control-label" for="start_date">Select date range</label>
                            <div class="col-md-8">
                              <div class='input-group date' id='datetimepicker1' data-date-format="dd/mm/yyyy">
                                <input type='text' class="form-control" placeholder="Punch In Date" class="form-control" name="date" id="date" value="<?php echo $date; ?>" />
                                <!-- data-date-format="dd/mm/yyyy" -->
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                              </div>
                            </div>
                        </div>
                  </div>
                  <div class="block col-md-6 pull-right no-border">
                        <div class="form-group">
                            <label for="select_employee" class="col-md-5 control-label">Select Employee</label>
                            <div class="col-md-7">
                                <select size="1" class="form-control" name="select_employee" id="select_employee">
                                    <option value="">All</option>
                                    <?php 
                                      if($getEmployees){
                                        foreach($getEmployees as $row) 
                                          {
                                              $uid = $row->id; 
                                              if(isset($empId) && ($empId == $uid) ){
                                                  echo '<option selected value="'.$uid.'">'.$row->first_name." ".$row->last_name." - (".$row->phone.')</option>';
                                              }else{
                                                echo '<option value="'.$uid.'">'.$row->first_name." ".$row->last_name." - (".$row->phone.')</option>';  
                                              }
                                          }
                                      } 
                                    ?>
                                </select>
                            </div>
                        </div>
                  </div>
                </form>
              </div>
            </div>
            <div class="table-responsive">
              <table id="example-datatable" class="table table-vcenter table-condensed table-bordered">
                  <thead>
                      <tr>
                          <th class="text-center">#</th>
                          <th class="text-center">Employee </th>
                          <th class="text-center">Phone</th>
                          <th class="text-center">Punch IN</th>
                          <th class="text-center">Punch OUT</th>
                          <th class="text-center">Punch IN Location</th>
                          <th class="text-center">Punch Out Location</th>
                         <!--  <th class="text-center">Punch IN Time</th>
                          <th class="text-center">Punch Out Time</th> -->
                          <th class="text-center">Details Report</th>
                      </tr>
                  </thead>
                  <tbody>
                      <?php 
                      if($reports){
                          $catPrice = array();
                          for($i = 0; $i < count($reports); $i++){
                            $id = str_replace('/', '_',rtrim(base64_encode($reports[$i]->id), '='));
                          ?>
                          <tr>
                              <td class="text-center"><?php echo str_pad($i + 1, 2,"0", STR_PAD_LEFT); ?></td>
                              <td class="text-center"><?php echo ucfirst($reports[$i]->empName); ?></td>
                              <td class="text-center"><?php echo $reports[$i]->phone; ?></td>
                              <td class="text-center"><?php echo $reports[$i]->punchInDate; ?></td>
                              <td class="text-center"><?php echo $reports[$i]->punchOutDate; ?></td>
                              <td class="text-center"><?php echo $reports[$i]->punchInLocation; ?></td>
                              <td class="text-center"><?php echo $reports[$i]->punchOutLocation; ?></td>
                              <!-- <td class="text-center"><?php echo $reports[$i]->inReading; ?></td>
                              <td class="text-center"><?php echo $reports[$i]->outReading; ?></td> -->
                              <td><a href="<?php echo CURRENT_MODULE.'users/locationDetails/'.$id;?>" data-toggle="tooltip" title="View" class="btn btn-xs btn-default"><i class="fa fa-eye" aria-hidden="true"></i></a></td>                              
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