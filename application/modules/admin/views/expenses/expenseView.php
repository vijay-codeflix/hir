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
            <h2><strong>Employee Expenses</strong></h2>
            <h2 class="pull-right" style="padding-top: 4px;">
                <form class="form-horizontal" id="exportCSV" name="exportCSV" action = "<?php echo CURRENT_MODULE.'expenses/expenseExportCSV'; ?>" method="POST">
                  <button type="button" id="downloadCSV" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> EXPORT CSV</button>
                </form>
            </h2>
        </div>
        <div class="row">
            <div class="col-md-12">       
                <form class="form-horizontal" id="viewBycity" name="viewBycity" action = "<?php echo CURRENT_MODULE.'expenses/list/'; ?>" method="POST">                        
                  <input type="hidden" value="" id="byStatus" />
                  <input type="hidden" value="" id="bycategory" />
                  <input type="hidden" value="" id="byEmployee" />
                  <div class="block col-md-4 pull-right no-border">
                        <div class="form-group">
                            <label for="select_status" class="col-md-5 control-label">Status</label>
                            <div class="col-md-7">
                                <!--<input list="select_statuses" size="1" class="form-control" placeholder="Select status here..." name="select_status" id="select_status">-->
                                    <!--<datalist id="select_statuses">-->
                                <select size="1" class="form-control  select-chosen" name="select_status" id="select_status">
                                    <option value="0">All</option>
                                    <?php 
                                    foreach($getStatus as $key => $statusVal) 
                                    { 
                                        if(isset($selectStatus) && ($selectStatus == ($key+1)) ){
                                            echo '<option selected value="'.($key+1).'">'.ucfirst($statusVal).'</option>';
                                        }else{
                                            echo '<option value="'.($key+1).'">'.ucfirst($statusVal).'</option>';   
                                        }
                                    }
                                    ?>
                                </select>
                                    <!--</datalist>-->
                            </div>
                        </div>
                  </div>
                  <div class="block col-md-4 pull-right no-border">
                        <div class="form-group">
                            <label for="select_employee" class="col-md-5 control-label">Employee Name</label>
                            <div class="col-md-7">
                                <!--<input list="select_employees" size="1" class="form-control" placeholder="Select employee here..." name="select_employee" id="select_employee">-->
                                    <!--<datalist id="select_employees">-->
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
                  <div class="block col-md-4 pull-right no-border">
                        <div class="form-group">
                            <label for="select_category" class="col-md-5 control-label">Category</label>
                            <div class="col-md-7">
                                <!--<input list="select_categories" size="1" class="form-control" placeholder="Select category here..." name="select_category" id="select_category">-->
                                    <!--<datalist id="select_categories">-->
                                <select size="1" class="form-control select-chosen" name="select_category" id="select_category">
                                    <option value="">All</option>
                                    <?php 
                                    if($getCategories){
                                      foreach($getCategories as $row) 
                                      {
                                        $ctid = $row->id; 
                                        if(isset($catId) && ($catId == $ctid) ){
                                            echo '<option selected value="'.$ctid.'">'.$row->name.'</option>';
                                        }else{
                                            echo '<option value="'.$ctid.'">'.$row->name.'</option>';  
                                        }
                                      }
                                    }
                                    ?>
                                </select>
                                    <!--</datalist>-->
                            </div>
                        </div>
                  </div>
                  <div class="block col-md-8 pull-left no-border">
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="start_date">Select Date Range</label>
                        <div class="col-md-9">
                            <div class="input-group input-daterange" data-date-format="dd/mm/yyyy">
                                <input type="text" id="start_date" name="start_date" value="<?php echo $startDate; ?>" class="form-control text-center" placeholder="From">
                                <span class="input-group-addon"><i class="fa fa-angle-right"></i></span>
                                <input type="text" id="end_date" name="end_date" value="<?php echo $endDate; ?>" class="form-control text-center" placeholder="To">
                            </div>
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
                          <th class="text-center">Employee Name(Grade)</th>
                          <th class="text-center">Employee Phone</th>
                          <th class="text-center">Category</th>
                          <th class="text-center">Requested Amount</th>
                          <th class="text-center">Approved Amount</th>
                          <th class="text-center">Allowed Amount</th>
                          <th class="text-center">Place (Grade)</th>
                          <th class="text-center">Reason</th>
                          <th class="text-center">Status</th>
                          <th class="text-center">Date</th>
                          <th class="text-center">Action</th>
                      </tr>
                  </thead>
                  <tbody>
                      <?php 
                      if($expenses){
                          $catPrice = array();
                          for($i = 0; $i < count($expenses); $i++){
                            $id = str_replace('/', '_',rtrim(base64_encode($expenses[$i]->id), '='));
                            $catKeyVal = $expenses[$i]->catId.'_'. strtotime(date('Y-m-d', strtotime($expenses[$i]->created_at))) . '_' . $expenses[$i]->empId;

                            if(array_key_exists($catKeyVal, $catPrice)){
                                $catPrice[$catKeyVal] =  $catPrice[$catKeyVal] +  $expenses[$i]->reqAmount;
                            }else{
                                $catPrice[$catKeyVal] = $expenses[$i]->reqAmount;
                            }
                          ?>
                          <tr>
                              <td class="text-center"><?php echo str_pad($i + 1, 2,"0", STR_PAD_LEFT); ?></td>
                              <td class="text-center"><?php echo ucfirst($expenses[$i]->empName) . " (".$expenses[$i]->empGrade.")"; ?></td>
                              <td class="text-center"><?php echo $expenses[$i]->phone; ?></td>
                              <td class="text-center"><?php echo ucfirst($expenses[$i]->catName); ?></td>
                              <td class="text-center">
                                <?php if( ($catPrice[$catKeyVal] > $expenses[$i]->allowAmount) && $expenses[$i]->categoryType != 'non_price' && $expenses[$i]->status == 'pending'){
                                    echo '<span class="label label-danger"> ' .$expenses[$i]->currency_symbol.' '.$expenses[$i]->reqAmount. '</span>';
                                    //<i class="fa fa-rupee"></i>
                                }else{
                                    echo $expenses[$i]->currency_symbol.' '.$expenses[$i]->reqAmount;
                                    //'<i class="fa fa-rupee"></i> '. 
                                } ?>
                              </td>
                              <td class="text-center"><!-- <i class="fa fa-rupee"></i> --> <?php echo $expenses[$i]->currency_symbol.' '.$expenses[$i]->approveAmount; ?></td>
                              <td class="text-center"><!-- <i class="fa fa-rupee"></i> --> <?php echo $expenses[$i]->currency_symbol.' '.$expenses[$i]->allowAmount; ?></td>
                              <td class="text-center"><?php echo ucfirst($expenses[$i]->place) . " (".$expenses[$i]->ctGrade.")"; ?></td>
                              <td class="text-center"><?php echo $expenses[$i]->reason; ?></td>
                              <td class="text-center"><?php 
                              $st = $expenses[$i]->status;
                              $status = "";
                              
                              if($st == "pending"){
                                  $status = '<span class="label label-warning">Pending</span>';
                              }else if($st == "approved"){
                                  $status = '<span class="label label-success">Approved</span>';
                              }else if($st == "rejected"){
                                  $status = '<span class="label label-danger">Rejected</span>';
                              }else if($st == "partial-approved"){
                                  $status = '<span class="label label-info">Partial approved</span>';
                              }
                              echo $status; 
                              ?></td>
                              <td class="text-center"><?php echo date(DEFAULT_DATETIME_FORMAT,strtotime($expenses[$i]->created_at)); ?></td>
                              <td class="text-center"><a href="javascript: show_confirm('<?php echo BASE_URL; ?>', 'View','<?php echo $expenses[$i]->id; ?>')">View</a> | 
                                <?php
                                if($this->session->userdata['logged_in']['userid'] ==  $expenses[$i]->parent_id && empty($expenses[$i]->allow_approved)){
                                ?>
                                <a href="javascript: expenseAction('approved','<?php echo BASE_URL; ?>','<?php echo $expenses[$i]->id; ?>');">Approve</a> | <a href="javascript: expenseAction('partial-approved','<?php echo BASE_URL; ?>','<?php echo $expenses[$i]->id; ?>');">Approve Patially</a> | 
                                
                                <?php }else if(!empty($expenses[$i]->allow_approved) && ( $expenses[$i]->allow_approved == $this->session->userdata['logged_in']['userid'])){ ?>
                                  <a href="javascript: expenseAction('approved','<?php echo BASE_URL; ?>','<?php echo $expenses[$i]->id; ?>');">Approve</a> | <a href="javascript: expenseAction('partial-approved','<?php echo BASE_URL; ?>','<?php echo $expenses[$i]->id; ?>');">Approve Patially</a> |

                                <?php }else if($expenses[$i]->login_user_approved > 0){ ?>
                                 <span class="text-success"> <!-- alert-success,alert-info,alert-danger,alert-warning -->Already Approved </span>
                                <?php }else if($this->session->userdata['logged_in']['usertype'] == 'Admin'){ ?>
                                   <a class="text-danger" href="javascript: expenseAction('approved_by_admin','<?php echo BASE_URL; ?>','<?php echo $expenses[$i]->id; ?>');">Approve</a> | <a class="text-danger" href="javascript: expenseAction('partial-approved_by_admin','<?php echo BASE_URL; ?>','<?php echo $expenses[$i]->id; ?>');">Approve Patially</a> | <a href="javascript: expenseAction('rejected','<?php echo BASE_URL; ?>','<?php echo $expenses[$i]->id; ?>');">Reject</a></td>
                                <?php }else if($this->session->userdata['logged_in']['usertype'] == 'Super Admin'){ ?>
                                   <a class="text-danger" href="javascript: expenseAction('approved_by_super_admin','<?php echo BASE_URL; ?>','<?php echo $expenses[$i]->id; ?>');">Approve</a> | <a class="text-danger" href="javascript: expenseAction('partial-approved_by_super_admin','<?php echo BASE_URL; ?>','<?php echo $expenses[$i]->id; ?>');">Approve Patially</a> | <a href="javascript: expenseAction('rejected','<?php echo BASE_URL; ?>','<?php echo $expenses[$i]->id; ?>');">Reject</a></td>
                                <?php }else{ ?>
                                  <a class="text-warning" href="javascript: viewApprovalStatus('<?php echo BASE_URL; ?>',<?php echo $expenses[$i]->id; ?>);">Waiting For Child Approval</a> | <a href="javascript: expenseAction('rejected','<?php echo BASE_URL; ?>','<?php echo $expenses[$i]->id; ?>');">Reject</a></td>
                                 <?php }  ?>
                                
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