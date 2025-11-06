<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
   <head>
      <meta charset="utf-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <title>Central Stock manager Dashboard</title>
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
      <link rel="stylesheet" href="<?php echo base_url();?>assets/css/select2.min.css">
      <!-- jQuery Js -->
      <link rel="stylesheet" href="<?php echo base_url();?>assets/css/jquery-ui.css">
      <script src="<?php echo base_url();?>assets/js/jquery-1.12.4.js"></script>
      <script src="<?php echo base_url();?>assets/js/jquery-ui.js"></script>
      <script src="<?php echo base_url();?>assets/js/select2.min.js"></script>
      <!-- Google Fonts-->
      <link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
      <link rel="stylesheet" href="<?php echo base_url();?>assets/css/Lightweight-Chart/cssCharts.css">
      <link rel="stylesheet" href="<?php echo base_url();?>assets/css/bootstrap-multiselect.css">
      <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
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
            <li><a class="dropdown-button waves-effect waves-dark" href="#!" data-activates="dropdown1"><i class="fa fa-user fa-fw"></i> <b><?php echo $_SESSION['logged_central_stock_manager']['name']?></b> <i class="material-icons right">arrow_drop_down</i></a></li>
         </ul>
      </nav>
      <!-- Dropdown Structure -->
      <ul id="dropdown1" class="dropdown-content">
         <!-- <li><a href="#"><i class="fa fa-user fa-fw"></i> My Profile</a> </li>
            <li><a href="#"><i class="fa fa-gear fa-fw"></i> Settings</a> </li> -->
         <li><a href="<?php echo base_url(); ?>logout?r=<?php echo base64_encode('logged_central_stock_manager'); ?>"><i class="fa fa-sign-out fa-fw"></i> Logout</a> </li>
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
               <li>
                  <a href="#" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Manage Stocks<span class="fa arrow"></span></a>
                  <ul class="nav nav-second-level">
                     <li> <a href="<?php echo base_url(); ?>brands">All Brands</a> </li>
                     <li> <a href="<?php echo base_url(); ?>brands/add">Add Brand</a> </li>
                     <li> <a href="<?php echo base_url(); ?>vendors">All Vendors</a> </li>
                     <li> <a href="<?php echo base_url(); ?>vendors/add">Add Vendor</a> </li>
                     <li> <a href="<?php echo base_url(); ?>add-medicine">Add Medicine</a> </li>
                     <li> <a href="<?php echo base_url(); ?>medicine">Medicine List</a> </li>
                     <li> <a href="<?php echo base_url(); ?>products">Products</a></li>
                     <li> <a href="<?php echo base_url(); ?>add-product">Add Product</a></li>
                     <li> <a href="<?php echo base_url(); ?>stocks/generic">Generic Name</a> </li>
                     <li> <a href="<?php echo base_url(); ?>product-vendors">Product Vendors</a></li>
                     <li> <a href="<?php echo base_url(); ?>stocks">Central Stocks Lists</a> </li>
                     <li> <a href="<?php echo base_url(); ?>stocks/active_stocks">Active Stocks Lists</a> </li>
                     <li> <a href="<?php echo base_url(); ?>stocks/add">Add Central Item</a> </li>
                     <li> <a href="<?php echo base_url(); ?>stocks/all_center_stocks">All Centre Stocks</a> </li>
                     <!--<li> <a href="<?php echo base_url(); ?>stocks/add_invoice">Add Invoice</a> </li>
                        <li> <a href="<?php echo base_url(); ?>stocks/invoice_list">Invoice List</a> </li>
                        <li> <a href="<?php echo base_url(); ?>stocks/categories">Categories</a></li>
                              <li> <a href="<?php echo base_url(); ?>stocks/add_category">Add Category</a></li> -->
                     <!-- <li><a href="<?php echo base_url(); ?>orders/inventory_dispense">Patient consumption</a></li> -->
                  </ul>
               </li>
               <!-- New Stocks Module -->
               <li>
                  <a href="#" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>New Stocks Module<span class="fa arrow"></span></a>
                  <ul class="nav nav-second-level">
                     <li> <a href="<?php echo base_url(); ?>stocks_new/dashboard"> Dashboard</a> </li>
                     <li> <a href="<?php echo base_url(); ?>stocks_new/brands"> Brands</a> </li>
                     <li> <a href="<?php echo base_url(); ?>stocks_new/add_brand"> Add Brand</a> </li>
                     <li> <a href="<?php echo base_url(); ?>stocks_new/vendors"> Vendors</a> </li>
                     <li> <a href="<?php echo base_url(); ?>stocks_new/add_vendor"> Add Vendor</a> </li>
                     <li> <a href="<?php echo base_url(); ?>stocks_new/medicines"> Medicines</a> </li>
                     <li> <a href="<?php echo base_url(); ?>stocks_new/add_medicine"> Add Medicine</a> </li>
                     <li> <a href="<?php echo base_url(); ?>stocks_new/batches"> Batches</a> </li>
                     <li> <a href="<?php echo base_url(); ?>stocks_new/add_batch"> Add Batch</a> </li>
                     <li> <a href="<?php echo base_url(); ?>stocks_new/stock_levels"> Stock Levels</a> </li>
                     <li> <a href="<?php echo base_url(); ?>stocks_new/central_stocks"> Central Stocks</a> </li>
                     <li> <a href="<?php echo base_url(); ?>stocks_new/center_stocks"> Center Stocks</a> </li>
                     <li> <a href="<?php echo base_url(); ?>stocks_new/transfers"> Transfers</a> </li>
                     <li> <a href="<?php echo base_url(); ?>stocks_new/add_transfer"> Add Transfer</a> </li>
                     <li> <a href="<?php echo base_url(); ?>stocks_new/sales"> Sales</a> </li>
                     <li> <a href="<?php echo base_url(); ?>stocks_new/add_sale"> Add Sale</a> </li>
                     <li> <a href="<?php echo base_url(); ?>stocks_new/reports"> Reports</a> </li>
                     <li> <a href="<?php echo base_url(); ?>stocks_new/low_stock_alerts"> Low Stock Alerts</a> </li>
                     <li> <a href="<?php echo base_url(); ?>stocks_new/expiry_alerts"> Expiry Alerts</a> </li>
                     <li> <a href="<?php echo base_url(); ?>stocks_new/returns"> Medicine Return List</a> </li>
                     <li> <a href="<?php echo base_url(); ?>stocks_new/medicine_returns"> Medicine Returns</a> </li>
                     <li> <a href="<?php echo base_url(); ?>stocks_new/stock_audit"> Stock Audit</a> </li>
                     <li> <a href="<?php echo base_url(); ?>stocks_new/disposal_reports"> Medicine Disposal List</a> </li>
                     <li> <a href="<?php echo base_url(); ?>stocks_new/medicine_disposal"> Medicine Disposal</a> </li>
                     <li> <a href="<?php echo base_url(); ?>stocks_new/audit_reports"> Audit Reports</a> </li>
                     <!-- <li> <a href="<?php echo base_url(); ?>stocks_new/categories"> Categories</a> </li> -->
                     <!-- <li> <a href="<?php echo base_url(); ?>stocks_new/generic_names"> Generic Names</a> </li> -->
                     <li> <a href="<?php echo base_url(); ?>stocks_new/vendor_returns"> Vendor Returns</a> </li>
                     <li> <a href="<?php echo base_url(); ?>stocks_new/stock_tracking_panel"> Stock Tracking Panel</a> </li>
                     <li> <a href="<?php echo base_url(); ?>stocks_new/stock_movements">Stock Movements</a> </li>
                     <li> <a href="<?php echo base_url(); ?>stocks_new/stock_additions_report">Stock Additions Report</a> </li>
                  </ul>
               </li>
               <li>
                  <a href="#" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Manage Orders<span class="fa arrow"></span></a>
                  <ul class="nav nav-second-level">
                     <li> <a href="<?php echo base_url(); ?>orders/orders">Centre requisition</a> </li>
                     <li> <a href="<?php echo base_url(); ?>orders/purchase_orders_list">Purchase orders</a> </li>
                     <li> <a href="<?php echo base_url(); ?>orders/my_orders">Orders</a> </li>
                     <li> <a href="<?php echo base_url(); ?>orders/my_internal_orders">Internal Orders</a> </li>
                     <li> <a href="<?php echo base_url(); ?>stocks/medicine_center_order">Medicine Order</a> </li>
                     <li><a href="<?php echo base_url(); ?>stocks/patient_items">Patient consumption</a></li>
                  </ul>
               </li>
               <li>
                  <a href="#" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Manage Purchase Orders<span class="fa arrow"></span></a>
                  <ul class="nav nav-second-level">
                     <li> <a href="<?php echo base_url(); ?>new_purchase_orders">New Purchase Orders</a> </li>
                     <li> <a href="<?php echo base_url(); ?>new_purchase_orders/add">Add New PO</a> </li>
                  </ul>
               </li>
               
               <li>
                  <a href="#" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Return<span class="fa arrow"></span></a>
                  <ul class="nav nav-second-level">
                     <li> <a href="<?php echo base_url(); ?>stocks/return_list">Return Item</a> </li>
                     <li> <a href="<?php echo base_url(); ?>stocks/vendor_return_list">Vendor Return</a> </li>
                     <li> <a href="<?php echo base_url(); ?>stocks/product_discard_list">Discard Product</a> </li>
                  </ul>
               </li>
               <li>
                  <a href="#" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Reports<span class="fa arrow"></span></a>
                  <ul class="nav nav-second-level">
                     <li><a href="<?php echo base_url(); ?>stocks/medicine_stock">Cash Medicine Sale Report</a> </li>
                     <li><a href="<?php echo base_url(); ?>orders/purchase_item">Purchase Items</a> </li>
                     <li><a href="<?php echo base_url(); ?>stocks/consumption_price">Consumption Report</a> </li>
                     <li><a href="<?php echo base_url(); ?>stocks/medicine_return_report">Medicine Return Report</a> </li>
                     <li> <a href="<?php echo base_url(); ?>stocks/stocks_reports">Stocks Report</a> </li>
                     <li> <a href="<?php echo base_url(); ?>stocks/transfer_stock_list">Stocks Transfer List</a> </li>
                     <li><a href="<?php echo base_url(); ?>stocks/all_center_audit_report">Audit Report Add</a></li>
                     <li><a href="<?php echo base_url(); ?>stocks/audit_report">Audit Report All Center</a></li>
                  </ul>
               </li>
               <li>
                  <a href="#" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Transfer Stocks<span class="fa arrow"></span></a>
                  <ul class="nav nav-second-level">
                    <li>
                      <a href="<?php echo base_url('new_purchase_orders/stock_transfer'); ?>">
                        Stock Transfer
                     </a>
                    </li>
                     <li> <a href="<?php echo base_url(); ?>stocks/add_center_item">Add Center Item</a> </li>
                     <li> <a href="<?php echo base_url(); ?>stocks/add_center_new_item">Add Center New Item</a> </li>
                  </ul>
               </li>
               <li> <a href="<?php echo base_url(); ?>stocks/center_stocks"><i class="fa fa-sitemap"></i>All Stocks</a> </li>
               <li> <a href="<?php echo base_url(); ?>stocks/add_orders" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Add PO</a></li>
               <li> <a href="<?php echo base_url(); ?>stocks/internal_orders" class="waves-effect waves-dark"><i class="fa fa-sitemap"></i>Add Internal PO</a></li>
               
               
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