<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Biling Manager Dashboard</title>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/materialize/css/materialize.min.css" media="screen,projection" />
<!-- Bootstrap Styles-->
<link href="<?php echo base_url();?>assets/css/bootstrap.min.css" rel="stylesheet" />
<!-- FontAwesome Styles-->
<link href="<?php echo base_url();?>assets/css/font-awesome.css" rel="stylesheet" />
<!-- Morris Chart Styles-->
<link href="<?php echo base_url();?>assets/css/morris/morris-0.4.3.min.css" rel="stylesheet" />
<!-- Custom Styles-->
<link href="<?php echo base_url();?>assets/css/custom-styles.css" rel="stylesheet" />
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<!-- jQuery Js -->
<link rel="stylesheet" href="<?php echo base_url();?>assets/css/jquery-ui.css">
<!--<script src="<?php echo base_url();?>assets/js/jquery-1.12.4.js"></script>-->
<script src="<?php echo base_url();?>assets/js/jquery.min.js"></script>
<script src="<?php echo base_url();?>assets/js/jquery-ui.js"></script>
<script src="<?php echo base_url();?>assets/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url();?>assets/css/select2.min.css">
<script src="<?php echo base_url();?>assets/js/select2.min.js"></script>
<!-- Google Fonts-->
<link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />

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
    <li><a class="dropdown-button waves-effect waves-dark" href="#!" data-activates="dropdown1"><i class="fa fa-user fa-fw"></i> <b><?php echo $_SESSION['logged_billing_manager']['name']?></b> <i class="material-icons right">arrow_drop_down</i></a></li>
  
  </ul>
</nav>
<!-- Dropdown Structure -->
<ul id="dropdown1" class="dropdown-content">
  <!-- <li><a href="#"><i class="fa fa-user fa-fw"></i> My Profile</a> </li>
  <li><a href="#"><i class="fa fa-gear fa-fw"></i> Settings</a> </li> -->
  <li><a href="<?php echo base_url(); ?>logout?r=<?php echo base64_encode('logged_billing_manager'); ?>"><i class="fa fa-sign-out fa-fw"></i> Logout</a> </li>
<li><a href="<?php echo base_url(); ?>employees/edit_center?employee_number=<?php echo $_SESSION['logged_billing_manager']['employee_number']?>"><i class="fa fa-user fa-fw"></i>Change Center </a></li>
 
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
            <li><a href="<?php echo base_url(); ?>pending-consultation-billing">Pending Billings</a></li>
            <li><a href="<?php echo base_url(); ?>partial-consultation">Partial Consultation</a></li>
        </ul>
      </li>
      
      <!-- NEW MODERN APPOINTMENTS SECTION -->
      <!-- <li> <a href="#" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Modern Appointments<span class="fa arrow"></span></a>
        <ul class="nav nav-second-level">
	        <li><a href="<?php echo base_url(); ?>modern-appointments/index"><i class="fa fa-sitemap"></i>Appointments list</a></li>
	        <li><a href="<?php echo base_url(); ?>modern-appointments/create"><i class="fa fa-plus-circle"></i> Book Appointment</a></li>
	        <li><a href="<?php echo base_url(); ?>modern-appointments/calendar"><i class="fa fa-calendar"></i> Calendar View</a></li>
	        <li><a href="<?php echo base_url(); ?>modern-appointments/reports"><i class="fa fa-sitemap"></i>Reports</a></li>
	        <li><a href="<?php echo base_url(); ?>modern-appointments/settings"><i class="fa fa-cog"></i> Settings</a></li>
        </ul>
      </li> -->
      
      <li> <a href="#" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Manage Billing<span class="fa arrow"></span></a>
        <ul class="nav nav-second-level">
          <li> <a href="<?php echo base_url(); ?>billings/consultation_billings">Consultation Patients</a> </li>
          <li> <a href="<?php echo base_url(); ?>billings/registation_billings">Registation Patients</a> </li>
          <li> <a href="<?php echo base_url(); ?>billings/procedure_billings">Procedure Patients</a> </li>
          <li> <a href="<?php echo base_url(); ?>billings/investigation_billings">Investigation Patients</a> </li>
          <li> <a href="<?php echo base_url(); ?>after-consultation">After consultation</a> </li>
          <li> <a href="<?php echo base_url(); ?>billings/payments">Billing payment</a> </li>
          <li> <a href="<?php echo base_url(); ?>billings/discount_lists">Discount codes</a> </li>
          <li> <a href="<?php echo base_url(); ?>billing_noreceipt_procedure">Procedure Receipts</a> </li>
          <li> <a href="<?php echo base_url(); ?>billing_noreceipt_patient_payments">Partial Payments Receipts</a> </li>
          <li> <a href="<?php echo base_url(); ?>billing_noreceipt_investigation">Investigation Receipts</a> </li>
          <li> <a href="<?php echo base_url(); ?>billing-noreceipt">Consultation Receipts</a> </li>
        </ul>
      </li>
      <li> <a href="#" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Ledger<span class="fa arrow"></span></a>
        <ul class="nav nav-second-level">
              <li> <a href="<?php echo base_url(); ?>accounts/accounts">Account Ledger</a> </li>
        </ul>
      </li>
      <li> <a href="#" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Manage Patients<span class="fa arrow"></span></a>
        <ul class="nav nav-second-level">
            <li> <a href="<?php echo base_url(); ?>patients/patients">Patients</a></li>
            <li> <a href="<?php echo base_url(); ?>check_reports">Check reports</a> </li>
        </ul>
      </li>
	  <li> <a href="#" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Reports<span class="fa arrow"></span></a>
        <ul class="nav nav-second-level">
          <li> <a href="<?php echo base_url(); ?>accounts/procedure_reports">Procedure</a> </li>
		  <li> <a href="<?php echo base_url(); ?>accounts/investigation_sales">Investigation</a> </li>
		  <li> <a href="<?php echo base_url(); ?>accounts/medicine_patients">Medicine</a> </li>
		  <li> <a href="<?php echo base_url(); ?>accounts/consultation_reports">Consultation</a> </li>
		  <li> <a href="<?php echo base_url(); ?>accounts/partialpayments_report">Partial</a> </li>
        </ul>
      </li>
	  <li> <a href="#" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Billing Medicine<span class="fa arrow"></span></a>
        <ul class="nav nav-second-level">
          <!--<li> <a href="<?php echo base_url(); ?>stocks/add_billing_medicine">Medicine</a> </li>-->
		  <li> <a href="<?php echo base_url(); ?>stocks/return_billing_medicine">Return Medicine</a> </li>
		   <li> <a href="<?php echo base_url(); ?>stocks/medicine_return_report">Medicine Return Report</a> </li>
		  <li> <a href="<?php echo base_url(); ?>accounts/medicine_patients">Sale Report Patient Wise</a> </li>
          <li> <a href="<?php echo base_url(); ?>stocks/center_medicine_report">Sale Report</a> </li>
        </ul>
      </li>
	  <li> <a href="#" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Manage Stocks<span class="fa arrow"></span></a>
        <ul class="nav nav-second-level">
          <li> <a href="<?php echo base_url(); ?>stocks/center_stocks">All Stocks</a> </li>
        </ul>
      </li>
      <li> <a href="#" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Manage Orders<span class="fa arrow"></span></a>
        <ul class="nav nav-second-level">
          <li> <a href="<?php echo base_url(); ?>orders/center_order">Order Summary</a> </li>
        </ul>
      </li>
	  
	  <li> <a href="#" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Cancel<span class="fa arrow"></span></a>
        <ul class="nav nav-second-level">
		  <li> <a href="<?php echo base_url(); ?>accounts/cancel_consultation_list">Consultation</a> </li>
		  <li> <a href="<?php echo base_url(); ?>accounts/cancel_medicine_list">Medicine</a> </li>
          <li> <a href="<?php echo base_url(); ?>accounts/cancel_procedure_list">Procedure</a> </li>
		</ul>
      </li>
	
	  <li> <a href="#" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Freezing<span class="fa arrow"></span></a>
        <ul class="nav nav-second-level">
		  <li> <a href="<?php echo base_url(); ?>accounts/freezing">Freezing Ledger</a> </li>
		  <li> <a href="<?php echo base_url(); ?>accounts/freezing_reports">Freezing Reports</a> </li>
          <li> <a href="<?php echo base_url(); ?>accounts/freezing_renewal">Freezing Renewal</a> </li>
		  <li> <a href="<?php echo base_url(); ?>doctors/freezingmo">Cryopreservation</a> </li>
		  <li> <a href="<?php echo base_url(); ?>doctors/discard">Discard</a> </li>
        </ul>
      </li>
		<li> <a href="<?php echo base_url(); ?>accounts/admission_form_list"><i class="fa fa-sitemap"></i>Admission Form</a> </li>
		<li> <a href="<?php echo base_url(); ?>investigation/patient_investigation_list" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Investigation Reports</a></li>
	    <li> <a href="<?php echo base_url(); ?>doctors/doctor_patient" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>OPD Register</a></li>
      <li> <a href="<?php echo base_url(); ?>stocks/center_audit_report" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Audit Report</a></li>
         <li> <a href="#" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Revenue Reports<span class="fa arrow"></span></a>
        <ul class="nav nav-second-level">
          <li> <a href="<?php echo base_url(); ?>accounts/consultation_origin">Consultation</a> </li>
          <li> <a href="<?php echo base_url(); ?>accounts/investigation_origin">Investigation</a> </li>
		  <li> <a href="<?php echo base_url(); ?>accounts/procedure_origin">Procedure</a> </li>
		  <li> <a href="<?php echo base_url(); ?>stocks/medicine_origin">Medicine</a> </li>
		</ul>
      </li>
	  <li> <a href="<?php echo base_url(); ?>doctors/patient_general_instructions" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>General Instructions</a></li>
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
