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
          $msg="<strong>Well done!</strong> user's detail inserted with success.";
           $style = 'display: block;';           
        }
        else if($this->session->flashdata('flash_message') == 'updated')
        {
          $msg = "<strong>Well done!</strong> user's detail updated with success.";
          $style = 'display: block;';
        }else if($this->session->flashdata('flash_message') == 'Deleted')
        {
          $msg = "<strong>Well done!</strong> user's detail deleted with success.";
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
            <h2><strong>Attendance List</strong></h2>
            <h2 class="pull-right" style="padding-top: 4px;">
                <form class="form-horizontal" id="exportCSV" name="exportCSV" action = "<?php echo CURRENT_MODULE.'users/attendanceListExport'; ?>" method="POST">
                  <button type="button" id="downloadCSV" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> EXPORT CSV</button>
                </form>
            </h2>
        </div>
        <div class="row">
          <div class="col-md-12">       
            <form class="form-horizontal" id="viewBycity" name="viewBycity" action = "<?php echo CURRENT_MODULE.'users/viewBycity/'; ?>" method="POST">                        
              <div class="block col-md-12">
                <div class="form-group col-md-6">
                    <label for="select_usertype" class="col-md-5 control-label">Active User Type</label>
                    <div class="col-md-7">
                        <!--<input list="browsers" size="1" class="form-control" placeholder="Select user type..." name="select_usertype" id="select_usertype" onchange="javascript: window.location.href='<?php echo CURRENT_MODULE.'users/attendanceList/'; ?>' + $(this).val()">-->
                            <!--<datalist id="browsers">-->
                        <select size="1" class="form-control select-chosen" name="select_usertype" id="select_usertype" onchange="javascript: window.location.href='<?php echo CURRENT_MODULE.'users/attendanceList/'; ?>' + $(this).val()">
                          <option value="punch-in" <?php if($method == 'punch-in'){ ?> selected <?php } ?>>Punch In</option>
                          <option value="punch-out" <?php if($method == 'punch-out'){ ?> selected <?php } ?>>Punch Out</option>
                          <option value="absent" <?php if($method == 'absent'){ ?> selected <?php } ?>>Absent</option>
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
                        <th class="text-center">Employee Name</th>
                        <th class="text-center">Employee Phone</th>
                        <th class="text-center">Punch <?php if($method == 'punch-in'){ ?> IN <?php }else if($method == 'punch-out'){ ?>Out<?php } ?></th>
                        <th class="text-center">Meter Reading</th>
                        <th class="text-center">Meter Reading Photo	</th>
                        <th class="text-center"><?php if($method == 'out'){ ?> Last Punch Time <?php }else{ ?>Total Reading<?php } ?> </th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if($users){
                         $j=0;
                         for($i = 0; $i < count($users); $i++){
                          if($method == 'punch-in'){
                            $punchObj = json_decode($users[$i]->punch_in);
                            $punchObj->total = "-";
                            
                          }elseif($method == 'punch-out'){
                            $punchObj = json_decode($users[$i]->punch_out);
                            $punchObj->total = "-";
                            
                          }elseif($method == 'absent'){
                              if(isset($users[$i]->punch_in_date)){
                                if($users[$i]->punch_in_date != date('Y-m-d') || $users[$i]->punch_in_date == NULL){
                                  
                                }else{
                                  continue;
                                }
                              }
                          }
                          else{
                            $punchObj = json_decode($users[$i]->punch_in);
                            $punchObj->total = "-";
                          }
                          $j++;
                          $index = str_pad($j, 2,"0", STR_PAD_LEFT);
                    ?>
                        <tr>
                            <td class="text-center"><?php echo $index; ?></td>
                            <td class="text-center"><?php echo $users[$i]->empName; ?></td>
                            <td class="text-center"><?php echo $users[$i]->phone; ?></td>
                            <td class="text-center"><?php echo (isset($punchObj) )? $punchObj->place: "-"; ?></td>
                            <td class="text-center"><?php echo (isset($punchObj) )? $punchObj->meter_reading_in_km : '-'; ?></td>
                            <td class="text-center">
                            <?php if(isset($punchObj)){ ?>
                <a href="<?php echo PUNCH_IMAGE.'large/'.$punchObj->meter_reading_photo; ?>" target="_blank" >
                  <img ng-src="<?php echo PUNCH_IMAGE.'thumb/'.$punchObj->meter_reading_photo; ?>" src="<?php echo PUNCH_IMAGE.'thumb/'.$punchObj->meter_reading_photo; ?>" alt="" width="50" height="50">
                </a>  
                            <?php }else{ echo "-"; } ?></td>
                            <td class="text-center"><?php echo (($method == 'out') ? $users[$i]->last_punch : ((isset($punchObj) ) ? $users[$i]->traveled_km : '-')); ?></td>
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