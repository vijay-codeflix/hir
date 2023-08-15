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
          $msg="<strong>Well done!</strong> dealer detail inserted with success.";
           $style = 'display: block;';           
        }
        else if($this->session->flashdata('flash_message') == 'imported')
        {
          $msg="<strong>Congrates!</strong> dealer imported with success.";
           $style = 'display: block;';           
        }
        else if($this->session->flashdata('flash_message') == 'updated')
        {
          $msg = "<strong>Well done!</strong> details updated with success.";
          $style = 'display: block;';
        }else if($this->session->flashdata('flash_message') == 'Deleted')
        {
          $msg = "<strong>Well done!</strong> dealer detail deleted with success.";
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
            <h2><strong>Dealers</strong></h2>
            <h2 class="pull-right" style="padding-top: 4px;">
                <?php echo form_open('admin/dealers/add'); ?>
                  <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Add Party</button>
                <?php echo form_close(); ?>
            </h2>
        </div>
        <div class="row">
            <div class="col-md-12">       
                <form class="form" id="viewBycity" name="viewBycity" action = "<?php echo CURRENT_MODULE.'dealers'; ?>" method="POST">
                    <div class="form-row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="select_employee" class="form-label">Select Employee</label>
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
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" for="start_date">Select date range</label>
                                <!--<div class="col-md-8">-->
                                    <div class="input-group input-daterange" data-date-format="dd/mm/yyyy">
                                        <input type="text" id="start_date" name="start_date" value="<?php echo $startDate; ?>" class="form-control text-center" placeholder="From">
                                        <span class="input-group-addon"><i class="fa fa-angle-right"></i></span>
                                        <input type="text" id="end_date" name="end_date" value="<?php echo $endDate; ?>" class="form-control text-center" placeholder="To">
                                    </div>
                                <!--</div>-->
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="select_category" class="form-label">Select Category</label>
                                <select size="1" class="form-control select-chosen" name="select_category" id="select_category">
                                    <option value="">All</option>
                                    <?php 
                                    // echo $catId;exit();
                                      if($getCategories){
                                        foreach($getCategories as $row) 
                                          {
                                              $uid = $row->id; 
                                              if(isset($catId) && ($catId == $uid) ){
                                                  echo '<option selected value="'.$uid.'">'.$row->name.'</option>';
                                              }else{
                                                echo '<option value="'.$uid.'">'.$row->name.'</option>';  
                                              }
                                          }
                                      } 
                                    ?>
                                </select>
                            </div>
                        </div>
                        <!--<div class="col-md-6">-->
                            <!--<div class="form-group">-->
                                <!--<label for="select_type" class="form-label">Select Type</label>-->
                                <!--<select size="1" class="form-control select-chosen" name="select_type" id="select_type">-->
                                    <!--<option value="">All</option>-->
                                     <?php 
                                    //   if($getTypes){
                                        // foreach($getTypes as $row) 
                                        //   {
                                            //   $uid = $row->id; 
                                            //   if(isset($typeId) && ($typeId == $uid) ){
                                                //   echo '<option selected value="'.$uid.'">'.$row->name.'</option>';
                                            //   }else{
                                                // echo '<option value="'.$uid.'">'.$row->name.'</option>';  
                                            //   }
                                        //   }
                                    //   } 
                                    ?>
                                <!--</select>-->
                            <!--</div>-->
                        <!--</div>-->
                    </div>
                    <!--<div class="form-row">.-->
                    <!--    <div class="col-md-6">-->
                            <!--<div class="col-md-12">-->
                    <!--            <button class="btn btn-sm btn-primary" type="submit"><i class="fa fa-angle-right"></i> Search</button>-->
                    <!--            <button class="btn btn-sm btn-primary" type="reset" id="reset-btn"><i class="fa fa-angle-right"></i> Reset</button>-->
                            <!--</div>-->
                    <!--  </div>-->
                    <!--</div>-->
                </form>
              </div>

                <div class="col-md-12">  
                  <div class="block col-md-8 no-border">
                        <div class="form-group">
                            <label for="csv" class="col-md-4 control-label">Import Dealers from CSV file</label>
                            <div class="col-md-8">
                              <form action="<?php echo base_url(); ?>admin/dealers/importDealers" method="post" enctype="multipart/form-data" name="importCsvForm" id="importCsvForm"> 
                                <input class="pull-left" name="csv" type="file" id="csv" accept=".csv" /> 
                                <button type="button" id="importCsv" class="btn btn-sm btn-primary pull-right"><i class="fa fa-plus"></i> Import</button>
                              </form> 
                            </div>
                        </div>
                  </div>
                </div>
            </div>
        <div class="table-responsive">
            <table id="example-datatable" class="table table-vcenter table-condensed table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">Name(Ph.no.)</th>
                        <th class="text-center">Company</th>
                        <th class="text-center">Category</th>
                        <!--<th class="text-center">Type</th>-->
                        <th class="text-center">Area</th>
                        <th class="text-center">Date</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if($dealers){
                        $catPrice = array();
                        for($i = 0; $i < count($dealers); $i++){
                          $id = str_replace('/', '_',rtrim(base64_encode($dealers[$i]->id), '='));
                        ?>
                        <tr>
                            <td class="text-center"><?php echo str_pad($i + 1, 2,"0", STR_PAD_LEFT); ?></td>
                            <td class="text-center">
                                <?php 
                                  if(!empty($dealers[$i]->dealer_name))
                                      echo ucfirst($dealers[$i]->dealer_name).'-'.$dealers[$i]->dealer_phone.')'; 
                                  else 
                                      echo ucfirst($dealers[$i]->owner_detail);
                                ?>
                            </td>
                            <td class="text-center"><?php echo $dealers[$i]->firm_name; ?></td>
                            <td class="text-center"><?php echo $dealers[$i]->category; ?></td>
                            <!--<td class="text-center"><?php echo $dealers[$i]->type; ?></td>-->
                            <td class="text-center"><?php echo $dealers[$i]->city_or_town; ?></td>
                            <td class="text-center"><?php echo date(DEFAULT_DATE_FORMAT,strtotime($dealers[$i]->created_at)); ?></td>
                            <td class="text-center">
                              <div class="btn-group">
                                  <a href="javascript: show_confirm('<?php echo BASE_URL; ?>', 'view','<?php echo $id; ?>')" onclick="$('#viewcities').modal('show');" data-toggle="tooltip" title="View" class="btn btn-xs"><i class="fa fa-eye"></i></a>
                                  <a href="<?php echo CURRENT_MODULE.'dealers/edit/'.$id;?>" data-toggle="tooltip" title="Edit" class="btn btn-xs btn-default"><i class="fa fa-pencil"></i></a>
                                  <a href="javascript: show_confirm('<?php echo BASE_URL; ?>', 'Delete','<?php echo $id; ?>')" data-toggle="tooltip" title="Delete" class="btn btn-xs btn-danger"><i class="fa fa-times"></i></a>          
                              
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