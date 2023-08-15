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
                        <h2><strong>Complains</strong></h2>
                        <h2 class="pull-right" style="padding-top: 4px;">
                            <?php echo form_open('admin/complains/add'); ?>
                            <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Add Complain</button>
                            <?php echo form_close(); ?>
                        </h2>
                    </div>
                    <div class="table-responsive">
                        <table id="example-datatable" class="table table-vcenter table-condensed table-bordered">
                            <thead>
                                <tr class="text-center">
                                    <th>#</th>
                                    <th>Complain No</th>
                                    <th>Employee Name</th>
                                    <th>Dealer Name</th>
                                    <th>Complain Type Id</th>
                                    <!--            <th>Closed By</th>-->
                                    <th>Remark</th>
                                    <th>Status</th>
                                    <th>Admin Remark</th>
                                    <th>Date</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($data) {
                                    $count = 1;
                                    foreach ($data as $row) {
                                        $id = $row['id'];
                                ?>
                                        <tr class="text-center">
                                            <td><?php echo $count++ ?></td>
                                            <td><?php echo $row['complain_no'] ?></td>
                                            <td><?php echo $row['first_name'] ?></td>
                                            <td><?php echo $row['dealer_name'] ?></td>
                                            <td><?php echo $row['name'] ?></td>
                                            <!--            <td>--><?php //echo $row['closed_by'] 
                                                                    ?><!--</td>-->
                                            <td><?php echo $row['remark'] ?></td>
                                            <td><?php echo $row['status_name'] ?></td>
                                            <td><?php echo $row['admin_remark'] ?></td>
                                            <td><?php echo $row['Date'] ?></td>
                                            <td class="text-center">
                                                <div class="btn-group">
                                                    <!-- <a href="javascript: show_confirm('<?php //echo BASE_URL; ?>', 'view','<?php //echo $id; ?>')" onclick="$('#viewcities').modal('show');" data-toggle="tooltip" title="View" class="btn btn-xs"><i class="fa fa-eye"></i></a> -->
                                                    <a href="<?php echo CURRENT_MODULE . 'complains/add_action/' . $id; ?>" data-toggle="tooltip" title="Edit" class="btn btn-xs btn-default"><i class="fa fa-pencil"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                <?php }
                                } ?>

                            <?php } ?>

                            </tbody>
                        </table>
                    </div>
                </div>

                <!--  </div> -->
                <!-- END Datatables Content -->
                <!-- END Page Content -->
                <?php
                $this->session->set_flashdata('permission', '');
                $this->session->set_flashdata('invalid_per', '');
                ?>