<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Easy TickIT - Admin Login</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(135deg, #EF7125, #017479);
            color: white;
        }

        .login-container {
            background: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            text-align: center;
            width: 350px;
        }

        .login-container h2 {
            margin-bottom: 20px;
            color: #4AACAD;
            font-size: 24px;
        }

        .input-group {
            margin-bottom: 15px;
            text-align: left;
        }

        .input-group label {
            font-size: 14px;
            font-weight: bold;
            color: #4AACAD;
            display: block;
            margin-bottom: 5px;
        }

        .input-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #4AACAD;
            border-radius: 6px;
            font-size: 16px;
            background: white;
            color: #4AACAD;
            transition: 0.3s;
        }

        .input-group input::placeholder {
            color: #A0D9D9;
        }

        .input-group input:focus {
            border-color: #EF7125;
            outline: none;
        }

        .btn {
            width: 100%;
            padding: 12px;
            background: #EF7125;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
            font-weight: bold;
        }

        .btn:hover {
            background: #D65F1B;
        }

        .forgot-password {
            display: block;
            margin-top: 10px;
            font-size: 14px;
            color: #4AACAD;
            text-decoration: none;
        }

        .forgot-password:hover {
            text-decoration: underline;
        }

        .error {
            color: red;
            font-size: 14px;
            margin-bottom: 10px;
        }

        @media (max-width: 400px) {
            .login-container {
                width: 90%;
                padding: 20px;
            }
        }
    </style>
</head>
<body>

    <div class="login-container">
        <h2>Admin Login</h2>

        <?php
        if (isset($_SESSION["login_error"])) {
            echo '<p class="error">' . $_SESSION["login_error"] . '</p>';
            unset($_SESSION["login_error"]); // Remove error message after displaying
        }
        ?>

        <form action="includes/admin_login.inc.php" method="POST">
            <div class="input-group">
                <label for="email">Admin Email</label>
                <input type="email" id="email" name="email" placeholder="Enter admin email" required>
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter password" required>
            </div>
            <button type="submit" class="btn">Login</button>
            <a href="#" class="forgot-password">Forgot password?</a>
        </form>
    </div>

</body>
</html>
