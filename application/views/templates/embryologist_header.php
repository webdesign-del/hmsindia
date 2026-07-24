<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Embryologist Dashboard</title>
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
if(isset($_SESSION['logged_embryologist']['center'])) {
    $center_id_session = $_SESSION['logged_embryologist']['center'];
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
    <li><a class="dropdown-button waves-effect waves-dark" href="#!" data-activates="dropdown1"><i class="fa fa-user fa-fw"></i> <b><?php echo $_SESSION['logged_embryologist']['name']?></b> <i class="material-icons right">arrow_drop_down</i></a></li>
  </ul>
</nav>
<!-- Dropdown Structure -->
<ul id="dropdown1" class="dropdown-content">
  <li><a href="#"><i class="fa fa-user fa-fw"></i> My Profile</a> </li>
  <li><a href="#"><i class="fa fa-gear fa-fw"></i> Settings</a> </li>
  <li><a href="<?php echo base_url(); ?>logout?r=<?php echo base64_encode('logged_embryologist'); ?>"><i class="fa fa-sign-out fa-fw"></i> Logout</a> </li>
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
      <li> <a href="#" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Manage Procedures<span class="fa arrow"></span></a>
        <ul class="nav nav-second-level">
          <li> <a href="<?php echo base_url(); ?>embryologist_records">My Procedures</a> </li>
		  <li> <a href="<?php echo base_url(); ?>accounts/freezing">Freezing Ledger</a> </li>
		  <li> <a href="<?php echo base_url(); ?>accounts/freezing_reports">Freezing Reports</a> </li>
		  <li> <a href="<?php echo base_url(); ?>accounts/freezing_renewal">Freezing Renewal</a> </li>
		  <li> <a href="<?php echo base_url(); ?>doctors/freezingmo">Cryopreservation</a> </li>
		  <li> <a href="<?php echo base_url(); ?>doctors/discard">Discard</a> </li>
		  <li> <a href="<?php echo base_url(); ?>accounts/clinical_reports">Clinical Reports</a></li>
        </ul>
      </li>
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