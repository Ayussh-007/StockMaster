<?php
// ============================================
// LOGIN_API.PHP - Login & Password Reset
// Database: stock_master
// ============================================

error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// ============================================
// DATABASE CONNECTION
// ============================================
$host = 'localhost';
$dbname = 'stock_master';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed']);
    exit;
}

$action = isset($_GET['action']) ? $_GET['action'] : '';

// ============================================
// ACTION: LOGIN
// ============================================
if ($action === 'login') {
    $jsonData = file_get_contents('php://input');
    $data = json_decode($jsonData, true);
    
    $loginId = isset($data['loginId']) ? trim($data['loginId']) : '';
    $pwd = isset($data['password']) ? $data['password'] : '';
    
    if (empty($loginId) || empty($pwd)) {
        http_response_code(400);
        echo json_encode(['error' => 'Login ID and Password are required']);
        exit;
    }
    
    // Find user by login_id
    $stmt = $pdo->prepare("SELECT id, login_id, email_id, password FROM login_credentials WHERE login_id = ?");
    $stmt->execute([$loginId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$user) {
        http_response_code(401);
        echo json_encode(['error' => 'Invalid Login ID']);
        exit;
    }
    
    // Verify password
    if (!password_verify($pwd, $user['password'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Invalid Password']);
        exit;
    }
    
    // Success!
    echo json_encode([
        'success' => true,
        'message' => 'Login successful!',
        'user' => [
            'id' => $user['id'],
            'loginId' => $user['login_id'],
            'email' => $user['email_id']
        ]
    ]);
    exit;
}

// ============================================
// ACTION: CHECK EMAIL EXISTS (for forgot password)
// ============================================
if ($action === 'check-email-exists') {
    $email = isset($_GET['email']) ? trim($_GET['email']) : '';
    
    if (empty($email)) {
        echo json_encode(['error' => 'Email required']);
        exit;
    }
    
    $stmt = $pdo->prepare("SELECT id, login_id FROM login_credentials WHERE email_id = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'exists' => $user ? true : false,
        'loginId' => $user ? $user['login_id'] : null
    ]);
    exit;
}

// ============================================
// ACTION: SEND OTP
// ============================================
if ($action === 'send-otp') {
    $jsonData = file_get_contents('php://input');
    $data = json_decode($jsonData, true);
    
    $email = isset($data['email']) ? trim($data['email']) : '';
    
    if (empty($email)) {
        http_response_code(400);
        echo json_encode(['error' => 'Email is required']);
        exit;
    }
    
    // Check if email exists
    $stmt = $pdo->prepare("SELECT id FROM login_credentials WHERE email_id = ?");
    $stmt->execute([$email]);
    if (!$stmt->fetch()) {
        http_response_code(404);
        echo json_encode(['error' => 'Email not registered']);
        exit;
    }
    
    // Generate 6-digit OTP
    $otp = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
    $expiresAt = date('Y-m-d H:i:s', strtotime('+10 minutes'));
    
    // Delete old OTPs for this email
    $stmt = $pdo->prepare("DELETE FROM password_reset_otp WHERE email_id = ?");
    $stmt->execute([$email]);
    
    // Insert new OTP
    $stmt = $pdo->prepare("INSERT INTO password_reset_otp (email_id, otp, expires_at) VALUES (?, ?, ?)");
    $stmt->execute([$email, $otp, $expiresAt]);
    
    // ============================================
    // SEND EMAIL WITH OTP
    // ============================================
    $to = $email;
    $subject = "Password Reset OTP - Stock Market";
    $message = "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; }
            .container { padding: 20px; background: #f8f9fa; border-radius: 10px; }
            .otp { font-size: 32px; font-weight: bold; color: #f59e0b; letter-spacing: 5px; }
            .note { color: #666; font-size: 14px; }
        </style>
    </head>
    <body>
        <div class='container'>
            <h2>Password Reset Request</h2>
            <p>Your OTP for password reset is:</p>
            <p class='otp'>$otp</p>
            <p class='note'>This OTP is valid for 10 minutes.</p>
            <p class='note'>If you did not request this, please ignore this email.</p>
        </div>
    </body>
    </html>
    ";

    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=UTF-8\r\n";
    $headers .= "From: noreply@stockmarket.com\r\n";
    
    // Try to send email
    $emailSent = @mail($to, $subject, $message, $headers);
    
    // For hackathon/testing: Return OTP in response (REMOVE IN PRODUCTION!)
    echo json_encode([
        'success' => true,
        'message' => 'OTP sent to your email',
        'debug_otp' => $otp  // REMOVE THIS LINE IN PRODUCTION!
    ]);
    exit;
}

// ============================================
// ACTION: VERIFY OTP
// ============================================
if ($action === 'verify-otp') {
    $jsonData = file_get_contents('php://input');
    $data = json_decode($jsonData, true);
    
    $email = isset($data['email']) ? trim($data['email']) : '';
    $otp = isset($data['otp']) ? trim($data['otp']) : '';
    
    if (empty($email) || empty($otp)) {
        http_response_code(400);
        echo json_encode(['error' => 'Email and OTP are required']);
        exit;
    }
    
    // Find valid OTP
    $stmt = $pdo->prepare("
        SELECT id FROM password_reset_otp 
        WHERE email_id = ? AND otp = ? AND is_used = 0 AND expires_at > NOW()
    ");
    $stmt->execute([$email, $otp]);
    $otpRecord = $stmt->fetch();
    
    if (!$otpRecord) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid or expired OTP']);
        exit;
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'OTP verified successfully'
    ]);
    exit;
}

// ============================================
// ACTION: RESET PASSWORD
// ============================================
if ($action === 'reset-password') {
    $jsonData = file_get_contents('php://input');
    $data = json_decode($jsonData, true);
    
    $email = isset($data['email']) ? trim($data['email']) : '';
    $otp = isset($data['otp']) ? trim($data['otp']) : '';
    $newPassword = isset($data['newPassword']) ? $data['newPassword'] : '';
    
    if (empty($email) || empty($otp) || empty($newPassword)) {
        http_response_code(400);
        echo json_encode(['error' => 'All fields are required']);
        exit;
    }
    
    // Validate password
    if (strlen($newPassword) < 8) {
        http_response_code(400);
        echo json_encode(['error' => 'Password must be at least 8 characters']);
        exit;
    }
    if (!preg_match('/[a-z]/', $newPassword) || !preg_match('/[A-Z]/', $newPassword) || !preg_match('/[!@#$%^&*(),.?":{}|<>]/', $newPassword)) {
        http_response_code(400);
        echo json_encode(['error' => 'Password must contain lowercase, uppercase, and special character']);
        exit;
    }
    
    // Verify OTP again
    $stmt = $pdo->prepare("
        SELECT id FROM password_reset_otp 
        WHERE email_id = ? AND otp = ? AND is_used = 0 AND expires_at > NOW()
    ");
    $stmt->execute([$email, $otp]);
    $otpRecord = $stmt->fetch();
    
    if (!$otpRecord) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid or expired OTP']);
        exit;
    }
    
    // Hash new password
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    
    // Update password
    $stmt = $pdo->prepare("UPDATE login_credentials SET password = ? WHERE email_id = ?");
    $stmt->execute([$hashedPassword, $email]);
    
    // Mark OTP as used
    $stmt = $pdo->prepare("UPDATE password_reset_otp SET is_used = 1 WHERE email_id = ?");
    $stmt->execute([$email]);
    
    echo json_encode([
        'success' => true,
        'message' => 'Password reset successfully!'
    ]);
    exit;
}

// ============================================
// INVALID ACTION
// ============================================
http_response_code(400);
echo json_encode(['error' => 'Invalid action']);
?>