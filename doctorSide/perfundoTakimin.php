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

$service = "SELECT * FROM prices WHERE NOT id=0";
$prep_service = $con->prepare($service);
$prep_service->execute();
$service_data = $prep_service->fetchAll();


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
            $diagnoza_err = '*Diagnose must be filled.';
            $invalid_dianoz = 'is-invalid';
        } else {
            $diagnoza = $_POST['diagnoza'];
            $diagnoza_err = '';
            $diag = $diagnoza;
        }

        if (empty($_POST['recepti'])) {
            $recepti_err = '*Prescription must be filled.';
            $invalid_recepti = 'is-invalid';
        } else {
            $recepti = $_POST['recepti'];
            $recepti_err = '';
            $rec = $recepti;
        }

        if(empty($_POST['service'])){
            $serviceErr = '*Service must be selected';
            $invalid_service = 'is-invalid';
        } else{
            $serviceErr = '';
            $service = $_POST['service'];

            $service_sql = "SELECT id FROM prices WHERE name=:service";
            $service_stmt = $con->prepare($service_sql);
            $service_stmt->bindParam(':service', $service);
            $service_stmt->execute();
            $service_row = $service_stmt->fetch();
        }

        if ($diagnoza_err == '' && $recepti_err == '' && $serviceErr == '') {
            $ins_sql = "UPDATE terminet SET statusi='Completed', diagnoza=:diagnoza, recepti=:recepti, service=:service WHERE id='$id'";
            $ins_prep = $con->prepare($ins_sql);
            $ins_prep->bindParam(':diagnoza', $diagnoza);
            $ins_prep->bindParam(':recepti', $recepti);
            $ins_prep->bindParam(':service', $service_row['id']);
            $ins_prep->execute();

            $delWait = "DELETE FROM waiting_list WHERE apointment_id='$id'";
            $del_prep = $con->prepare($delWait);

            if ($del_prep->execute()) {
                header("Location: terminet.php");
            }
        }
    }

    if (isset($_POST['perfundo_rezervo'])) {
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

        if ($diagnoza_err == '' && $recepti_err == '') {
            header("Location: perfundo_rezervo.php?numri_personal=$numri_personal&data=$data&diagnoza=$diagnoza&recepti=$recepti&ora=$ora&id=$id");
        }
    }

    ?>


    <article class="finishApp">
        <a href="terminet.php" class="goBack text-dark" style="height: 30px;" title="Go back"><i class="fa-solid fa-arrow-left"></i></a>
        <section class="finishSection">
            <div>
                <div class="h1_flex">
                    <h1 class="h2">Appointment details</h1>
                </div>
            </div>

            <div class="form-floating mb-2">
                <input type="text" class="form-control" readonly id="floatingInput" name="username" placeholder="Patient" value="<?= $row['pacienti'] ?>">
                <label for="floatingInput">Patient</label>
            </div>


            <div class="form-floating mb-2">
                <input type="text" class="form-control" readonly id="floatingInput" name="username" placeholder="Personal ID" value="<?= $row['numri_personal'] ?>">
                <label for="floatingInput">Personal ID</label>
            </div>

            <div class="form-floating mb-2">
                <input type="text" class="form-control" readonly id="floatingInput" name="username" placeholder="Email" value="<?= $row['email_pacientit'] ?>">
                <label for="floatingInput">Email</label>
            </div>

            <div class="form-floating mb-2">
                <input type="text" class="form-control" readonly id="floatingInput" name="username" placeholder="Date" value="<?= $row['data'] ?>">
                <label for="floatingInput">Date</label>
            </div>

            <div class="form-floating mb-2">
                <input type="text" class="form-control" readonly id="floatingInput" name="username" placeholder="Time" value="<?= $row['ora'] ?>">
                <label for="floatingInput">Time</label>
            </div>

            <form method="post" autocomplete="off">
                <div class="mb-1">
                     <select class="form-select gender <?= $invalid_service ?? "" ?>" aria-label="Default select example" name="service">
                        <option value="">Select service</option>
                        <?php foreach($service_data as $service_data) : ?>
                        <option value="<?= $service_data['name'] ?>"><?= $service_data['name'] ?></option>
                        <?php endforeach; ?>
                     </select>
                     <span class="text-danger fw-normal"><?php echo $serviceErr; ?></span>
                 </div>
                <div class="mb-2">
                    <label for="diagnoza" class="form-label">Diagnose:</label>
                    <textarea class="form-control <?= $invalid_dianoz ?? '' ?>" style="resize:none;" id="diagnoza" rows="3" maxlength="350" name="diagnoza"><?= $diag; ?></textarea>
                    <span class="text-danger fw-normal"><?php echo $diagnoza_err; ?></span>
                </div>

                <div class="mb-2">
                    <label for="diagnoza" class="form-label">Prescription:</label>
                    <textarea class="form-control <?= $invalid_recepti ?? '' ?>" style="resize:none;" id="diagnoza" rows="3" maxlength="350" name="recepti"><?= $rec; ?></textarea>
                    <span class="text-danger fw-normal"><?php echo $recepti_err; ?></span>
                </div>

                <input type="submit" value="Complete" name="perfundo" class="text-center h4 fw-normal p-2 mt-3 rounded w-100 bg-primary border-0 text-white">
                <input type="submit" value="Complete and book" name="perfundo_rezervo" class="text-center fw-normal h5 p-2 rounded w-100 bg-warning border-0 text-white">
            </form>
        </section>
    </article>

</body>

</html>