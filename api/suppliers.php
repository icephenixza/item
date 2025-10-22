<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../config/database.php';

class SupplierAPI {
    private $conn;
    private $table = 'suppliers';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function list($search = '', $status = '', $page = 1, $limit = 10) {
        try {
            $offset = ($page - 1) * $limit;
            
            // Build base query
            $whereConditions = [];
            $params = [];
            
            if (!empty($search)) {
                $whereConditions[] = "(name LIKE ? OR code LIKE ? OR contact_person LIKE ? OR phone LIKE ?)";
                $searchTerm = "%$search%";
                $params = array_merge($params, [$searchTerm, $searchTerm, $searchTerm, $searchTerm]);
            }
            
            if (!empty($status)) {
                $whereConditions[] = "status = ?";
                $params[] = $status;
            }
            
            $whereClause = !empty($whereConditions) ? 'WHERE ' . implode(' AND ', $whereConditions) : '';
            
            // Count total records
            $countQuery = "SELECT COUNT(*) as total FROM {$this->table} $whereClause";
            $countStmt = $this->conn->prepare($countQuery);
            
            if (!empty($params)) {
                $countStmt->execute($params);
            } else {
                $countStmt->execute();
            }
            
            $totalRecords = $countStmt->fetchColumn();
            $totalPages = ceil($totalRecords / $limit);
            
            // Get paginated data
            $query = "SELECT * FROM {$this->table} $whereClause ORDER BY created_at DESC LIMIT ? OFFSET ?";
            $stmt = $this->conn->prepare($query);
            
            $allParams = array_merge($params, [$limit, $offset]);
            $stmt->execute($allParams);
            
            $suppliers = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return [
                'status' => 'success',
                'data' => $suppliers,
                'pagination' => [
                    'current_page' => (int)$page,
                    'total_pages' => (int)$totalPages,
                    'total_records' => (int)$totalRecords,
                    'limit' => (int)$limit
                ]
            ];
            
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => 'เกิดข้อผิดพลาดในการดึงข้อมูล: ' . $e->getMessage()
            ];
        }
    }

    public function get($id) {
        try {
            $query = "SELECT * FROM {$this->table} WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$id]);
            
            $supplier = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($supplier) {
                return [
                    'status' => 'success',
                    'data' => $supplier
                ];
            } else {
                return [
                    'status' => 'error',
                    'message' => 'ไม่พบข้อมูลผู้จำหน่าย'
                ];
            }
            
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => 'เกิดข้อผิดพลาดในการดึงข้อมูล: ' . $e->getMessage()
            ];
        }
    }

    public function create($data) {
        try {
            // Check if code already exists
            $checkQuery = "SELECT id FROM {$this->table} WHERE code = ?";
            $checkStmt = $this->conn->prepare($checkQuery);
            $checkStmt->execute([$data['code']]);
            
            if ($checkStmt->fetch()) {
                return [
                    'status' => 'error',
                    'message' => 'รหัสผู้จำหน่ายนี้มีอยู่แล้ว'
                ];
            }
            
            $query = "INSERT INTO {$this->table} 
                     (code, name, contact_person, phone, email, website, address, notes, status, created_at) 
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
            
            $stmt = $this->conn->prepare($query);
            $result = $stmt->execute([
                $data['code'],
                $data['name'],
                $data['contact_person'] ?? null,
                $data['phone'] ?? null,
                $data['email'] ?? null,
                $data['website'] ?? null,
                $data['address'] ?? null,
                $data['notes'] ?? null,
                $data['status'] ?? 'active'
            ]);
            
            if ($result) {
                return [
                    'status' => 'success',
                    'message' => 'เพิ่มข้อมูลผู้จำหน่ายเรียบร้อยแล้ว',
                    'id' => $this->conn->lastInsertId()
                ];
            } else {
                return [
                    'status' => 'error',
                    'message' => 'เกิดข้อผิดพลาดในการเพิ่มข้อมูล'
                ];
            }
            
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => 'เกิดข้อผิดพลาดในการเพิ่มข้อมูล: ' . $e->getMessage()
            ];
        }
    }

    public function update($id, $data) {
        try {
            // Check if code already exists (excluding current record)
            $checkQuery = "SELECT id FROM {$this->table} WHERE code = ? AND id != ?";
            $checkStmt = $this->conn->prepare($checkQuery);
            $checkStmt->execute([$data['code'], $id]);
            
            if ($checkStmt->fetch()) {
                return [
                    'status' => 'error',
                    'message' => 'รหัสผู้จำหน่ายนี้มีอยู่แล้ว'
                ];
            }
            
            $query = "UPDATE {$this->table} SET 
                     code = ?, name = ?, contact_person = ?, phone = ?, email = ?, 
                     website = ?, address = ?, notes = ?, status = ?, updated_at = NOW() 
                     WHERE id = ?";
            
            $stmt = $this->conn->prepare($query);
            $result = $stmt->execute([
                $data['code'],
                $data['name'],
                $data['contact_person'] ?? null,
                $data['phone'] ?? null,
                $data['email'] ?? null,
                $data['website'] ?? null,
                $data['address'] ?? null,
                $data['notes'] ?? null,
                $data['status'] ?? 'active',
                $id
            ]);
            
            if ($result) {
                return [
                    'status' => 'success',
                    'message' => 'อัพเดทข้อมูลผู้จำหน่ายเรียบร้อยแล้ว'
                ];
            } else {
                return [
                    'status' => 'error',
                    'message' => 'เกิดข้อผิดพลาดในการอัพเดทข้อมูล'
                ];
            }
            
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => 'เกิดข้อผิดพลาดในการอัพเดทข้อมูล: ' . $e->getMessage()
            ];
        }
    }

    public function delete($id) {
        try {
            // Check if supplier is referenced in other tables (optional)
            // You might want to check material_receipts table if it exists
            
            $query = "DELETE FROM {$this->table} WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            $result = $stmt->execute([$id]);
            
            if ($result && $stmt->rowCount() > 0) {
                return [
                    'status' => 'success',
                    'message' => 'ลบข้อมูลผู้จำหน่ายเรียบร้อยแล้ว'
                ];
            } else {
                return [
                    'status' => 'error',
                    'message' => 'ไม่พบข้อมูลผู้จำหน่ายที่ต้องการลบ'
                ];
            }
            
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => 'เกิดข้อผิดพลาดในการลบข้อมูล: ' . $e->getMessage()
            ];
        }
    }
}

// Handle request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Create database connection
        $database = new Database();
        $db = $database->getConnection();
        
        if (!$db) {
            throw new Exception('ไม่สามารถเชื่อมต่อฐานข้อมูลได้');
        }
        
        $api = new SupplierAPI($db);
        $action = $_POST['action'] ?? '';
        
        switch ($action) {
            case 'list':
                $search = $_POST['search'] ?? '';
                $status = $_POST['status'] ?? '';
                $page = (int)($_POST['page'] ?? 1);
                $limit = (int)($_POST['limit'] ?? 10);
                
                $response = $api->list($search, $status, $page, $limit);
                break;
                
            case 'get':
                $id = (int)($_POST['id'] ?? 0);
                if ($id <= 0) {
                    throw new Exception('รหัสผู้จำหน่ายไม่ถูกต้อง');
                }
                
                $response = $api->get($id);
                break;
                
            case 'create':
                $requiredFields = ['code', 'name'];
                foreach ($requiredFields as $field) {
                    if (empty($_POST[$field])) {
                        throw new Exception("กรุณากรอก{$field}");
                    }
                }
                
                $response = $api->create($_POST);
                break;
                
            case 'update':
                $id = (int)($_POST['id'] ?? 0);
                if ($id <= 0) {
                    throw new Exception('รหัสผู้จำหน่ายไม่ถูกต้อง');
                }
                
                $requiredFields = ['code', 'name'];
                foreach ($requiredFields as $field) {
                    if (empty($_POST[$field])) {
                        throw new Exception("กรุณากรอก{$field}");
                    }
                }
                
                $response = $api->update($id, $_POST);
                break;
                
            case 'delete':
                $id = (int)($_POST['id'] ?? 0);
                if ($id <= 0) {
                    throw new Exception('รหัสผู้จำหน่ายไม่ถูกต้อง');
                }
                
                $response = $api->delete($id);
                break;
                
            default:
                throw new Exception('Action ไม่ถูกต้อง');
        }
        
    } catch (Exception $e) {
        $response = [
            'status' => 'error',
            'message' => $e->getMessage()
        ];
    }
} else {
    $response = [
        'status' => 'error',
        'message' => 'Method ไม่ถูกต้อง'
    ];
}

echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>