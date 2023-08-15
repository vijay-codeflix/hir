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
            <h2><strong><?php if($typeOfPayment == 'approved'){ echo "Approved"; }else{ echo "Pending"; } ?> Payments</strong></h2>
            <h2 class="pull-right" style="padding-top: 4px;">
                <form class="form-horizontal" id="exportCSV" name="exportCSV" action = "<?php echo CURRENT_MODULE.'payments/paymentExportCSV'; ?>" method="POST">
                  <input type="hidden" value="<?php echo $typeOfPayment; ?>" id="type"  name="type" />
                  <button type="button" id="downloadCSV" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> EXPORT CSV</button>
                </form>
            </h2>
            <h2 style="float: right;">Total amount: <strong><i class="fa fa-rupee"></i> <?php echo $total; ?></strong></h2>
        </div>
        <div class="row">
          <div class="col-md-12">       
              <form class="form-horizontal" id="viewBycity" name="viewBycity" action = "<?php echo CURRENT_MODULE.'payments/'.$typeOfPayment; ?>" method="POST">                        
                <input type="hidden" value="" id="byEmployee" />
                
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
                        <th class="text-center">Dealer Name</th>
                        <th class="text-center">Employee Name</th>
                        <th class="text-center">Amount </th>
                        <th class="text-center">Type</th>
                        <th class="text-center">Cheque Detail</th>
                        <th class="text-center">Payment Detail</th>
                        <th class="text-center">Date</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if($response){
                         for($i = 0; $i < count($response); $i++){
                          $id = str_replace('/', '_',rtrim(base64_encode($response[$i]->id), '=')); 
                    ?>
                        <tr>
                            <td class="text-center"><?php echo str_pad($i + 1, 2,"0", STR_PAD_LEFT); ?></td>
                            <td class="text-center"><?php echo ucfirst($response[$i]->dealer_name); ?></td>
                            <td class="text-center"><?php echo ucfirst($response[$i]->empName); ?></td>
                            <td class="text-center"><i class="fa fa-rupee"></i> <?php echo $response[$i]->amount; ?></td>
                            <td class="text-center"><?php echo ucfirst($response[$i]->payment_method); ?></td>
                            <td class="text-center"><?php echo $response[$i]->cheque_detail; ?></td>
                            <td class="text-center"><?php echo $response[$i]->payment_details; ?></td>
                            <td class="text-center"><?php echo $response[$i]->reqDate; ?></td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="javascript: show_confirm('<?php echo BASE_URL; ?>', 'view','<?php echo $id; ?>')" onclick="$('#view-users').modal('show');" data-toggle="tooltip" title="View" class="btn btn-xs"><i class="fa fa-eye"></i></a>
                                    <?php if($typeOfPayment == 'pending'){ ?>
                                    <a href="javascript: show_confirm('<?php echo BASE_URL; ?>', 'approve','<?php echo $id; ?>')" data-toggle="tooltip" title="Approve" class="btn btn-xs btn-default"><i class="fa fa-check"></i></a>
                                    <a href="javascript: show_confirm('<?php echo BASE_URL; ?>', 'delete','<?php echo $id; ?>')" data-toggle="tooltip" title="Delete" class="btn btn-xs btn-danger"><i class="fa fa-times"></i></a>
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
        
   <!--  </div> -->
    <!-- END Datatables Content -->	
    <?php } ?>
<!-- END Page Content -->
<?php 
  $this->session->set_flashdata('permission','');
  $this->session->set_flashdata('invalid_per','');
?>