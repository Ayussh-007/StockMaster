<?php
// ============================================
// API.PHP - Unified Backend for StockMaster
// Handles: Register, Login, Forgot Password
// Database: stock_master
// Tables: login_credentials, password_reset_otp
// ============================================

// 1. CONFIGURATION
error_reporting(E_ALL);
ini_set('display_errors', 0); // Don't echo errors to screen, it breaks JSON

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// 2. DATABASE CONNECTION
$host = 'localhost';
$dbname = 'stock_master';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database connection failed']);
    exit;
}

// 3. ROUTING
$action = isset($_GET['action']) ? $_GET['action'] : '';
$input = json_decode(file_get_contents('php://input'), true);

try {
    switch ($action) {

        // ============================================================
        // ACTION: CHECK LOGIN ID (For Signup)
        // ============================================================
        case 'check-login-id':
            $loginId = $_GET['login_id'] ?? '';
            $stmt = $pdo->prepare("SELECT id FROM login_credentials WHERE login_id = ?");
            $stmt->execute([$loginId]);
            echo json_encode(['exists' => $stmt->rowCount() > 0]);
            break;

        // ============================================================
        // ACTION: CHECK EMAIL AVAILABILITY (For Signup)
        // ============================================================
        case 'check-email':
            $email = $_GET['email'] ?? '';
            $stmt = $pdo->prepare("SELECT id FROM login_credentials WHERE email_id = ?");
            $stmt->execute([$email]);
            echo json_encode(['exists' => $stmt->rowCount() > 0]);
            break;

        // ============================================================
        // ACTION: REGISTER NEW USER
        // ============================================================
        case 'register':
            $loginId = trim($input['loginId']);
            $email = trim($input['email']);
            $pwd = $input['password'];

            // Basic Validation
            if (empty($loginId) || empty($email) || empty($pwd)) {
                throw new Exception("All fields are required");
            }

            // Check Duplicates
            $stmt = $pdo->prepare("SELECT id FROM login_credentials WHERE login_id = ? OR email_id = ?");
            $stmt->execute([$loginId, $email]);
            if ($stmt->rowCount() > 0) {
                throw new Exception("Login ID or Email already exists");
            }

            // Insert
            $hashedPassword = password_hash($pwd, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO login_credentials (login_id, email_id, password) VALUES (?, ?, ?)");
            $stmt->execute([$loginId, $email, $hashedPassword]);

            echo json_encode(['success' => true, 'message' => 'Registration successful']);
            break;

        // ============================================================
        // ACTION: LOGIN USER (Missing in your previous code)
        // ============================================================
        case 'login':
            $loginId = $input['loginId'] ?? '';
            $pwd = $input['password'] ?? '';

            $stmt = $pdo->prepare("SELECT id, login_id, email_id, password FROM login_credentials WHERE login_id = ?");
            $stmt->execute([$loginId]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($pwd, $user['password'])) {
                echo json_encode([
                    'success' => true,
                    'user' => [
                        'id' => $user['id'],
                        'loginId' => $user['login_id'],
                        'email' => $user['email_id']
                    ]
                ]);
            } else {
                echo json_encode(['success' => false, 'error' => 'Invalid credentials']);
            }
            break;

        // ============================================================
        // ACTION: CHECK EMAIL EXISTS (For Forgot Password)
        // ============================================================
        case 'check-email-exists':
            $email = $_GET['email'] ?? '';
            $stmt = $pdo->prepare("SELECT id FROM login_credentials WHERE email_id = ?");
            $stmt->execute([$email]);
            echo json_encode(['exists' => $stmt->rowCount() > 0]);
            break;

        // ============================================================
        // ACTION: SEND OTP (For Forgot Password)
        // ============================================================
        case 'send-otp':
            $email = $input['email'] ?? '';

            // 1. Verify Email
            $stmt = $pdo->prepare("SELECT id FROM login_credentials WHERE email_id = ?");
            $stmt->execute([$email]);
            if ($stmt->rowCount() == 0) {
                throw new Exception("Email not registered");
            }

            // 2. Generate OTP
            $otp = rand(100000, 999999);
            $expiry = date('Y-m-d H:i:s', strtotime('+10 minutes'));

            // 3. Store in Database (Cleanup old OTPs first)
            $del = $pdo->prepare("DELETE FROM password_reset_otp WHERE email_id = ?");
            $del->execute([$email]);

            $stmt = $pdo->prepare("INSERT INTO password_reset_otp (email_id, otp, expires_at) VALUES (?, ?, ?)");
            $stmt->execute([$email, $otp, $expiry]);

            // 4. SIMULATE SENDING EMAIL (For Localhost/XAMPP)
            // This writes the OTP to a file in your project folder named 'otp_log.txt'
            $logMessage = "[" . date('Y-m-d H:i:s') . "] OTP for $email: $otp" . PHP_EOL;
            file_put_contents("otp_log.txt", $logMessage, FILE_APPEND);

            echo json_encode(['success' => true, 'message' => 'OTP generated. Check otp_log.txt']);
            break;

        // ============================================================
        // ACTION: RESET PASSWORD
        // ============================================================
        case 'reset-password':
            $email = $input['email'];
            $otp = $input['otp'];
            $newPass = $input['newPassword'];

            // 1. Validate OTP
            $stmt = $pdo->prepare("SELECT id FROM password_reset_otp WHERE email_id = ? AND otp = ? AND is_used = 0 AND expires_at > NOW()");
            $stmt->execute([$email, $otp]);

            if ($stmt->rowCount() == 0) {
                throw new Exception("Invalid or expired OTP");
            }

            $otpRecord = $stmt->fetch();

            // 2. Update Password
            $hashedPassword = password_hash($newPass, PASSWORD_DEFAULT);
            $update = $pdo->prepare("UPDATE login_credentials SET password = ? WHERE email_id = ?");
            $update->execute([$hashedPassword, $email]);

            // 3. Mark OTP as used
            $mark = $pdo->prepare("UPDATE password_reset_otp SET is_used = 1 WHERE id = ?");
            $mark->execute([$otpRecord['id']]);

            echo json_encode(['success' => true, 'message' => 'Password reset successfully']);
            break;

        // ============================================================
        // DEFAULT: ERROR
        // ============================================================
        default:
            echo json_encode(['error' => "Invalid action: $action"]);
            break;
    }

} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>