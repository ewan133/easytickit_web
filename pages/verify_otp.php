<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #008F9A;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .otp-container {
            background: #FFFFFF;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .otp-container h2 {
            color: #007583;
        }
        .form-control {
            border: 2px solid #62C2C1;
        }
        .btn-verify {
            background-color: #F26922;
            color: white;
            border: none;
        }
        .btn-verify:hover {
            background-color: #E05B1E;
        }
    </style>
</head>
<body>
    <div class="otp-container">
        <h2>OTP Verification</h2>
        <p>Enter the OTP sent to your email</p>
        <form action="../includes/validate_otp.php" method="POST">
            <input type="text" name="otp" class="form-control mb-3" placeholder="Enter OTP" required>
            <button type="submit" name="verify_otp" class="btn btn-verify w-100 py-2">Verify OTP</button>
        </form>
    </div>
</body>
</html>