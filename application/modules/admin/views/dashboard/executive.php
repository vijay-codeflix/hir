<!-- Page content -->
<div id="page-content">
   <div class="content-header content-header-media small-header-exe">
      <div class="header-section">
         <div class="row">
            <div class="col-sm-6 col-lg-4 remove-padding-right">
               <h1>Welcome <strong><?php echo $executive; ?></strong><br><small>You Look Awesome!</small></h1>
            </div>
         </div>
      </div>
      <img src="<?php echo ASSETS_IMAGE;?>loginpage_bg.png" alt="header image" class="animation-pulseSlow">
   </div>
   <div class="row">
      <div class="col-md-8 col-md-offset-2">
         <?php if( $this->session->flashdata('register') ){  ?>
            <div class="alert alert-success alert-dismissable">
              <button aria-hidden="true" data-dismiss="alert" class="close" type="button">&times;</button>
              <h4><i class="fa fa-check-circle"></i> Success</h4> <?php echo $this->session->flashdata('register'); ?>
            </div>
          <?php } ?>

          <?php if( $this->session->flashdata('location_not_found') ){  ?>
            <div class="alert alert-info alert-dismissable">
              <button aria-hidden="true" data-dismiss="alert" class="close" type="button">&times;</button>
              <h4><i class="fa fa-check-circle"></i> Service not available</h4> <strong>Dear <?php echo $this->session->flashdata('location_not_found'); ?>,</strong><br><?php echo LOCATION_NOT_FOUND; ?>
            </div>
          <?php } ?>

          <?php if( $this->session->flashdata('login_error') ){  ?>
            <div class="alert alert-danger alert-dismissable">
              <button aria-hidden="true" data-dismiss="alert" class="close" type="button">&times;</button>
              <h4><i class="fa fa-times-circle"></i> ERROR</h4> <?php echo $this->session->flashdata('login_error'); ?>
            </div>
          <?php } ?>

         <!--Start  Block  -->
         <div class="block">
            <div class="block-title">
               <h2><strong>Customer Login</strong></h2>
            </div>
            <!-- Basic Form Elements Content -->
            <form id="form-validation" class="form-horizontal form-bordered" enctype="multipart/form-data" method="post" action="<?php echo BASE_URL.'admin/customerarea/login'; ?>" novalidate="novalidate">
               <div class="form-group">
                  <label for="code" class="col-xs-5 col-md-5 control-label">Enter Mobile Number / Customer Code:</label>
                  <div class="col-xs-7 col-md-5 push">
                     <input type="text" placeholder="Mobile number or customer code" class="form-control" name="code" id="code" value = "<?php echo set_value('code'); ?>">                 
                  </div>
                  <div class="col-xs-7 col-md-2 pull-right">
                     <button class="btn btn-md btn-primary iconbg" type="submit">Go&nbsp;&nbsp;<i class="fa fa-angle-right"></i></button>
                  </div>
               </div>
               <div class="form-group ">
                  <div class="col-md-3 pull-right text-center">
                     <a href="<?php echo BASE_URL.'admin/customerarea/signup'; ?>" data-toggle="tooltip" title="New Customer" ><u>New Customer</u></a>
                  </div>
               </div>
            </form>
         </div>
         <!-- End City Add Block-->
      </div>
   </div>
<!-- END Page Content -->
<script type="text/javascript">localStorage.setItem("activeLiIndex", 0);localStorage.setItem("activeLiAnchorIndex", null);</script>