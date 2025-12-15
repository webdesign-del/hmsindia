<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Admin Dashboard</title>
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
<script src="<?php echo base_url();?>assets/js/jquery-1.12.4.js"></script>
<script src="<?php echo base_url();?>assets/js/jquery-ui.js"></script>
<!-- Google Fonts-->
<link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
<link rel="stylesheet" href="<?php echo base_url();?>assets/css/Lightweight-Chart/cssCharts.css">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<link rel="stylesheet" href="<?php echo base_url();?>assets/css/bootstrap-multiselect.css">

<!-- TimePicker -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
<style type="text/css">
.navbar-side {
    position: absolute!important;
   }
</style>
</head>
<body>
<div id="wrapper">
<nav class="navbar navbar-default top-navbar" role="navigation">
  <div class="navbar-header">
    <button type="button" class="navbar-toggle waves-effect waves-dark" data-toggle="collapse" data-target=".sidebar-collapse"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
    <a class="navbar-brand waves-effect waves-dark logo_section" href="<?php echo base_url(); ?>"><img src="<?php echo base_url(); ?>assets/images/IndiaIVFClinic_logo.png" /></a>
    <div id="sideNav" href=""><i class="material-icons dp48">toc</i></div>
  </div>
  <?php $notice = get_admin_notification(); ?>
  <ul class="nav navbar-top-links navbar-right">
    <li><a class="dropdown-button waves-effect waves-dark" href="#!" data-activates="dropdown4">
    	<?php if($notice['count'] > 0){
			echo '<span class="notice_count">'.$notice['count'].'</span>';
        } ?>
	    <i class="fa fa-bell fa-fw" aria-hidden="true"></i> <i class="material-icons right">arrow_drop_down</i></a>
    </li>
    <li><a class="dropdown-button waves-effect waves-dark" href="#!" data-activates="dropdown1"><i class="fa fa-user fa-fw"></i> <b><?php echo $_SESSION['logged_administrator']['name']?></b> <i class="material-icons right">arrow_drop_down</i></a></li>
  </ul>
</nav>
<!-- Dropdown Structure -->
<ul id="dropdown1" class="dropdown-content">
  <li><a href="<?php echo base_url('settings/'); ?>"><i class="fa fa-gear fa-fw"></i> Settings</a> </li>
  <li><a href="<?php echo base_url('password/'); ?>"><i class="fa fa-gear fa-fw"></i> Change Password</a> </li>
  <li><a href="<?php echo base_url(); ?>logout?r=<?php echo base64_encode('logged_administrator'); ?>"><i class="fa fa-sign-out fa-fw"></i> Logout</a> </li>
</ul>
<ul id="dropdown4" class="dropdown-content dropdown-tasks w250 taskList">
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
      <!-- <li> <a href="#" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Manage Stocks<span class="fa arrow"></span></a>
        <ul class="nav nav-second-level">
          <li> <a href="<?php echo base_url(); ?>stocks">All Central Stocks</a> </li>
          <li> <a href="<?php echo base_url(); ?>stocks/all_center_stocks">All Centre Stocks</a> </li>
          <li> <a href="<?php echo base_url(); ?>stocks/medicine_stock">Cash Medicine Sale Report</a> </li>
          <li> <a href="<?php echo base_url(); ?>stocks/categories">Categories</a></li>
          <li> <a href="<?php echo base_url(); ?>vendors">All Vendors</a> </li>
          <li> <a href="<?php echo base_url(); ?>brands">All Brands</a> </li>
          <li> <a href="<?php echo base_url(); ?>stocks/generic">Generic Name</a> </li>
          <li> <a href="<?php echo base_url(); ?>stocks/stocks_reports">Live Stocks Report</a> </li>
          <li> <a href="<?php echo base_url(); ?>stocks/all_audit_report">All Audit Report</a> </li>
          <li> <a href="<?php echo base_url(); ?>stocks/add_invoice">Add Invoice</a> </li>
          <li> <a href="<?php echo base_url(); ?>stocks/invoice_list">Invoice List</a> </li>
          <li> <a href="<?php echo base_url(); ?>add-medicine">Add Medicine</a> </li>
          <li> <a href="<?php echo base_url(); ?>medicine">Medicine List</a> </li>
        </ul>
      </li> -->
      <li> <a href="#" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Modern Stock Management<span class="fa arrow"></span></a>
        <ul class="nav nav-second-level">
          <li> <a href="<?php echo base_url(); ?>stocks_new/dashboard"> Dashboard</a> </li>
          <li> <a href="<?php echo base_url(); ?>stocks_new/medicines"> Medicines</a> </li>
          <li> <a href="<?php echo base_url(); ?>stocks_new/batches"> Batches</a> </li>
          <li> <a href="<?php echo base_url(); ?>stocks_new/stock_levels"> Stock Levels</a> </li>
          <li> <a href="<?php echo base_url(); ?>stocks_new/central_stocks"> Central Stocks</a> </li>
          <li> <a href="<?php echo base_url(); ?>stocks_new/center_stocks"> Center Stocks</a> </li>
          <li> <a href="<?php echo base_url(); ?>stocks_new/transfers"> Transfers</a> </li>
          <li> <a href="<?php echo base_url(); ?>stocks_new/sales"> Sales</a> </li>
          <li> <a href="<?php echo base_url(); ?>stocks_new/reports"> Reports</a> </li>
        </ul>
      </li>
      <li> <a href="<?php echo base_url(); ?>accounts/procedure_advice"><i class="fa fa-sitemap"></i>Financial Clearance</a> </li>
	  <li> <a href="#" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Revenue Reports<span class="fa arrow"></span></a>
        <ul class="nav nav-second-level">
          <li> <a href="<?php echo base_url(); ?>accounts/consultation_origin">Consultation</a> </li>
          <li> <a href="<?php echo base_url(); ?>accounts/investigation_origin">Investigation</a> </li>
		  <li> <a href="<?php echo base_url(); ?>accounts/procedure_origin">Procedure</a> </li>
		  <!-- <li> <a href="<?php echo base_url(); ?>stocks/medicine_origin">Medicine</a> </li> -->
		</ul>
      </li>
      <li> <a href="#" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Manage Centers<span class="fa arrow"></span></a>
        <ul class="nav nav-second-level">
          <li> <a href="<?php echo base_url(); ?>centers">All Centers</a> </li>
          <li> <a href="<?php echo base_url(); ?>centers/add">Add Center</a> </li>
        </ul>
      </li>
      <li> <a href="#" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Manage Camps<span class="fa arrow"></span></a>
        <ul class="nav nav-second-level">
          <li> <a href="<?php echo base_url(); ?>camps">All Camps</a> </li>
          <li> <a href="<?php echo base_url(); ?>camps/add">Add Camp</a> </li>
        </ul>
      </li>
      <li> <a href="#" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Manage Employees<span class="fa arrow"></span></a>
        <ul class="nav nav-second-level">
          <li> <a href="<?php echo base_url(); ?>employees">All Employees</a> </li>
          <li> <a href="<?php echo base_url(); ?>employees/add">Add Employee</a> </li>
        </ul>
      </li>
      <li> <a href="#" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Manage Procedures<span class="fa arrow"></span></a>
        <ul class="nav nav-second-level">
		  <li> <a href="<?php echo base_url(); ?>procedures/package">All Package</a> </li>
          <li> <a href="<?php echo base_url(); ?>procedures/add_package">Add Package</a> </li>
          <li> <a href="<?php echo base_url(); ?>procedures">All Procedures</a> </li>
          <!-- <li> <a href="<?php echo base_url(); ?>procedures/parent_procedures">Parent Procedure</a> </li> -->
          <li> <a href="<?php echo base_url(); ?>procedures/add">Add Procedure</a> </li>
          <li> <a href="<?php echo base_url(); ?>procedures/forms">All Forms</a> </li>
          <li> <a href="<?php echo base_url(); ?>procedures/add_form">Add Forms</a> </li>
		  <li> <a href="<?php echo base_url(); ?>procedures/form_relationship">Procedure Forms Relations</a> </li>
        </ul>
      </li>
      <li> <a href="#" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Manage Consultation<span class="fa arrow"></span></a>
        <ul class="nav nav-second-level">
          <li> <a href="<?php echo base_url(); ?>procedures/ids">All Consultation ID's</a> </li>
          <li> <a href="<?php echo base_url(); ?>procedures/add_id">Add Consultation ID</a> </li>
        </ul>
      </li>
	   <li> <a href="#" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Manage Master Investigations<span class="fa arrow"></span></a>
        <ul class="nav nav-second-level">
          <li> <a href="<?php echo base_url(); ?>investigation/master_investigation">All Master Investigations</a> </li>
          <li> <a href="<?php echo base_url(); ?>investigation/add_master">Add Master Investigation</a> </li>
        </ul>
      </li>
      <li> <a href="#" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Manage Investigations<span class="fa arrow"></span></a>
        <ul class="nav nav-second-level">
          <li> <a href="<?php echo base_url(); ?>investigation/investigation">All Investigations</a> </li>
          <li> <a href="<?php echo base_url(); ?>investigation/add">Add Investigation</a> </li>
        </ul>
      </li>
       <li> <a href="#" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Manage Doctors<span class="fa arrow"></span></a>
        <ul class="nav nav-second-level">
          <li> <a href="<?php echo base_url(); ?>doctors/doctors">All Doctors</a> </li>
          <li> <a href="<?php echo base_url(); ?>doctors/add">Add Doctor</a> </li>
          <li> <a href="<?php echo base_url(); ?>junior-doctors">Junior Doctors</a> </li>
        </ul>
      </li>
      <li> <a href="#" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Manage Accounts<span class="fa arrow"></span></a>
        <ul class="nav nav-second-level">
              <li> <a href="<?php echo base_url(); ?>accounts/accounts">Account Ledger</a> </li>
              <li> <a href="<?php echo base_url(); ?>accounts/patient_ledger">Patient Ledger</a> </li>
              <li> <a href="<?php echo base_url(); ?>accounts/reconciliation">Center Reconciliation</a> </li>
              <li> <a href="<?php echo base_url(); ?>accounts/purchase-orders">Purchase Order</a> </li>
              <li> <a href="<?php echo base_url(); ?>accounts/purchase-orders-list">Purchase Order List</a> </li>
              <!-- <li> <a href="<?php echo base_url(); ?>my-approvals">My Approvals</a> </li> -->
        </ul>
      </li>
      <li> <a href="#" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Manage Billing<span class="fa arrow"></span></a>
        <ul class="nav nav-second-level">
              <!--<li> <a href="<?php echo base_url(); ?>billings/all_billings">All billings</a> </li>-->
			   <li> <a href="<?php echo base_url(); ?>accounts/procedure_billings">Procedure Billings</a> </li>
		       <li> <a href="<?php echo base_url(); ?>accounts/investigation_billings">Investigation Billings</a> </li>
		       <li> <a href="<?php echo base_url(); ?>accounts/consultation_billings">Consultation Billings</a> </li>
              <li> <a href="<?php echo base_url(); ?>billings/billing_discount">Billing discount List</a> </li>
              <li> <a href="<?php echo base_url(); ?>doctor-consultations">Doctor's Consultation</a> </li>
        </ul>
      </li>
      <!-- <li> <a href="#" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Manage Orders<span class="fa arrow"></span></a>
        <ul class="nav nav-second-level">
          <li> <a href="<?php echo base_url(); ?>orders/orders">Centre requisition</a> </li>
          <li> <a href="<?php echo base_url(); ?>orders/my_orders">Orders</a> </li>
          <li> <a href="<?php echo base_url(); ?>orders/purchase_orders_list">Purchase orders</a> </li>
		      <li> <a href="<?php echo base_url(); ?>orders/purchase_internal_orders_list">Internal orders</a> </li>
        </ul>
      </li> -->
      <!-- purchase order module  -->
       <li> <a href="#" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Manage  Orders<span class="fa arrow"></span></a>
        <ul class="nav nav-second-level">
          <li> <a href="<?php echo base_url(); ?>new_purchase_orders">New Purchase Orders</a> </li>
          <li> <a href="<?php echo base_url(); ?>new_purchase_orders/add">Add New PO</a> </li>
          <li> <a href="<?php echo base_url(); ?>new_purchase_orders/status">Pending Purchase Orders</a> </li>
        </ul>
      </li>
      <li> <a href="#" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Manage Patients<span class="fa arrow"></span></a>
        <ul class="nav nav-second-level">
            <li> <a href="<?php echo base_url(); ?>patients/patients">Patients</a></li>
        </ul>
      </li>
	  <li> <a href="#" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Freezing Renewal<span class="fa arrow"></span></a>
        <ul class="nav nav-second-level">
           <li> <a href="<?php echo base_url(); ?>accounts/freezing_renewal">Freezing Renewal</a> </li>
          <li> <a href="<?php echo base_url(); ?>doctors/freezingmo">Cryopreservation</a> </li>
          <li> <a href="<?php echo base_url(); ?>doctors/discard">Discard</a> </li>
        </ul>
      </li>
	   <li> <a href="#" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Reports<span class="fa arrow"></span></a>
        <ul class="nav nav-second-level">
           <li> <a href="<?php echo base_url(); ?>accounts/procedure_reports">Procedure</a> </li>
		   <li> <a href="<?php echo base_url(); ?>accounts/investigation_sales">Investigation</a> </li>
		   <!-- <li> <a href="<?php echo base_url(); ?>stocks/medicine_stock">Medicine Report (Item Wise)</a> </li> -->
		   <li> <a href="<?php echo base_url(); ?>accounts/medicine_patients">Medicine Report (Patient Wise)</a> </li>
		   <li> <a href="<?php echo base_url(); ?>accounts/consultation_reports">Consultation</a> </li>
		   <li> <a href="<?php echo base_url(); ?>accounts/partialpayments_report">Partial</a> </li>
		   <!-- <li> <a href="<?php echo base_url(); ?>stocks/patient_items">Patient consumption</a></li> -->
                            <li> <a href="<?php echo base_url(); ?>stocks_new/patient_consumption_report">Patient Consumption Report</a> </li>
		   <!-- <li> <a href="<?php echo base_url(); ?>stocks/consumption_price">All Consumption Report</a> </li> -->
        </ul>
      </li>
	  <li> <a href="#" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Clinical Reports<span class="fa arrow"></span></a>
        <ul class="nav nav-second-level">
           <li> <a href="<?php echo base_url(); ?>accounts/clinical_reports">Clinical Reports List</a> </li>
		   <li> <a href="<?php echo base_url(); ?>accounts/updatereports_admin">Clinical Report Monthly</a> </li>
		   <li> <a href="<?php echo base_url(); ?>accounts/add_clinical_reports">Add Clinical Center</a> </li>
		</ul>
      </li>
	  <li class=""> <a href="<?php echo base_url(); ?>doctors/pcp_ndt" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>PCPNDT</a></li>
	  <li><a class="waves-effect waves-dark" href="<?php echo base_url(); ?>accounts/reports"><i class="fa fa-sitemap"></i> Revenue Dashboard</a> </li>
      <li> <a href="#" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Mou<span class="fa arrow"></span></a>
        <ul class="nav nav-second-level">
           <li> <a href="<?php echo base_url(); ?>accounts/mou">Add Mou</a> </li>
		   <li> <a href="<?php echo base_url(); ?>accounts/moulist">Mou List</a> </li>
		</ul>
      </li>
	   <li> <a href="#" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Liason<span class="fa arrow"></span></a>
        <ul class="nav nav-second-level">
           <li> <a href="<?php echo base_url(); ?>accounts/liason">Add Liason</a> </li>
		   <li> <a href="<?php echo base_url(); ?>accounts/liasonlist">Liason List</a> </li>
		</ul>
      </li>
	  <li> <a href="#" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Document<span class="fa arrow"></span></a>
        <ul class="nav nav-second-level">
           <li> <a href="<?php echo base_url(); ?>accounts/indiaivf_document">Add Document</a> </li>
		   <li> <a href="<?php echo base_url(); ?>accounts/indiaivf_document_list">Document List</a> </li>
		</ul>
      </li>
	  <li> <a href="#" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Cancel<span class="fa arrow"></span></a>
        <ul class="nav nav-second-level">
		  <li> <a href="<?php echo base_url(); ?>accounts/cancel_consultation_list">Consultation</a> </li>
		  <li> <a href="<?php echo base_url(); ?>accounts/cancel_medicine_list">Medicine</a> </li>
          <li> <a href="<?php echo base_url(); ?>accounts/cancel_procedure_list">Procedure</a> </li>
		</ul>
      </li>
	 <li> <a href="<?php echo base_url(); ?>doctors/patient_duration" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>OPD Consltation Duration</a></li>
	 <li> <a href="#" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Donor<span class="fa arrow"></span></a>
        <ul class="nav nav-second-level">
          <li> <a href="<?php echo base_url(); ?>accounts/add_donor">Add Donor</a> </li>
		  <li> <a href="<?php echo base_url(); ?>accounts/donor_list">Donor List</a> </li>
		</ul>
      </li>
    <li><a href="<?php echo base_url(); ?>patients/timeline_view" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Patient Agent</a></li>
	  <li> <a href="<?php echo base_url(); ?>billings/forma_invoice_list" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Forma Invoice</a></li>
	  <li> <a href="<?php echo base_url(); ?>doctors/doctor_patient" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>OPD Register</a></li>
	  <li> <a href="<?php echo base_url(); ?>accounts/revenue_potential" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Revenue Potential</a></li>
	  <li> <a href="<?php echo base_url(); ?>my_appointments" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Appointments</a></li>
	  <li> <a href="<?php echo base_url(); ?>accounts/patient_center_wise_report" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Patient Report</a></li>
	  <li> <a href="<?php echo base_url(); ?>logs" class="waves-effect waves-dark"><i class="fa fa-list-alt"></i>System Logs</a></li>
     <li> <a href="<?php echo base_url(); ?>patients/timeline_view" class="waves-effect waves-dark"><i class="fa fa-list-alt"></i>Consultation Timeline</a></li>
	  <li> <a href="#" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Doctor Referral<span class="fa arrow"></span></a>
        <ul class="nav nav-second-level">
          <li> <a href="<?php echo base_url(); ?>accounts/add_doctor_referral">Add Doctor Referral</a> </li>
		  <li> <a href="<?php echo base_url(); ?>accounts/doctor_referral_list">Doctor Referral List</a> </li>
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
