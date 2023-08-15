<?php
//echo "<pre>";
//print_r($types);
//print_r($datas);
//exit();
?>

<!-- Page content -->
<div id="page-content">
    <?php
    if ($this->session->flashdata('invalid_per') != '') {
    ?>
        <div class="alert text-center">
            <?php echo $this->session->flashdata('invalid_per'); ?>
        </div>
    <?php
    } else {

    ?>

        <!- Datatables Content -->
            <div class="block full">
                <div class="block-title">
                    <h2><strong>Follow Up</strong></h2>
                    <h2 class="pull-right" style="padding-top: 4px;">
                        <?php echo form_open('admin/followup/add'); ?>
                        <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Add Follow Up</button>
                        <?php echo form_close(); ?>
                    </h2>
                </div>
                <div class="row">
                    <div class="col-md-12">

                        <form class="form-horizontal" id="viewByfollowup" name="viewByfollowup" action="<?php echo CURRENT_MODULE . 'followup'; ?>" method="POST">
                            <div class="block col-md-6 pull-left no-border">
                                <div class="form-group">
                                    <label for="select_category" class="col-md-5 control-label">Select Category</label>
                                    <div class="col-md-7">

                                        <!--<input list="select_categories" size="1" class="form-control" placeholder="Select categories here..." name="type_follow" id="select_category">-->
                                        <!--<datalist id="select_categories">-->
                                        <select size="1" class="form-control  select-chosen" name="type_follow" id="select_employee">
                                            <option value="">All</option>
                                            <?php

                                            if ($types) {
                                                foreach ($types as $row) {
                                                    $id = $row['id'];
                                                    if (isset($type_id_id) && ($type_id_id == $id)) {
                                                        echo '<option selected value="' . $row['id'] . '" >' . $row['name'] . '</option>';
                                                    } else {
                                                        echo '<option  value="' . $row['id'] . '" >' . $row['name'] . '</option>';
                                                    }
                                                }
                                            }
                                            ?>
                                        </select>
                                        <!--</datalist>-->
                                    </div>

                                </div>
                            </div>

                        </form>


                    </div>

                </div>
                <div class="table-responsive">


                    <table id="example-datatable" class="table table-vcenter table-condensed table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">Number</th>
                                <th class="text-center">Employee Name</th>
                                <th class="text-center">Firm Name</th>
                                <th class="text-center">Follow Up Type Name</th>
                                <th class="text-center">Remark</th>
                                <th class="text-center">Submit Date</th>
                                <th class="text-center">Follow Up Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($datas) {
                                $count = 1;
                                foreach ($datas as $row) {
                            ?>
                                    <tr>
                                        <td class="text-center"><?php echo $count++ ?></td>
                                        <td class="text-center"><?php echo $row['number'] ?></td>
                                        <td class="text-center"><?php echo $row['first_name'] . " " . $row['last_name'] ?></td>
                                        <td class="text-center"><?php echo $row['firm_name'] ?></td>
                                        <td class="text-center"><?php echo $row['name'] ?></td>
                                        <td class="text-center"><?php echo $row['remark'] ?></td>
                                        <td class="text-center"><?php echo $row['submit_date'] ?></td>
                                        <td class="text-center"><?php echo $row['follow_up_date'] ?></td>

                                    </tr>
                            <?php }
                            } ?>

                        <?php } ?>

                        </tbody>
                    </table>
                </div>
            </div>
</div>


<!--  </div> -->
<!-- END Datatables Content -->
<!-- END Page Content -->
<?php
$this->session->set_flashdata('permission', '');
$this->session->set_flashdata('invalid_per', '');
?>