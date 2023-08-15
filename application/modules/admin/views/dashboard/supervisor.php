<!-- Page content -->
<div id="page-content">
	<div class="content-header content-header-media small-header-sup">
        <div class="header-section">
            <div class="row">
            	
                <!-- Top Stats -->
                   <div class="col-md-12 col-lg-12">
                    <div class="row text-center">                        
                        <div class="col-xs-6 col-sm-3">
                            <h2 class="animation-hatch">
                                <strong><?php echo ($customer['customerTotal'] > 1)? 'Customers' : 'Customer'; ?></strong><br>
                                <small><?php echo $customer['customerTotal']; ?></small>
                            </h2>
                        </div>
                        <div class="col-xs-6 col-sm-3">
                            <h2 class="animation-hatch">
                                <strong><?php echo ($executive > 1)? 'Executives' : 'Executive'; ?></strong><br>
                                <small><?php echo $executive; ?></small>
                            </h2>
                        </div>
                        <div class="col-xs-6 col-sm-3">
                            <h2 class="animation-hatch">
                                <strong><?php echo ($delivery_boy > 1)? 'Delivery Boys' : 'Delivery Boy'; ?></strong><br>
                                <small><?php echo $delivery_boy; ?></small>
                            </h2>
                        </div>
                        <div class="col-xs-6 col-sm-3">
                            <h2 class="animation-hatch">
                                <strong><?php echo ($products > 1)? 'Products' : 'Products'; ?></strong><br>
                                <small><?php echo $products; ?></small>
                            </h2>
                        </div>                       
                    </div>
                  
                 </div>  
               
               
                <!-- END Top Stats -->
            </div>
        </div>
        <!-- For best results use an image with a resolution of 2560x248 pixels (You can also use a blurred image with ratio 10:1 - eg: 1000x100 pixels - it will adjust and look great!) -->
        <img src="<?php echo ASSETS_IMAGE;?>loginpage_bg.png" alt="header image" class="animation-pulseSlow">
    </div>
    
    <?php  if (isset($customer['locationData']) && !empty($customer['locationData']) ) { ?>
        
        <div class="row">  

          <?php foreach($customer['locationData'] as $row) { ?>

          <div class="col-md-6">
                <!-- Your Plan Widget -->
                <div class="widget">
                    <div class="widget-extra themed-background-dark">
                       
                        <h3 class="widget-content-light">
                             <strong><?php echo ucfirst(strtolower($row->loc_name)); ?></strong>
                            <small><a href="javascript: void(0);"></a></small>
                        </h3>
                    </div>
                    <div class="widget-extra-full">
                        <div class="row text-center">
                            <div class="col-xs-6 col-lg-12">
                                <h3>
                                    <strong><?php echo $row->custCount ?></strong><br>
                                    <small><?php echo ($row->custCount > 1)? 'Customers' : 'Customer'; ?></small>
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END Your Plan Widget -->    

          </div>
          <?php } ?>  
        </div>
    <?php } ?>  



<!-- END Page Content -->
<script type="text/javascript">localStorage.setItem("activeLiIndex", 0);localStorage.setItem("activeLiAnchorIndex", null);</script>