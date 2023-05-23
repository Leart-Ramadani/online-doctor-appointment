<?php
include('../config.php');
if (!isset($_SESSION['emri']) && !isset($_SESSION['mbiemri'])) {
    header("Location: login.php");
}
?>

<?php


$d_sql = "SELECT * FROM patient_table WHERE numri_personal=:numri_personal";
$d_prep = $con->prepare($d_sql);
$d_prep->bindParam(':numri_personal', $_SESSION['numri_personal']);
$d_prep->execute();
$row = $d_prep->fetch();

$ter_sql = "SELECT * FROM terminet_e_mia WHERE id=:id";
$ter_prep = $con->prepare($ter_sql);
$ter_prep->bindParam(':id', $_SESSION['idAnulo']);
$ter_prep->execute();
$stm = $ter_prep->fetch();



$msg = '';
if (isset($_POST['anulo'])) {
    $emri = $stm['emri_pacientit'];
    $mbiemri = $stm['mbiemri_pacientit'];
    $numri_personal = $row['numri_personal'];
    $email = $row['email'];
    $telefoni = $row['telefoni'];
    $doktori = $stm['doktori'];
    $departamenti = $stm['departamenti'];
    $data = $stm['data'];
    $ora = $stm['ora'];
    $arsyejaAnulimit = $_POST['arsyejaAnulimit'];

    if (empty($_POST['arsyejaAnulimit'])) {
        $msg = 'Arsyeja e anulimit nuk duhet lene zbrazur.';
        $njoftim = 'njoftim';
    } else {
        $req_sql = "INSERT INTO kerkesatanulimit(emri_pacientit, mbiemri_pacientit, numri_personal, email, telefoni, doktori, departamenti, data, ora, arsyeja_anulimit)
            VALUES(:emri, :mbiemri, :numri_personal, :email, :telefoni, :doktori, :departamenti, :data, :ora, :arysejaAnulimit)";
        $res_prep = $con->prepare($req_sql);
        $res_prep->bindParam(':emri', $emri);
        $res_prep->bindParam(':mbiemri', $mbiemri);
        $res_prep->bindParam(':numri_personal', $numri_personal);
        $res_prep->bindParam(':email', $email);
        $res_prep->bindParam(':telefoni', $telefoni);
        $res_prep->bindParam(':doktori', $doktori);
        $res_prep->bindParam(':departamenti', $departamenti);
        $res_prep->bindParam(':data', $data);
        $res_prep->bindParam(':ora', $ora);
        $res_prep->bindParam(':arysejaAnulimit', $arsyejaAnulimit);
        if ($res_prep->execute()) {
            $msg = "Kerkesa juaj per anulimin e terminit eshte derguar me sukses. <br>
                    Ju do te njoftoheni permes email-it per pergjigjjen e kerkeses!";
            $njoftim = 'njoftim';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Termient e mia</title>
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
    <div class="sideBlock">
        <button type="button" class="ham" id="ham_menu"><i class="fa-solid fa-bars"></i></button>
    </div>


    <div class="flex-shrink-0 p-3 text-white bg-dark sidebar">
        <button type="button" class="close_side"><i class="fa-solid fa-close"></i></button>
        <p class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
            <span class=" sess_admin"><?php echo $_SESSION['emri'] . ' ' . $_SESSION['mbiemri'] ?></span>
        </p>
        <hr>
        <ul class="nav nav-pills flex-column mb-auto">
            <li><a href="../index.php" class="nav-link text-white">Ballina</a></li>
            <li class="nav-item"><a href="rezervoTermin.php" class="nav-link text-white">Terminet</a></li>
            <li><a href="terminet_e_mia.php" class="nav-link text-white active" aria-current="page">Terminet e mia</a></li>
            <li><a href="ankesat.php" class="nav-link text-white">Ankesat</a></li>
            <li><a href="historiaTermineve(pacientit).php" class="nav-link text-white">Historia e termineve</a></li>
        </ul>
        <hr>
        <div class="dropdown">
            <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="https://w7.pngwing.com/pngs/200/420/png-transparent-user-profile-computer-icons-overview-rectangle-black-data-thumbnail.png" alt="" width="32" height="32" class="rounded-circle me-2">
                <strong class="useri"><?php echo $_SESSION['username'] ?></strong>
            </a>
            <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
                <li><a class="dropdown-item" href="profili.php">Profile</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item" href="logout.php">Sign out</a></li>
            </ul>
        </div>
    </div>



    <?php
    $pacienti_sql = "SELECT * FROM patient_table WHERE numri_personal=:numri_personal";
    $pacienti_prep = $con->prepare($pacienti_sql);
    $pacienti_prep->bindParam(':numri_personal', $_SESSION['numri_personal']);
    $pacienti_prep->execute();
    $pacienti_fetch = $pacienti_prep->fetch();
    $emri_pacientit = $pacienti_fetch['emri'];
    $mbiemri_pacientit = $pacienti_fetch['mbiemri'];


    $sql = "SELECT * FROM terminet_e_mia WHERE emri_pacientit=:emri_pacientit AND mbiemri_pacientit=:mbiemri_pacientit";
    $prep = $con->prepare($sql);
    $prep->bindParam(':emri_pacientit', $emri_pacientit);
    $prep->bindParam(':mbiemri_pacientit', $mbiemri_pacientit);
    $prep->execute();
    $data = $prep->fetchAll();

    if (!$data) {
        $empty = 'empty';
    } else {
        $empty = '';
    }

    ?>
    <main class="main mainRes">
        <?php if ($empty == '') : ?>
            <table class="table table-striped text-center mt-2 table_patient">
                <thead>
                    <tr>
                        <th scope="col" style="display: none;">ID</th>
                        <th scope="col">Doktori</th>
                        <th scope="col">Departamenti</th>
                        <th scope="col">Data</th>
                        <th scope="col">Ora</th>
                        <th scope="col">Aksioni</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data as $data) : ?>
                        <tr>
                            <td class="idAnulo" style="display: none;"><?= $data['id'] ?></td>
                            <td><?= $data['doktori'] ?></td>
                            <td><?= $data['departamenti'] ?></td>
                            <td><?= $data['data'] ?></td>
                            <td><?= $data['ora'] ?></td>
                            <td class="text-center">
                                <a class="text-decoration-none text-white anuloPop ">
                                    <button class="btn btn-warning w-100 p-1 text-white rez">Anulo</button>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>


        <?php if ($empty == 'empty') : ?>
            <article style="margin-left: 200px; width: 100%;" class="mt-5">
                <h1 class=" h1 fw-normal text-center mt-5">Nuk ke termine te rezervuara.</h1>
            </article>
        <?php endif; ?>
    </main>

    <article id="popWrapper" class="popWrapper <?= $njoftim ?? '' ?>">

    </article>


    <div id="popWindow" class="popAnulo">
        <div class="pac_h5">
            <h5>Anulo terminin</h5>
            <button id="close" class="close">
                <i class="fa-solid fa-close rezervoClose"></i>
            </button>
        </div>

        <h5 class="det_pac_h4">Detajet e pacientit</h5>

        <div class="emri_pac">
            <p>Emri: <span><?= $pacienti_fetch['emri'] ?></span></p>
            <hr>
            <p>Mbiemri: <span><?= $pacienti_fetch['mbiemri'] ?></span></p>
            <hr>
            <p>Email: <span><?= $pacienti_fetch['email'] ?></span></p>
            <hr>
            <p>Numri personal: <span><?= $pacienti_fetch['numri_personal'] ?></span></p>
            <hr>
            <p>Nr. telefonit: <span><?= $pacienti_fetch['telefoni'] ?></span></p>
            <hr>
        </div>

        <h5 class="det_pac_h4">Detajet e terminit</h5>

        <div class="emri_pac doc_pac">

        </div>

        <form action="terminet_e_mia.php" method="POST" class="submit_anu">
            <div class="mb-3">
                <label for="arsyeja" class="form-label">Arsyeja e anulimit:</label>
                <textarea class="form-control text" id="arsyeja" rows="3" maxlength="250" name="arsyejaAnulimit"></textarea>
            </div>

            <button type="submit" name="anulo" class="anulo btn btn-primary w-25">Dergo</button>
        </form>
    </div>

    <div class="popMsg <?= $njoftim ?? '' ?>">
        <div class="pac_h5">
            <h5>Njoftim</h5>
            <button id="close" class="close">
                <i class="fa-solid fa-close rezervoClose"></i>
            </button>
        </div>
        <p class="mt-2 ps-1"><?php echo $msg; ?></p>
    </div>
</body>

</html>