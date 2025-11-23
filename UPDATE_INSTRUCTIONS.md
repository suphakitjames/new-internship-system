# คำแนะนำการอัพเดทระบบจัดการฝึกงาน

## การอัพเดทครั้งนี้เพิ่มฟีเจอร์ใหม่:

### 1. ผลการพิจารณาของคณะ (Faculty Approval)
- สถานะการพิจารณา (pending, approved, rejected)
- วันที่พิจารณา
- ความคิดเห็นจากคณะ

### 2. ผลการตอบรับจากหน่วยงาน (Company Response)
- สถานะการตอบรับ (pending, accepted, rejected)
- วันที่ตอบกลับ
- ความคิดเห็นจากบริษัท/หน่วยงาน

### 3. ผลการตอบกลับเอกสาร (Document Response)
- สถานะเอกสาร (pending, submitted, approved, rejected)
- วันที่ตอบกลับ
- ไฟล์เอกสารที่แนบ

### 4. ฟีเจอร์ปริ้นเอกสาร
- ปริ้นเอกสารคำขอฝึกงานของนิสิตแต่ละคน
- แสดงข้อมูลครบถ้วน พร้อมสถานะทั้งหมด

## ขั้นตอนการติดตั้ง:

### 1. อัพเดทฐานข้อมูล
เปิด phpMyAdmin หรือ MySQL client แล้วรันไฟล์:
```
update_internship_requests_table.sql
```

หรือรันคำสั่ง SQL โดยตรง:
```sql
USE internship_system;

ALTER TABLE internship_requests
ADD COLUMN faculty_approval_status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
ADD COLUMN faculty_approval_date DATETIME NULL,
ADD COLUMN faculty_comment TEXT NULL,
ADD COLUMN company_response_status ENUM('pending', 'accepted', 'rejected') DEFAULT 'pending',
ADD COLUMN company_response_date DATETIME NULL,
ADD COLUMN company_response_comment TEXT NULL,
ADD COLUMN document_response_status ENUM('pending', 'submitted', 'approved', 'rejected') DEFAULT 'pending',
ADD COLUMN document_response_date DATETIME NULL,
ADD COLUMN document_files TEXT NULL,
ADD COLUMN round_id INT NULL,
ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;
```

### 2. ตรวจสอบการอัพเดท
ตรวจสอบว่าคอลัมน์ใหม่ถูกเพิ่มเรียบร้อยแล้ว:
```sql
DESCRIBE internship_requests;
```

### 3. ทดสอบระบบ
1. เข้าสู่ระบบในฐานะ Admin
2. ไปที่หน้า "อนุมัติคำขออฝึกงาน"
3. คลิกปุ่ม "ดู" ที่คำขอใดๆ
4. ทดสอบการสลับแท็บทั้ง 3:
   - แท็บ 1: อนุมัติคำขอ
   - แท็บ 2: ผลการอนุมัติ (แสดงสถานะ 3 ประเภท)
   - แท็บ 3: ข้อมูลนิสิต (มีปุ่มปริ้นเอกสาร)
5. ทดสอบการปริ้นเอกสาร

## ฟีเจอร์ที่เพิ่มเข้ามา:

### หน้า Modal ใหม่ (3 แท็บ)

#### แท็บ 1: อนุมัติคำขอ
- แสดงข้อมูลพื้นฐานของนิสิต
- ข้อมูลบริษัท/หน่วยงาน
- ระยะเวลาฝึกงาน
- สถานะคำขอปัจจุบัน
- ปุ่มอนุมัติ/ไม่อนุมัติ (ถ้ายังรอพิจารณา)

#### แท็บ 2: ผลการอนุมัติ
แสดงสถานะทั้ง 3 ประเภทในการ์ดสีสันสวยงาม:
1. **ผลการพิจารณาของคณะ** (สีน้ำเงิน)
   - สถานะ: รอพิจารณา / อนุมัติ / ไม่อนุมัติ
   - วันที่พิจารณา
   - ความคิดเห็น

2. **ผลการตอบรับจากหน่วยงาน** (สีเขียว)
   - สถานะ: รอตอบกลับ / ตอบรับ / ปฏิเสธ
   - วันที่ตอบกลับ
   - ความคิดเห็น

3. **ผลการตอบกลับเอกสาร** (สีม่วง)
   - สถานะ: รอส่งเอกสาร / ส่งแล้ว / อนุมัติ / ไม่อนุมัติ
   - วันที่ตอบกลับ

#### แท็บ 3: ข้อมูลนิสิต
- ข้อมูลส่วนตัวครบถ้วน (รหัสนิสิต, ชื่อ, สาขา, ปี, GPA, อีเมล, เบอร์โทร)
- ข้อมูลสถานที่ฝึกงาน
- ระยะเวลาฝึกงาน
- **ปุ่มปริ้นเอกสาร** - พิมพ์เอกสารคำขอฝึกงานพร้อมสถานะทั้งหมด

### การออกแบบ UI/UX:
- ใช้ Gradient สีสันสวยงาม
- Animation เมื่อสลับแท็บ
- Responsive Design
- สีสันแยกตามประเภทสถานะ
- ไอคอนประกอบทุกส่วน

## ข้อมูลเพิ่มเติมสำหรับคณะ:
- คณะมีนิสิต **10,000+ คน**
- **เฉพาะนิสิตปี 3** เท่านั้นที่สามารถฝึกงานได้
- แบ่งตามสาขาวิชาต่างๆ

## การใช้งานในอนาคต:
ระบบนี้พร้อมสำหรับการขยายฟีเจอร์เพิ่มเติม เช่น:
- การอัพโหลดเอกสารแนบ
- การแจ้งเตือนผ่านอีเมล
- ระบบ Workflow อัตโนมัติ
- Dashboard แสดงสถิติแบบ Real-time
- ระบบรายงานขั้นสูง

## หมายเหตุ:
- ข้อมูลเดิมจะไม่สูญหาย
- คอลัมน์ใหม่จะมีค่าเริ่มต้นเป็น 'pending'
- สามารถย้อนกลับได้โดยการ DROP COLUMN (ไม่แนะนำ)

## การแก้ไขปัญหา:

### ถ้า SQL Error:
1. ตรวจสอบว่าตารางชื่อ `internship_requests` มีอยู่จริง
2. ตรวจสอบว่าคอลัมน์ยังไม่ถูกเพิ่มไปแล้ว
3. ตรวจสอบ MySQL version (ควรเป็น 5.7+)

### ถ้าหน้าเว็บไม่แสดงผล:
1. Clear browser cache
2. ตรวจสอบ Console ใน Browser DevTools
3. ตรวจสอบ PHP error log

---

**สร้างโดย:** ระบบจัดการฝึกงาน  
**วันที่:** <?= date('Y-m-d H:i:s') ?>  
**เวอร์ชัน:** 2.0
