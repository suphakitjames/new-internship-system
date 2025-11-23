-- Create regions table
CREATE TABLE IF NOT EXISTS regions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    name_th VARCHAR(100) NOT NULL
);

-- Create provinces table
CREATE TABLE IF NOT EXISTS provinces (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    region_id INT,
    FOREIGN KEY (region_id) REFERENCES regions(id) ON DELETE CASCADE
);

-- Insert Regions
INSERT INTO regions (name, name_th) VALUES 
('North', 'ภาคเหนือ'),
('Northeast', 'ภาคตะวันออกเฉียงเหนือ'),
('Central', 'ภาคกลาง'),
('East', 'ภาคตะวันออก'),
('West', 'ภาคตะวันตก'),
('South', 'ภาคใต้');

-- Insert Provinces
-- North (ID: 1)
INSERT INTO provinces (name, region_id) VALUES 
('เชียงใหม่', 1), ('เชียงราย', 1), ('ลำปาง', 1), ('ลำพูน', 1), ('แม่ฮ่องสอน', 1), ('น่าน', 1), ('พะเยา', 1), ('แพร่', 1), ('อุตรดิตถ์', 1);

-- Northeast (ID: 2)
INSERT INTO provinces (name, region_id) VALUES 
('ขอนแก่น', 2), ('นครราชสีมา', 2), ('อุบลราชธานี', 2), ('อุดรธานี', 2), ('มหาสารคาม', 2), ('กาฬสินธุ์', 2), ('ร้อยเอ็ด', 2), ('บุรีรัมย์', 2), ('สุรินทร์', 2), ('ศรีสะเกษ', 2), ('ชัยภูมิ', 2), ('เลย', 2), ('หนองคาย', 2), ('บึงกาฬ', 2), ('นครพนม', 2), ('สกลนคร', 2), ('มุกดาหาร', 2), ('ยโสธร', 2), ('อำนาจเจริญ', 2), ('หนองบัวลำภู', 2);

-- Central (ID: 3)
INSERT INTO provinces (name, region_id) VALUES 
('กรุงเทพมหานคร', 3), ('นนทบุรี', 3), ('ปทุมธานี', 3), ('สมุทรปราการ', 3), ('สมุทรสาคร', 3), ('สมุทรสงคราม', 3), ('นครปฐม', 3), ('พระนครศรีอยุธยา', 3), ('อ่างทอง', 3), ('ลพบุรี', 3), ('สิงห์บุรี', 3), ('ชัยนาท', 3), ('สระบุรี', 3), ('นครนายก', 3), ('สุพรรณบุรี', 3), ('นครสวรรค์', 3), ('พิจิตร', 3), ('พิษณุโลก', 3), ('เพชรบูรณ์', 3), ('กำแพงเพชร', 3), ('สุโขทัย', 3), ('อุทัยธานี', 3);

-- East (ID: 4)
INSERT INTO provinces (name, region_id) VALUES 
('ชลบุรี', 4), ('ระยอง', 4), ('จันทบุรี', 4), ('ตราด', 4), ('ฉะเชิงเทรา', 4), ('ปราจีนบุรี', 4), ('สระแก้ว', 4);

-- West (ID: 5)
INSERT INTO provinces (name, region_id) VALUES 
('ราชบุรี', 5), ('กาญจนบุรี', 5), ('เพชรบุรี', 5), ('ประจวบคีรีขันธ์', 5), ('ตาก', 5);

-- South (ID: 6)
INSERT INTO provinces (name, region_id) VALUES 
('ภูเก็ต', 6), ('กระบี่', 6), ('พังงา', 6), ('ระนอง', 6), ('สุราษฎร์ธานี', 6), ('ชุมพร', 6), ('นครศรีธรรมราช', 6), ('พัทลุง', 6), ('สงขลา', 6), ('สตูล', 6), ('ตรัง', 6), ('ปัตตานี', 6), ('ยะลา', 6), ('นราธิวาส', 6);
