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

        $sql = "SELECT o.id, o.doktori, o.departamenti, o.data, o.nga_ora, o.deri_oren, o.kohezgjatja, o.zene_deri, d.name AS
        'dep_name' FROM orari AS o INNER JOIN departamentet AS d ON o.departamenti = d.id WHERE o.id=:id";
        $doc_prep = $con->prepare($sql);
        $doc_prep->bindParam(':id', $_SESSION['id_ofApp']);
        $doc_prep->execute();
        $row = $doc_prep->fetch();
        $doc = $row['doktori'];


        echo $return = "
            <p>Doctor: <span class='doc_name'>{$row['doktori']}</span></p> 
            <hr>
            <p>Departament: <span class='doc_dep'>{$row['dep_name']}</span></p> 
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

        $sql_doc = "SELECT u.fullName, u.departament, d.name AS 'dep_name' FROM users AS u INNER JOIN departamentet AS d ON u.departament = d.id
        WHERE userType=2 AND fullName=:doktori";
        $stm = $con->prepare($sql_doc);
        $stm->bindParam(':doktori', $doc);
        $stm->execute();
        $docInfo = $stm->fetch();
        
        $doktori = $docInfo['fullName'];
        $dep = $docInfo['dep_name'];

        
        $sql = "SELECT fullName, personal_id, email, phone FROM users WHERE userType=1 AND personal_id=:personal_id";
        $prep = $con->prepare($sql);
        $prep->bindParam(':personal_id', $_SESSION['numri_personal']);
        $prep->execute();
        $patient_data = $prep->fetch();

        $pacienti = $patient_data['fullName'];
        $numri_personal = $patient_data['personal_id'];
        $email_pacientit = $patient_data['email'];


        $gender_sql = "SELECT gender FROM users WHERE userType=1 AND personal_id=:personal_id";
        $gender_prep = $con->prepare($gender_sql);
        $gender_prep->bindParam(':personal_id', $_SESSION['numri_personal']);
        $gender_prep->execute();
        $gender_data = $gender_prep->fetch();

        if ($gender_data['gender'] == 'Mashkull') {
            $gjinia = 'Dear Mr.';
        } else {
            $gjinia = 'Dear Mrs..';
        }


        $check_sql = "SELECT * FROM terminet WHERE doktori=:doktori AND numri_personal=:numri_personal AND data=:data";
        $check_prep = $con->prepare($check_sql);
        $check_prep->bindParam(':doktori', $doktori);
        $check_prep->bindParam(':numri_personal', $numri_personal);
        $check_prep->bindParam(':data', $row['data']);
        $check_prep->execute();
        $check_data = $check_prep->fetch();

        if ($check_data) {
            echo "<script>
                    alert('You have an appointment booked at Dr.{$check_data['doktori']}.');
                    window.location.replace('rezervoTermin.php');
                </script>";
        } else {
            if ($row['zene_deri'] < $row['deri_oren']) {

                $terminet_sql = "INSERT INTO terminet(doktori, departamenti, pacienti, numri_personal, email_pacientit, data, ora, statusi)
                        VALUES(:doktori, :departamenti, :pacienti, :numri_personal, :email_pacientit, :data, :ora, 'Booked')";
                $terminet_prep = $con->prepare($terminet_sql);
                $terminet_prep->bindParam(':doktori', $doktori);
                $terminet_prep->bindParam(':departamenti', $docInfo['departament']);
                $terminet_prep->bindParam(':pacienti', $pacienti);
                $terminet_prep->bindParam(':numri_personal', $numri_personal);
                $terminet_prep->bindParam(':email_pacientit', $email_pacientit);
                $terminet_prep->bindParam(':data', $row['data']);
                $terminet_prep->bindParam(':ora', $row['zene_deri']);

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

                
                    if ($update_prep->execute()) {

                        $mail = new PHPMailer(true);


                        try {
                            //Server settings
                            $mail->SMTPDebug = 0;                                       //Enable verbose debug output
                            $mail->isSMTP();                                            //Send using SMTP
                            $mail->Host       = 'smtp.gmail.com';                       //Set the SMTP server to send through
                            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                            $mail->Username   = SITE_EMAIL;            //SMTP username
                            $mail->Password   = SITE_PASSWORD;                             //SMTP password
                            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            //Enable implicit TLS encryption
                            $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

                            //Recipients
                            $mail->setFrom('no@reply.com', 'terminet-online.com');
                            $mail->addAddress($email_pacientit, $pacienti);                           //Add a recipient


                            //Content
                            $mail->isHTML(true);                                        //Set email format to HTML


                            $mail->Subject = 'Appointment Details';
                            $mail->Body    =   "<p style='font-size: 16px; color: black;'>
                                            $gjinia{$pacienti},
                                            <br> <br>
                                            Your appointment in date:$data, on time:{$row['zene_deri']}, 
                                            at dr.{$check_data['doktori']}
                                            has been successfully booked
                                            <br><br>
                                            Sincierly, <br>
                                            sistemi-termineve-online.com
                                            </p>";

                            $mail->send();

                            echo "<script>
                                alert('Your appointment has been successfully booked.');
                                window.location.replace('terminet_e_mia.php');
                            </script>";
                            unset($_SESSION['id_ofApp']);
                        } catch (Exception $e) {
                            echo "<script>
                                alert('Problems with server or internet')
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
                    alert('We are sorry but all appointments has been booked.');
                    window.location.replace('rezervoTermin.php');
                </script>";
            }
        }
    }
    ?>