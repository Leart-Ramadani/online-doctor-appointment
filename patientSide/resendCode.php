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
$name = $data['emri'];
$lastName = $data['mbiemri'];

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
    $mail->setFrom('no@reply.com', 'terminet-online.com');
    $mail->addAddress($email, $name . ' ' . $lastName);                           //Add a recipient


    //Content
    $mail->isHTML(true);                                        //Set email format to HTML

    $veri_code = rand(111111, 999999);
    $veri_date = date('Y-m-d');
    $veri_time = date('H:i:s');

    $mail->Subject = 'Email verification';
    $mail->Body    = "<p style='font-size: 16px;'>
                    Kodi per te verifikuar llogarin tende: <b>$veri_code</b> <br>
                    Ky kod eshte valid vetem per 02:30 minuta!
                    </p>";
    

    $mail->send();

    $sql = "UPDATE users SET veri_code=:veri_code, veri_date=:veri_date, veri_time=:veri_time WHERE username=:username";
    $prep = $con->prepare($sql);
    $prep->bindParam(':username', $_SESSION['verify']);
    $prep->bindParam(':veri_code', $veri_code);
    $prep->bindParam(':veri_date', $veri_date);
    $prep->bindParam(':veri_time', $veri_time);
    $prep->execute();

    echo "<script>
            alert('Shiko emailin tuaj per kodin verifikues!');
            window.location.replace('./emailVerification.php');
        </script>";

    exit();
    
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}




