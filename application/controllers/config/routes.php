<?php

defined('BASEPATH') OR exit('No direct script access allowed');



$route['default_controller'] = 'welcome';

$route['dashboard'] = 'welcome/dashboard';

$route['logout'] = 'welcome/logout';

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



// billing discount

$route['discount-request'] = 'accounts/discount_request';

$route['discount-approval'] = 'billings/discount_approved';



//Account Modules

$route['download-ledger'] = 'accounts/download_ledger';

$route['export-billing'] = 'billingcontroller/export_billing';

$route['partial-payment-receipt/(:any)'] = 'accounts/partial_payment_receipt/$1';



$route['partial-consultation'] = 'billingcontroller/partial_consultation/';

$route['cancel-partial-consultation/(:any)'] = 'billingcontroller/cancel_partial_consultation/$1';

// New Billing module

$route['appointment'] = 'billingcontroller/appointment';

$route['booking'] = 'billingcontroller/booking';

$route['after-consultation'] = 'billingcontroller/after_consultation';

$route['after-consultation-step-2'] = 'billingcontroller/after_consultation_billing';

$route['billing-noreceipt'] = 'billingcontroller/billing_noreceipt';

$route['upload-receipt/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'billingcontroller/upload_receipt/$1/$2/$3/$4/$5';

$route['consultation/(:any)'] = 'billingcontroller/consultation/$1';

$route['upload-package-form/(:any)'] = 'billingcontroller/upload_package_form/$1';





//Appointment modules

$route['my_appointments'] = 'appointmentcontroller/my_appointments';
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

$route['procedure_reports/(:any)'] = 'doctors/procedure_reports/$1';

$route['procedure_upload/(:any)/(:any)'] = 'doctors/procedure_upload/$1/$2';

$route['procedure_form/(:any)/(:any)/(:any)/(:any)'] = 'doctors/procedure_form/$1/$2/$3/$4';

$route['check_procedure_form/(:any)/(:any)/(:any)/(:any)'] = 'doctors/check_procedure_form/$1/$2/$3/$4';

$route['my-reports/(:any)/(:any)'] = 'doctors/lab_reports/$1/$2';

$route['procedure_report_status/(:any)/(:any)/(:any)/(:any)'] = 'doctors/procedure_report_status/$1/$2/$3/$4';

$route['jd_appointments'] = 'doctors/junior_doctor_appointments';







//Investigation Dashboards

$route['my_investigation'] = 'investigation/my_investigation';

$route['patient_investigation/(:any)'] = 'investigation/patient_investigation/$1';



//Patient Controllers

$route['patient_reports/(:any)'] = 'patients/patient_reports/$1';

$route['report_status/(:any)/(:any)/(:any)'] = 'patients/report_status/$1/$2/$3';

$route['check_reports'] = 'patients/check_reports';

$route['ipd-records/(:any)/(:any)'] = 'patients/patient_records/$1/$2';



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

$route['my_discharge'] = 'doctors/my_discharge';

$route['discharge-records/(:any)/(:any)'] = 'patients/patient_discharge_records/$1/$2';