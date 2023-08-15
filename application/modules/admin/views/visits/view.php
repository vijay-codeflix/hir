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
            <h2><strong>Employee Visits</strong></h2>
            <h2 class="pull-right" style="padding-top: 4px;">
                <form class="form-horizontal" id="exportCSV" name="exportCSV" action = "<?php echo CURRENT_MODULE.'visits/visitExportCSV'; ?>" method="POST">
                  <button type="button" id="downloadCSV" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> EXPORT CSV</button>
                </form>
            </h2>
        </div>
        <div class="row">
            <div class="col-md-12">       
                <form class="form-horizontal" id="viewBycity" name="viewBycity" action = "<?php echo CURRENT_MODULE.'visits'; ?>" method="POST">
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
                                <!--<input list="browsers" size="1" class="form-control" name="select_employee" id="select_employee" placeholder="Select Employee here...">-->
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
                </form>
              </div>
            </div>
            <div class="table-responsive">
              <table id="example-datatable" class="table table-vcenter table-condensed table-bordered">
                  <thead>
                      <tr>
                          <th class="text-center">#</th>
                          <th class="text-center">Employee</th>
                          <th class="text-center">Party Name</th>
                          <th class="text-center">Company Name</th>
                          <th class="text-center">Area</th>
                          <th class="text-center">Visited</th>
                          <th class="text-center">Action</th>
                      </tr>
                  </thead>
                  <tbody>
                      <?php 
                      if($visits){
                          $catPrice = array();
                          for($i = 0; $i < count($visits); $i++){
                            $id = str_replace('/', '_',rtrim(base64_encode($visits[$i]->id), '='));
                          ?>
                          <tr>
                              <td class="text-center"><?php echo str_pad($i + 1, 2,"0", STR_PAD_LEFT); ?></td>
                              <td class="text-center"><?php echo ucwords($visits[$i]->first_name.' '.$visits[$i]->last_name); ?></td>
                              <?php 
                                if(empty($visits[$i]->party->id)){
                                    echo '<td class="text-center" colspan=3> This party is deleted</td><td style="display: none"></td><td style="display: none"></td>';
                                }else{
                              ?>
                                    <td class="text-center">
                                        <?php //echo ucfirst($visits[$i]->name); ?>
                                        <?php 
                                        if(!empty($visits[$i]->name))
                                            echo ucwords($visits[$i]->name); 
                                        else 
                                            echo ucwords($visits[$i]->party->owner_detail);
                                      ?>
                                    </td>
                                    <td class="text-center"><?php echo $visits[$i]->contact_firm; ?></td>
                                    <td class="text-center"><?php echo $visits[$i]->area_or_town; ?></td>
                              <?php 
                                }
                              ?>
                              <td class="text-center"><?php echo date(DEFAULT_DATE_FORMAT,strtotime($visits[$i]->created_at)); ?></td>
                              <td class="text-center">
                                <div class="btn-group">
                                     <?php 
                                       if(empty($visits[$i]->party->id)){
                                           echo '<i class="fa fa-exclamation text-danger" data-toggle="tooltip" title="Error"></i>';
                                       }else{
                                     ?>
                                           <a href="javascript: show_confirm('<?php echo BASE_URL; ?>', 'view','<?php echo $id; ?>')" onclick="$('#viewcities').modal('show');" data-toggle="tooltip" title="View" class="btn btn-xs"><i class="fa fa-eye"></i></a>
                                     <?php 
                                       }
                                     ?>
                                </div>
                            </td>
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