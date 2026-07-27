/* ============================================================
   INDIA IVF — HMS Patient Journey & Collections
   Shared application logic + render engine (Unified Dynamic Production Build)
   ============================================================ */

/* ============================================================
   MODULE 1: CONFIGURATION & CONSTANTS
   Data models, navigation routes, and global lookups
   ============================================================ */

const BOOKED_GROUP = {
    group: 'booked', label: 'Booked Patient', ic: '◷', children: [
        { id: 'booked-patient-list', label: 'Booked Patient List', ic: '≡' },
        { id: 'patient-journey', label: 'Booked Patient Journey', ic: '◷' }
    ]
};

const NAV = {
    doctor: [
        { id: 'doc-dashboard', label: 'Dashboard', ic: '▦' },
        { id: 'prebook-scheduled', label: 'Appointment Scheduled', ic: '📅' },
        { id: 'prebook-missed', label: 'Missed Appointments', ic: '⚑' },
        { id: 'prebook-cnb', label: 'Consulted Not Booked', ic: '◐' },
        BOOKED_GROUP,
        { id: 'doc-milestone', label: 'Milestone Form', ic: '✚' },
        { id: 'doc-achievement', label: 'Achievement Table', ic: '▤' }
    ],
    ch: [
        { id: 'ch-dashboard', label: 'Dashboard', ic: '▦' },
        { id: 'prebook-scheduled', label: 'Appointment Scheduled', ic: '📅' },
        { id: 'prebook-missed', label: 'Missed Appointments', ic: '⚑' },
        { id: 'prebook-cnb', label: 'Consulted Not Booked', ic: '◐' },
        BOOKED_GROUP,
        { id: 'ch-collections', label: 'Expected Collections', ic: '₹' },
        { id: 'ch-achievement', label: 'Achievement Table', ic: '▤' },
        { id: 'ch-aging', label: 'Aging Buckets', ic: '⏳' }
    ],
    fc: [
        { id: 'fc-dashboard', label: 'My Patients', ic: '▦' },
        { id: 'prebook-scheduled', label: 'Appointment Scheduled', ic: '📅' },
        { id: 'prebook-missed', label: 'Missed Appointments', ic: '⚑' },
        { id: 'prebook-cnb', label: 'Consulted Not Booked', ic: '◐' },
        BOOKED_GROUP,
        { id: 'fc-actions', label: 'Action Queue', ic: '✓' },
        { id: 'fc-refund', label: 'Refund Queue', ic: '↩' },
        { id: 'fc-wallet', label: 'Patient Wallet', ic: '◈' }
    ],
    accounts: [
        { id: 'acc-dashboard', label: 'Collections', ic: '▦' },
        { id: 'prebook-scheduled', label: 'Appointment Scheduled', ic: '📅' },
        { id: 'prebook-missed', label: 'Missed Appointments', ic: '⚑' },
        { id: 'prebook-cnb', label: 'Consulted Not Booked', ic: '◐' },
        BOOKED_GROUP,
        { id: 'acc-refund', label: 'Refund Queue', ic: '↩' },
        { id: 'acc-aging', label: 'Company Aging', ic: '⏳' },
        { id: 'acc-triggers', label: 'Red Triggers', ic: '⚑' }
    ],
    mgmt: [
        { id: 'mgmt-dashboard', label: 'Dashboard', ic: '▦' },
        { id: 'prebook-scheduled', label: 'Appointment Scheduled', ic: '📅' },
        { id: 'prebook-missed', label: 'Missed Appointments', ic: '⚑' },
        { id: 'prebook-cnb', label: 'Consulted Not Booked', ic: '◐' },
        BOOKED_GROUP,
        { id: 'mgmt-centres', label: 'Centre Comparison', ic: '▥' },
        { id: 'mgmt-aging', label: 'Aging Snapshot', ic: '⏳' },
        { id: 'mgmt-triggers', label: 'Red Trigger Pile-up', ic: '⚑' },
        { id: 'mgmt-approvals', label: 'Approval Queue', ic: '⚖' }
    ]
};

var HIDDEN_TRACKS = new Set();
var COLLAPSE_DONE = false;

const TRACK_DEFS = [
  { key: 'clinical-stage', label: 'Ideal Clinical Stage', color: 'var(--primary)' },
  { key: 'procedure', label: 'Procedure', color: 'var(--gold)' },
  { key: 'clinical-form', label: 'Clinical Form', color: 'var(--blue)' },
  { key: 'embryology-form', label: 'Embryology', color: '#8a64b8' },
  { key: 'financial', label: 'Financial', color: 'var(--green)' }
];

var BPL_CATMAP = {
  'IIC-2603-118': 'IVF with Bed', 'IIC-2603-126': 'IVF without Bed', 'IIC-2604-203': 'IVF with Bed',
  'IIC-2604-211': 'Non IVF with Bed', 'IIC-2605-088': 'OPD', 'IIC-2602-051': 'IVF with Bed',
  'IIC-2601-019': 'IVF with Bed', 'IIC-2603-140': 'IVF with Bed', 'IIC-2604-225': 'Non IVF without Bed',
  'IIC-2605-101': 'OPD', 'IIC-2603-160': 'IVF with Bed', 'IIC-2604-240': 'Non IVF with Bed',
  'IIC-2603-175': 'IVF with Bed', 'IIC-2605-115': 'IVF without Bed', 'IIC-2604-260': 'Non IVF without Bed',
  'IIC-2602-070': 'IVF with Bed'
};

var JFORM_DEFS = {
  c_kyc: { l: 'KYC', f: ['Patient full name', 'Government ID type', 'ID number', 'Address', 'Emergency contact'] },
  c_initial: { l: 'Initial Assessment Sheet', f: ['Chief complaint', 'Menstrual / obstetric history', 'Examination findings', 'Provisional diagnosis', 'Advice'] },
  c_opd_presc: { l: 'OPD Prescription', f: ['Diagnosis', 'Medications', 'Investigations advised', 'Follow-up date'] },
  c_withdrawal: { l: 'Withdrawal Format', f: ['Indication', 'Consent obtained', 'Witness name', 'Date'] },
  c_oi: { l: 'Ovulation Induction Protocol', f: ['Protocol type', 'Start date', 'Gonadotropin and dose', 'Monitoring schedule'] },
  c_trigger: { l: 'Trigger Module', f: ['Trigger drug', 'Dose', 'Date and time given', 'Planned OPU date'] },
  c_admission: { l: 'Admission Form', f: ['Admission date', 'Ward / Bed', 'Admitting diagnosis', 'Consultant'] },
  c_ga: { l: 'GA / Anaesthesia Record', f: ['ASA grade', 'Pre-anaesthetic check', 'Anaesthetic plan', 'Consent signed'] },
  c_opu: { l: 'Clinical Ovum Pick-Up (OPU)', f: ['Follicles aspirated', 'Oocytes retrieved', 'Complications', 'Operator'] },
  c_opu_disch: { l: 'OPU Discharge Summary', f: ['Condition at discharge', 'Medications on discharge', 'Follow-up date', 'Instructions'] },
  c_pre_et: { l: 'Pre-Embryo Transfer', f: ['Endometrial thickness', 'Embryo readiness', 'Transfer plan', 'Consent signed'] },
  c_et: { l: 'Embryo Transfer', f: ['Catheter type', 'Number transferred', 'Difficulty', 'Operator'] },
  c_et_disch: { l: 'Embryo Transfer Discharge', f: ['Condition at discharge', 'Medications on discharge', 'Follow-up date', 'Instructions'] },
  c_bhcg: { l: 'Serum Beta-hCG', f: ['Sample date', 'Beta-hCG value (mIU/ml)', 'Interpretation'] },
  c_outcome: { l: 'Outcome', f: ['Clinical pregnancy', 'Cardiac activity', 'Remarks', 'Date'] },
  f_pkg_estimate: { l: 'Pkg Estimate', f: ['Recommended package', 'Package code', 'Estimated amount', 'Discount offered', 'Counsellor name', 'Valid till', 'Remarks'] },
  e_oocyte_d3: { l: 'Oocyte-Embryo Record (D3)', f: ['Date', 'Embryologist', 'Oocyte count', 'Grade', 'Remarks'] },
  e_sperm_prep: { l: 'Sperm Preparation', f: ['Date', 'Embryologist', 'Count / motility', 'Preparation method', 'Remarks'] },
  e_emb_opu: { l: 'Embryology OPU', f: ['Date', 'Embryologist', 'Oocytes retrieved', 'Maturity (MII)', 'Remarks'] },
  e_emb_record: { l: 'Embryology Embryo Record', f: ['Date', 'Embryologist', 'No. of embryos', 'Grade', 'Remarks'] },
  e_emb_transfer: { l: 'Embryology Embryo Transfer', f: ['Date', 'Embryos transferred', 'Grade', 'Cryo-preserved', 'Remarks'] }
};

var JSTAGE_FORMS = {
  'First Consult': { c: [['c_kyc', 0], ['c_initial', 0]], e: [] },
  'Package Estimate': { c: [], e: [] },
  'CNB Visits': { c: [['c_opd_presc', 0]], e: [] },
  'Booked': { c: [['c_withdrawal', 10]], e: [['e_oocyte_d3', 10], ['e_sperm_prep', 10]] },
  'Pre-Procedure': { c: [], e: [] },
  'Ovarian Stimulation': { c: [['c_oi', 50]], e: [] },
  'Endometrial Preparation': { c: [], e: [] },
  'Trigger': { c: [['c_trigger', 100], ['c_admission', 100], ['c_ga', 100]], e: [] },
  'OPU': { c: [['c_opu', 100], ['c_opu_disch', 100]], e: [['e_emb_opu', 100], ['e_emb_record', 100]] },
  'Progesterone Change': { c: [], e: [] },
  'Embryo Transfer': { c: [['c_pre_et', 100], ['c_et', 100], ['c_et_disch', 100]], e: [['e_emb_record', 100], ['e_emb_transfer', 100]] },
  'B-HCG': { c: [['c_bhcg', 100]], e: [] },
  'Cardiac Activity': { c: [['c_outcome', 100]], e: [] }
};

var PKG_CAT = {
  'IP222': 'IVF with Bed', 'IP11': 'IVF with Bed', 'Donor': 'IVF with Bed', 'Composite': 'Non IVF without Bed',
  'FET': 'IVF without Bed', 'IUI': 'Non IVF with Bed', 'ICSI': 'Non IVF without Bed', 'OPD': 'OPD'
};

var CAT_PRIORITY = ['IVF with Bed', 'Non IVF with Bed', 'Non IVF without Bed', 'IVF without Bed', 'OPD'];

var PKG_NAME = {
  'IP222': 'Comprehensive IVF (Self)', 'IP11': 'IVF Self Cycle', 'Donor': 'Donor IVF Programme',
  'Composite': 'Composite Add-on', 'FET': 'Frozen Embryo Transfer', 'IUI': 'Intrauterine Insemination',
  'ICSI': 'ICSI (Embryology Lab)', 'OPD': 'OPD Consultation', 'IP05': 'Frozen Embryo Transfer'
};

var PKG_PRICE = {
  'IP222': 260000, 'IP11': 195000, 'Donor': 390000, 'Composite': 50000,
  'FET': 58000, 'IUI': 22000, 'ICSI': 45000, 'OPD': 2000, 'IP05': 55000
};

var J_STAGE_RANGE = { 3: [0, 0], 4: [1, 9], 5: [10, 10], 6: [12, 18], 7: [18, 18], 8: [20, 20], 9: [22, 24], 10: [25, 28], 11: [42, 42], 12: [56, 56] };

var JCOMM_META = { diet: { lbl: 'Diet chart', ic: '🍎' }, psy: { lbl: 'Counselling', ic: '🧠' } };
var JCOMP_META = { video: { lbl: 'Video testimonial', ic: '🎥' }, consent: { lbl: 'Consent form', ic: '✍️' }, affidavit: { lbl: 'Affidavit', ic: '📜' } };
var JCOMP_MAP = { 'Booked': ['affidavit'], 'OPU': ['consent'], 'Cardiac Activity': ['video'] };

var JDONE = {}, JVALUES = {};

/* ============================================================
   MODULE 2: ROUTING & NAVIGATION
   Sidebar builder, screen switching, subtabs
   ============================================================ */

function fill(id, html) { const e = document.getElementById(id); if (e) e.innerHTML = html; }

function buildSidebar(role) {
    const nav = document.getElementById('sideNav'); if (!nav) return;
    let firstSet = false;
    const html = NAV[role].map(n => {
        if (n.children) {
            const gid = 'grp-' + n.group;
            const inner = n.children.map(c => {
                const active = (!firstSet) ? (firstSet = true, ' active') : '';
                return '<div class="side-link side-sub' + active + '" data-screen="' + c.id + '">' +
                    '<span class="ic">' + c.ic + '</span>' + c.label + '</div>';
            }).join('');
            return '<div class="side-group" id="' + gid + '"><div class="side-grp-head" data-grp="' + n.group + '"><span class="ic">' + n.ic + '</span>' + n.label + '<span class="side-grp-caret">▾</span></div><div class="side-grp-children">' + inner + '</div></div>';
        }
        const active = (!firstSet) ? (firstSet = true, ' active') : '';
        return '<div class="side-link' + active + '" data-screen="' + n.id + '"><span class="ic">' + n.ic + '</span>' + n.label + '</div>';
    }).join('');
    nav.innerHTML = html;
    nav.querySelectorAll('.side-link').forEach(l => l.onclick = () => showScreen(l.dataset.screen));
    nav.querySelectorAll('.side-grp-head').forEach(h => {
        h.onclick = () => { const g = h.parentElement; g.classList.toggle('collapsed'); };
    });
}

function showScreen(id) {
    if (id === 'prebook-scheduled' || id === 'prebook-missed' || id === 'prebook-cnb') {
        prebookOpen(id.slice(8)); return;
    }
    document.querySelectorAll('.screen').forEach(s => s.classList.toggle('active', s.id === id));
    document.querySelectorAll('.side-link').forEach(l => l.classList.toggle('active', l.dataset.screen === id));
    if (id === 'booked-patient-list' && typeof renderBookedList === 'function') renderBookedList();
    try { window.scrollTo(0, 0); } catch (e) {}
}

function subSwitch(group, idx) {
  document.querySelectorAll('[data-sub="' + group + '"]').forEach((t, i) => t.classList.toggle('active', i === idx));
  document.querySelectorAll('[data-panel="' + group + '"]').forEach((p, i) => p.style.display = (i === idx ? 'block' : 'none'));
}

function backToDashboard() {
  const r = document.body.dataset.role;
  const d = { doctor: 'doc-dashboard', ch: 'ch-dashboard', fc: 'fc-dashboard', accounts: 'acc-dashboard', mgmt: 'mgmt-dashboard' }[r];
  if (d) showScreen(d);
}

/* ============================================================
   MODULE 3: GENERAL HELPERS & UI CHARTS
   ============================================================ */

function hbar(label, pct, color, valText) {
  const w = Math.max(4, Math.min(100, pct));
  return '<div class="hbar"><div class="hbar-lbl">' + label + '</div>' +
    '<div class="hbar-track"><div class="hbar-fill ' + color + '" style="width:' + w + '%">' + pct + '%</div></div>' +
    '<div class="hbar-val">' + valText + '</div></div>';
}

function donut(pct, label) {
  return '<div class="donut" style="background:conic-gradient(var(--primary) ' + pct + '%, var(--surface-2) 0)">' +
    '<div class="donut-c"><div class="v">' + pct + '%</div><div class="l">' + label + '</div></div></div>';
}

function sparkline(arr) {
  const mx = Math.max.apply(null, arr);
  return '<div class="spark">' + arr.map((v, i) =>
    '<span class="' + (i === arr.length - 1 ? 'hi' : '') + '" style="height:' + Math.round(v / mx * 100) + '%"></span>').join('') + '</div>';
}

function ptCell(p, showCentre) {
  return '<td><div class="pt-name">' + p.name + '</div><div class="pt-id">' + p.id +
    (showCentre ? ' · ' + p.centre : '') + '</div></td>';
}

function triggerPill(type) {
  const m = {
    'Missed collection': 'pill-red', 'OPU miss': 'pill-red', 'Gate override': 'pill-amber',
    'Reconciliation pending': 'pill-blue', 'Stim 12-day cap': 'pill-amber'
  };
  return '<span class="pill ' + (m[type] || 'pill-grey') + '"><span class="dot"></span>' + type + '</span>';
}

function flagColor(f) { return f === 'green' ? 'var(--green)' : f === 'amber' ? 'var(--amber)' : 'var(--red)'; }
function jStatusPill(s) {
  if (s === 'done') return '<span class="pill pill-green"><span class="dot"></span>Completed</span>';
  if (s === 'current') return '<span class="pill pill-amber"><span class="dot"></span>In progress</span>';
  return '<span class="pill pill-grey"><span class="dot"></span>Upcoming</span>';
}

function cnbQualityPill(q) {
    const map = { Hot: '#dc2626', Cold: '#2563eb', Dead: '#6b7280' };
    const bg = { Hot: '#fee2e2', Cold: '#dbeafe', Dead: '#e5e7eb' };
    const c = map[q] || '#6b7280', b = bg[q] || '#e5e7eb';
    return '<span style="display:inline-flex;align-items:center;gap:4px;padding:2px 8px;border-radius:11px;font-size:11px;font-weight:600;background:' + b + ';color:' + c + '"><span style="width:6px;height:6px;border-radius:50%;background:' + c + '"></span>' + q + '</span>';
}

function jEsc(s) { return String(s == null ? '' : s).replace(/[&<>"]/g, function (c) { return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;' }[c]; }); }
function triggerScore(t) { return (t.days || 0) * (t.value || 0) / 1000; }
if (typeof fmtDate === 'undefined') { window.fmtDate = function(d) { return d ? d : '—'; }; }

/* ============================================================
   MODULE 4: PREBOOK FUNNEL — API INTEGRATION & PAGINATION
   ============================================================ */
const CNB_EDITS = {};
function cnbGet(id, field, fallback) { return (CNB_EDITS[id] && CNB_EDITS[id][field] != null) ? CNB_EDITS[id][field] : fallback; }
function cnbSet(id, field, val) { if (!CNB_EDITS[id]) CNB_EDITS[id] = {}; CNB_EDITS[id][field] = val; }

let globalCNBData = [];
let filteredCNBData = [];
let currentPage = 1;
const rowsPerPage = 50;

function prebookOpen(type) {
    document.querySelectorAll('.screen').forEach(s => s.classList.toggle('active', s.id === 'prebook-list'));
    document.querySelectorAll('.side-link').forEach(l => l.classList.toggle('active', l.dataset.screen === 'prebook-' + type));
    try { window.scrollTo(0, 0); } catch (e) {}
    const sel = document.getElementById('prebookRange');
    const range = sel ? sel.value : 'today';
    renderPrebookList(type, range);
}

function renderPrebookList(type, range) {
    const labels = { scheduled: 'Appointments Scheduled', missed: 'Missed Appointments', cnb: 'Consulted Not Booked' };
    const tt = document.getElementById('prebookTitle'); if (tt) tt.textContent = labels[type];
    const wrap = document.getElementById('prebookListBody'); if (!wrap) return;

    if (type === 'cnb') {
        if (globalCNBData.length === 0) {
            wrap.innerHTML = '<div style="padding:20px; text-align:center; color:var(--text-soft)">Loading live data...</div>';
            fetch('/api/get_cnb_data/')
                .then(res => res.json())
                .then(apiData => {
                    globalCNBData = apiData; filteredCNBData = [...globalCNBData]; currentPage = 1; setupUIAndRender(wrap);
                })
                .catch(err => {
                    console.error("Error fetching CNB data:", err);
                    wrap.innerHTML = '<div class="empty-note" style="margin:14px; color:red;">Failed to load data from server.</div>';
                });
        } else {
            currentPage = 1; setupUIAndRender(wrap);
        }
    }
}

function setupUIAndRender(wrap) {
    if (!document.getElementById('cnbFilterBar')) {
        wrap.innerHTML = `
            <div id="cnbFilterBar" style="padding: 15px; display: flex; gap: 10px; flex-wrap: wrap; background: var(--surface); border-bottom: 1px solid var(--border-soft); align-items: center;">
                <input type="text" id="filterPatientId" placeholder="Filter Patient ID..." oninput="applyFilters()" style="flex: 1; min-width: 120px; padding: 6px 12px; border: 1px solid var(--border-soft); border-radius: 4px;">
                <input type="text" id="filterName" placeholder="Filter Patient Name..." oninput="applyFilters()" style="flex: 1; min-width: 150px; padding: 6px 12px; border: 1px solid var(--border-soft); border-radius: 4px;">
                <input type="text" id="filterDoctor" placeholder="Filter Doctor..." oninput="applyFilters()" style="flex: 1; min-width: 150px; padding: 6px 12px; border: 1px solid var(--border-soft); border-radius: 4px;">
                <input type="text" id="filterCenter" placeholder="Filter Center..." oninput="applyFilters()" style="flex: 1; min-width: 150px; padding: 6px 12px; border: 1px solid var(--border-soft); border-radius: 4px;">
                <input type="date" id="filterDate" onchange="applyFilters()" style="padding: 6px 12px; border: 1px solid var(--border-soft); border-radius: 4px;">
                <button onclick="saveCNBEdits()" style="padding: 6px 16px; background-color: #2563eb; color: white; border: none; border-radius: 4px; font-weight: bold; cursor: pointer;">Save Edits</button>
            </div>
            <div id="cnbTableContainer"></div>
        `;
    }
    renderTablePage();
}

window.applyFilters = function() {
    const idVal = (document.getElementById('filterPatientId').value || '').toLowerCase();
    const nameVal = (document.getElementById('filterName').value || '').toLowerCase();
    const doctorVal = (document.getElementById('filterDoctor').value || '').toLowerCase();
    const centerVal = (document.getElementById('filterCenter').value || '').toLowerCase();
    const dateVal = document.getElementById('filterDate').value || '';

    filteredCNBData = globalCNBData.filter(p => {
        const pId = String(p.patient_id || p.paitent_id || p.id || '').toLowerCase();
        const pName = (p.name || '').toLowerCase();
        const pDoctor = (p.doctor_name || p.doctor || '').toLowerCase();
        const pCenter = (p.center_name || p.center || '').toLowerCase();
        return pId.includes(idVal) && pName.includes(nameVal) && pDoctor.includes(doctorVal) && pCenter.includes(centerVal) && (dateVal === '' || p.date.startsWith(dateVal));
    });
    currentPage = 1; renderTablePage();
};

function renderTablePage() {
    const tableContainer = document.getElementById('cnbTableContainer'); if (!tableContainer) return;
    const ts = document.getElementById('prebookSub');
    if (ts) ts.textContent = `Total ${filteredCNBData.length} patient(s) · Page ${currentPage} of ${Math.max(1, Math.ceil(filteredCNBData.length / rowsPerPage))}`;

    const startIndex = (currentPage - 1) * rowsPerPage;
    const endIndex = startIndex + rowsPerPage;
    const paginatedData = filteredCNBData.slice(startIndex, endIndex);

    const head = '<thead><tr><th>SN</th><th>Patient ID</th><th>Patient Name</th><th>Date of Consult</th><th>Centre</th><th>Doctor</th><th>Appointment For</th><th>FC Name</th><th>Quality</th><th>FC Comment</th><th>Latest Connected Date</th><th>Latest Comment</th></tr></thead>';
    const body = paginatedData.length ? paginatedData.map((p, index) => {
        const patientId = String(p.patient_id || p.appointment_internal_id || '').trim();
        const q = cnbGet(patientId, 'quality', p.quality || 'Cold');
        const fcc = cnbGet(patientId, 'fcComment', p.fc_comment || '');
        const lc = cnbGet(patientId, 'lastConn', p.latest_connected_date || '');
        const lcm = cnbGet(patientId, 'lastComment', p.latest_comment || '');
        const opt = q => ['Hot', 'Cold', 'Dead'].map(o => '<option value="' + o + '"' + (o === q ? ' selected' : '') + '>' + o + '</option>').join('');
        return '<tr><td>' + (startIndex + index + 1) + '</td><td><a class="iic-link" onclick="prebookToJourney(\'\', \'' + patientId + '\')">' + patientId + '</a></td><td class="strong">' + (p.name || 'Unknown') + '</td><td>' + fmtDate(p.date) + '</td><td>' + (p.center_name || '—') + '</td><td>' + (p.doctor_name || '—') + '</td><td>' + (p.appoitment_for || '—') + '</td><td>' + (p.councellor || '—') + '</td><td><select class="cnb-q-sel" data-id="' + patientId + '" onchange="cnbSet(this.dataset.id,\'quality\',this.value); renderTablePage();">' + opt(q) + '</select><div style="margin-top:3px">' + cnbQualityPill(q) + '</div></td><td><textarea class="cnb-cmt" rows="2" data-id="' + patientId + '" oninput="cnbSet(this.dataset.id,\'fcComment\',this.value)">' + fcc.replace(/"/g, '&quot;') + '</textarea></td><td><input type="date" class="cnb-date" data-id="' + patientId + '" value="' + lc + '" oninput="cnbSet(this.dataset.id,\'lastConn\',this.value)"/></td><td><textarea class="cnb-cmt" rows="2" data-id="' + patientId + '" oninput="cnbSet(this.dataset.id,\'lastComment\',this.value)">' + lcm.replace(/"/g, '&quot;') + '</textarea></td></tr>';
    }).join('') : '<tr><td colspan="12"><div class="empty-note" style="margin:14px">No data found matching your filters.</div></td></tr>';

    tableContainer.innerHTML = '<table class="tbl cnb-tbl">' + head + '<tbody id="prebookRows">' + body + '</tbody></table>' + `
      <div style="padding: 15px; display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--border-soft); background: var(--surface);">
          <div>Showing ${filteredCNBData.length > 0 ? startIndex + 1 : 0} to ${Math.min(endIndex, filteredCNBData.length)} of ${filteredCNBData.length} records</div>
          <div style="display: flex; gap: 8px;"><button class="btn btn-ghost btn-sm" onclick="changePage(-1)" ${currentPage === 1 ? 'disabled' : ''}>← Previous</button><button class="btn btn-ghost btn-sm" onclick="changePage(1)" ${currentPage === Math.max(1, Math.ceil(filteredCNBData.length / rowsPerPage)) ? 'disabled' : ''}>Next →</button></div>
      </div>`;
}

window.changePage = function(dir) {
    currentPage += dir; renderTablePage();
    try { window.scrollTo({ top: document.querySelector('.card-body.flush').offsetTop - 50, behavior: 'smooth' }); } catch (e) {}
};

window.saveCNBEdits = function() {
    if (Object.keys(CNB_EDITS).length === 0) { alert("No changes made."); return; }
    const payload = Object.keys(CNB_EDITS).map(id => ({ patient_id: String(id).trim(), quality: CNB_EDITS[id].quality || "Cold", fc_comment: CNB_EDITS[id].fcComment || "", latest_connected_date: CNB_EDITS[id].lastConn || "", latest_comment: CNB_EDITS[id].lastComment || "" }));
    fetch('/api/save_cnb_edits/', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ edits: payload }) })
        .then(res => res.json().then(d => { if (!res.ok) throw new Error(d.message); return d; }))
        .then(() => {
            alert("Data successfully saved!");
            payload.forEach(item => {
                let found = globalCNBData.find(x => String(x.patient_id).trim() === item.patient_id);
                if (found) { Object.assign(found, item); } delete CNB_EDITS[item.patient_id];
            });
            renderTablePage();
        }).catch(err => alert("Failed to save: " + err.message));
};

/* ============================================================
   MODULE 5: DYNAMIC BOOKED PATIENT LIST ENGINE
   ============================================================ */
let dbBookedPatientsPool = [];
let filteredBookedPatients = [];
let bplCurrentPage = 1;
const bplRowsPerPage = 50;
let BPL_VIEW = 'list';

document.addEventListener("DOMContentLoaded", function() {
    const bBodyCheck = document.getElementById('bplRows'); if (!bBodyCheck) return;
    fetch('/api/get_dynamic_booked_patients/')
        .then(res => res.json())
        .then(data => {
            dbBookedPatientsPool = data.map(item => ({
                id: String(item.patient_id).trim(),
                receiptNumber: item.receipt_number ? String(item.receipt_number).trim() : "",
                wifeName: item.name || 'Unknown', husbandName: item.husband_name || '—',
                firstConsultDate: item.date || '—', bookingDate: item.on_date || '—',
                centre: item.center_name || '—', doctor: item.doctor_name || '—', pkg: item.code || 'Standard Procedure',
                net: parseFloat(item.fees || 0), collected: parseFloat(item.total_payment_done || 0), pending: parseFloat(item.pending_amount || 0), 
                stageNo: item.stage_no || 2, 
                stageName: item.stage_name || 'Booking Done'
            }));
            filteredBookedPatients = [...dbBookedPatientsPool];
            bplPopulateDynamicFilters(); renderBookedList();
        }).catch(() => { if (bBodyCheck) bBodyCheck.innerHTML = `<tr><td colspan="13" style="color:red; text-align:center;">Database Fetch Failed.</td></tr>`; });
});

function bplPopulateDynamicFilters() {
    const pSel = document.getElementById('bplPkg'); if (pSel && pSel.dataset.filled !== '1') { [...new Set(dbBookedPatientsPool.map(p => p.pkg))].sort().forEach(pk => { const o = document.createElement('option'); o.value = pk; o.textContent = pk; pSel.appendChild(o); }); pSel.dataset.filled = '1'; }
    const cSel = document.getElementById('bplCentre'); if (cSel && cSel.dataset.filled !== '1') { [...new Set(dbBookedPatientsPool.map(p => p.centre))].sort().forEach(c => { const o = document.createElement('option'); o.value = c; o.textContent = c; cSel.appendChild(o); }); cSel.dataset.filled = '1'; }
}

window.filterBookedData = function() { renderBookedList(true); };

window.renderBookedList = function(resetPage = false) {
    const bRowsContainer = document.getElementById('bplRows'); if (!bRowsContainer) return;
    if (resetPage) bplCurrentPage = 1;

    const centreVal = (document.getElementById('bplCentre') || {}).value || '';
    const pkgVal = (document.getElementById('bplPkg') || {}).value || '';
    const searchVal = ((document.getElementById('bplSearch') || {}).value || '').trim().toLowerCase();
    const bkFrom = document.getElementById('bplBkFrom') ? document.getElementById('bplBkFrom').value : '';
    const bkTo = document.getElementById('bplBkTo') ? document.getElementById('bplBkTo').value : '';

    filteredBookedPatients = dbBookedPatientsPool.filter(p => {
        if (centreVal && p.centre !== centreVal) return false;
        if (pkgVal && p.pkg !== pkgVal) return false;
        if (bkFrom && p.bookingDate < bkFrom) return false;
        if (bkTo && p.bookingDate > bkTo) return false;
        if (searchVal) { return (p.wifeName + ' ' + p.husbandName + ' ' + p.id + ' ' + p.doctor + ' ' + p.pkg).toLowerCase().includes(searchVal); }
        return true;
    });

    const totalRecords = filteredBookedPatients.length;
    const totalPages = Math.max(1, Math.ceil(totalRecords / bplRowsPerPage));
    const cnt = document.getElementById('bplCount'); if (cnt) cnt.textContent = `· Page ${bplCurrentPage} of ${totalPages} (${totalRecords} records)`;

    const startIndex = (bplCurrentPage - 1) * bplRowsPerPage;
    const paginatedChunk = filteredBookedPatients.slice(startIndex, startIndex + bplRowsPerPage);

    bRowsContainer.innerHTML = paginatedChunk.map((p, index) => '<tr><td><b>' + (startIndex + index + 1) + '</b></td><td><span class="bpl-iic" style="color:#2563eb; font-weight:600; cursor:pointer;" onclick="prebookToJourney(\'' + p.receiptNumber + '\', \'' + p.id + '\')">' + p.id + '</span></td><td class="strong">' + p.wifeName + '</td><td>' + p.husbandName + '</td><td>' + p.firstConsultDate + '</td><td>' + p.bookingDate + '</td><td>' + p.centre + '</td><td>' + p.doctor + '</td><td><code>' + p.pkg + '</code></td><td><span class="bpl-stage-pill">' + p.stageNo + ' · ' + p.stageName + '</span></td><td class="col-num">₹' + p.net.toFixed(2) + '</td><td class="col-num" style="color:#16a34a;">₹' + p.collected.toFixed(2) + '</td><td class="col-num" style="color:#dc2626; font-weight:600;">₹' + Math.max(0, p.pending).toFixed(2) + '</td></tr>').join('') || '<tr><td colspan="13" style="text-align:center;">No active patient records found.</td></tr>';

    renderBookedCards(paginatedChunk); injectBplPaginationControls(totalRecords, totalPages, startIndex, startIndex + bplRowsPerPage);
};

function renderBookedCards(chunk) {
    const wrap = document.getElementById('bplCardsWrap'); if (!wrap) return;
    wrap.innerHTML = chunk.map(function(p) {
        const pct = p.net > 0 ? Math.min(100, Math.round((p.collected / p.net) * 100)) : 0;
        return `<div class="bpl-card" style="background:var(--surface); border:1px solid var(--border-soft); border-radius:8px; padding:15px; box-shadow:0 1px 3px rgba(0,0,0,0.05); cursor:pointer;" onclick="prebookToJourney('${p.receiptNumber}', '${p.id}')"><div class="bpl-card-top" style="display:flex; justify-content:space-between;"><div><div class="bpl-card-name" style="font-weight:600; font-size:14px; text-transform:uppercase;">${p.wifeName}</div><div style="font-size:11.5px; color:var(--text-soft);">ID: ${p.id} · H: ${p.husbandName}</div></div><span class="pill pill-amber" style="font-size:11px; padding:2px 6px;"><span class="dot"></span>Live DB Row</span></div><div style="display:flex; gap:6px; margin:8px 0;"><span class="chip">📍 ${p.centre}</span><span class="muted" style="font-size:12px; color:var(--text-soft);">Dr. ${p.doctor}</span></div><div style="font-size:12.5px; margin-bottom:6px;">Package: <code>${p.pkg}</code></div><div style="font-size:12.5px; margin-bottom:10px;">Stage: <span style="background:#fee2e2; color:#dc2626; padding:2px 6px; border-radius:4px; font-weight:bold;">${p.stageNo} · ${p.stageName}</span></div><div style="background:#e5e7eb; height:6px; border-radius:3px; overflow:hidden; margin-bottom:6px;"><i style="display:block; background:#16a34a; height:100%; width:${pct}%;"></i></div><div style="display:flex; justify-content:space-between; font-size:11.5px;"><span>Paid <b>₹${p.collected.toFixed(2)}</b> of ₹${p.net.toFixed(2)}</span><span>${pct}%</span></div></div>`;
    }).join('');
    applyBookedView();
}

function injectBplPaginationControls(total, maxPages, start, end) {
    let pagerWrap = document.getElementById('bplPagerBarModule');
    if (!pagerWrap) {
        const target = document.getElementById('bplTableWrap') || document.getElementById('bplCardsWrap');
        if (target) { pagerWrap = document.createElement('div'); pagerWrap.id = 'bplPagerBarModule'; pagerWrap.style = "padding:15px; display:flex; justify-content:space-between; align-items:center; border-top:1px solid var(--border-soft); background:var(--surface); font-size:13px;"; target.parentNode.appendChild(pagerWrap); }
    }
    if (pagerWrap) {
        if (total === 0) { pagerWrap.innerHTML = `<div>Showing 0 to 0 of 0 entries</div>`; return; }
        pagerWrap.innerHTML = `<div>Showing <b>${start + 1}</b> to <b>${Math.min(end, total)}</b> of <b>${total}</b> records</div><div style="display:flex; gap:8px;"><button class="btn btn-ghost btn-sm" onclick="changeBplPage(-1)" ${bplCurrentPage === 1 ? 'disabled' : ''}>← Previous</button><button class="btn btn-ghost btn-sm" onclick="changeBplPage(1)" ${bplCurrentPage === maxPages ? 'disabled' : ''}>Next →</button></div>`;
    }
}

window.changeBplPage = function(dir) { bplCurrentPage += dir; renderBookedList(false); };
window.resetBookedFilters = function() { ['bplCentre', 'bplPkg', 'bplSearch', 'bplBkFrom', 'bplBkTo'].forEach(id => { const e = document.getElementById(id); if (e) e.value = ''; }); filteredBookedPatients = [...dbBookedPatientsPool]; bplCurrentPage = 1; renderBookedList(false); };
window.setBookedView = function(v) { BPL_VIEW = v; const lb = document.getElementById('bplViewListBtn'), cb = document.getElementById('bplViewCardBtn'); if (lb) lb.classList.toggle('active', v === 'list'); if (cb) cb.classList.toggle('active', v === 'card'); applyBookedView(); };
window.applyBookedView = function() { const t = document.getElementById('bplTableWrap'), c = document.getElementById('bplCardsWrap'); if (t) t.style.display = (BPL_VIEW === 'card' ? 'none' : ''); if (c) c.style.display = (BPL_VIEW === 'card' ? 'grid' : 'none'); };

window.exportBookedList = function() {
    const headers = ['SN', 'Patient ID', 'Wife Name', 'Husband Name', 'Date of First Consult', 'Date of Booking', 'Centre', 'Doctor', 'Booked Packages', 'Current Stage', 'Net Package Amount', 'Amount Received', 'Pending Amount'];
    const body = document.getElementById('bplRows'); if (!body) return;
    const lines = [headers.join(',')];
    body.querySelectorAll('tr').forEach(tr => {
        const tds = [...tr.querySelectorAll('td')]; if (tds.length < 13) return;
        const rowData = tds.map(td => td.innerText.replace(/[\r\n]+/g, ' ').trim());
        lines.push(rowData.map(text => `"${(text || '').replace(/"/g, '""')}"`).join(','));
    });
    const blob = new Blob([lines.join('\n')], { type: 'text/csv;charset=utf-8;' });
    const a = document.createElement('a'); a.href = URL.createObjectURL(blob); a.download = `booked-patient-list-${new Date().toISOString().slice(0,10)}.csv`;
    document.body.appendChild(a); a.click(); a.remove();
};

/* ============================================================
   MODULE 6: DYNAMIC PATIENT JOURNEY ENGINE & LEDGER BUILDER
   ============================================================ */
window.prebookToJourney = function(receiptNumber, patientId) {
    let uniqueToken = (receiptNumber && receiptNumber !== 'undefined' && receiptNumber !== '') ? receiptNumber : '';
    if (!uniqueToken && (patientId && patientId !== 'undefined' && patientId !== '')) {
        uniqueToken = patientId;
    }
    if (!uniqueToken || uniqueToken === '—') { alert("Alert: Missing valid Receipt or Patient Identification Number."); return; }
    showScreen('patient-journey'); window.journeySelect(uniqueToken);
};

window.jToggleHead = function(btn) {
    var bar = btn.closest('.j-head-toggle'); if (!bar) return;
    var wrap = bar.nextElementSibling; if (!wrap || !wrap.classList.contains('j-head-collapse')) return;
    var hidden = wrap.classList.toggle('collapsed');
    btn.classList.toggle('collapsed', hidden); btn.setAttribute('aria-expanded', hidden ? 'false' : 'true');
    var lbl = btn.querySelector('.jh-lbl'); if (lbl) lbl.textContent = hidden ? 'Show patient details' : 'Hide patient details';
};

function coreJourneyMatrixFallback(p) {
    const wIni = (p.wifeName || 'W').charAt(0).toUpperCase(); const hIni = (p.husbandName || 'H').charAt(0).toUpperCase();
    const completePct = p.net > 0 ? Math.min(100, Math.round((p.collected / p.net) * 100)) : 0;
    const svgCall = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:16px; height:16px;"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>';
    const svgWa = '<svg viewBox="0 0 24 24" fill="currentColor" style="width:16px; height:16px;"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21h.01c5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.823 9.823 0 0 0 12.04 2zm0 18.16h-.01c-1.48 0-2.93-.4-4.2-1.15l-.3-.18-3.12.82.83-3.04-.2-.31a8.187 8.187 0 0 1-1.26-4.39c0-4.54 3.7-8.23 8.24-8.23 2.2 0 4.27.86 5.82 2.42a8.183 8.183 0 0 1 2.41 5.82c.02 4.54-3.68 8.24-8.21 8.24zm4.52-6.16c-.25-.12-1.47-.72-1.69-.81-.23-.08-.39-.12-.56.13-.16.25-.64.81-.79.97-.14.17-.29.18-.54.06-.25-.12-1.05-.39-1.99-1.23-.74-.66-1.23-1.47-1.38-1.72-.14-.25-.02-.38.11-.51.11-.11.25-.29.37-.43.12-.14.16-.25.25-.41.08-.16.04-.31-.02-.43-.06-.12-.55-1.34-.76-1.83-.2-.48-.41-.42-.56-.42-.14 0-.31-.02-.48-.02s-.43.06-.65.31c-.22.25-.85.83-.85 2.03 0 1.2.87 2.36.99 2.52.12.16 1.71 2.62 4.15 3.67.58.25 1.03.4 1.38.51.58.18 1.11.16 1.52.1.46-.07 1.42-.58 1.62-1.14.2-.56.2-1.04.14-1.14-.06-.1-.22-.16-.46-.28z"/></svg>';
    const svgNote = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:16px; height:16px;"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>';
    return `<div class="j-head-toggle" style="margin-bottom:10px;"><button type="button" class="j-head-btn" aria-expanded="true" onclick="jToggleHead(this)" style="background:none; border:none; color:#800020; font-weight:600; cursor:pointer;"><span class="jh-ic">▼</span> <span class="jh-lbl">Hide patient details</span></button></div><div class="j-head-collapse"><div class="kyc-bar" style="background:var(--surface); border:1px solid var(--border-soft); border-radius:12px; padding:20px; display:flex; gap:20px; flex-wrap:wrap; align-items:center;"><div class="kyc-avatar-pair" style="display:flex; position:relative; width:80px; height:45px; align-items:center;"><div class="kyc-avatar wife-av" style="width:45px; height:45px; border-radius:50%; display:flex; align-items:center; justify-content:center; border:2px solid #fff; box-shadow:0 2px 4px rgba(0,0,0,0.1); z-index:2; overflow:hidden; background:#fce7f3;">${p.wife_photo ? `<img src="${p.wife_photo}" style="width:100%; height:100%; object-fit:cover;">` : `<span style="color:#db2777; font-weight:bold;">${wIni}</span>`}</div><div class="kyc-avatar husband-av" style="width:45px; height:45px; border-radius:50%; display:flex; align-items:center; justify-content:center; border:2px solid #fff; box-shadow:0 2px 4px rgba(0,0,0,0.1); margin-left:-15px; z-index:1; overflow:hidden; background:#dbeafe;">${p.husband_photo ? `<img src="${p.husband_photo}" style="width:100%; height:100%; object-fit:cover;">` : `<span style="color:#2563eb; font-weight:bold;">${hIni}</span>`}</div></div><div class="kyc-couple" style="flex:2; min-width:240px;"><div class="kyc-row" style="font-size:13.5px;"><span class="kyc-tag wife" style="background:#fce7f3; color:#db2777; padding:1px 5px; border-radius:4px; font-size:10px; font-weight:bold; margin-right:8px; text-transform:uppercase;">Wife</span><b>${p.wifeName}</b> <span class="kyc-age" style="color:var(--text-soft); font-size:12px; margin-left:6px;">${p.wifeAge} yrs</span></div><div class="kyc-row" style="font-size:13.5px; margin-top:4px;"><span class="kyc-tag husband" style="background:#dbeafe; color:#2563eb; padding:1px 5px; border-radius:4px; font-size:10px; font-weight:bold; margin-right:8px; text-transform:uppercase;">Husband</span><b>${p.husbandName}</b> <span class="kyc-age" style="color:var(--text-soft); font-size:12px; margin-left:6px;">${p.husbandAge} yrs</span></div></div><div class="kyc-meta" style="flex:1.5; font-size:12px; border-left:1px solid var(--border-soft); padding-left:20px;"><div>IIC ID: &nbsp;&nbsp;&nbsp;&nbsp;<b>IIC-${p.id}</b></div><div>CENTRE: &nbsp;<b>${p.centre}</b></div><div>DOCTOR: &nbsp;<b>${p.doctor}</b></div></div><div class="kyc-actions" style="display:flex; gap:8px;"><a href="tel:+91${p.phone}" class="kyc-action call" title="Call" style="background:#f3f4f6; width:32px; height:32px; border-radius:50%; display:flex; align-items:center; justify-content:center; color:var(--text-main); text-decoration:none;">${svgCall}</a><a href="https://wa.me/91${p.phone}" target="_blank" rel="noopener" class="kyc-action wa" title="WhatsApp" style="background:#dcfce7; width:32px; height:32px; border-radius:50%; display:flex; align-items:center; justify-content:center; color:#15803d; text-decoration:none;">${svgWa}</a><button type="button" class="kyc-action note" title="Log Note" onclick="alert('Note log overlay layer context triggered.')" style="background:#f3f4f6; width:32px; height:32px; border-radius:50%; border:none; display:flex; align-items:center; justify-content:center; color:var(--text-main); cursor:pointer;">${svgNote}</button></div><div class="kyc-status" style="text-align:right; margin-left:auto;"><span style="font-size:10px; background:#f3f4f6; padding:2px 8px; border-radius:12px; font-weight:bold; color:var(--text-soft);">STAGE 9 / 13</span><div style="font-weight:700; font-size:15px; color:#800020; text-transform:uppercase;">${p.pkg}</div><span class="pill pill-green" style="background:#dcfce7; color:#15803d; font-size:11px; padding:2px 8px; border-radius:4px; font-weight:600; display:inline-flex; align-items:center; gap:4px; margin-top:4px;"><span class="dot" style="width:5px; height:5px; background:#15803d; border-radius:50%;"></span>● On track</span></div></div></div><div class="j-strip" style="display:grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap:1px; background:var(--border-soft); border:1px solid var(--border-soft); border-radius:8px; overflow:hidden; margin:20px 0;"><div style="background:var(--surface); padding:10px 15px;"><span style="font-size:10px; color:var(--text-soft);">Booking (T)</span><b style="display:block; font-size:13px; margin-top:2px;">${p.signup}</b></div><div style="background:var(--surface); padding:10px 15px;"><span style="font-size:10px; color:var(--text-soft);">Next Step</span><b style="display:block; font-size:13px; color:#2563eb; margin-top:2px;">26 May 2026</b></div><div style="background:var(--surface); padding:10px 15px;"><span style="font-size:10px; color:var(--text-soft);">Payment Cleared</span><b style="display:block; font-size:13px; color:#16a34a; margin-top:2px;">${completePct}%</b></div><div style="background:var(--surface); padding:10px 15px;"><span style="font-size:10px; color:var(--text-soft);">Package</span><b style="display:block; font-size:13px; margin-top:2px;">${p.pkg}</b></div><div style="background:var(--surface); padding:10px 15px;"><span style="font-size:10px; color:var(--text-soft);">Gate</span><span style="background:#dcfce7; color:#15803d; font-size:11px; padding:1px 6px; border-radius:4px; font-weight:bold; display:inline-block; margin-top:2px;">✓ Open</span></div></div>`;
}

window.journeySelect = function(uniqueToken) {
    if (!uniqueToken || uniqueToken === '—') return;

    const detailContainer = document.getElementById('journeyDetail');
    if (!detailContainer) return;

    detailContainer.innerHTML = `<div style="padding:40px; text-align:center; color:var(--text-soft)">Streaming safe dynamic patient matrix from unified engine...</div>`;

    const list = document.getElementById('journeyList'); if (list) list.innerHTML = '';
    const input = document.getElementById('journeySearchInput'); if (input) input.value = '';

    fetch(`/api/get_patient_profile_detail/?receipt_number=${encodeURIComponent(uniqueToken)}`)
        .then(res => res.json())
        .then(response => {
            if (response.status === 'error') throw new Error(response.message);
            const rData = response.data || {};

            const totalFees = parseFloat(rData.fees || 0);
            const totalPaid = parseFloat(rData.payment_done || 0);
            const totalPending = parseFloat(rData.pending || (totalFees - totalPaid));
            const calculatedPaidPct = totalFees > 0 ? Math.round((totalPaid / totalFees) * 100) : 0;
            const formattedDate = rData.on_date ? rData.on_date.split('T')[0] : '—';
            const cleanPhoneToken = String(rData.patient_phone || rData.wife_phone || '—').replace(/[^0-9]/g, '');

            const integratedPatientObject = {
                id: String(rData.patient_id || '—').trim(), receipt_number: String(rData.receipt_number || '').trim(),
                type: 'scheduled', flag: totalPending > 0 ? 'amber' : 'green', gate: '✓ Open',
                net: totalFees, paid: calculatedPaidPct, collected: totalPaid, pending: totalPending,
                signup: formattedDate, date: formattedDate,
                centre: rData.husband_address ? rData.husband_address.split(',')[0].replace(/[\r\n]+/g, ' ').trim() : 'India IVF Centre',
                doctor: rData.doctor_name || 'Dr. A. Verma', pkg: rData.code || 'IP222', desc: rData.procedure_name || 'Comprehensive IVF',
                wifeName: rData.wife_name || 'Unknown', husbandName: rData.husband_name || '—', wifeAge: rData.wife_age || '—', husbandAge: rData.husband_age || '—', 
                phone: cleanPhoneToken, wife_photo: rData.wife_photo || '', husband_photo: rData.husband_photo || ''
            };

            window.isPrebookPatient = function(obj) { return false; };
            window.patientNewStage = function(obj) { return 4; };
            window.patientKYC = function(obj) { return { wifeName: obj.wifeName, husbandName: obj.husbandName, wifeAge: obj.wifeAge, husbandAge: obj.husbandAge }; };
            window.patientPhone = function(obj) { return obj.phone; }; window.fmtPhone = function(ph) { return ph; };

            if (typeof journeyMatrix === 'function') {
                detailContainer.innerHTML = journeyMatrix(integratedPatientObject);
            } else {
                console.warn("journeyMatrix function not natively instantiated in document. Executing compiled high-fidelity layout wrapper.");
                detailContainer.innerHTML = coreJourneyMatrixFallback(integratedPatientObject);
            }

            if (typeof jAddExtraCols === 'function') jAddExtraCols(detailContainer, integratedPatientObject);
            if (typeof jInsertPackagesBox === 'function') jInsertPackagesBox(detailContainer, integratedPatientObject);
            if (typeof jApplyJourneyV5 === 'function') jApplyJourneyV5(detailContainer, integratedPatientObject);
            if (typeof jApplyJourneyV8 === 'function') jApplyJourneyV8(detailContainer, integratedPatientObject);

            const targetProcsArray = response.procedures && response.procedures.length > 0 ? response.procedures : [{ 
                on_date: rData.on_date || '—', 
                category: rData.category || 'OPD Billing', 
                procedure_name: rData.procedure_name || 'Procedural Record Line', 
                code: rData.code || '—', 
                fees: totalFees, 
                payment_done: totalPaid, 
                pending: totalPending 
            }];
            
            appendCustomLedgerToJourney(targetProcsArray, totalFees, totalPaid, totalPending);
        })
        .catch(err => { detailContainer.innerHTML = `<div class="callout" style="color:var(--red); border-left: 4px solid var(--red); padding: 15px;"><b>Data Sync Failed:</b> ${err.message}</div>`; });
};

function appendCustomLedgerToJourney(procs, fees, paid, pending) {
    const detailContainer = document.getElementById('journeyDetail'); if (!detailContainer) return;
    
    const legacyBox = document.getElementById('jointProcedureLedgerCard'); if (legacyBox) legacyBox.remove();
    document.querySelectorAll('.dynamic-ledger-stacked-card').forEach(el => el.remove());

    if (!procs || procs.length === 0) return;

    const tableRowsHtml = procs.map(p => {
        let rawDate = p.on_date ? p.on_date.replace('T', ' ').split('.')[0] : '—';
        
        let catColor = '#f3f4f6';
        let catText = '#374151';
        let currentCategory = p.category || 'Non IVF without Bed';
        
        if (currentCategory.toLowerCase().includes('bed')) {
            catColor = '#e0f2fe';
            catText = '#0369a1';
        } else if (currentCategory.toLowerCase().includes('non ivf')) {
            catColor = '#f1f5f9';
            catText = '#475569';
        } else if (currentCategory.toLowerCase().includes('opd')) {
            catColor = '#e5e7eb';
            catText = '#4b5563';
        }

        return `
        <tr style="border-bottom: 1px solid #f3f4f6; background:#fff;">
            <td style="padding:16px 20px; white-space:nowrap; color:#6b7280;">${rawDate}</td>
            <td style="padding:16px 20px;">
                <span class="wf-tag" style="background:${catColor}; color:${catText}; padding:4px 8px; border-radius:4px; font-size:11px; font-weight: 600; border: 1px solid rgba(0,0,0,0.03); display: inline-block;">${currentCategory}</span>
            </td>
            <td style="padding:16px 20px; font-weight:500; color:#1f2937;">${p.procedure_name || '—'}</td>
            <td style="padding:16px 20px; font-family:monospace; font-weight:600; color:#4b5563;">${p.code || '—'}</td>
            <td style="padding:16px 20px; text-align:right; font-weight:500; color:#374151;">₹${parseFloat(p.fees).toFixed(2)}</td>
            <td style="padding:16px 20px; text-align:right; color:#10b981; font-weight:600;">₹${parseFloat(p.payment_done).toFixed(2)}</td>
            <td style="padding:16px 20px; text-align:right; color:#ef4444; font-weight:600;">₹${parseFloat(p.pending).toFixed(2)}</td>
        </tr>`;
    }).join('');

    const singleLedgerCardHtml = `
        <div class="card" id="jointProcedureLedgerCard" style="margin-top:20px; border:1px solid #ebdada; border-radius:8px; overflow:hidden; background:#fff; box-shadow: 0 4px 12px rgba(0,0,0,0.03);">
            <div class="card-head" style="background:#fff; padding:16px 20px; border-bottom:1px solid #f3f4f6; font-weight:600;">
                <div class="card-title" style="color:#800020; font-size:14px; font-family:inherit; font-weight:bold;">Live Patient Procedure Ledger (Joint Query Data)</div>
            </div>
            <div class="card-body flush" style="padding:0;">
                <div class="table-wrap" style="overflow-x:auto;">
                    <table class="tbl" style="width:100%; border-collapse:collapse; font-size:13px; text-align:left;">
                        <thead>
                            <tr style="background:#fff; color:#9ca3af; border-bottom:1px solid #f3f4f6; font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:0.5px;">
                                <th style="padding:12px 20px; width:20%;">Date</th>
                                <th style="padding:12px 20px; width:15%;">Category</th>
                                <th style="padding:12px 20px; width:30%;">Procedure Name</th>
                                <th style="padding:12px 20px; width:10%;">Code</th>
                                <th style="padding:12px 20px; text-align:right; width:10%;">Fees</th>
                                <th style="padding:12px 20px; text-align:right; width:10%;">Payment Done</th>
                                <th style="padding:12px 20px; text-align:right; width:15%;">Pending Balance</th>
                            </tr>
                        </thead>
                        <tbody style="color:#4b5563;">
                            ${tableRowsHtml}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>`;

    detailContainer.insertAdjacentHTML('beforeend', singleLedgerCardHtml);
}

/* ============================================================
   MODULE 7: CORE BOOTSTRAP & LOCATION INTERCEPTOR
   ============================================================ */
window.addEventListener('DOMContentLoaded', () => {
    const role = document.body.dataset.role; if (typeof buildSidebar === 'function') buildSidebar(role);
    const rendererMap = { doctor: window.renderDoctor, ch: window.renderCH, fc: window.renderFC, accounts: window.renderAccounts, mgmt: window.renderMgmt };
    if (rendererMap[role]) rendererMap[role]();
    if (typeof renderStageWidget === 'function') renderStageWidget();
    if (typeof renderJourney === 'function') renderJourney();
    if (typeof renderPrebook === 'function') renderPrebook();
    if (typeof initAging === 'function') initAging();
});

document.addEventListener("DOMContentLoaded", function() {
    const activeUserCenterPill = document.getElementById('userActiveLocationLabel');
    if (activeUserCenterPill) {
        const rawCenterContext = activeUserCenterPill.innerText.replace(' Centre', '').trim();
        console.log(`HMS Session Initialized: Operating under verified context validation rules for [${rawCenterContext}].`);
        const bplCentreDropdown = document.getElementById('bplCentre');
        if (bplCentreDropdown) {
            setTimeout(() => {
                bplCentreDropdown.value = rawCenterContext;
                if (typeof renderBookedList === 'function') renderBookedList();
            }, 300);
        }
    }
});