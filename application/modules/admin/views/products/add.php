<?php
?>

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
                    <h2><strong>Add Products</strong></h2>
                </div>
                <!-- END Form Elements Title -->

                <!-- Basic Form Elements Content -->
                <form id="form-validation" class="form-horizontal form-bordered" enctype="multipart/form-data" method="post" action="<?php echo BASE_URL . 'admin/products/insert'; ?>">
                    <div class="form-group">
                        <label for="employee_id" class="col-md-4 control-label">Category<span class="text-danger" id="dangerP">*</span></label>
                        <div class="col-md-7">
                            <?php

                            $data = array(
                                'name'          => 'category_id',
                                'id'              => 'category_id',
                                'class'            => 'form-control select-chosen',
                            );
                            $options = array("" => "Please select",);
                            if ($category) {
                                foreach ($category as $list) {
                                    $options[$list->id] = ucwords($list->name);
                                }
                            }
                            $select = "";
                            echo form_dropdown($data, $options, $select);

                            if (form_error('category_id')) { ?>
                                <div id="name-2-error" class="help-block animation-slideDown falseForm">
                                    <?php echo form_error('category_id'); ?>
                                </div>
                            <?php } ?>

                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-md-4 control-label">Name<span class="text-danger">*</span></label>
                        <div class="col-md-7">
                            <input type="text" placeholder="Product name" class="form-control" name="name" id="name" value="<?php echo set_value('name'); ?>">
                            <?php if (form_error('name')) { ?>
                                <div id="name-2-error" class="help-block animation-slideDown falseForm">
                                    <?php echo form_error('name'); ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="unit" class="col-md-4 control-label">Unit<span class="text-danger">*</span></label>
                        <div class="col-md-7">
                            <input type="text" placeholder="Unit" class="form-control" name="unit" id="unit" value="<?php echo set_value('unit'); ?>">
                            <?php if (form_error('unit')) { ?>
                                <div id="unit-2-error" class="help-block animation-slideDown falseForm">
                                    <?php echo form_error('unit'); ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="ah" class="col-md-4 control-label">AH<span class="text-danger">*</span></label>
                        <div class="col-md-7">
                            <input type="text" placeholder="AH" class="form-control" name="ah" id="ah" value="<?php echo set_value('ah'); ?>">
                            <?php if (form_error('ah')) { ?>
                                <div id="ah-2-error" class="help-block animation-slideDown falseForm">
                                    <?php echo form_error('ah'); ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="mrp" class="col-md-4 control-label">MRP<span class="text-danger">*</span></label>
                        <div class="col-md-7">
                            <input type="text" placeholder="MRP" class="form-control" name="mrp" id="mrp" value="<?php echo set_value('mrp'); ?>">
                            <?php if (form_error('mrp')) { ?>
                                <div id="mrp-2-error" class="help-block animation-slideDown falseForm">
                                    <?php echo form_error('mrp'); ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="default_dealer_price" class="col-md-4 control-label">Default Dealer Price<span class="text-danger">*</span></label>
                        <div class="col-md-7">
                            <input type="text" placeholder="Dealer Dealer Price" class="form-control" name="default_dealer_price" id="default_dealer_price" value="<?php echo set_value('default_dealer_price'); ?>">
                            <?php if (form_error('default_dealer_price')) { ?>
                                <div id="mrp-2-error" class="help-block animation-slideDown falseForm">
                                    <?php echo form_error('default_dealer_price'); ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="sort_order" class="col-md-4 control-label">Sort<span class="text-danger">*</span></label>
                        <div class="col-md-7">
                            <input type="number" placeholder="Sort" class="form-control" name="sort_order" id="sort_order" value="<?php echo set_value('sort_order'); ?>" min="0">
                            <?php if (form_error('sort_order')) { ?>
                                <div id="mrp-2-error" class="help-block animation-slideDown falseForm">
                                    <?php echo form_error('sort_order'); ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="form-group form-actions">
                        <div class="col-md-9 col-md-offset-4">
                            <button class="btn btn-sm btn-primary" type="submit"><i class="fa fa-angle-right"></i> Add Product</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>