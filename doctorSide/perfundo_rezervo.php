<?php

include('../config.php');
require_once('../emailData.php');

$numri_personal = $_GET['numri_personal'];
$data = $_GET['data'];  
$diagnoza = $_GET['diagnoza'];
$recepti = $_GET['recepti'];
$ora = $_GET['ora'];
$id = $_GET['id'];

$sql = "SELECT * FROM users WHERE userType=1 AND personal_id=:personal_id";
$prep = $con->prepare($sql);
$prep->bindParam(':personal_id', $numri_personal);
$prep->execute();
$row = $prep->fetch();

$doc_sql = "SELECT * FROM users WHERE userType=2 AND fullName=:fullName";
$doc_prep = $con->prepare($doc_sql);
$doc_prep->bindParam(':fullName', $_SESSION['doctor']);
$doc_prep->execute();
$doc_data = $doc_prep->fetch();


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $_SESSION['doctor'] ?> | Complete and book another</title>
    <link rel="stylesheet" href="../css/main.css">
    <link rel="icon" href="../photos/doctor.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link rel="stylesheet" href="../bootstrap-5.1.3-examples/sidebars/sidebars.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous" defer></script>
    <script src="../bootstrap-5.1.3-examples/sidebars/sidebars.js" defer></script>
    <!-- Font-awesome script -->
    <script src="https://kit.fontawesome.com/a28016bfcd.js" crossorigin="anonymous"></script>
    <style>
        body{
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>
</head>

<body>
    <?php
    require("../patientSide/PHPMailer-master/src/Exception.php");
    require("../patientSide/PHPMailer-master/src/PHPMailer.php");
    require("../patientSide/PHPMailer-master/src/SMTP.php");
    //Import PHPMailer classes into the global namespace
    //These must be at the top of your script, not inside a function

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    if (isset($_POST['submit'])) {
        $numer = $_POST['nr'];
        $d_w_m = $_POST['d_w_m'];
        $final_date = date("Y-m-d", strtotime("$data + $numer $d_w_m"));



        $doktori = $doc_data['fullName'];
        $dep = $doc_data['departament'];
        $pacienti = $row['fullName'];
        $numri_personal = $row['personal_id'];
        $email_pacientit = $row['email'];



        $gender_sql = "SELECT gender FROM users WHERE userType=1 AND personal_id=:personal_id";
        $gender_prep = $con->prepare($gender_sql);
        $gender_prep->bindParam(':personal_id', $numri_personal);
        $gender_prep->execute();
        $gender_data = $gender_prep->fetch();

        if ($gender_data['gender'] == 'Mashkull') {
            $gjinia = 'Dear Mr.';
        } else {
            $gjinia = 'Dear Mrs.';
        }

        $check_sql = "SELECT * FROM orari WHERE doktori=:doktori AND data=:data";
        $check_prep = $con->prepare($check_sql);
        $check_prep->bindParam(':doktori', $doktori);
        $check_prep->bindParam(':data', $final_date);
        $check_prep->execute();
        $check_data = $check_prep->fetch();


        if ($check_data) {
            $zene_deri = $check_data['zene_deri'];
            if ($zene_deri < $check_data['deri_oren']) {

                $terminet_sql = "INSERT INTO terminet(doktori, departamenti, pacienti, numri_personal, email_pacientit, data, ora, statusi)
                        VALUES(:doktori, :departamenti, :pacienti, :numri_personal, :email_pacientit, :data, :ora, 'Booked')";
                $terminet_prep = $con->prepare($terminet_sql);
                $terminet_prep->bindParam(':doktori', $doktori);
                $terminet_prep->bindParam(':departamenti', $dep);
                $terminet_prep->bindParam(':emri_pacientit', $pacienti);
                $terminet_prep->bindParam(':numri_personal', $numri_personal);
                $terminet_prep->bindParam(':email_pacientit', $email_pacientit);
                $terminet_prep->bindParam(':data', $final_date);
                $terminet_prep->bindParam(':ora', $zene_deri);

                if ($terminet_prep->execute()) {
                    $time1 = $check_data['zene_deri'];
                    $interval2 = $check_data['kohezgjatja'];
                    $date = new DateTime($time1);
                    $date->add(new DateInterval("PT0H{$interval2}M0S"));
                    $date_format = $date->format("H:i:s");


                    $update_orari = "UPDATE orari SET zene_deri=:zene_deri WHERE doktori=:doktori AND data=:data";
                    $update_prep = $con->prepare($update_orari);
                    $update_prep->bindParam(':doktori', $doktori);
                    $update_prep->bindParam(':data', $final_date);
                    $update_prep->bindParam(':zene_deri', $date_format);
                
                    if ($update_prep->execute()) {
                        $ins_sql = "INSERT INTO historia_e_termineve(doktori, departamenti, pacienti, numri_personal, email_pacientit, data, ora, diagnoza, recepti)
                        VALUES(:doktori, :departamenti, :pacienti, :numri_personal, :email_pacientit, :data, :ora, :diagnoza, :recepti)";

                        $ins_prep = $con->prepare($ins_sql);
                        $ins_prep->bindParam(':doktori', $doktori);
                        $ins_prep->bindParam(':departamenti', $dep);
                        $ins_prep->bindParam(':pacienti', $pacienti);
                        $ins_prep->bindParam(':numri_personal', $numri_personal);
                        $ins_prep->bindParam(':email_pacientit', $email_pacientit);
                        $ins_prep->bindParam(':data', $data);
                        $ins_prep->bindParam(':ora', $ora);
                        $ins_prep->bindParam(':diagnoza', $diagnoza);
                        $ins_prep->bindParam(':recepti', $recepti);

                        if ($ins_prep->execute()) {
                            $del_sql = "DELETE FROM terminet WHERE id=:id";
                            $del_prep = $con->prepare($del_sql);
                            $del_prep->bindParam(':id', $id);
                            $del_prep->execute();


                        }


                        $mail = new PHPMailer(true);

                        try {
                            //Server settings
                            $mail->SMTPDebug = 0;                                       //Enable verbose debug output
                            $mail->isSMTP();                                            //Send using SMTP
                            $mail->Host       = 'smtp.gmail.com';                       //Set the SMTP server to send through
                            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                            $mail->Username   = SITE_EMAIL;                         //SMTP username
                            $mail->Password   = SITE_PASSWORD;                            //SMTP password
                            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            //Enable implicit TLS encryption
                            $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

                            //Recipients
                            $mail->setFrom('no@reply.com', 'terminet-online.com');
                            $mail->addAddress($email_pacientit, $pacienti);                           //Add a recipient


                            //Content
                            $mail->isHTML(true);                                        //Set email format to HTML


                            $mail->Subject = 'Appointment booked';
                            $mail->Body    =   "<p style='font-size: 16px; color: black;'>
                                            $gjinia{$pacienti},
                                            <br> <br>
                                            Your second appointment in:$final_date, on time:$zene_deri, 
                                            has been successfully booked.
                                            <br><br>
                                            Sincierly, <br>
                                            sistemi-termineve-online.com
                                            </p>";

                            $mail->send();

                            echo "<script>
                                alert('Appointment successfully booked.');
                                window.location.replace('terminet.php');
                            </script>";
                        } catch (Exception $e) {
                            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                            echo "<script>
                                window.location.replace('terminet.php');
                            </script>";
                        }

                        exit();
                    }
                }
            } else {
                $delete_orari = "DELETE FROM orari WHERE doktori=:doktori AND data=:data";
                $delete_prep = $con->prepare($delete_orari);
                $delete_prep->bindParam(':doktori', $doktori);
                $delete_prep->bindParam(':data', $final_date);
                $delete_prep->execute();
                echo "<script>
                    alert('We are sorry but there aren't any free appointments to book.');
                    window.location.replace('perfundo_rezervo.php?numri_personal=$numri_personal&&data=$data');
                </script>";
            }
        } else {
            $next_appointment_sql = "INSERT INTO terminet_e_dyta(doktori, departament, pacienti, numri_personal, email_pacientit, data)
            VALUES(:doktori, :departament, :pacienti, :numri_personal, :email_pacientit, :data)";
            $next_appointment_prep = $con->prepare($next_appointment_sql);
            $next_appointment_prep->bindParam(':doktori', $doktori);
            $next_appointment_prep->bindParam(':departament', $dep);
            $next_appointment_prep->bindParam(':pacienti', $pacienti);
            $next_appointment_prep->bindParam(':numri_personal', $numri_personal);
            $next_appointment_prep->bindParam(':email_pacientit', $email_pacientit);
            $next_appointment_prep->bindParam(':data', $final_date);
            if($next_appointment_prep->execute()){
                $del_sql = "DELETE FROM terminet WHERE id=:id";
                $del_prep = $con->prepare($del_sql);
                $del_prep->bindParam(':id', $id);
                $del_prep->execute();


                echo "<script>
                        alert('Your schedule on: $final_date, isn't set but the appointment will be automaticlly booked once that your schedule will be set.');
                        window.location.replace('terminet.php');
                    </script>";
            }
            
        }
    }
    ?>
    <form class="form-signin" method="POST" enctype="multipart/form-data" autocomplete="off">
        <h1 class="h3 mb-3 fw-normal text-center">Complete and book another appointment</h1>
        <div class="form-floating">
            <input type="text" class="form-control mb-2" readonly id="floatingInput" name="doktori" placeholder="Doctor" value="<?= $_SESSION['doctor'] ?>">
            <label for="floatingInput">Doctor: </label>
        </div>

        <div class="form-floating">
            <input type="text" class="form-control mb-2" readonly id="floatingInput" name="pacienti" placeholder="Patient" value="<?= $row['fullName'] ?> ">
            <label for="floatingInput">Patient:</label>
        </div>

        <div class="form-floating">
            <input type="text" class="form-control mb-2" readonly id="floatingInput" name="pacienti" placeholder="Personal ID" value="<?= $row['personal_id'] ?>">
            <label for="floatingInput">Personal ID:</label>
        </div>


        <div class="mb-3" id="zgjedh">
            <label for="zgjedh">When will the patient come again:</label>
            <div class="w-25 d-inline-block">
                <select class="form-select <?= $invalid_duration ?? "" ?> " aria-label="Default select example" name="nr">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                </select>
            </div>

            <div class="w-50 d-inline-block">
                <select class="form-select <?= $invalid_duration ?? "" ?> ms-5" aria-label="Default select example" name="d_w_m">
                    <option value="day">Days</option>
                    <option value="week">Weeks</option>
                    <option value="month">Months</option>
                </select>
            </div>
        </div>

        <button class="w-100 btn btn-m btn-primary" type="submit" name="submit">Complete</button>
    </form>
</body>

</html>
