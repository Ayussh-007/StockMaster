<?php
session_start();
include("db_connect.php");

$error = ""; // Error message holder

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $vu_id = $_POST['username'];
    $entered_year = $_POST['password'];  // birth year entered by student

    $query = "SELECT name, dob FROM student_details WHERE vuid = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $vu_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $dob_year = date("Y", strtotime($row['dob']));

        if ($dob_year === $entered_year) {
            $_SESSION['student_name'] = $row['name'];
            header("Location: test_portal.php");
            exit();
        } else {
            $error = "❌ Incorrect Birth Year";
        }
    } else {
        $error = "❌ VUID not found";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Student Login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f0f2f5;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }
    .login-box {
      background: white;
      padding: 40px;
      border-radius: 12px;
      box-shadow: 0 0 15px rgba(0,0,0,0.1);
      text-align: center;
      width: 100%;
      max-width: 400px;
    }
    .logo {
      width: 250px;
      height: 250px;
      object-fit: cover;
      margin-bottom: 20px;
    }
  </style>
</head>
<body>

<div class="login-box">
  <img src="images/bg.jpg" alt="Class Logo" class="logo">
  <h4 class="mb-4"><b>Student Login</b></h4>

  <?php if (!empty($error)) : ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
  <?php endif; ?>

  <form method="POST" action="">
    <div class="mb-3">
      <input type="text" name="username" class="form-control" placeholder="Enter VUID" required>
    </div>
    <div class="mb-3">
      <input type="password" name="password" class="form-control" placeholder="Enter Birth Year" required>
    </div>
    <button type="submit" class="btn btn-primary w-100">Login</button>
  </form>
</div>

</body>
</html>
