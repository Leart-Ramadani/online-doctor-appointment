<?php
include('../config.php');
if (!isset($_SESSION['emri']) && !isset($_SESSION['mbiemri'])) {
    header("Location: login.php");
}

$sql = "SELECT emri, mbiemri, numri_personal, email, telefoni FROM patient_table WHERE numri_personal=:numri_personal";
$prep = $con->prepare($sql);
$prep->bindParam(':numri_personal', $_SESSION['numri_personal']);
$prep->execute();
$patient_data = $prep->fetch();

?>




<?php

$sql = "SELECT * FROM orari WHERE id=:id";
$doc_prep = $con->prepare($sql);
$doc_prep->bindParam(':id', $id);
$doc_prep->execute();
$row = $doc_prep->fetch();


$sql_doc = "SELECT fullName, departamenti FROM doctor_personal_info WHERE fullName=:doktori";
$stm = $con->prepare($sql_doc);
$stm->bindParam(':doktori', $row['doktori']);
$stm->execute();
$data = $stm->fetch();

?>



    <?php

    require 'C:\xampp\htdocs\Sistemi-per-rezervimin-e-termineve\patientSide\PHPMailer-master\src\Exception.php';
    require 'C:\xampp\htdocs\Sistemi-per-rezervimin-e-termineve\patientSide\PHPMailer-master\src\PHPMailer.php';
    require 'C:\xampp\htdocs\Sistemi-per-rezervimin-e-termineve\patientSide\PHPMailer-master\src\SMTP.php';
    //Import PHPMailer classes into the global namespace
    //These must be at the top of your script, not inside a function

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    if (isset($_POST['checking_viewbtn'])) {

        $_SESSION['id_ofApp'] = $_POST['id'];

        $sql = "SELECT * FROM orari WHERE id=:id";
        $doc_prep = $con->prepare($sql);
        $doc_prep->bindParam(':id', $_SESSION['id_ofApp']);
        $doc_prep->execute();
        $row = $doc_prep->fetch();
        $doc = $row['doktori'];

        $sql_doc = "SELECT fullName, departamenti FROM doctor_personal_info WHERE fullName=:doktori";
        $stm = $con->prepare($sql_doc);
        $stm->bindParam(':doktori', $doc);
        $stm->execute();
        $data = $stm->fetch();


        echo $return = "
            <p>Doctor: <span class='doc_name'>{$data['fullName']}</span></p> 
            <hr>
            <p>Departament: <span class='doc_dep'>{$data['departamenti']}</span></p> 
            <hr>
            <p>Appointment Date: <span class='app_date'>{$row['data']}</span></p> 
            <hr>
            <p>Schedule: <span class='app_time'> {$row['nga_ora']} - {$row['deri_oren']}<span></p>
            <hr>
            <p>Duration: <span class='app_dur'>{$row['kohezgjatja']}min</span></p>
            <hr>";
    }



    if (isset($_POST['rezervo'])) {
        $id = $_SESSION['id_ofApp']; 
        
        $sql = "SELECT * FROM orari WHERE id=:id";
        $doc_prep = $con->prepare($sql);
        $doc_prep->bindParam(':id', $id);
        $doc_prep->execute();
        $row = $doc_prep->fetch();
        $doc = $row['doktori'];

        $sql_doc = "SELECT fullName, departamenti FROM doctor_personal_info WHERE fullName=:doktori";
        $stm = $con->prepare($sql_doc);
        $stm->bindParam(':doktori', $doc);
        $stm->execute();
        $data = $stm->fetch();

        $dep = $data['departamenti'];

        $doktori = $data['fullName'];
        $emri_pacientit = $patient_data['emri'];
        $mbiemri_pacientit = $patient_data['mbiemri'];
        $numri_personal = $patient_data['numri_personal'];
        $email_pacientit = $patient_data['email'];
        $data = $row['data'];
        $zene_deri = $row['zene_deri'];

        $sql = "SELECT emri, mbiemri, numri_personal, email, telefoni FROM patient_table WHERE numri_personal=:numri_personal";
        $prep = $con->prepare($sql);
        $prep->bindParam(':numri_personal', $_SESSION['numri_personal']);
        $prep->execute();
        $patient_data = $prep->fetch();


        $gender_sql = "SELECT gjinia FROM patient_table WHERE numri_personal=:numri_personal";
        $gender_prep = $con->prepare($gender_sql);
        $gender_prep->bindParam(':numri_personal', $patient_data['numri_personal']);
        $gender_prep->execute();
        $gender_data = $gender_prep->fetch();

        if ($gender_data['gjinia'] == 'Mashkull') {
            $gjinia = 'Dear Mr.';
        } else {
            $gjinia = 'Dear Mrs.';
        }


        $check_sql = "SELECT * FROM terminet_e_mia WHERE doktori=:doktori AND numri_personal=:numri_personal AND data=:data";
        $check_prep = $con->prepare($check_sql);
        $check_prep->bindParam(':doktori', $doktori);
        $check_prep->bindParam(':numri_personal', $numri_personal);
        $check_prep->bindParam(':data', $data);
        $check_prep->execute();
        $check_data = $check_prep->fetch();

        if ($check_data) {
            echo "<script>
                    alert('You already have booked one appointment at Dr.{$check_data['doktori']}.');
                    window.location.replace('rezervoTermin.php');
                </script>";
        } else {
            if ($row['zene_deri'] < $row['deri_oren']) {

                $terminet_sql = "INSERT INTO terminet(doktori, emri_pacientit, mbiemri_pacientit, numri_personal, email_pacientit, data, ora)
                        VALUES(:doktori, :emri_pacientit, :mbiemri_pacientit, :numri_personal, :email_pacientit, :data, :ora)";
                $terminet_prep = $con->prepare($terminet_sql);
                $terminet_prep->bindParam(':doktori', $doktori);
                $terminet_prep->bindParam(':emri_pacientit', $emri_pacientit);
                $terminet_prep->bindParam(':mbiemri_pacientit', $mbiemri_pacientit);
                $terminet_prep->bindParam(':numri_personal', $numri_personal);
                $terminet_prep->bindParam(':email_pacientit', $email_pacientit);
                $terminet_prep->bindParam(':data', $data);
                $terminet_prep->bindParam(':ora', $zene_deri);

                if ($terminet_prep->execute()) {
                    $time1 = $row['zene_deri'];
                    $interval2 = $row['kohezgjatja'];
                    $date = new DateTime($time1);
                    $date->add(new DateInterval("PT0H{$interval2}M0S"));
                    $date_format = $date->format("H:i:s");


                    $update_orari = "UPDATE orari SET zene_deri=:zene_deri WHERE id=:id";
                    $update_prep = $con->prepare($update_orari);
                    $update_prep->bindParam(':id', $id);
                    $update_prep->bindParam(':zene_deri', $date_format);
                    $update_prep->execute();

                    $ter_sql = "INSERT INTO terminet_e_mia(emri_pacientit, mbiemri_pacientit, numri_personal, doktori, departamenti, data, ora)
                    VALUES(:emri_pacientit, :mbiemri_pacientit, :numri_personal, :dok, :dep, :date, :ora)";
                    $ter_prep = $con->prepare($ter_sql);
                    $ter_prep->bindParam(':emri_pacientit', $emri_pacientit);
                    $ter_prep->bindParam(':mbiemri_pacientit', $mbiemri_pacientit);
                    $ter_prep->bindParam(':numri_personal', $numri_personal);
                    $ter_prep->bindParam(':dok', $doktori);
                    $ter_prep->bindParam(':dep', $dep);
                    $ter_prep->bindParam(':date', $data);
                    $ter_prep->bindParam(':ora', $zene_deri);
                    if ($ter_prep->execute()) {

                        $mail = new PHPMailer(true);

                        try {
                            //Server settings
                            $mail->SMTPDebug = 0;                                       //Enable verbose debug output
                            $mail->isSMTP();                                            //Send using SMTP
                            $mail->Host       = 'smtp.gmail.com';                       //Set the SMTP server to send through
                            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                            $mail->Username   = 'terminet.online@gmail.com';            //SMTP username
                            $mail->Password   = 'vaiddzxpncfvvksh';                          //SMTP password
                            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            //Enable implicit TLS encryption
                            $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

                            //Recipients
                            $mail->setFrom('terminet.online@gmail.com', 'terminet-online.com');
                            $mail->addAddress($email_pacientit, $emri_pacientit . ' ' . $mbiemri_pacientit);                           //Add a recipient


                            //Content
                            $mail->isHTML(true);                                        //Set email format to HTML


                            $mail->Subject = 'Appointment has been booked';
                            $mail->Body    =   "<p style='font-size: 16px; color: black;'>
                                            $gjinia{$mbiemri_pacientit},
                                            <br> <br>
                                            Your appointment in date:$data, time:$zene_deri, 
                                            has been successfully booked.
                                            <br><br>
                                            Sincerely, <br>
                                            sistemi-termineve-online.com
                                            </p>";

                            $mail->send();

                            echo "<script>
                                alert('Appointment has been successfully booked.');
                                window.location.replace('terminet_e_mia.php');
                            </script>";
                            unset($_SESSION['id_ofApp']);
                        } catch (Exception $e) {
                            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                            echo "<script>
                                window.location.replace('rezervoTermin.php');
                            </script>";
                        }

                        exit();
                    }
                }
            } else {
                $delete_orari = "DELETE FROM orari WHERE id=:id";
                $delete_prep = $con->prepare($delete_orari);
                $delete_prep->bindParam(':id', $id);
                $delete_prep->execute();
                echo "<script>
                    alert('We are sorry but all the appoinments has been booked.');
                    // window.location.replace('rezervoTermin.php');
                </script>";
            }
        }
    }
    ?>