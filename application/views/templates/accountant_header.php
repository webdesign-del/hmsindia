<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
   <head>
      <meta charset="utf-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <title>Accountant Dashboard</title>
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
      <!-- <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"> -->
      <!-- jQuery Js -->
      <link rel="stylesheet" href="<?php echo base_url();?>assets/css/jquery-ui.css">
      <!-- <script src="<?php echo base_url();?>assets/js/jquery-1.12.4.js"></script> -->
      <script src="<?php echo base_url();?>assets/js/jquery.min.js"></script>
      <script src="<?php echo base_url();?>assets/js/jquery-ui.js"></script>
      <script src="<?php echo base_url();?>assets/js/bootstrap.min.js"></script>
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
            <!-- <a class="navbar-brand waves-effect waves-dark" href="<?php echo base_url(); ?>"><strong>IndiaIVF</strong></a> -->
                <a class="navbar-brand waves-effect waves-dark logo_section" href="<?php echo base_url(); ?>">
                  <img src="<?php echo base_url(); ?>assets/images/IndiaIVFClinic_logo.png" /></a>
            <div id="sideNav" href=""><i class="material-icons dp48">toc</i></div>
         </div>
         <ul class="nav navbar-top-links navbar-right">
            <li><a class="dropdown-button waves-effect waves-dark" href="#!" data-activates="dropdown4"><i class="fa fa-bell fa-fw" aria-hidden="true"></i> <i class="material-icons right">arrow_drop_down</i></a></li>
            <li><a class="dropdown-button waves-effect waves-dark" href="#!" data-activates="dropdown1"><i class="fa fa-user fa-fw"></i> <b><?php echo $_SESSION['logged_accountant']['name']?></b> <i class="material-icons right">arrow_drop_down</i></a></li>
         </ul>
      </nav>
      <!-- Dropdown Structure -->
      <ul id="dropdown1" class="dropdown-content">
         <?php $center = $_SESSION['logged_accountant']['center'];
            if($center == 0){?>
         <li><a href="<?php echo base_url('settings/'); ?>"><i class="fa fa-gear fa-fw"></i> Settings</a> </li>
         <?php } ?>
         <li><a href="<?php echo base_url(); ?>logout?r=<?php echo base64_encode('logged_accountant'); ?>"><i class="fa fa-sign-out fa-fw"></i> Logout</a> </li>
      </ul>
      <ul id="dropdown4" class="dropdown-content dropdown-tasks w250 taskList">
         <li>
            <div> <strong>Billing Department</strong> <span class="pull-right text-muted"> </span> </div>
            <p>Lorem Ipsum has been the industry's standard dummy text ever since the 1500s...</p>
            </a> 
         </li>
         <li class="divider"></li>
         <li> <a class="text-center" href="#"> <strong>Read All Notifications</strong> <i class="fa fa-angle-right"></i> </a> </li>
      </ul>
      <!--/. NAV TOP  -->
      <nav class="navbar-default navbar-side" style="overflow:scroll;height:100%" role="navigation">
         <div class="sidebar-collapse">
            <?php if ($_SESSION['logged_accountant']['username'] == "Anshul@tomorrowcapital.in") { ?>
            <ul class="nav" id="main-menu">
               <li> <a class="active-menu waves-effect waves-dark" href="<?php echo base_url(); ?>"><i class="fa fa-dashboard"></i> Dashboard</a> </li>
               <li>
                  <a href="#" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Manage Accounts<span class="fa arrow"></span></a>
                  <ul class="nav nav-second-level">
                     <li> <a href="<?php echo base_url(); ?>accounts/accounts">Account Ledger</a> </li>
                  </ul>
               </li>
               <li>
                  <a href="#" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Reports<span class="fa arrow"></span></a>
                  <ul class="nav nav-second-level">
                     <li> <a href="<?php echo base_url(); ?>accounts/procedure_reports">Procedure</a> </li>
                     <li> <a href="<?php echo base_url(); ?>accounts/investigation_sales">Investigation</a> </li>
                     <li> <a href="<?php echo base_url(); ?>stocks/medicine_stock">Medicine Report (Item Wise)</a> </li>
                     <li> <a href="<?php echo base_url(); ?>accounts/consultation_reports">Consultation</a> </li>
                     <li> <a href="<?php echo base_url(); ?>accounts/partialpayments_report">Partial</a> </li>
                     <li><a href="<?php echo base_url(); ?>stocks/patient_items">Patient Consumption</a></li>
                     <li> <a href="<?php echo base_url(); ?>stocks/stocks_reports">Live Stocks Report</a> </li>
                  </ul>
               </li>
               <li><a class="waves-effect waves-dark" href="<?php echo base_url(); ?>accounts/reports"><i class="fa fa-sitemap"></i> Revenue Dashboard</a> </li>
               <li>
                  <a href="#" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Mou<span class="fa arrow"></span></a>
                  <ul class="nav nav-second-level">
                     <li> <a href="<?php echo base_url(); ?>accounts/moulist">Mou List</a> </li>
                  </ul>
               </li>
               <li>
                  <a href="#" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Cancel<span class="fa arrow"></span></a>
                  <ul class="nav nav-second-level">
                     <li> <a href="<?php echo base_url(); ?>accounts/cancel_consultation_list">Consultation</a> </li>
                     <li> <a href="<?php echo base_url(); ?>accounts/cancel_medicine_list">Medicine</a> </li>
                     <li> <a href="<?php echo base_url(); ?>accounts/cancel_procedure_list">Procedure</a> </li>
                  </ul>
               </li>
               <li> <a href="<?php echo base_url(); ?>accounts/wallet_list"><i class="fa fa-sitemap"></i>Refund Amount</a> </li>
               <li> <a href="<?php echo base_url(); ?>accounts/procedure_advice"><i class="fa fa-sitemap"></i>Financial Clearance</a> </li>
               <li><a class="waves-effect waves-dark" href="<?php echo base_url(); ?>accounts/procedure_origin"><i class="fa fa-sitemap"></i> Procedure Revenue Reports</a> </li>
               <li><a class="waves-effect waves-dark" href="<?php echo base_url(); ?>procedures"><i class="fa fa-sitemap"></i>Procedures List</a> </li>
               <li><a class="waves-effect waves-dark" href="<?php echo base_url(); ?>stocks/all_center_stocks"><i class="fa fa-sitemap"></i> All Center Stocks</a> </li>
               <li> <a href="<?php echo base_url(); ?>accounts/patient_center_wise_report" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Patient Journey</a></li>
            </ul>
            <?php }else{ ?>
            <ul class="nav" id="main-menu">
               <li> <a class="active-menu waves-effect waves-dark" href="<?php echo base_url(); ?>"><i class="fa fa-dashboard"></i> Dashboard</a> </li>
               <li>
                  <a href="#" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Manage Accounts<span class="fa arrow"></span></a>
                  <ul class="nav nav-second-level">
                     <li> <a href="<?php echo base_url(); ?>accounts/accounts">Account Ledger</a> </li>
                     <li> <a href="<?php echo base_url(); ?>accounts/center_patient_ledger">Patient Ledger</a> </li>
                     <!--<li> <a href="<?php echo base_url(); ?>accounts/center_accepted">Accept Billing</a> </li>-->
                     <li> <a href="<?php echo base_url(); ?>accounts/center_reconciliation">Center Reconciliation</a> </li>
                     <li> <a href="<?php echo base_url(); ?>accounts/purchase-orders">Purchase Order</a> </li>
                    <li> <a href="<?php echo base_url(); ?>accounts/purchase-orders-list">Purchase Order List</a> </li>
                    <!-- <li> <a href="<?php echo base_url(); ?>my-approvals">My Approvals</a> </li> -->
                  </ul>
               </li>
               <li>
                  <a href="#" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Manage Billings<span class="fa arrow"></span></a>
                  <ul class="nav nav-second-level">
                     <!-- <li> <a href="<?php echo base_url(); ?>accounts/requests">Billing Requests</a> </li>-->
                     <li> <a href="<?php echo base_url(); ?>accounts/procedure_patients">Procedure Patients</a> </li>
                     <li> <a href="<?php echo base_url(); ?>accounts/investigation_patients">Investigation Patients</a> </li>
                     <li> <a href="<?php echo base_url(); ?>accounts/consultation_patients">Consultation Patients</a> </li>
                     <li> <a href="<?php echo base_url(); ?>accounts/registration_patients">Registration Patients</a> </li>
                     <li> <a href="<?php echo base_url(); ?>accounts/partialpayments_request">Partial Payments Request</a> </li>
                     <?php if(isset($_SESSION['logged_accountant']) && !empty($_SESSION['logged_accountant'])){
                        $center = $_SESSION['logged_accountant']['center'];
                        if($center == 0){
                        ?>
                     <li> <a href="<?php echo base_url(); ?>accounts/partial_payments">Partial Payments</a> </li>
                     <?php } } ?>
                  </ul>
               </li>
               <li>
                  <a href="#" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Reports<span class="fa arrow"></span></a>
                  <ul class="nav nav-second-level">
                     <li> <a href="<?php echo base_url(); ?>accounts/procedure_reports">Procedure</a> </li>
                     <li> <a href="<?php echo base_url(); ?>accounts/investigation_sales">Investigation</a> </li>
                     <li> <a href="<?php echo base_url(); ?>stocks/medicine_stock">Medicine Report (Item Wise)</a> </li>
                     <li> <a href="<?php echo base_url(); ?>accounts/medicine_patients">Medicine Report (Patient Wise)</a> </li>
                     <li> <a href="<?php echo base_url(); ?>stocks/medicine_return_report">Medicine Return Report</a> </li>
                     <li> <a href="<?php echo base_url(); ?>accounts/consultation_reports">Consultation</a> </li>
                     <li> <a href="<?php echo base_url(); ?>accounts/partialpayments_report">Partial</a> </li>
                     <li><a href="<?php echo base_url(); ?>stocks/patient_items">Patient Consumption</a></li>
                     <li><a href="<?php echo base_url(); ?>stocks/all_consumption">All Consumption</a></li>
                     <li> <a href="<?php echo base_url(); ?>stocks/stocks_reports">Live Stocks Report</a> </li>
                  </ul>
               </li>
               <li><a class="waves-effect waves-dark" href="<?php echo base_url(); ?>accounts/reports"><i class="fa fa-sitemap"></i> Revenue Dashboard</a> </li>
               <li>
                  <a href="#" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Mou<span class="fa arrow"></span></a>
                  <ul class="nav nav-second-level">
                     <li> <a href="<?php echo base_url(); ?>accounts/mou">Add Mou</a> </li>
                     <li> <a href="<?php echo base_url(); ?>accounts/moulist">Mou List</a> </li>
                  </ul>
               </li>
               <li>
                  <a href="#" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Cancel<span class="fa arrow"></span></a>
                  <ul class="nav nav-second-level">
                     <li> <a href="<?php echo base_url(); ?>accounts/cancel_consultation_list">Consultation</a> </li>
                     <li> <a href="<?php echo base_url(); ?>accounts/cancel_medicine_list">Medicine</a> </li>
                     <li> <a href="<?php echo base_url(); ?>accounts/cancel_procedure_list">Procedure</a> </li>
                  </ul>
               </li>
               <li>
                  <a href="#" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Product Advisory Fee<span class="fa arrow"></span></a>
                  <ul class="nav nav-second-level">
                     <li> <a href="<?php echo base_url(); ?>accounts/add_training">Add Product Advisory</a> </li>
                     <li> <a href="<?php echo base_url(); ?>accounts/training_list">Product Advisory List</a> </li>
                  </ul>
               </li>
               <li>
                  <a href="#" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Fellowship And Training<span class="fa arrow"></span></a>
                  <ul class="nav nav-second-level">
                     <li> <a href="<?php echo base_url(); ?>accounts/add_fellowship">Fellowship And Training Add</a> </li>
                     <li> <a href="<?php echo base_url(); ?>accounts/fellowship_and_training">Fellowship And Training List</a> </li>
                  </ul>
               </li>
               <li>
                  <a href="#" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Revenue Reports<span class="fa arrow"></span></a>
                  <ul class="nav nav-second-level">
                     <li> <a href="<?php echo base_url(); ?>accounts/consultation_origin">Consultation</a> </li>
                     <li> <a href="<?php echo base_url(); ?>accounts/investigation_origin">Investigation</a> </li>
                     <li> <a href="<?php echo base_url(); ?>accounts/procedure_origin">Procedure</a> </li>
                     <li> <a href="<?php echo base_url(); ?>stocks/medicine_origin">Medicine</a> </li>
                  </ul>
               </li>
               <li> <a href="<?php echo base_url(); ?>accounts/patient_final_billing"><i class="fa fa-sitemap"></i>Patients Final Billing</a> </li>
               <li> <a href="<?php echo base_url(); ?>accounts/wallet_list"><i class="fa fa-sitemap"></i>Refund Amount</a> </li>
               <li> <a href="<?php echo base_url(); ?>accounts/procedure_advice"><i class="fa fa-sitemap"></i>Financial Clearance</a> </li>
               <li><a class="waves-effect waves-dark" href="<?php echo base_url(); ?>procedures"><i class="fa fa-sitemap"></i>Procedures List</a> </li>
               <li><a class="waves-effect waves-dark" href="<?php echo base_url(); ?>stocks/all_center_stocks"><i class="fa fa-sitemap"></i> All Center Stocks</a> </li>
               <li> <a href="<?php echo base_url(); ?>accounts/patient_center_wise_report" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Patient Journey</a></li>
               <li> <a href="<?php echo base_url(); ?>accounts/revenue_potential" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Revenue Potential</a></li>
                <li><a href="<?php echo base_url(); ?>my_appointments" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>My appointments</a></li>
            </ul>
            <?php } ?>
         </div>
      </nav>
      <!-- /. NAV SIDE  -->
      <div id="page-wrapper">
      <?php 
         if(isset($_GET['m']) && !empty($_GET['m'])){
         	echo '<div class="col-sm-12 col-xs-12 '.base64_decode($_GET['t']).'"><h4>'.base64_decode($_GET['m']).'</h4></div>';
         }
         ?>