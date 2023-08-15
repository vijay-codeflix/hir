<style>
	.select2-container {
		display: none !important;
	}

	select {

		display: block !important;
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
						<a title="" data-toggle="tooltip" class="btn btn-alt btn-sm btn-default" href="<?php echo BASE_URL . 'admin/expenses'; ?>" data-original-title="Close"><i class="fa fa-times"></i></a>
					</div>
					<h2><strong>Add Inquery</strong></h2>
				</div>
				<!-- END Form Elements Title -->

				<!-- Basic Form Elements Content -->
				<form id="form-validation" class="form-horizontal form-bordered" enctype="multipart/form-data" method="post" action="<?php echo BASE_URL . 'admin/enquires/insert'; ?>">
					<div class="form-group">
						<label for="enquiry_no" class="col-md-4 control-label">Inquiry no.<span class="text-danger">*</span></label>
						<div class="col-md-7">
							<input readonly type="text" class="form-control" name="enquiry_no" id="enquiry_no" value="<?php if (set_value('enquiry_no')) {
																															echo set_value('enquiry_no');
																														} else {
																															echo $enquiry_no;
																														} ?>">
							<?php if (form_error('enquiry_no')) { ?>
								<div id="enquiry_no-2-error" class="help-block animation-slideDown falseForm">
									<?php echo form_error('enquiry_no'); ?>
								</div>
							<?php } ?>
						</div>
					</div>
					<!--   <div class="form-group">-->
					<!--    <label for="name" class="col-md-4 control-label">Category<span class="text-danger">*</span></label>-->
					<!--    <div class="col-md-7">-->
					<!--        <input type="text" placeholder="Category name" class="form-control" name="name" id="name" value="<?php //if(set_value('name')){ echo set_value('name'); } 
																																	?>">-->
					<!--           <?php //if(form_error('name')){ 
									?>-->
					<!--      <div id="name-2-error" class="help-block animation-slideDown falseForm">-->
					<!--         	<?php //echo form_error('name'); 
										?>-->
					<!--         </div>-->
					<!--        <?php //} 
								?>-->
					<!--    </div>-->
					<!--</div>-->
					<div class="form-group">
						<label for="party_category_id" class="col-md-4 control-label">Party Category<span class="text-danger">*</span></label>
						<div class="col-md-7">
							<select size="1" class="form-control" name="party_category_id" id="party_category_id">
								<option value="">Please select</option>
								<?php
								foreach ($party_category as $row) {
									echo '<option value="' . $row->id . '">' . $row->name . '</option>';
								}
								?>
							</select>
							<?php if (form_error('party_category_id')) { ?>
								<div id="party_category_id-2-error" class="help-block animation-slideDown falseForm">
									<?php echo form_error('party_category_id'); ?>
								</div>
							<?php } ?>
						</div>
					</div>

					<div class="form-group">
						<label for="party_id" class="col-md-4 control-label">Party Name<span class="text-danger"></span></label>
						<div class="col-md-7">
							<select size="1" class="form-control" name="party_id" id="party_id">
								<option value="">Please select</option>
							</select>
							<?php if (form_error('party_id')) { ?>
								<div id="party_id-2-error" class="help-block animation-slideDown falseForm">
									<?php echo form_error('party_id'); ?>
								</div>
							<?php } ?>
						</div>
					</div>
					<div class="form-group">
						<label for="external_party" class="col-md-4 control-label">External Party<span class="text-danger" id="dangerP"></span></label>
						<div class="col-md-7">
							<input type="text" placeholder="External Party" class="form-control" name="party_name" id="external_party" value="<?php echo set_value('remark'); ?>">
						</div>
					</div>

					<div id="clone">
						<div class="row">
							<div class="col-md-12">
								<div class="block">
									<div class="block-title">
										<div class="block-options pull-right">
											<button type="button" class="btn btn-primary" id="clone-product">Add</button>
										</div>
										<h2><strong>Product</strong></h2>
									</div>
									<div class="form-group">
										<label class="col-md-3 control-label" for="example-hf-email">Product Category</label>
										<div class="col-md-9">
											<select size="1" class="form-control " name="product[0][product_category_id]" id="product_category_id0" onchange="product(this,'product_id0');">
												<option value="">Please select</option>
												<?php
												foreach ($product_category as $row) {
													echo '<option value="' . $row->id . '">' . $row->name . '</option>';
												}
												?>
											</select>
											<?php if (form_error('product[0][product_category_id]')) { ?>
												<div id="product_category_id0-2-error" class="help-block animation-slideDown falseForm">
													<?php echo form_error('product[0][product_category_id]'); ?>
												</div>
											<?php } ?>
										</div>
									</div>
									<div class="form-group">
										<label class="col-md-3 control-label" for="example-hf-password">Product</label>
										<div class="col-md-9">
											<select size="1" class="form-control" name="product[0][product_id]" id="product_id0">
												<option value="">Please select</option>
											</select>
											<?php if (form_error('product[0][product_id]')) { ?>
												<div id="product_id0-2-error" class="help-block animation-slideDown falseForm">
													<?php echo form_error('product[0][product_id]'); ?>
												</div>
											<?php } ?>
										</div>
									</div>


								</div>
							</div>
						</div>

					</div>

					<div class="form-group form-actions">
						<div class="col-md-9 col-md-offset-4">
							<button class="btn btn-sm btn-primary" type="submit"><i class="fa fa-angle-right"></i> Add category</button>
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