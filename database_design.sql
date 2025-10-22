
-- ตาราง หมวดหมู่วัสดุ (Material Categories)
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL COMMENT 'ชื่อหมวดหมู่',
    description TEXT COMMENT 'คำอธิบายหมวดหมู่',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) COMMENT = 'ตารางหมวดหมู่วัสดุ';

-- ตาราง แหล่งที่มา/ผู้จำหน่าย (Suppliers)
CREATE TABLE suppliers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200) NOT NULL COMMENT 'ชื่อผู้จำหน่าย/แหล่งที่มา',
    contact_person VARCHAR(100) COMMENT 'ผู้ติดต่อ',
    phone VARCHAR(20) COMMENT 'เบอร์โทรศัพท์',
    email VARCHAR(100) COMMENT 'อีเมล',
    address TEXT COMMENT 'ที่อยู่',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) COMMENT = 'ตารางผู้จำหน่าย/แหล่งที่มา';

-- ตาราง วัสดุ (Materials/Items)
CREATE TABLE materials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(20) UNIQUE NOT NULL COMMENT 'รหัสวัสดุ',
    name VARCHAR(200) NOT NULL COMMENT 'ชื่อวัสดุ',
    description TEXT COMMENT 'คำอธิบายวัสดุ',
    category_id INT COMMENT 'หมวดหมู่วัสดุ',
    unit VARCHAR(20) NOT NULL COMMENT 'หน่วย (ชิ้น, แผ่น, ริม, ขีด ฯลฯ)',
    price DECIMAL(10,2) DEFAULT 0 COMMENT 'ราคาต่อหน่วย',
    minimum_stock INT DEFAULT 0 COMMENT 'จำนวนขั้นต่ำที่ควรมีในสต็อก',
    current_stock INT DEFAULT 0 COMMENT 'จำนวนคงเหลือปัจจุบัน',
    location VARCHAR(100) COMMENT 'ตำแหน่งเก็บ',
    status ENUM('active', 'inactive') DEFAULT 'active' COMMENT 'สถานะ',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
) COMMENT = 'ตารางข้อมูลวัสดุ';

-- ตาราง พนักงาน/ผู้ใช้งาน (Users/Employees)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL COMMENT 'ชื่อผู้ใช้',
    password VARCHAR(255) NOT NULL COMMENT 'รหัสผ่าน (encrypted)',
    first_name VARCHAR(100) NOT NULL COMMENT 'ชื่อ',
    last_name VARCHAR(100) NOT NULL COMMENT 'นามสกุล',
    email VARCHAR(100) UNIQUE COMMENT 'อีเมล',
    phone VARCHAR(20) COMMENT 'เบอร์โทรศัพท์',
    department VARCHAR(100) COMMENT 'แผนก',
    position VARCHAR(100) COMMENT 'ตำแหน่ง',
    role ENUM('admin', 'staff', 'user') DEFAULT 'user' COMMENT 'สิทธิการใช้งาน',
    status ENUM('active', 'inactive') DEFAULT 'active' COMMENT 'สถานะ',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) COMMENT = 'ตารางผู้ใช้งาน';

-- ตาราง การรับวัสดุเข้าคลัง (Material Receipts)
CREATE TABLE material_receipts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    receipt_date DATE NOT NULL COMMENT 'วันที่รับวัสดุ',
    supplier_id INT COMMENT 'ผู้จำหน่าย/แหล่งที่มา',
    invoice_number VARCHAR(50) COMMENT 'เลขที่ใบแจ้งหนี้',
    total_amount DECIMAL(12,2) DEFAULT 0 COMMENT 'ยอดรวมทั้งหมด',
    notes TEXT COMMENT 'หมายเหตุ',
    received_by INT COMMENT 'ผู้รับวัสดุ',
    status ENUM('pending', 'completed', 'cancelled') DEFAULT 'pending' COMMENT 'สถานะ',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (supplier_id) REFERENCES suppliers(id) ON DELETE SET NULL,
    FOREIGN KEY (received_by) REFERENCES users(id) ON DELETE SET NULL
) COMMENT = 'ตารางการรับวัสดุเข้าคลัง';

-- ตาราง รายละเอียดการรับวัสดุ (Material Receipt Details)
CREATE TABLE material_receipt_details (
    id INT AUTO_INCREMENT PRIMARY KEY,
    receipt_id INT NOT NULL COMMENT 'รหัสการรับวัสดุ',
    material_id INT NOT NULL COMMENT 'รหัสวัสดุ',
    quantity INT NOT NULL COMMENT 'จำนวนที่รับ',
    unit_price DECIMAL(10,2) DEFAULT 0 COMMENT 'ราคาต่อหน่วย',
    total_price DECIMAL(12,2) DEFAULT 0 COMMENT 'ราคารวม',
    expiry_date DATE COMMENT 'วันหมดอายุ (ถ้ามี)',
    batch_number VARCHAR(50) COMMENT 'หมายเลขล็อต',
    notes TEXT COMMENT 'หมายเหตุ',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (receipt_id) REFERENCES material_receipts(id) ON DELETE CASCADE,
    FOREIGN KEY (material_id) REFERENCES materials(id) ON DELETE CASCADE
) COMMENT = 'ตารางรายละเอียดการรับวัสดุ';

-- ตาราง การเบิกวัสดุ (Material Withdrawals/Requests)
CREATE TABLE material_withdrawals (
    id INT AUTO_INCREMENT PRIMARY KEY,
    withdrawal_date DATE NOT NULL COMMENT 'วันที่เบิก',
    request_by INT NOT NULL COMMENT 'ผู้ขอเบิก',
    department VARCHAR(100) COMMENT 'แผนก',
    purpose TEXT COMMENT 'วัตถุประสงค์การใช้งาน',
    approved_by INT COMMENT 'ผู้อนุมัติ',
    approved_date DATETIME COMMENT 'วันที่อนุมัติ',
    status ENUM('pending', 'approved', 'rejected', 'completed') DEFAULT 'pending' COMMENT 'สถานะ',
    notes TEXT COMMENT 'หมายเหตุ',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (request_by) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (approved_by) REFERENCES users(id) ON DELETE SET NULL
) COMMENT = 'ตารางการเบิกวัสดุ';

-- ตาราง รายละเอียดการเบิกวัสดุ (Material Withdrawal Details)
CREATE TABLE material_withdrawal_details (
    id INT AUTO_INCREMENT PRIMARY KEY,
    withdrawal_id INT NOT NULL COMMENT 'รหัสการเบิกวัสดุ',
    material_id INT NOT NULL COMMENT 'รหัสวัสดุ',
    quantity_requested INT NOT NULL COMMENT 'จำนวนที่ขอเบิก',
    quantity_approved INT DEFAULT 0 COMMENT 'จำนวนที่อนุมัติ',
    quantity_issued INT DEFAULT 0 COMMENT 'จำนวนที่จ่ายจริง',
    unit_cost DECIMAL(10,2) DEFAULT 0 COMMENT 'ต้นทุนต่อหน่วย',
    total_cost DECIMAL(12,2) DEFAULT 0 COMMENT 'ต้นทุนรวม',
    notes TEXT COMMENT 'หมายเหตุ',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (withdrawal_id) REFERENCES material_withdrawals(id) ON DELETE CASCADE,
    FOREIGN KEY (material_id) REFERENCES materials(id) ON DELETE CASCADE
) COMMENT = 'ตารางรายละเอียดการเบิกวัสดุ';

-- ตาราง ประวัติการเคลื่อนไหววัสดุ (Material Movement History)
CREATE TABLE material_movements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    material_id INT NOT NULL COMMENT 'รหัสวัสดุ',
    movement_type ENUM('in', 'out', 'adjust') NOT NULL COMMENT 'ประเภทการเคลื่อนไหว',
    reference_type ENUM('receipt', 'withdrawal', 'adjustment') COMMENT 'ประเภทเอกสารอ้างอิง',
    reference_id INT COMMENT 'รหัสเอกสารอ้างอิง',
    quantity INT NOT NULL COMMENT 'จำนวน (+/-)',
    balance_before INT NOT NULL COMMENT 'ยอดคงเหลือก่อน',
    balance_after INT NOT NULL COMMENT 'ยอดคงเหลือหลัง',
    unit_cost DECIMAL(10,2) DEFAULT 0 COMMENT 'ต้นทุนต่อหน่วย',
    movement_date DATETIME NOT NULL COMMENT 'วันที่เคลื่อนไหว',
    created_by INT COMMENT 'ผู้บันทึก',
    notes TEXT COMMENT 'หมายเหตุ',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (material_id) REFERENCES materials(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
) COMMENT = 'ตารางประวัติการเคลื่อนไหววัสดุ';

-- ตาราง การตั้งค่าระบบ (System Settings)
CREATE TABLE system_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) UNIQUE NOT NULL COMMENT 'คีย์การตั้งค่า',
    setting_value TEXT COMMENT 'ค่าการตั้งค่า',
    description TEXT COMMENT 'คำอธิบาย',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) COMMENT = 'ตารางการตั้งค่าระบบ';

-- ดัชนี (Indexes) เพื่อเพิ่มประสิทธิภาพการค้นหา
CREATE INDEX idx_materials_code ON materials(code);
CREATE INDEX idx_materials_category ON materials(category_id);
CREATE INDEX idx_materials_current_stock ON materials(current_stock);
CREATE INDEX idx_receipt_date ON material_receipts(receipt_date);
CREATE INDEX idx_withdrawal_date ON material_withdrawals(withdrawal_date);
CREATE INDEX idx_withdrawal_status ON material_withdrawals(status);
CREATE INDEX idx_movement_material ON material_movements(material_id);
CREATE INDEX idx_movement_date ON material_movements(movement_date);
CREATE INDEX idx_users_username ON users(username);
CREATE INDEX idx_users_status ON users(status);

-- ข้อมูลเริ่มต้น (Initial Data)

-- เพิ่มผู้ดูแลระบบ
INSERT INTO users (username, password, first_name, last_name, email, role, status) 
VALUES ('admin', MD5('admin123'), 'Admin', 'System', 'admin@office.com', 'admin', 'active');

-- เพิ่มหมวดหมู่เริ่มต้น
INSERT INTO categories (name, description) VALUES 
('เครื่องเขียน', 'ปากกา ดินสอ ยางลบ และเครื่องเขียนต่างๆ'),
('กระดาษ', 'กระดาษ A4 กระดาษโฟโต้ กระดาษการ์ด'),
('อุปกรณ์สำนักงาน', 'ลวดเย็บกระดาษ คลิป แฟ้ม'),
('วัสดุทำความสะอาด', 'ผงซักฟอก สบู่ ผ้าเช็ด'),
('อุปกรณ์คอมพิวเตอร์', 'หมึกปริ้นเตอร์ แผ่น CD/DVD'),
('อื่นๆ', 'วัสดุอื่นๆ ที่ไม่อยู่ในหมวดหมู่ข้างต้น');

-- เพิ่มผู้จำหน่ายเริ่มต้น
INSERT INTO suppliers (name, contact_person, phone, email) VALUES 
('บริษัท นภาพรรณดีวลลภ์ จำกัด', 'คุณสมชาย', '02-123-4567', 'contact@supplier1.com'),
('ยอดนยาม', 'คุณสมหญิง', '02-234-5678', 'sales@supplier2.com'),
('ร้านเครื่องเขียนจำกัด', 'คุณสมศักดิ์', '02-345-6789', 'info@supplier3.com');

-- เพิ่มการตั้งค่าระบบเริ่มต้น
INSERT INTO system_settings (setting_key, setting_value, description) VALUES 
('company_name', 'บริษัท/หน่วยงาน', 'ชื่อบริษัทหรือหน่วยงาน'),
('system_version', '1.0', 'เวอร์ชันของระบบ'),
('low_stock_alert', '10', 'แจ้งเตือนเมื่อสต็อกต่ำกว่าจำนวนนี้'),
('auto_approve_limit', '1000', 'อนุมัติการเบิกอัตโนมัติหากมูลค่าไม่เกินจำนวนนี้');