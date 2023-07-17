<?php

include('../config.php');

$id = $_GET['id'];

$updateStatus = "UPDATE terminet SET statusi='In progres' WHERE id='$id'";
$updatePrep = $con->prepare($updateStatus);
$updatePrep->execute();

$sql = "SELECT * FROM terminet WHERE id=:id";
$prep = $con->prepare($sql);
$prep->bindParam(':id', $id);
$prep->execute();
$row = $prep->fetch();

$ora = $row['ora'];


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


<body>
    <?php
    $diagnoza_err = $recepti_err = '';
    $diag = $rec = '';


    if (isset($_POST['perfundo'])) {
        $doktori = $row['doktori'];

        $sql_doc = "SELECT fullName, departament FROM users WHERE userType=2 AND fullName=:fullName";
        $doc_prep = $con->prepare($sql_doc);
        $doc_prep->bindParam(':fullName', $doktori);
        $doc_prep->execute();
        $doc_data = $doc_prep->fetch();

        $departamenti = $doc_data['departament'];


        $pacienti = $row['pacienti'];
        $numri_personal = $row['numri_personal'];
        $email_pacientit = $row['email_pacientit'];
        $data = $row['data'];
        $ora = $row['ora'];
        $diagnoza = $_POST['diagnoza'];
        $recepti = $_POST['recepti'];

        if (empty($_POST['diagnoza'])) {
            $diagnoza_err = 'Diagnose must be filled.';
            $invalid_dianoz = 'is-invalid';
        } else {
            $diagnoza = $_POST['diagnoza'];
            $diagnoza_err = '';
            $diag = $diagnoza;
        }

        if (empty($_POST['recepti'])) {
            $recepti_err = 'Prescription must be filled.';
            $invalid_recepti = 'is-invalid';
        } else {
            $recepti = $_POST['recepti'];
            $recepti_err = '';
            $rec = $recepti;
        }

        if ($diagnoza_err == '' && $recepti_err == '') {
            $ins_sql = "UPDATE terminet SET statusi='Completed', diagnoza=:diagnoza, recepti=:recepti WHERE id='$id'";
            $ins_prep = $con->prepare($ins_sql);
            $ins_prep->bindParam(':diagnoza', $diagnoza);
            $ins_prep->bindParam(':recepti', $recepti);
            $ins_prep->execute();

            $delWait = "DELETE FROM waiting_list WHERE apointment_id='$id'";
            $del_prep = $con->prepare($delWait);

            if ($del_prep->execute()) {
                header("Location: terminet.php");
            }
        }
    }

    if(isset($_POST['perfundo_rezervo'])){
        $numri_personal = $row['numri_personal'];
        $data = $row['data'];
        
        if (empty($_POST['diagnoza'])) {
            $diagnoza_err = 'Diagnose must be filled.';
            $invalid_dianoz = 'is-invalid';
        } else {
            $diagnoza = $_POST['diagnoza'];
            $diagnoza_err = '';
            $diag = $diagnoza;
        }

        if (empty($_POST['recepti'])) {
            $recepti_err = 'Prescription must be filled.';
            $invalid_recepti = 'is-invalid';
        } else {
            $recepti = $_POST['recepti'];
            $recepti_err = '';
            $rec = $recepti;
        }

        if($diagnoza_err == '' && $recepti_err == ''){
            header("Location: perfundo_rezervo.php?numri_personal=$numri_personal&data=$data&diagnoza=$diagnoza&recepti=$recepti&ora=$ora&id=$id");
        }
    }

    ?>
    <article class="appointment_wrapper">
        <section class="appointment">
            <div>
                <a href="terminet.php" class="goBack" title="Go back"><i class="fa-solid fa-arrow-left"></i></a>
                <div class="h1_flex">
                    <h1 class="appointment_h1 app_h1">Appointment details</h1>
                </div>
            </div>
            <div>
                <label>Patient:</label>
                <p class="appointment_p"><?= $row['pacienti'] ?></p>
            </div>
            <div>
                <label>Personal ID:</label>
                <p class="appointment_p"><?= $row['numri_personal'] ?></p>
            </div>
            <div>
                <label>Email:</label>
                <p class="appointment_p"><?= $row['email_pacientit'] ?></p>
            </div>
            <div>
                <label>Date:</label>
                <p class="appointment_p"><?= $row['data'] ?></p>
            </div>

            <div>
                <label>Time:</label>
                <p class="appointment_p"><?= $row['ora'] ?></p>
            </div>


            <form method="post" autocomplete="off">
                <label for="komentiDoktorit" class="form-label fs-5">Diagnose:</label>
                <div>
                    <textarea class="form-control <?= $invalid_dianoz ?? '' ?>" style="resize: none;" id="komentiDoktorit" rows="3" maxlength="250" name="diagnoza"><?= $diag; ?></textarea>
                </div>
                <span class="text-danger fw-normal mb-5"><?php echo $diagnoza_err; ?></span> <br>
                <label for="recepti" class="form-label fs-5">Prescription:</label>
                <div>
                    <textarea class="form-control <?= $invalid_recepti ?? '' ?>" style="resize: none;" id="recepti" rows="4" maxlength="250" name="recepti"><?= $rec; ?></textarea>
                </div>
                <span class="text-danger fw-normal"><?php echo $recepti_err; ?></span> <br>
                <input type="submit" value="Perfundo takimin" name="perfundo" class="text-center h4 fw-normal p-2 mt-3 rounded w-100 bg-primary border-0 text-white">
                <input type="submit" value="Perfundo takimin dhe rezervo nje te ri" name="perfundo_rezervo" class="text-center fw-normal h5 p-2 rounded w-100 bg-warning border-0 text-white">
            </form>
        </section>
    </article>

</body>

</html>