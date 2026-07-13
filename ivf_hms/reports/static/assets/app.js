/* ============================================================
   INDIA IVF — HMS Patient Journey & Collections
   Shared application logic + render engine
   ============================================================ */

const BOOKED_GROUP={group:'booked',label:'Booked Patient',ic:'◷',children:[
  {id:'booked-patient-list',label:'Booked Patient List',ic:'≡'},
  {id:'patient-journey',label:'Booked Patient Journey',ic:'◷'}
]};
const NAV={
  doctor:[
    {id:'doc-dashboard',label:'Dashboard',ic:'▦'},
    {id:'prebook-scheduled',label:'Appointment Scheduled',ic:'📅'},
    {id:'prebook-missed',label:'Missed Appointments',ic:'⚑'},
    {id:'prebook-cnb',label:'Consulted Not Booked',ic:'◐'},
    BOOKED_GROUP,
    {id:'doc-milestone',label:'Milestone Form',ic:'✚'},
    {id:'doc-achievement',label:'Achievement Table',ic:'▤'}
  ],
  ch:[
    {id:'ch-dashboard',label:'Dashboard',ic:'▦'},
    {id:'prebook-scheduled',label:'Appointment Scheduled',ic:'📅'},
    {id:'prebook-missed',label:'Missed Appointments',ic:'⚑'},
    {id:'prebook-cnb',label:'Consulted Not Booked',ic:'◐'},
    BOOKED_GROUP,
    {id:'ch-collections',label:'Expected Collections',ic:'₹'},
    {id:'ch-achievement',label:'Achievement Table',ic:'▤'},
    {id:'ch-aging',label:'Aging Buckets',ic:'⏳'}
  ],
  fc:[
    {id:'fc-dashboard',label:'My Patients',ic:'▦'},
    {id:'prebook-scheduled',label:'Appointment Scheduled',ic:'📅'},
    {id:'prebook-missed',label:'Missed Appointments',ic:'⚑'},
    {id:'prebook-cnb',label:'Consulted Not Booked',ic:'◐'},
    BOOKED_GROUP,
    {id:'fc-actions',label:'Action Queue',ic:'✓'},
    {id:'fc-refund',label:'Refund Queue',ic:'↩'},
    {id:'fc-wallet',label:'Patient Wallet',ic:'◈'}
  ],
  accounts:[
    {id:'acc-dashboard',label:'Collections',ic:'▦'},
    {id:'prebook-scheduled',label:'Appointment Scheduled',ic:'📅'},
    {id:'prebook-missed',label:'Missed Appointments',ic:'⚑'},
    {id:'prebook-cnb',label:'Consulted Not Booked',ic:'◐'},
    BOOKED_GROUP,
    {id:'acc-refund',label:'Refund Queue',ic:'↩'},
    {id:'acc-aging',label:'Company Aging',ic:'⏳'},
    {id:'acc-triggers',label:'Red Triggers',ic:'⚑'}
  ],
  mgmt:[
    {id:'mgmt-dashboard',label:'Dashboard',ic:'▦'},
    {id:'prebook-scheduled',label:'Appointment Scheduled',ic:'📅'},
    {id:'prebook-missed',label:'Missed Appointments',ic:'⚑'},
    {id:'prebook-cnb',label:'Consulted Not Booked',ic:'◐'},
    BOOKED_GROUP,
    {id:'mgmt-centres',label:'Centre Comparison',ic:'▥'},
    {id:'mgmt-aging',label:'Aging Snapshot',ic:'⏳'},
    {id:'mgmt-triggers',label:'Red Trigger Pile-up',ic:'⚑'},
    {id:'mgmt-approvals',label:'Approval Queue',ic:'⚖'}
  ]
};

function fill(id,html){ const e=document.getElementById(id); if(e) e.innerHTML=html; }

function buildSidebar(role){
  const nav=document.getElementById('sideNav');
  if(!nav) return;
  let firstSet=false;
  const html=NAV[role].map(n=>{
    if(n.children){
      const gid='grp-'+n.group;
      const inner=n.children.map(c=>{
        const active=(!firstSet)?(firstSet=true,' active'):'';
        return '<div class="side-link side-sub'+active+'" data-screen="'+c.id+'">'+
          '<span class="ic">'+c.ic+'</span>'+c.label+'</div>';
      }).join('');
      return '<div class="side-group" id="'+gid+'">'+
        '<div class="side-grp-head" data-grp="'+n.group+'">'+
          '<span class="ic">'+n.ic+'</span>'+n.label+
          '<span class="side-grp-caret">▾</span>'+
        '</div>'+
        '<div class="side-grp-children">'+inner+'</div>'+
      '</div>';
    }
    const active=(!firstSet)?(firstSet=true,' active'):'';
    return '<div class="side-link'+active+'" data-screen="'+n.id+'">'+
      '<span class="ic">'+n.ic+'</span>'+n.label+'</div>';
  }).join('');
  nav.innerHTML=html;
  nav.querySelectorAll('.side-link').forEach(l=>l.onclick=()=>showScreen(l.dataset.screen));
  nav.querySelectorAll('.side-grp-head').forEach(h=>{
    h.onclick=()=>{ const g=h.parentElement; g.classList.toggle('collapsed'); };
  });
}
function showScreen(id){
  // Route the 3 dedicated prebook sidebar tabs to the shared listing screen
  if(id==='prebook-scheduled'||id==='prebook-missed'||id==='prebook-cnb'){
    prebookOpen(id.slice(8));
    return;
  }
  document.querySelectorAll('.screen').forEach(s=>s.classList.toggle('active',s.id===id));
  document.querySelectorAll('.side-link').forEach(l=>l.classList.toggle('active',l.dataset.screen===id));
  if(id==='booked-patient-list' && typeof renderBookedList==='function') renderBookedList();
  try{window.scrollTo(0,0);}catch(e){}
}




// ==========================================
// 1. Consulted Not Booked
// ==========================================

function prebookOpen(type){
  // direct screen swap (avoids re-entering showScreen which routes prebook-* aliases here)
  document.querySelectorAll('.screen').forEach(s=>s.classList.toggle('active',s.id==='prebook-list'));
  document.querySelectorAll('.side-link').forEach(l=>l.classList.toggle('active',l.dataset.screen==='prebook-'+type));
  try{window.scrollTo(0,0);}catch(e){}
  const sel=document.getElementById('prebookRange');
  const range=sel?sel.value:'today';
  renderPrebookList(type,range);
}
// ==========================================
// 1. HELPER FUNCTIONS
// ==========================================
const CNB_EDITS = {};

function cnbGet(id, field, fallback) {
  return (CNB_EDITS[id] && CNB_EDITS[id][field] != null) ? CNB_EDITS[id][field] : fallback;
}

function cnbSet(id, field, val) {
  if (!CNB_EDITS[id]) CNB_EDITS[id] = {};
  CNB_EDITS[id][field] = val;
}

function cnbQualityPill(q) {
  const map = { Hot: '#dc2626', Cold: '#2563eb', Dead: '#6b7280' };
  const bg = { Hot: '#fee2e2', Cold: '#dbeafe', Dead: '#e5e7eb' };
  const c = map[q] || '#6b7280', b = bg[q] || '#e5e7eb';
  return '<span style="display:inline-flex;align-items:center;gap:4px;padding:2px 8px;border-radius:11px;font-size:11px;font-weight:600;background:'+b+';color:'+c+'"><span style="width:6px;height:6px;border-radius:50%;background:'+c+'"></span>'+q+'</span>';
}

if (typeof fmtDate === 'undefined') {
  window.fmtDate = function(d) {
    if (!d) return '—';
    return d;
  };
}

// ==========================================
// 2. STATE & PAGINATION VARIABLES
// ==========================================
let globalCNBData = []; 
let filteredCNBData = []; // NEW: Holds the active filtered dataset
let currentPage = 1;
const rowsPerPage = 50; 

// ==========================================
// 3. MAIN FUNCTION
// ==========================================
function renderPrebookList(type, range) {
  const labels = { scheduled: 'Appointments Scheduled', missed: 'Missed Appointments', cnb: 'Consulted Not Booked' };
  const tt = document.getElementById('prebookTitle'); 
  if (tt) tt.textContent = labels[type];
  
  const wrap = document.getElementById('prebookListBody');
  if (!wrap) return; 

  if (type === 'cnb') {
    if (globalCNBData.length === 0) {
        wrap.innerHTML = '<div style="padding:20px; text-align:center; color:var(--text-soft)">Loading live data...</div>';

        fetch('/api/get_cnb_data/')
          .then(response => response.json())
          .then(apiData => {
            globalCNBData = apiData; 
            filteredCNBData = [...globalCNBData]; // Initialize filtered data
            currentPage = 1; 
            setupUIAndRender(wrap); // Setup filters and render
          })
          .catch(error => {
            console.error("Error fetching CNB data:", error);
            wrap.innerHTML = '<div class="empty-note" style="margin:14px; color:red;">Failed to load data from server.</div>';
          });
    } else {
        currentPage = 1; 
        setupUIAndRender(wrap);
    }
  }
}

// ==========================================
// 4. SETUP UI AND FILTERING LOGIC
// ==========================================
function setupUIAndRender(wrap) {
    // Inject the filter bar with Patient ID input and Save button added
    if (!document.getElementById('cnbFilterBar')) {
        wrap.innerHTML = `
            <div id="cnbFilterBar" style="padding: 15px; display: flex; gap: 10px; flex-wrap: wrap; background: var(--surface); border-bottom: 1px solid var(--border-soft); align-items: center;">
                <input type="text" id="filterPatientId" placeholder="Filter Patient ID..." oninput="applyFilters()" style="flex: 1; min-width: 120px; padding: 6px 12px; border: 1px solid var(--border-soft); border-radius: 4px;">
                <input type="text" id="filterName" placeholder="Filter Patient Name..." oninput="applyFilters()" style="flex: 1; min-width: 150px; padding: 6px 12px; border: 1px solid var(--border-soft); border-radius: 4px;">
                <input type="text" id="filterDoctor" placeholder="Filter Doctor..." oninput="applyFilters()" style="flex: 1; min-width: 150px; padding: 6px 12px; border: 1px solid var(--border-soft); border-radius: 4px;">
                <input type="text" id="filterCenter" placeholder="Filter Center..." oninput="applyFilters()" style="flex: 1; min-width: 150px; padding: 6px 12px; border: 1px solid var(--border-soft); border-radius: 4px;">
                <input type="date" id="filterDate" onchange="applyFilters()" style="padding: 6px 12px; border: 1px solid var(--border-soft); border-radius: 4px;">
                
                <button onclick="saveCNBEdits()" style="padding: 6px 16px; background-color: #2563eb; color: white; border: none; border-radius: 4px; font-weight: bold; cursor: pointer;">
                    Save Edits
                </button>
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
        // Safe check: ensure we take patient_id or paitent_id properly
        const pId = String(p.patient_id || p.paitent_id || p.id || '').toLowerCase(); 
        const pName = (p.name || '').toLowerCase();
        const pDoctor = (p.doctor_name || p.doctor || '').toLowerCase(); 
        const pCenter = (p.center_name || p.center || '').toLowerCase();
        const pDate = p.date || '';

        const matchesId = pId.includes(idVal);
        const matchesName = pName.includes(nameVal);
        const matchesDoctor = pDoctor.includes(doctorVal);
        const matchesCenter = pCenter.includes(centerVal);
        const matchesDate = dateVal === '' || pDate.startsWith(dateVal);

        return matchesId && matchesName && matchesDoctor && matchesCenter && matchesDate;
    });

    currentPage = 1; 
    renderTablePage();
};

// ==========================================
// 5. FUNCTION TO RENDER SPECIFIC PAGE (MODIFIED)
// ==========================================
function renderTablePage() {
    const tableContainer = document.getElementById('cnbTableContainer');
    if (!tableContainer) return;

    const ts = document.getElementById('prebookSub');
    if (ts) ts.textContent = `Total ${filteredCNBData.length} patient(s) · Page ${currentPage} of ${Math.max(1, Math.ceil(filteredCNBData.length / rowsPerPage))}`;

    const startIndex = (currentPage - 1) * rowsPerPage;
    const endIndex = startIndex + rowsPerPage;
    const paginatedData = filteredCNBData.slice(startIndex, endIndex);

    const head = '<thead><tr>'+
      '<th>SN</th><th>Patient ID</th><th>Patient Name</th><th>Date of Consult</th>'+
      '<th>Centre</th><th>Doctor</th><th>Appointment For</th><th>FC Name</th><th>Quality</th>'+
      '<th>FC Comment</th><th>Latest Connected Date</th><th>Latest Comment</th>'+
    '</tr></thead>';

    const body = paginatedData.length ? paginatedData.map((p, index) => {
      const actualSN = startIndex + index + 1;
      
      // Kuch records me agar actual standard id nahi hai to isme auto numerical internal id map ho jayegi backend se
      const patientId = String(p.patient_id || p.appointment_internal_id || '').trim();
      
      const patientName = p.name || 'Unknown';
      const aptDate = p.date || '';
      const doctorName = p.doctor_name || p.doctor || '—'; 
      const aptFor = p.appoitment_for || '—'; 
      const centreName = p.center_name || p.centre || '—'; 
      const fcname = p.councellor;
      
      // CHANGE: Pehle cnbGet checking local edits dekhega, agar nahi hai to direct backend se aaya hua data use karega
      const q = cnbGet(patientId, 'quality', p.quality || 'Cold');
      const fcc = cnbGet(patientId, 'fcComment', p.fc_comment || '');
      const lc = cnbGet(patientId, 'lastConn', p.latest_connected_date || '');
      const lcm = cnbGet(patientId, 'lastComment', p.latest_comment || '');
      const opt = q => ['Hot','Cold','Dead'].map(o => '<option value="'+o+'"'+(o===q?' selected':'')+'>'+o+'</option>').join('');
      
      return '<tr>'+
        '<td class="sn-col">'+ actualSN +'</td>'+
        '<td><a class="iic-link" onclick="prebookToJourney(\''+patientId+'\')">'+patientId+'</a></td>'+
        '<td class="strong">'+patientName+'</td>'+
        '<td>'+fmtDate(aptDate)+'</td>'+
        '<td>'+centreName+'</td>'+
        '<td>'+doctorName+'</td>'+
        '<td>'+aptFor+'</td>'+
        '<td>'+fcname+'</td>'+
        '<td>'+
          '<select class="cnb-q-sel" data-id="'+patientId+'" onchange="cnbSet(this.dataset.id,\'quality\',this.value); renderTablePage();">'+opt(q)+'</select>'+
          '<div style="margin-top:3px">'+cnbQualityPill(q)+'</div>'+
        '</td>'+
        '<td><textarea class="cnb-cmt" rows="2" data-id="'+patientId+'" oninput="cnbSet(this.dataset.id,\'fcComment\',this.value)">'+fcc.replace(/"/g,'&quot;')+'</textarea></td>'+
        '<td><input type="date" class="cnb-date" data-id="'+patientId+'" value="'+lc+'" oninput="cnbSet(this.dataset.id,\'lastConn\',this.value)"/></td>'+
        '<td><textarea class="cnb-cmt" rows="2" data-id="'+patientId+'" oninput="cnbSet(this.dataset.id,\'lastComment\',this.value)">'+lcm.replace(/"/g,'&quot;')+'</textarea></td>'+
      '</tr>';
    }).join('') : '<tr><td colspan="11"><div class="empty-note" style="margin:14px">No data found matching your filters.</div></td></tr>';
    
    const totalPages = Math.max(1, Math.ceil(filteredCNBData.length / rowsPerPage));
    const paginationHTML = `
      <div style="padding: 15px; display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--border-soft); background: var(--surface);">
          <div>Showing ${filteredCNBData.length > 0 ? startIndex + 1 : 0} to ${Math.min(endIndex, filteredCNBData.length)} of ${filteredCNBData.length} records</div>
          <div style="display: flex; gap: 8px;">
              <button class="btn btn-ghost btn-sm" onclick="changePage(-1)" ${currentPage === 1 ? 'disabled' : ''}>← Previous</button>
              <button class="btn btn-ghost btn-sm" onclick="changePage(1)" ${currentPage === totalPages ? 'disabled' : ''}>Next →</button>
          </div>
      </div>
    `;

    tableContainer.innerHTML = '<table class="tbl cnb-tbl">' + head + '<tbody id="prebookRows">' + body + '</tbody></table>' + paginationHTML;
}

// ==========================================
// 6. FUNCTION TO CHANGE PAGE
// ==========================================
window.changePage = function(direction) {
    const totalPages = Math.ceil(filteredCNBData.length / rowsPerPage);
    currentPage += direction;
    
    if (currentPage < 1) currentPage = 1;
    if (currentPage > totalPages) currentPage = totalPages;
    
    renderTablePage(); // Rely on setup UI, just update the table portion
    
    try {
        const topOfTable = document.querySelector('.card-body.flush').offsetTop;
        window.scrollTo({ top: topOfTable - 50, behavior: 'smooth' });
    } catch(e) {}
};

// ==========================================
// 7. SAVE DATA TO BACKEND (MODIFIED)
// ==========================================
window.saveCNBEdits = function() {
    if (Object.keys(CNB_EDITS).length === 0) {
        alert("No changes have been made yet.");
        return;
    }

    const payload = Object.keys(CNB_EDITS).map(patientId => {
        return {
            patient_id: String(patientId).trim(),
            quality: CNB_EDITS[patientId].quality || "Cold",
            fc_comment: CNB_EDITS[patientId].fcComment || "",
            latest_connected_date: CNB_EDITS[patientId].lastConn || "",
            latest_comment: CNB_EDITS[patientId].lastComment || ""
        };
    });

    const btn = document.querySelector('button[onclick="saveCNBEdits()"]');
    if (btn) btn.textContent = "Saving...";

    fetch('/api/save_cnb_edits/', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ edits: payload })
    })
    .then(response => {
        return response.json().then(data => {
            if (!response.ok) throw new Error(data.message || "Server Error encountered");
            return data;
        });
    })
    .then(data => {
        alert("Data successfully saved to the new table!");
        
        // CHANGE: Purane states ko global array me manually append ya update karna takiy refresh na karna pade
        payload.forEach(item => {
            let found = globalCNBData.find(x => String(x.patient_id).trim() === item.patient_id);
            if (found) {
                found.quality = item.quality;
                found.fc_comment = item.fc_comment;
                found.latest_connected_date = item.latest_connected_date;
                found.latest_comment = item.latest_comment;
            }
            // Clear local temporary edit state
            delete CNB_EDITS[item.patient_id];
        });
        
        // Re-render UI to display completely synchronized database inputs
        renderTablePage();

        if (btn) btn.textContent = "Save Edits";
    })
    .catch(error => {
        console.error("Error saving data:", error);
        alert("Failed to save data: " + error.message);
        if (btn) btn.textContent = "Save Edits";
    });
};









/* ============================================================
   DYNAMIC BOOKED PATIENT LIST — FULL PAGINATION & DATA ENGINE
   ============================================================ */
let dbBookedPatientsPool = [];      
let filteredBookedPatients = [];    
let bplCurrentPage = 1;             
const bplRowsPerPage = 50;          
let BPL_VIEW = 'list';              

document.addEventListener("DOMContentLoaded", function() {
    const bBodyCheck = document.getElementById('bplRows');
    if(!bBodyCheck) return; // Safeguard if running on secondary workspace templates

    fetch('/api/get_dynamic_booked_patients/')
        .then(res => {
            if(!res.ok) throw new Error("HMS link dynamic core validation failure.");
            return res.json();
        })
        .then(data => {
            dbBookedPatientsPool = data.map(item => {
                // Parse and validate live string token values to safely separate receipt nos from IDs
                let cleanReceipt = (item.receipt_number && String(item.receipt_number).trim() !== "") ? String(item.receipt_number).trim() : "";
                
                return {
                    id: String(item.patient_id).trim(),
                    receiptNumber: cleanReceipt, // STABLE BINDING: Map live row token string
                    wifeName: item.name || 'Unknown',
                    husbandName: item.husband_name || '—', 
                    firstConsultDate: item.date || '—',
                    bookingDate: item.on_date || '—',
                    centre: item.center_name || '—',
                    doctor: item.doctor_name || '—',
                    pkg: item.code || 'Standard Procedure',
                    net: parseFloat(item.fees || 0),
                    collected: parseFloat(item.total_payment_done || 0),
                    pending: parseFloat(item.pending_amount || 0),
                    councellor: item.councellor || '—',
                    stageName: 'Consultation Done'
                };
            });
            
            filteredBookedPatients = [...dbBookedPatientsPool];
            bplPopulateDynamicFilters();
            renderBookedList();
        })
        .catch(err => {
            console.error("Fetch Failure:", err);
            if(bBodyCheck) bBodyCheck.innerHTML = `<tr><td colspan="13" style="color:red; text-align:center; padding:20px; font-weight:bold;">Database Fetch Failed: Pipeline tracking parameters breakdown.</td></tr>`;
        });
});

function bplPopulateDynamicFilters() {
    const pkgSel = document.getElementById('bplPkg');
    if (pkgSel && pkgSel.dataset.filled !== '1') {
        const uniquePkgs = [...new Set(dbBookedPatientsPool.map(p => p.pkg))].sort();
        uniquePkgs.forEach(pk => {
            const o = document.createElement('option'); o.value = pk; o.textContent = pk;
            pkgSel.appendChild(o);
        });
        pkgSel.dataset.filled = '1';
    }

    const centreSel = document.getElementById('bplCentre');
    if (centreSel && centreSel.dataset.filled !== '1') {
        const uniqueCentres = [...new Set(dbBookedPatientsPool.map(p => p.centre))].sort();
        uniqueCentres.forEach(c => {
            const o = document.createElement('option'); o.value = c; o.textContent = c;
            centreSel.appendChild(o);
        });
        centreSel.dataset.filled = '1';
    }
}

window.filterBookedData = function() {
    renderBookedList(true);
};

window.renderBookedList = function(resetPage = false) {
    const bRowsContainer = document.getElementById('bplRows');
    if(!bRowsContainer) return;

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

        if (searchVal) {
            const haystack = (p.wifeName + ' ' + p.husbandName + ' ' + p.id + ' ' + p.doctor + ' ' + p.pkg).toLowerCase();
            if (haystack.indexOf(searchVal) < 0) return false;
        }
        return true;
    });

    const totalRecords = filteredBookedPatients.length;
    const totalPages = Math.max(1, Math.ceil(totalRecords / bplRowsPerPage));

    const cnt = document.getElementById('bplCount');
    if(cnt) cnt.textContent = `· Page ${bplCurrentPage} of ${totalPages} (${totalRecords} records)`;

    const startIndex = (bplCurrentPage - 1) * bplRowsPerPage;
    const endIndex = startIndex + bplRowsPerPage;
    const paginatedChunk = filteredBookedPatients.slice(startIndex, endIndex);

    bRowsContainer.innerHTML = paginatedChunk.map((p, index) => {
        const structuralSn = startIndex + index + 1;
        return '<tr>' +
          '<td><b>' + structuralSn + '</b></td>' + 
          // CELL RESOLUTION FIXED: Dynamic invocation uses p.receiptNumber explicitly to avoid mapping mixups
          '<td><span class="bpl-iic" style="color:#2563eb; font-weight:600; cursor:pointer;" onclick="prebookToJourney(\'' + (p.receiptNumber || '') + '\', \'' + p.id + '\')">' + p.id + '</span></td>' +
          '<td class="strong">' + p.wifeName + '</td>' +
          '<td>' + p.husbandName + '</td>' + 
          '<td>' + (p.firstConsultDate) + '</td>' +
          '<td>' + (p.bookingDate) + '</td>' +
          '<td>' + p.centre + '</td>' +
          '<td>' + p.doctor + '</td>' +
          '<td><code>' + p.pkg + '</code></td>' +
          '<td><span class="bpl-stage-pill">1 · ' + p.stageName + '</span></td>' +
          '<td class="col-num">₹' + p.net.toFixed(2) + '</td>' +
          '<td class="col-num" style="color:#16a34a;">₹' + p.collected.toFixed(2) + '</td>' +
          '<td class="col-num" style="color:#dc2626; font-weight:600;">₹' + Math.max(0, p.pending).toFixed(2) + '</td>' +
        '</tr>';
    }).join('') || '<tr><td colspan="13" style="text-align:center; padding:24px">No active patients found matching selections.</td></tr>';

    renderBookedCards(paginatedChunk);
    injectBplPaginationControls(totalRecords, totalPages, startIndex, endIndex);
};

function renderBookedCards(chunk) {
    const wrap = document.getElementById('bplCardsWrap'); 
    if (!wrap) return;

    wrap.innerHTML = chunk.map(function(p) {
        const completionPercentage = p.net > 0 ? Math.min(100, Math.round((p.collected / p.net) * 100)) : 0;
        return `
            <div class="bpl-card" style="background:var(--surface); border:1px solid var(--border-soft); border-radius:8px; padding:15px; box-shadow:0 1px 3px rgba(0,0,0,0.05); cursor:pointer;" onclick="prebookToJourney('${p.receiptNumber || ''}', '${p.id}')">
                <div class="bpl-card-top" style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:10px;">
                    <div>
                        <div class="bpl-card-name" style="font-weight:600; font-size:14px; text-transform:uppercase;">${p.wifeName}</div>
                        <div class="bpl-card-sub" style="font-size:11.5px; color:var(--text-soft); margin-top:2px;">ID: ${p.id} · H: ${p.husbandName}</div>
                    </div>
                    <span class="pill pill-amber" style="font-size:11px; padding:2px 6px; font-weight:600;"><span class="dot"></span>Live DB Row</span>
                </div>
                <div class="bpl-card-centre" style="display:flex; gap:6px; flex-wrap:wrap; margin-bottom:8px;">
                    <span class="chip" style="background:#f3f4f6; font-size:11.5px; padding:2px 6px; border-radius:4px;">📍 ${p.centre}</span>
                    <span class="muted" style="font-size:12px; color:var(--text-soft); font-style:italic;">Dr. ${p.doctor}</span>
                </div>
                <div class="bpl-card-proc" style="font-size:12.5px; margin-bottom:6px;">Package: <code style="background:#f3f4f6; padding:2px 4px; border-radius:4px; font-weight:bold;">${p.pkg}</code></div>
                <div class="bpl-card-stage" style="font-size:12.5px; margin-bottom:10px;">Current Stage: <span style="background:#fee2e2; color:#dc2626; padding:2px 6px; border-radius:4px; font-weight:bold; font-size:11.5px;">${p.stageName}</span></div>
                <div class="bpl-paybar" style="background:#e5e7eb; height:6px; border-radius:3px; overflow:hidden; margin-bottom:6px;"><i style="display:block; background:#16a34a; height:100%; width:${completionPercentage}%;"></i></div>
                <div class="bpl-payrow" style="display:flex; justify-content:space-between; font-size:11.5px; color:var(--text-soft);">
                    <span>Paid <b>₹${p.collected.toFixed(2)}</b> of ₹${p.net.toFixed(2)}</span><span>${completionPercentage}%</span>
                </div>
            </div>
        `;
    }).join('') || '<div style="color:var(--text-soft); padding:8px 4px">No card layouts cached.</div>';
    applyBookedView();
}

function injectBplPaginationControls(total, maxPages, start, end) {
    let pagerWrap = document.getElementById('bplPagerBarModule');
    if(!pagerWrap) {
        const targetTableWrap = document.getElementById('bplTableWrap') || document.getElementById('bplCardsWrap');
        if(targetTableWrap) {
            pagerWrap = document.createElement('div'); pagerWrap.id = 'bplPagerBarModule';
            pagerWrap.style = "padding:15px; display:flex; justify-content:space-between; align-items:center; border-top:1px solid var(--border-soft); background:var(--surface); font-size:13px;";
            targetTableWrap.parentNode.appendChild(pagerWrap);
        }
    }
    if(pagerWrap) {
        if(total === 0) { pagerWrap.innerHTML = `<div>Showing 0 to 0 of 0 entries</div><div></div>`; return; }
        pagerWrap.innerHTML = `
            <div>Showing <b>${start + 1}</b> to <b>${Math.min(end, total)}</b> of <b>${total}</b> records</div>
            <div style="display:flex; gap:8px;">
                <button class="btn btn-ghost btn-sm" onclick="changeBplPage(-1)" ${bplCurrentPage === 1 ? 'disabled' : ''}>← Previous</button>
                <button class="btn btn-ghost btn-sm" onclick="changeBplPage(1)" ${bplCurrentPage === maxPages ? 'disabled' : ''}>Next →</button>
            </div>
        `;
    }
}

window.changeBplPage = function(direction) {
    const totalPages = Math.ceil(filteredBookedPatients.length / bplRowsPerPage);
    bplCurrentPage += direction;
    if (bplCurrentPage < 1) bplCurrentPage = 1;
    if (bplCurrentPage > totalPages) bplCurrentPage = totalPages;
    renderBookedList(false);
};

window.resetBookedFilters = function() {
    ['bplCentre', 'bplPkg', 'bplSearch', 'bplBkFrom', 'bplBkTo'].forEach(id => {
        const element = document.getElementById(id); if (element) element.value = '';
    });
    filteredBookedPatients = [...dbBookedPatientsPool];
    bplCurrentPage = 1;
    renderBookedList(false);
};

window.setBookedView = function(v) {
    BPL_VIEW = v;
    const lb = document.getElementById('bplViewListBtn'), cb = document.getElementById('bplViewCardBtn');
    if (lb) lb.classList.toggle('active', v === 'list');
    if (cb) cb.classList.toggle('active', v === 'card');
    applyBookedView();
};

window.applyBookedView = function() {
    const t = document.getElementById('bplTableWrap'), c = document.getElementById('bplCardsWrap');
    if (t) t.style.display = (BPL_VIEW === 'card' ? 'none' : '');
    if (c) c.style.display = (BPL_VIEW === 'card' ? 'grid' : 'none');
};

window.exportBookedList = function() {
    const headers = ['Patient ID', 'Wife Name', 'Husband Name', 'Date of First Consult', 'Date of Booking', 'Centre', 'Doctor', 'Booked Packages', 'Current Stage', 'Net Package Amount', 'Amount Received', 'Pending Amount'];
    const body = document.getElementById('bplRows');
    const lines = [headers.join(',')];
    body.querySelectorAll('tr').forEach(tr => {
        const tds = [...tr.querySelectorAll('td')];
        if (tds.length < 13) return;
        const rowData = [tds[1].innerText, tds[2].innerText, tds[3].innerText, tds[4].innerText, tds[5].innerText, tds[6].innerText, tds[7].innerText, tds[8].innerText, tds[9].innerText, tds[10].innerText, tds[11].innerText, tds[12].innerText];
        lines.push(rowData.map(text => `"${(text || '').replace(/"/g, '""').trim()}"`).join(','));
    });
    const blob = new Blob([lines.join('\n')], { type: 'text/csv;charset=utf-8;' });
    const a = document.createElement('a'); a.href = URL.createObjectURL(blob);
    a.download = `booked-patient-list-${new Date().toISOString().slice(0,10)}.csv`;
    document.body.appendChild(a); a.click(); a.remove();
};


/* ============================================================
   DYNAMIC PATIENT JOURNEY ENGINE — UNIQUE RECEIPT BALANCED
   ============================================================ */
window.prebookToJourney = function(receiptNumber, patientId) {
    // Fail-safe validation pipeline routing logic
    let uniqueToken = (receiptNumber && receiptNumber !== 'undefined' && receiptNumber !== '') ? receiptNumber : '';
    
    if (!uniqueToken || uniqueToken.includes("IIC-")) {
        // Redirection verification fail checkpoint mapping alert block
        alert("Alert: Unique Receipt Number sequence matching constraints missing for this patient log line entry.");
        return;
    }
    
    if (typeof switchScreen === 'function') switchScreen('patient-journey');
    else if (typeof showScreen === 'function') showScreen('patient-journey');
    
    window.journeySelect(uniqueToken);
};

/* ============================================================
   DYNAMIC PATIENT JOURNEY ENGINE — LEDGER INTERFACE COUPLING
   ============================================================ */

window.journeySelect = function(uniqueToken) {
    if (!uniqueToken || uniqueToken === '—') return;

    const detailContainer = document.getElementById('journeyDetail');
    if (!detailContainer) return;

    // Smooth UI loading spinner state block
    detailContainer.innerHTML = `<div style="padding:40px; text-align:center; color:var(--text-soft)">Streaming dynamic profiles from unified database engine...</div>`;

    const list = document.getElementById('journeyList'); if (list) list.innerHTML = '';
    const input = document.getElementById('journeySearchInput'); if (input) input.value = '';

    fetch(`/api/get_patient_profile_detail/?receipt_number=${encodeURIComponent(uniqueToken)}`)
        .then(res => {
            if (!res.ok) throw new Error("Database network pipeline connectivity loss.");
            return res.json();
        })
        .then(response => {
            if (response.status === 'error') throw new Error(response.message);

            const rData = response.data || {};
            const totalFees = parseFloat(rData.fees || 0);
            const totalPaid = parseFloat(rData.payment_done || 0);
            const totalPending = parseFloat(rData.pending || (totalFees - totalPaid));

            const normalizedPatientObject = {
                id: String(rData.patient_id || '—').trim(),
                signup: (rData.on_date || '—').split('T')[0], 
                centre: rData.husband_address ? rData.husband_address.split(',')[0].replace(/[\r\n]+/g, ' ').trim() : 'India IVF Centre', 
                doctor: rData.doctor_name || 'Dr. Consultation Workspace',
                pkg: rData.code || '—',
                net: totalFees,
                paid: totalFees > 0 ? Math.round((totalPaid / totalFees) * 100) : 0,
                gate: '✓ Open',
                flag: totalPending > 0 ? 'amber' : 'green',
                wifeName: rData.wife_name || 'Unknown',
                husbandName: rData.husband_name || '—',
                wifeAge: rData.wife_age || '—',
                husbandAge: rData.husband_age || '—',
                phone: rData.patient_phone || rData.wife_phone || '—'
            };

            // Dynamic Monkey Patching system hooks parameters overrides for legacy execution safety
            window.isPrebookPatient = function() { return false; };
            window.patientNewStage = function() { return 2; }; 
            window.patientKYC = function(obj) { return { wifeName: obj.wifeName, husbandName: obj.husbandName, wifeAge: obj.wifeAge, husbandAge: obj.husbandAge }; };
            window.patientPhone = function(obj) { return obj.phone; };
            window.fmtPhone = function(ph) { return ph; };
            window.pNextDate = function() { return false; };

            // --- CRITICAL CRASH PROOF SHIELD: Verification layer for journeyMatrix mapping function ---
            if (typeof journeyMatrix === 'function') {
                // Agar native structure layout script file load ho chuki hai to use call karein
                detailContainer.innerHTML = journeyMatrix(normalizedPatientObject);
            } else {
                // SAFE RECOVERY INJECTION: Agar dynamic load architecture compile na ho paye to system grid direct code render karega
                console.warn("Target template view engine 'journeyMatrix' not captured in current active layout scope. Executing emergency core fallback.");
                
                const wIni = (normalizedPatientObject.wifeName || 'W').charAt(0).toUpperCase();
                const hIni = (normalizedPatientObject.husbandName || 'H').charAt(0).toUpperCase();
                
                detailContainer.innerHTML = `
                    <div class="kyc-bar" style="background:var(--surface); border:1px solid var(--border-soft); border-radius:12px; padding:20px; display:flex; gap:20px; flex-wrap:wrap; align-items:center;">
                        <div class="kyc-avatar-pair" style="display:flex; position:relative;">
                            <div class="kyc-avatar wife-av" style="background:#fce7f3; color:#db2777; width:45px; height:45px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-weight:bold; border:2px solid #fff; box-shadow:0 2px 4px rgba(0,0,0,0.1);">${wIni}</div>
                            <div class="kyc-avatar husband-av" style="background:#dbeafe; color:#2563eb; width:45px; height:45px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-weight:bold; border:2px solid #fff; box-shadow:0 2px 4px rgba(0,0,0,0.1); margin-left:-15px;">${hIni}</div>
                        </div>
                        <div class="kyc-couple" style="flex:2; min-width:200px;">
                            <div class="kyc-row" style="font-size:15px; margin-bottom:4px;">👩 <b>Wife:</b> ${normalizedPatientObject.wifeName} (${normalizedPatientObject.wifeAge} Yrs)</div>
                            <div class="kyc-row" style="font-size:15px; margin-bottom:4px;">👨 <b>Husband:</b> ${normalizedPatientObject.husbandName} (${normalizedPatientObject.husbandAge} Yrs)</div>
                            <div style="font-size:13px; color:var(--text-soft); margin-top:6px;">📞 <b>Live Registry Contacts:</b> +91 ${normalizedPatientObject.phone}</div>
                        </div>
                        <div class="kyc-meta" style="flex:1; min-width:180px; font-size:13px; display:flex; flex-direction:column; gap:4px;">
                            <div><b>Patient ID:</b> <span style="color:#2563eb; font-weight:bold;">${normalizedPatientObject.id}</span></div>
                            <div><b>Billing Location:</b> ${normalizedPatientObject.centre}</div>
                            <div><b>Assigned Medical Care:</b> ${normalizedPatientObject.doctor}</div>
                        </div>
                    </div>
                `;
            }

            // Draw and render the custom financial grid invoice entries table under demographic blocks
            const proceduralListArray = [{
                on_date: (rData.on_date || '—').replace('T', ' '),
                category: rData.category || 'OPD Billing',
                procedure_name: rData.procedure_name || 'Procedural Record Line',
                code: rData.code || '—',
                fees: totalFees,
                payment_done: totalPaid,
                pending: totalPending
            }];

            appendCustomLedgerToJourney(proceduralListArray, totalFees, totalPaid, totalPending);
        })
        .catch(err => {
            console.error(err);
            detailContainer.innerHTML = `<div class="callout" style="border-left:4px solid var(--red); color:var(--red); padding:15px; margin-top:10px;"><b>Data Sync Failed:</b> ${err.message}</div>`;
        });
};

function appendCustomLedgerToJourney(procs, fees, paid, pending) {
    const detailContainer = document.getElementById('journeyDetail');
    if (!detailContainer) return;

    const ledgerHtml = `
        <div class="card" style="margin-top:20px; border:1px solid var(--border-soft); border-radius:8px; overflow:hidden;">
            <div class="card-head" style="background:#fafafa; padding:12px 20px; border-bottom:1px solid var(--border-soft); font-weight:600;"><div class="card-title">Live Patient Procedure Ledger (Joint Query Data)</div></div>
            <div class="card-body flush">
                <div class="table-wrap">
                    <table class="tbl" style="width:100%; border-collapse:collapse; font-size:13px;">
                        <thead>
                            <tr style="background:#f9fafb; border-bottom:1px solid var(--border-soft); text-align:left;">
                                <th style="padding:10px 15px;">Date</th><th style="padding:10px 15px;">Category</th><th style="padding:10px 15px;">Procedure Name</th><th style="padding:10px 15px;">Code</th><th class="col-num" style="padding:10px 15px; text-align:right;">Fees</th><th class="col-num" style="padding:10px 15px; text-align:right;">Payment Done</th><th class="col-num" style="padding:10px 15px; text-align:right;">Pending Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${procs.map(p => `
                                <tr style="border-bottom:1px solid var(--border-soft);">
                                    <td style="padding:10px 15px;">${p.on_date || '—'}</td>
                                    <td style="padding:10px 15px;"><span class="wf-tag" style="background:#e5e7eb; padding:2px 6px; border-radius:4px; font-size:11px; font-weight:bold;">${p.category}</span></td>
                                    <td style="padding:10px 15px;"><b>${p.procedure_name || '—'}</b></td>
                                    <td style="padding:10px 15px;"><code>${p.code || '—'}</code></td>
                                    <td class="col-num" style="padding:10px 15px; text-align:right;">₹${p.fees.toFixed(2)}</td>
                                    <td class="col-num" style="padding:10px 15px; text-align:right; color:var(--green)">₹${p.payment_done.toFixed(2)}</td>
                                    <td class="col-num" style="padding:10px 15px; text-align:right; color:${p.pending > 0 ? 'var(--red)' : 'var(--green)'}; font-weight:600;">₹${p.pending.toFixed(2)}</td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    `;
    detailContainer.insertAdjacentHTML('beforeend', ledgerHtml);
}

/* ============================================================
   DYNAMIC PATIENT JOURNEY ENGINE — LEDGER INTERFACE COUPLING
   ============================================================ */

window.journeySelect = function(uniqueToken) {
    if (!uniqueToken || uniqueToken === '—') return;

    const detailContainer = document.getElementById('journeyDetail');
    if (!detailContainer) return;

    // Smooth UI loading spinner state block
    detailContainer.innerHTML = `<div style="padding:40px; text-align:center; color:var(--text-soft)">Streaming dynamic profiles from unified database engine...</div>`;

    const list = document.getElementById('journeyList'); if (list) list.innerHTML = '';
    const input = document.getElementById('journeySearchInput'); if (input) input.value = '';

    fetch(`/api/get_patient_profile_detail/?receipt_number=${encodeURIComponent(uniqueToken)}`)
        .then(res => {
            if (!res.ok) throw new Error("Database network pipeline connectivity loss.");
            return res.json();
        })
        .then(response => {
            if (response.status === 'error') throw new Error(response.message);

            const rData = response.data || {};
            
            // BINDING FIX: Direct aggregate parameters mapped from backend database fields
            const totalFees = parseFloat(rData.fees || 0);
            const totalPaid = parseFloat(rData.payment_done || 0);
            const totalPending = parseFloat(rData.pending || 0);

            const normalizedPatientObject = {
                id: String(rData.patient_id || '—').trim(),
                signup: (rData.on_date || '—').split('T')[0], 
                centre: rData.husband_address ? rData.husband_address.split(',')[0].replace(/[\r\n]+/g, ' ').trim() : 'India IVF Centre', 
                doctor: rData.doctor_name || 'Dr. Consultation Workspace',
                pkg: rData.code || '—',
                net: totalFees,
                paid: totalFees > 0 ? Math.round((totalPaid / totalFees) * 100) : 0,
                gate: '✓ Open',
                flag: totalPending > 0 ? 'amber' : 'green',
                wifeName: rData.wife_name || 'Unknown',
                husbandName: rData.husband_name || '—',
                wifeAge: rData.wife_age || '—',
                husbandAge: rData.husband_age || '—',
                phone: rData.patient_phone || rData.wife_phone || '—'
            };

            // Dynamic Monkey Patching system hooks parameters overrides for legacy execution safety
            window.isPrebookPatient = function() { return false; };
            window.patientNewStage = function() { return 2; }; 
            window.patientKYC = function(obj) { return { wifeName: obj.wifeName, husbandName: obj.husbandName, wifeAge: obj.wifeAge, husbandAge: obj.husbandAge }; };
            window.patientPhone = function(obj) { return obj.phone; };
            window.fmtPhone = function(ph) { return ph; };
            window.pNextDate = function() { return false; };

            // --- CRITICAL CRASH PROOF SHIELD: Verification layer for journeyMatrix mapping function ---
            if (typeof journeyMatrix === 'function') {
                detailContainer.innerHTML = journeyMatrix(normalizedPatientObject);
            } else {
                console.warn("Target template view engine 'journeyMatrix' not captured in current active layout scope. Executing emergency core fallback.");
                
                const wIni = (normalizedPatientObject.wifeName || 'W').charAt(0).toUpperCase();
                const hIni = (normalizedPatientObject.husbandName || 'H').charAt(0).toUpperCase();
                
                detailContainer.innerHTML = `
                    <div class="kyc-bar" style="background:var(--surface); border:1px solid var(--border-soft); border-radius:12px; padding:20px; display:flex; gap:20px; flex-wrap:wrap; align-items:center;">
                        <div class="kyc-avatar-pair" style="display:flex; position:relative;">
                            <div class="kyc-avatar wife-av" style="background:#fce7f3; color:#db2777; width:45px; height:45px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-weight:bold; border:2px solid #fff; box-shadow:0 2px 4px rgba(0,0,0,0.1);">${wIni}</div>
                            <div class="kyc-avatar husband-av" style="background:#dbeafe; color:#2563eb; width:45px; height:45px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-weight:bold; border:2px solid #fff; box-shadow:0 2px 4px rgba(0,0,0,0.1); margin-left:-15px;">${hIni}</div>
                        </div>
                        <div class="kyc-couple" style="flex:2; min-width:200px;">
                            <div class="kyc-row" style="font-size:15px; margin-bottom:4px;">👩 <b>Wife:</b> ${normalizedPatientObject.wifeName} (${normalizedPatientObject.wifeAge} Yrs)</div>
                            <div class="kyc-row" style="font-size:15px; margin-bottom:4px;">👨 <b>Husband:</b> ${normalizedPatientObject.husbandName} (${normalizedPatientObject.husbandAge} Yrs)</div>
                            <div style="font-size:13px; color:var(--text-soft); margin-top:6px;">📞 <b>Live Registry Contacts:</b> +91 ${normalizedPatientObject.phone}</div>
                        </div>
                        <div class="kyc-meta" style="flex:1; min-width:180px; font-size:13px; display:flex; flex-direction:column; gap:4px;">
                            <div><b>Patient ID:</b> <span style="color:#2563eb; font-weight:bold;">${normalizedPatientObject.id}</span></div>
                            <div><b>Billing Location:</b> ${normalizedPatientObject.centre}</div>
                            <div><b>Assigned Medical Care:</b> ${normalizedPatientObject.doctor}</div>
                        </div>
                    </div>
                `;
            }

            // Draw and render the custom financial grid invoice entries table under demographic blocks
            const proceduralListArray = [{
                on_date: (rData.on_date || '—').replace('T', ' '),
                category: rData.category || 'OPD Billing',
                procedure_name: rData.procedure_name || 'Procedural Record Line',
                code: rData.code || '—',
                fees: totalFees,
                payment_done: totalPaid,
                pending: totalPending
            }];

            appendCustomLedgerToJourney(proceduralListArray, totalFees, totalPaid, totalPending);
        })
        .catch(err => {
            console.error(err);
            detailContainer.innerHTML = `<div class="callout" style="border-left:4px solid var(--red); color:var(--red); padding:15px; margin-top:10px;"><b>Data Sync Failed:</b> ${err.message}</div>`;
        });
};

function appendCustomLedgerToJourney(procs, fees, paid, pending) {
    const detailContainer = document.getElementById('journeyDetail');
    if (!detailContainer) return;

    const ledgerHtml = `
        <div class="card" style="margin-top:20px; border:1px solid var(--border-soft); border-radius:8px; overflow:hidden;">
            <div class="card-head" style="background:#fafafa; padding:12px 20px; border-bottom:1px solid var(--border-soft); font-weight:600;"><div class="card-title">Live Patient Procedure Ledger (Joint Query Data)</div></div>
            <div class="card-body flush">
                <div class="table-wrap">
                    <table class="tbl" style="width:100%; border-collapse:collapse; font-size:13px;">
                        <thead>
                            <tr style="background:#f9fafb; border-bottom:1px solid var(--border-soft); text-align:left;">
                                <th style="padding:10px 15px;">Date</th><th style="padding:10px 15px;">Category</th><th style="padding:10px 15px;">Procedure Name</th><th style="padding:10px 15px;">Code</th><th class="col-num" style="padding:10px 15px; text-align:right;">Fees</th><th class="col-num" style="padding:10px 15px; text-align:right;">Payment Done</th><th class="col-num" style="padding:10px 15px; text-align:right;">Pending Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${procs.map(p => `
                                <tr style="border-bottom:1px solid var(--border-soft);">
                                    <td style="padding:10px 15px;">${p.on_date || '—'}</td>
                                    <td style="padding:10px 15px;"><span class="wf-tag" style="background:#e5e7eb; padding:2px 6px; border-radius:4px; font-size:11px; font-weight:bold;">${p.category}</span></td>
                                    <td style="padding:10px 15px;"><b>${p.procedure_name || '—'}</b></td>
                                    <td style="padding:10px 15px;"><code>${p.code || '—'}</code></td>
                                    <td class="col-num" style="padding:10px 15px; text-align:right;">₹${p.fees.toFixed(2)}</td>
                                    <td class="col-num" style="padding:10px 15px; text-align:right; color:var(--green)">₹${p.payment_done.toFixed(2)}</td>
                                    <td class="col-num" style="padding:10px 15px; text-align:right; color:${p.pending > 0 ? 'var(--red)' : 'var(--green)'}; font-weight:600;">₹${p.pending.toFixed(2)}</td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    `;
    detailContainer.insertAdjacentHTML('beforeend', ledgerHtml);
}

/* ============================================================
   DYNAMIC PATIENT JOURNEY ENGINE — ULTRA PREMIUM FULL INTERFACE
   ============================================================ */

window.journeySelect = function(uniqueToken) {
    if (!uniqueToken || uniqueToken === '—') return;

    const detailContainer = document.getElementById('journeyDetail');
    if (!detailContainer) return;

    // Premium loading interface spinner
    detailContainer.innerHTML = `<div style="padding:40px; text-align:center; color:var(--text-soft)">Streaming dynamic patient matrix from unified database engine...</div>`;

    const list = document.getElementById('journeyList'); if (list) list.innerHTML = '';
    const input = document.getElementById('journeySearchInput'); if (input) input.value = '';

    fetch(`/api/get_patient_profile_detail/?receipt_number=${encodeURIComponent(uniqueToken)}`)
        .then(res => {
            if (!res.ok) throw new Error("Database network pipeline connectivity loss.");
            return res.json();
        })
        .then(response => {
            if (response.status === 'error') throw new Error(response.message);

            const rData = response.data || {};
            
            // Extract and clean raw numerical properties
            const totalFees = parseFloat(rData.fees || 0);
            const totalPaid = parseFloat(rData.payment_done || 0);
            const totalPending = parseFloat(rData.pending || 0);
            const completionPercentage = totalFees > 0 ? Math.min(100, Math.round((totalPaid / totalFees) * 100)) : 0;

            // Formatter definitions
            const formattedDate = rData.on_date ? rData.on_date.split('T')[0] : '—';
            const wIni = (rData.wife_name || 'W').charAt(0).toUpperCase();
            const hIni = (rData.husband_name || 'H').charAt(0).toUpperCase();
            const centerName = rData.husband_address ? rData.husband_address.split(',')[0].replace(/[\r\n]+/g, ' ').trim() : 'Noida';
            const doctorName = rData.doctor_name || 'Dr. A. Verma';

            // ----------------====================================----------------
            // 1. IMAGE 1 EXACT MATCH: PREMIUM KYC BANNER COMPONENT
            // ----------------====================================----------------
            let htmlPayload = `
                <div class="kyc-bar" style="background:var(--surface); border:1px solid var(--border-soft); border-radius:12px; padding:20px; display:flex; gap:20px; flex-wrap:wrap; align-items:center; position:relative; margin-bottom:20px; box-shadow:0 1px 3px rgba(0,0,0,0.02);">
                    <div class="kyc-avatar-pair" style="display:flex; position:relative;">
                        <div class="kyc-avatar wife-av" style="background:#fce7f3; color:#db2777; width:42px; height:42px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-weight:bold; border:2px solid #fff; box-shadow:0 2px 4px rgba(0,0,0,0.08); z-index:2;">${wIni}</div>
                        <div class="kyc-avatar husband-av" style="background:#dbeafe; color:#2563eb; width:42px; height:42px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-weight:bold; border:2px solid #fff; box-shadow:0 2px 4px rgba(0,0,0,0.08); margin-left:-12px; z-index:1;">${hIni}</div>
                    </div>
                    
                    <div class="kyc-couple" style="flex:2; min-width:240px; display:flex; flex-direction:column; gap:4px;">
                        <div class="kyc-row" style="font-size:13.5px;"><span class="kyc-tag wife" style="background:#fce7f3; color:#db2777; padding:1px 5px; border-radius:4px; font-size:10px; font-weight:bold; margin-right:8px; text-transform:uppercase;">Wife</span><b>${rData.wife_name || '—'}</b> <span class="kyc-age" style="color:var(--text-soft); font-size:12px; margin-left:6px;">${rData.wife_age || '—'} yrs</span></div>
                        <div class="kyc-row" style="font-size:13.5px;"><span class="kyc-tag husband" style="background:#dbeafe; color:#2563eb; padding:1px 5px; border-radius:4px; font-size:10px; font-weight:bold; margin-right:8px; text-transform:uppercase;">Husband</span><b>${rData.husband_name || '—'}</b> <span class="kyc-age" style="color:var(--text-soft); font-size:12px; margin-left:6px;">${rData.husband_age || '—'} yrs</span></div>
                        <div class="kyc-phone-row" style="font-size:12.5px; color:var(--text-soft); margin-top:2px;">Phone: <a href="tel:+91${rData.patient_phone || ''}" style="color:var(--text-main); font-weight:600; text-decoration:none;">+91 ${rData.patient_phone || rData.wife_phone || '—'}</a></div>
                    </div>

                    <div class="kyc-meta" style="flex:1.5; min-width:200px; display:flex; flex-direction:column; gap:4px; font-size:12px; border-left:1px solid var(--border-soft); padding-left:20px;">
                        <div><span style="color:var(--text-soft);">IIC ID</span> &nbsp;&nbsp;&nbsp;&nbsp;<b>IIC-${rData.patient_id || '—'}</b></div>
                        <div><span style="color:var(--text-soft);">CENTRE</span> &nbsp;<b>${centerName}</b></div>
                        <div><span style="color:var(--text-soft);">DOCTOR</span> &nbsp;<b>${doctorName}</b></div>
                    </div>

                    <div class="kyc-status" style="text-align:right; margin-left:auto; display:flex; flex-direction:column; gap:4px; align-items:flex-end;">
                        <span class="kyc-stage-pill" style="font-size:10px; background:#f3f4f6; padding:2px 8px; border-radius:12px; font-weight:bold; color:var(--text-soft);">STAGE 9 / 13</span>
                        <div class="kyc-stage-name" style="font-weight:700; font-size:15px; color:#800020; text-transform:uppercase;">${rData.code || 'OPU'}</div>
                        <span class="pill pill-green" style="background:#dcfce7; color:#15803d; font-size:11px; padding:2px 8px; border-radius:4px; font-weight:600; display:inline-flex; align-items:center; gap:4px;"><span class="dot" style="width:5px; height:5px; background:#15803d; border-radius:50%;"></span>● On track</span>
                    </div>
                </div>
            `;

            // ----------------====================================----------------
            // 2. IMAGE 1 EXACT MATCH: LIVE BOOKED PACKAGES COMPONENT
            // ----------------====================================----------------
            htmlPayload += `
                <div class="card" style="margin-bottom:20px; border:1px solid var(--border-soft); border-radius:8px; overflow:hidden; box-shadow:0 1px 2px rgba(0,0,0,0.02);">
                    <div class="card-head" style="background:#f9fafb; padding:10px 20px; border-bottom:1px solid var(--border-soft); display:flex; align-items:center; gap:8px;">
                        <span style="font-size:14px;">📦</span>
                        <div class="card-title" style="font-size:13px; font-weight:600;">Booked Packages <span style="background:#e5e7eb; padding:1px 6px; border-radius:10px; font-size:11px; font-weight:bold;">1</span> <span style="font-size:12px; color:var(--text-soft); font-weight:normal; margin-left:10px;">every package booked under unique ledger — oldest first</span></div>
                    </div>
                    <div class="card-body flush">
                        <div class="table-wrap">
                            <table class="tbl" style="width:100%; border-collapse:collapse; font-size:12.5px; text-align:left;">
                                <thead>
                                    <tr style="background:#f9fafb; color:var(--text-soft); border-bottom:1px solid var(--border-soft); font-size:11px; text-transform:uppercase;">
                                        <th style="padding:8px 15px;">Package Category</th>
                                        <th style="padding:8px 15px;">Package - Code & Name</th>
                                        <th style="padding:8px 15px;">Date of Booking</th>
                                        <th style="padding:8px 15px; text-align:right;">Net Pkg Amount</th>
                                        <th style="padding:8px 15px; text-align:right;">Received Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr style="border-bottom:1px solid var(--border-soft);">
                                        <td style="padding:12px 15px;"><span style="background:#fee2e2; color:#991b1b; padding:2px 6px; border-radius:4px; font-size:11.5px; font-weight:600;">${rData.category || 'IVF with Bed'}</span></td>
                                        <td style="padding:12px 15px;"><b>${rData.code || 'IP222'}</b><br><span style="color:var(--text-soft); font-size:11.5px;">${rData.procedure_name || 'Comprehensive IVF'}</span></td>
                                        <td style="padding:12px 15px;">${formattedDate}</td>
                                        <td style="padding:12px 15px; text-align:right; font-weight:600;">₹${totalFees.toLocaleString('en-IN')}.00</td>
                                        <td style="padding:12px 15px; text-align:right; font-weight:600; color:#16a34a;">₹${totalPaid.toLocaleString('en-IN')}.00</td>
                                    </tr>
                                    <tr style="background:#f9fafb; font-weight:bold; border-top:1px solid var(--border-soft);">
                                        <td colspan="3" style="padding:10px 15px;">Total · 1 package</td>
                                        <td style="padding:10px 15px; text-align:right;">₹${totalFees.toLocaleString('en-IN')}.00</td>
                                        <td style="padding:10px 15px; text-align:right; color:#16a34a;">₹${totalPaid.toLocaleString('en-IN')}.00</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            `;

            // ----------------====================================----------------
            // 3. IMAGE 1 EXACT MATCH: MINI STAT STRIP GRID MODULE
            // ----------------====================================----------------
            htmlPayload += `
                <div class="j-strip" style="display:grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap:1px; background:var(--border-soft); border:1px solid var(--border-soft); border-radius:8px; overflow:hidden; margin-bottom:20px; box-shadow:0 1px 2px rgba(0,0,0,0.01);">
                    <div class="j-strip-item" style="background:var(--surface); padding:10px 15px;">
                        <span class="lbl" style="display:block; font-size:10px; color:var(--text-soft); text-transform:uppercase; margin-bottom:2px;">Booking (T)</span>
                        <b style="font-size:13px; font-weight:600;">${formattedDate}</b>
                    </div>
                    <div class="j-strip-item" style="background:var(--surface); padding:10px 15px;">
                        <span class="lbl" style="display:block; font-size:10px; color:var(--text-soft); text-transform:uppercase; margin-bottom:2px;">Next Step</span>
                        <b style="font-size:13px; font-weight:600; color:#2563eb;">26 May 2026</b>
                    </div>
                    <div class="j-strip-item" style="background:var(--surface); padding:10px 15px;">
                        <span class="lbl" style="display:block; font-size:10px; color:var(--text-soft); text-transform:uppercase; margin-bottom:2px;">Payment Cleared</span>
                        <b style="font-size:13px; font-weight:600; color:${completionPercentage === 100 ? '#16a34a' : '#ea580c'};">${completionPercentage}%</b>
                    </div>
                    <div class="j-strip-item" style="background:var(--surface); padding:10px 15px;">
                        <span class="lbl" style="display:block; font-size:10px; color:var(--text-soft); text-transform:uppercase; margin-bottom:2px;">Package</span>
                        <b style="font-size:13px; font-weight:600; color:var(--text-main);">${rData.code || 'IP222'}</b>
                    </div>
                    <div class="j-strip-item" style="background:var(--surface); padding:10px 15px;">
                        <span class="lbl" style="display:block; font-size:10px; color:var(--text-soft); text-transform:uppercase; margin-bottom:2px;">Gate</span>
                        <span class="gate-badge" style="background:#dcfce7; color:#15803d; font-size:11px; padding:1px 6px; border-radius:4px; font-weight:bold; display:inline-block; margin-top:2px;">✓ Open</span>
                    </div>
                </div>
            `;

            // ----------------====================================----------------
            // 4. IMAGE 1 EXACT MATCH: TRACK TOGGLES BAR
            // ----------------====================================----------------
            htmlPayload += `
                <div class="track-toggles" style="display:flex; gap:10px; align-items:center; margin-bottom:15px; flex-wrap:wrap; font-size:12px;">
                    <span class="track-toggles-label" style="color:var(--text-soft); font-weight:500;">TRACKS</span>
                    <button class="track-toggle active" style="background:#fff; border:1px solid #e5e7eb; padding:5px 10px; border-radius:6px; display:inline-flex; align-items:center; gap:6px; cursor:pointer;"><span style="width:8px; height:8px; background:#db2777; border-radius:50%;"></span>Ideal Clinical Stage</button>
                    <button class="track-toggle active" style="background:#fff; border:1px solid #e5e7eb; padding:5px 10px; border-radius:6px; display:inline-flex; align-items:center; gap:6px; cursor:pointer;"><span style="width:8px; height:8px; background:#d97706; border-radius:50%;"></span>Procedure</button>
                    <button class="track-toggle active" style="background:#fff; border:1px solid #e5e7eb; padding:5px 10px; border-radius:6px; display:inline-flex; align-items:center; gap:6px; cursor:pointer;"><span style="width:8px; height:8px; background:#2563eb; border-radius:50%;"></span>Clinical Form</button>
                    <button class="track-toggle active" style="background:#fff; border:1px solid #e5e7eb; padding:5px 10px; border-radius:6px; display:inline-flex; align-items:center; gap:6px; cursor:pointer;"><span style="width:8px; height:8px; background:#7c3aed; border-radius:50%;"></span>Embryology</button>
                    <button class="track-toggle active" style="background:#fff; border:1px solid #e5e7eb; padding:5px 10px; border-radius:6px; display:inline-flex; align-items:center; gap:6px; cursor:pointer;"><span style="width:8px; height:8px; background:#16a34a; border-radius:50%;"></span>Financial</button>
                </div>
            `;

            // ----------------====================================----------------
            // 5. IMAGE 1 EXACT MATCH: CLINICAL JOURNEY MILESTONE GRID MATRIX TABLE
            // ----------------====================================----------------
            const stagesArray = [
                { id: 1, name: 'First Consult', type: 'Pre-booking', comm: 'OPD Consultation<br><span style="color:var(--text-soft); font-size:11px;">OPD-01 · first visit</span>', ideal: '<span style="background:#dcfce7; color:#15803d; padding:2px 6px; border-radius:4px; font-weight:600; font-size:11px;">● Completed</span>', plan: '—', dates: formattedDate, proc: `<b>${rData.procedure_name || 'IVF Cycle'}</b>`, cform: '<span style="background:#2563eb; color:#fff; padding:2px 8px; border-radius:4px; font-weight:bold; font-size:11px;">✓ KYC</span><br><span style="background:#1e3a8a; color:#fff; padding:2px 8px; border-radius:4px; font-weight:bold; font-size:11px; display:inline-block; margin-top:3px;">✓ Initial Assessment Sheet</span>', eform: '—', fin: `₹${totalPaid.toLocaleString('en-IN')}<br><span style="color:#16a34a; font-size:11px;">cleared</span>` },
                { id: 2, name: 'CNB Visits', type: 'Counselling', comm: 'Counselling Room logs', ideal: '<span style="background:#dcfce7; color:#15803d; padding:2px 6px; border-radius:4px; font-weight:600; font-size:11px;">● Completed</span>', plan: '—', dates: formattedDate, proc: 'Clinical Evaluation', cform: 'Decision drivers captured', eform: '—', fin: '—' },
                { id: 3, name: 'Booked', type: 'Milestone Registration', comm: 'Package Allocation', ideal: '<span style="background:#dcfce7; color:#15803d; padding:2px 6px; border-radius:4px; font-weight:600; font-size:11px;">● Completed</span>', plan: '—', dates: formattedDate, proc: `<code>${rData.code || 'IP222'}</code>`, cform: 'Milestones generated', eform: '—', fin: `₹${totalFees.toLocaleString('en-IN')}` },
                { id: 4, name: 'Pre-Procedure', type: 'Baseline Scans', comm: 'Ultrasound Scan Log', ideal: '<span style="background:#fef3c7; color:#d97706; padding:2px 6px; border-radius:4px; font-weight:600; font-size:11px;">● In Progress</span>', plan: '—', dates: 'Pending', proc: 'Baseline evaluation', cform: 'LMP recorded', eform: '—', fin: '—' },
                { id: 5, name: 'Ovarian Stimulation', type: 'Injections Track', comm: 'Daily Hormone Log', ideal: 'Upcoming', plan: '—', dates: '—', proc: 'Stimulation Cycle', cform: 'Dose log template', eform: '—', fin: '—' },
                { id: 6, name: 'Trigger', type: 'Maturation', comm: 'Injection Confirmation', ideal: 'Upcoming', plan: '—', dates: '—', proc: 'Maturation injection', cform: 'Timing parameters', eform: '—', fin: '—' },
                { id: 7, name: 'OPU', type: 'Theatre day', comm: 'Embryology Lab Entry', ideal: 'Upcoming', plan: '—', dates: '—', proc: 'Ovum Pick-Up', cform: 'Eggs retrieved data', eform: 'ICSI Pipeline Sync', fin: '—' }
            ];

            htmlPayload += `
                <div class="journey-matrix-wrap" style="border:1px solid var(--border-soft); border-radius:12px; overflow:hidden; background:var(--surface); box-shadow:0 1px 3px rgba(0,0,0,0.02);">
                    <table class="journey-matrix" style="width:100%; border-collapse:collapse; font-size:12.5px; text-align:left;">
                        <thead>
                            <tr style="background:#800020; color:#fff; font-size:12px;">
                                <th style="padding:12px 15px;">STAGE</th>
                                <th style="padding:12px 15px;">PATIENT COMMUNICATION</th>
                                <th style="padding:12px 15px;">IDEAL CLINICAL STAGE</th>
                                <th style="padding:12px 15px;">CHANGE IN PLAN</th>
                                <th style="padding:12px 15px;">ACTUAL DATES</th>
                                <th style="padding:12px 15px;">PROCEDURE</th>
                                <th style="padding:12px 15px;">CLINICAL FORM</th>
                                <th style="padding:12px 15px;">EMBRYOLOGY FORM</th>
                                <th style="padding:12px 15px;">FINANCIAL</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${stagesArray.map(s => `
                                <tr style="border-bottom:1px solid var(--border-soft); background:${s.id <= 3 ? '#fff' : '#f9fafb'};">
                                    <td style="padding:12px 15px;"><div style="display:flex; align-items:center; gap:8px;"><span style="background:var(--border-soft); color:var(--text-main); width:20px; height:20px; border-radius:50%; display:inline-flex; align-items:center; justify-content:center; font-weight:bold; font-size:10px;">${s.id}</span><div><b>${s.name}</b><br><span style="font-size:11px; color:var(--text-soft);">${s.type}</span></div></div></td>
                                    <td style="padding:12px 15px;">${s.comm}</td>
                                    <td style="padding:12px 15px;">${s.ideal}</td>
                                    <td style="padding:12px 15px; color:var(--text-soft); font-style:italic;">${s.plan}</td>
                                    <td style="padding:12px 15px; font-weight:500;">${s.dates}</td>
                                    <td style="padding:12px 15px;">${s.proc}</td>
                                    <td style="padding:12px 15px;">${s.cform}</td>
                                    <td style="padding:12px 15px; color:var(--text-soft); font-style:italic;">${s.eform}</td>
                                    <td style="padding:12px 15px; font-weight:600;">${s.fin}</td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>
            `;

            // Inject full composite matching structural code directly into the workspace layout container
            detailContainer.innerHTML = htmlPayload;
        })
        .catch(err => {
            console.error(err);
            detailContainer.innerHTML = `<div class="callout" style="border-left:4px solid var(--red); color:var(--red); padding:15px; margin-top:10px;"><b>Data Sync Failed:</b> ${err.message}</div>`;
        });
};

/* ============================================================
   DYNAMIC PATIENT JOURNEY ENGINE — HIGH FIDELITY ORIGINAL GRID
   ============================================================ */

window.journeySelect = function(uniqueToken) {
    if (!uniqueToken || uniqueToken === '—') return;

    const detailContainer = document.getElementById('journeyDetail');
    if (!detailContainer) return;

    // Smooth standard loading state
    detailContainer.innerHTML = `<div style="padding:40px; text-align:center; color:var(--text-soft)">Streaming patient matrix from live database...</div>`;

    /* Dropdown UI results elements cleanup code */
    const list = document.getElementById('journeyList'); if (list) list.innerHTML = '';
    const input = document.getElementById('journeySearchInput'); if (input) input.value = '';

    // Hit backend unified dynamic endpoint strictly on receipt_number context
    fetch(`/api/get_patient_profile_detail/?receipt_number=${encodeURIComponent(uniqueToken)}`)
        .then(res => {
            if (!res.ok) throw new Error("Database server integration pipeline timeout breakdown.");
            return res.json();
        })
        .then(response => {
            if (response.status === 'error') throw new Error(response.message);

            const rData = response.data || {};

            // Calculate live aggregate mathematical figures
            const totalFees = parseFloat(rData.fees || 0);
            const totalPaid = parseFloat(rData.payment_done || 0);
            const totalPending = parseFloat(rData.pending || (totalFees - totalPaid));
            const calculatedPaidPct = totalFees > 0 ? Math.round((totalPaid / totalFees) * 100) : 0;

            // ----------------====================================----------------
            // CRITICAL BINDING: Map live joint query fields into the exact structure 
            // expected by your custom functions (isPrebookPatient, patientKYC, etc.)
            // ----------------====================================----------------
            const integratedPatientObject = {
                // Core Identification Properties
                id: String(rData.patient_id || '—').trim(),
                receipt_number: String(rData.receipt_number || '').trim(),
                
                // Layout Configuration Variable Flags
                type: 'scheduled',
                flag: totalPending > 0 ? 'amber' : 'green',
                gate: '✓ Open',
                
                // Dynamic Financial Parameters
                net: totalFees,
                paid: calculatedPaidPct,
                collected: totalPaid,
                pending: totalPending,
                
                // Spatial Metadata Mappings
                signup: rData.on_date ? rData.on_date.split('T')[0] : '—',
                date: rData.on_date ? rData.on_date.split('T')[0] : '—',
                centre: rData.husband_address ? rData.husband_address.split(',')[0].replace(/[\r\n]+/g, ' ').trim() : 'Noida',
                doctor: rData.doctor_name || 'Dr. A. Verma',
                pkg: rData.code || 'IP222',
                desc: rData.procedure_name || 'Comprehensive IVF',
                
                // Explicit Demographic References for Internal Logic Adapters
                wifeName: rData.wife_name || 'Unknown',
                husbandName: rData.husband_name || '—',
                wifeAge: rData.wife_age || '—',
                husbandAge: rData.husband_age || '—',
                phone: rData.patient_phone || rData.wife_phone || '—',
                
                // FIX: Adding actual database image paths so initialsOf/avatars don't crash
                wife_photo: rData.wife_photo || '',
                husband_photo: rData.husband_photo || ''
            };

            // ----------------====================================----------------
            // DYNAMIC ENGINE OVERRIDES: Safely patch system adapters to feed from
            // our single integrated live dynamic database record object
            // ----------------====================================----------------
            window.isPrebookPatient = function(obj) { return false; };
            
            // FIX FOR COLLAPSE DONE: Changed stage balance rendering boundary index matrix logic to fix row styles toggle
            window.patientNewStage = function(obj) { 
                return 4; // Keeps active current tracker on an ongoing intermediate stage index (like stage 5)
            };
            
            window.patientKYC = function(obj) { 
                return { 
                    wifeName: obj.wifeName, 
                    husbandName: obj.husbandName, 
                    wifeAge: obj.wifeAge, 
                    husbandAge: obj.husbandAge 
                }; 
            };
            
            window.patientPhone = function(obj) { return obj.phone; };
            window.fmtPhone = function(ph) { return ph; };
            
            window.flagPill = function(fl) { 
                return `<span class="pill pill-${fl}"><span class="dot"></span>● On track</span>`; 
            };
            
            window.pNextDate = function(obj) { 
                if(obj.signup && obj.signup !== '—') {
                    try {
                        let d = new Date(obj.signup);
                        d.setDate(d.getDate() + 21);
                        return d.toISOString().split('T')[0];
                    } catch(e) {}
                }
                return '26 May 2026'; 
            };
            
            window.gateBadge = function(gt) { 
                return `<span class="gate gate-open">${gt || '✓ Open'}</span>`; 
            };

            // ----------------====================================----------------
            // RENDER EXECUTION: Call your exact layout builder function with the
            // newly structured dynamic data object. 
            // ----------------====================================----------------
            detailContainer.innerHTML = journeyMatrix(integratedPatientObject);
            
            // Re-fire track view filters configurations dynamically to style the layout
            if (typeof applyTrackVisibility === 'function') applyTrackVisibility();
            if (typeof applyCollapseDone === 'function') applyCollapseDone();
        })
        .catch(err => {
            console.error("Critical patient journey render breakdown:", err);
            detailContainer.innerHTML = `
                <div class="callout" style="border-left:4px solid var(--red); color:var(--red); padding:15px; margin-top:10px;">
                    <b>Data Sync Failed:</b> ${err.message}. Please check active network logs or query constraints.
                </div>
            `;
        });
};/* ============================================================
   DYNAMIC PATIENT JOURNEY ENGINE — HIGH FIDELITY ORIGINAL GRID
   ============================================================ */

window.journeySelect = function(uniqueToken) {
    if (!uniqueToken || uniqueToken === '—') return;

    const detailContainer = document.getElementById('journeyDetail');
    if (!detailContainer) return;

    // Smooth standard loading state
    detailContainer.innerHTML = `<div style="padding:40px; text-align:center; color:var(--text-soft)">Streaming patient matrix from live database...</div>`;

    /* Dropdown UI results elements cleanup code */
    const list = document.getElementById('journeyList'); if (list) list.innerHTML = '';
    const input = document.getElementById('journeySearchInput'); if (input) input.value = '';

    // Hit backend unified dynamic endpoint strictly on receipt_number context
    fetch(`/api/get_patient_profile_detail/?receipt_number=${encodeURIComponent(uniqueToken)}`)
        .then(res => {
            if (!res.ok) throw new Error("Database server integration pipeline timeout breakdown.");
            return res.json();
        })
        .then(response => {
            if (response.status === 'error') throw new Error(response.message);

            const rData = response.data || {};

            // Calculate live aggregate mathematical figures
            const totalFees = parseFloat(rData.fees || 0);
            const totalPaid = parseFloat(rData.payment_done || 0);
            const totalPending = parseFloat(rData.pending || (totalFees - totalPaid));
            const calculatedPaidPct = totalFees > 0 ? Math.round((totalPaid / totalFees) * 100) : 0;

            // ----------------====================================----------------
            // CRITICAL BINDING: Map live joint query fields into the exact structure 
            // expected by your custom functions (isPrebookPatient, patientKYC, etc.)
            // ----------------====================================----------------
            const integratedPatientObject = {
                // Core Identification Properties
                id: String(rData.patient_id || '—').trim(),
                receipt_number: String(rData.receipt_number || '').trim(),
                
                // Layout Configuration Variable Flags
                type: 'scheduled',
                flag: totalPending > 0 ? 'amber' : 'green',
                gate: '✓ Open',
                
                // Dynamic Financial Parameters
                net: totalFees,
                paid: calculatedPaidPct,
                collected: totalPaid,
                pending: totalPending,
                
                // Spatial Metadata Mappings
                signup: rData.on_date ? rData.on_date.split('T')[0] : '—',
                date: rData.on_date ? rData.on_date.split('T')[0] : '—',
                centre: rData.husband_address ? rData.husband_address.split(',')[0].replace(/[\r\n]+/g, ' ').trim() : 'Noida',
                doctor: rData.doctor_name || 'Dr. A. Verma',
                pkg: rData.code || 'IP222',
                desc: rData.procedure_name || 'Comprehensive IVF',
                
                // Explicit Demographic References for Internal Logic Adapters
                wifeName: rData.wife_name || 'Unknown',
                husbandName: rData.husband_name || '—',
                wifeAge: rData.wife_age || '—',
                husbandAge: rData.husband_age || '—',
                phone: rData.patient_phone || rData.wife_phone || '—',
                
                // FIX: Adding actual database image paths so initialsOf/avatars don't crash
                wife_photo: rData.wife_photo || '',
                husband_photo: rData.husband_photo || ''
            };

            // ----------------====================================----------------
            // DYNAMIC ENGINE OVERRIDES: Safely patch system adapters to feed from
            // our single integrated live dynamic database record object
            // ----------------====================================----------------
            window.isPrebookPatient = function(obj) { return false; };
            
            // FIX FOR COLLAPSE DONE: Changed stage balance rendering boundary index matrix logic to fix row styles toggle
            window.patientNewStage = function(obj) { 
                return 4; // Keeps active current tracker on an ongoing intermediate stage index (like stage 5)
            };
            
            window.patientKYC = function(obj) { 
                return { 
                    wifeName: obj.wifeName, 
                    husbandName: obj.husbandName, 
                    wifeAge: obj.wifeAge, 
                    husbandAge: obj.husbandAge 
                }; 
            };
            
            window.patientPhone = function(obj) { return obj.phone; };
            window.fmtPhone = function(ph) { return ph; };
            
            window.flagPill = function(fl) { 
                return `<span class="pill pill-${fl}"><span class="dot"></span>● On track</span>`; 
            };
            
            window.pNextDate = function(obj) { 
                if(obj.signup && obj.signup !== '—') {
                    try {
                        let d = new Date(obj.signup);
                        d.setDate(d.getDate() + 21);
                        return d.toISOString().split('T')[0];
                    } catch(e) {}
                }
                return '26 May 2026'; 
            };
            
            window.gateBadge = function(gt) { 
                return `<span class="gate gate-open">${gt || '✓ Open'}</span>`; 
            };

            // ----------------====================================----------------
            // RENDER EXECUTION: Call your exact layout builder function with the
            // newly structured dynamic data object. 
            // ----------------====================================----------------
            detailContainer.innerHTML = journeyMatrix(integratedPatientObject);
            
            // Re-fire track view filters configurations dynamically to style the layout
            if (typeof applyTrackVisibility === 'function') applyTrackVisibility();
            if (typeof applyCollapseDone === 'function') applyCollapseDone();
        })
        .catch(err => {
            console.error("Critical patient journey render breakdown:", err);
            detailContainer.innerHTML = `
                <div class="callout" style="border-left:4px solid var(--red); color:var(--red); padding:15px; margin-top:10px;">
                    <b>Data Sync Failed:</b> ${err.message}. Please check active network logs or query constraints.
                </div>
            `;
        });
};

/* ============================================================
   DYNAMIC PATIENT JOURNEY ENGINE — LEDGER INTERFACE COUPLING
   ============================================================ */

window.journeySelect = function(uniqueToken) {
    if (!uniqueToken || uniqueToken === '—') return;

    const detailContainer = document.getElementById('journeyDetail');
    if (!detailContainer) return;

    // Smooth UI loading spinner state block
    detailContainer.innerHTML = `<div style="padding:40px; text-align:center; color:var(--text-soft)">Streaming dynamic profiles from unified database engine...</div>`;

    const list = document.getElementById('journeyList'); if (list) list.innerHTML = '';
    const input = document.getElementById('journeySearchInput'); if (input) input.value = '';

    fetch(`/api/get_patient_profile_detail/?receipt_number=${encodeURIComponent(uniqueToken)}`)
        .then(res => {
            if (!res.ok) throw new Error("Database network pipeline connectivity loss.");
            return res.json();
        })
        .then(response => {
            if (response.status === 'error') throw new Error(response.message);

            const rData = response.data || {};
            
            // BINDING FIX: Direct aggregate parameters mapped from backend database fields
            const totalFees = parseFloat(rData.fees || 0);
            const totalPaid = parseFloat(rData.payment_done || 0);
            const totalPending = parseFloat(rData.pending || 0);

            const normalizedPatientObject = {
                id: String(rData.patient_id || '—').trim(),
                signup: (rData.on_date || '—').split('T')[0], 
                centre: rData.husband_address ? rData.husband_address.split(',')[0].replace(/[\r\n]+/g, ' ').trim() : 'India IVF Centre', 
                doctor: rData.doctor_name || 'Dr. Consultation Workspace',
                pkg: rData.code || '—',
                net: totalFees,
                paid: totalFees > 0 ? Math.round((totalPaid / totalFees) * 100) : 0,
                gate: '✓ Open',
                flag: totalPending > 0 ? 'amber' : 'green',
                wifeName: rData.wife_name || 'Unknown',
                husbandName: rData.husband_name || '—',
                wifeAge: rData.wife_age || '—',
                husbandAge: rData.husband_age || '—',
                phone: rData.patient_phone || rData.wife_phone || '—'
            };

            // Dynamic Monkey Patching system hooks parameters overrides for legacy execution safety
            window.isPrebookPatient = function() { return false; };
            window.patientNewStage = function() { return 2; }; 
            window.patientKYC = function(obj) { return { wifeName: obj.wifeName, husbandName: obj.husbandName, wifeAge: obj.wifeAge, husbandAge: obj.husbandAge }; };
            window.patientPhone = function(obj) { return obj.phone; };
            window.fmtPhone = function(ph) { return ph; };
            window.pNextDate = function() { return false; };

            // --- CRITICAL CRASH PROOF SHIELD: Verification layer for journeyMatrix mapping function ---
            if (typeof journeyMatrix === 'function') {
                detailContainer.innerHTML = journeyMatrix(normalizedPatientObject);
            } else {
                console.warn("Target template view engine 'journeyMatrix' not captured in current active layout scope. Executing emergency core fallback.");
                
                const wIni = (normalizedPatientObject.wifeName || 'W').charAt(0).toUpperCase();
                const hIni = (normalizedPatientObject.husbandName || 'H').charAt(0).toUpperCase();
                
                detailContainer.innerHTML = `
                    <div class="kyc-bar" style="background:var(--surface); border:1px solid var(--border-soft); border-radius:12px; padding:20px; display:flex; gap:20px; flex-wrap:wrap; align-items:center;">
                        <div class="kyc-avatar-pair" style="display:flex; position:relative;">
                            <div class="kyc-avatar wife-av" style="background:#fce7f3; color:#db2777; width:45px; height:45px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-weight:bold; border:2px solid #fff; box-shadow:0 2px 4px rgba(0,0,0,0.1);">${wIni}</div>
                            <div class="kyc-avatar husband-av" style="background:#dbeafe; color:#2563eb; width:45px; height:45px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-weight:bold; border:2px solid #fff; box-shadow:0 2px 4px rgba(0,0,0,0.1); margin-left:-15px;">${hIni}</div>
                        </div>
                        <div class="kyc-couple" style="flex:2; min-width:200px;">
                            <div class="kyc-row" style="font-size:15px; margin-bottom:4px;">👩 <b>Wife:</b> ${normalizedPatientObject.wifeName} (${normalizedPatientObject.wifeAge} Yrs)</div>
                            <div class="kyc-row" style="font-size:15px; margin-bottom:4px;">👨 <b>Husband:</b> ${normalizedPatientObject.husbandName} (${normalizedPatientObject.husbandAge} Yrs)</div>
                            <div style="font-size:13px; color:var(--text-soft); margin-top:6px;">📞 <b>Live Registry Contacts:</b> +91 ${normalizedPatientObject.phone}</div>
                        </div>
                        <div class="kyc-meta" style="flex:1; min-width:180px; font-size:13px; display:flex; flex-direction:column; gap:4px;">
                            <div><b>Patient ID:</b> <span style="color:#2563eb; font-weight:bold;">${normalizedPatientObject.id}</span></div>
                            <div><b>Billing Location:</b> ${normalizedPatientObject.centre}</div>
                            <div><b>Assigned Medical Care:</b> ${normalizedPatientObject.doctor}</div>
                        </div>
                    </div>
                `;
            }

            // Draw and render the custom financial grid invoice entries table under demographic blocks
            const proceduralListArray = [{
                on_date: (rData.on_date || '—').replace('T', ' '),
                category: rData.category || 'OPD Billing',
                procedure_name: rData.procedure_name || 'Procedural Record Line',
                code: rData.code || '—',
                fees: totalFees,
                payment_done: totalPaid,
                pending: totalPending
            }];

            appendCustomLedgerToJourney(proceduralListArray, totalFees, totalPaid, totalPending);
        })
        .catch(err => {
            console.error(err);
            detailContainer.innerHTML = `<div class="callout" style="border-left:4px solid var(--red); color:var(--red); padding:15px; margin-top:10px;"><b>Data Sync Failed:</b> ${err.message}</div>`;
        });
};

function appendCustomLedgerToJourney(procs, fees, paid, pending) {
    const detailContainer = document.getElementById('journeyDetail');
    if (!detailContainer) return;

    const ledgerHtml = `
        <div class="card" style="margin-top:20px; border:1px solid var(--border-soft); border-radius:8px; overflow:hidden;">
            <div class="card-head" style="background:#fafafa; padding:12px 20px; border-bottom:1px solid var(--border-soft); font-weight:600;"><div class="card-title">Live Patient Procedure Ledger (Joint Query Data)</div></div>
            <div class="card-body flush">
                <div class="table-wrap">
                    <table class="tbl" style="width:100%; border-collapse:collapse; font-size:13px;">
                        <thead>
                            <tr style="background:#f9fafb; border-bottom:1px solid var(--border-soft); text-align:left;">
                                <th style="padding:10px 15px;">Date</th><th style="padding:10px 15px;">Category</th><th style="padding:10px 15px;">Procedure Name</th><th style="padding:10px 15px;">Code</th><th class="col-num" style="padding:10px 15px; text-align:right;">Fees</th><th class="col-num" style="padding:10px 15px; text-align:right;">Payment Done</th><th class="col-num" style="padding:10px 15px; text-align:right;">Pending Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${procs.map(p => `
                                <tr style="border-bottom:1px solid var(--border-soft);">
                                    <td style="padding:10px 15px;">${p.on_date || '—'}</td>
                                    <td style="padding:10px 15px;"><span class="wf-tag" style="background:#e5e7eb; padding:2px 6px; border-radius:4px; font-size:11px; font-weight:bold;">${p.category}</span></td>
                                    <td style="padding:10px 15px;"><b>${p.procedure_name || '—'}</b></td>
                                    <td style="padding:10px 15px;"><code>${p.code || '—'}</code></td>
                                    <td class="col-num" style="padding:10px 15px; text-align:right;">₹${p.fees.toFixed(2)}</td>
                                    <td class="col-num" style="padding:10px 15px; text-align:right; color:var(--green)">₹${p.payment_done.toFixed(2)}</td>
                                    <td class="col-num" style="padding:10px 15px; text-align:right; color:${p.pending > 0 ? 'var(--red)' : 'var(--green)'}; font-weight:600;">₹${p.pending.toFixed(2)}</td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    `;
    detailContainer.insertAdjacentHTML('beforeend', ledgerHtml);
}
/* ============================================================
   CRITICAL CORE BOOT ROUTING — LEAVE THIS UNTOUCHED TO KEEP SIDEBAR ALIVE
   ============================================================ */
window.addEventListener('DOMContentLoaded', () => {
    const role = document.body.dataset.role;
    if (typeof buildSidebar === 'function') buildSidebar(role);
    
    const rendererMap = {
        doctor: window.renderDoctor,
        ch: window.renderCH,
        fc: window.renderFC,
        accounts: window.renderAccounts,
        mgmt: window.renderMgmt
    };
    if (rendererMap[role]) rendererMap[role]();
    
    if (typeof renderStageWidget === 'function') renderStageWidget();
    if (typeof renderJourney === 'function') renderJourney();
    if (typeof renderPrebook === 'function') renderPrebook();
    if (typeof initAging === 'function') initAging();
});

