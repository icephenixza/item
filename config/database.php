<?php
// ไฟล์การเชื่อมต่อฐานข้อมูล
// Database Connection Configuration

class Database {
    private $host = 'localhost';
    private $db_name = 'office_material_system';
    private $username = 'root';  // เปลี่ยนตามการตั้งค่า XAMPP ของคุณ
    private $password = '';      // เปลี่ยนตามการตั้งค่า XAMPP ของคุณ
    private $port = '3306';
    public $conn;

    public function getConnection() {
        $this->conn = null;
        
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . 
                ";port=" . $this->port . 
                ";dbname=" . $this->db_name . 
                ";charset=utf8mb4", 
                $this->username, 
                $this->password,
                array(
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
                )
            );
            
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        
        return $this->conn;
    }
}

// ฟังก์ชันตรวจสอบการเชื่อมต่อ
function testConnection() {
    $database = new Database();
    $db = $database->getConnection();
    
    if($db != null) {
        echo "เชื่อมต่อฐานข้อมูลสำเร็จ!";
        return true;
    } else {
        echo "ไม่สามารถเชื่อมต่อฐานข้อมูลได้!";
        return false;
    }
}

// ฟังก์ชันสำหรับป้องกัน SQL Injection
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// ฟังก์ชันสำหรับการ pagination
function getPaginationData($page, $records_per_page, $total_records) {
    $total_pages = ceil($total_records / $records_per_page);
    $offset = ($page - 1) * $records_per_page;
    
    return array(
        'total_pages' => $total_pages,
        'current_page' => $page,
        'offset' => $offset,
        'records_per_page' => $records_per_page,
        'total_records' => $total_records
    );
}

// ฟังก์ชันสำหรับการแสดงข้อความ Alert
function showAlert($message, $type = 'info') {
    $alertClass = '';
    switch($type) {
        case 'success':
            $alertClass = 'alert-success';
            break;
        case 'error':
        case 'danger':
            $alertClass = 'alert-danger';
            break;
        case 'warning':
            $alertClass = 'alert-warning';
            break;
        default:
            $alertClass = 'alert-info';
    }
    
    return '<div class="alert ' . $alertClass . ' alert-dismissible fade show" role="alert">
                ' . $message . '
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>';
}

// ฟังก์ชันสำหรับการตรวจสอบสิทธิ์
function checkUserPermission($required_role, $user_role) {
    $roles = array('user' => 1, 'staff' => 2, 'admin' => 3);
    
    if(!isset($roles[$user_role]) || !isset($roles[$required_role])) {
        return false;
    }
    
    return $roles[$user_role] >= $roles[$required_role];
}

// ฟังก์ชันสำหรับการบันทึก Log
function writeLog($action, $user_id, $details = '') {
    $database = new Database();
    $db = $database->getConnection();
    
    if($db != null) {
        try {
            $query = "INSERT INTO system_logs (user_id, action, details, created_at) 
                     VALUES (:user_id, :action, :details, NOW())";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':action', $action);
            $stmt->bindParam(':details', $details);
            $stmt->execute();
        } catch(PDOException $e) {
            // Silent fail for logging
        }
    }
}
?>