<?php
// ============================================
// API.PHP - Backend for Stock Market Signup
// Database: stock_market
// Table: login_credentials
// ============================================

// Show errors (helpful for debugging)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set JSON headers
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// ============================================
// DATABASE CONNECTION
// ============================================
$host = 'localhost';
$dbname = 'stock_master';    // Your database name
$username = 'root';           // Default XAMPP username
$password = '';               // Default XAMPP has no password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed: ' . $e->getMessage()]);
    exit;
}

// ============================================
// GET ACTION FROM URL
// ============================================
$action = isset($_GET['action']) ? $_GET['action'] : '';

// ============================================
// HANDLE DIFFERENT ACTIONS
// ============================================

if ($action === 'check-login-id') {
    // --------------------------------------------
    // CHECK IF LOGIN ID EXISTS
    // URL: api.php?action=check-login-id&login_id=xxxxx
    // --------------------------------------------
    
    $loginId = isset($_GET['login_id']) ? trim($_GET['login_id']) : '';
    
    if (empty($loginId)) {
        echo json_encode(['error' => 'Login ID is required']);
        exit;
    }
    
    $stmt = $pdo->prepare("SELECT id FROM login_credentials WHERE login_id = ?");
    $stmt->execute([$loginId]);
    $exists = $stmt->fetch() ? true : false;
    
    echo json_encode(['exists' => $exists]);
    exit;
}

if ($action === 'check-email') {
    // --------------------------------------------
    // CHECK IF EMAIL EXISTS
    // URL: api.php?action=check-email&email=xxxxx
    // --------------------------------------------
    
    $email = isset($_GET['email']) ? trim($_GET['email']) : '';
    
    if (empty($email)) {
        echo json_encode(['error' => 'Email is required']);
        exit;
    }
    
    $stmt = $pdo->prepare("SELECT id FROM login_credentials WHERE email_id = ?");
    $stmt->execute([$email]);
    $exists = $stmt->fetch() ? true : false;
    
    echo json_encode(['exists' => $exists]);
    exit;
}

if ($action === 'register') {
    // --------------------------------------------
    // REGISTER NEW USER
    // URL: api.php?action=register (POST request)
    // --------------------------------------------
    
    // Get JSON data from request body
    $jsonData = file_get_contents('php://input');
    $data = json_decode($jsonData, true);
    
    $loginId = isset($data['loginId']) ? trim($data['loginId']) : '';
    $email = isset($data['email']) ? trim($data['email']) : '';
    $pwd = isset($data['password']) ? $data['password'] : '';
    
    // ---- VALIDATION ----
    $errors = [];
    
    // Login ID validation
    if (empty($loginId)) {
        $errors[] = 'Login ID is required';
    } elseif (strlen($loginId) < 6 || strlen($loginId) > 12) {
        $errors[] = 'Login ID must be 6-12 characters';
    }
    
    // Email validation
    if (empty($email)) {
        $errors[] = 'Email is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format';
    }
    
    // Password validation
    if (empty($pwd)) {
        $errors[] = 'Password is required';
    } else {
        if (strlen($pwd) < 8) {
            $errors[] = 'Password must be at least 8 characters';
        }
        if (!preg_match('/[a-z]/', $pwd)) {
            $errors[] = 'Password must contain a lowercase letter';
        }
        if (!preg_match('/[A-Z]/', $pwd)) {
            $errors[] = 'Password must contain an uppercase letter';
        }
        if (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $pwd)) {
            $errors[] = 'Password must contain a special character';
        }
    }
    
    // Return errors if any
    if (!empty($errors)) {
        http_response_code(400);
        echo json_encode(['error' => implode(', ', $errors)]);
        exit;
    }
    
    // ---- CHECK FOR DUPLICATES ----
    $stmt = $pdo->prepare("SELECT id FROM login_credentials WHERE login_id = ?");
    $stmt->execute([$loginId]);
    if ($stmt->fetch()) {
        http_response_code(409);
        echo json_encode(['error' => 'Login ID already exists']);
        exit;
    }
    
    $stmt = $pdo->prepare("SELECT id FROM login_credentials WHERE email_id = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        http_response_code(409);
        echo json_encode(['error' => 'Email already registered']);
        exit;
    }
    
    // ---- HASH PASSWORD & INSERT ----
    $hashedPassword = password_hash($pwd, PASSWORD_DEFAULT);
    
    try {
        $stmt = $pdo->prepare("INSERT INTO login_credentials (login_id, email_id, password) VALUES (?, ?, ?)");
        $stmt->execute([$loginId, $email, $hashedPassword]);
        
        http_response_code(201);
        echo json_encode([
            'success' => true,
            'message' => 'Account created successfully!',
            'userId' => $pdo->lastInsertId()
        ]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Registration failed: ' . $e->getMessage()]);
    }
    exit;
}

// ============================================
// INVALID ACTION
// ============================================
http_response_code(400);
echo json_encode(['error' => 'Invalid action. Use: check-login-id, check-email, or register']);
?>