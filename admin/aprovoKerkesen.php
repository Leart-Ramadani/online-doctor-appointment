<?php
include('../config.php');
require_once('../emailData.php');

$id = $_GET['id'];

$sql = "SELECT * FROM kerkesatanulimit WHERE id=:id";
$stm = $con->prepare($sql);
$stm->bindParam(':id', $id);
$stm->execute();
$data = $stm->fetch();

$gender_sql = "SELECT gender FROM users WHERE userType=1 AND personal_id=:personal_id";
$gender_prep = $con->prepare($gender_sql);
$gender_prep->bindParam(':personal_id', $data['numri_personal']);
$gender_prep->execute();
$gender_data = $gender_prep->fetch();

if ($gender_data['gender'] == 'Male') {
    $gjinia = 'Dear Mr.';
} else if($gender_data['gender'] == 'Female') {
    $gjinia = 'Dear Mrs.';
}


require("../patientSide/PHPMailer-master/src/Exception.php");
require("../patientSide/PHPMailer-master/src/PHPMailer.php");
require("../patientSide/PHPMailer-master/src/SMTP.php");

//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {

    $mail->SMTPDebug = 0;
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = SITE_EMAIL;            //SMTP username
    $mail->Password   = SITE_PASSWORD;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;


    $mail->setFrom('no@reply.com', 'sistemi-termineve-online.com');
    $mail->addAddress($data['email'], $data['pacienti']);


    //Content
    $mail->isHTML(true);


    $mail->Subject = 'Appointment cancelation';
    $mail->Body    = "<p'>
                    $gjinia{$data['pacienti']},
                    <br> <br>
                    We would like to inform you that your request to cancel the appointment scheduled for {$data['data']} at 
                    {$data['ora']}, due to the reason: '{$data['arsyeja_anulimit']}', has been approved.
                    <br>
                    If you have any further questions or concerns, please don't hesitate to reach out to us. We are here to assist you.
                    <br>
                    Thank you for your understanding and cooperation.
                    <br><br>
                    Sincerely, <br>
                    sistemi-termineve-online.com
                    </p>";

    $mail->send();





    $waitList = "SELECT * FROM waiting_list WHERE doctor=:doctor AND departament=:departament AND date=:date AND time=:time";
    $waitPrep = $con->prepare($waitList);
    $waitPrep->bindParam(':doctor', $data['doktori']);
    $waitPrep->bindParam(':departament', $data['departamenti']);
    $waitPrep->bindParam(':date', $data['data']);
    $waitPrep->bindParam(':time', $data['ora']);
    $waitPrep->execute();
    $waitData = $waitPrep->fetch();

    if ($waitData) {

        $patientInfo = "SELECT * FROM users WHERE userType=1 AND personal_id=:personal_id";
        $patient_prep = $con->prepare($patientInfo);
        $patient_prep->bindParam(':personal_id', $waitData['personal_id']);
        $patient_prep->execute();
        $patientData = $patient_prep->fetch();


        $ins_wait = "UPDATE terminet SET statusi='Booked', pacienti=:pacienti, numri_personal=:numri_personal,  
                email_pacientit=:email_pacientit WHERE id=:id";
        $ins_prep = $con->prepare($ins_wait);
        $ins_prep->bindParam(':id', $waitData['apointment_id']);
        $ins_prep->bindParam(':pacienti', $patientData['fullName']);
        $ins_prep->bindParam(':numri_personal', $patientData['personal_id']);
        $ins_prep->bindParam(':email_pacientit', $patientData['email']);
        $ins_prep->execute();

        $deleteWait = "DELETE FROM waiting_list WHERE id=:id";
        $delete_prep = $con->prepare($deleteWait);
        $delete_prep->bindParam(':id', $waitData['id']);
        $delete_prep->execute();

        $del_kerkesa_sql = "DELETE FROM kerkesatanulimit WHERE id=:id";
        $del_kerkesa_prep = $con->prepare($del_kerkesa_sql);
        $del_kerkesa_prep->bindParam(':id', $id);
        $del_kerkesa_prep->execute();

        $mail = new PHPMailer(true);

        try {

            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = SITE_EMAIL;            //SMTP username
            $mail->Password   = SITE_PASSWORD;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;


            $mail->setFrom('no@reply.com', 'sistemi-termineve-online.com');
            $mail->addAddress($patientData['email'], $patientData['fullName']);


            //Content
            $mail->isHTML(true);


            $mail->Subject = 'Appointment Confirmation';
            $mail->Body    = "<p>
                    $gjinia{$patientData['fullName']},
                    <br> <br>
                    We are delighted to inform you that an appointment has become available for you from the waiting list.
                    <br>
                    Appointment Details:
                    Date: {$waitData['date']}
                    Time: {$waitData['time']}
                    <br><br>
                    Please make sure to mark your calendar and arrive on time for your appointment.
                    <br> <br>
                    If you have any further questions or require assistance, please feel free to contact us.
                    <br> <br>
                    Best regards, <br>
                    sistemi-termineve-online.com
                    </p>";

            $mail->send();

            echo "<script>
                alert('Request approved.');
                window.location.replace('kerkesatAnulimit.php');
            </script>";
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    } else {

        $del_sql = "DELETE FROM terminet WHERE doktori=:doktori AND 
                pacienti=:pacienti AND 
                numri_personal=:numri_personal AND
                data=:data AND ora=:ora";
        $del_prep = $con->prepare($del_sql);
        $del_prep->bindParam(':doktori', $data['doktori']);
        $del_prep->bindParam(':pacienti', $data['pacienti']);
        $del_prep->bindParam(':numri_personal', $data['numri_personal']);
        $del_prep->bindParam(':data', $data['data']);
        $del_prep->bindParam(':ora', $data['ora']);

        $del_kerkesa_sql = "DELETE FROM kerkesatanulimit WHERE id=:id";
        $del_kerkesa_prep = $con->prepare($del_kerkesa_sql);
        $del_kerkesa_prep->bindParam(':id', $id);
        $del_kerkesa_prep->execute();
        echo "<script>
                alert('Request approved.');
                window.location.replace('kerkesatAnulimit.php');
            </script>";
    }
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
