<?php
/**
 * page_footer.php
 *
 * Author: pixelcave
 *
 * The footer of each page
 *
 */
?>  
            </div>
            <!-- Footer -->
            <footer class="clearfix">
                <!-- <div class="pull-right">
                    Crafted with <i class="fa fa-heart text-danger"></i> by <a href="http://goo.gl/vNS3I" target="_blank">pixelcave</a>
                </div> -->
                <div class="text-center">
                  <div class ="row">
                    <div class="col-xs-12">
                      <!-- <span id="year-copy"></span> --> Copyright &copy; <?=date('Y')?>  HIR Industries | All Rights Reserved</a>
                  </div>
                </div>
                </div>
            </footer>
            <!-- END Footer -->
        </div>
        <!-- END Main Container -->
    </div>
    <!-- END Page Container -->
</div>
<!-- END Page Wrapper -->

<!-- Scroll to top link, initialized in js/app.js - scrollToTop() -->
<a href="#" id="to-top"><i class="fa fa-angle-double-up"></i></a>

<!-- User Settings, modal which opens from Settings link (found in top right user menu) and the Cog link (found in sidebar user info) -->
<div id="modal-user-settings" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header text-center">
                <h3 class="modal-title"> Expense Request</h3>
            </div>
            <!-- END Modal Header -->

            <!-- Modal Body -->
            <div class="modal-body">
                <form action="" method="post" class="form-horizontal form-bordered">
                    <div class="form-group form-actions">
                        <div class="col-xs-12 text-right">
                            <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-sm btn-primary iconbg">Save</button>
                        </div>
                    </div>
                </form>
            </div>
            <!-- END Modal Body -->
        </div>
    </div>
</div>
<!-- END User Settings -->
<div id="modal-delete" class="modal fade" role="dialog" aria-hidden="true">
      <form action="" method="post" class="form-horizontal form-bordered">            
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header text-center">
                    <h4 class="modal-title">Do you really want to delete?</h4>
                </div>
                <!-- END Modal Header -->
                <!-- Modal Body -->
                <div class="modal-body">                   
                    <fieldset>
                        <legend>
                            <div class="col-xs-6 text-right">
                            <button type="submit" class="btn btn-info">Delete</button>
                            </div>
                            <div class="col-xs-6 text-left">
                            <button type="button" class="btn btn-info" data-dismiss="modal">Cancel</button>
                        </div>
                            <br><br></legend>                       
                    </fieldset>                    
                </div>
                <!-- END Modal Body -->
            </div>
        </div>
      </form>
</div>
<!--- Model for display User information   -->
<div id="view-users-detail" class="modal fade" role="dialog" aria-hidden="true">
      <form action="" method="post" class="form-horizontal form-bordered">            
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <!-- Modal Header -->
                <!--<div class="modal-header text-center"></div> -->
                <!-- END Modal Header -->
                <!-- Modal Body -->
                <div class="modal-body remove-padding">    
                  <div class="row">
                    <div class="col-md-12">               
                      <div class="block remove-margin">
                          <!-- Typeahead Title -->
                          <div class="block-title">
                              <div class="block-options pull-right" onclick="$('#view-users-detail').modal('hide');">
                                  <a data-original-title="" href="javascript: void(0);" class="btn btn-alt btn-sm btn-default" data-toggle="tooltip" title=""><i class="fa fa-times"></i></a>
                              </div>
                              <h2><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong>&nbsp;&nbsp;&nbsp;&nbsp;</h2>
                          </div>
                          <!-- END Typeahead Title -->
                          <!-- Typeahead Content -->
                          <form action="javascript: void(0);" method="post" class="form-horizontal form-bordered" onsubmit="return false;">
                            <div class="table-responsive none-border" id="userDetails">
                                
                            </div> 
                          </form>
                          <!-- END Typeahead Content -->
                      </div>
                    </div>
                  </div>
                </div>
                <!-- END Modal Body -->
            </div>
        </div>
      </form>
</div>
<!--- END- Model for display User information-->

<!--- Model with close button to display User information   -->
<div id="view-users" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <!--<div class="modal-header text-center"></div> -->
            <!-- END Modal Header -->
            <!-- Modal Body -->
            <div class="modal-body remove-padding">    
              <div class="row">
                <div class="col-md-12">               
                  <div class="block remove-margin">
                  <!-- Typeahead Title -->
                  <div class="block-title">
                      <div class="block-options pull-right" onclick="$('#view-users').modal('hide');">
                          <a data-original-title="" href="javascript: void(0);" class="btn btn-alt btn-sm btn-default" data-toggle="tooltip" title=""><i class="fa fa-times"></i></a>
                      </div>
                      <h2 id="viewTitle"><strong>View&nbsp;User</strong></h2>
                  </div>
                  <!-- END Typeahead Title -->

                  <!-- Typeahead Content -->
                  <form id = "normalmodel" name = "normalmodel" action="javascript: void(0);" method="post" class="form-horizontal" onsubmit="return false;">
                    
                  </form>
                  <!-- END Typeahead Content -->
              </div>
            </div>
          </div>
        </div>
        <!-- END Modal Body -->
    </div>
</div>
</div>
<!--- END- Model for display User information-->