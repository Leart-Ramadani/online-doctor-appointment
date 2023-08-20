<?php
include '../config.php';
include '../emailData.php';

require("../patientSide/PHPMailer-master/src/Exception.php");
require("../patientSide/PHPMailer-master/src/PHPMailer.php");
require("../patientSide/PHPMailer-master/src/SMTP.php");
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if (isset($_POST['action']) && $_POST['action'] == 'getDates') {
    $doctor = $_POST['doctor'];

    $avail_sql = "SELECT data FROM orari WHERE doktori=:doctor";
    $avail_prep = $con->prepare($avail_sql);
    $avail_prep->bindParam(':doctor', $doctor);
    $avail_prep->execute();
    $avail_data = $avail_prep->fetchAll(PDO::FETCH_ASSOC);

    $response = [];

    foreach ($avail_data as $availability) {
        $response[] = $availability['data'];
    }

    echo json_encode($response);
}


if (isset($_POST['action']) && $_POST['action'] == 'getTimes') {
    $doctor = $_POST['doctor'];
    $date = $_POST['date'];

    $time_sql = "SELECT * FROM orari WHERE doktori=:doctor AND data=:date";
    $time_prep = $con->prepare($time_sql);
    $time_prep->bindParam(':doctor', $doctor);
    $time_prep->bindParam(':date', $date);
    $time_prep->execute();
    $time_data = $time_prep->fetch(PDO::FETCH_ASSOC);


    $result = array();
    $currentTime = strtotime($time_data['nga_ora']);
    $endTime = strtotime($time_data['deri_oren']);

    while ($currentTime < $endTime) {
        $result[] = date('H:i', $currentTime);
        $currentTime = strtotime('+' . $time_data['kohezgjatja'] . ' minutes', $currentTime);
    }

    $appointments = '';
    foreach ($result as $time) {
        $checkApp = "SELECT * FROM terminet WHERE (statusi='Booked' OR statusi='In progres') AND doktori=:doktori AND data=:data AND ora=:ora";
        $appPrep = $con->prepare($checkApp);
        $appPrep->bindParam(':doktori', $time_data['doktori']);
        $appPrep->bindParam(':data', $time_data['data']);
        $appPrep->bindParam(':ora', $time);
        $appPrep->execute();
        $appData = $appPrep->fetch();


        $com = "SELECT * FROM terminet WHERE statusi='Completed' AND doktori=:doktori AND data=:data AND ora=:ora";
        $com_prep = $con->prepare($com);
        $com_prep->bindParam(':doktori', $time_data['doktori']);
        $com_prep->bindParam(':data', $time_data['data']);
        $com_prep->bindParam(':ora', $time);
        $com_prep->execute();
        $comData = $com_prep->fetch();

        if ($appData) {
            $appointments .= "<button class='btn btn-primary disabled' style='width: 75px;'  title='This appointment is booked'>{$time}</button>";
        } else if ($comData) {
            $appointments .= "<button class='btn btn-primary disabled' style='width: 75px;' title='This appointment is completed'>{$time}</button>";
        } else {
            $appointments .= "<button class='btn btn-primary' style='width: 75px;' value='{$time}' onclick='getValue(this.value)'  data-bs-toggle='modal' data-bs-target='#referenceBooking'>{$time}</button>";
        }
    }

    echo $appointments;
}


if (isset($_POST['action']) && $_POST['action'] == 'showAppointment') {
    $doctor = $_POST['doctor'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $personal_id = $_POST['personal_id'];

    $patient_sql = "SELECT fullName, personal_id FROM users WHERE userType=1 AND personal_id=:personal_id";
    $patient_prep = $con->prepare($patient_sql);
    $patient_prep->bindParam(':personal_id', $personal_id);
    $patient_prep->execute();
    $patientInfo = $patient_prep->fetch(PDO::FETCH_ASSOC);

    $doctor_sql = "SELECT 
                        u.fullName,
                        u.departament,
                        d.name AS 'departament'
                        FROM users AS u 
                        INNER JOIN departamentet AS d 
                        ON u.departament=d.id
                        WHERE u.userType=2 AND u.fullName=:doctor";
    $doctor_prep = $con->prepare($doctor_sql);
    $doctor_prep->bindParam(':doctor', $doctor);
    $doctor_prep->execute();
    $doctor_info = $doctor_prep->fetch(PDO::FETCH_ASSOC);

    $response = [
        "Doctor" => $doctor_info['fullName'],
        "Departament" => $doctor_info['departament'],
        "Patient" => $patientInfo['fullName'],
        "PersonalID" => $patientInfo['personal_id'],
        "Date" => $date,
        "Time" => $time
    ];

    echo json_encode($response);
}


if (isset($_POST['action']) && $_POST['action'] == 'bookAppointment') {
    $id = $_POST['id'];
    $doctor = $_POST['doctor'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $personal_id = $_POST['personal_id'];


    $patient_sql = "SELECT 
        fullName, 
        personal_id,
        gender,
        email 
        FROM users 
        WHERE userType=1 AND personal_id=:personal_id";
    $patient_prep = $con->prepare($patient_sql);
    $patient_prep->bindParam(':personal_id', $personal_id);
    $patient_prep->execute();
    $patientInfo = $patient_prep->fetch(PDO::FETCH_ASSOC);


    if($patientInfo['gender'] == 'Male'){
        $gender = "Dear Mr.{$patientInfo['fullName']}";
    } else{
        $gender = "Dear Mrs.{$patientInfo['fullName']}";
    }

        $doctor_sql = "SELECT 
        u.fullName,
        u.departament AS 'dep_id',
        d.name AS 'dep_name'
        FROM users AS u 
        INNER JOIN departamentet AS d 
        ON u.departament=d.id
        WHERE u.userType=2 AND u.fullName=:doctor";
    $doctor_prep = $con->prepare($doctor_sql);
    $doctor_prep->bindParam(':doctor', $doctor);
    $doctor_prep->execute();
    $doctor_info = $doctor_prep->fetch(PDO::FETCH_ASSOC);

    $terminet_sql = "INSERT INTO terminet(doktori, departamenti, pacienti, numri_personal, email_pacientit, data, ora, statusi)
    VALUES(:doktori, :departamenti, :pacienti, :numri_personal, :email_pacientit, :data, :ora, 'Booked')";
    $terminet_prep = $con->prepare($terminet_sql);
    $terminet_prep->bindParam(':doktori', $doctor);
    $terminet_prep->bindParam(':departamenti', $doctor_info['dep_id']);
    $terminet_prep->bindParam(':pacienti', $patientInfo['fullName']);
    $terminet_prep->bindParam(':numri_personal', $patientInfo['personal_id']);
    $terminet_prep->bindParam(':email_pacientit', $patientInfo['email']);
    $terminet_prep->bindParam(':data', $date);
    $terminet_prep->bindParam(':ora', $time);

    if ($terminet_prep->execute()) {

        $update_sql = "UPDATE reference SET status='Transfered'";
        $update_prep = $con->prepare($update_sql);
        $update_prep->execute();


        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->SMTPDebug = 0;                                       //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                       //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = SITE_EMAIL;                             //SMTP username
            $mail->Password   = SITE_PASSWORD;                          //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         //Enable implicit TLS encryption
            $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $mail->setFrom('no@reply.com', 'online-appointment.com');
            $mail->addAddress($patientInfo['email'], $patientInfo['fullName']);                           //Add a recipient


            //Content
            $mail->isHTML(true);                                        //Set email format to HTML


            $mail->Subject = 'Appointment Booking Confirmation';
            $mail->Body    =   "<p>
                                $gender, <br><br>
                                We are pleased to inform you that your appointment has been successfully booked. Here are the details:
                                <br>
                                Appointment Date: $date <br>
                                Appointment Time: $time <br>
                                Doctor: Dr. $doctor <br>
                                Department: {$doctor_info['dep_name']}
                                <br><br>
                                If you have any questions or need to make changes to your appointment, please feel free to contact us.
                                <br><br>
                                Thank you for choosing our online appointment system.
                                <br> <br>
                                Best regards,
                                <br>
                                online-appointment-booking.com
                            </p>";

            $mail->send();

            unset($_SESSION['id_ofApp']);
            echo "booked";
        } catch (Exception $e) {
            echo  "Problems with server or internet";
        }

        exit();
    }
}
