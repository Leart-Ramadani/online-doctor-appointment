<?php

include('../config.php');

$numri_personal = $_GET['numri_personal'];
$data = $_GET['data'];
$diagnoza = $_GET['diagnoza'];
$recepti = $_GET['recepti'];
$ora = $_GET['ora'];
$id = $_GET['id'];

$sql = "SELECT * FROM patient_table WHERE numri_personal=:numri_personal";
$prep = $con->prepare($sql);
$prep->bindParam(':numri_personal', $numri_personal);
$prep->execute();
$row = $prep->fetch();

$doc_sql = "SELECT * FROM doctor_personal_info WHERE fullName=:fullName";
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
    <title><?php echo $_SESSION['doctor'] ?></title>
    <link rel="stylesheet" href="../css/main.css">
    <link rel="icon" href="../photos/doctor.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link rel="stylesheet" href="../bootstrap-5.1.3-examples/sidebars/sidebars.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous" defer></script>
    <script src="../bootstrap-5.1.3-examples/sidebars/sidebars.js" defer></script>
    <!-- Font-awesome script -->
    <script src="https://kit.fontawesome.com/a28016bfcd.js" crossorigin="anonymous"></script>
</head>

<body>
    <?php
    require 'C:\xampp\htdocs\Sistemi-per-rezervimin-e-termineve\patientSide\PHPMailer-master\src\Exception.php';
    require 'C:\xampp\htdocs\Sistemi-per-rezervimin-e-termineve\patientSide\PHPMailer-master\src\PHPMailer.php';
    require 'C:\xampp\htdocs\Sistemi-per-rezervimin-e-termineve\patientSide\PHPMailer-master\src\SMTP.php';
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
        $dep = $doc_data['departamenti'];
        $emri_pacientit = $row['emri'];
        $mbiemri_pacientit = $row['mbiemri'];
        $numri_personal = $row['numri_personal'];
        $email_pacientit = $row['email'];



        $gender_sql = "SELECT gjinia FROM patient_table WHERE numri_personal=:numri_personal";
        $gender_prep = $con->prepare($gender_sql);
        $gender_prep->bindParam(':numri_personal', $numri_personal);
        $gender_prep->execute();
        $gender_data = $gender_prep->fetch();

        if ($gender_data['gjinia'] == 'Mashkull') {
            $gjinia = 'I nderuar z.';
        } else {
            $gjinia = 'E nderuar znj.';
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

                $terminet_sql = "INSERT INTO terminet(doktori, emri_pacientit, mbiemri_pacientit, numri_personal, email_pacientit, data, ora)
                        VALUES(:doktori, :emri_pacientit, :mbiemri_pacientit, :numri_personal, :email_pacientit, :data, :ora)";
                $terminet_prep = $con->prepare($terminet_sql);
                $terminet_prep->bindParam(':doktori', $doktori);
                $terminet_prep->bindParam(':emri_pacientit', $emri_pacientit);
                $terminet_prep->bindParam(':mbiemri_pacientit', $mbiemri_pacientit);
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
                    $update_prep->execute();

                    $ter_sql = "INSERT INTO terminet_e_mia(emri_pacientit, mbiemri_pacientit, numri_personal, doktori, departamenti, data, ora)
                    VALUES(:emri_pacientit, :mbiemri_pacientit, :numri_personal, :dok, :dep, :date, :ora)";
                    $ter_prep = $con->prepare($ter_sql);
                    $ter_prep->bindParam(':emri_pacientit', $emri_pacientit);
                    $ter_prep->bindParam(':mbiemri_pacientit', $mbiemri_pacientit);
                    $ter_prep->bindParam(':numri_personal', $numri_personal);
                    $ter_prep->bindParam(':dok', $doktori);
                    $ter_prep->bindParam(':dep', $dep);
                    $ter_prep->bindParam(':date', $final_date);
                    $ter_prep->bindParam(':ora', $zene_deri);
                    if ($ter_prep->execute()) {
                        $ins_sql = "INSERT INTO historia_e_termineve(doktori, departamenti, pacienti, numri_personal, email_pacientit, data, ora, diagnoza, recepti)
                        VALUES(:doktori, :departamenti, :pacienti, :numri_personal, :email_pacientit, :data, :ora, :diagnoza, :recepti)";

                        $pacienti = $emri_pacientit.' '.$mbiemri_pacientit;
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

                            $terminetMia_sql = "DELETE FROM terminet_e_mia WHERE numri_personal=:numri_personal AND data=:data AND ora=:ora";
                            $terminetMia_prep = $con->prepare($terminetMia_sql);
                            $terminetMia_prep->bindParam(':numri_personal', $numri_personal);
                            $terminetMia_prep->bindParam(':data', $data);
                            $terminetMia_prep->bindParam(':ora', $ora);
                            $terminetMia_prep->execute();

                        }


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


                            $mail->Subject = 'Termini u rezeruva';
                            $mail->Body    =   "<p style='font-size: 16px; color: black;'>
                                            $gjinia{$mbiemri_pacientit},
                                            <br> <br>
                                            Termini juaj i dytë me datë:$final_date, në orën:$zene_deri, 
                                            është rezervuar me sukses.
                                            <br><br>
                                            Me respekt, <br>
                                            sistemi-termineve-online.com
                                            </p>";

                            $mail->send();

                            echo "<script>
                                alert('Termini u rezervua me sukses.');
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
                    alert('Na vjen keq mirepo nuk ka termine te lira ne kete date.');
                    window.location.replace('perfundo_rezervo.php?numri_personal=$numri_personal&&data=$data');
                </script>";
            }
        } else {
            $next_appointment_sql = "INSERT INTO terminet_e_dyta(doktori, emri_pacientit, mbiemri_pacientit, numri_personal, email_pacientit, data)
            VALUES(:doktori, :emri_pacientit, :mbiemri_pacientit, :numri_personal, :email_pacientit, :data)";
            $next_appointment_prep = $con->prepare($next_appointment_sql);
            $next_appointment_prep->bindParam(':doktori', $doktori);
            $next_appointment_prep->bindParam(':emri_pacientit', $emri_pacientit);
            $next_appointment_prep->bindParam(':mbiemri_pacientit', $mbiemri_pacientit);
            $next_appointment_prep->bindParam(':numri_personal', $numri_personal);
            $next_appointment_prep->bindParam(':email_pacientit', $email_pacientit);
            $next_appointment_prep->bindParam(':data', $final_date);
            if($next_appointment_prep->execute()){
                $del_sql = "DELETE FROM terminet WHERE id=:id";
                $del_prep = $con->prepare($del_sql);
                $del_prep->bindParam(':id', $id);
                $del_prep->execute();

                $terminetMia_sql = "DELETE FROM terminet_e_mia WHERE numri_personal=:numri_personal AND data=:data AND ora=:ora";
                $terminetMia_prep = $con->prepare($terminetMia_sql);
                $terminetMia_prep->bindParam(':numri_personal', $numri_personal);
                $terminetMia_prep->bindParam(':data', $data);
                $terminetMia_prep->bindParam(':ora', $ora);
                $terminetMia_prep->execute();
                echo "<script>
                        alert('Juve nuk ju eshte caktuar orari i punes me daten: $final_date, mirepo termini do te rezervohet sapo te ju caktohet orari i punes.');
                        window.location.replace('terminet.php');
                    </script>";
            }
            
        }
    }
    ?>
    <form class="form-signin" method="POST" enctype="multipart/form-data" autocomplete="off">
        <h1 class="h3 mb-3 fw-normal text-center">Perfundo dhe rezervo takim te ri</h1>
        <div class="form-floating">
            <input type="text" class="form-control mb-2" readonly id="floatingInput" name="doktori" placeholder="Doktori" value="<?= $_SESSION['doctor'] ?>">
            <label for="floatingInput">Doktori: </label>
        </div>

        <div class="form-floating">
            <input type="text" class="form-control mb-2" readonly id="floatingInput" name="pacienti" placeholder="Pacienti" value="<?= $row['emri'] . ' ' . $row['mbiemri'] ?> ">
            <label for="floatingInput">Pacienti:</label>
        </div>

        <div class="form-floating">
            <input type="text" class="form-control mb-2" readonly id="floatingInput" name="pacienti" placeholder="Numri personal" value="<?= $row['numri_personal'] ?>">
            <label for="floatingInput">Numri personal:</label>
        </div>


        <div class="mb-3" id="zgjedh">
            <label for="zgjedh">Pas sa kohe duhet te vije pacienti:</label>
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
                    <option value="day">Dite</option>
                    <option value="week">Jave</option>
                    <option value="month">Muaj</option>
                </select>
            </div>
        </div>

        <button class="w-100 btn btn-m btn-primary" type="submit" name="submit">Perfundo dhe rezervo takimin tjeter</button>
    </form>
</body>

</html>
