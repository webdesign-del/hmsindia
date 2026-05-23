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

$route['stocks_new/view_document/(:any)/(:num)'] = 'stocks_new/view_document/$1/$2';
$route['stocks_new/download_document/(:any)/(:num)'] = 'stocks_new/download_document/$1/$2';

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

$route['follow-up-ipd/(:any)'] = 'doctors/follow_up_ipd/$1';

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

//$route['accounts/assessment_form/(:num)'] = 'accounts/assessment_form/$1';







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
$route['new_purchase_orders/selective_approve/(:any)'] = 'new_purchase_orders/selective_approve/$1';
$route['new_purchase_orders/reject/(:any)'] = 'new_purchase_orders/reject/$1';
$route['new_purchase_orders/complete/(:any)'] = 'new_purchase_orders/complete/$1';

// Stock export routes
$route['stocks/All-Center-Medicine'] = 'stocks/all_center_stocks';



// New Stocks Module Routes
$route['stocks_new/dashboard'] = 'stocks_new/dashboard';
$route['stocks_new/brands'] = 'stocks_new/brands';
$route['stocks_new/add_brand'] = 'stocks_new/add_brand';
$route['stocks_new/edit_brand/(:any)'] = 'stocks_new/edit_brand/$1';
$route['stocks_new/delete_brand/(:any)'] = 'stocks_new/delete_brand/$1';
$route['stocks_new/vendors'] = 'stocks_new/vendors';
$route['stocks_new/add_vendor'] = 'stocks_new/add_vendor';
$route['stocks_new/edit_vendor/(:any)'] = 'stocks_new/edit_vendor/$1';
$route['stocks_new/delete_vendor/(:any)'] = 'stocks_new/delete_vendor/$1';
$route['stocks_new/medicines'] = 'stocks_new/medicines';
$route['stocks_new/add_medicine'] = 'stocks_new/add_medicine';
$route['stocks_new/save_medicine'] = 'stocks_new/save_medicine';
$route['stocks_new/edit_medicine/(:any)'] = 'stocks_new/edit_medicine/$1';
$route['stocks_new/update_medicine/(:any)'] = 'stocks_new/update_medicine/$1';
$route['stocks_new/delete_medicine/(:any)'] = 'stocks_new/delete_medicine/$1';
$route['stocks_new/batches'] = 'stocks_new/batches';
$route['stocks_new/add_batch'] = 'stocks_new/add_batch';
$route['stocks_new/edit_batch'] = 'stocks_new/save_batch';
$route['stocks_new/edit_batch/(:any)'] = 'stocks_new/edit_batch/$1';
$route['stocks_new/update_batch/(:any)'] = 'stocks_new/update_batch/$1';
$route['stocks_new/delete_batch/(:any)'] = 'stocks_new/delete_batch/$1';
$route['stocks_new/stock_levels'] = 'stocks_new/stock_levels';
$route['stocks_new/transfers'] = 'stocks_new/transfers';
$route['stocks_new/add_transfer'] = 'stocks_new/add_transfer';
$route['stocks_new/save_transfer'] = 'stocks_new/save_transfer';
$route['stocks_new/edit_transfer/(:any)'] = 'stocks_new/edit_transfer/$1';
$route['stocks_new/update_transfer/(:any)'] = 'stocks_new/update_transfer/$1';
$route['stocks_new/delete_transfer/(:any)'] = 'stocks_new/delete_transfer/$1';
$route['stocks_new/approve_transfer/(:any)'] = 'stocks_new/approve_transfer/$1';
$route['stocks_new/complete_transfer/(:any)'] = 'stocks_new/complete_transfer/$1';
$route['stocks_new/multi_transfer'] = 'stocks_new/multi_transfer';
$route['stocks_new/department_transfer'] = 'stocks_new/department_transfer';
$route['stocks_new/get_available_stocks'] = 'stocks_new/get_available_stocks';
$route['stocks_new/get_employees_by_location'] = 'stocks_new/get_employees_by_location';
$route['stocks_new/sales'] = 'stocks_new/sales';
$route['stocks_new/add_sale'] = 'stocks_new/add_sale';
$route['stocks_new/save_sale'] = 'stocks_new/save_sale';
$route['stocks_new/edit_sale/(:any)'] = 'stocks_new/edit_sale/$1';
$route['stocks_new/update_sale/(:any)'] = 'stocks_new/update_sale/$1';
$route['stocks_new/delete_sale/(:any)'] = 'stocks_new/delete_sale/$1';
$route['stocks_new/confirm_sale/(:any)'] = 'stocks_new/confirm_sale/$1';
$route['stocks_new/print_sale/(:any)'] = 'stocks_new/print_sale/$1';
$route['stocks_new/reports'] = 'stocks_new/reports';
$route['stocks_new/low_stock_alerts'] = 'stocks_new/low_stock_alerts';
$route['stocks_new/expiry_alerts'] = 'stocks_new/expiry_alerts';
$route['stocks_new/medicine_returns'] = 'stocks_new/medicine_returns';
$route['stocks_new/process_return'] = 'stocks_new/process_return';
$route['stocks_new/returns'] = 'stocks_new/returns';
$route['stocks_new/approve_return/(:any)'] = 'stocks_new/approve_return/$1';
$route['stocks_new/disapprove_return/(:any)'] = 'stocks_new/disapprove_return/$1';
$route['stocks_new/get_patient_receipts'] = 'stocks_new/get_patient_receipts';
$route['stocks_new/get_returnable_items'] = 'stocks_new/get_returnable_items';
$route['stocks_new/stock_audit'] = 'stocks_new/stock_audit';
$route['stocks_new/process_audit'] = 'stocks_new/process_audit';
$route['stocks_new/audit_reports'] = 'stocks_new/audit_reports';
$route['stocks_new/medicine_disposal'] = 'stocks_new/medicine_disposal';
$route['stocks_new/process_disposal'] = 'stocks_new/process_disposal';
$route['stocks_new/disposal_reports'] = 'stocks_new/disposal_reports';
$route['stocks_new/invoices'] = 'stocks_new/invoices';
$route['stocks_new/add_invoice'] = 'stocks_new/add_invoice';
$route['stocks_new/save_invoice'] = 'stocks_new/save_invoice';
$route['stocks_new/edit_invoice/(:any)'] = 'stocks_new/edit_invoice/$1';
$route['stocks_new/update_invoice/(:any)'] = 'stocks_new/update_invoice/$1';
$route['stocks_new/approve_invoice/(:any)'] = 'stocks_new/approve_invoice/$1';
$route['stocks_new/print_invoice/(:any)'] = 'stocks_new/print_invoice/$1';
$route['stocks_new/categories'] = 'stocks_new/categories';
$route['stocks_new/add_category'] = 'stocks_new/add_category';
$route['stocks_new/save_category'] = 'stocks_new/save_category';
$route['stocks_new/edit_category/(:any)'] = 'stocks_new/edit_category/$1';
$route['stocks_new/update_category/(:any)'] = 'stocks_new/update_category/$1';
$route['stocks_new/activate_category/(:any)'] = 'stocks_new/activate_category/$1';
$route['stocks_new/deactivate_category/(:any)'] = 'stocks_new/deactivate_category/$1';
$route['stocks_new/generic_names'] = 'stocks_new/generic_names';
$route['stocks_new/add_generic_name'] = 'stocks_new/add_generic_name';
$route['stocks_new/save_generic_name'] = 'stocks_new/save_generic_name';
$route['stocks_new/edit_generic_name/(:any)'] = 'stocks_new/edit_generic_name/$1';
$route['stocks_new/update_generic_name/(:any)'] = 'stocks_new/update_generic_name/$1';
$route['stocks_new/activate_generic_name/(:any)'] = 'stocks_new/activate_generic_name/$1';
$route['stocks_new/deactivate_generic_name/(:any)'] = 'stocks_new/deactivate_generic_name/$1';
$route['stocks_new/vendor_returns'] = 'stocks_new/vendor_returns';
$route['stocks_new/add_vendor_return'] = 'stocks_new/add_vendor_return';
$route['stocks_new/save_vendor_return'] = 'stocks_new/save_vendor_return';
$route['stocks_new/vendor_return_reports'] = 'stocks_new/vendor_return_reports';
$route['stocks_new/purchase_orders_for_stock'] = 'stocks_new/purchase_orders_for_stock';
$route['stocks_new/add_stock_from_po/(:num)'] = 'stocks_new/add_stock_from_po/$1';
$route['stocks_new/process_stock_from_po'] = 'stocks_new/process_stock_from_po';
$route['stocks_new/po_stock_history'] = 'stocks_new/po_stock_history';

// Stock Tracking Panel Routes
$route['stocks_new/stock_tracking_panel'] = 'stocks_new/stock_tracking_panel';
$route['stocks_new/stock_movements'] = 'stocks_new/stock_movements';
$route['stocks_new/track_po_batches/(:num)'] = 'stocks_new/track_po_batches/$1';
$route['stocks_new/get_stock_movements'] = 'stocks_new/get_stock_movements';
$route['stocks_new/get_transfers'] = 'stocks_new/get_transfers';
$route['stocks_new/get_sales'] = 'stocks_new/get_sales';
$route['stocks_new/get_summary_stats'] = 'stocks_new/get_summary_stats';
$route['stocks_new/search_stock_movements'] = 'stocks_new/search_stock_movements';
$route['stocks_new/export_stock_report'] = 'stocks_new/export_stock_report';
$route['stocks_new/update_payment_status'] = 'stocks_new/update_payment_status';
$route['stocks_new/add_billing_item'] = 'stocks_new/add_billing_item';
// export functinality  
$route['stocks_new/central_stocks_export'] = 'stocks_new/central_stocks_export';
$route['stocks_new/center_stocks_export'] = 'stocks_new/center_stocks_export';
// Patient Final Billing Routes
$route['accounts/patient_final_billing'] = 'accounts/patient_final_billing';
$route['accounts/generate_final_bill/(:num)'] = 'accounts/generate_final_bill/$1';
$route['accounts/check_final_billing_eligibility'] = 'accounts/check_final_billing_eligibility';
$route['accounts/search_patients_for_final_billing'] = 'accounts/search_patients_for_final_billing';
$route['accounts/get_patient_procedure_details'] = 'accounts/get_patient_procedure_details';
