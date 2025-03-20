<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/login.css" rel="stylesheet">
</head>

<body>
    <div class="container-fluid vh-100 d-flex align-items-center justify-content-center">
        <div class="row w-100 h-100">
            <!-- Login Section (70%) -->
            <div class="col-md-7 bg-white d-flex flex-column align-items-center justify-content-center p-5">
                <img src="../assets/logo.png" alt="Logo" width="180" class="mb-3">
                <h2 class="text-primary fw-bold">easytickIT</h2>
                <p class="text-muted">Events made easy.</p>

                <!-- Display Errors (if any) -->
                <?php if (isset($_SESSION["login_error"])): ?>
                    <div class="alert alert-danger"><?= $_SESSION["login_error"]; ?></div>
                    <?php unset($_SESSION["login_error"]); ?>
                <?php endif; ?>

                <!-- Login Form -->
                <form action="../includes/login.inc.php" method="POST" class="w-75">
                    <input type="email" name="email" class="form-control mb-3" placeholder="Institutional Email"
                        style="height: 50px;" required>
                    <input type="password" name="password" class="form-control mb-3" placeholder="Password"
                        style="height: 50px;" required>
                    <a href="#" class="text-primary text-decoration-none mb-3">Forgot Password?</a>
                    <button type="submit" class="btn btn-orange w-100 py-2">Log In</button>
                </form>
            </div>

            <!-- Register Section (30%) -->
            <div class="col-md-5 d-flex flex-column align-items-center justify-content-center text-white"
                style="background: linear-gradient(135deg, #008f9a, #007583);">
                <h2 class="fw-bold">New Here?</h2>
                <button class="btn btn-orange mt-3 px-4 py-2" onclick="window.location.href='register.php';">
                    Register
                </button>
            </div>
        </div>
    </div>
</body>

</html>
