const files=[
{name:'poster.jpg',type:'Afbeelding',size:'812 KB',modified:'12-05-2026 14:32'},
{name:'facts.xlsx',type:'Excel',size:'45 KB',modified:'12-05-2026 13:18'},
{name:'rapport.pdf',type:'PDF',size:'1.2 MB',modified:'12-05-2026 11:07'},
{name:'logo.png',type:'Afbeelding',size:'203 KB',modified:'11-05-2026 09:41'}
];
const views=[...document.querySelectorAll('.view')], nav=[...document.querySelectorAll('.nav')];
function openView(id){views.forEach(v=>v.classList.toggle('active',v.id===id));nav.forEach(n=>n.classList.toggle('active',n.dataset.view===id));document.querySelector('.sidebar').classList.remove('open')}
nav.forEach(n=>n.onclick=()=>openView(n.dataset.view));
document.querySelectorAll('[data-open]').forEach(b=>b.onclick=()=>openView(b.dataset.open));
document.getElementById('menu').onclick=()=>document.querySelector('.sidebar').classList.toggle('open');
function row(f){return `<tr><td><strong>${f.name}</strong></td><td>${f.type}</td><td>${f.size}</td><td>${f.modified}</td></tr>`}
document.getElementById('recent').innerHTML=files.map(row).join('');
function render(){let q=document.getElementById('fileSearch').value.toLowerCase(),t=document.getElementById('typeFilter').value;let list=files.filter(f=>f.name.toLowerCase().includes(q)&&(!t||f.type===t));document.getElementById('allFiles').innerHTML=list.map(row).join('');document.getElementById('empty').hidden=list.length>0}
document.getElementById('fileSearch').oninput=render;document.getElementById('typeFilter').onchange=render;render();
const input=document.getElementById('fileInput'),sel=document.getElementById('selected'),drop=document.getElementById('drop');
function show(file){if(!file)return;sel.hidden=false;sel.innerHTML=`<strong>${file.name}</strong><br><small>${Math.ceil(file.size/1024)} KB</small>`}
input.onchange=()=>show(input.files[0]);['dragenter','dragover'].forEach(e=>drop.addEventListener(e,x=>{x.preventDefault();drop.classList.add('drag')}));['dragleave','drop'].forEach(e=>drop.addEventListener(e,x=>{x.preventDefault();drop.classList.remove('drag')}));drop.addEventListener('drop',e=>show(e.dataTransfer.files[0]));
document.getElementById('uploadForm').onsubmit=e=>{e.preventDefault();let t=document.getElementById('toast');t.textContent='Demo: PHP moet deze upload straks echt verwerken.';t.classList.add('show');setTimeout(()=>t.classList.remove('show'),2400)};
