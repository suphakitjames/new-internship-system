# คู่มือการอัปโหลดโปรเจกต์ขึ้น GitHub

เอกสารนี้จะแนะนำขั้นตอนการนำโค้ดขึ้น GitHub ทีละขั้นตอนครับ

## 1. เตรียมความพร้อม (Prerequisites)

### ตรวจสอบว่ามี Git หรือยัง

เปิด Terminal (หรือ Command Prompt) แล้วพิมพ์คำสั่ง:

```bash
git --version
```

ถ้าขึ้นเวอร์ชัน (เช่น `git version 2.x.x`) แปลว่ามีแล้ว ถ้ายังไม่มีให้ดาวน์โหลดและติดตั้ง [Git](https://git-scm.com/downloads) ก่อนครับ

### สมัคร GitHub

ถ้ายังไม่มีบัญชี ให้สมัครที่ [github.com](https://github.com/)

## 2. เริ่มต้นใช้งาน Git ในโปรเจกต์ (Initialize)

เปิด Terminal ในโฟลเดอร์โปรเจกต์ของคุณ (`c:\xampp1\htdocs\test_internship`) แล้วทำตามนี้:

1. **สร้าง Repository:**

   ```bash
   git init
   ```

   คำสั่งนี้จะสร้างโฟลเดอร์ `.git` ที่ซ่อนอยู่ เพื่อเริ่มเก็บประวัติการแก้ไข

2. **ตรวจสอบไฟล์ที่จะไม่เอาขึ้น (Optional):**
   ผมได้สร้างไฟล์ `.gitignore` ให้แล้ว เพื่อบอก Git ว่าไม่ต้องเอาไฟล์ขยะหรือไฟล์ตั้งค่าส่วนตัวขึ้นไป (เช่น `vendor/`, `.env`)

3. **เพิ่มไฟล์ทั้งหมดเข้าสู่ Staging Area:**

   ```bash
   git add .
   ```

4. **บันทึกไฟล์ (Commit):**
   ```bash
   git commit -m "Initial commit: อัปโหลดโปรเจกต์ครั้งแรก"
   ```

## 3. สร้าง Repository บน GitHub

1. ไปที่ [GitHub.com](https://github.com/) แล้วล็อกอิน
2. กดปุ่ม **+** มุมขวาบน เลือก **New repository**
3. ตั้งชื่อ Repository (เช่น `internship-system`)
4. เลือก **Public** (สาธารณะ) หรือ **Private** (ส่วนตัว)
5. **ไม่ต้อง** ติ๊กเลือก "Initialize this repository with a README" (เพราะเรามีโปรเจกต์อยู่แล้ว)
6. กดปุ่ม **Create repository**

## 4. เชื่อมต่อและอัปโหลด (Connect & Push)

เมื่อสร้างเสร็จ GitHub จะหน้านั้นจะแสดงคำสั่งมาให้ ดูที่หัวข้อ **"…or push an existing repository from the command line"**

ก๊อปปี้คำสั่ง 3 บรรทัดนั้นมาวางใน Terminal ของคุณ ทีละบรรทัด:

1. **เชื่อมต่อกับ GitHub:**
   _(เปลี่ยน `YOUR_USERNAME` เป็นชื่อผู้ใช้ของคุณ)_

   ```bash
   git remote add origin https://github.com/YOUR_USERNAME/internship-system.git
   ```

2. **เปลี่ยนชื่อ Branch หลักเป็น main (ถ้ายังไม่ได้เป็น):**

   ```bash
   git branch -M main
   ```

3. **อัปโหลดโค้ด (Push):**
   ```bash
   git push -u origin main
   ```

## 5. (ทางเลือก) ใช้ GitHub Desktop

ถ้าไม่ถนัดใช้คำสั่ง (Command Line) สามารถใช้โปรแกรม **GitHub Desktop** ได้:

1. ดาวน์โหลด [GitHub Desktop](https://desktop.github.com/)
2. เปิดโปรแกรม เลือก **File** > **Add Local Repository**
3. เลือกโฟลเดอร์ `c:\xampp1\htdocs\test_internship`
4. กด **Publish repository** ด้านบนขวาเพื่ออัปขึ้น GitHub ได้เลย
