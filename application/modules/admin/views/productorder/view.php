<!-- Page content -->
<div id="page-content">
    <?php
    if ($this->session->flashdata('invalid_per') != '') { ?>
        <div class="alert text-center">
            <?php echo $this->session->flashdata('invalid_per'); ?>
        </div>
    <?php
    } else {
        //flash messages
        $msg = '';

        $style = 'display:none;';
        if ($this->session->flashdata('flash_message')) {
            if ($this->session->flashdata('flash_message') == 'inserted') {
                $msg = "<strong>Well done!</strong> user's detail inserted with success.";
                $style = 'display: block;';
            } else if ($this->session->flashdata('flash_message') == 'updated') {
                $msg = "<strong>Well done!</strong> user's detail updated with success.";
                $style = 'display: block;';
            } else if ($this->session->flashdata('flash_message') == 'Deleted') {
                $msg = "<strong>Well done!</strong> user's detail deleted with success.";
                $style = 'display: block;';
            } else {
                $msg = '<strong>Oh snap!</strong> Your Action is Not Perform.';
                $style = 'display: block;';
            }
        }
        $permisnMsg = '';
        $css = 'display: none;';
        if ($this->session->flashdata('permission') != '') {
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
                        <h2><strong>Products Order Information</strong></h2>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <!-- Basic Form Elements Block -->
                            <div class="block">
                                <!-- Basic Form Elements Title -->
                                <div class="block-title">
                                    <h2><strong>Product Order</strong></h2>
                                </div>
                                <!-- END Form Elements Title -->


                                <?php
                                if ($product_details) {
                                    $total_nos = $total_price = $total_weight = 0;
                                    for ($i = 0; $i < count($product_details); $i++) {
                                        $unit_price = (!empty($product_details[$i]->dealer_price)) ? $product_details[$i]->dealer_price : $product_details[$i]->product_mrp;

                                        $total_nos += (float)$product_details[$i]->nos;
                                        $total_price += (float)$product_details[$i]->nos * (float)$unit_price;
                                        $total_weight += (float)$product_details[$i]->nos * (float)$product_details[$i]->product_ah;
                                ?>

                                    <?php } ?>
                                <?php } ?>


                                <table class="table table-vcenter table-condensed table-bordered">
                                    <tbody>
                                        <tr>
                                            <td>PO Number</td>
                                            <td><?= $product_order_details[0]->po_number; ?></td>
                                        </tr>
                                        <tr>
                                            <td>Company Name</td>
                                            <td><?= $product_order_details[0]->firm_name; ?></td>
                                        </tr>
                                        <tr>
                                            <td>Employee Name</td>
                                            <td><?= $product_order_details[0]->employee_name; ?></td>
                                        </tr>
                                        <tr>
                                            <td>Dispatch Date</td>
                                            <td><?= $product_order_details[0]->dispatch_date; ?></td>
                                        </tr>
                                        <tr>
                                            <td>Total NOS</td>
                                            <td><?= $total_nos; ?></td>
                                        </tr>
                                        <tr>
                                            <td>Total Price</td>
                                            <td><?= $total_price; ?></td>
                                        </tr>
                                        <tr>
                                            <td>Total Weight</td>
                                            <td><?= $total_weight; ?></td>
                                        </tr>
                                        <tr>
                                            <td>Admin Dispatch Date</td>
                                            <td><?= $product_order_details[0]->admin_dispatch_date; ?></td>
                                        </tr>
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
        <div class="block full">
            <div class="block-title">
                <h2><strong>Dispatch Date</strong></h2>
            </div>
            <div class="row">
                <form id="form-validation" class="form-horizontal form-bordered" enctype="multipart/form-data" method="post" action="<?php echo BASE_URL . 'admin/productorder/addDisptachDate'; ?>">
                    <div class="form-group">
                        <div class="form-group">
                            <label for="admin_dispatch_date" class="col-md-4 control-label">Dispatch Date<span class="text-danger">*</span></label>
                            <div class="col-md-7">
                                <div class='input-group date' id='datetimepicker1'>
                                    <input type='text' class="form-control" placeholder="Dispatch Date" class="form-control" name="admin_dispatch_date" id="admin_dispatch_date" />
                                    <!-- data-date-format="dd/mm/yyyy" -->
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                                <?php if (form_error('admin_dispatch_date')) { ?>
                                    <div id="admin_dispatch_date-2-error" class="help-block animation-slideDown falseForm">
                                        <?php echo form_error('admin_dispatch_date'); ?>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <input type='text' class="form-contro d-none" name="id" id="id" value="<?= base64_encode($product_order_details[0]->id) ?>" hidden />

                    <!-- <div class="form-group form-actions"> -->
                    <div class="col-md-9 col-md-offset-4">
                        <button class="btn btn-sm btn-primary" type="submit"><i class="fa fa-angle-right"></i> Add Dispatch Date</button>
                    </div>
                    <!-- </div> -->
                </form>
            </div>
        </div>

        <!-- Datatables Content -->
        <div class="block full">
            <div class="block-title">
                <h2><strong>Product Details</strong></h2>
            </div>
            <div class="row">

                <div class="table-responsive">
                    <table id="example-datatable" class="table table-vcenter table-condensed table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center">Product Category Name</th>
                                <th class="text-center">Product Name</th>
                                <th class="text-center">Product Item Code</th>
                                <th class="text-center">Product Unit</th>
                                <!--                        <th class="text-center">Product AH</th>-->
                                <!--                        <th class="text-center">Product MRP</th>-->
                                <th class="text-center">NOS</th>
                                <!--                        <th class="text-center">Rate</th>-->
                                <th class="text-center">Unit Price</th>
                                <th class="text-center">Total Price</th>
                                <th class="text-center">Weight</th>
                                <th class="text-center">Total Weight</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            if ($product_details) {
                                for ($i = 0; $i < count($product_details); $i++) {
                                    $unit_price = (!empty($product_details[$i]->dealer_price)) ? $product_details[$i]->dealer_price : $product_details[$i]->product_mrp;
                                    $total_price = (float)$product_details[$i]->nos * (float)$unit_price;
                                    $total_weight = (float)$product_details[$i]->nos * (float)$product_details[$i]->product_ah;
                            ?>
                                    <tr>
                                        <td class="text-center"><?php echo (!empty($product_details[$i]->product_catgeory_name)) ? $product_details[$i]->product_catgeory_name : '-'; ?></td>
                                        <td class="text-center"><?php echo (!empty($product_details[$i]->product_name)) ? $product_details[$i]->product_name : '-'; ?></td>
                                        <td class="text-center"><?php echo (!empty($product_details[$i]->product_item_code)) ? $product_details[$i]->product_item_code : '-'; ?></td>
                                        <td class="text-center"><?php echo (!empty($product_details[$i]->product_unit)) ? $product_details[$i]->product_unit : '-'; ?></td>
                                        <!--                                <td class="text-center">--><?php //echo (!empty($product_details[$i]->product_ah)) ? $product_details[$i]->product_ah : '-'; 
                                                                                                        ?><!--</td>-->
                                        <!--                                <td class="text-center">--><?php //echo (!empty($product_details[$i]->product_mrp)) ? $product_details[$i]->product_mrp : '-'; 
                                                                                                        ?><!--</td>-->
                                        <td class="text-center"><?php echo (!empty($product_details[$i]->nos)) ? $product_details[$i]->nos : '-'; ?></td>
                                        <!--                                <td class="text-center">--><?php //echo (!empty($product_details[$i]->rate)) ? $product_details[$i]->rate : '-'; 
                                                                                                        ?><!--</td>-->
                                        <td class="text-center"><?php echo $unit_price ?></td>
                                        <td class="text-center"><?php echo $total_price ?></td>
                                        <td class="text-center"><?php echo (!empty($product_details[$i]->weight)) ? $product_details[$i]->weight : '-'; ?></td>
                                        <td class="text-center"><?php echo (!empty($total_weight)) ? $total_weight : '-'; ?></td>
                                    </tr>
                                <?php } ?>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- END Datatables Content -->
    <?php } ?>
    <!-- END Page Content -->
    <?php
    $this->session->set_flashdata('permission', '');
    $this->session->set_flashdata('invalid_per', '');
    ?>