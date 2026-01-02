<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
   <head>
      <meta charset="utf-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <title>Stock Manager Dashboard</title>
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
            <li><a class="dropdown-button waves-effect waves-dark" href="#!" data-activates="dropdown1"><i class="fa fa-user fa-fw"></i> <b><?php echo $_SESSION['logged_stock_manager']['name']?></b> <i class="material-icons right">arrow_drop_down</i></a></li>
         </ul>
      </nav>
      <!-- Dropdown Structure -->
      <ul id="dropdown1" class="dropdown-content">
         <!-- <li><a href="#"><i class="fa fa-user fa-fw"></i> My Profile</a> </li>
            <li><a href="#"><i class="fa fa-gear fa-fw"></i> Settings</a> </li> -->
         <li><a href="<?php echo base_url(); ?>logout?r=<?php echo base64_encode('logged_stock_manager'); ?>"><i class="fa fa-sign-out fa-fw"></i> Logout</a> </li>
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
             <!--  <li>
                  <a href="#" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Billing Medicine<span class="fa arrow"></span></a>
                  <ul class="nav nav-second-level">
                     <li> <a href="<?php echo base_url(); ?>stocks/add_billing_medicine">Medicine</a> </li>
                     <li> <a href="<?php echo base_url(); ?>stocks/return_billing_medicine">Return Medicine</a> </li>
                     <li> <a href="<?php echo base_url(); ?>accounts/medicine_patients">Sale Report Patient Wise</a> </li>
                     <li> <a href="<?php echo base_url(); ?>stocks/center_medicine_report">Sale Report</a> </li>
                     <li> <a href="<?php echo base_url(); ?>stocks/center_audit_report">Audit Report</a> </li>
                  </ul>
               </li>-->
			   <li> <a href="#" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Manage Appointments<span class="fa arrow"></span></a>
        <ul class="nav nav-second-level">
          <li> <a href="<?php echo base_url(); ?>embryologist_records">My Appointment</a> </li>
        </ul>
      </li>
               <li>
                  <a href="#" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Manage Stocks<span class="fa arrow"></span></a>
                  <ul class="nav nav-second-level">
                     <li> <a href="<?php echo base_url(); ?>stocks_new/center_stocks">All Stocks</a> </li>
                     <li> <a href="<?php echo base_url(); ?>stocks_new/audit_reports" class="waves-effect waves-dark">Audit Report</a></li>

                     <!-- <li> <a href="<?php echo base_url(); ?>stocks/center_stocks">All Stocks</a> </li> -->
                       <!-- <li> <a href="<?php echo base_url(); ?>stocks/center_audit_report">Audit Report</a> </li> -->
                  </ul>
               </li>
               <!-- <li>
                  <a href="#" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Manage Billing Items<span class="fa arrow"></span></a>
                  <ul class="nav nav-second-level">
                     <li> <a href="<?php echo base_url(); ?>stocks/add_billing_item">Add Patient Consumptions</a> </li>
                     <li> <a href="<?php echo base_url(); ?>stocks/patient_items">Patient Consumptions</a> </li>
                  </ul>
               </li> -->
               <li>
                  <a href="#" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i> New Manage Billing Items<span class="fa arrow"></span></a>
                  <ul class="nav nav-second-level">
                     <li> <a href="<?php echo base_url(); ?>stocks_new/add_billing_item">Add Consumptions</a> </li>
                     <li> <a href="<?php echo base_url(); ?>stocks_new/patient_consumption_report">Patient Consumption Report</a> </li>
                     <li> <a href="<?php echo base_url(); ?>stocks_new/patient_consumption_summary">Patient Consumption Summary</a> </li>
                     <li> <a href="<?php echo base_url(); ?>stocks_new/medicine_package">Patient Medicine Package</a> </li>
                     <li> <a href="<?php echo base_url(); ?>stocks_new/all_consumption_report">All Consumption Report</a> </li>
                  </ul>
               </li>
                  
               <li>
                  <a href="#" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Manage Orders<span class="fa arrow"></span></a>
                  <ul class="nav nav-second-level">
                     <li> <a href="<?php echo base_url(); ?>orders/center_order">Order Summary</a> </li>
                  </ul>
               </li>
               <li>
                  <a href="#" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Procedure Reports<span class="fa arrow"></span></a>
                  <ul class="nav nav-second-level">
                     <li> <a href="<?php echo base_url(); ?>accounts/procedure_reports">Procedure Reports</a> </li>
                     <li> <a href="<?php echo base_url(); ?>patients/patients">Patients</a> </li>
					 <li> <a href="<?php echo base_url(); ?>accounts/procedure_reports">Procedure Bill</a> </li>
					 <li> <a href="<?php echo base_url(); ?>accounts/accounts">Procedure Partial Bill</a> </li>
				  </ul>
               </li>
               <li> <a href="#" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Consent Book<span class="fa arrow"></span></a>
          <ul class="nav nav-second-level">
		  <li> <a href="#" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Consent Surrogacy<span class="fa arrow"></span></a>
          <ul class="nav nav-second-level">
            <li> <a href="<?php echo base_url(); ?>doctors/divorce_ewidow">DivorceE widow</a> </li>
			<li> <a href="<?php echo base_url(); ?>doctors/agreement_for_surrogacy">Agreement For Surrogacy</a> </li>
			<li> <a href="<?php echo base_url(); ?>doctors/couple_for_availing_surrogacy">Couple For Availing Surrogacy</a> </li>
			<li> <a href="<?php echo base_url(); ?>doctors/fitness_of_surrogate_mother">Fitness Of Surrogate Mother</a> </li>
			<li> <a href="<?php echo base_url(); ?>doctors/consent_form_for_withdrawal">Consent Form For Withdrawal</a> </li>
			<li> <a href="<?php echo base_url(); ?>doctors/screening_of_the_surrogate">Screening Of The Surrogate</a> </li>
			<li> <a href="<?php echo base_url(); ?>doctors/acknowledgment">Acknowledgment</a> </li>
          </ul>
        </li>
		 <li> <a href="#" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Admission Form<span class="fa arrow"></span></a>
          <ul class="nav nav-second-level">
            <li> <a href="<?php echo base_url(); ?>doctors/ipd_admission_form">Ipd Admission Form</a> </li>
			<li> <a href="<?php echo base_url(); ?>doctors/anaethesia_consent_form">Anaethesia Consent Form</a> </li>
			<li> <a href="<?php echo base_url(); ?>doctors/ot_consent_form">Ot Consent Form</a> </li>
          </ul>
        </li>
		<li> <a href="<?php echo base_url(); ?>doctors/ovarian_stem_cell" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Ovarian Stem Cell</a></li>
		    
    <li> <a href="#" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Incorporated<span class="fa arrow"></span></a>
          <ul class="nav nav-second-level">
            <li> <a href="<?php echo base_url(); ?>doctors/consent_for_blastocyst">Consent For Blastocyst</a> </li>
			<li> <a href="<?php echo base_url(); ?>doctors/consent_for_embryo_glue">Consent For Embryo Glue</a> </li>
			<li> <a href="<?php echo base_url(); ?>doctors/consent_for_icsi">Consent For Icsi</a> </li>
			<li> <a href="<?php echo base_url(); ?>doctors/consent_for_lah">Consent For Lah</a> </li>
			<li> <a href="<?php echo base_url(); ?>doctors/consent_for_microfluidics_sperm">Consent For Microfluidics Sperm</a> </li>
      <li> <a href="<?php echo base_url(); ?>doctors/consent_for_pgt">Consent For Pgt</a> </li>
      <li> <a href="<?php echo base_url(); ?>doctors/consent_for_sperm_mobil">Consent For Sperm Mobil</a> </li>
      <li> <a href="<?php echo base_url(); ?>doctors/consent_for_thawing_of_gametes">Consent For Thawing Of Gametes</a> </li>
         </ul>
   </li>  

    <li style="height:200px"> <a href="#" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Art Consent<span class="fa arrow"></span></a>
          <ul class="nav nav-second-level">
            <li> <a href="<?php echo base_url(); ?>doctors/consent_form">couple/women</a> </li>
            <li> <a href="<?php echo base_url(); ?>doctors/intrauterine_insemination">IUI husbands semen</a> </li>
            <li> <a href="<?php echo base_url(); ?>doctors/form8">IUI donors  semen</a> </li>
            <li> <a href="<?php echo base_url(); ?>doctors/form8_single_woman">IUI donors  semen single woman</a> </li>
            <li> <a href="<?php echo base_url(); ?>doctors/form9">Embryo Freezing</a> </li>
            <li> <a href="<?php echo base_url(); ?>doctors/form10">Sperms /oocytes Freezing</a> </li>
            <li> <a href="<?php echo base_url(); ?>doctors/form11">Parental Freezing consent minor</a> </li>
            <li> <a href="<?php echo base_url(); ?>doctors/form12">Oocyte retrieval</a> </li>
            <li> <a href="<?php echo base_url(); ?>doctors/consent_for_embryo_transfer">Embryo transfer</a> </li>
            <li> <a href="<?php echo base_url(); ?>doctors/form13">Consent donor of eggs</a> </li>
            <li> <a href="<?php echo base_url(); ?>doctors/form15">Consent donor of sperm</a> </li>
            <li> <a href="<?php echo base_url(); ?>doctors/cfpros">Posthumous Retrieval of Sperm</a> </li>
            <li> <a href="<?php echo base_url(); ?>doctors/form18">Consent form for withdrawal</a> </li>
            <li> <a href="<?php echo base_url(); ?>doctors/risk_consent">Process ,risk and consent ART</a> </li>
            <li> <a href="<?php echo base_url(); ?>doctors/couple_donor_egg">Recipient couple Donor egg</a> </li>
            <li> <a href="<?php echo base_url(); ?>doctors/consent_for_semen_collection">Instructions & consent semen collection</a> </li>
            <li> <a href="<?php echo base_url(); ?>doctors/micro_tese">PESA/TESA/TESE/MICRO TESE</a> </li>
            <li> <a href="<?php echo base_url(); ?>doctors/ovarian_platelet_rich_plasma">Ovarian PRP</a> </li>
            <li> <a href="<?php echo base_url(); ?>doctors/uterine_platelet_rich_plasma">Uterine PRP</a> </li>
            <li> <a href="<?php echo base_url(); ?>doctors/testicular_platelet_rich_plasma">Testicular PRP</a> </li>
            <li> <a href="<?php echo base_url(); ?>doctors/patient_testimonial">Testimonial</a> </li>
            <li> <a href="<?php echo base_url(); ?>doctors/low_ovarian_reserve_females">Stimulation of low ovarian reserve females</a> </li>
          </ul>
        </li>
		
          </ul>
        </li>
  <li> <a href="<?php echo base_url(); ?>doctors/pcp_ndt" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>PCPNDT</a></li>
 <li> <a href="<?php echo base_url(); ?>accounts/clinical_reports" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Clinical Reports</a></li>
 <li> <a href="<?php echo base_url(); ?>doctors/patient_general_instructions" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>General Instructions</a></li>
  <li> <a href="<?php echo base_url(); ?>accounts/patient_financial_clearance" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Procedure Financial Clearance</a></li>
     
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
