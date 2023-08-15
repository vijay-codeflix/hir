<!-- Page content -->
<div id="page-content">
    <div class="row">
        <div class="col-md-7 col-md-offset-2-5">
            <!-- Basic Form Elements Block -->
            <div class="block">
                <div class="block-title">
                    <h2><strong>Force Update</strong> Setting</h2>
                </div>
                <!-- END Form Elements Title -->
                <!-- Basic Form Elements Content -->
                <form id="app_update_setting" class="form-horizontal form-bordered" method="post" action="<?php echo BASE_URL . 'admin/settings/app_add_settings'; ?>">
                    <div class="form-group">
                        <label for="latest_version_code" class="col-md-4 control-label">Version Code<span class="text-danger">*</span></label>
                        <div class="col-md-7">
                            <input type="text" placeholder="App Version" class="form-control" name="latest_version_code" id="latest_version_code" value="<?= $app_version_setting['latest_version_code']; ?>">
                            <div id="latest_version_code-2-error" class="help-block animation-slideDown falseForm">
                                <?php echo form_error($app_version_setting['latest_version_code']); ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="latest_version" class="col-md-4 control-label">Version Name<span class="text-danger">*</span></label>
                        <div class="col-md-7">
                            <input type="text" placeholder="App Version Number" class="form-control" name="latest_version" id="latest_version" value="<?= $app_version_setting['latest_version']; ?>">
                            <div id="latest_version-2-error" class="help-block animation-slideDown falseForm">
                                <?php echo form_error($app_version_setting['latest_version']); ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="update_required" class="col-md-4 control-label">Force Update<span class="text-danger">*</span></label>
                        <div class="col-md-7">
                        <label for="user_enable" class="radio-inline">
                            <input type="radio" name="update_required" id="update_required" value="1" <?= ($app_version_setting['update_required'] == 1)? "checked": ""; ?>> Yes
                        </label>
                        <label for="user_enable" class="radio-inline">
                            <input type="radio" name="update_required" id="update_required" value="0" <?= ($app_version_setting['update_required'] == 0)? "checked": ""; ?>> No
                        </label>
                            <div id="update_required-2-error" class="help-block animation-slideDown falseForm">
                                <?php echo form_error($app_version_setting['update_required']); ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group form-actions">
                        <div class="col-md-9 col-md-offset-4">
                            <button class="btn btn-sm btn-primary" type="submit"><i class="fa fa-angle-right"></i> Force Update Setting</button>
                        </div>
                    </div>
                </form>
            </div>
            <!-- END Basic Form Elements Block -->
        </div>
    </div>
    <!-- </div> -->
    <!-- END Page Content -->