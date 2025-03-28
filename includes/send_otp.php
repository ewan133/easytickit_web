<?php
require_once 'session_config.inc.php';
include('../includes/dbh.inc.php'); // Database connection

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    echo "Step 1: Form submitted.<br>";

    if (!isset($_POST['send_otp'])) {
        echo "Step 2: Button name missing.<br>";
        exit();
    }

    $email = trim($_POST['email']);
    echo "Step 3: Email received: $email<br>";

    $otp = rand(100000, 999999);
    $_SESSION['otp'] = $otp;
    $_SESSION['register_data'] = $_POST; // Store form data temporarily

    require '../Mail/phpmailer/PHPMailerAutoload.php';
    $mail = new PHPMailer;

    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 587;
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = 'tls';

    $mail->Username = 'easytickit8@gmail.com';  
    $mail->Password = 'idhc cblv lxlq vzhi';  

    $mail->setFrom('easytickit8@gmail.com', 'OTP Verification');
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject = "Your OTP Verification Code";
    $mail->Body = "<p>Dear user,</p>
                    <h3>Your OTP is <b>$otp</b></h3>
                    <p>Use this code to complete your registration.</p>";

    if ($mail->send()) {
        echo "Step 4: OTP sent successfully.<br>";
        error_log("OTP sent successfully to $email"); 
        header("Location: ../pages/verify_otp.php");
        exit();
    } else {
        echo "Step 5: Error sending OTP.<br>";
        error_log("Mailer Error: " . $mail->ErrorInfo);
        $_SESSION['register_error'] = "Error sending OTP. Please try again.";
        //header("Location: ../pages/register.php");
        exit();
    }
} else {
    echo "Step 6: No form submission detected.<br>";
}
?>
