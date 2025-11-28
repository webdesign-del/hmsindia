<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
   <head>
      <meta charset="utf-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <title>MRD Dashboard</title>
      <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
      <link rel="stylesheet" href="<?php echo base_url(); ?>assets/materialize/css/materialize.min.css" media="screen,projection" />
      <!-- Bootstrap Styles-->
      <link href="<?php echo base_url();?>assets/css/bootstrap.css" rel="stylesheet" />
      <!-- FontAwesome Styles-->
      <link href="<?php echo base_url();?>assets/css/font-awesome.css" rel="stylesheet" />
      <!-- Morris Chart Styles-->
      <link href="<?php echo base_url();?>assets/css/morris/morris-0.4.3.min.css" rel="stylesheet" />
      <!-- Custom Styles-->
      <link href="<?php echo base_url();?>assets/css/custom-styles.css" rel="stylesheet" />
      <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
      <!-- jQuery Js -->
      <link rel="stylesheet" href="<?php echo base_url();?>assets/css/jquery-ui.css">
      <link rel="stylesheet" href="<?php echo base_url();?>assets/css/select2.min.css">
      <script src="<?php echo base_url();?>assets/js/jquery-1.12.4.js"></script>
      <script src="<?php echo base_url();?>assets/js/jquery-ui.js"></script>
      <script src="<?php echo base_url();?>assets/js/select2.min.js"></script>
      <!-- Google Fonts-->
      <link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
      <link rel="stylesheet" href="<?php echo base_url();?>assets/css/Lightweight-Chart/cssCharts.css">
      <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
   </head>
   <body>
      <div id="wrapper">
      <nav class="navbar navbar-default top-navbar" role="navigation">
         <div class="navbar-header">
            <button type="button" class="navbar-toggle waves-effect waves-dark" data-toggle="collapse" data-target=".sidebar-collapse"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
    <a class="navbar-brand waves-effect waves-dark logo_section" href="<?php echo base_url(); ?>">
      <img src="<?php echo base_url(); ?>assets/images/IndiaIVFClinic_logo.png" /></a>
            <div id="sideNav" href=""><i class="material-icons dp48">toc</i></div>
         </div>
         <?php $notice = get_center_notification(); ?>
         <ul class="nav navbar-top-links navbar-right">
            <li><a class="dropdown-button waves-effect waves-dark" href="#!" data-activates="dropdown4">
               <?php if($notice['count'] > 0){
                  echo '<span class="notice_count">'.$notice['count'].'</span>';
                       } ?>
               <i class="fa fa-bell fa-fw" aria-hidden="true"></i> <i class="material-icons right">arrow_drop_down</i></a>
            </li>
            <li><a class="dropdown-button waves-effect waves-dark" href="#!" data-activates="dropdown1"><i class="fa fa-user fa-fw"></i> <b><?php echo $_SESSION['logged_telecaller']['name']?></b> <i class="material-icons right">arrow_drop_down</i></a></li>
         </ul>
      </nav>
      <!-- Dropdown Structure -->
      <ul id="dropdown1" class="dropdown-content">
         <!-- <li><a href="#"><i class="fa fa-user fa-fw"></i> My Profile</a> </li>
            <li><a href="#"><i class="fa fa-gear fa-fw"></i> Settings</a> </li> -->
         <li><a href="<?php echo base_url(); ?>logout?r=<?php echo base64_encode('logged_telecaller'); ?>"><i class="fa fa-sign-out fa-fw"></i> Logout</a> </li>
      </ul>
      <ul id="dropdown4" class="dropdown-content dropdown-tasks w250 taskList notification_list">
         <?php //var_dump($notice);die;
            if($notice['count'] > 0){
            	echo $notice['html'];
            }
            ?>
      </ul>
      <!--/. NAV TOP  -->
      <nav class="navbar-default navbar-side" style="overflow:scroll;height:100%" role="navigation">
         <div class="sidebar-collapse">
            <ul class="nav" id="main-menu">
				<li> <a class="active-menu waves-effect waves-dark" href="<?php echo base_url(); ?>"><i class="fa fa-dashboard"></i> Dashboard</a> </li>
				 <li> <a href="#" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Manage Appointments<span class="fa arrow"></span></a>
        <ul class="nav nav-second-level">
	        <li><a href="<?php echo base_url(); ?>appointment">Book appointment</a></li>
	        <li><a href="<?php echo base_url(); ?>follow-up-appointment">Book Follow Up</a></li>
            <li><a href="<?php echo base_url(); ?>my_appointments">My appointments</a></li>
             <li><a href="<?php echo base_url(); ?>my_appointments_camp">My appointments in camp</a></li>
         </ul>
      </li>
			</ul>
         </div>
      </nav>
      <!-- /. NAV SIDE  -->
      <div id="page-wrapper">
      <?php 
         if(isset($_GET['m']) && !empty($_GET['m'])){
         	echo '<div class="col-sm-12 col-xs-12 '.base64_decode($_GET['t']).'"><h4>'.base64_decode($_GET['m']).'</h4></div>';
         }
         ?>
