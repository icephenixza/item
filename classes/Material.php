<?php
// Class สำหรับจัดการข้อมูลวัสดุ
// Material Management Class

require_once __DIR__ . '/../config/database.php';

class Material {
    private $conn;
    private $table_name = "materials";

    // Properties
    public $id;
    public $code;
    public $name;
    public $description;
    public $category_id;
    public $unit;
    public $price;
    public $minimum_stock;
    public $current_stock;
    public $location;
    public $status;

    public function __construct($db) {
        $this->conn = $db;
    }

    // อ่านข้อมูลวัสดุทั้งหมด
    function readAll($search = '', $category_id = '', $limit = 50, $offset = 0) {
        $query = "SELECT m.*, c.name as category_name 
                 FROM " . $this->table_name . " m
                 LEFT JOIN categories c ON m.category_id = c.id
                 WHERE m.status = 'active'";
        
        if(!empty($search)) {
            $query .= " AND (m.code LIKE :search OR m.name LIKE :search OR m.description LIKE :search)";
        }
        
        if(!empty($category_id)) {
            $query .= " AND m.category_id = :category_id";
        }
        
        $query .= " ORDER BY m.code ASC LIMIT :limit OFFSET :offset";
        
        $stmt = $this->conn->prepare($query);
        
        if(!empty($search)) {
            $search_term = "%{$search}%";
            $stmt->bindParam(':search', $search_term);
        }
        
        if(!empty($category_id)) {
            $stmt->bindParam(':category_id', $category_id);
        }
        
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt;
    }

    // นับจำนวนวัสดุทั้งหมด
    function countAll($search = '', $category_id = '') {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " m
                 WHERE m.status = 'active'";
        
        if(!empty($search)) {
            $query .= " AND (m.code LIKE :search OR m.name LIKE :search OR m.description LIKE :search)";
        }
        
        if(!empty($category_id)) {
            $query .= " AND m.category_id = :category_id";
        }
        
        $stmt = $this->conn->prepare($query);
        
        if(!empty($search)) {
            $search_term = "%{$search}%";
            $stmt->bindParam(':search', $search_term);
        }
        
        if(!empty($category_id)) {
            $stmt->bindParam(':category_id', $category_id);
        }
        
        $stmt->execute();
        $row = $stmt->fetch();
        return $row['total'];
    }

    // อ่านข้อมูลวัสดุตาม ID
    function readOne() {
        $query = "SELECT m.*, c.name as category_name 
                 FROM " . $this->table_name . " m
                 LEFT JOIN categories c ON m.category_id = c.id
                 WHERE m.id = :id LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();

        $row = $stmt->fetch();

        if($row) {
            $this->code = $row['code'];
            $this->name = $row['name'];
            $this->description = $row['description'];
            $this->category_id = $row['category_id'];
            $this->unit = $row['unit'];
            $this->price = $row['price'];
            $this->minimum_stock = $row['minimum_stock'];
            $this->current_stock = $row['current_stock'];
            $this->location = $row['location'];
            $this->status = $row['status'];
            return true;
        }
        return false;
    }

    // เพิ่มวัสดุใหม่
    function create() {
        $query = "INSERT INTO " . $this->table_name . "
                 SET code=:code, name=:name, description=:description,
                     category_id=:category_id, unit=:unit, price=:price,
                     minimum_stock=:minimum_stock, current_stock=:current_stock,
                     location=:location, status=:status";

        $stmt = $this->conn->prepare($query);

        // Clean data
        $this->code = htmlspecialchars(strip_tags($this->code));
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->unit = htmlspecialchars(strip_tags($this->unit));
        $this->location = htmlspecialchars(strip_tags($this->location));

        // Bind data
        $stmt->bindParam(':code', $this->code);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':category_id', $this->category_id);
        $stmt->bindParam(':unit', $this->unit);
        $stmt->bindParam(':price', $this->price);
        $stmt->bindParam(':minimum_stock', $this->minimum_stock);
        $stmt->bindParam(':current_stock', $this->current_stock);
        $stmt->bindParam(':location', $this->location);
        $stmt->bindParam(':status', $this->status);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // แก้ไขข้อมูลวัสดุ
    function update() {
        $query = "UPDATE " . $this->table_name . "
                 SET code=:code, name=:name, description=:description,
                     category_id=:category_id, unit=:unit, price=:price,
                     minimum_stock=:minimum_stock, location=:location,
                     status=:status, updated_at=NOW()
                 WHERE id=:id";

        $stmt = $this->conn->prepare($query);

        // Clean data
        $this->code = htmlspecialchars(strip_tags($this->code));
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->unit = htmlspecialchars(strip_tags($this->unit));
        $this->location = htmlspecialchars(strip_tags($this->location));

        // Bind data
        $stmt->bindParam(':code', $this->code);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':category_id', $this->category_id);
        $stmt->bindParam(':unit', $this->unit);
        $stmt->bindParam(':price', $this->price);
        $stmt->bindParam(':minimum_stock', $this->minimum_stock);
        $stmt->bindParam(':location', $this->location);
        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':id', $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // ลบวัสดุ (อัพเดทสถานะเป็น inactive)
    function delete() {
        $query = "UPDATE " . $this->table_name . " 
                 SET status='inactive', updated_at=NOW() 
                 WHERE id=:id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // อัพเดทจำนวนสต็อก
    function updateStock($material_id, $quantity, $movement_type = 'adjust') {
        try {
            $this->conn->beginTransaction();

            // อ่านสต็อกปัจจุบัน
            $query = "SELECT current_stock FROM " . $this->table_name . " WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $material_id);
            $stmt->execute();
            $row = $stmt->fetch();
            
            if(!$row) {
                throw new Exception("ไม่พบข้อมูลวัสดุ");
            }

            $current_stock = $row['current_stock'];
            $new_stock = $current_stock + $quantity;

            if($new_stock < 0) {
                throw new Exception("จำนวนสต็อกไม่เพียงพอ");
            }

            // อัพเดทสต็อก
            $query = "UPDATE " . $this->table_name . " 
                     SET current_stock = :new_stock, updated_at = NOW() 
                     WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':new_stock', $new_stock);
            $stmt->bindParam(':id', $material_id);
            $stmt->execute();

            // บันทึกประวัติการเคลื่อนไหว
            $query = "INSERT INTO material_movements 
                     (material_id, movement_type, quantity, balance_before, balance_after, movement_date, created_by)
                     VALUES (:material_id, :movement_type, :quantity, :balance_before, :balance_after, NOW(), :created_by)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':material_id', $material_id);
            $stmt->bindParam(':movement_type', $movement_type);
            $stmt->bindParam(':quantity', $quantity);
            $stmt->bindParam(':balance_before', $current_stock);
            $stmt->bindParam(':balance_after', $new_stock);
            $stmt->bindParam(':created_by', $_SESSION['user_id'] ?? 1);
            $stmt->execute();

            $this->conn->commit();
            return true;

        } catch(Exception $e) {
            $this->conn->rollback();
            return false;
        }
    }

    // ตรวจสอบสต็อกต่ำ
    function getLowStockItems() {
        $query = "SELECT m.*, c.name as category_name 
                 FROM " . $this->table_name . " m
                 LEFT JOIN categories c ON m.category_id = c.id
                 WHERE m.current_stock <= m.minimum_stock 
                 AND m.status = 'active'
                 ORDER BY (m.current_stock - m.minimum_stock) ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // ตรวจสอบรหัสวัสดุซ้ำ
    function codeExists($code, $exclude_id = null) {
        $query = "SELECT id FROM " . $this->table_name . " WHERE code = :code";
        
        if($exclude_id) {
            $query .= " AND id != :exclude_id";
        }

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':code', $code);
        
        if($exclude_id) {
            $stmt->bindParam(':exclude_id', $exclude_id);
        }
        
        $stmt->execute();
        
        return $stmt->rowCount() > 0;
    }

    // สร้างรหัสวัสดุอัตโนมัติ
    function generateMaterialCode($category_id) {
        // รูปแบบ: CategoryPrefix + Year + Month + Running Number
        $prefix = 'MAT';
        
        // หาหมวดหมู่เพื่อสร้าง prefix
        $query = "SELECT name FROM categories WHERE id = :category_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':category_id', $category_id);
        $stmt->execute();
        $row = $stmt->fetch();
        
        if($row) {
            $prefix = strtoupper(substr($row['name'], 0, 3));
        }

        $year_month = date('Ym');
        
        // หา running number
        $query = "SELECT MAX(CAST(SUBSTRING(code, -3) AS UNSIGNED)) as max_num 
                 FROM " . $this->table_name . " 
                 WHERE code LIKE :pattern";
        $pattern = $prefix . $year_month . '%';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':pattern', $pattern);
        $stmt->execute();
        $row = $stmt->fetch();
        
        $next_num = ($row['max_num'] ?? 0) + 1;
        
        return $prefix . $year_month . sprintf('%03d', $next_num);
    }
}
?>