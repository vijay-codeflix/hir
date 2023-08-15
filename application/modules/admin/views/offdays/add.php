<!-- Page content -->
<div id="page-content">
	<div class="row">
	        <div class="col-md-7 col-md-offset-2-5">
	            <!-- Basic Form Elements Block -->
	            <div class="block">
	                <!-- Basic Form Elements Title -->
	                <div class="block-title">
	                    <div class="block-options pull-right">
	                        <a title="" data-toggle="tooltip" class="btn btn-alt btn-sm btn-default" href="<?php echo BASE_URL.'admin/offdays'; ?>" data-original-title="Close"><i class="fa fa-times"></i></a>
	                    </div>
	                    <h2><strong>Add Offdays</strong></h2>
	                </div>
	                <!-- END Form Elements Title -->

	                <!-- Basic Form Elements Content -->
	                <form id = "form-validation" class="form-horizontal form-bordered" enctype="multipart/form-data" method="post" action="<?php echo BASE_URL.'admin/offdays/insert'; ?>">
                        <div class="form-group">
	                        <label for="title" class="col-md-4 control-label">Offdays Title<span class="text-danger">*</span></label>
	                        <div class="col-md-7">
	                            <input type="text" class="form-control" name="title" placeholder="Sunday/Monday/Festival/etc" id="title" value="<?php if(set_value('title')){ echo set_value('title'); } ?>">
                                <?php if(form_error('title')){ ?>
			                        <div id="title-2-error" class="help-block animation-slideDown falseForm">
		                            	<?php echo form_error('title'); ?>
		                            </div>
	                            <?php } ?>
	                        </div>
	                    </div>
	                    <div class="form-group">
	                        <label class="col-md-4 control-label" for="date">Offdays Date<span class="text-danger">*</span></label>
	                        <div class="col-md-7">
	                           <!--  <div class="input-group input-date" data-date-format="dd/mm/yyyy" data-provide="datepicker">
	                                <input type="text" id="date" name="date" class="form-control text-center" placeholder="From">
	                            </div> -->
	                            <div class="input-group date" data-provide="datepicker" data-date-format="yyyy-mm-dd">
								    <input type="text" id="date" name="date" class="form-control">
								    <div class="input-group-addon">
								        <span class="glyphicon glyphicon-th"></span>
								    </div>
								</div>
	                        </div>
	                    </div>
                  		<div class="form-group">
	                        <label for="type" class="col-md-4 control-label">Offdays Type<span class="text-danger">*</span></label>
	                        <div class="col-md-7">
	                            <input type="radio" name="type" id="type" value="1">Weekly
	                            <input type="radio" name="type" id="type" value="2">Monthly
	                            <input type="radio" name="type" id="type" value="3">Yearly
	                            <input type="radio" name="type" id="type" value="4">Once
	                            <?php if(form_error('type')){ ?>
			                        <div id="type-2-error" class="help-block animation-slideDown falseForm">
		                            	<?php echo form_error('type'); ?>
		                            </div>
	                            <?php } ?>
	                        </div>
	                    </div>

	                    <div class="form-group form-actions">
	                        <div class="col-md-9 col-md-offset-4">
	                            <button class="btn btn-sm btn-primary" type="submit"><i class="fa fa-angle-right"></i> Add offdays</button>
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
<script type="text/javascript">
	$('.datepicker').datepicker({
	    format: 'mm/dd/yyyy',
	    startDate: '-3d'
	});
</script>