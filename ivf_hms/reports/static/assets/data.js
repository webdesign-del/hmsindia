/* ============================================================
   INDIA IVF — HMS Patient Journey & Collections
   Sample dataset (demo data — not real patients)
   ============================================================ */

const TODAY = '2026-05-25';

/* ---- formatting helpers ---- */
function fmtINR(n){
  if(n===0||n==null) return '₹0';
  const s=Math.round(Math.abs(n)).toString();
  let last3=s.slice(-3), rest=s.slice(0,-3);
  if(rest) last3=','+last3;
  rest=rest.replace(/\B(?=(\d{2})+(?!\d))/g,',');
  return (n<0?'-₹':'₹')+rest+last3;
}
const MON=['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
function fmtDate(iso){ if(!iso) return '—'; const d=new Date(iso+'T00:00:00');
  return d.getDate()+' '+MON[d.getMonth()]+' '+d.getFullYear(); }
function addDays(iso,n){ const d=new Date(iso+'T00:00:00'); d.setDate(d.getDate()+n);
  return d.toISOString().slice(0,10); }
function daysBetween(a,b){ return Math.round((new Date(b)-new Date(a))/86400000); }

/* ---- milestone template ---- */
const OFFSET=[0,0,10,18,20,22,26,42];           // days from booking (T)
const STEP_SHORT=['Booking','LMP','Stimulation','Trigger','OPU','Embryology','Embryo Transfer','Beta HCG'];

/* ---- expanded 12-stage clinical-journey list ---- */
const STAGES_FULL=[
 {key:'First Consult',         day:'Pre-booking',     group:'pre'},
 {key:'CNB Visits',             day:'Pre-booking',     group:'pre'},
 {key:'Booked',                 day:'T (Day 0)',       group:'book'},
 {key:'Pre-Procedure',          day:'T+1 — T+9',       group:'clinical'},
 {key:'Ovarian Stimulation',    day:'T+10',            group:'clinical', donorChoice:true},
 {key:'Endometrial Preparation',day:'T+12 — T+18',     group:'clinical'},
 {key:'Trigger',                day:'T+18',            group:'clinical'},
 {key:'OPU',                    day:'T+20',            group:'clinical'},
 {key:'Progesterone Change',    day:'T+22 — T+24',     group:'lab'},
 {key:'Embryo Transfer',        day:'T+25 — T+28',     group:'clinical'},
 {key:'B-HCG',                  day:'T+42',            group:'clinical'},
 {key:'Cardiac Activity',       day:'T+56',            group:'clinical'}
];
/* old patient.stage (0..7) → new STAGES_FULL index */
const OLD_TO_NEW_STAGE=[2,3,4,6,7,8,9,10];

/* patient KYC (couple names + ages) — deterministic per IIC ID */
const HUSBAND_FIRST_NAMES=['Rohit','Vikram','Anand','Naveen','Sanjay','Manish','Rakesh','Vivek','Aditya','Suresh','Ravi','Pankaj','Kunal','Mohit','Rahul','Arjun','Karan','Nikhil','Saurabh','Tarun','Deepak','Amit','Sandeep','Pradeep'];
const HUSBAND_OVERRIDES={
 'IIC-2602-070':{first:'Imran'}, /* Sana Mir — Kashmiri */
 'IIC-2604-260':{first:'Rakesh'},
 'IIC-2605-220':{first:'Aman'},
 'IIC-2605-205':{first:'Vinay'}
};
function patientKYC(p){
  const id=p.id||'';
  let seed=0; for(let i=0;i<id.length;i++) seed+=id.charCodeAt(i); seed=seed%10000;
  const wifeName=p.name||'';
  const parts=wifeName.split(' ');
  const lastName=parts[parts.length-1]||'';
  const ov=HUSBAND_OVERRIDES[id];
  const husbandFirst=ov?ov.first:HUSBAND_FIRST_NAMES[seed%HUSBAND_FIRST_NAMES.length];
  const husbandName=husbandFirst+(lastName?' '+lastName:'');
  const wifeAge=28+(seed%14);                        /* 28..41 */
  const husbandAge=wifeAge+2+((seed>>4)%5);           /* +2..+6 */
  return {wifeName,wifeAge,husbandName,husbandAge};
}
function patientPhone(p){
  const id=p.id||'';
  let s=0; for(let i=0;i<id.length;i++) s+=id.charCodeAt(i);
  const prefix=['98','97','99','91','87','88','89','94','96'][s%9];
  const rest=String((s*123457+987654321)%100000000).padStart(8,'0');
  return prefix+rest;
}
function fmtPhone(n){const s=String(n);return '+91 '+s.slice(0,5)+' '+s.slice(5);}
function initialsOf(name){
  return (name||'').split(' ').map(p=>p[0]||'').join('').slice(0,2).toUpperCase()||'?';
}
function patientNewStage(p){
  if(p.type==='scheduled'||p.type==='missed') return 0;
  if(p.type==='cnb') return 1;
  if(typeof p.stage==='number') return OLD_TO_NEW_STAGE[p.stage]!=null?OLD_TO_NEW_STAGE[p.stage]:2;
  return 2;
}
function isPrebookPatient(p){ return p.type==='scheduled'||p.type==='missed'||p.type==='cnb'; }
/* date offset (days from booking T) per new stage idx */
const NEW_STAGE_OFFSET={2:0,3:5,4:10,5:14,6:18,7:20,8:22,9:26,10:42,11:56};
const MS_PAY_PCT=[10,0,40,50,0,0,0,0];           // milestone payment, % of net
const CUM_REQ=[10,10,50,100,100,100,100,100];    // cumulative % cleared to be on-track

const MS=[
 {key:'Booking',day:'T · Day 0',pay:'10% of package',gType:'data',gName:'Hard Data Gate',
  gate:'No 10% receipt can be generated until the doctor enters expected dates for all 7 milestones.',
  log:'Expected dates for the next 6 milestones',
  fields:[
   {l:'Expected withdrawal / LMP date',t:'date',req:true,src:'Doctor',use:'Achievement · Slack triggers'},
   {l:'Expected stimulation start date',t:'date',req:true,src:'Doctor',use:'Achievement · projections'},
   {l:'Expected trigger date',t:'date',req:true,src:'Doctor',use:'Achievement · projections'},
   {l:'Expected OPU date',t:'date',req:true,src:'Doctor',use:'Achievement · projections'},
   {l:'Expected ET date',t:'date',req:true,src:'Doctor',use:'Achievement · projections'},
   {l:'Expected beta HCG date',t:'date',req:true,src:'Doctor',use:'Achievement'},
   {l:'Package code(s) selected',t:'multiselect',req:true,src:'FC / Doctor',use:'Wallet · milestone %'},
   {l:'Total package amount',t:'currency',req:true,src:'System',use:'Wallet · projections'},
   {l:'Discount amount',t:'currency',req:true,src:'FC',use:'Wallet'},
   {l:'Net amount after discount',t:'auto',req:'auto',src:'System',use:'Wallet · milestones'}
 ]},
 {key:'LMP / Withdrawal',day:'T',pay:'Nil',gType:'data',gName:'Hard Data Gate',
  gate:'Cannot proceed to the Stimulation tab until the LMP date is logged and the stim date recalculated.',
  log:'LMP date · recalculate stim date if needed',
  fields:[
   {l:'Actual LMP date',t:'date',req:true,src:'Doctor',use:'Stim date recalc'},
   {l:'Cycle decision',t:'select',opts:['Proceed','Skip','Cancel'],req:true,src:'Doctor',use:'Workflow routing'},
   {l:'Revised expected stim date',t:'date',req:'If rebased',src:'Auto / Doctor',use:'Achievement'}
 ]},
 {key:'Stimulation',day:'T + 10',pay:'40% of package',gType:'pay',gName:'Hard Payment Gate + Approval',
  gate:'No stim start until FC confirms 40% payment. The OI form auto-closes at day 12 — director approval is required for a continuation form.',
  log:'OI form · injection schedule · scan plan',
  fields:[
   {l:'Actual stim start date',t:'date',req:true,src:'Doctor',use:'Achievement · date rebase'},
   {l:'Total injections prescribed',t:'number',req:true,src:'Doctor',use:'Pro-rata computation'},
   {l:'Injection log entry (per day)',t:'repeat',ph:'Date + dose',unit:'injection',req:'Per injection',src:'Doctor',use:'Pro-rata · wallet debit'},
   {l:'TVS monitoring scan',t:'repeat',ph:'Date + findings',unit:'scan',req:'Per scan',src:'Doctor',use:'Audit'},
   {l:'Consult during stim',t:'repeat',ph:'Date + notes',unit:'consult',req:'Per consult',src:'Doctor',use:'Audit'},
   {l:'Mid-stim outcome',t:'select',opts:['Continuing','Cancelled — no egg','Cancelled — patient','Package change'],req:'If applicable',src:'Doctor',use:'Reconciliation trigger'},
   {l:'Day-12 status',t:'auto',req:'System flag',src:'System',use:'Director approval trigger'}
 ]},
 {key:'Trigger',day:'T + 18',pay:'Full balance · 10% CH flexibility',gType:'pay',gName:'Hard Payment Gate',
  gate:'Trigger cannot be marked done until the full balance is cleared. Centre Head may invoke 10% flexibility.',
  log:'Trigger time · dose · medication',
  fields:[
   {l:'Trigger date',t:'date',req:true,src:'Doctor',use:'Achievement'},
   {l:'Trigger time',t:'time',req:true,src:'Doctor',use:'OPU scheduling'},
   {l:'Medication and dose',t:'text',req:true,src:'Doctor',use:'Audit'},
   {l:'Patient confirmation of OPU readiness',t:'yesno',req:true,src:'Doctor',use:'OPU gate'}
 ]},
 {key:'OPU',day:'T + 20',pay:'Full balance must clear',gType:'pay',gName:'Hard Payment Gate + Override',
  gate:'OPU theatre booking is blocked unless 100% is cleared. Director override only.',
  log:'Eggs retrieved · fertilisation outcome · embryologist log',
  fields:[
   {l:'Actual OPU date and time',t:'datetime',req:true,src:'Doctor',use:'Achievement'},
   {l:'Number of eggs retrieved',t:'number',req:true,src:'Doctor',use:'Outcome routing'},
   {l:'Anesthesia notes',t:'textarea',req:true,src:'Doctor',use:'Clinical record'},
   {l:'Embryologist preliminary report',t:'textarea',req:true,src:'Embryologist',use:'Clinical record'},
   {l:'OPU outcome flag',t:'select',opts:['Eggs retrieved','No eggs','Patient withdrew','Other'],req:true,src:'Doctor',use:'Workflow routing'}
 ]},
 {key:'Embryology',day:'Lab · sub-step',pay:'—',gType:'data',gName:'Hard Data Gate',
  gate:'Embryology outcome must be logged — it routes embryo-transfer planning and billing.',
  log:'Fertilisation · grades · freeze plan',
  fields:[
   {l:'Number fertilised',t:'number',req:true,src:'Embryologist',use:'Outcome routing'},
   {l:'Embryo grades',t:'text',req:true,src:'Embryologist',use:'ET planning'},
   {l:'ICSI performed',t:'yesno',req:true,src:'Embryologist',use:'Billing line'},
   {l:'Blastocyst day',t:'select',opts:['Day 3','Day 5'],req:true,src:'Embryologist',use:'ET timing'},
   {l:'Freeze plan',t:'text',req:true,src:'Embryologist',use:'Wallet'},
   {l:'Embryology outcome',t:'select',opts:['Viable','Arrest','No fertilisation'],req:true,src:'Embryologist',use:'Workflow routing'}
 ]},
 {key:'Embryo Transfer',day:'T + 25 to T + 28',pay:'Nil · already paid',gType:'pay',gName:'Hard Payment Gate + Override',
  gate:'ET slot cannot be released unless 100% is cleared. Director override only.',
  log:'Embryo grade · transfer details',
  fields:[
   {l:'Actual ET date',t:'date',req:true,src:'Doctor',use:'Achievement'},
   {l:'Number of embryos transferred',t:'number',req:true,src:'Doctor',use:'Clinical record'},
   {l:'Embryo grade transferred',t:'text',req:true,src:'Doctor',use:'Clinical record'},
   {l:'Fresh or frozen transfer',t:'select',opts:['Fresh','FET'],req:true,src:'Doctor',use:'Billing line'}
 ]},
 {key:'Beta HCG / Outcome',day:'T + 42',pay:'Nil',gType:'data',gName:'Hard Data Gate',
  gate:'The patient record cannot close until the beta HCG outcome and next-step recommendation are logged.',
  log:'Test result · next-step recommendation · FET routing',
  fields:[
   {l:'Beta HCG value',t:'number',req:true,src:'Doctor',use:'Outcome'},
   {l:'Date of test',t:'date',req:true,src:'Doctor',use:'Achievement'},
   {l:'Number of gestational sacs',t:'number',req:'If applicable',src:'Doctor',use:'Clinical record'},
   {l:'Next-step recommendation',t:'select',opts:['Cycle closed','FET plan','Failed-cycle rebook','Change of management'],req:true,src:'Doctor',use:'Workflow routing · cycle close'}
 ]}
];

/* ---- patients (16 demo records across 6 centres) ---- */
const PATIENTS=[
 {id:'IIC-2603-118',name:'Anjali Mehra',  centre:'Noida',       doctor:'Dr. A. Verma', fc:'P. Rao',  pkg:'IP222',            net:260000,discount:25000,signup:'2026-05-05',stage:4,flag:'green',gate:'open',paid:100},
 {id:'IIC-2603-126',name:'Priya Sharma',  centre:'Noida',       doctor:'Dr. A. Verma', fc:'P. Rao',  pkg:'IP11',             net:195000,discount:18000,signup:'2026-05-15',stage:2,flag:'amber',gate:'pay', paid:10},
 {id:'IIC-2604-203',name:'Kavita Reddy',  centre:'Noida',       doctor:'Dr. A. Verma', fc:'P. Rao',  pkg:'IP222 + Composite',net:310000,discount:30000,signup:'2026-05-07',stage:3,flag:'red',  gate:'pay', paid:50},
 {id:'IIC-2604-211',name:'Sunita Nair',   centre:'Noida',       doctor:'Dr. A. Verma', fc:'J. Saini',pkg:'IP11',             net:180000,discount:15000,signup:'2026-05-24',stage:1,flag:'green',gate:'open',paid:10},
 {id:'IIC-2605-088',name:'Meena Gupta w',   centre:'Noida',       doctor:'Dr. A. Verma', fc:'P. Rao',  pkg:'IP222',            net:275000,discount:22000,signup:'2026-05-25',stage:0,flag:'amber',gate:'data',paid:0},
 {id:'IIC-2602-051',name:'Ritu Verma',    centre:'Noida',       doctor:'Dr. A. Verma', fc:'J. Saini',pkg:'Donor',            net:390000,discount:0,    signup:'2026-04-29',stage:6,flag:'green',gate:'open',paid:100},
 {id:'IIC-2601-019',name:'Pooja Singh',   centre:'Noida',       doctor:'Dr. A. Verma', fc:'P. Rao',  pkg:'IP11',             net:185000,discount:16000,signup:'2026-04-13',stage:7,flag:'green',gate:'open',paid:100},
 {id:'IIC-2603-140',name:'Neha Kapoor',   centre:'Vasant Vihar',doctor:'Dr. K. Bhatia',fc:'A. Dutta',pkg:'IP222',            net:240000,discount:20000,signup:'2026-05-03',stage:5,flag:'green',gate:'open',paid:100},
 {id:'IIC-2604-225',name:'Shalini Roy',   centre:'Vasant Vihar',doctor:'Dr. K. Bhatia',fc:'A. Dutta',pkg:'IP11',             net:225000,discount:18000,signup:'2026-05-02',stage:2,flag:'red',  gate:'pay', paid:10},
 {id:'IIC-2605-101',name:'Divya Menon',   centre:'Vasant Vihar',doctor:'Dr. K. Bhatia',fc:'A. Dutta',pkg:'IP222',            net:290000,discount:24000,signup:'2026-05-24',stage:0,flag:'green',gate:'data',paid:0},
 {id:'IIC-2603-160',name:'Aarti Joshi',   centre:'Rohini',      doctor:'Dr. N. Sethi', fc:'S. Bose', pkg:'IP222',            net:255000,discount:21000,signup:'2026-05-05',stage:4,flag:'amber',gate:'pay', paid:92},
 {id:'IIC-2604-240',name:'Rekha Das',     centre:'Rohini',      doctor:'Dr. N. Sethi', fc:'S. Bose', pkg:'IP11',             net:270000,discount:22000,signup:'2026-05-04',stage:3,flag:'red',  gate:'pay', paid:50},
 {id:'IIC-2603-175',name:'Swati Malhotra',centre:'Gurgaon',     doctor:'Dr. R. Pillai',fc:'V. Khanna',pkg:'IP222',           net:235000,discount:19000,signup:'2026-04-29',stage:6,flag:'green',gate:'open',paid:100},
 {id:'IIC-2605-115',name:'Nidhi Agarwal', centre:'Gurgaon',     doctor:'Dr. R. Pillai',fc:'V. Khanna',pkg:'IP11',            net:200000,discount:16000,signup:'2026-05-23',stage:1,flag:'green',gate:'open',paid:10},
 {id:'IIC-2604-260',name:'Geeta Yadav',   centre:'Ghaziabad',   doctor:'Dr. M. Chawla',fc:'K. Menon',pkg:'IP222',            net:215000,discount:17000,signup:'2026-05-14',stage:2,flag:'amber',gate:'open',paid:50},
 {id:'IIC-2602-070',name:'Sana Mir',      centre:'Srinagar',    doctor:'Dr. F. Wani',  fc:'R. Bhat', pkg:'IP11',             net:250000,discount:20000,signup:'2026-05-02',stage:5,flag:'green',gate:'open',paid:100}
];

/* ---- per-patient derived helpers ---- */
function pExpDate(p,m){ return addDays(p.signup,OFFSET[m]); }
function pVar(p,m){ return ((p.id.charCodeAt(9)+p.id.charCodeAt(10)+m*3)%7)-3; } // -3..+3 deterministic
function pActDate(p,m){ return addDays(pExpDate(p,m),pVar(p,m)); }
function pCollected(p){ return Math.round(p.net*p.paid/100); }
function pRequiredNow(p){ return Math.round(p.net*CUM_REQ[p.stage]/100); }
function pDue(p){ return Math.max(0,pRequiredNow(p)-pCollected(p)); }
function pNextDate(p){ return p.stage>=7? null : pExpDate(p,p.stage+1); }
function flagPill(f){ const m={green:['pill-green','On track'],amber:['pill-amber','Within window'],red:['pill-red','Overdue']};
  const x=m[f]; return '<span class="pill '+x[0]+'"><span class="dot"></span>'+x[1]+'</span>'; }
function gateBadge(g){ const m={open:['gate-open','✓ Open'],pay:['gate-pay','🔒 Payment'],data:['gate-data','🔒 Data']};
  const x=m[g]; return '<span class="gate '+x[0]+'">'+x[1]+'</span>'; }

/* ---- refund queue (reconciliation-triggered) ---- */
const REFUNDS=[
 {id:'IIC-2604-203',name:'Kavita Reddy', centre:'Noida',       fc:'P. Rao',  trigger:'Package change — IP222 → Composite', amount:38000,mode:'Insurance',verified:'Yes',    status:'Pending execution'},
 {id:'IIC-2603-126',name:'Priya Sharma', centre:'Noida',       fc:'P. Rao',  trigger:'Stimulation cancelled — patient stopped',amount:46800,mode:'Cash',     verified:'Pending',status:'Awaiting FC mode'},
 {id:'IIC-2604-225',name:'Shalini Roy',  centre:'Vasant Vihar',fc:'A. Dutta',trigger:'Stimulation cancelled — no eggs',    amount:45000,mode:'Insurance',verified:'Yes',    status:'Pending execution'},
 {id:'IIC-2604-240',name:'Rekha Das',    centre:'Rohini',      fc:'S. Bose', trigger:'Outcome failed — change of management',amount:62000,mode:'Cash',     verified:'Yes',    status:'Pending execution'},
 {id:'IIC-2604-260',name:'Geeta Yadav',  centre:'Ghaziabad',   fc:'K. Menon',trigger:'Package changed mid-treatment',      amount:28500,mode:'Insurance',verified:'Yes',    status:'Pending execution'},
 {id:'IIC-2602-051',name:'Ritu Verma',   centre:'Noida',       fc:'J. Saini',trigger:'Donor cycle — residual code-bundle', amount:19500,mode:'Cash',     verified:'Yes',    status:'Executed'}
];

/* ---- red triggers (HMS exception pile-up) ---- */
const TRIGGERS=[
 {type:'Missed collection',     id:'IIC-2604-203',name:'Kavita Reddy',  centre:'Noida',       stage:'Trigger',    value:155000,days:6},
 {type:'Missed collection',     id:'IIC-2604-240',name:'Rekha Das',     centre:'Rohini',      stage:'Trigger',    value:135000,days:9},
 {type:'OPU miss',              id:'IIC-2603-160',name:'Aarti Joshi',   centre:'Rohini',      stage:'OPU',        value:20400, days:3},
 {type:'Gate override',         id:'IIC-2604-225',name:'Shalini Roy',   centre:'Vasant Vihar',stage:'OPU',        value:0,     days:2},
 {type:'Reconciliation pending',id:'IIC-2604-260',name:'Geeta Yadav',   centre:'Ghaziabad',   stage:'Stimulation',value:28500, days:4},
 {type:'Missed collection',     id:'IIC-2603-126',name:'Priya Sharma',  centre:'Noida',       stage:'Stimulation',value:78000, days:5},
 {type:'Stim 12-day cap',       id:'IIC-2604-225',name:'Shalini Roy',   centre:'Vasant Vihar',stage:'Stimulation',value:0,     days:1}
];
const STAGE_WT={'OPU':3,'OPU miss':3,'Trigger':2.4,'Embryo Transfer':2.2,'Stimulation':1.6,'Booking':1};
function triggerScore(t){ const base=(t.value||60000); return Math.round(t.days*base*(STAGE_WT[t.stage]||1.5)/1000); }

/* ---- director approval queue ---- */
const APPROVALS=[
 {type:'OPU payment override',     id:'IIC-2603-160',name:'Aarti Joshi', centre:'Rohini',      detail:'OPU theatre needed — 92% cleared, ₹20,400 balance pending.',req:'2026-05-25'},
 {type:'12-day stim extension',    id:'IIC-2604-225',name:'Shalini Roy', centre:'Vasant Vihar',detail:'OI form hit the day-12 cap — continuation form requested.',req:'2026-05-24'},
 {type:'Large refund approval',    id:'IIC-2604-240',name:'Rekha Das',   centre:'Rohini',      detail:'Refund ₹62,000 — above the ₹50,000 director threshold.',req:'2026-05-24'},
 {type:'Special discount escalation',id:'IIC-2605-101',name:'Divya Menon',centre:'Vasant Vihar',detail:'Discount ₹58,000 exceeds the ₹40,000 counsellor limit.',req:'2026-05-22'}
];

/* ---- override & exception log ---- */
const OVERRIDES=[
 {ts:'2026-05-24 16:20',type:'OPU / ET payment override',id:'IIC-2603-175',name:'Swati Malhotra',centre:'Gurgaon',   by:'Dr. Somendra',reason:'ET slot released — 96% cleared, balance scheduled next day'},
 {ts:'2026-05-23 11:05',type:'12-day stim extension',    id:'IIC-2604-260',name:'Geeta Yadav',   centre:'Ghaziabad', by:'Dr. Somendra',reason:'Slow responder — 2-day extension clinically justified'},
 {ts:'2026-05-22 09:40',type:'Large refund approval',    id:'IIC-2602-051',name:'Ritu Verma',    centre:'Noida',     by:'Dr. Richika', reason:'Donor residual ₹19,500 bundled into combined code — approved'},
 {ts:'2026-05-21 18:12',type:'Discount escalation',      id:'IIC-2605-088',name:'Meena Gupta',   centre:'Noida',     by:'Founder',     reason:'Festive package discount — approved within policy'}
];

/* ---- day-before Slack / email reminders (for 26 May) ---- */
const SLACK=[
 {id:'IIC-2603-126',name:'Priya Sharma',  centre:'Noida',  step:'Stimulation start — 40% payment due', amount:78000, ms:'Awaiting 40%'},
 {id:'IIC-2604-203',name:'Kavita Reddy',  centre:'Noida',  step:'Trigger — full balance due',          amount:155000,ms:'Balance pending'},
 {id:'IIC-2603-118',name:'Anjali Mehra',  centre:'Noida',  step:'OPU — confirm 100% cleared',          amount:0,     ms:'Cleared'},
 {id:'IIC-2603-160',name:'Aarti Joshi',   centre:'Rohini', step:'OPU — theatre booking',               amount:20400, ms:'92% cleared'},
 {id:'IIC-2605-115',name:'Nidhi Agarwal', centre:'Gurgaon',step:'Stimulation start — 40% payment due', amount:80000, ms:'Awaiting 40%'}
];

/* ---- centre-level collection & performance ---- */
const CENTRE_STATS=[
 {centre:'Vasant Vihar',exp:268000,act:268000,due:4,aging:392000,red:2,adh:100,ontrack:82},
 {centre:'Noida',       exp:312000,act:234000,due:5,aging:486000,red:2,adh:75, ontrack:71},
 {centre:'Rohini',      exp:195000,act:122000,due:3,aging:540000,red:2,adh:63, ontrack:64},
 {centre:'Gurgaon',     exp:224000,act:210000,due:3,aging:268000,red:0,adh:94, ontrack:88},
 {centre:'Ghaziabad',   exp:148000,act:120000,due:2,aging:175000,red:1,adh:81, ontrack:76},
 {centre:'Srinagar',    exp:96000, act:96000, due:1,aging:84000, red:0,adh:100,ontrack:90}
];
/* ---- aging buckets per centre [0-30,31-60,61-90,91-180,180+] ---- */
const AGING={
 'Noida':       [180000,126000,90000,52000,38000],
 'Vasant Vihar':[150000,98000, 74000,40000,30000],
 'Rohini':      [140000,150000,120000,80000,50000],
 'Gurgaon':     [120000,78000, 40000,20000,10000],
 'Ghaziabad':   [70000, 45000, 32000,18000,10000],
 'Srinagar':    [44000, 22000, 12000,6000, 0]
};
/* ---- collection by procedure step (all centres, today) ---- */
const STEP_COLLECT=[
 {step:'Booking · 10%',  exp:124000,act:118000},
 {step:'Stimulation · 40%',exp:386000,act:262000},
 {step:'Trigger · balance',exp:455000,act:380000},
 {step:'OPU · clearance',exp:178000,act:172000},
 {step:'ET',             exp:64000, act:64000},
 {step:'Beta HCG',       exp:36000, act:36000}
];
/* ---- weekly collection trend (for sparkline) ---- */
const WEEK_TREND=[920000,1040000,880000,1180000,1010000,1243000,1050000];

/* ---- prebook funnel (pre 10% booking) ---- */
const PREBOOK=[
 // appointments scheduled (today and upcoming)
 {id:'IIC-2605-220',name:'Reema Bansal',  date:'2026-05-25',centre:'Noida',       type:'scheduled'},
 {id:'IIC-2605-221',name:'Tina Verma',    date:'2026-05-25',centre:'Noida',       type:'scheduled'},
 {id:'IIC-2605-222',name:'Pallavi Singh', date:'2026-05-25',centre:'Vasant Vihar',type:'scheduled'},
 {id:'IIC-2605-223',name:'Roshni Saxena', date:'2026-05-25',centre:'Gurgaon',     type:'scheduled'},
 {id:'IIC-2605-225',name:'Lakshmi Iyer',  date:'2026-05-26',centre:'Noida',       type:'scheduled'},
 {id:'IIC-2605-228',name:'Anita Pathak',  date:'2026-05-27',centre:'Rohini',      type:'scheduled'},
 {id:'IIC-2605-232',name:'Surbhi Joshi',  date:'2026-05-28',centre:'Gurgaon',     type:'scheduled'},
 {id:'IIC-2605-235',name:'Charu Bali',    date:'2026-05-30',centre:'Noida',       type:'scheduled'},
 {id:'IIC-2605-240',name:'Heena Singhal', date:'2026-06-02',centre:'Vasant Vihar',type:'scheduled'},
 // missed appointments
 {id:'IIC-2605-200',name:'Veena Sahay',   date:'2026-05-25',centre:'Noida',       type:'missed'},
 {id:'IIC-2605-180',name:'Smita Kapoor',  date:'2026-05-22',centre:'Noida',       type:'missed'},
 {id:'IIC-2605-178',name:'Ritika Khanna', date:'2026-05-21',centre:'Vasant Vihar',type:'missed'},
 {id:'IIC-2605-175',name:'Mansi Tandon',  date:'2026-05-23',centre:'Noida',       type:'missed'},
 {id:'IIC-2605-165',name:'Pooja Saxena',  date:'2026-05-18',centre:'Ghaziabad',   type:'missed'},
 // consulted, not booked (CNB)
 {id:'IIC-2605-205',name:'Asha Tiwari t',   date:'2026-05-25',centre:'Noida',       type:'cnb',doctor:'Dr. A. Verma', treatment:'IVF Self-cycle (IP222)',          quality:'Hot', fcComment:'Couple keen — finalising package after weekend.',         lastConn:'2026-05-27',lastComment:'Sent IP222 brochure on WhatsApp; will revert Mon.'},
 {id:'IIC-2605-188',name:'Sunita Chopra', date:'2026-05-24',centre:'Noida',       type:'cnb',doctor:'Dr. A. Verma', treatment:'IUI x3 then IVF if needed',       quality:'Hot', fcComment:'Husband travelling — booking deferred to next week.',     lastConn:'2026-05-28',lastComment:'Follow-up call Mon; mentioned insurance query.'},
 {id:'IIC-2605-185',name:'Aditi Sharma',  date:'2026-05-23',centre:'Gurgaon',     type:'cnb',doctor:'Dr. R. Pillai',treatment:'IVF Self-cycle (IP11)',           quality:'Cold',fcComment:'Comparing with another clinic - price sensitive.',        lastConn:'2026-05-26',lastComment:'Asked for EMI options; brochure resent.'},
 {id:'IIC-2605-170',name:'Kiran Mathur',  date:'2026-05-20',centre:'Rohini',      type:'cnb',doctor:'Dr. N. Sethi', treatment:'IVF + Composite Add-on (IP222+C)',quality:'Hot', fcComment:"Waiting for husband's semen analysis report.",            lastConn:'2026-05-27',lastComment:'SA done at local lab; awaiting upload.'},
 {id:'IIC-2605-160',name:'Bhavna Dixit',  date:'2026-05-17',centre:'Noida',       type:'cnb',doctor:'Dr. A. Verma', treatment:'Donor egg IVF',                   quality:'Cold',fcComment:'Family discussion ongoing on donor cycle.',                lastConn:'2026-05-25',lastComment:'Counselled on donor protocols; will decide in 2 wks.'},
 {id:'IIC-2605-156',name:'Manisha Rao',   date:'2026-05-16',centre:'Vasant Vihar',type:'cnb',doctor:'Dr. K. Bhatia',treatment:'IUI cycle',                       quality:'Cold',fcComment:'Wants 2nd opinion before committing.',                     lastConn:'2026-05-24',lastComment:'Visiting another consultant 28-May.'},
 {id:'IIC-2605-150',name:'Sneha Kulkarni',date:'2026-05-14',centre:'Noida',       type:'cnb',doctor:'Dr. A. Verma', treatment:'IVF Self-cycle (IP11)',           quality:'Dead',fcComment:'No response after 5 follow-ups - treat as dead lead.',     lastConn:'2026-05-20',lastComment:'Phone unreachable; WhatsApp not delivered.'}
];

/* ---- aging-patients detailed dataset ---- */
const FY_MONTHS=[
 '2025-04','2025-05','2025-06','2025-07','2025-08','2025-09',
 '2025-10','2025-11','2025-12','2026-01','2026-02','2026-03',
 '2026-04','2026-05','2026-06','2026-07','2026-08','2026-09',
 '2026-10','2026-11','2026-12','2027-01','2027-02','2027-03'
];
const FY_LABELS=["Apr'25","May'25","Jun'25","Jul'25","Aug'25","Sep'25","Oct'25","Nov'25","Dec'25","Jan'26","Feb'26","Mar'26","Apr'26","May'26","Jun'26","Jul'26","Aug'26","Sep'26","Oct'26","Nov'26","Dec'26","Jan'27","Feb'27","Mar'27"];
function bucketIdx(d){if(d<=30)return 0;if(d<=60)return 1;if(d<=90)return 2;if(d<=180)return 3;return 4;}
function paymentInMonth(p,ym){const f=p.payments.find(x=>x.m===ym);return f?f.a:0;}
function paymentsTotal(p){return p.payments.reduce((s,x)=>s+x.a,0);}
function patientGstSplit(p){const ex=Math.round(p.incGst/1.18);return [ex,p.incGst-ex];}
function pkgMonthLabel(sig){const i=FY_MONTHS.indexOf(sig.slice(0,7));return i>=0?FY_LABELS[i]:sig.slice(0,7);}
function pkgFY(sig){const y=+sig.slice(0,4),m=+sig.slice(5,7);const s=m>=4?y:y-1;return 'FY '+s+'-'+String(s+1).slice(2);}
function pkgDisc(p){return Math.round(p.gross*p.discPct/100);}

const AGING_PATIENTS=[
 {id:'IIC-2604-203',name:'Kavita Reddy',  centre:'Noida',       fc:'P. Rao',   pkg:'IP222+CMP',desc:'IVF Self-cycle + Composite Add-on',signup:'2026-05-07',gross:340000,discPct:8.8, incGst:310000,milestone:'Trigger',     daysOverdue:6,  lastFu:'2026-05-22',referred:'2026-05-20',status:'Active · Trigger balance due',         invoice:'IIC/N/26-27/0203',history:'Booked 07-May · 40% paid 14-May · Trigger balance 6d overdue · 2 follow-ups',         payments:[{m:'2026-05',a:155000}]},
 {id:'IIC-2603-126',name:'Priya Sharma',  centre:'Noida',       fc:'P. Rao',   pkg:'IP11',     desc:'IVF Self-cycle Standard',           signup:'2026-05-15',gross:215000,discPct:9.3, incGst:195000,milestone:'Stimulation', daysOverdue:5,  lastFu:'2026-05-22',referred:'2026-05-21',status:'Active · Stim 40% pending',            invoice:'IIC/N/26-27/0126',history:'Booked 15-May · 10% paid · Stim 40% 5d overdue · Tele follow-up 22-May',             payments:[{m:'2026-05',a:19500}]},
 {id:'IIC-2603-160',name:'Aarti Joshi',   centre:'Rohini',      fc:'S. Bose',  pkg:'IP222',    desc:'IVF Self-cycle Premium',            signup:'2026-05-05',gross:280000,discPct:8.9, incGst:255000,milestone:'OPU',         daysOverdue:3,  lastFu:'2026-05-23',referred:'2026-05-22',status:'OPU clearance 8% short',               invoice:'IIC/R/26-27/0160',history:'92% cleared · ₹20,400 short of OPU · awaiting director override',                    payments:[{m:'2026-05',a:234600}]},
 {id:'IIC-2604-240',name:'Rekha Das',     centre:'Rohini',      fc:'S. Bose',  pkg:'IP11',     desc:'IVF Self-cycle Standard',           signup:'2026-05-04',gross:295000,discPct:8.5, incGst:270000,milestone:'Trigger',     daysOverdue:9,  lastFu:'2026-05-22',referred:'2026-05-20',status:'Trigger balance overdue',              invoice:'IIC/R/26-27/0240',history:'50% paid 14-May · Trigger balance 9d overdue · patient cited cashflow',              payments:[{m:'2026-05',a:135000}]},
 {id:'IIC-2604-225',name:'Shalini Roy',   centre:'Vasant Vihar',fc:'A. Dutta', pkg:'IP11',     desc:'IVF Self-cycle Standard',           signup:'2026-05-02',gross:245000,discPct:8.2, incGst:225000,milestone:'Stim cancelled',daysOverdue:12,lastFu:'2026-05-21',referred:'2026-05-19',status:'Stim cancelled · refund pending',      invoice:'IIC/V/26-27/0225',history:'No eggs · pro-rata refund ₹45,000 computed · awaiting mode confirmation',             payments:[{m:'2026-05',a:22500}]},
 {id:'IIC-2604-260',name:'Geeta Yadav',   centre:'Ghaziabad',   fc:'K. Menon', pkg:'IP222',    desc:'IVF Self-cycle Premium',            signup:'2026-05-14',gross:235000,discPct:8.5, incGst:215000,milestone:'Stim · pkg change',daysOverdue:4,lastFu:'2026-05-22',referred:'2026-05-20',status:'Package change · reconciliation open',  invoice:'IIC/G/26-27/0260',history:'Package change mid-stim · new code pending · ₹28.5k residual',                       payments:[{m:'2026-05',a:107500}]},
 {id:'IIC-2502-180',name:'Renu Singh',    centre:'Noida',       fc:'P. Rao',   pkg:'IP222',    desc:'IVF Self-cycle Premium',            signup:'2026-02-10',gross:290000,discPct:6.9, incGst:270000,milestone:'Stimulation', daysOverdue:45, lastFu:'2026-04-15',referred:'2026-03-25',status:'Stim 40% pending · cycle on hold',     invoice:'IIC/N/25-26/0180',history:'Booked Feb · only 10% paid · cycle paused 45d · ₹85k pending',                       payments:[{m:'2026-02',a:27000}]},
 {id:'IIC-2603-140',name:'Neha Kapoor',   centre:'Vasant Vihar',fc:'A. Dutta', pkg:'IP222',    desc:'IVF Self-cycle Premium',            signup:'2026-03-15',gross:265000,discPct:9.4, incGst:240000,milestone:'Trigger',     daysOverdue:38, lastFu:'2026-04-19',referred:'2026-04-10',status:'Trigger balance pending',              invoice:'IIC/V/25-26/0140',history:'50% paid Apr · Trigger balance 38d · 4 follow-ups · open',                            payments:[{m:'2026-03',a:24000},{m:'2026-04',a:96000}]},
 {id:'IIC-2502-145',name:'Ranjana Sahay', centre:'Rohini',      fc:'S. Bose',  pkg:'IP222+CMP',desc:'IVF + Composite Add-on',            signup:'2026-02-18',gross:325000,discPct:9.2, incGst:295000,milestone:'Trigger',     daysOverdue:60, lastFu:'2026-03-27',referred:'2026-03-15',status:'Trigger balance 60d overdue',          invoice:'IIC/R/25-26/0145',history:'Booked Feb · 50% paid Mar · Trigger 60d overdue · escalated',                        payments:[{m:'2026-02',a:29500},{m:'2026-03',a:118000}]},
 {id:'IIC-2502-205',name:'Komal Aggarwal',centre:'Noida',       fc:'J. Saini', pkg:'IP222',    desc:'IVF Self-cycle Premium',            signup:'2026-02-22',gross:295000,discPct:8.5, incGst:270000,milestone:'Trigger',     daysOverdue:78, lastFu:'2026-03-08',referred:'2026-03-01',status:'Trigger pending · likely withdrawal',  invoice:'IIC/N/25-26/0205',history:'50% paid · Trigger 78d overdue · patient stopped responding',                       payments:[{m:'2026-02',a:27000},{m:'2026-03',a:108000}]},
 {id:'IIC-2502-095',name:'Anjali Khanna', centre:'Gurgaon',     fc:'V. Khanna',pkg:'IP11',     desc:'IVF Self-cycle Standard',           signup:'2026-02-26',gross:225000,discPct:8.9, incGst:205000,milestone:'Stim',        daysOverdue:88, lastFu:'2026-03-01',referred:'2026-02-28',status:'Cycle on hold · 40% pending',          invoice:'IIC/G/25-26/0095',history:'10% paid · Stim 40% overdue 88d · patient lost interest',                            payments:[{m:'2026-02',a:20500}]},
 {id:'IIC-2502-090',name:'Tina Bhardwaj', centre:'Noida',       fc:'P. Rao',   pkg:'IP222',    desc:'IVF Self-cycle Premium',            signup:'2026-02-02',gross:280000,discPct:7.9, incGst:258000,milestone:'OPU · override',daysOverdue:110,lastFu:'2026-02-05',referred:'2026-02-01',status:'OPU override unresolved',              invoice:'IIC/N/25-26/0090',history:'OPU done with director override · ₹40k still pending · 110d overdue',               payments:[{m:'2026-02',a:218000}]},
 {id:'IIC-2501-200',name:'Megha Pillai',  centre:'Vasant Vihar',fc:'A. Dutta', pkg:'IP222+CMP',desc:'IVF + Composite Add-on',            signup:'2026-01-08',gross:340000,discPct:8.2, incGst:312000,milestone:'Trigger',     daysOverdue:150,lastFu:'2025-12-29',referred:'2025-12-25',status:'Cycle stalled · 50% pending',          invoice:'IIC/V/25-26/0200',history:'50% paid Jan · Trigger 150d overdue · refund discussion',                            payments:[{m:'2026-01',a:31200},{m:'2026-01',a:125000}]},
 {id:'IIC-2501-300',name:'Sweta Kumari',  centre:'Noida',       fc:'J. Saini', pkg:'IP11',     desc:'IVF Self-cycle Standard',           signup:'2025-11-08',gross:215000,discPct:9.3, incGst:195000,milestone:'Stim',        daysOverdue:200,lastFu:'2025-12-10',referred:'2025-12-01',status:'Likely write-off',                     invoice:'IIC/N/25-26/0300',history:'Booked Nov 2025 · only 10% paid · 200d overdue · likely write-off',                  payments:[{m:'2025-11',a:19500}]},
 {id:'IIC-2412-010',name:'Sarika Iyer',   centre:'Ghaziabad',   fc:'K. Menon', pkg:'IP222',    desc:'IVF Self-cycle Premium',            signup:'2025-10-10',gross:275000,discPct:9.1, incGst:250000,milestone:'OPU',         daysOverdue:220,lastFu:'2025-11-15',referred:'2025-11-01',status:'Cycle abandoned · ₹35k pending',       invoice:'IIC/G/25-26/0010',history:'OPU done · ₹35k pending since Oct 2025 · 220d overdue',                              payments:[{m:'2025-10',a:25000},{m:'2025-10',a:100000},{m:'2025-10',a:90000}]},
 {id:'IIC-2412-300',name:'Rashmi Verma',  centre:'Srinagar',    fc:'R. Bhat',  pkg:'IP11',     desc:'IVF Self-cycle Standard',           signup:'2025-09-17',gross:200000,discPct:7.5, incGst:185000,milestone:'Trigger',     daysOverdue:250,lastFu:'2025-10-15',referred:'2025-10-01',status:'Write-off recommended',                invoice:'IIC/S/25-26/0300',history:'50% paid Sep 2025 · Trigger 250d overdue · write-off recommended',                   payments:[{m:'2025-09',a:18500},{m:'2025-09',a:74000}]}
];

/* ---- aggregate totals ---- */
const TOT_EXP=CENTRE_STATS.reduce((s,c)=>s+c.exp,0);
const TOT_ACT=CENTRE_STATS.reduce((s,c)=>s+c.act,0);
const TOT_AGING=Object.values(AGING).reduce((s,a)=>s+a.reduce((x,y)=>x+y,0),0);
const COMPANY_BUCKETS=[0,1,2,3,4].map(i=>Object.values(AGING).reduce((s,a)=>s+a[i],0));

/* ============================================================
   THIRD-PARTY REPRODUCTION — Donors & Surrogates
   (demo data — used in Booked Patient Journey detail pages)
   ============================================================ */
const DONORS=[
 {id:'DNR-2025-014',name:'Asha Kapoor',     age:27,blood:'O+', height:'5\'5"',weight:'56 kg',amh:'4.2 ng/mL',phenotype:'Wheatish · Black hair · Brown eyes',education:'B.Sc Nursing',
  occupation:'Staff Nurse',marital:'Married · 1 child',priorCycles:2,priorYield:'14 / 16 eggs (avg)',
  screening:'Cleared',hiv:'Non-reactive',hbsag:'Non-reactive',hcv:'Non-reactive',vdrl:'Non-reactive',thalassemia:'Negative',karyotype:'46,XX Normal',
  agency:'In-house registry',anonymity:'Anonymous',consent:'2026-04-12',contractValue:85000,paid:85000,centre:'Noida',coordinator:'P. Rao',phone:'9876xx2014',
  status:'Active · cycle in progress',notes:'High AMH, predictable response. Cleared all genetic and infectious screening. Consent signed for current cycle.'},
 {id:'DNR-2025-027',name:'Meera Joshi',     age:29,blood:'A+', height:'5\'3"',weight:'58 kg',amh:'3.6 ng/mL',phenotype:'Fair · Brown hair · Hazel eyes',education:'B.A.',
  occupation:'Homemaker',marital:'Married · 2 children',priorCycles:1,priorYield:'12 eggs',
  screening:'Cleared',hiv:'Non-reactive',hbsag:'Non-reactive',hcv:'Non-reactive',vdrl:'Non-reactive',thalassemia:'Negative',karyotype:'46,XX Normal',
  agency:'Sparsh Surrogacy Agency',anonymity:'Anonymous',consent:'2026-04-30',contractValue:90000,paid:45000,centre:'Vasant Vihar',coordinator:'A. Dutta',phone:'9876xx2027',
  status:'Active · stimulation phase',notes:'Reliable donor, second cycle with India IVF. Repeat KYC done 28 Apr 2026.'},
 {id:'DNR-2025-031',name:'Rekha Bisht',     age:25,blood:'B+', height:'5\'4"',weight:'54 kg',amh:'5.1 ng/mL',phenotype:'Wheatish · Black hair · Brown eyes',education:'B.Com',
  occupation:'Bank teller',marital:'Married · 1 child',priorCycles:0,priorYield:'First cycle',
  screening:'Cleared',hiv:'Non-reactive',hbsag:'Non-reactive',hcv:'Non-reactive',vdrl:'Non-reactive',thalassemia:'Negative',karyotype:'46,XX Normal',
  agency:'In-house registry',anonymity:'Anonymous',consent:'2026-05-15',contractValue:80000,paid:40000,centre:'Rohini',coordinator:'S. Bose',phone:'9876xx2031',
  status:'Active · OPU planned',notes:'First-time donor. AMH high — risk-of-OHSS protocol to be followed.'}
];

const SURROGATES=[
 {id:'SUR-2025-006',name:'Sunita Devi',     age:31,blood:'O+', height:'5\'2"',weight:'62 kg',bmi:'24.8',parity:'G3P2 · 2 living children',obstetricHist:'Both prior deliveries full-term vaginal, no complications',
  endometrium:'8.2 mm (trilaminar)',hsg:'Normal cavity',
  screening:'Cleared',hiv:'Non-reactive',hbsag:'Non-reactive',hcv:'Non-reactive',vdrl:'Non-reactive',gtt:'Normal',thyroid:'TSH 2.1',
  legalStatus:'Surrogacy contract signed · ART Act 2021 compliant',consent:'2026-04-20',ec:'Eligibility Certificate issued',insurance:'36 months · Star Health',
  agency:'In-house registry',anonymity:'Known to couple',contractValue:550000,paid:275000,centre:'Noida',coordinator:'P. Rao',phone:'9876xx3006',husband:'Ramesh Kumar · 34 yrs',
  status:'Active · endometrial preparation',notes:'Altruistic surrogacy under ART Act. Endometrium responding well to oestrogen.'},
 {id:'SUR-2025-009',name:'Lakshmi Yadav',   age:33,blood:'B+', height:'5\'3"',weight:'64 kg',bmi:'25.3',parity:'G2P1 · 1 living child',obstetricHist:'Prior LSCS · uneventful recovery',
  endometrium:'9.0 mm (trilaminar)',hsg:'Normal cavity',
  screening:'Cleared',hiv:'Non-reactive',hbsag:'Non-reactive',hcv:'Non-reactive',vdrl:'Non-reactive',gtt:'Normal',thyroid:'TSH 1.8',
  legalStatus:'Surrogacy contract signed · ART Act 2021 compliant',consent:'2026-05-02',ec:'EC under processing',insurance:'36 months · Niva Bupa',
  agency:'Sparsh Surrogacy Agency',anonymity:'Known to couple',contractValue:600000,paid:300000,centre:'Vasant Vihar',coordinator:'A. Dutta',phone:'9876xx3009',husband:'Vinod Yadav · 36 yrs',
  status:'Active · ET planned',notes:'Prior LSCS — flagged for high-risk obstetric monitoring post-transfer.'}
];

/* deterministic donor / surrogate assignment per patient */
function _hashIdx(s,n){let h=0;for(let i=0;i<s.length;i++)h=(h*31+s.charCodeAt(i))%9973;return h%n;}
function patientDonor(p){
  if(!p||!p.id) return null;
  if(p.donorId){return DONORS.find(d=>d.id===p.donorId)||DONORS[_hashIdx(p.id,DONORS.length)];}
  return DONORS[_hashIdx(p.id,DONORS.length)];
}
function patientSurrogate(p){
  if(!p||!p.id) return null;
  if(p.surrogateId){return SURROGATES.find(s=>s.id===p.surrogateId)||SURROGATES[_hashIdx(p.id,SURROGATES.length)];}
  return SURROGATES[_hashIdx(p.id,SURROGATES.length)];
}
/* default selection — donor cycles auto-select Donor, surrogacy contracts auto-select Surrogate */
function patientDonorMode(p){
  if(p&&p.donorMode) return p.donorMode;
  if(p&&p.pkg&&/donor/i.test(p.pkg)) return 'Donor';
  return 'Self-cycle';
}
function patientSurrogateMode(p){
  if(p&&p.surrogateMode) return p.surrogateMode;
  if(p&&p.pkg&&/surrogate|surrogacy/i.test(p.pkg)) return 'Surrogate';
  return 'Self';
}
