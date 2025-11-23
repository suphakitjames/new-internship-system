# 🚀 คำแนะนำการอัพเดทฐานข้อมูล

## ⚠️ สำคัญ: รันตามลำดับนี้เท่านั้น!

### ขั้นตอนที่ 1: สร้างตาราง internship_rounds

1. เปิด **phpMyAdmin** (http://localhost/phpmyadmin)
2. เลือกฐานข้อมูล **`internship_system`**
3. คลิกแท็บ **"SQL"**
4. **Copy** โค้ดด้านล่างนี้:

```sql
USE internship_system;

-- สร้างตาราง internship_rounds
CREATE TABLE IF NOT EXISTS `internship_rounds` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `round_name` varchar(100) NOT NULL COMMENT 'ชื่อรอบการฝึกงาน',
  `year` int(4) NOT NULL COMMENT 'ปีการศึกษา',
  `start_date` date NOT NULL COMMENT 'วันเริ่มต้น',
  `end_date` date NOT NULL COMMENT 'วันสิ้นสุด',
  `is_active` tinyint(1) DEFAULT 1 COMMENT 'สถานะเปิด/ปิด',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- เพิ่มข้อมูลตัวอย่าง
INSERT INTO `internship_rounds` (`round_name`, `year`, `start_date`, `end_date`, `is_active`) VALUES
('รอบที่ 1/2567', 2024, '2024-06-01', '2024-08-31', 1),
('รอบที่ 2/2567', 2024, '2024-11-01', '2025-01-31', 1),
('รอบที่ 1/2568', 2025, '2025-06-01', '2025-08-31', 1);
```

5. **Paste** ลงในช่อง SQL
6. คลิก **"Go"**
7. ✅ ควรเห็นข้อความ **"3 rows inserted"**

---

### ขั้นตอนที่ 2: เพิ่มคอลัมน์ใหม่ในตาราง internship_requests

**Copy ทีละคำสั่ง** แล้ว **Paste และ Go** ทีละคำสั่ง:

#### 1. เพิ่มคอลัมน์ faculty_approval_status
```sql
ALTER TABLE internship_requests
ADD COLUMN faculty_approval_status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending' COMMENT 'สถานะการพิจารณาจากคณะ';
```

#### 2. เพิ่มคอลัมน์ faculty_approval_date
```sql
ALTER TABLE internship_requests
ADD COLUMN faculty_approval_date DATETIME NULL COMMENT 'วันที่คณะพิจารณา';
```

#### 3. เพิ่มคอลัมน์ faculty_comment
```sql
ALTER TABLE internship_requests
ADD COLUMN faculty_comment TEXT NULL COMMENT 'ความคิดเห็นจากคณะ';
```

#### 4. เพิ่มคอลัมน์ company_response_status
```sql
ALTER TABLE internship_requests
ADD COLUMN company_response_status ENUM('pending', 'accepted', 'rejected') DEFAULT 'pending' COMMENT 'สถานะการตอบรับจากบริษัท';
```

#### 5. เพิ่มคอลัมน์ company_response_date
```sql
ALTER TABLE internship_requests
ADD COLUMN company_response_date DATETIME NULL COMMENT 'วันที่บริษัทตอบกลับ';
```

#### 6. เพิ่มคอลัมน์ company_response_comment
```sql
ALTER TABLE internship_requests
ADD COLUMN company_response_comment TEXT NULL COMMENT 'ความคิดเห็นจากบริษัท';
```

#### 7. เพิ่มคอลัมน์ document_response_status
```sql
ALTER TABLE internship_requests
ADD COLUMN document_response_status ENUM('pending', 'submitted', 'approved', 'rejected') DEFAULT 'pending' COMMENT 'สถานะการตอบกลับเอกสาร';
```

#### 8. เพิ่มคอลัมน์ document_response_date
```sql
ALTER TABLE internship_requests
ADD COLUMN document_response_date DATETIME NULL COMMENT 'วันที่ตอบกลับเอกสาร';
```

#### 9. เพิ่มคอลัมน์ document_files
```sql
ALTER TABLE internship_requests
ADD COLUMN document_files TEXT NULL COMMENT 'ไฟล์เอกสารที่แนบ (JSON array)';
```

#### 10. เพิ่มคอลัมน์ round_id
```sql
ALTER TABLE internship_requests
ADD COLUMN round_id INT NULL COMMENT 'รอบการฝึกงาน';
```

#### 11. เพิ่มคอลัมน์ updated_at
```sql
ALTER TABLE internship_requests
ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'วันที่อัพเดทล่าสุด';
```

---

### ขั้นตอนที่ 3: อัพเดทข้อมูลเก่า

```sql
UPDATE internship_requests 
SET 
    faculty_approval_status = COALESCE(faculty_approval_status, 'pending'),
    company_response_status = COALESCE(company_response_status, 'pending'),
    document_response_status = COALESCE(document_response_status, 'pending')
WHERE id > 0;
```

---

### ขั้นตอนที่ 4: ตรวจสอบผลลัพธ์

รันคำสั่งนี้เพื่อดูโครงสร้างตาราง:

```sql
DESCRIBE internship_requests;
```

หรือ

```sql
SHOW COLUMNS FROM internship_requests;
```

**คุณควรเห็นคอลัมน์ใหม่เหล่านี้:**
- ✅ faculty_approval_status
- ✅ faculty_approval_date
- ✅ faculty_comment
- ✅ company_response_status
- ✅ company_response_date
- ✅ company_response_comment
- ✅ document_response_status
- ✅ document_response_date
- ✅ document_files
- ✅ round_id
- ✅ updated_at

---

## 🎯 หลังจากรัน SQL สำเร็จแล้ว:

1. **รีเฟรชหน้าเว็บ** (กด Ctrl+F5)
2. **เข้าสู่ระบบในฐานะ Admin**
3. **ไปที่หน้า "อนุมัติคำขออฝึกงาน"**
4. **คลิกปุ่ม "ดู" 👁️** ที่คำขอใดๆ
5. **ทดสอบแท็บทั้ง 3:**
   - แท็บ 1: อนุมัติคำขอ
   - แท็บ 2: ผลการอนุมัติ (แสดงสถานะ 3 ประเภท)
   - แท็บ 3: ข้อมูลนิสิต (มีปุ่มปริ้นเอกสาร 🖨️)

---

## ⚠️ หมายเหตุสำคัญ:

### ถ้าเจอ Error "Duplicate column name"
- **ไม่เป็นไร!** แสดงว่าคอลัมน์นั้นมีอยู่แล้ว
- ให้ข้ามไปรันคำสั่งถัดไป

### ถ้าเจอ Error อื่นๆ
1. ตรวจสอบว่าเลือกฐานข้อมูล `internship_system` แล้ว
2. ตรวจสอบว่ารันขั้นตอนที่ 1 (สร้างตาราง internship_rounds) แล้ว
3. ลอง Clear browser cache แล้วรีเฟรชหน้าเว็บ

---

## 📞 ต้องการความช่วยเหลือ?

ถ้ายังมีปัญหา ให้:
1. Screenshot หน้าจอ Error
2. Copy ข้อความ Error
3. บอกว่ากำลังรันคำสั่งไหนอยู่

---

**สร้างโดย:** ระบบจัดการฝึกงาน  
**วันที่:** 2025-11-22  
**เวอร์ชัน:** 2.0
