<?php
?>

<style>
    .select2-container {
        display: none !important;
    }
</style>
<!-- Page content -->
<div id="page-content">
    <div class="row">
        <div class="col-md-7 col-md-offset-2-5">
            <!-- Basic Form Elements Block -->
            <div class="block">
                <!-- Basic Form Elements Title -->
                <div class="block-title">
                    <div class="block-options pull-right">
                        <a title="" data-toggle="tooltip" class="btn btn-alt btn-sm btn-default" href="<?php echo BASE_URL . 'admin/products/view'; ?>" data-original-title="Close"><i class="fa fa-times"></i></a>
                    </div>
                    <h2><strong>Add Follow Up</strong></h2>
                </div>
                <!-- END Form Elements Title -->

                <!-- Basic Form Elements Content -->
                <form id="form-validation" class="form-horizontal form-bordered" enctype="multipart/form-data" method="post" action="<?php echo BASE_URL . 'admin/followup/insert'; ?>">
                    <div class="form-group">
                        <label for="employee_id" class="col-md-4 control-label">Employee<span class="text-danger" id="dangerP">*</span></label>
                        <div class="col-md-7">
                            <?php

                            $data = array(
                                'name'          => 'employee_id',
                                'id'              => 'employee_id',
                                'class'            => 'form-control select-chosen',
                            );
                            $options = array("" => "Please select",);
                            if ($users) {
                                foreach ($users as $list) {
                                    $options[$list->id] = ucwords($list->first_name) . ' ' . ucwords($list->last_name);
                                }
                            }
                            $select = "";
                            echo form_dropdown($data, $options, $select, "onchange=javascript:dealers('" . BASE_URL . "',this)");

                            if (form_error('employee_id')) { ?>
                                <div id="name-2-error" class="help-block animation-slideDown falseForm">
                                    <?php echo form_error('employee_id'); ?>
                                </div>
                            <?php } ?>

                        </div>
                    </div>
                    <div class="form-group">
                        <label for="user_type" class="col-md-4 control-label">Submit Date<span class="text-danger">*</span></label>
                        <div class="col-md-7">
                            <div class='input-group date' id='datetimepicker2'>
                                <input type='text' class="form-control" placeholder="submit_date" class="form-control" name="submit_date" id="submit_date" value="<?= date('d/m/Y') ?>" readonly />
                                <!-- data-date-format="dd/mm/yyyy" -->
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                            <?php if (form_error('submit_date')) { ?>
                                <div id="date-2-error" class="help-block animation-slideDown falseForm">
                                    <?php echo form_error('submit_date'); ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="party_id" class="col-md-4 control-label">Party<span class="text-danger" id="dangerP">*</span></label>
                        <div class="col-md-7">
                            <?php

                            $data = array(
                                'name'          => 'party_id',
                                'id'              => 'party_id',
                                'class'            => 'form-control select-chosen',
                            );
                            $options = array("" => "Please select",);
                            if ($party) {
                                foreach ($party as $list) {
                                    $options[$list->id] = ucwords($list->firm_name);
                                }
                            }
                            $select = "";
                            echo form_dropdown($data, $options, $select);

                            if (form_error('party_id')) { ?>
                                <div id="name-2-error" class="help-block animation-slideDown falseForm">
                                    <?php echo form_error('party_id'); ?>
                                </div>
                            <?php } ?>

                        </div>
                    </div>
                    <div class="form-group">
                        <label for="follow_up_type_id" class="col-md-4 control-label">Follow up Type<span class="text-danger" id="dangerP">*</span></label>
                        <div class="col-md-7">
                            <?php

                            $data = array(
                                'name'          => 'follow_up_type_id',
                                'id'              => 'follow_up_type_id',
                                'class'            => 'form-control select-chosen',
                            );
                            $options = array("" => "Please select",);
                            if ($types) {
                                foreach ($types as $list) {
                                    $options[$list['id']] = ucwords($list['name']);
                                }
                            }
                            $select = "";
                            echo form_dropdown($data, $options, $select);

                            if (form_error('follow_up_type_id')) { ?>
                                <div id="name-2-error" class="help-block animation-slideDown falseForm">
                                    <?php echo form_error('follow_up_type_id'); ?>
                                </div>
                            <?php } ?>

                        </div>
                    </div>
                    <div class="form-group">
                        <label for="user_type" class="col-md-4 control-label">Follow In Date<span class="text-danger">*</span></label>
                        <div class="col-md-7">
                            <div class='input-group date' id='datetimepicker1'>
                                <input type='text' class="form-control" placeholder="Follow Up Date" class="form-control" name="follow_up_date" id="follow_up_date" />
                                <!-- data-date-format="dd/mm/yyyy" -->
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                            <?php if (form_error('follow_up_date')) { ?>
                                <div id="follow_up_date-2-error" class="help-block animation-slideDown falseForm">
                                    <?php echo form_error('follow_up_date'); ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="remark" class="col-md-4 control-label">Remark<span class="text-danger">*</span></label>
                        <div class="col-md-7">
                            <input type="text" placeholder="Remark" class="form-control" name="remark" id="remark" value="<?php echo set_value('remark'); ?>">
                            <?php if (form_error('remark')) { ?>
                                <div id="remark-2-error" class="help-block animation-slideDown falseForm">
                                    <?php echo form_error('remark'); ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="form-group form-actions">
                        <div class="col-md-9 col-md-offset-4">
                            <button class="btn btn-sm btn-primary" type="submit"><i class="fa fa-angle-right"></i> Add Follow Up</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>