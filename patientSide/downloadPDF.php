<?php
include('../config.php');
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
}

$id = $_GET['id'];

$sql = "SELECT 
        t.id, 
        t.doktori, 
        t.departamenti, 
        t.pacienti, 
        t.numri_personal, 
        t.email_pacientit, 
        t.data, 
        t.ora, 
        t.statusi, 
        t.diagnoza, 
        t.recepti, 
        t.service, 
        t.paied,
        d.name AS 'dep_name', 
        p.price AS 'price', 
        c.code AS 'diagnose' 
        FROM terminet AS t 
        INNER JOIN departamentet AS d 
        ON t.departamenti = d.id 
        INNER JOIN prices AS p
        ON t.service = p.id
        INNER JOIN icd_code AS c 
        ON t.diagnoza=c.id
        WHERE t.id='$id'";
$prep = $con->prepare($sql);
$prep->execute();
$data = $prep->fetch();

if ($data['paied'] == true) {
    $paied = 'Yes';
} else if ($data['paied'] == false) {
    $paied = 'No';
}

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
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../css/main.css">
<link rel="icon" href="../photos/icon-admin.png">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
<link rel="stylesheet" href="../bootstrap-5.1.3-examples/sidebars/sidebars.css">
<link rel="stylesheet" href="../css/responsive.css">
<!-- JQuery link -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js" defer></script>
<script src="../js/index.js" defer></script>
<!-- Font-awesome script -->
<script src="https://kit.fontawesome.com/a28016bfcd.js" crossorigin="anonymous" defer></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
<script src="../bootstrap-5.1.3-examples/sidebars/sidebars.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<title>Download Appointment</title>

</head>

<body class="" style="background-color: rgb(228,240,240);"> 
    <article class="perscriptionContainer" id="pdfElemenet">
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
                    <h1>Dr. <?= $data['doktori'] ?></h1>
                    <p><?= $data['dep_name'] ?></p>
                </div>
                <div class="appointmentInfo">
                    <div>
                        <p class="appointmentId">Appointment ID</p>
                        <p class="app_data" style="margin-left: 5px; width: 167px;"><?= $data['id'] ?></p>
                        <p class="appointmentId" style="padding-left: 5px;">Payment fee</p>
                        <p class="app_data" style="margin-left: 5px; width: 210px;"><?= $data['price'] ?>&euro;</p>
                        <p class="appointmentId" style="padding-left: 5px;">Paied</p>
                        <p class="app_data" style="margin-left: 5px; width: 110px;"><?= $paied ?></p>
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
                        <p class="app_data" style="width: 180px; margin-inline: 5px;"><?= $patient_info['personal_id'] ?></p>
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
                        <p>Diagnosis </p>
                        <p class="app_data" style="width: 678px; margin-left: 5px;"><?= $data['diagnose'] ?></p>
                    </div>
                    <div class="desPrescription d-flex">
                        <h3>Prescription:</h3>
                        <p><?= $data['recepti'] ?></p>
                    </div>
                </div>
            </div>

            <div class="prescriptionFooter">
                <img src="../photos/mediacal stamp.png" alt="">
                <p class="doctorsName"><?= $data['doktori'] ?></p>
                <p>Doctor's signature</p>
            </div>
        </div>
    </article>

    <script>
        const element = document.getElementById('pdfElemenet');
        const opt = {
            margin: 0,
            filename: 'appointment_details.pdf',
            image: {
                type: 'jpeg',
                quality: 0.98
            },
            html2canvas: {
                scale: 1
            },
            jsPDF: {
                unit: 'in',
                format: 'A4',
                orientation: 'portrait'
            }
        };

        // New Promise-based usage:
        html2pdf().set(opt).from(element).save();
    
        setTimeout( () => {
            window.location.replace('historiaTermineve(pacientit).php');
        }, 3000);
    </script>

</body>

</html>