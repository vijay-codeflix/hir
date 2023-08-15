<!-- Page content -->
<div id="page-content">
    <div class="content-header content-header-media small-header">
        <div class="header-section">
            <div class="row">
                <!-- Top Stats -->
                <div class="col-md-12 col-lg-12">
                    <div class="row text-center">
                        <?php if (isset($adminCount)) { ?>
                            <div class="col-xs-6 col-sm-4">
                                <h2 class="animation-hatch">
                                    <strong>Admin(s)</strong><br>
                                    <small><?php echo str_pad($adminCount, 2, "0", STR_PAD_LEFT); ?></small>
                                </h2>
                            </div>
                            <div class="col-xs-6 col-sm-4">
                                <h2 class="animation-hatch">
                                    <strong>Sub Admin(s)</strong><br>
                                    <small><?php echo str_pad($subAdminCount, 2, "0", STR_PAD_LEFT); ?></small>
                                </h2>
                            </div>
                            <div class="col-xs-6 col-sm-4">
                                <h2 class="animation-hatch">
                                    <strong>Employee(s)</strong><br>
                                    <small><?php echo str_pad($empCount, 2, "0", STR_PAD_LEFT); ?></small>
                                </h2>
                            </div>
                        <?php } ?>
                        <?php if (!isset($adminCount) && isset($subAdminCount)) { ?>
                            <div class="col-xs-6 col-sm-6">
                                <h2 class="animation-hatch">
                                    <strong>Sub Admin(s)</strong><br>
                                    <small><?php echo str_pad($subAdminCount, 2, "0", STR_PAD_LEFT); ?></small>
                                </h2>
                            </div>
                            <div class="col-xs-6 col-sm-6">
                                <h2 class="animation-hatch">
                                    <strong>Employee(s)</strong><br>
                                    <small><?php echo str_pad($empCount, 2, "0", STR_PAD_LEFT); ?></small>
                                </h2>
                            </div>
                        <?php } ?>
                    </div>


                    <!-- We hide the last stat to fit the other 3 on small devices -->


                </div>

                <!-- END Top Stats -->
            </div>
            <!-- Mini Charts Row -->


        </div>
        <!-- For best results use an image with a resolution of 2560x248 pixels (You can also use a blurred image with ratio 10:1 - eg: 1000x100 pixels - it will adjust and look great!) -->
        <img src="<?php echo ASSETS_IMAGE; ?>placeholders\headers\widget<?php echo rand(1, 11) ?>_header.jpg" alt="header image" class="animation-pulseSlow">
    </div>
    <!-- Page content -->
    <div id="page-content">
        <!-- eCommerce Dashboard Header -->
        <div class="content-header">
            <ul class="nav-horizontal text-center">
                <li>
                    <a href="users/liveMap"><i class="fa fa-map-marker"></i> Live Map</a>
                </li>
                <li>
                    <a href="users"><i class="fa fa-users"></i> Users</a>
                </li>
                <li>
                    <a href="dealers"><i class="gi gi-crown"></i> Parties</a>
                </li>
                <li>
                    <a href="page_ecom_products.php"><i class="gi gi-shopping_bag"></i> Products</a>
                </li>
                <li>
                    <a href="page_ecom_customer_view.php"><i class="gi gi-user"></i> Employee visits</a>
                </li>
            </ul>
        </div>
        <!-- END eCommerce Dashboard Header -->

        <!-- Quick Stats -->

        <div class="row text-center">
            <div class="col-md-6">
                <a href="javascript:void(0)" class="widget widget-hover-effect2">
                    <div class="widget-extra themed-background">
                        <h4 class="widget-content-light"><strong>Active</strong> Users</h4>
                    </div>
                    <div class="widget-extra-full"><span class="h2 animation-expandOpen"><?php echo str_pad($liveUserCount, 2, "0", STR_PAD_LEFT); ?></span></div>
                </a>
            </div>
            <div class="col-md-6">
                <a href="javascript:void(0)" class="widget widget-hover-effect2">
                    <div class="widget-extra themed-background-fancy">
                        <h4 class="widget-content-light"><strong>Absent</strong> Users</h4>
                    </div>
                    <div class="widget-extra-full"><span class="h2 themed-color-dark animation-expandOpen"><?php echo str_pad($absentUserCount, 2, "0", STR_PAD_LEFT); ?></span></div>
                </a>
            </div>
        </div>
        <!-- END Quick Stats -->

        <!-- Orders and Products -->
        <div class="row">
            <div class="col-md-12">
                <!-- Latest Orders Block -->
                <div class="block">
                    <!-- Latest Orders Title -->
                    <div class="block-title widget-extra">
                        <div class="block-options pull-right">
                            <a href="users" class="btn btn-alt btn-sm btn-default " data-toggle="tooltip" title="Show All"><i class="fa fa-eye"></i></a>
                        </div>
                        <h2 class="widget-content-light"><strong>User</strong> List</h2>
                    </div>
                    <!-- END Latest Orders Title -->

                    <!-- Latest Orders Content -->
                    <table id="example-datatable" class="table table-vcenter table-condensed table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">Name</th>
                                <th class="text-center">Type of user</th>
                                <th class="text-center">Parent</th>
                                <th class="text-center">Phone</th>
                            </tr>
                        </thead>

                        <tbody id="userlist-table">
                            <?php
                            if ($users) {
                                for ($i = 0; $i < count($users); $i++) {
                                    $userId = str_replace('/', '_', rtrim(base64_encode($users[$i]->id), '='));
                                    $nextUrl = (ucfirst($users[$i]->userType) == "Employee") ? "javascript: void(0)" : CURRENT_MODULE . 'users/index/' . $userId;
                            ?>
                                    <tr>
                                        <td class="text-center"><?php echo str_pad($i + 1, 2, "0", STR_PAD_LEFT);; ?></td>
                                        <td class="text-center"><a href="javascript:void(0)"><strong><?php echo $users[$i]->first_name . " " . $users[$i]->last_name; ?></strong></a></td>
                                        <td class="text-center"><?php echo ucfirst($users[$i]->userType); ?></td>
                                        <td class="text-center"><?php echo $users[$i]->parent_name; ?></td>
                                        <td class="text-center"><?php echo $users[$i]->phone; ?></td>

                                    </tr>
                                <?php } ?>

                            <?php } ?>

                        </tbody>
                    </table>
                    <!-- END Latest Orders Content -->
                </div>
                <!-- END Latest Orders Block -->
            </div>
            <div class="col-md-12">
                <!-- Latest Orders Block -->

                <div class="block full">
                    <div class="block-title">
                        <div class="block-options pull-right">
                            <a href="dealers" class="btn btn-alt btn-sm btn-default" data-toggle="tooltip" title="Show All"><i class="fa fa-eye"></i></a>
                        </div>
                        <h2><strong>Dealer</strong> List</h2>
                    </div>
                    <div class="table-responsive">
                        <table id="example-datatable" class="table table-vcenter table-condensed table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Name</th>
                                    <th class="text-center">Firm</th>
                                    <th class="text-center">Phone</th>
                                    <th class="text-center">Area</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($dealers) {
                                    $catPrice = array();
                                    for ($i = 0; $i < count($dealers); $i++) {
                                        $id = str_replace('/', '_', rtrim(base64_encode($dealers[$i]->id), '='));
                                ?>
                                        <tr>
                                            <td class="text-center"><?php echo str_pad($i + 1, 2, "0", STR_PAD_LEFT); ?></td>
                                            <td class="text-center text-primary"><a href="javascript:void(0)"><strong><?php echo ucfirst($dealers[$i]->dealer_name); ?></strong></a></td>
                                            <td class="text-center"><?php echo $dealers[$i]->firm_name; ?></td>
                                            <td class="text-center"><?php echo $dealers[$i]->dealer_phone; ?></td>
                                            <td class="text-center"><?php echo $dealers[$i]->city_or_town; ?></td>
                                        </tr>
                                    <?php } ?>
                                <?php } ?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <!-- END Datatables Content -->
            <!-- END Orders and Products -->
            <div class="block full">
                <div class="block-title themed-background-autumn">
                    <h2><strong>Attendance Report</strong></h2>
                </div>
                <div class="table-responsive">
                    <table id="example-datatable" class="table table-vcenter table-condensed table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">Employee </th>
                                <th class="text-center">Phone</th>
                                <th class="text-center">Punch IN</th>
                                <th class="text-center">Punch OUT</th>
                                <th class="text-center">Punch IN Location</th>
                                <th class="text-center">Punch OUT Location</th>
                                <th class="text-center">Total Distance Km (by System)</th>
                                <th class="text-center">Total Hours</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($attendanceReport) {
                                $catPrice = array();
                                for ($i = 0; $i < count($attendanceReport); $i++) {
                                    $id = str_replace('/', '_', rtrim(base64_encode($attendanceReport[$i]->id), '='));
                            ?>
                                    <tr>
                                        <td class="text-center"><?php echo str_pad($i + 1, 2, "0", STR_PAD_LEFT); ?></td>
                                        <td class="text-center"><?php echo ucfirst($attendanceReport[$i]->empName); ?></td>
                                        <td class="text-center"><?php echo $attendanceReport[$i]->phone; ?></td>
                                        <td class="text-center"><?php echo $attendanceReport[$i]->punchInDate; ?></td>
                                        <td class="text-center"><?php echo $attendanceReport[$i]->punchOutDate; ?></td>
                                        <td class="text-center"><?php echo $attendanceReport[$i]->punchInLocation; ?></td>
                                        <td class="text-center"><?php echo $attendanceReport[$i]->punchOutLocation; ?></td>
                                        <td class="text-center"><?php echo $attendanceReport[$i]->totalDistance; ?></td>
                                        <td class="text-center"><?php echo $attendanceReport[$i]->totalLoggedTime; ?></td>
                                    </tr>
                                <?php } ?>
                            <?php } ?>

                        </tbody>
                    </table>
                </div>
            </div>
            <!-- </div> -->
        </div>
        <!-- END Page Content -->
    </div>
<!-- </div> -->
<!-- END Page Content -->
