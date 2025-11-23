// Test file - ตรวจสอบว่า JavaScript ทำงานหรือไม่
console.log('✅ admin_requests.js ถูกโหลดแล้ว!');

// Test ว่า jQuery พร้อมใช้งานหรือไม่
if (typeof $ !== 'undefined') {
    console.log('✅ jQuery พร้อมใช้งาน');
} else {
    console.error('❌ jQuery ไม่พร้อมใช้งาน');
}

// Test ว่ามี element ที่ต้องการหรือไม่
$(document).ready(function () {
    console.log('✅ Document Ready');
    console.log('Modal element:', $('#viewModal').length > 0 ? '✅ พบ' : '❌ ไม่พบ');
    console.log('Student Info element:', $('#studentInfo').length > 0 ? '✅ พบ' : '❌ ไม่พบ');
    console.log('Company Info element:', $('#companyInfo').length > 0 ? '✅ พบ' : '❌ ไม่พบ');
    console.log('Faculty Status element:', $('#facultyStatus').length > 0 ? '✅ พบ' : '❌ ไม่พบ');
});
