<!-- Page content -->
<div id="page-content">
    <div class="row">
        <div class="col-md-7 col-md-offset-2-5">
            <!-- Basic Form Elements Block -->
            <div class="block">
                <!-- Basic Form Elements Title -->
                <div class="block-title">
                    <div class="block-options pull-right">
                        <a title="" data-toggle="tooltip" class="btn btn-alt btn-sm btn-default" href="<?php echo BASE_URL.'admin/dealers/view'; ?>" data-original-title="Close"><i class="fa fa-times"></i></a>
                    </div>
                    <h2><strong>Edit Partie</strong></h2>
                </div>
                <!-- END Form Elements Title -->

                <!-- Basic Form Elements Content -->
                <form id = "form-validation" class="form-horizontal form-bordered" enctype="multipart/form-data" method="post" action="<?php echo BASE_URL.'admin/dealers/update'; ?>">
                    <input type="hidden" name="id" id="id" value="<?php echo $dealer->id; ?>" >
                    <div class="form-group">
                        <label for="employee_id" class="col-md-4 control-label">Employee Name<span class="text-danger" id="dangerP">*</span></label>
                        <div class="col-md-7">
                            <?php
                            $data = array(
                                'name'          => 'employee_id',
                                'id'          	=> 'employee_id',
                                'class'			=> 'form-control select-chosen',
                                'disabled'      => true,
                            );
                            $options = array("" => "Please select",);
                            if ($getEmployees) {
                                foreach ($getEmployees as $employee) {
                                    $options[$employee->id] = ucwords($employee->first_name." ".$employee->last_name);
                                }
                            }
                            $select = $dealer->employee_id;
                            echo form_dropdown($data, $options, $select); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="dealer_category" class="col-md-4 control-label">Party Category<span class="text-danger" id="dangerP">*</span></label>
                        <div class="col-md-7">
                            <?php
                            $data = array(
                                'name'          => 'dealer_category',
                                'id'          => 'dealer_category',
                                'class'			=> 'form-control select-chosen',
                            );
                            $options = array("" => "Please select",);
                            if ($getDealerCategories) {
                                foreach ($getDealerCategories as $dealerCategories) {
                                    $options[$dealerCategories->id] = ucwords($dealerCategories->categoryName);
                                }
                            }
                            $select = $dealer->dealer_category;
                            echo form_dropdown($data, $options, $select); ?>
                        </div>
                    </div>
                    <input type="hidden" id="dealer_type" name="dealer_type" value="14">
                    <!--<div class="form-group">-->
                        <!--<label for="dealer_type" class="col-md-4 control-label">Party Type<span class="text-danger" id="dangerP">*</span></label>-->
                        <!--<div class="col-md-7">-->
                            <?php
                                // $data = array(
                                //     'name'          => 'dealer_type',
                                //     'id' 			=> 'dealer_type',
                                //     'class'			=> 'form-control select-chosen',
                                // );
                                // $options = array("" => "Please select",);
                                // if ($getDealerTypes) {
                                //     foreach ($getDealerTypes as $dealerTypes) {
                                //         $options[$dealerTypes->id] = ucwords($dealerTypes->typeName);
                                //     }
                                // }
                                // $select = $dealer->dealer_type;
                                // echo form_dropdown($data, $options, $select); 
                            ?>
                        <!--</div>-->
                    <!--</div>-->
                    <?php 
                        if(!empty($dealer->dealer_name))
                        {
                    ?>
                        <div class="form-group">
                            <label for="dealer_name" class="col-md-4 control-label">Name<span class="text-danger">*</span></label>
                            <div class="col-md-7">
                                <input type="text" placeholder="Partie name" class="form-control" name="dealer_name" id="dealer_name" value="<?php if(set_value('dealer_name')){ echo set_value('dealer_name'); }else{ echo $dealer->dealer_name; } ?>">
                                <?php if(form_error('dealer_name')){ ?>
                                    <div id="dealer_name-2-error" class="help-block animation-slideDown falseForm">
                                        <?php echo form_error('dealer_name'); ?>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="dealer_phone" class="col-md-4 control-label">Phone<span class="text-danger">*</span></label>
                            <div class="col-md-7">
                                <input type="text" placeholder="Partie phone number" class="form-control" name="dealer_phone" id="dealer_phone" value="<?php if(set_value('dealer_phone')){ echo set_value('dealer_phone'); }else{ echo $dealer->dealer_phone; } ?>">
                                <?php if(form_error('dealer_phone')){ ?>
                                    <div id="dealer_phone-2-error" class="help-block animation-slideDown falseForm">
                                        <?php echo form_error('dealer_phone'); ?>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    <?php
                        }else{
                            echo '<div id="exist_div">';
                            $count = count($owner_ids)-1;
                            foreach($owner_ids as $key => $value){
                    ?>
                        <div class='row' id="owner_contact_details">
                            <!--<div class='col-10'>-->
                                <?php
                                    if($key == 0){
                                        echo '<label for="dealer_name" class="col-md-4 control-label" style="margin-top:15px">Contact person details<span class="text-danger">*</span></label>';
                                    }
                                    // elseif($key == $count){
                                    //     echo '<div class="form-group col-md-4">
                                    //                 <a href="javascript: add_new_owner()" id="add-more" class="btn btn-sm btn-primary" style="float:right; margin-top:3px" data-toggle="tooltip" title="Add new"><i class="fa fa-plus"></i></a>
                                    //           </div>';
                                    // }
                                    else{
                                        echo '<div class="col-md-4"></div>';
                                    }
                                ?>
                                <div class="form-group col-md-4">
                                    <!--<div class="col-md-7">-->
                                        <input type="text" placeholder="Partie name" class="form-control" name="dealer_name[]" id="dealer_name" value="<?= $owner_names[$key] ?>">
                                        <?php if(form_error('dealer_name[]')){ ?>
                                            <div id="dealer_name-2-error" class="help-block animation-slideDown falseForm">
                                                <?php echo form_error('dealer_name[]'); ?>
                                            </div>
                                        <?php } ?>
                                    <!--</div>-->
                                </div>
                                <div class="form-group col-md-2">
                                    <!--<label for="dealer_phone" class="col-md-4 control-label">Phone<span class="text-danger">*</span></label>-->
                                    <!--<div class="col-md-7">-->
                                        <input type="hidden" id="owner_id" name="owner_id[]" value="<?= $value ?>">
                                        <input type="hidden" id="is_deleted" name="is_deleted[]" value="0">
                                        <input type="text" placeholder="Partie phone number" class="form-control" name="dealer_phone[]" id="dealer_phone" value="<?= $owner_phones[$key] ?>">
                                        <?php if(form_error('dealer_phone[]')){ ?>
                                            <div id="dealer_phone-2-error" class="help-block animation-slideDown falseForm">
                                                <?php echo form_error('dealer_phone[]'); ?>
                                            </div>
                                        <?php } ?>
                                    <!--</div>-->
                                </div>
                            <!--</div>-->
                            <div class='form-group col-md-1'>
                                <a href="javascript: delete_owner(<?php echo $value; ?>)" data-toggle="tooltip" title="Delete" class="btn btn-sm btn-danger" style="margin-top:3px"><i class="fa fa-times"></i></a>
                            </div>
                        </div>
                    <?php
                            }
                            echo '</div>';
                        }
                    ?>
                    
                    <div class="form-group">
                        <label for="firm_name" class="col-md-4 control-label">Company name<span class="text-danger">*</span></label>
                        <div class="col-md-7">
                            <input type="text" placeholder="Company name" class="form-control" name="firm_name" id="firm_name" value="<?php if(set_value('firm_name')){ echo set_value('firm_name'); }else{ echo $dealer->firm_name; } ?>">
                            <?php if(form_error('firm_name')){ ?>
                                <div id="firm_name-2-error" class="help-block animation-slideDown falseForm">
                                    <?php echo form_error('firm_name'); ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="address" class="col-md-4 control-label">Address<span class="text-danger">*</span></label>
                        <div class="col-md-7">
                            <input type="text" placeholder="Address" class="form-control" name="address" id="address" value="<?php if(set_value('address')){ echo set_value('address'); }else{ echo $dealer->address; } ?>">
                            <?php if(form_error('address')){ ?>
                                <div id="address-2-error" class="help-block animation-slideDown falseForm">
                                    <?php echo form_error('address'); ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="city_or_town" class="col-md-4 control-label">City/Town<span class="text-danger">*</span></label>
                        <div class="col-md-7">
                            <input type="text" placeholder="City/Town" class="form-control" name="city_or_town" id="city_or_town" value="<?php if(set_value('city_or_town')){ echo set_value('city_or_town'); }else{ echo $dealer->city_or_town; } ?>">
                            <?php if(form_error('city_or_town')){ ?>
                                <div id="city_or_town-2-error" class="help-block animation-slideDown falseForm">
                                    <?php echo form_error('city_or_town'); ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="gst_number" class="col-md-4 control-label">GST number<span class="text-danger">*</span></label>
                        <div class="col-md-7">
                            <input type="text" placeholder="GST number" class="form-control" name="gst_number" id="gst_number" value="<?php if(set_value('gst_number')){ echo set_value('gst_number'); }else{ echo $dealer->gst_number; } ?>">
                            <?php if(form_error('gst_number')){ ?>
                                <div id="gst_number-2-error" class="help-block animation-slideDown falseForm">
                                    <?php echo form_error('gst_number'); ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="dealer_aadhar" class="col-md-4 control-label">Partie Aadhar</label>
                        <div class="col-md-7">
                            <input type="text" placeholder="Partie aadhar" class="form-control" name="dealer_aadhar" id="dealer_aadhar" value="<?php if(set_value('dealer_aadhar')){ echo set_value('dealer_aadhar'); }else{ echo $dealer->dealer_aadhar; } ?>">
                            <?php if(form_error('dealer_aadhar')){ ?>
                                <div id="dealer_aadhar-2-error" class="help-block animation-slideDown falseForm">
                                    <?php echo form_error('dealer_aadhar'); ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="form-group form-actions">
                        <div class="col-md-9 col-md-offset-4">
                            <button class="btn btn-sm btn-primary" type="submit"><i class="fa fa-angle-right"></i> Update Partie</button>
                        </div>
                    </div>
                </form>
                <!-- END Basic Form Elements Content -->
            </div>
            <!-- END Basic Form Elements Block -->
        </div>
    </div>
    <!-- </div> -->
    <!-- END Page Content -->