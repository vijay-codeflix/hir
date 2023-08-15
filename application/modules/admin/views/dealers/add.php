<head>
    <script type="text/javascript"src="https://ajax.googleapis.com/ajax/libs/jquery/1.3/jquery.min.js">    </script>
</head>

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
                    <h2><strong>Add Partie</strong></h2>
                </div>
                <!-- END Form Elements Title -->

                <!-- Basic Form Elements Content -->
                <form id = "form-validation" class="form-horizontal form-bordered" enctype="multipart/form-data" method="post" action="<?php echo BASE_URL.'admin/dealers/insert'; ?>">
                    <div class="form-group">
                        <label for="employee_id" class="col-md-4 control-label">Employee Name<span class="text-danger" id="dangerP">*</span></label>
                        <div class="col-md-7">
                            <?php
                            $data = array(
                                'name'          => 'employee_id',
                                'id'          	=> 'employee_id',
                                'class'			=> 'form-control select-chosen',
                            );
                            $options = array("" => "Please select",);
                            if ($getEmployees) {
                                foreach ($getEmployees as $employee) {
                                    $options[$employee->id] = ucwords($employee->first_name." ".$employee->last_name);
                                }
                            }
                            $select = "";
                            echo form_dropdown($data, $options, $select); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="dealer_category" class="col-md-4 control-label">Party Category<span class="text-danger" id="dangerP">*</span></label>
                        <div class="col-md-7">
                            <?php
                            $data = array(
                                'name'          => 'dealer_category',
                                'id'			=> 'dealer_category',
                                'class'			=> 'form-control select-chosen',
                            );
                            $options = array("" => "Please select",);
                            if ($getDealerCategories) {
                                foreach ($getDealerCategories as $dealerCategories) {
                                    $options[$dealerCategories->id] = ucwords($dealerCategories->categoryName);
                                }
                            }
                            $select = "";
                            echo form_dropdown($data, $options, $select); ?>
                        </div>
                    </div>
                    <input type="hidden" id="dealer_type" name="dealer_type" value="14">
                    <!--<div class="form-group">-->
                        <!--<label for="dealer_type" class="col-md-4 control-label">Party Type<span class="text-danger" id="dangerP">*</span></label>-->
                        <!--<div class="col-md-7">-->
                            <?php
                            // $data = array(
                                // 'name'          => 'dealer_type',
                                // 'id'			=> 'dealer_type',
                                // 'class'			=> 'form-control select-chosen',
                            // );
                            // $options = array("" => "Please select",);
                            // if ($getDealerTypes) {
                                // foreach ($getDealerTypes as $dealerTypes) {
                                    // $options[$dealerTypes->id] = ucwords($dealerTypes->typeName);
                                // }
                            // }
                            // $select = "";
                            // echo form_dropdown($data, $options, $select); 
                            ?>
                        <!--</div>-->
                    <!--</div>-->
                    <div id="exist_div">
                        <div class="form-group">
                            <label for="dealer_name" class="col-md-4 control-label">Name<span class="text-danger">*</span></label>
                            <div class="col-md-7">
                                <input type="text" placeholder="Partie name" class="form-control" name="dealer_name[]" id="dealer_name" value="<?php  //echo set_value('dealer_name'); ?>">
                                <?php if(form_error('dealer_name[]')){ ?>
                                    <div id="dealer_name-2-error" class="help-block animation-slideDown falseForm">
                                        <?php echo form_error('dealer_name[]'); ?>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="dealer_phone" class="col-md-4 control-label">Phone<span class="text-danger">*</span></label>
                            <div class="col-md-7">
                                <input type="text" placeholder="Partie phone number" class="form-control" name="dealer_phone[]" id="dealer_phone" value="<?php //echo set_value('dealer_phone'); ?>">
                                <?php if(form_error('dealer_phone[]')){ ?>
                                    <div id="dealer_phone-2-error" class="help-block animation-slideDown falseForm">
                                        <?php echo form_error('dealer_phone[]'); ?>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <!---->
                    <!--                    <div id="new_div">-->
                    <!---->
                    <!--                    </div>-->
                    <div class="form-group aligh-right">
                        <div class="col-md-9 col-md-offset-4">
                            <a id='add-more' class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Add Name And Phone</a>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="firm_name" class="col-md-4 control-label">Company name<span class="text-danger">*</span></label>
                        <div class="col-md-7">
                            <input type="text" placeholder="Company name" class="form-control" name="firm_name" id="firm_name" value="<?php echo set_value('firm_name'); ?>">
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
                            <input type="text" placeholder="Address" class="form-control" name="address" id="address" value="<?php echo set_value('address'); ?>">
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
                            <input type="text" placeholder="City/Town" class="form-control" name="city_or_town" id="city_or_town" value="<?php echo set_value('city_or_town'); ?>">
                            <?php if(form_error('city_or_town')){ ?>
                                <div id="city_or_town-2-error" class="help-block animation-slideDown falseForm">
                                    <?php echo form_error('city_or_town'); ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="gst_number" class="col-md-4 control-label">GST number
                        <!--<span class="text-danger">*</span>-->
                        </label>
                        <div class="col-md-7">
                            <input type="text" placeholder="GST number" class="form-control" name="gst_number" id="gst_number" value="<?php echo set_value('gst_number'); ?>">
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
                            <input type="text" placeholder="Partie aadhar" class="form-control" name="dealer_aadhar" id="dealer_aadhar" value="<?php echo set_value('dealer_aadhar'); ?>">
                            <?php if(form_error('dealer_aadhar')){ ?>
                                <div id="dealer_aadhar-2-error" class="help-block animation-slideDown falseForm">
                                    <?php echo form_error('dealer_aadhar'); ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="form-group form-actions">
                        <div class="col-md-9 col-md-offset-4">
                            <button class="btn btn-sm btn-primary" type="submit"><i class="fa fa-angle-right"></i> Add Partie</button>
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

    <script>
        $( document ).ready(function() {
            var num = 0;
            $('#add-more').on('click',function(){
                var newelement= $("#exist_div").eq(0).clone();
                num = num + 1;
                var newNum = num + 1;

                newelement.find('input').each(function(i){
                    $(this).attr('name',$(this).attr('name')+newNum);
                });
                $('#exist_div').last().after(newelement);
                // $($(this).attr('name')+newNum).val('');
                // console.log($(this).attr('name')+newNum);
            });
        });
    </script>
