<?php
$test_id = $_GET['test_id'] ?? '';

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
      position: relative;
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
      width: 300px;
      height: 300px;
      object-fit: cover;
      margin-bottom: 20px;
    }
    .go-back {
      position: absolute;
      bottom: 20px;
      right: 20px;
    }
  </style>
  <style>
    /* Fullscreen white background */
    #loader {
      position: fixed;
      width: 100%;
      height: 100%;
      background-color: #ffffff;
      z-index: 9999;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    /* Cradle fade-in animation */
    .pendulum {
      width: 220px;
      height: 180px;
      background-color: #f8c6cf;
      border-radius: 5%;
      border-top: 15px solid #eee7d5;
      display: flex;
      justify-content: center;
      align-items: flex-start;
      position: relative;
      opacity: 0;
      animation: fadeInCradle 1s ease forwards;
    }

    @keyframes fadeInCradle {
      to {
        opacity: 1;
      }
    }

    .pendulum_box {
      display: flex;
      padding-top: 120px;
      gap: 5px;
    }

    .ball {
      height: 40px;
      width: 40px;
      border-radius: 50%;
      background-color: #455681;
      position: relative;
      transform-origin: 50% -300%;
    }

    .ball::before {
      content: '';
      width: 2px;
      height: 120px;
      background-color: #fffeff;
      left: 50%;
      transform: translateX(-50%);
      top: -120px;
      position: absolute;
    }

    .ball.first {
      animation: firstball 0.9s alternate ease-in infinite;
    }

    @keyframes firstball {
      0% { transform: rotate(35deg); }
      50% { transform: rotate(0deg); }
    }

    .ball.last {
      animation: lastball 0.9s alternate ease-out infinite;
    }

    @keyframes lastball {
      50% { transform: rotate(0deg); }
      100% { transform: rotate(-35deg); }
    }
  </style>
</head>
<body>
<div id="loader">
  <div class="pendulum">
    <div class="pendulum_box">
      <div class="ball first"></div>
      <div class="ball"></div>
      <div class="ball"></div>
      <div class="ball"></div>
      <div class="ball last"></div>
    </div>
  </div>
</div>
  <div class="login-box">
    <img src="images/bg.jpg" alt="Class Logo" class="logo">

    <h4 class="mb-4"><b>Student Login</b></h4>

    <!-- ✅ FORM updated to send data to validate_login.php correctly -->
    <form action="validate_login.php" method="POST">
      <input type="hidden" name="test_id" value="<?php echo $test_id; ?>">

      <div class="mb-3">
        <input type="text" name="username" class="form-control" placeholder="Enter VUID" required>
      </div>
      <div class="mb-3">
        <input type="password" name="password" class="form-control" placeholder="Enter Birth Year" required>
      </div>
      <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>
  </div>

  <div class="go-back">
    <a href="javascript:history.back()" class="btn btn-outline-secondary">← Go Back</a>
  </div> 
 <script>
  // Show content after loading is complete
  window.addEventListener("load", function () {
    const loader = document.getElementById("loader");
    const mainContent = document.getElementById("mainContent");

    setTimeout(() => {
      loader.style.display = "none";
      mainContent.style.display = "block";
    }, 2500); // Simulated loading delay
  });
</script>

</body>
</html>
