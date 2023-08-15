<!-- Page content -->
<div id="page-content">
  <?php
    if($this->session->flashdata('invalid_per')!= '')
    { ?>
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
    <!-- User details -->
    <div class="row">
      <div class="col-md-12">
        <!-- Basic Form Elements Block -->
        <div class="block">
          <div class="block-title">
            <h2><strong>User Information</strong></h2>
          </div>    
          <div class="row">
            <div class="col-md-12">
              <!-- Basic Form Elements Block -->
              <div class="block">
                <!-- Basic Form Elements Title -->
                <div class="block-title">
                  <h2><strong>Profile</strong></h2>
                </div>
                <!-- END Form Elements Title -->
                
                <table class="table table-vcenter table-condensed table-bordered">
                  <tbody>
                    <tr><td>First Name</td><td><?= $user_details->first_name; ?></td></tr>
                    <tr><td>Last Name</td><td><?= $user_details->last_name; ?></td></tr>
                    <tr><td>Email</td><td><?= $user_details->email; ?></td></tr>
                    <tr><td>Phone</td><td><?= $user_details->phone; ?></td></tr>
                    <tr><td>Employee ID</td><td><?= $user_details->emp_id; ?></td></tr>
                    <tr><td>Type</td><td><?= 'Employee';//$user_details->user_type; ?></td></tr>
                    <tr><td>Device</td><td><?= $user_details->device_model; ?></td></tr>
                    <tr><td>Mobile APK Version</td><td><?= ($user_details->mobile_apk_version != null) ? $user_details->mobile_apk_version : ' '; ?></td></tr>
                    <tr><td>Sub Admin</td><td><?= $user_details->parent_name; ?></td></tr>
                  </tbody>
                </table>
                                       
              </div>
              <!-- END Basic Form Elements Block -->
            </div>
          </div>
        </div>
        <!-- END Basic Form Elements Block -->
      </div>
    </div>

    <!-- Datatables Content -->
    <div class="block full">
        <div class="block-title">
            <h2><strong>Attendances</strong></h2>
        </div>
        <div class="row">
        <div class="table-responsive">
        <table id="example-datatable" class="table table-vcenter table-condensed table-bordered">
          <thead>
            <tr>
              <th class="text-center">Start Point</th>
              <th class="text-center">End Point</th>
              <th class="text-center">Start Time</th>
              <th class="text-center">End Time</th>
              <th class="text-center">Logged Time</th>
              <th class="text-center">KM</th>
              <th class="text-center">Date</th>              
              <th class="text-center">Status</th>
              <th class="text-center">Action</th>
            </tr>
          </thead>

          <tbody>
            <?php
            if($user_attendance){
              for($i = 0; $i < count($user_attendance); $i++){
                $attendanceId = str_replace('/', '_',rtrim(base64_encode($user_attendance[$i]->id), '=')); 
                $punch_in = json_decode($user_attendance[$i]->punch_in);
               if(!empty(($user_attendance[$i]->punch_out))){
                  $punch_out = json_decode($user_attendance[$i]->punch_out);
                  $meter_reading_in_km = (isset($punch_out->meter_reading_in_km) && isset($punch_in->meter_reading_in_km)) ? $punch_out->meter_reading_in_km - $punch_in->meter_reading_in_km : ' - ';
                  $log_time = (isset($punch_out->data)) ? gmdate("H:i:s", (strtotime($punch_out->date) - strtotime($punch_in->date) )) : ' - ';
                }else{
                  $punch_out = (object) array('place'=> '-', 'date' => '-', 'meter_reading_in_km' => 0);
                  $meter_reading_in_km = '-';
                  $log_time = '-';                  
                }
                ?>
              <tr>
              <td class="text-center"><?php echo (isset($punch_in->place)) ? $punch_in->place: ' - '; ?></td>
              <td class="text-center"><?php echo (isset($punch_out->place)) ? $punch_out->place: ' - '; ?></td>
              <td class="text-center"><?php echo (isset($punch_in->date)) ? $punch_in->date: ' - '; ?></td>
              <td class="text-center"><?php echo (isset($punch_out->date)) ? $punch_out->date: ' - '; ?></td>
              <td class="text-center"><?php echo $log_time; ?></td>
              <td class="text-center"><?php echo $meter_reading_in_km; ?></td>
              <td class="text-center"><?php echo $user_attendance[$i]->punch_in_date; ?></td>
              <td class="text-center"> <?php echo 'Status'; ?></td>
              <td class="text-center"> <?php echo '<a href="'.BASE_URL.'admin/users/userRoute/'.$attendanceId.'">View Route</a>'; ?></td>
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