
<!-- This file is use for display message in view page.-->
<?php if($this->session->flashdata('flash_message_success')) {?>  
  <div class="alert alert-success">
      <?php echo $this->session->flashdata('flash_message_success'); ?>
  </div>
  <?php } 

    if($this->session->flashdata('flash_message')) {?>  
  <div class="alert alert-danger">
      <?php echo $this->session->flashdata('flash_message'); ?>
  </div>
  <?php } ?>
  
 <div class="alert alert-danger alert-dismissable" id="notdelete" style="display: none;">
       <h4><i class="fa fa-times-circle"></i> ACCESS DENIED</h4> 
      You can not delete <a href="javascript: void(0);" class="alert-link"><span></span></a> there are already some data related to this!
  </div>
