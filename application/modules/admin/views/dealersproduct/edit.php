<!-- Page content -->
<?php //print_r($dealer_product_details); exit;?>
<div id="page-content">

    <div class="row">
        <div class="col-md-7 col-md-offset-2-5">
            <!-- Basic Form Elements Block -->
            <div class="block">
                <!-- Basic Form Elements Title -->
                <div class="block-title">
                    <div class="block-options pull-right">
                        <a title="" data-toggle="tooltip" class="btn btn-alt btn-sm btn-default" href="<?php echo BASE_URL.'admin/dealersproduct/product_list/'.base64_encode($dealer[0]->id); ?>" data-original-title="Close"><i class="fa fa-times"></i></a>
                    </div>
                    <h2><strong>Update Party Product Price</strong></h2>
                </div>
                <!-- END Form Elements Title -->

                <!-- Basic Form Elements Content -->
                <form id = "form-validation" class="form-horizontal form-bordered" enctype="multipart/form-data" method="post" action="<?php echo BASE_URL.'admin/dealersproduct/update'; ?>">
                    <input type="hidden" name="dealer_id" id="dealer_id" value="<?php echo $dealer[0]->id; ?>" >
                    <input type="hidden" name="product_id" id="product_id" value="<?php echo $product[0]->id; ?>" >
                    <div class="form-group">
                        <label for="dealer_id" class="col-md-4 control-label">Party Name<span class="text-danger" id="dangerP">*</span></label>
                        <div class="col-md-7">
                            <h5><?= $product[0]->name?></h5>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="product_id" class="col-md-4 control-label">Product Name<span class="text-danger" id="dangerP">*</span></label>
                        <div class="col-md-7">
                            <h5><?= $dealer[0]->firm_name?></h5>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="dealer_price" class="col-md-4 control-label">Price<span class="text-danger">*</span></label>
                        <div class="col-md-7">
                            <input type="number" placeholder="Price" class="form-control" name="dealer_price" id="dealer_price" value="<?php if($amount){echo $amount[0]->dealer_price;} ?>">
                            <?php if(form_error('dealer_price')){ ?>
                                <div id="dealer_price-2-error" class="help-block animation-slideDown falseForm">
                                    <?php echo form_error('dealer_price'); ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="form-group form-actions">
                        <div class="col-md-9 col-md-offset-4">
                            <button class="btn btn-sm btn-primary" type="submit"><i class="fa fa-angle-right"></i> Update Party Product Price</button>
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
