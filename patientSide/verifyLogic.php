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


$username = $_SESSION['verify'];

if (isset($_POST['otpCode'])) {
    $otpCode = $_POST['otpCode'];
    $sql = "SELECT * FROM users WHERE userType=1 AND username=:username";
    $prep = $con->prepare($sql);
    $prep->bindParam(':username', $_SESSION['verify']);
    $prep->execute();
    $data = $prep->fetch(PDO::FETCH_ASSOC);

    $email = $data['email'];
    $fullName = $data['fullName'];
    $gender = $data['gender'];

    if ($gender == 'Male') {
        $msg = "Dear Mr.$fullName";
    } else {
        $msg = "Dear Mrs.$fullName";
    }

    $veri_code = $data['veri_code'];
    $veri_time = $data['registerd'];

    if ($otpCode == $veri_code) {
        $currentTimestamp = time();

        $otherTimestamp = strtotime($veri_time);

        $timeDifference = abs($currentTimestamp - $otherTimestamp);

        $threshold = 180;
        if ($timeDifference < $threshold) {
            $verified = true;
            $verified_code = 000000;

            $update_sql = "UPDATE users SET verificated=:verified, veri_code=:veri_code WHERE username=:username";
            $update_prep = $con->prepare($update_sql);
            $update_prep->bindParam(':verified', $verified);
            $update_prep->bindParam(':veri_code', $verified_code);
            $update_prep->bindParam(':username', $_SESSION['verify']);
            $update_prep->execute();


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
                $mail->setFrom('no@reply.com', 'online-appointment.com');
                $mail->addAddress($email, $fullName);                           //Add a recipient


                //Content
                $mail->isHTML(true);                                        //Set email format to HTML


                $mail->Subject = 'Account Verification Successful';
                $mail->Body    = "<p>
                                $gender,
                                <br>
                                We're excited to inform you that your account has been successfully verified. 
                                Thank you for completing the verification process.
                                <br><br>
                                With your verified account, you can now enjoy all the benefits and features of 
                                our platform. Feel free to explore and make the most out of your experience.
                                <br><br>
                                If you have any questions or need assistance, our support team is here to help. 
                                Simply reply to this email or visit our support page.
                                <br><br>
                                Thank you for choosing online-appointment-booking.com. We look forward to serving you!
                                <br><br>
                                Best regards,
                                <br>
                                online-appointment-booking.com
                                </p>";


                $mail->send();

                $_SESSION['username'] = $data['username'];
                $_SESSION['fullName'] = $fullName;
                $_SESSION['numri_personal'] = $data['personal_id'];

                unset($_SESSION['verify']);
                echo "verified";
            } catch (Exception $e) {
                echo "something went wrong";
            }
        } else {
            echo "expierd code";
        }
    } else {
        echo "wrong code";
    }
}
