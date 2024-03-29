<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('../config.php');
require_once('../emailData.php');
if (!isset($_SESSION['fullName'])) {
    header("Location: login.php");
}

$sql = "SELECT fullName, personal_id, email, phone FROM users WHERE userType=1 AND personal_id=:personal_id";
$prep = $con->prepare($sql);
$prep->bindParam(':personal_id', $_SESSION['numri_personal']);
$prep->execute();
$patient_data = $prep->fetch();

?>





    <?php

    require("./PHPMailer-master/src/Exception.php");
    require("./PHPMailer-master/src/PHPMailer.php");
    require("./PHPMailer-master/src/SMTP.php");
    //Import PHPMailer classes into the global namespace
    //These must be at the top of your script, not inside a function

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    if (isset($_POST['checking_viewbtn'])) {
        $_SESSION['id_ofApp'] = $_POST['id'];

        $id = $_SESSION['id_ofApp'];
        $sql = "SELECT * FROM orari WHERE id=:id";
        $doc_prep = $con->prepare($sql);
        $doc_prep->bindParam(':id', $id);
        $doc_prep->execute();
        $row = $doc_prep->fetch();


        $sql_doc = "SELECT fullName, departament FROM users WHERE userType=2 AND fullName=:doktori";
        $stm = $con->prepare($sql_doc);
        $stm->bindParam(':doktori', $row['doktori']);
        $stm->execute();
        $data = $stm->fetch();


        $sql = "SELECT o.id, o.doktori, o.departamenti, o.data, o.nga_ora, o.deri_oren, o.kohezgjatja, o.zene_deri, d.name AS 'dep_name' 
            FROM orari AS o INNER JOIN departamentet AS d ON o.departamenti = d.id WHERE o.id=:id";
        $doc_prep = $con->prepare($sql);
        $doc_prep->bindParam(':id', $_SESSION['id_ofApp']);
        $doc_prep->execute();
        $row = $doc_prep->fetch();

        $result = array();
        $currentTime = strtotime($row['nga_ora']);
        $endTime = strtotime($row['deri_oren']);

        while ($currentTime < $endTime) {
            $result[] = date('H:i', $currentTime);
            $currentTime = strtotime('+' . $row['kohezgjatja'] . ' minutes', $currentTime);
        }


        $appointments = '';
        foreach ($result as $time) {
            $checkApp = "SELECT * FROM terminet WHERE (statusi='Booked' OR statusi='In progres') AND doktori=:doktori AND data=:data AND ora=:ora";
            $appPrep = $con->prepare($checkApp);
            $appPrep->bindParam(':doktori', $row['doktori']);
            $appPrep->bindParam(':data', $row['data']);
            $appPrep->bindParam(':ora', $time);
            $appPrep->execute();
            $appData = $appPrep->fetch();


            $com = "SELECT * FROM terminet WHERE statusi='Completed' AND doktori=:doktori AND data=:data AND ora=:ora";
            $com_prep = $con->prepare($com);
            $com_prep->bindParam(':doktori', $row['doktori']);
            $com_prep->bindParam(':data', $row['data']);
            $com_prep->bindParam(':ora', $time);
            $com_prep->execute();
            $comData = $com_prep->fetch();

            if ($appData) {
                $appointments .= "<button class='btn btn-danger' style='width: 80px;' value='{$time}' onclick='waitList(this.value)' data-bs-toggle='modal' data-bs-target='#staticBackdrop' title='This appointment is booked'>{$time}</button>";
            } else if ($comData) {
                $appointments .= "<button class='btn btn-primary disabled' style='width: 80px;' title='This appointment is completed'>{$time}</button>";
            } else {
                $appointments .= "<button class='btn btn-primary' style='width: 80px;' value='{$time}' onclick='getValue(this.value)'>{$time}</button>";
            }
        }


        $date = date_create($row['data']);
        $date = date_format($date, "d/m/Y, D");

        echo $return = "
            <p>Doctor: <span class='doc_name'>{$row['doktori']}</span></p>
            <hr>
            <p>Departament: <span class='doc_dep'>{$row['dep_name']}</span></p>
            <hr>
            <p>Appointment Date: <span class='app_date'>{$date}</span></p>
            <hr>
            <p>Duration: <span class='app_dur'>{$row['kohezgjatja']}min</span></p>
            <hr>
            <p>Time: <span class='appTime'></span></p>
            <hr>
            <div class='d-flex flex-wrap gap-2 pt-2 pb-2' style='height: 200px; overflow-y: scroll; padding-left: 22px;'>" . $appointments . "</div>";
    }



    if (isset($_POST['rezervo'])) {
        $id = $_SESSION['id_ofApp'];
        $time = $_POST['time'];

        $sql = "SELECT * FROM orari WHERE id=:id";
        $doc_prep = $con->prepare($sql);
        $doc_prep->bindParam(':id', $id);
        $doc_prep->execute();
        $row = $doc_prep->fetch();
        $doc = $row['doktori'];
        $data = $row['data'];

        $sql_doc = "SELECT u.fullName, u.departament, d.name AS 'dep_name' FROM users AS u INNER JOIN departamentet AS d ON u.departament = d.id
        WHERE userType=2 AND fullName=:doktori";
        $stm = $con->prepare($sql_doc);
        $stm->bindParam(':doktori', $doc);
        $stm->execute();
        $docInfo = $stm->fetch();

        $doktori = $docInfo['fullName'];
        $dep = $docInfo['dep_name'];


        $sql = "SELECT fullName, gender, personal_id, email, phone FROM users WHERE userType=1 AND personal_id=:personal_id";
        $prep = $con->prepare($sql);
        $prep->bindParam(':personal_id', $_SESSION['numri_personal']);
        $prep->execute();
        $patient_data = $prep->fetch();

        $pacienti = $patient_data['fullName'];
        $numri_personal = $patient_data['personal_id'];
        $email_pacientit = $patient_data['email'];

        if ($patient_data['gender'] == 'Male') {
            $gender = "Dear Mr.$pacienti";
        } else {
            $gender = "Dear Mrs.$pacienti";
        }

        $paiedSql = "SELECT * FROM terminet WHERE numri_personal=:numri_personal AND statusi='Completed' AND paied=0";
        $paied_prep = $con->prepare($paiedSql);
        $paied_prep->bindParam(':numri_personal', $numri_personal);
        $paied_prep->execute();
        $data_paied = $paied_prep->fetch();

        if ($data_paied) {
            echo "not paied";
        } else {
            $check_sql = "SELECT * FROM terminet WHERE doktori=:doktori AND numri_personal=:numri_personal AND data=:data";
            $check_prep = $con->prepare($check_sql);
            $check_prep->bindParam(':doktori', $doktori);
            $check_prep->bindParam(':numri_personal', $numri_personal);
            $check_prep->bindParam(':data', $data);
            $check_prep->execute();
            $check_data = $check_prep->fetch();

            if ($check_data) {
                echo "Appointment exists";
            } else {

                $terminet_sql = "INSERT INTO terminet(doktori, departamenti, pacienti, numri_personal, email_pacientit, data, ora, statusi)
                            VALUES(:doktori, :departamenti, :pacienti, :numri_personal, :email_pacientit, :data, :ora, 'Booked')";
                $terminet_prep = $con->prepare($terminet_sql);
                $terminet_prep->bindParam(':doktori', $doktori);
                $terminet_prep->bindParam(':departamenti', $docInfo['departament']);
                $terminet_prep->bindParam(':pacienti', $pacienti);
                $terminet_prep->bindParam(':numri_personal', $numri_personal);
                $terminet_prep->bindParam(':email_pacientit', $email_pacientit);
                $terminet_prep->bindParam(':data', $data);
                $terminet_prep->bindParam(':ora', $time);

                if ($terminet_prep->execute()) {
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
                        $mail->addAddress($email_pacientit, $pacienti);                           //Add a recipient


                        //Content
                        $mail->isHTML(true);                                        //Set email format to HTML


                        $mail->Subject = 'Appointment Booking Confirmation';
                        $mail->Body    =   "<p>
                                            $gender, <br><br>
                                            We are pleased to inform you that your appointment has been successfully booked. Here are the details:
                                            <br>
                                            Appointment Date: $data <br>
                                            Appointment Time: $time <br>
                                            Doctor: Dr. $doctor <br>
                                            Department: $dep
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
                        echo "Appointment booked";
                    } catch (Exception $e) {
                        echo  "Problems with server or internet";
                    }

                    exit();
                }
            }
        }
    }
    ?>