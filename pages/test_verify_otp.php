<!DOCTYPE html>
<html>
<body>

<h2>Test Verify OTP</h2>

<input id="email" type="email" placeholder="Enter email" style="width:300px;"><br><br>
<input id="otp" type="text" placeholder="Enter OTP" style="width:300px;"><br><br>
<button onclick="verifyOtp()">Verify OTP</button>

<pre id="result" style="margin-top:20px; background:#eee; padding:10px;"></pre>

<script>
function verifyOtp() {
    const email = document.getElementById("email").value;
    const otp   = document.getElementById("otp").value;

    fetch("http://localhost/stock_master/login_api.php?action=verify-otp", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ email, otp })
    })
    .then(res => res.json())
    .then(data => {
        document.getElementById("result").textContent = JSON.stringify(data, null, 2);
    })
    .catch(err => {
        document.getElementById("result").textContent = "Error: " + err;
    });
}//change
</script>  

</body>
</html>
