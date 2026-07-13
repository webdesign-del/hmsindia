from django.urls import path
from . import views

urlpatterns = [
    # Template routes
    path('', views.index, name='index'),
    path('doctor/', views.doctor_view, name='doctor_view'),
    path('centre-head/', views.centre_head_view, name='centre_head_view'),
    path('financial-counsellor/', views.fc_view, name='fc_view'),
    path('accounts/', views.accounts_view, name='accounts_view'),
    path('management/', views.management_view, name='management_view'),
    
    # Naya API Route
    path('api/get_cnb_data/', views.get_cnb_data, name='get_cnb_data'),

    # Add this line right below it for saving:
    path('api/save_cnb_edits/', views.save_cnb_edits, name='save_cnb_edits'),

    path('procedure-billing/', views.procedure_billing_view, name='procedure_billing_view'),
    path('api/get_procedure_billing_data/', views.get_procedure_billing_data, name='get_procedure_billing_data'),
    path('api/get_dynamic_booked_patients/', views.get_dynamic_booked_patients, name='get_dynamic_booked_patients'),

    path('api/get_patient_profile_detail/', views.get_patient_profile_detail, name='get_patient_profile_detail'),
]