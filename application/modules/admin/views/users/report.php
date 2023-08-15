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
            <h2><strong>Attendance Report</strong></h2>
            <h2 class="pull-right" style="padding-top: 4px;">
                <form class="form-horizontal" id="exportCSV" name="exportCSV" action = "<?php echo CURRENT_MODULE.'users/attendanceReportExportCSV'; ?>" method="POST">
                  <button type="button" id="downloadCSV" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> EXPORT CSV</button>
                </form>
            </h2>
        </div>
        <div class="row">
            <div class="col-md-12">       
                <form class="form-horizontal" id="viewBycity" name="viewBycity" action = "<?php echo CURRENT_MODULE.'users/attendanceReport'; ?>" method="POST">
                  <div class="block col-md-6 pull-left no-border">
                    <div class="form-group">
                            <label class="col-md-4 control-label" for="start_date">Select date range</label>
                            <div class="col-md-8">
                                <div class="input-group input-daterange" data-date-format="dd/mm/yyyy">
                                    <input type="text" id="start_date" name="start_date" value="<?php echo $startDate; ?>" class="form-control text-center" placeholder="From">
                                    <span class="input-group-addon"><i class="fa fa-angle-right"></i></span>
                                    <input type="text" id="end_date" name="end_date" value="<?php echo $endDate; ?>" class="form-control text-center" placeholder="To">
                                </div>
                            </div>
                        </div>
                  </div>
                  <div class="block col-md-6 pull-right no-border">
                        <div class="form-group">
                            <label for="select_employee" class="col-md-5 control-label">Select Employee</label>
                            <div class="col-md-7">
                                <!--<input list="browsers" placeholder="Select employee here..." size="1" class="form-control" name="select_employee" id="select_employee">-->
                                    <!--<datalist id="browsers">-->
                                <select size="1" class="form-control select-chosen" name="select_employee" id="select_employee">
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
                                    <!--</datalist>-->
                            </div>
                        </div>
                  </div>
                  <div class="block col-md-1 pull-right no-border">
                    <div class="form-group">
                      <div class="col-md-12">
                        <button type="reset" id="reset-btn" class="form-control btn-primary">Reset</button>
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
                          <th class="text-center">Employee	</th>
                          <th class="text-center">Phone</th>
                          <th class="text-center">Punch IN</th>
                          <th class="text-center">Punch OUT</th>
                          <th class="text-center">Punch IN Location</th>
                          <th class="text-center">Punch Out Location</th>
                          <th class="text-center">Punch IN Meter Reading</th>
                          <th class="text-center">Punch Out Meter Reading</th>
                          <th class="text-center">Punch IN Meter Reading Photo</th>
                          <th class="text-center">Punch Out Meter Reading Photo</th>
                          <th class="text-center">Total Distance Km (by users)</th>
                          <th class="text-center">Total Distance Km (by System)</th>
                          <th class="text-center">Total Hours</th>
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
                              <td class="text-center"><?php echo $reports[$i]->inReading; ?></td>
                              <td class="text-center"><?php echo $reports[$i]->outReading; ?></td>
                              <td class="text-center">
                                <?php if(isset($reports[$i]->inPhoto) && $reports[$i]->inPhoto != ''){ ?>
                                    <a href="<?php echo PUNCH_IMAGE.'large/'.$reports[$i]->inPhoto; ?>" target="_blank" >
                                    <img ng-src="<?php echo PUNCH_IMAGE.'thumb/'.$reports[$i]->inPhoto; ?>" src="<?php echo PUNCH_IMAGE.'thumb/'.$reports[$i]->inPhoto; ?>" alt="" width="50" height="50">
                                    </a>  
                                <?php }else{ echo "-"; } ?>
                              </td>
                              <td class="text-center">
                                <?php if(isset($reports[$i]->outPhoto) && $reports[$i]->outPhoto != ''){ ?>
                                    <a href="<?php echo PUNCH_IMAGE.'large/'.$reports[$i]->outPhoto; ?>" target="_blank" >
                                    <img ng-src="<?php echo PUNCH_IMAGE.'thumb/'.$reports[$i]->outPhoto; ?>" src="<?php echo PUNCH_IMAGE.'thumb/'.$reports[$i]->outPhoto; ?>" alt="" width="50" height="50">
                                    </a>  
                                <?php }else{ echo "-"; } ?>
                              </td>
                              <td class="text-center"><?php echo $reports[$i]->totalReading; ?></td>
                              <td class="text-center"><?php echo $reports[$i]->totalDistance; ?></td>
                              <td class="text-center"><?php echo $reports[$i]->totalLoggedTime; ?></td>
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