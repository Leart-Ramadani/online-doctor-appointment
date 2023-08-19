<?php
include '../config.php';
if (!isset($_SESSION['fullName'])) {
    header("Location: login.php");
}

$id = $_GET['id'];

$sql = "SELECT
        r.id,
        r.appointment_id,
        r.to_departament AS 'to_departament_id',
        t.doktori AS 'doctor',
        t.departamenti,
        t.pacienti,
        t.data,
        t.ora,
        t.numri_personal,
        t.recepti,
        d1.name AS 'departament',
        d2.name AS 'to_departament'
        FROM reference AS r
        INNER JOIN terminet AS t
        ON r.appointment_id=t.id
        INNER JOIN departamentet AS d1
        ON t.departamenti=d1.id
        INNER JOIN departamentet AS d2
        ON r.to_departament=d2.id
        WHERE r.id=:id";
$prep = $con->prepare($sql);
$prep->bindParam(':id', $id);
$prep->execute();
$data = $prep->fetch(PDO::FETCH_ASSOC);

$patient_sql = "SELECT * FROM users WHERE userType=1 AND personal_id=:personal_id";
$patient_prep = $con->prepare($patient_sql);
$patient_prep->bindParam(':personal_id', $data['numri_personal']);
$patient_prep->execute();
$patient_info = $patient_prep->fetch();


$birthday = date_create($patient_info['birthday']);
$birthday = date_format($birthday, "d/m/Y");

function calculateAge($birthdate)
{
    $birthdateObj = DateTime::createFromFormat('d/m/Y', $birthdate);
    $currentDateObj = new DateTime();

    $ageInterval = $birthdateObj->diff($currentDateObj);

    $age = $ageInterval->y;

    return $age;
}

$age = calculateAge($birthday);

$time = date_create($data['ora']);
$time = date_format($time, "H:i");

$date = date_create($data['data']);
$date = date_format($date, "d/m/Y");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reference Info</title>
    <link rel="shortcut icon" href="../photos/icon-hospital.png">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/responsive.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link rel="stylesheet" href="../bootstrap-5.1.3-examples/sidebars/sidebars.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous" defer></script>
    <script src="../bootstrap-5.1.3-examples/sidebars/sidebars.js" defer></script>
    <!-- JQuery link -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js" defer></script>
    <script src="../js/index.js" defer></script>
    <script src="../js/anulo.js" defer></script>
    <!-- Font-awesome script -->
    <script src="https://kit.fontawesome.com/a28016bfcd.js" crossorigin="anonymous" defer></script>

</head>

<body>
    <article class="perscriptionContainer d-flex justify-content-start" id="pdfElemenet">
        <div class="prescriptionWrapper">
            <div class="prescriptionHeader">
                <div>
                    <h1>
                        Medical Center <br>
                        <small>Lorem ipsum</small>
                    </h1>
                </div>
                <div>
                    <p>123, Lorem Ipsum St.</p>
                    <p>+38344521522</p>
                    <p>online-appointmnet@gmail.com</p>
                </div>
            </div>

            <div class="prescriptionBody">
                <div>
                    <img src="../photos/hospital logo.png" alt="Hospital logo">
                </div>
                <div class="prescriptionDoc">
                    <h1>Dr.<?= $data['doctor'] ?></h1>
                    <p><?= $data['departament'] ?></p>
                </div>
                <div class="appointmentInfo">
                    <div class="d-flex justify-content-end">
                        <p class="appointmentId">Appointment ID</p>
                        <p class="app_data" style="margin-left: 5px; width: 150px;"><?= $data['id'] ?></p>
                    </div>
                    <div>
                        <p class="patientName">Patient's Name </p>
                        <p class="app_data" style="width: 373px; margin-inline: 5px;"><?= $data['pacienti'] ?></p>
                        <p class="date">Date </p>
                        <p class="app_data" style="width: 94px; margin-left: 5px;"><?= $date ?></p>
                        <p class="date" style="margin-left: 5px;">Ora </p>
                        <p class="app_data" style="width: 94px; margin-left: 5px;"><?= $time ?></p>
                    </div>
                    <div>
                        <p class="date">Personal ID </p>
                        <p class="app_data" style="width: 180px; margin-inline: 5px;"><?= $data['numri_personal'] ?></p>
                        <p class="patientName">Adress </p>
                        <p class="app_data" style="width: 425px; margin-left: 5px;"><?= $patient_info['adress'] ?></p>
                    </div>
                    <div>
                        <p class="dateOfBirth">Date of birth </p>
                        <p class="app_data" style="width: 209px; margin-inline: 5px;"><?= $birthday ?></p>
                        <p class="age">Age </p>
                        <p class="app_data" style="width: 150px; margin-inline: 5px;"><?= $age ?></p>
                        <p class="gender">Gender </p>
                        <p class="app_data" style="width: 200px; margin-left: 5px;"><?= $patient_info['gender'] ?></p>
                    </div>
                    <div>
                        <p>To departament </p>
                        <p class="app_data" style="width: 640px; margin-left: 5px;"><?= $data['to_departament'] ?></p>
                    </div>
                    <div class="desPrescription d-flex">
                        <h3>Prescription:</h3>
                        <p><?= $data['recepti'] ?></p>
                    </div>
                </div>
            </div>

            <div class="prescriptionFooter">
                <img src="../photos/mediacal stamp.png" alt="">
                <p class="doctorsName"><?= $data['doctor'] ?></p>
                <p>Doctor's signature</p>
            </div>
        </div>

        <section class="d-flex">
            <div>

            </div>
        </section>
    </article>
</body>