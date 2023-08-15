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
                        <a title="" data-toggle="tooltip" class="btn btn-alt btn-sm btn-default" href="<?php echo BASE_URL . 'admin/complains/view'; ?>" data-original-title="Close"><i class="fa fa-times"></i></a>
                    </div>
                    <h2><strong>Complaint Action</strong></h2>
                </div>
                <!-- END Form Elements Title -->

                <!-- Basic Form Elements Content -->
                <form id="form-validation" class="form-horizontal form-bordered" enctype="multipart/form-data" method="post" action="<?php echo BASE_URL . 'admin/complains/insert_action/'. $complaint_data['id']; ?>">

                    <div class="form-group">
                        <label for="status_id" class="col-md-4 control-label">Status<span class="text-danger" id="dangerP">*</span></label>
                        <div class="col-md-7">
                            <?php

                            $data = array(
                                'name' => 'status_id',
                                'id' => 'status_id',
                                'class' => 'form-control select-chosen',
                            );
                            $options = array("" => "Please select",);
                            if ($status_types) {
                                foreach ($status_types as $list) {
                                    $options[$list->id] = ucwords($list->name);
                                }
                            }
                            $select = "";
                            echo form_dropdown($data, $options, $select);

                            if (form_error('status_id')) { ?>
                                <div id="name-2-error" class="help-block animation-slideDown falseForm">
                                    <?php echo form_error('status_id'); ?>
                                </div>
                            <?php } ?>

                        </div>
                    </div>

                    <div class="form-group">
                        <label for="admin_remark" class="col-md-4 control-label">Remark<span class="text-danger">*</span></label>
                        <div class="col-md-7">
                            <input type="text" placeholder="Remark" class="form-control" name="admin_remark" id="admin_remark" value="<?php echo set_value('admin_remark'); ?>">
                            <?php if (form_error('admin_remark')) { ?>
                                <div id="remark-2-error" class="help-block animation-slideDown falseForm">
                                    <?php echo form_error('admin_remark'); ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="form-group form-actions">
                        <div class="col-md-9 col-md-offset-4">
                            <button class="btn btn-sm btn-primary" type="submit"><i class="fa fa-angle-right"></i> Submit</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>