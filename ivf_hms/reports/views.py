import json
from django.shortcuts import render
from django.http import JsonResponse
from django.db import connection
from django.views.decorators.csrf import csrf_exempt 
from django.utils import timezone 

def index(request):
    return render(request, 'index.html')

def doctor_view(request):
    return render(request, 'doctor.html')

def centre_head_view(request):
    return render(request, 'centre-head.html')

def fc_view(request):
    return render(request, 'financial-counsellor.html')

def accounts_view(request):
    return render(request, 'accounts.html')

def management_view(request):
    return render(request, 'management.html')

# --- Naya API Endpoint (Database se CNB data lane ke liye) ---

def get_cnb_data(request):
    query = """
        SELECT 
            a.id AS appointment_internal_id,
            # Agat patient_id blank ya null hai to use Appointment Internal ID assign karein takiy unique identity bani rahe
            COALESCE(NULLIF(TRIM(a.paitent_id), ''), CAST(a.id AS CHAR)) AS patient_id, 
            a.wife_name AS name, 
            a.appoitmented_date AS date,
            a.councellor, 
            c.center_name, 
            d.name AS doctor_name,
            # Naye table se pehle se saved data uthana takiy refresh karne par dikhe
            r.quality,
            r.fc_comment,
            r.latest_connected_date,
            r.latest_comment
        FROM hms_appointments a
        LEFT JOIN hms_patient_procedure p 
            ON a.id = p.appointment_id
        LEFT JOIN hms_centers c 
            ON a.appoitment_for = c.center_number
        LEFT JOIN hms_doctors d 
            ON a.appoitmented_doctor = d.ID
        LEFT JOIN reports_patientcnb r
            ON COALESCE(NULLIF(TRIM(a.paitent_id), ''), CAST(a.id AS CHAR)) = r.patient_id
        WHERE a.paitent_type = 'new_patient' 
          AND a.status = 'consultation_done'
          AND p.appointment_id IS NULL
    """
    
    with connection.cursor() as cursor:
        cursor.execute(query)
        columns = [col[0] for col in cursor.description]
        results = [dict(zip(columns, row)) for row in cursor.fetchall()]

    return JsonResponse(results, safe=False)


# from .models import PatientCNB  # Remember to import your model!
@csrf_exempt 
def save_cnb_edits(request):
    if request.method == 'POST':
        try:
            data = json.loads(request.body)
            edits = data.get('edits', [])
            current_now = timezone.now() 
            
            with connection.cursor() as cursor:
                for edit in edits:
                    patient_id = edit.get('patient_id')
                    
                    if not patient_id or str(patient_id).strip() == "" or str(patient_id).lower() == "none" or str(patient_id) == "—":
                        continue 
                        
                    patient_id = str(patient_id).strip()
                    quality = edit.get('quality') or 'Cold'
                    fc_comment = edit.get('fc_comment') or ''
                    latest_comment = edit.get('latest_comment') or ''
                    
                    latest_connected_date = edit.get('latest_connected_date')
                    if not latest_connected_date or str(latest_connected_date).strip() == '':
                        latest_connected_date = None
                    
                    cursor.execute("SELECT id FROM reports_patientcnb WHERE patient_id = %s", [patient_id])
                    row = cursor.fetchone()
                    
                    if row:
                        cursor.execute("""
                            UPDATE reports_patientcnb 
                            SET quality = %s, fc_comment = %s, latest_connected_date = %s, latest_comment = %s, updated_at = %s
                            WHERE patient_id = %s
                        """, [quality, fc_comment, latest_connected_date, latest_comment, current_now, patient_id])
                    else:
                        cursor.execute("""
                            INSERT INTO reports_patientcnb (patient_id, quality, fc_comment, latest_connected_date, latest_comment, created_at, updated_at) 
                            VALUES (%s, %s, %s, %s, %s, %s, %s)
                        """, [patient_id, quality, fc_comment, latest_connected_date, latest_comment, current_now, current_now])

            return JsonResponse({'status': 'success', 'message': f'Successfully saved {len(edits)} patient records!'})
            
        except Exception as e:
            print(f"CRITICAL SQL EXECUTION ERROR: {str(e)}") 
            return JsonResponse({'status': 'error', 'message': f'Database operational error: {str(e)}'}, status=500)
            
    return JsonResponse({'status': 'error', 'message': 'Use POST.'}, status=405)


# 1. HTML Loader — Views template router
def procedure_billing_view(request):
    return render(request, 'doctor.html') 


# 2. API Endpoint — Naye section 'Procedure Billing' screen ke data ke liye (Added husband_name)
def get_procedure_billing_data(request):
    query = """
        SELECT 
            a.id AS appointment_internal_id,
            COALESCE(NULLIF(TRIM(a.paitent_id), ''), CAST(a.id AS CHAR)) AS patient_id, 
            a.wife_name AS name, 
            a.husband_name, -- Added here
            a.appoitmented_date AS date,
            p.on_date,
            p.councellor, 
            c.center_name, 
            d.name AS doctor_name,
            p.code,
            p.fees,
            IFNULL(SUM(pay.payment_done), 0) AS total_payment_done,
            (p.fees - IFNULL(SUM(pay.payment_done), 0)) AS pending_amount
        FROM hms_appointments a
        INNER JOIN hms_patient_procedure p 
            ON a.id = p.appointment_id
        LEFT JOIN hms_doctor_consultation dc 
            ON a.id = dc.appointment_id
        LEFT JOIN hms_doctors d 
            ON dc.doctor_id = d.ID 
        LEFT JOIN hms_centers c 
            ON p.billing_at = c.center_number
        LEFT JOIN hms_patient_payments pay
            ON p.receipt_number = pay.billing_id 
            AND pay.status IN ('0', '1')
        WHERE a.paitent_type = 'new_patient' 
          AND a.status = 'consultation_done'
          AND p.status IN ('pending', 'approved')
        GROUP BY 
            a.id, 
            a.paitent_id, 
            a.wife_name, 
            a.husband_name, -- Added to GROUP BY
            a.appoitmented_date, 
            p.councellor, 
            c.center_name, 
            d.name,
            p.code,      
            p.on_date,   
            p.fees       
        ORDER BY a.appoitmented_date DESC;
    """
    
    with connection.cursor() as cursor:
        cursor.execute(query)
        columns = [col[0] for col in cursor.description]
        results = [dict(zip(columns, row)) for row in cursor.fetchall()]

    # Decimal values handling
    for row in results:
        row['fees'] = float(row['fees']) if row['fees'] else 0.0
        row['total_payment_done'] = float(row['total_payment_done']) if row['total_payment_done'] else 0.0
        row['pending_amount'] = float(row['pending_amount']) if row['pending_amount'] else 0.0

    return JsonResponse(results, safe=False)


# 3. API Endpoint — Booked Patient List data pipeline (Added husband_name)
def get_dynamic_booked_patients(request):
    query = """
        SELECT 
            a.id AS appointment_internal_id,
            COALESCE(NULLIF(TRIM(a.paitent_id), ''), CAST(a.id AS CHAR)) AS patient_id, 
            a.wife_name AS name, 
            a.husband_name, -- Added here
            a.appoitmented_date AS date,
            p.on_date,
            p.councellor, 
            c.center_name, 
            d.name AS doctor_name,
            p.code,
            p.fees,
            IFNULL(SUM(pay.payment_done), 0) AS total_payment_done,
            (p.fees - IFNULL(SUM(pay.payment_done), 0)) AS pending_amount
        FROM hms_appointments a
        INNER JOIN hms_patient_procedure p 
            ON a.id = p.appointment_id
        LEFT JOIN hms_doctor_consultation dc 
            ON a.id = dc.appointment_id
        LEFT JOIN hms_doctors d 
            ON dc.doctor_id = d.ID 
        LEFT JOIN hms_centers c 
            ON p.billing_at = c.center_number
        LEFT JOIN hms_patient_payments pay
            ON p.receipt_number = pay.billing_id 
            AND pay.status IN ('0', '1')
        WHERE a.paitent_type = 'new_patient' 
          AND a.status = 'consultation_done'
          AND p.status IN ('pending', 'approved')
        GROUP BY 
            a.id, 
            a.paitent_id, 
            a.wife_name, 
            a.husband_name, -- Added to GROUP BY
            a.appoitmented_date, 
            p.councellor, 
            c.center_name, 
            d.name,
            p.code,      
            p.on_date,   
            p.fees       
        ORDER BY a.appoitmented_date DESC;
    """
    
    with connection.cursor() as cursor:
        cursor.execute(query)
        columns = [col[0] for col in cursor.description]
        results = [dict(zip(columns, row)) for row in cursor.fetchall()]

    # Critical Step: Float typecasting mapping loop to serialize numbers safely
    for row in results:
        row['fees'] = float(row['fees']) if row['fees'] else 0.0
        row['total_payment_done'] = float(row['total_payment_done']) if row['total_payment_done'] else 0.0
        row['pending_amount'] = float(row['pending_amount']) if row['pending_amount'] else 0.0

    return JsonResponse(results, safe=False)



import json
from django.shortcuts import render
from django.http import JsonResponse
from django.db import connection

# --- A. HTML Page Router view ---
def procedure_billing_view(request):
    return render(request, 'doctor.html') 


# --- B. API Endpoint: Procedure Billing screen dynamic entries ---
def get_procedure_billing_data(request):
    query = """
        SELECT 
            a.id AS appointment_internal_id,
            COALESCE(NULLIF(TRIM(a.paitent_id), ''), CAST(a.id AS CHAR)) AS patient_id, 
            a.wife_name AS name, 
            a.husband_name,
            a.appoitmented_date AS date,
            p.on_date,
            p.councellor, 
            c.center_name, 
            d.name AS doctor_name,
            p.code,
            p.fees,
            IFNULL(SUM(pay.payment_done), 0) AS total_payment_done,
            (p.fees - IFNULL(SUM(pay.payment_done), 0)) AS pending_amount
        FROM hms_appointments a
        INNER JOIN hms_patient_procedure p 
            ON a.id = p.appointment_id
        LEFT JOIN hms_doctor_consultation dc 
            ON a.id = dc.appointment_id
        LEFT JOIN hms_doctors d 
            ON dc.doctor_id = d.ID 
        LEFT JOIN hms_centers c 
            ON p.billing_at = c.center_number
        LEFT JOIN hms_patient_payments pay
            ON p.receipt_number = pay.billing_id 
            AND pay.status IN ('0', '1')
        WHERE a.paitent_type = 'new_patient' 
          AND a.status = 'consultation_done'
          AND p.status IN ('pending', 'approved')
        GROUP BY 
            a.id, a.paitent_id, a.wife_name, a.husband_name, a.appoitmented_date, 
            p.councellor, c.center_name, d.name, p.code, p.on_date, p.fees       
        ORDER BY a.appoitmented_date DESC;
    """
    with connection.cursor() as cursor:
        cursor.execute(query)
        columns = [col[0] for col in cursor.description]
        results = [dict(zip(columns, row)) for row in cursor.fetchall()]

    for row in results:
        row['fees'] = float(row['fees']) if row['fees'] else 0.0
        row['total_payment_done'] = float(row['total_payment_done']) if row['total_payment_done'] else 0.0
        row['pending_amount'] = float(row['pending_amount']) if row['pending_amount'] else 0.0

    return JsonResponse(results, safe=False)


# --- C. API Endpoint: Booked Patient List dynamic dataset (CRITICAL: Added p.receipt_number) ---
def get_dynamic_booked_patients(request):
    query = """
        SELECT 
            a.id AS appointment_internal_id,
            COALESCE(NULLIF(TRIM(a.paitent_id), ''), CAST(a.id AS CHAR)) AS patient_id, 
            a.wife_name AS name, 
            a.husband_name,
            p.receipt_number, -- YEH LINE HONI COMPULSORY THI FRONTEND SERIALIZATION KE LIYE
            a.appoitmented_date AS date,
            p.on_date,
            p.councellor, 
            c.center_name, 
            d.name AS doctor_name,
            p.code,
            p.fees,
            IFNULL(SUM(pay.payment_done), 0) AS total_payment_done,
            (p.fees - IFNULL(SUM(pay.payment_done), 0)) AS pending_amount
        FROM hms_appointments a
        INNER JOIN hms_patient_procedure p 
            ON a.id = p.appointment_id
        LEFT JOIN hms_doctor_consultation dc 
            ON a.id = dc.appointment_id
        LEFT JOIN hms_doctors d 
            ON dc.doctor_id = d.ID 
        LEFT JOIN hms_centers c 
            ON p.billing_at = c.center_number
        LEFT JOIN hms_patient_payments pay
            ON p.receipt_number = pay.billing_id 
            AND pay.status IN ('0', '1')
        WHERE a.paitent_type = 'new_patient' 
          AND a.status = 'consultation_done'
          AND p.status IN ('pending', 'approved')
        GROUP BY 
            a.id, a.paitent_id, a.wife_name, a.husband_name, p.receipt_number, a.appoitmented_date, 
            p.councellor, c.center_name, d.name, p.code, p.on_date, p.fees       
        ORDER BY a.appoitmented_date DESC;
    """
    with connection.cursor() as cursor:
        cursor.execute(query)
        columns = [col[0] for col in cursor.description]
        results = [dict(zip(columns, row)) for row in cursor.fetchall()]

    for row in results:
        row['fees'] = float(row['fees']) if row['fees'] else 0.0
        row['total_payment_done'] = float(row['total_payment_done']) if row['total_payment_done'] else 0.0
        row['pending_amount'] = float(row['pending_amount']) if row['pending_amount'] else 0.0

    return JsonResponse(results, safe=False)


# --- D. API Endpoint: Dynamic Single Joint Patient Profile Lookup ---
def get_patient_profile_detail(request):
    receipt_number = request.GET.get('receipt_number')
    if not receipt_number:
        return JsonResponse({'status': 'error', 'message': 'Unique Receipt Number token parameter missing.'}, status=400)
        
    # Python context string wrapper executing safe raw SQL parameters mapping
    joint_query = """
        SELECT 
            p.ID, p.patient_id, p.patient_phone, p.wife_name, p.wife_phone, p.wife_photo, 
            p.wife_age, p.husband_name, p.husband_phone, p.husband_age, p.husband_address, p.husband_photo,
            proc.on_date, proc.category, proc.code, proc.fees, proc.procedure_name, proc.receipt_number,
            IFNULL(SUM(pay.payment_done), 0) AS dynamic_payment_done,
            (proc.fees - IFNULL(SUM(pay.payment_done), 0)) AS dynamic_pending_amount
        FROM hms_patients p
        LEFT JOIN hms_patient_procedure proc 
            ON p.patient_id = proc.patient_id
        LEFT JOIN hms_patient_payments pay 
            ON proc.receipt_number = pay.billing_id AND pay.status IN ('0', '1')
        WHERE proc.receipt_number = %s 
          AND proc.status IN ('pending', 'approved')
        GROUP BY 
            p.ID, p.patient_id, p.patient_phone, p.wife_name, p.wife_phone, p.wife_photo, 
            p.wife_age, p.husband_name, p.husband_phone, p.husband_age, p.husband_address, p.husband_photo,
            proc.on_date, proc.category, proc.code, proc.fees, proc.procedure_name, proc.receipt_number
        LIMIT 1;
    """
    
    try:
        with connection.cursor() as cursor:
            # Python standard mapping parameters list tuple ensures %s is securely parameterized
            cursor.execute(joint_query, [receipt_number])
            row = cursor.fetchone()
            
            if row:
                columns = [col[0] for col in cursor.description]
                result_data = dict(zip(columns, row))
                
                result_data['fees'] = float(result_data['fees']) if result_data['fees'] else 0.0
                result_data['payment_done'] = float(result_data['dynamic_payment_done'])
                result_data['pending'] = float(result_data['dynamic_pending_amount'])
                
                return JsonResponse({
                    'status': 'success',
                    'data': result_data
                })
            else:
                return JsonResponse({
                    'status': 'error', 
                    'message': 'No profile or procedure logs found matching this active Receipt ID.'
                }, status=404)
                
    except Exception as e:
        print(f"CRITICAL SQL FAIL: {str(e)}")
        return JsonResponse({'status': 'error', 'message': f'Database pipeline query failed: {str(e)}'}, status=500)