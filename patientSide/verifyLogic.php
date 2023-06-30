<?php
include('../config.php');
require_once('../emailData.php');

require "C:xampp\htdocs\E-commerce\PHPMailer-master\src\Exception.php";
require "C:xampp\htdocs\E-commerce\PHPMailer-master\src\PHPMailer.php";
require "C:xampp\htdocs\E-commerce\PHPMailer-master\src\SMTP.php";
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if (!isset($_SESSION['verify'])) {
    header("Location: signup.php");
}


$sql = "SELECT * FROM patient_table WHERE username = :username";
$prep = $con->prepare($sql);
$prep->bindParam(':username', $_SESSION['verify']);
$prep->execute();
$data = $prep->fetch();

$email = $data['email'];
$name = $data['emri'];
$lastName = $data['mbiemri'];
$username = $data['username'];


$otp = $_POST['otp'];

$veri_code = $data['veri_code'];

$veri_date = $data['veri_date'];

$veri_time = $data['veri_time'];
$time = new DateTime($veri_time);
$time->add(new DateInterval("PT0H2M30S"));
$time_format = $time->format("H:i:s");

if ($otp === $veri_code) {
    if ($veri_date === date("Y-m-d") && $veri_time <= $time_format) {
        $verificated = true;

        $ver_sql = "UPDATE patient_table SET verificated=:verificated WHERE username=:username";
        $ver_prep = $con->prepare($ver_sql);
        $ver_prep->bindParam(':username', $_SESSION['verify']);
        $ver_prep->bindParam(':verificated', $verificated);
        $ver_prep->execute();



        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->SMTPDebug = 0;                                       //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                       //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = SITE_EMAIL;                 //SMTP username
            $mail->Password   = SITE_PASSWORD;                     //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         //Enable implicit TLS encryption
            $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $mail->setFrom('no@reply.com', 'terminet-online.com');
            $mail->addAddress($email, $name . ' ' . $lastName);                           //Add a recipient


            //Content
            $mail->isHTML(true);                                        //Set email format to HTML

            $veri_code = rand(111111, 999999);
            $veri_date = date('Y-m-d');
            $veri_time = date('H:i:s');

            $mail->Subject = 'Email verification';
            $mail->Body    = "<p style='font-size: 16px;'>
                    $username your account is successfully verified.
                </p>";


            $mail->send();

            echo "Your account has been successfully verified";



            exit();
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }

        unset($_SESSION['verify']);
        echo "Your account has been successfully verified";
    } else {
        echo "This code has expierd!";
    }
} else {
    echo "Wrong code. Please try again!";
}
