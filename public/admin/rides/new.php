<?php
 
 require_once('../../../private/initialize.php');

 if(is_post_request()) {

 }

  $ride = new Ride($args);
 <?php $page_title = 'Create Ride'; ?>
<?php include(SHARED_PATH . '/admin_header.php'); ?>