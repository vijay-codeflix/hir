<!-- Page content -->
<div id="page-content">
	<div class="row">
	        <div class="col-md-7 col-md-offset-2-5">
	            <!-- Basic Form Elements Block -->
	            <div class="block">
	                <!-- Basic Form Elements Title -->
	                <div class="block-title">
	                    <div class="block-options pull-right">
	                        <a title="" data-toggle="tooltip" class="btn btn-alt btn-sm btn-default" href="<?php echo BASE_URL.'admin/users/employeeGrade'; ?>" data-original-title="Close"><i class="fa fa-times"></i></a>
	                    </div>
	                    <h2><?php if($type == 'view' ){ echo "View"; }else{ echo "Edit"; }?> <strong>Employee Grade</strong></h2>
	                </div>
	                <!-- END Form Elements Title -->

	                <!-- Basic Form Elements Content -->
                    <form id = "userAddForm" class="form-horizontal form-bordered" enctype="multipart/form-data" method="post" action="<?php echo BASE_URL.'admin/users/updateEmployeeGrade'; ?>">
	                	<input type="hidden" name="id" id="id" value="<?php echo $gradeView[0]->gid; ?>" >
                        <?php 
                            if($cityGrades){ ?>
                                <fieldset>
                                    <legend><i class="fa fa-angle-right"></i> Grade Details<strong></strong></legend>
                                    <div class="form-group">
                                        <label for="grade" class="col-md-4 control-label">Grade name<span class="text-danger">*</span></label>
                                        <div class="col-md-7">
                                            <input <?php if($type == 'view'){ echo "disabled"; } ?> type="text" placeholder="Grade name" class="form-control" name="grade" id="grade" value="<?php if(set_value('grade')){ echo set_value('grade'); }else{ echo $gradeView[0]->grade; } ?>">
                                            <?php if(form_error('grade')){ ?>
                                                <div id="grade-2-error" class="help-block animation-slideDown falseForm">
                                                    <?php echo form_error('grade'); ?>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </fieldset>
                                <?php foreach ($cityGrades as $key => $value) { ?>    
                                    <fieldset>
                                        <legend><i class="fa fa-angle-right"></i> City Grade - <strong><?php echo $value; ?></strong></legend>
                                        <?php if($categories){
                                            foreach ($categories as $key1 => $value1) {
                                        ?>
                                        <div class="form-group">
                                            <label for="user_type" class="col-md-4 control-label"><?php echo ucwords($value1->name); ?><span class="text-danger">*</span></label>
                                            <div class="col-md-7">
                                                <?php if($gradeView[0]->grade_id == ''){ ?>
                                                    <input type="text" placeholder="Category amount" <?php if($type == 'view'){ echo "disabled"; } ?> class="form-control" name="<?php echo $value.'_99_'.$value1->id; ?>" id="<?php echo $value.'_99_'.$value1->id; ?>" value="">
                                                <?php
                                                    }else{
                                                        $isMatched = false;
                                                        foreach ($gradeView as $key2 => $value2) {
                                                            if($value2->city_grade == $value && $value2->category_id == $value1->id){
                                                                $isMatched = true;
                                                                ?>
                                                                <input type="text" <?php if($type == 'view'){ echo "disabled"; } ?> placeholder="Category amount" class="form-control" name="<?php echo $value.'_99_'.$value1->id.'_99_'.$value2->id; ?>" id="<?php echo $value.'_99_'.$value1->id; ?>" value="<?php echo $value2->amount; ?>">
                                                                <?php
                                                            }
                                                        }

                                                        if(!$isMatched){ ?>
                                                            <input type="text" placeholder="Category amount" class="form-control" <?php if($type == 'view'){ echo "disabled"; } ?> name="<?php echo $value.'_99_'.$value1->id; ?>" id="<?php echo $value.'_99_'.$value1->id; ?>" value="">
                                                    <?php } } ?>
                                            </div>
                                        </div>
                                        <?php
                                            }
                                        }?>	  
                                    </fieldset>
                                <?php } 
                                if($type == 'edit'){
                                ?>
                            <div class="form-group form-actions">
                                <div class="col-md-9 col-md-offset-4">
                                    <button class="btn btn-sm btn-primary" type="submit"><i class="fa fa-angle-right"></i> Update Grade</button>
                                </div>
                            </div>
                        <?php } } ?>
	                </form>
	                <!-- END Basic Form Elements Content -->
	            </div>
	            <!-- END Basic Form Elements Block -->
	        </div>
	</div>
<!-- </div> -->
<!-- END Page Content -->