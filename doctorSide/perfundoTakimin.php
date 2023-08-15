<?php
include('../config.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);


$id = $_GET['id'];

$updateStatus = "UPDATE terminet SET statusi='In progres' WHERE id='$id'";
$updatePrep = $con->prepare($updateStatus);
$updatePrep->execute();

$sql = "SELECT t.id, t.doktori, t.departamenti, t.pacienti, t.numri_personal, t.email_pacientit, t.data, t.ora, t.statusi, t.diagnoza, t.recepti, t.service, t.paied,
d.name AS 'dep_name', p.price AS 'price', c.code AS 'diagnose_code' 
FROM terminet AS t 
INNER JOIN departamentet AS d ON t.departamenti = d.id 
INNER JOIN prices AS p ON t.service = p.id
INNER JOIN icd_code AS c ON t.diagnoza = c.id
WHERE t.id=:id";


$prep = $con->prepare($sql);
$prep->bindParam(':id', $id);
$prep->execute();
$row = $prep->fetch();

if ($row['paied'] == true) {
    $paied = 'Yes';
} else if ($row['paied'] == false) {
    $paied = 'No';
}

$ora = $row['ora'];

$service = "SELECT * FROM prices WHERE NOT id=0";
$prep_service = $con->prepare($service);
$prep_service->execute();
$service_data = $prep_service->fetchAll();

$patient_sql = "SELECT * FROM users WHERE userType=1 AND personal_id=:personal_id";
$patient_prep = $con->prepare($patient_sql);
$patient_prep->bindParam(':personal_id', $row['numri_personal']);
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

$date = date_create($row['data']);
$date = date_format($date, "d/m/Y");

$time = date_create($row['ora']);
$time = date_format($time, "H:i");


$depSql = "SELECT * FROM departamentet WHERE NOT id=0";
$depPrep = $con->prepare($depSql);
$depPrep->execute();
$depData = $depPrep->fetchAll();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $_SESSION['doctor'] ?> | Complete Appointment</title>
    <link rel="stylesheet" href="../css/main.css">
    <link rel="icon" href="../photos/doctor.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link rel="stylesheet" href="../bootstrap-5.1.3-examples/sidebars/sidebars.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous" defer></script>
    <script src="../bootstrap-5.1.3-examples/sidebars/sidebars.js" defer></script>
    <!-- Font-awesome script -->
    <script src="https://kit.fontawesome.com/a28016bfcd.js" crossorigin="anonymous"></script>

</head>


<body style="background: #f5f5f5;">
    <?php
    $diagnoza_err = $recepti_err = $serviceErr = '';
    $diag = $rec = $serv = '';




    // if (isset($_POST['perfundo_rezervo'])) {
    //     $numri_personal = $row['numri_personal'];
    //     $data = $row['data'];

    //     if (empty($_POST['diagnoza'])) {
    //         $diagnoza_err = 'Diagnose must be filled.';
    //         $invalid_dianoz = 'is-invalid';
    //     } else {
    //         $diagnoza = $_POST['diagnoza'];
    //         $diagnoza_err = '';
    //         $diag = $diagnoza;
    //     }

    //     if (empty($_POST['recepti'])) {
    //         $recepti_err = 'Prescription must be filled.';
    //         $invalid_recepti = 'is-invalid';
    //     } else {
    //         $recepti = $_POST['recepti'];
    //         $recepti_err = '';
    //         $rec = $recepti;
    //     }

    //     if ($diagnoza_err == '' && $recepti_err == '') {
    //         header("Location: perfundo_rezervo.php?numri_personal=$numri_personal&data=$data&diagnoza=$diagnoza&recepti=$recepti&ora=$ora&id=$id");
    //     }
    // }

    ?>

    <article class="perscriptionContainer">
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
                    <h1>Dr. <?= $row['doktori'] ?></h1>
                    <p><?= $row['dep_name'] ?></p>
                </div>
                <div class="appointmentInfo">
                    <div class="d-flex justify-content-end">
                        <p class="appointmentId">Appointment ID</p>
                        <p class="app_data app-ID" style="margin-left: 5px; width: 120px;"><?= $row['id'] ?></p>
                    </div>
                    <div>
                        <p class="patientName">Patient's Name </p>
                        <p class="app_data" style="width: 373px; margin-inline: 5px;"><?= $row['pacienti'] ?></p>
                        <p class="date">Date </p>
                        <p class="app_data" style="width: 94px; margin-left: 5px;"><?= $date ?></p>
                        <p class="date" style="margin-left: 5px;">Time </p>
                        <p class="app_data" style="width: 86px; margin-left: 5px;"><?= $time ?></p>
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
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="w-50 d-flex flex-column align-items-start">
                            <select class="form-select gender service <?= $invalid_service ?? "" ?>" aria-label="Default select example" name="service" style="width: 320px;">
                                <option value="">Select service</option>
                                <?php
                                foreach ($service_data as $service_data) {
                                    if ($service_data == $serv) {
                                ?>
                                        <option value="<?= $service_data['name'] ?>" selected><?= $service_data['name'] ?></option>
                                    <?php } else { ?>
                                        <option value="<?= $service_data['name'] ?>"><?= $service_data['name'] ?></option>
                                <?php
                                    }
                                } ?>
                            </select>
                            <span class="text-danger fw-normal serviceErr"></span>
                        </div>
                        <div class="diagnose flex-column align-items-start">
                            <div class="diagnose-selected">
                                <input type="text" class="form-control diagnose-input" id="floatingInput" name="diagnoza" placeholder="Select diagnose" readonly style="height: 50px !important;" value="<?= $diag ?>">
                            </div>
                            <div class="diagnose-content">
                                <div class="diagnose-search">
                                    <input type="text" class="form-control searchDiagnose" id="floatingInput" placeholder="Search diagnose">
                                </div>
                                <div class="diagnose-options">
                                    <ul class="options">

                                    </ul>
                                </div>
                            </div>
                            <span class="text-danger fw-normal diagnoseErr"></span>
                        </div>
                        <div class="w-50 departament d-none flex-column" style=" margin-left: 113px;">
                            <select class="form-select gender departamentInp" aria-label="Default select example" name="departament" style="width: 320px;">
                                <option value="">Select departament</option>
                                <?php foreach ($depData as $depData) { ?>
                                    <option value="<?= $depData['name'] ?>"><?= $depData['name'] ?></option>
                                <?php } ?>
                            </select>
                            <span class="text-danger fw-normal departamentErr"></span>
                        </div>
                    </div>
                    <div class="desPrescription d-flex mt-2 flex-column">
                        <h3>Rp:</h3>
                        <textarea class="form-control prescription" style="resize:none; height: 370px; background: transparent;" id="diagnoza" rows="3" maxlength="2000" name="recepti"><?= $rec; ?></textarea>
                        <span class="text-danger fw-normal prescriptionErr"></span>
                    </div>
                    <input type="submit" value="Complete" class="text-center h4 fw-normal p-2 mt-3 rounded w-25 bg-primary border-0 text-white finishAppointment_btn">

                </div>
            </div>

            <div class="prescriptionFooter">
                <img src="../photos/mediacal stamp.png" alt="">
                <p class="doctorsName"><?= $row['doktori'] ?></p>
                <p>Doctor's signature</p>
            </div>
        </div>
    </article>


    <script>

    </script>


    <!-- JQuery link -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <script src="../js/finishAppoitment.js"></script>
</body>

</html>