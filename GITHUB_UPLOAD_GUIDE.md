# วิธีนำ Project ขึ้น GitHub (แบบทับตัวเดิม)

ทำตามขั้นตอนต่อไปนี้ใน Terminal (cmd หรือ PowerShell):

1.  **เปิด Terminal** ในโฟลเดอร์โปรเจคนี้ (`c:\xampp1\htdocs\test_internship`)

2.  **เตรียม Git และบันทึกไฟล์ปัจจุบัน**
    พิมพ์คำสั่งทีละบรรทัด:

    ```bash
    git init
    git add .
    git commit -m "Update latest version"
    ```

3.  **ตั้งค่า Branch หลัก**

    ```bash
    git branch -M main
    ```

4.  **เชื่อมต่อกับ GitHub Repository เดิม**

    - **กรณีที่ 1: ยังไม่เคยเชื่อมต่อมาก่อน**
      ให้ใช้คำสั่งนี้ (เปลี่ยน `<URL>` เป็นลิ้งค์ GitHub ของคุณ):

      ```bash
      git remote add origin <URL_GITHUB_REPO_ของคุณ>
      ```

      _ตัวอย่าง:_ `git remote add origin https://github.com/username/my-project.git`

    - **กรณีที่ 2: เคยเชื่อมต่อแล้ว หรือต้องการเปลี่ยน Repo ปลายทาง**
      ให้ใช้คำสั่งนี้เพื่ออัพเดทลิ้งค์:
      ```bash
      git remote set-url origin <URL_GITHUB_REPO_ของคุณ>
      ```

5.  **อัพโหลดแบบทับตัวเดิม (Force Push)**
    - ⚠️ **คำเตือน:** คำสั่งนี้จะลบไฟล์และประวัติบน GitHub ที่ไม่ตรงกับในเครื่องเราออกทั้งหมด และเอาไฟล์ปัจจุบันในเครื่องเราไปทับ
    ```bash
    git push -u origin main --force
    ```

---

**สรุปคำสั่งรวดเดียว (Copy ไปแก้ URL แล้ววางได้เลย):**

```bash
git init
git add .
git commit -m "Force update"
git branch -M main
git remote remove origin
git remote add origin <ใส่_URL_GITHUB_ตรงนี้>
git push -u origin main --force
```
