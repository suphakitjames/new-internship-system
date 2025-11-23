// JavaScript สำหรับหน้า internship_requests.php
// Global variable to store current request data
let currentRequestData = null;

// Close modal function
function closeModal() {
    document.getElementById('viewModal').classList.add('hidden');
    currentRequestData = null;
}

function showRequestDetails(data) {
    currentRequestData = data;
    
    // ข้อมูลนิสิต
    const studentInfo = `
        <div class="bg-white p-4 rounded-xl">
            <div class="text-sm text-slate-500 mb-1">รหัสนิสิต</div>
            <div class="font-mono font-bold text-lg text-blue-600">${data.student_code}</div>
        </div>
        <div class="bg-white p-4 rounded-xl">
            <div class="text-sm text-slate-500 mb-1">ชื่อ-นามสกุล</div>
            <div class="font-medium text-slate-900">${data.full_name}</div>
        </div>
        <div class="bg-white p-4 rounded-xl">
            <div class="text-sm text-slate-500 mb-1">สาขาวิชา</div>
            <div class="font-medium text-slate-900">${data.major}</div>
        </div>
        <div class="bg-white p-4 rounded-xl">
            <div class="text-sm text-slate-500 mb-1">ชั้นปี</div>
            <div class="font-medium text-slate-900">ปี ${data.year_level}</div>
        </div>
        <div class="bg-white p-4 rounded-xl">
            <div class="text-sm text-slate-500 mb-1">เกรดเฉลี่ย (GPA)</div>
            <div class="font-bold text-lg ${data.gpa >= 3.0 ? 'text-green-600' : data.gpa >= 2.5 ? 'text-amber-600' : 'text-red-600'}">${data.gpa || '-'}</div>
        </div>
        <div class="bg-white p-4 rounded-xl">
            <div class="text-sm text-slate-500 mb-1">เบอร์โทรศัพท์</div>
            <div class="font-medium text-slate-900">${data.phone || '-'}</div>
        </div>
    `;
    
    // ข้อมูลบริษัท
    const companyInfo = `
        <div class="bg-white p-4 rounded-xl">
            <div class="font-bold text-lg text-slate-900 mb-2">${data.company_name}</div>
            <div class="text-sm text-slate-600 mb-1">
                <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                ${data.province_name || '-'}
            </div>
            ${data.company_address ? `<div class="text-sm text-slate-600">${data.company_address}</div>` : ''}
            <div class="mt-3 pt-3 border-t border-slate-200">
                <div class="text-sm text-slate-500">ระยะเวลาฝึกงาน</div>
                <div class="font-medium text-slate-900">${formatDate(data.start_date)} - ${formatDate(data.end_date)}</div>
            </div>
        </div>
    `;
    
    // สถานะต่างๆ
    document.getElementById('studentInfo').innerHTML = studentInfo;
    document.getElementById('companyInfo').innerHTML = companyInfo;
    document.getElementById('facultyStatus').innerHTML = getApprovalStatusBadge(data.faculty_approval_status || 'pending');
    document.getElementById('companyStatus').innerHTML = getCompanyResponseBadge(data.company_response_status || 'pending');
    document.getElementById('documentStatus').innerHTML = getDocumentStatusBadge(data.document_response_status || 'pending');
    document.getElementById('mainStatus').innerHTML = getStatusBadge(data.status);
    
    // ปุ่มอนุมัติ/ไม่อนุมัติหลัก (ถ้ายังรอพิจารณา)
    if (data.status === 'pending') {
        document.getElementById('approvalButtons').innerHTML = `
            <button onclick="approveRequest(${data.id}, '${data.full_name}')" class="flex-1 bg-green-600 text-white px-6 py-3 rounded-xl font-medium hover:bg-green-700 transition-all shadow-lg shadow-green-500/30">
                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                อนุมัติคำขอ
            </button>
            <button onclick="rejectRequest(${data.id}, '${data.full_name}')" class="flex-1 bg-red-600 text-white px-6 py-3 rounded-xl font-medium hover:bg-red-700 transition-all shadow-lg shadow-red-500/30">
                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                ไม่อนุมัติ
            </button>
        `;
    } else {
        document.getElementById('approvalButtons').innerHTML = '';
    }
    
    // Show modal
    document.getElementById('viewModal').classList.remove('hidden');
}

// Status badge functions
function getStatusBadge(status) {
    const badges = {
        'pending': '<span class="inline-flex items-center px-6 py-3 bg-amber-100 text-amber-800 rounded-full text-lg font-medium"><svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>รอพิจารณา</span>',
        'approved': '<span class="inline-flex items-center px-6 py-3 bg-green-100 text-green-800 rounded-full text-lg font-medium"><svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>อนุมัติแล้ว</span>',
        'rejected': '<span class="inline-flex items-center px-6 py-3 bg-red-100 text-red-800 rounded-full text-lg font-medium"><svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>ไม่อนุมัติ</span>'
    };
    return badges[status] || badges['pending'];
}

function getApprovalStatusBadge(status) {
    const badges = {
        'pending': '<span class="inline-flex items-center px-3 py-1.5 bg-amber-100 text-amber-800 rounded-full text-sm font-medium">รอพิจารณา</span>',
        'approved': '<span class="inline-flex items-center px-3 py-1.5 bg-green-100 text-green-800 rounded-full text-sm font-medium">✓ อนุมัติ</span>',
        'rejected': '<span class="inline-flex items-center px-3 py-1.5 bg-red-100 text-red-800 rounded-full text-sm font-medium">✗ ไม่อนุมัติ</span>'
    };
    return badges[status] || badges['pending'];
}

function getCompanyResponseBadge(status) {
    const badges = {
        'pending': '<span class="inline-flex items-center px-3 py-1.5 bg-amber-100 text-amber-800 rounded-full text-sm font-medium">รอตอบกลับ</span>',
        'accepted': '<span class="inline-flex items-center px-3 py-1.5 bg-green-100 text-green-800 rounded-full text-sm font-medium">✓ ตอบรับ</span>',
        'rejected': '<span class="inline-flex items-center px-3 py-1.5 bg-red-100 text-red-800 rounded-full text-sm font-medium">✗ ปฏิเสธ</span>'
    };
    return badges[status] || badges['pending'];
}

function getDocumentStatusBadge(status) {
    const badges = {
        'pending': '<span class="inline-flex items-center px-3 py-1.5 bg-amber-100 text-amber-800 rounded-full text-sm font-medium">รอส่งเอกสาร</span>',
        'submitted': '<span class="inline-flex items-center px-3 py-1.5 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">📄 ส่งแล้ว</span>',
        'approved': '<span class="inline-flex items-center px-3 py-1.5 bg-green-100 text-green-800 rounded-full text-sm font-medium">✓ อนุมัติ</span>',
        'rejected': '<span class="inline-flex items-center px-3 py-1.5 bg-red-100 text-red-800 rounded-full text-sm font-medium">✗ ไม่อนุมัติ</span>'
    };
    return badges[status] || badges['pending'];
}

function formatDate(dateString) {
    if (!dateString) return '-';
    const date = new Date(dateString);
    return date.toLocaleDateString('th-TH', { year: 'numeric', month: 'long', day: 'numeric' });
}

// Update status functions
function updateFacultyStatus(status) {
    if (!currentRequestData) return;
    
    const statusText = status === 'approved' ? 'อนุมัติ' : 'ไม่อนุมัติ';
    if (confirm(`คุณต้องการเปลี่ยนผลการพิจารณาของคณะเป็น "${statusText}" ใช่หรือไม่?`)) {
        updateStatus('faculty', status);
    }
}

function updateCompanyStatus(status) {
    if (!currentRequestData) return;
    
    const statusText = status === 'accepted' ? 'ตอบรับ' : 'ปฏิเสธ';
    if (confirm(`คุณต้องการเปลี่ยนผลการตอบรับจากหน่วยงานเป็น "${statusText}" ใช่หรือไม่?`)) {
        updateStatus('company', status);
    }
}

function updateDocumentStatus(status) {
    if (!currentRequestData) return;
    
    const statusText = status === 'submitted' ? 'ส่งแล้ว' : status === 'approved' ? 'อนุมัติ' : 'ไม่อนุมัติ';
    if (confirm(`คุณต้องการเปลี่ยนผลการตอบกลับเอกสารเป็น "${statusText}" ใช่หรือไม่?`)) {
        updateStatus('document', status);
    }
}

function updateStatus(type, status) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = 'index.php?page=admin&action=process_update_status';
    
    const idInput = document.createElement('input');
    idInput.type = 'hidden';
    idInput.name = 'request_id';
    idInput.value = currentRequestData.id;
    
    const typeInput = document.createElement('input');
    typeInput.type = 'hidden';
    typeInput.name = 'status_type';
    typeInput.value = type;
    
    const statusInput = document.createElement('input');
    statusInput.type = 'hidden';
    statusInput.name = 'status';
    statusInput.value = status;
    
    form.appendChild(idInput);
    form.appendChild(typeInput);
    form.appendChild(statusInput);
    
    document.body.appendChild(form);
    form.submit();
}

function approveRequest(requestId, studentName) {
    if (confirm(`คุณต้องการอนุมัติคำขอของ ${studentName} ใช่หรือไม่?`)) {
        updateRequestStatus(requestId, 'approved');
    }
}

function rejectRequest(requestId, studentName) {
    const reason = prompt(`กรุณาระบุเหตุผลที่ไม่อนุมัติคำขอของ ${studentName}:`);
    if (reason !== null && reason.trim() !== '') {
        updateRequestStatus(requestId, 'rejected', reason);
    }
}

function updateRequestStatus(requestId, status, reason = '') {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = 'index.php?page=admin&action=process_update_request_status';
    
    const idInput = document.createElement('input');
    idInput.type = 'hidden';
    idInput.name = 'request_id';
    idInput.value = requestId;
    
    const statusInput = document.createElement('input');
    statusInput.type = 'hidden';
    statusInput.name = 'status';
    statusInput.value = status;
    
    form.appendChild(idInput);
    form.appendChild(statusInput);
    
    if (reason) {
        const reasonInput = document.createElement('input');
        reasonInput.type = 'hidden';
        reasonInput.name = 'reason';
        reasonInput.value = reason;
        form.appendChild(reasonInput);
    }
    
    document.body.appendChild(form);
    form.submit();
}

// Print function (based on your original code)
function printStudentDocument() {
    if (!currentRequestData) return;
    
    const printWindow = window.open('', '_blank');
    const printContent = `
        <!doctype html>
        <html>
        <head>
            <meta charset="utf-8">
            <title>หนังสือขอความอนุเคราะห์ให้นิสิตฝึกประสบการณ์วิชาชีพ - ${currentRequestData.student_code}</title>
            <style type="text/css">
                @media print {
                    button { display: none !important; }
                    input, textarea {
                        border: none !important;
                        box-shadow: none !important;
                        outline: none !important;
                    }
                }
                body {
                    font-family: 'TH Sarabun New', 'Sarabun', sans-serif;
                    font-size: 14px;
                    line-height: 1.6;
                    color: #333;
                    max-width: 800px;
                    margin: 0 auto;
                    padding: 20px;
                }
                .header {
                    text-align: center;
                    margin-bottom: 30px;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                }
                td {
                    padding: 4px;
                }
                .btn {
                    padding: 8px 16px;
                    margin: 5px;
                    border: none;
                    border-radius: 4px;
                    cursor: pointer;
                }
                .btn-success {
                    background-color: #28a745;
                    color: white;
                }
            </style>
            <script type="text/javascript">
                function print_page() {
                    window.print();
                }
            </script>
        </head>
        <body>
            <div align="right">
                <button type="button" class="btn btn-success" onclick="window.close();">ย้อนกลับ</button>
                <button type="button" class="btn btn-success" onclick="print_page();">🖨️ พิมพ์</button>
            </div>
            
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td colspan="3" align="center"><img src="../img/garuda.png" width="110" onerror="this.style.display='none'"></td>
                </tr>
                <tr>
                    <td width="50%" valign="top">ที่ ศธ 0530.10/</td>
                    <td width="20%">&nbsp;</td>
                    <td width="30%">คณะการบัญชีและการจัดการ<br>
                    มหาวิทยาลัยมหาสารคาม<br>
                    ตำบลขามเรียง อำเภอกันทรวิชัย<br>
                    จังหวัดมหาสารคาม 44150</td>
                </tr>
                <tr>
                    <td valign="top">&nbsp;</td>
                    <td><input type="text" placeholder="กรอกวันที่" style="width:90%; font-size:14px"></td>
                    <td>&nbsp;</td>
                </tr>
                <tr><td height="12" colspan="3"></td></tr>
            </table>
            
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="6%">เรื่อง</td>
                    <td colspan="2">ขอความอนุเคราะห์ให้นิสิตฝึกประสบการณ์วิชาชีพ</td>
                </tr>
                <tr><td height="10" colspan="3"></td></tr>
                <tr>
                    <td>เรียน</td>
                    <td colspan="2"><input type="text" value="${currentRequestData.company_name}" style="width:95%; font-size:14px"></td>
                </tr>
                <tr><td height="10" colspan="3"></td></tr>
                <tr>
                    <td colspan="2">สิ่งที่ส่งมาด้วย</td>
                    <td width="88%">1. แบบตอบรับนิสิตเข้ารับการฝึกประสบการณ์วิชาชีพ</td>
                </tr>
                <tr>
                    <td colspan="2">&nbsp;</td>
                    <td>2. ประวัติย่อ (Resume)</td>
                </tr>
            </table>
            
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr><td height="12"></td></tr>
                <tr>
                    <td>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
                    ด้วยคณะการบัญชีและการจัดการ ได้เปิดการเรียนการสอนนิสิตระดับปริญญาตรี หลักสูตรบัญชีบัณฑิต และหลักสูตรบริหารธุรกิจบัณฑิต 
                    และคณะฯ พิจารณาแล้วเห็นว่าหน่วยงานของท่านมีความเหมาะสมอย่างยิ่งในการเพิ่มทักษะและเสริมสร้างประสบการณ์ในการทำงานให้แก่นิสิต
                    ดังนั้น จึงใคร่ขอความอนุเคราะห์ให้ ${currentRequestData.full_name} นิสิตสาขาวิชา${currentRequestData.major} 
                    เข้ารับการฝึกประสบการณ์วิชาชีพในหน่วยงานของท่าน <strong>ระหว่าง${formatDate(currentRequestData.start_date)} - ${formatDate(currentRequestData.end_date)}</strong>
                    </td>
                </tr>
                <tr><td height="10"></td></tr>
                <tr>
                    <td>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
                    จึงเรียนมาเพื่อโปรดพิจารณาให้ความอนุเคราะห์ และขอบพระคุณในความกรุณาของท่านมา ณ โอกาสนี้<p>&nbsp;</p>
                    </td>
                </tr>
                <tr>
                    <td align="center">ขอแสดงความนับถือ<br><br><br>
                    (ผู้ช่วยศาสตราจารย์ ดร.นิติพงษ์ ส่งศรีโรจน์)<br>
                    คณบดีคณะการบัญชีและการจัดการ<br>
                    มหาวิทยาลัยมหาสารคาม<br></td>
                </tr>
                <tr><td>&nbsp;</td></tr>
                <tr>
                    <td style="font-size:12px"><br>ฝ่ายกิจการนิสิต<br>
                    คณะการบัญชีและการจัดการ<br>
                    มหาวิทยาลัยมหาสารคาม<br>
                    โทรศัพท์ 0-4375-4333 ต่อ 3433<br>
                    โทรสาร 0-4375-4422</td>
                </tr>
            </table>
        </body>
        </html>
    `;
    
    printWindow.document.write(printContent);
    printWindow.document.close();
}
