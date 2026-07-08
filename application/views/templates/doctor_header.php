<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Doctor Dashboard</title>
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
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<!-- jQuery Js -->
<link rel="stylesheet" href="<?php echo base_url();?>assets/css/jquery-ui.css">
<link rel="stylesheet" href="<?php echo base_url();?>assets/css/select2.min.css">

<link rel="stylesheet" href="<?php echo base_url();?>assets/css/bootstrap-multiselect.css">

<script src="<?php echo base_url();?>assets/js/jquery-1.12.4.js"></script>
<script src="<?php echo base_url();?>assets/js/jquery-ui.js"></script>
<script src="<?php echo base_url();?>assets/js/select2.min.js"></script>



<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<!-- Google Fonts-->
<link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
<link rel="stylesheet" href="<?php echo base_url();?>assets/css/Lightweight-Chart/cssCharts.css">
<style typ="text/css">
ul.nav.nav-second-level.collapse.in li {
    font-size: 13px;
    margin-left: -5px;
}
.blink-text {
    animation: textBlink 10.8s linear infinite;
    font-weight: bold;
    font-size: 18px;
    color: #ce5679; /* 💡 आप अपनी पसंद का कलर (जैसे रेड या ऑरेंज) यहाँ सेट कर सकते हैं */
}

@keyframes textBlink {
    0% { opacity: 1; }
    50% { opacity: 0; }
    100% { opacity: 1; }
}
</style>
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
  <?php //$notice = get_center_notification(); ?>
  <ul class="nav navbar-top-links navbar-right">
     <li>
    <a class="dropdown-button waves-effect waves-dark" href="#!" data-activates="dropdown4">
        <i class="fa fa-map-marker fa-fw" aria-hidden="true"></i>
        <?php 
// 1. Session se active center id nikali
$center_id_session = '';
if(isset($_SESSION['logged_doctor']['center'])) {
    $center_id_session = $_SESSION['logged_doctor']['center'];
} elseif(isset($_SESSION['logged_administrator']['center'])) {
    $center_id_session = $_SESSION['logged_administrator']['center'];
}

// 2. Database se center row nikali
$center_query = $this->db->get_where('hms_centers', array('center_number' => $center_id_session))->row_array();

$center_name = isset($center_query['center_name']) ? $center_query['center_name'] : 'Unknown Center';

// 3. 🎯 LOGO KO GLOBAL SESSION ME SET KARIYE
$_SESSION['global_center_logo'] = isset($center_query['upload_photo_1']) ? $center_query['upload_photo_1'] : '';
?>

<span class="blink-text"><?php echo $center_name; ?></span>

    </a>
</li>
    <li><a class="dropdown-button waves-effect waves-dark" href="#!" data-activates="dropdown4">
    	<?php /*if($notice['count'] > 0){
			echo '<span class="notice_count">'.$notice['count'].'</span>';
        }*/ ?>
	    <i class="fa fa-bell fa-fw" aria-hidden="true"></i> <i class="material-icons right">arrow_drop_down</i></a>
     </li>
    <li><a class="dropdown-button waves-effect waves-dark" href="#!" data-activates="dropdown1"><i class="fa fa-user fa-fw"></i> <b><?php echo $_SESSION['logged_doctor']['name']?></b> <i class="material-icons right">arrow_drop_down</i></a></li>
  
  </ul>
</nav>
<!-- Dropdown Structure -->
<ul id="dropdown1" class="dropdown-content">
  <!-- <li><a href="#"><i class="fa fa-user fa-fw"></i> My Profile</a> </li>
  <li><a href="#"><i class="fa fa-gear fa-fw"></i> Settings</a> </li> -->
  <li><a href="<?php echo base_url(); ?>logout?r=<?php echo base64_encode('logged_doctor'); ?>"><i class="fa fa-sign-out fa-fw"></i> Logout</a> </li>
<li><a href="<?php echo base_url(); ?>employees/edit_doctor_center?ID=<?php echo $_SESSION['logged_doctor']['doctor_id']?>"><i class="fa fa-user fa-fw"></i>Change Center </a></li>
 
</ul>
<ul id="dropdown4" class="dropdown-content dropdown-tasks w250 taskList notification_list">
	 <?php //var_dump($notice);die;
		/*if($notice['count'] > 0){
			echo $notice['html'];
		}*/
	?>
</ul>
<!--/. NAV TOP  -->
<nav class="navbar-default navbar-side" style="overflow:scroll;height:100%" role="navigation">
  <div class="sidebar-collapse">
    <ul class="nav" id="main-menu">
      <li> <a class="active-menu waves-effect waves-dark" href="<?php echo base_url(); ?>"><i class="fa fa-dashboard"></i> Dashboard</a> </li>
      <li> <a href="#" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Manage Appointments<span class="fa arrow"></span></a>
        <ul class="nav nav-second-level">
          
           <?php if($_SESSION['logged_doctor']['is_primary'] == "1"){?>
          <li> <a href="<?php echo base_url(); ?>doctor_appointments">My Appointment</a> </li>
          <?php }else{  ?>
           <li> <a href="<?php echo base_url(); ?>doctors/appointment_doctor">My Appointment</a> </li>
          <?php  }  ?>
          <li> <a href="<?php echo base_url(); ?>doctors/manage_discharge_forms">Discharge Forms</a> </li>
        </ul>
      </li>
      <li> <a href="#" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Manage Patients<span class="fa arrow"></span></a>
        <ul class="nav nav-second-level">
          <li> <a href="<?php echo base_url(); ?>my_ipd">My IPD</a> </li>
          <li> <a href="<?php echo base_url(); ?>my_discharge">My Discharge</a> </li>
          <li> <a href="<?php echo base_url(); ?>my_reports">My Reports</a> </li>    
          <!-- <li> <a href="<?php echo base_url(); ?>check_reports">Check reports</a> </li> -->
        </ul>
      </li>
	   <li> <a href="<?php echo base_url(); ?>accounts/clinical_reports" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Clinical Reports</a>
      <?php if($_SESSION['logged_doctor']['username'] == "dr_rohit"){?>
        <li> <a href="#" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Manage Bllings<span class="fa arrow"></span></a>
          <ul class="nav nav-second-level">
            <li> <a href="<?php echo base_url(); ?>billings/all_billings">All billings</a> </li>
          </ul>
        </li>
      <?php } ?>
      
      <li> <a href="#" class="waves-effect waves-dark"><i class="fa fa-book"></i>Consent Book<span class="fa arrow"></span></a>
        <ul class="nav nav-second-level">
          <li> <a href="#" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Consent Surrogacy<span class="fa arrow"></span></a>
            <ul class="nav nav-third-level">
              <li> <a href="<?php echo base_url(); ?>doctors/divorce_ewidow">DivorceE widow</a> </li>
              <li> <a href="<?php echo base_url(); ?>doctors/agreement_for_surrogacy">Agreement For Surrogacy</a> </li>
              <li> <a href="<?php echo base_url(); ?>doctors/couple_for_availing_surrogacy">Couple For Availing Surrogacy</a> </li>
              <li> <a href="<?php echo base_url(); ?>doctors/fitness_of_surrogate_mother">Fitness Of Surrogate Mother</a> </li>
              <li> <a href="<?php echo base_url(); ?>doctors/consent_form_for_withdrawal">Consent Form For Withdrawal</a> </li>
              <li> <a href="<?php echo base_url(); ?>doctors/screening_of_the_surrogate">Screening Of The Surrogate</a> </li>
              <li> <a href="<?php echo base_url(); ?>doctors/acknowledgment">Acknowledgment</a> </li>
            </ul>
          </li>
           <li> <a href="<?php echo base_url(); ?>doctors/ovarian_exosome_therapy"><i class="fa fa-sitemap"></i>Ovarian Exosome Therapy</a> </li>
           <li> <a href="#" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Affidavit Form<span class="fa arrow"></span></a>
            <ul class="nav nav-third-level">
              <li> <a href="<?php echo base_url(); ?>doctors/new_ed_affidavit">New Ed affidavit</a> </li>
              <li> <a href="<?php echo base_url(); ?>doctors/od_affidavit">OD affidavit</a> </li>
              <li> <a href="<?php echo base_url(); ?>doctors/donor_sperm_affidavit">Donor Sperm Affidavit</a> </li>
            </ul>
          </li>
          
          <li> <a href="#" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Admission Form<span class="fa arrow"></span></a>
            <ul class="nav nav-third-level">
              <li> <a href="<?php echo base_url(); ?>doctors/ipd_admission_form">Ipd Admission Form</a> </li>
              <li> <a href="<?php echo base_url(); ?>doctors/anaethesia_consent_form">Anaethesia Consent Form</a> </li>
              <li> <a href="<?php echo base_url(); ?>doctors/ot_consent_form">Ot Consent Form</a> </li>
            </ul>
          </li>
          
          <li> <a href="<?php echo base_url(); ?>doctors/ovarian_stem_cell" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Ovarian Stem Cell</a></li>
          
          <li> <a href="#" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Incorporated<span class="fa arrow"></span></a>
            <ul class="nav nav-third-level">
              <li> <a href="<?php echo base_url(); ?>doctors/consent_for_blastocyst">Consent For Blastocyst</a> </li>
              <li> <a href="<?php echo base_url(); ?>doctors/consent_for_embryo_glue">Consent For Embryo Glue</a> </li>
              <li> <a href="<?php echo base_url(); ?>doctors/consent_for_icsi">Consent For Icsi</a> </li>
              <li> <a href="<?php echo base_url(); ?>doctors/consent_for_lah">Consent For Lah</a> </li>
              <li> <a href="<?php echo base_url(); ?>doctors/consent_for_microfluidics_sperm">Consent For Microfluidics Sperm</a> </li>
              <li> <a href="<?php echo base_url(); ?>doctors/consent_for_pgt">Consent For Pgt</a> </li>
              <li> <a href="<?php echo base_url(); ?>doctors/consent_for_sperm_mobil">Consent For Sperm Mobil</a> </li>
              <li> <a href="<?php echo base_url(); ?>doctors/consent_for_thawing_of_gametes">Consent For Thawing Of Gametes</a> </li>
              <li> <a href="<?php echo base_url(); ?>doctors/testicular_stem_cell">Testicular Stem Cell</a> </li>
              <li> <a href="<?php echo base_url(); ?>doctors/consent_for_oocyte_activation">Consent For Oocyte Activation</a> </li>
            </ul>
          </li>  

          <li> <a href="#" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Art Consent<span class="fa arrow"></span></a>
            <ul class="nav nav-third-level">
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
      
      <?php if($_SESSION['logged_doctor']['username'] == "drss@indiaivf.in"){?>
        <!--<li> <a href="<?php echo base_url(); ?>stocks/all_consumption" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>All Consumption</a></li>-->
      <?php } ?>
      
      <li> <a href="<?php echo base_url(); ?>doctors/pcp_ndt" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>PCPNDT</a></li>
      <li> <a href="<?php echo base_url(); ?>investigation/patient_investigation_list" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Investigation Reports</a></li>
      <li> <a href="<?php echo base_url(); ?>doctors/doctor_patient" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>OPD Register</a></li>
      <li> <a href="<?php echo base_url(); ?>doctors/patient_duration" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>OPD Consltation Duration</a></li>
      <li> <a href="#" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Donor<span class="fa arrow"></span></a>
        <ul class="nav nav-second-level">
          <li> <a href="<?php echo base_url(); ?>accounts/add_donor">Add Donor</a> </li>
          <li> <a href="<?php echo base_url(); ?>accounts/donor_list">Donor List</a> </li>
        </ul>
      </li>
      <li> <a href="<?php echo base_url(); ?>doctors/patient_general_instructions" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>General Instructions</a></li>
      <li> <a href="<?php echo base_url(); ?>billings/forma_invoice_list" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Debtors List</a></li>
      <li> <a href="<?php echo base_url(); ?>accounts/patient_center_wise_report" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Patient Journey</a></li>
      <li> <a href="#" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Camp Appointments<span class="fa arrow"></span></a>
        <ul class="nav nav-second-level">
          <li> <a href="<?php echo base_url(); ?>my_appointments_camp">My appointments in camp</a> </li>
          <li> <a href="<?php echo base_url(); ?>accounts/assessment_form_list">Assessment List</a> </li>
        </ul>
      </li>
       <li> <a href="<?php echo base_url(); ?>accounts/patient_financial_clearance" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Procedure Financial Clearance</a></li>
       <li> <a href="<?php echo base_url(); ?>accounts/patient_journey" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Procedure Journey</a></li>
               <li> <a href="<?php echo base_url(); ?>accounts/procedure_list" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Procedure Complete</a></li>
     
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