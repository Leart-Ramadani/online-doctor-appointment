<?php

include('../config.php');
require_once('../emailData.php');

require("./PHPMailer-master/src/Exception.php");
require("./PHPMailer-master/src/PHPMailer.php");
require("./PHPMailer-master/src/SMTP.php");
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$query = "SELECT * FROM users WHERE username=:username";
$query_prep = $con->prepare($query);
$query_prep->bindParam(':username', $_SESSION['verify']);
$query_prep->execute();
$data = $query_prep->fetch();

$email = $data['email'];
$fullName = $data['fullName'];

if($data['gender'] == 'Male'){
    $gender = "Dear Mr.$fullName";
} else {
    $gender = "Dear Mrs.$fullName";
}

$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = 0;                                       //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                       //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = SITE_EMAIL;                 //SMTP username
    $mail->Password   = SITE_PASSWORD;                 //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         //Enable implicit TLS encryption
    $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('no@reply.com', 'online-appointment.com');
    $mail->addAddress($email, $fullName);                           //Add a recipient


    //Content
    $mail->isHTML(true);                                        //Set email format to HTML

    $veri_code = rand(111111, 999999);

    $mail->Subject = 'Your Account Verification Code';
    $mail->Body    = "<p>
                        $gender, <br><br>
                        Thank you for signing up with us. To verify your account, 
                        please use the following verification code:
                        <br>
                        Verification Code: <b>$veri_code</b>
                        <br><br>
                        Please note that this code is valid for the next 02:30 seconds. 
                        After this period, you'll need to request a new verification code 
                        if needed.
                        <br><br>
                        If you didn't initiate this account verification, please ignore 
                        this email or contact our support team immediately.
                        <br><br>
                        Best regards,
                        <br>
                        online-appointment-booking.com
                    </p>";
    

    $mail->send();

    $sql = "UPDATE users SET veri_code=:veri_code, registerd=NOW() WHERE username=:username AND userType=1";
    $prep = $con->prepare($sql);
    $prep->bindParam(':username', $_SESSION['verify']);
    $prep->bindParam(':veri_code', $veri_code);
    $prep->execute();

    echo "<script>
            alert('Check your email for verification code!');
            window.location.replace('./emailVerification.php');
        </script>";

    exit();
    
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}




