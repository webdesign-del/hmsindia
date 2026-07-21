import json
import hashlib
from django.shortcuts import render, redirect
from django.http import JsonResponse
from django.db import connection
from django.views.decorators.csrf import csrf_exempt 
from django.utils import timezone
from django.contrib import messages

# ============================================================
# 1. LEGACY HMS_DOCTORS DIRECT AUTHENTICATION & SESSIONS
# ============================================================

def login_view(request):
    if 'doctor_id' in request.session:
        return redirect('doctor_view')
        
    if request.method == "POST":
        username_input = request.POST.get('username')
        password_input = request.POST.get('password')
        
        # MD5 hashing logic sequence matching standard
        password_md5 = hashlib.md5(password_input.encode('utf-8')).hexdigest()
        
        query = """
            SELECT ID, name, email, username 
            FROM hms_doctors 
            WHERE username = %s AND password = %s 
            LIMIT 1;
        """
        
        with connection.cursor() as cursor:
            cursor.execute(query, [username_input, password_md5])
            doctor_row = cursor.fetchone()
            
        if doctor_row:
            request.session['doctor_id'] = doctor_row[0]
            request.session['doctor_name'] = doctor_row[1]
            request.session['doctor_email'] = doctor_row[2]
            request.session['doctor_username'] = doctor_row[3]
            request.session['user_role'] = 'doctor'
            return redirect('doctor_view')
        else:
            messages.error(request, "Invalid username or password string context.")
            
    return render(request, "index.html")


def logout_view(request):
    request.session.flush()
    return redirect('login')


def doctor_workspace_view(request):
    if 'doctor_id' not in request.session:
        messages.error(request, "Please sign in to access the Doctor Workspace.")
        return redirect('login')
        
    logged_doctor_name = request.session.get('doctor_name', 'Dr. Consultation Workspace')
    active_center_name = "Noida Centre"

    context = {
        'active_center': active_center_name,
        'doctor_name': logged_doctor_name,
    }
    return render(request, 'doctor_workspace.html', context)


# ============================================================
# 2. STANDARD HTML PAGE ROUTERS
# ============================================================

def index(request):
    if 'doctor_id' in request.session:
        return redirect('doctor_view')
    return render(request, 'index.html')

def doctor_view(request):
    if 'doctor_id' not in request.session:
        return redirect('login')
    return render(request, 'doctor.html')

def centre_head_view(request):
    return render(request, 'centre-head.html')

def fc_view(request):
    return render(request, 'financial-counsellor.html')

def accounts_view(request):
    return render(request, 'accounts.html')

def management_view(request):
    return render(request, 'management.html')

def procedure_billing_view(request):
    if 'doctor_id' not in request.session:
        return redirect('login')
    return render(request, 'doctor.html') 


# ============================================================
# 3. CLINICAL FUNNEL (CNB) PIPELINE API ENDPOINTS
# ============================================================

def get_cnb_data(request):
    query = """
        SELECT 
            a.id AS appointment_internal_id,
            COALESCE(NULLIF(TRIM(a.paitent_id), ''), CAST(a.id AS CHAR)) AS patient_id, 
            a.wife_name AS name, 
            a.appoitmented_date AS date,
            a.councellor, 
            c.center_name, 
            d.name AS doctor_name,
            r.quality,
            r.fc_comment,
            r.latest_connected_date,
            r.latest_comment
        FROM hms_appointments a
        LEFT JOIN hms_patient_procedure p ON a.id = p.appointment_id
        LEFT JOIN hms_centers c ON a.appoitment_for = c.center_number
        LEFT JOIN hms_doctors d ON a.appoitmented_doctor = d.ID
        LEFT JOIN reports_patientcnb r ON COALESCE(NULLIF(TRIM(a.paitent_id), ''), CAST(a.id AS CHAR)) = r.patient_id
        WHERE a.paitent_type = 'new_patient' 
          AND a.status = 'consultation_done'
          AND p.appointment_id IS NULL
    """
    with connection.cursor() as cursor:
        cursor.execute(query)
        columns = [col[0] for col in cursor.description]
        results = [dict(zip(columns, row)) for row in cursor.fetchall()]
    return JsonResponse(results, safe=False)


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

            return JsonResponse({'status': 'success', 'message': f'Successfully saved {len(edits)} records!'})
        except Exception as e:
            return JsonResponse({'status': 'error', 'message': f'Database operational error: {str(e)}'}, status=500)
            
    return JsonResponse({'status': 'error', 'message': 'Use POST.'}, status=405)


# ============================================================
# 4. PATIENT-WISE REGISTRY LAYER FOR BILLING TABLES & BOOKED LISTS
# ============================================================

def get_procedure_billing_data(request):
    # FIX: Grouped strictly by patient ID details to represent a unique row entry per patient
    query = """
        SELECT 
            MIN(a.id) AS appointment_internal_id,
            COALESCE(NULLIF(TRIM(a.paitent_id), ''), CAST(a.id AS CHAR)) AS patient_id, 
            MIN(a.wife_name) AS name, 
            MIN(a.husband_name) AS husband_name,
            MIN(p.receipt_number) AS receipt_number,
            MIN(a.appoitmented_date) AS date,
            MIN(p.on_date) AS on_date,
            MIN(p.councellor) AS councellor, 
            MIN(c.center_name) AS center_name, 
            MIN(d.name) AS doctor_name,
            GROUP_CONCAT(DISTINCT p.code SEPARATOR ', ') AS code,
            SUM(p.fees) AS fees,
            IFNULL(SUM(pay.payment_done), 0) AS total_payment_done,
            (SUM(p.fees) - IFNULL(SUM(pay.payment_done), 0)) AS pending_amount
        FROM hms_appointments a
        INNER JOIN hms_patient_procedure p ON a.id = p.appointment_id
        LEFT JOIN hms_doctor_consultation dc ON a.id = dc.appointment_id
        LEFT JOIN hms_doctors d ON dc.doctor_id = d.ID 
        LEFT JOIN hms_centers c ON p.billing_at = c.center_number
        LEFT JOIN hms_patient_payments pay ON p.receipt_number = pay.billing_id AND pay.status IN ('0', '1')
        WHERE a.paitent_type = 'new_patient' 
          AND a.status = 'consultation_done'
          AND p.status IN ('pending', 'approved')
        GROUP BY COALESCE(NULLIF(TRIM(a.paitent_id), ''), CAST(a.id AS CHAR))
        ORDER BY MIN(a.appoitmented_date) DESC;
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


def get_dynamic_booked_patients(request):
    # FIX: Combined multi-procedures row inputs under a unified patient context aggregate summary
    query = """
        SELECT 
            MIN(a.id) AS appointment_internal_id,
            COALESCE(NULLIF(TRIM(a.paitent_id), ''), CAST(a.id AS CHAR)) AS patient_id, 
            MIN(a.wife_name) AS name, 
            MIN(a.husband_name) AS husband_name,
            MIN(p.receipt_number) AS receipt_number, 
            MIN(a.appoitmented_date) AS date,
            MIN(p.on_date) AS on_date,
            MIN(p.councellor) AS councellor, 
            MIN(c.center_name) AS center_name, 
            MIN(d.name) AS doctor_name,
            GROUP_CONCAT(DISTINCT p.code SEPARATOR ', ') AS code,
            SUM(p.fees) AS fees,
            IFNULL(SUM(pay.payment_done), 0) AS total_payment_done,
            (SUM(p.fees) - IFNULL(SUM(pay.payment_done), 0)) AS pending_amount
        FROM hms_appointments a
        INNER JOIN hms_patient_procedure p ON a.id = p.appointment_id
        LEFT JOIN hms_doctor_consultation dc ON a.id = dc.appointment_id
        LEFT JOIN hms_doctors d ON dc.doctor_id = d.ID 
        LEFT JOIN hms_centers c ON p.billing_at = c.center_number
        LEFT JOIN hms_patient_payments pay ON p.receipt_number = pay.billing_id AND pay.status IN ('0', '1')
        WHERE a.paitent_type = 'new_patient' 
          AND a.status = 'consultation_done'
          AND p.status IN ('pending', 'approved')
        GROUP BY COALESCE(NULLIF(TRIM(a.paitent_id), ''), CAST(a.id AS CHAR))
        ORDER BY MIN(a.appoitmented_date) DESC;
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


# ============================================================
# 5. ALL PROCEDURES LIST LOOKUP UNDER UNIQUE PATIENT PROFILE DETAILS
# ============================================================

def get_patient_profile_detail(request):
    receipt_number = request.GET.get('receipt_number')
    if not receipt_number:
        return JsonResponse({'status': 'error', 'message': 'Unique Receipt Number token parameter missing.'}, status=400)
        
    # Step A: Pehle primary demographic matrix fetch karein patient profile context data se
    demographics_query = """
        SELECT 
            p.ID, p.patient_id, p.patient_phone, p.wife_name, p.wife_phone, p.wife_photo, 
            p.wife_age, p.husband_name, p.husband_phone, p.husband_age, p.husband_address, p.husband_photo
        FROM hms_patients p
        INNER JOIN hms_patient_procedure proc ON p.patient_id = proc.patient_id
        WHERE proc.receipt_number = %s
        LIMIT 1;
    """
    
    # Step B: Uss unique patient_id ke saare procedures ki sequential items list pull karein
    procedures_query = """
        SELECT 
            proc.on_date, proc.category, proc.code, proc.fees, proc.procedure_name, proc.receipt_number,
            IFNULL(SUM(pay.payment_done), 0) AS dynamic_payment_done,
            (proc.fees - IFNULL(SUM(pay.payment_done), 0)) AS dynamic_pending_amount
        FROM hms_patient_procedure proc
        LEFT JOIN hms_patient_payments pay ON proc.receipt_number = pay.billing_id AND pay.status IN ('0', '1')
        WHERE proc.patient_id = %s AND proc.status IN ('pending', 'approved')
        GROUP BY 
            proc.on_date, proc.category, proc.code, proc.fees, proc.procedure_name, proc.receipt_number
        ORDER BY proc.on_date ASC;
    """
    
    try:
        with connection.cursor() as cursor:
            cursor.execute(demographics_query, [receipt_number])
            demo_row = cursor.fetchone()
            
            if not demo_row:
                return JsonResponse({'status': 'error', 'message': 'No profile match found.'}, status=404)
                
            demo_cols = [col[0] for col in cursor.description]
            result_data = dict(zip(demo_cols, demo_row))
            
            # Ab saare linked procedures details nikalte hain
            cursor.execute(procedures_query, [result_data['patient_id']])
            proc_rows = cursor.fetchall()
            proc_cols = [col[0] for col in cursor.description]
            
            procedures_list = []
            total_fees = 0.0
            total_paid = 0.0
            total_pending = 0.0
            
            for row in proc_rows:
                p_dict = dict(zip(proc_cols, row))
                p_dict['fees'] = float(p_dict['fees']) if p_dict['fees'] else 0.0
                p_dict['payment_done'] = float(p_dict['dynamic_payment_done'])
                p_dict['pending'] = float(p_dict['dynamic_pending_amount'])
                
                # Global aggregates update
                total_fees += p_dict['fees']
                total_paid += p_dict['payment_done']
                total_pending += p_dict['pending']
                
                procedures_list.append(p_dict)
            
            # Legacy engine variables keys to make sure frontend template doesn't crash
            result_data['fees'] = total_fees
            result_data['payment_done'] = total_paid
            result_data['pending'] = total_pending
            result_data['code'] = procedures_list[0]['code'] if procedures_list else 'OPD'
            result_data['category'] = procedures_list[0]['category'] if procedures_list else 'General'
            result_data['procedure_name'] = procedures_list[0]['procedure_name'] if procedures_list else 'Consultation'
            result_data['on_date'] = procedures_list[0]['on_date'] if procedures_list else None
            
            return JsonResponse({
                'status': 'success',
                'data': result_data,
                'procedures': procedures_list  # Pure distinct procedures package list
            })
            
    except Exception as e:
        return JsonResponse({'status': 'error', 'message': f'Query failed: {str(e)}'}, status=500)