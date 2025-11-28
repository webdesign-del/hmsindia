<?php

defined('BASEPATH') OR exit('No direct script access allowed');



$route['default_controller'] = 'welcome';

$route['dashboard'] = 'welcome/dashboard';

$route['logout'] = 'welcome/logout';

$route['logs'] = 'logs/index';
$route['logs/test'] = 'logs/test';
$route['logs/ajax'] = 'logs/ajax';
$route['logs/clear'] = 'logs/clear';
$route['logs/stats'] = 'logs/stats';
$route['logs/download'] = 'logs/download';

$route['stocks'] = 'stocks/stocks';

$route['centers'] = 'centers/centers';

$route['employees'] = 'employees/employees';

$route['procedures'] = 'procedures/procedures';

$route['doctors'] = 'doctors/doctors';

$route['doctor-consultations'] = 'doctors/doctor_consultations';

$route['junior-doctors'] = 'doctors/junior_doctors';

$route['junior-doctors/add'] = 'doctors/add_junior_doctors';

$route['junior-doctors/edit'] = 'doctors/edit_junior_doctors';

$route['junior-doctors/delete'] = 'doctors/delete_junior_doctors';

$route['settings'] = 'welcome/settings';

$route['password'] = 'welcome/password';

$route['vendors'] = 'vendors/vendors';

$route['brands'] = 'brands/brands';

$route['doctor-login'] = 'doctors/login';

$route['patient_details/(:any)'] = 'accounts/patient_details/$1';

$route['my-approvals'] = 'accounts/my_approvals';

$route['user-approval-stats'] = 'accounts/user_approval_stats';

$route['debug-users'] = 'accounts/debug_users';

$route['patient-profile'] = 'welcome/patient_profile';

$route['404_override'] = 'welcome/not_found';

$route['translate_uri_dashes'] = FALSE;



//Stocks

$route['products'] = 'stocks/stock_products';

$route['add-product'] = 'stocks/stock_product_add';

$route['edit-product'] = 'stocks/stock_product_edit';

$route['product-brands/(:any)'] = 'stocks/stock_product_brands/$1';

$route['product-vendors'] = 'stocks/stock_product_vendors';

$route['assign-vendor'] = 'stocks/product_vendor_add';

$route['edit-product-vendor/(:any)'] = 'stocks/product_vendor_edit/$1';

$route['add-medicine'] = 'stocks/stock_medicine_add';

$route['edit-medicine'] = 'stocks/stock_medicine_edit';

$route['medicine'] = 'stocks/stock_medicine';

// billing discount

$route['discount-request'] = 'accounts/discount_request';

$route['discount_disapprove_request'] = 'accounts/discount_disapprove_request';


$route['discount-approval'] = 'billings/discount_approved';



//Account Modules

$route['download-ledger'] = 'accounts/download_ledger';

$route['export-billing'] = 'billingcontroller/export_billing';

$route['partial-payment-receipt/(:any)'] = 'accounts/partial_payment_receipt/$1';

$route['accounts/details/(:any)'] = 'accounts/details/$1';



$route['partial-consultation'] = 'billingcontroller/partial_consultation/';

$route['cancel-partial-consultation/(:any)'] = 'billingcontroller/cancel_partial_consultation/$1';

// New Billing module

$route['appointment'] = 'billingcontroller/appointment';

$route['booking'] = 'billingcontroller/booking';

$route['after-consultation'] = 'billingcontroller/after_consultation';

$route['after-consultation-step-2'] = 'billingcontroller/after_consultation_billing';

$route['after-consultation-step-3'] = 'billingcontroller/package_consultation_billing';

$route['billing-noreceipt'] = 'billingcontroller/billing_noreceipt';

$route['billing_noreceipt_procedure'] = 'billingcontroller/billing_noreceipt_procedure';

$route['billing_noreceipt_investigation'] = 'billingcontroller/billing_noreceipt_investigation';

$route['billing_noreceipt_patient_payments'] = 'billingcontroller/billing_noreceipt_patient_payments';

$route['upload-receipt/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'billingcontroller/upload_receipt/$1/$2/$3/$4/$5';

$route['consultation/(:any)'] = 'billingcontroller/consultation/$1';

$route['registation/(:any)'] = 'billingcontroller/registation/$1';

$route['upload-package-form/(:any)'] = 'billingcontroller/upload_package_form/$1';





//Modern Appointment modules

$route['appointments'] = 'appointmentcontroller/modern_appointments';
$route['appointment/getAppointments'] = 'appointmentcontroller/getAppointments';
$route['appointment/create'] = 'appointmentcontroller/create';
$route['appointment/updateStatus'] = 'appointmentcontroller/updateStatus';
$route['appointment/reschedule'] = 'appointmentcontroller/reschedule';
$route['appointment/cancel'] = 'appointmentcontroller/cancel';
$route['appointment/getDetails/(:any)'] = 'appointmentcontroller/getDetails/$1';
$route['appointment/export'] = 'appointmentcontroller/export';
$route['appointment/getAvailableSlots'] = 'appointmentcontroller/getAvailableSlots';

// // Modern Appointments Extended Modules
// $route['modern-appointments/create'] = 'appointmentcontroller/modern_create';
// $route['modern-appointments/calendar'] = 'appointmentcontroller/modern_calendar';
// $route['modern-appointments/reports'] = 'appointmentcontroller/modern_reports';
// $route['modern-appointments/settings'] = 'appointmentcontroller/modern_settings';
// $route['modern-appointments/analytics'] = 'appointmentcontroller/modern_analytics';
// $route['modern-appointments/notifications'] = 'appointmentcontroller/modern_notifications';
// $route['modern-appointments/templates'] = 'appointmentcontroller/modern_templates';
// $route['modern-appointments/integrations'] = 'appointmentcontroller/modern_integrations';

// // Modern Appointment Controller Routes
$route['modern-appointments/create'] = 'ModernAppointmentController/create';
$route['modern-appointments/createAppointment'] = 'ModernAppointmentController/createAppointment';
$route['modern-appointments/searchPatient'] = 'ModernAppointmentController/searchPatient';
$route['modern-appointments/getDoctorsByCenter'] = 'ModernAppointmentController/getDoctorsByCenter';
$route['modern-appointments/getAvailableSlots'] = 'ModernAppointmentController/getAvailableSlots';
$route['modern-appointments/getCampsByCenter'] = 'ModernAppointmentController/getCampsByCenter';
$route['modern-appointments/createCamp'] = 'ModernAppointmentController/createCamp';
$route['modern-appointments/checkCampTemplates'] = 'ModernAppointmentController/checkCampTemplates';
$route['modern-appointments/getDetails/(:any)'] = 'ModernAppointmentController/getDetails/$1';
$route['modern-appointments/updateStatus'] = 'ModernAppointmentController/updateStatus';
$route['modern-appointments/reschedule'] = 'ModernAppointmentController/reschedule';
$route['modern-appointments/cancel'] = 'ModernAppointmentController/cancel';
$route['modern-appointments/getAppointments'] = 'ModernAppointmentController/getAppointments';
$route['modern-appointments/export'] = 'ModernAppointmentController/export';

// Modern Appointments Dashboard and Views
$route['modern-appointments'] = 'ModernAppointmentController/dashboard';
$route['modern-appointments/index'] = 'ModernAppointmentController/dashboard';
$route['modern-appointments/calendar'] = 'ModernAppointmentController/calendar';
$route['modern-appointments/reports'] = 'ModernAppointmentController/reports';
$route['modern-appointments/settings'] = 'ModernAppointmentController/settings';

//API Routes
$route['api/appointment/create'] = 'Api/AppointmentApi/create';
$route['api/appointment/updateStatus'] = 'Api/AppointmentApi/updateStatus';
$route['api/appointment/getAppointments'] = 'Api/AppointmentApi/getAppointments';
$route['api/appointment/getDetails/(:any)'] = 'Api/AppointmentApi/getDetails/$1';
$route['api/appointment/getAvailableSlots'] = 'Api/AppointmentApi/getAvailableSlots';
$route['api/appointment/reschedule'] = 'Api/AppointmentApi/reschedule';
$route['api/appointment/cancel'] = 'Api/AppointmentApi/cancel';
$route['api/appointment/leadsquare'] = 'Api/AppointmentApi/leadsquareIntegration';

//Legacy routes (for backward compatibility)
$route['my_appointments'] = 'appointmentcontroller/my_appointments';
$route['my_appointments_camp'] = 'appointmentcontroller/my_appointments_in_camp';
$route['all-appointments'] = 'appointmentcontroller/all_appointments';
$route['telecaller-appointments'] = 'appointmentcontroller/telecaller_appointments';
$route['follow-up-appointment'] = 'appointmentcontroller/followup_appointment';
$route['pending-consultation-billing'] = 'appointmentcontroller/pending_consultation_billing';
$route['daily-appointments'] = 'appointmentcontroller/daily_appointments';
$route['partial-billing/(:any)'] = 'billingcontroller/partial_billing/$1';

//Doctor Dashboards

$route['doctor_appointments'] = 'doctors/doctor_appointments';

$route['my_ipd'] = 'doctors/my_ipd';

$route['my_reports'] = 'doctors/my_reports';

$route['consultation_done/(:any)'] = 'doctors/consultation_done/$1';

$route['follow-up/(:any)'] = 'doctors/follow_up/$1';

$route['follow-up-clean/(:any)'] = 'doctors/follow_up_clean/$1';

$route['follow-up-form/(:any)'] = 'doctors/follow_up_form/$1';

$route['follow-up-print/(:any)'] = 'doctors/follow_up_print/$1';

$route['print-submitted-consultation/(:any)'] = 'doctors/print_submitted_consultation/$1';

$route['procedure_reports/(:any)'] = 'doctors/procedure_reports/$1';

$route['procedure_upload/(:any)/(:any)'] = 'doctors/procedure_upload/$1/$2';

$route['procedure_form/(:any)/(:any)/(:any)/(:any)'] = 'doctors/procedure_form/$1/$2/$3/$4';

$route['check_procedure_form/(:any)/(:any)/(:any)/(:any)'] = 'doctors/check_procedure_form/$1/$2/$3/$4';

$route['procedure_form_donor/(:any)/(:any)/(:any)'] = 'doctors/procedure_form_donor/$1/$2/$3';

$route['check_procedure_form_donor/(:any)/(:any)/(:any)'] = 'doctors/check_procedure_form_donor/$1/$2/$3';

$route['my-reports/(:any)/(:any)'] = 'doctors/lab_reports/$1/$2';

$route['procedure_report_status/(:any)/(:any)/(:any)/(:any)'] = 'doctors/procedure_report_status/$1/$2/$3/$4';

$route['jd_appointments'] = 'doctors/junior_doctor_appointments';







//Investigation Dashboards

$route['my_investigation'] = 'investigation/my_investigation';

//$route['patient_investigation/(:any)'] = 'investigation/patient_investigation/$1';
//$route['patient_investigation_details/(:any)'] = 'investigation/patient_investigation_details/$1';



//Patient Controllers

$route['patient_reports/(:any)'] = 'patients/patient_reports/$1';

$route['report_status/(:any)/(:any)/(:any)'] = 'patients/report_status/$1/$2/$3';

$route['check_reports'] = 'patients/check_reports';

$route['ipd-records/(:any)/(:any)'] = 'patients/patient_records/$1/$2';

$route['ipd-psychological/(:any)/(:any)'] = 'patients/patient_psychological/$1/$2';

$route['patient-discharge/(:any)/(:any)/(:any)'] = '/patients/patient_discharge_summary/$1/$2/$3';


//embryologist dashboard

$route['embryologist_records'] = 'procedures/embryologist_records';

$route['doctor-appointments'] = 'welcome/doctor_appointments';

$route['check-patient'] = 'billings/check_patient';

//Doctor dashboard
$route['doctors'] = 'doctors/consent_form'; 

$route['doctors'] = 'doctors/intrauterine_insemination';

$route['doctors'] = 'doctors/form8';

$route['doctors'] = 'doctors/form8_single_woman';

$route['doctors'] = 'doctors/form9';

$route['doctors'] = 'doctors/form10';

$route['doctors'] = 'doctors/form11';

$route['doctors'] = 'doctors/form12';

$route['doctors'] = 'doctors/consent_for_embryo_transfer';

$route['doctors'] = 'doctors/form13';

$route['doctors'] = 'doctors/form15';

$route['doctors'] = 'doctors/cfpros';

$route['doctors'] = 'doctors/form18'; 

$route['doctors'] = 'doctors/risk_consent';

$route['doctors'] = 'doctors/couple_donor_egg';

$route['doctors'] = 'doctors/consent_for_semen_collection';

$route['doctors'] = 'doctors/micro_tese';

$route['doctors'] = 'doctors/ovarian_platelet_rich_plasma';

$route['doctors'] = 'doctors/uterine_platelet_rich_plasma';

$route['doctors'] = 'doctors/testicular_platelet_rich_plasma';

$route['doctors'] = 'doctors/patient_testimonial';

$route['doctors'] = 'doctors/low_ovarian_reserve_females';

$route['doctors'] = 'doctors/divorce_ewidow';

$route['doctors'] = 'doctors/agreement_for_surrogacy';

$route['doctors'] = 'doctors/couple_for_availing_surrogacy';

$route['doctors'] = 'doctors/fitness_of_surrogate_mother';

$route['doctors'] = 'doctors/consent_form_for_withdrawal';

$route['doctors'] = 'doctors/screening_of_the_surrogate';

$route['doctors'] = 'doctors/acknowledgment';

$route['doctors'] = 'doctors/divorce_ewidow';

$route['doctors'] = 'doctors/donor_sperm_affidavit';

$route['doctors'] = 'doctors/new_ed_affidavit';

// purchase orders

$route['accounts/purchase-orders-list'] = 'accounts/purchase_order_list';
$route['accounts/purchase-orders'] = 'accounts/purchase_order';
$route['accounts/purchase-order-payment/(:any)'] = 'accounts/purchase_order_payment/$1';
$route['accounts/save-purchase-order-payment/(:any)'] = 'accounts/save_payment_purchase_order/$1';
$route['new_purchase_orders/status'] = 'new_purchase_orders/status';

// GRN (Goods Receipt Note) routes
$route['accounts/grn-list'] = 'accounts/grn_list';
$route['accounts/add-grn/(:any)'] = 'accounts/add_grn/$1';
$route['accounts/save-grn'] = 'accounts/save_grn';
$route['accounts/view-grn/(:any)'] = 'accounts/view_grn/$1';

// hub-spoke
$route['centers/hub_spoke'] = 'centers/hub_spoke';
$route['centers/add_hub_spoke'] = 'centers/add_hub_spoke';
$route['centers/edit_hub_spoke/(:any)'] = 'centers/edit_hub_spoke/$1';
$route['centers/delete_hub_spoke/(:any)'] = 'centers/delete_hub_spoke/$1';
$route['centers/view_hub_spoke/(:any)'] = 'centers/view_hub_spoke/$1';
$route['orders/edit_purchase_order/(:any)'] = 'orders/edit_purchase_order/$1';

// New Purchase Orders System
$route['new_purchase_orders'] = 'new_purchase_orders/index';
$route['new_purchase_orders/add'] = 'new_purchase_orders/add';
$route['new_purchase_orders/save'] = 'new_purchase_orders/save';
$route['new_purchase_orders/edit/(:any)'] = 'new_purchase_orders/edit/$1';
$route['new_purchase_orders/update/(:any)'] = 'new_purchase_orders/update/$1';
$route['new_purchase_orders/view/(:any)'] = 'new_purchase_orders/view/$1';
$route['new_purchase_orders/delete/(:any)'] = 'new_purchase_orders/delete/$1';
$route['new_purchase_orders/approve/(:any)'] = 'new_purchase_orders/approve/$1';
$route['new_purchase_orders/reject/(:any)'] = 'new_purchase_orders/reject/$1';
$route['new_purchase_orders/complete/(:any)'] = 'new_purchase_orders/complete/$1';

// Stock export routes
$route['stocks/All-Center-Medicine'] = 'stocks/all_center_stocks';


