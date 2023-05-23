<?php
include('../config.php');
if (!isset($_SESSION['emri']) && !isset($_SESSION['mbiemri'])) {
    header("Location: login.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historia e termineve</title>
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
            <li><a href="terminet_e_mia.php" class="nav-link text-white">Terminet e mia</a></li>
            <li><a href="ankesat.php" class="nav-link text-white">Ankesat</a></li>
            <li><a href="historiaTermineve(pacientit).php" class="nav-link text-white active" aria-current="page">Historia e termineve</a></li>
        </ul>
        <hr>
        <div class="dropdown">
            <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="https://w7.pngwing.com/pngs/200/420/png-transparent-user-profile-computer-icons-overview-rectangle-black-data-thumbnail.png" alt="" width="32" height="32" class="rounded-circle me-2">
                <strong class="useri"><?php echo $_SESSION['username'] ?></strong>
            </a>
            <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
                <li><a class="dropdown-item" href="profili.php">Profili</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item" href="logout.php">Sign out</a></li>
            </ul>
        </div>
    </div>

    <?php
    $sql = "SELECT * FROM historia_e_termineve WHERE numri_personal=:numri_personal";
    $stm = $con->prepare($sql);
    $stm->bindParam(':numri_personal', $_SESSION['numri_personal']);
    $stm->execute();
    $data = $stm->fetchAll();

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
                        <th scope="col">Doktori</th>
                        <th scope="col">Departamenti</th>
                        <th scope="col">Data</th>
                        <th scope="col">Ora</th>
                        <th scope="col">Diagnoza</th>
                        <th scope="col">Recepti</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data as $data) : ?>
                        <tr>
                            <td><?= $data['doktori'] ?></td>
                            <td><?= $data['departamenti'] ?></td>
                            <td><?= $data['data'] ?></td>
                            <td><?= $data['ora'] ?> </td>
                            <td><?= $data['diagnoza'] ?></td>
                            <td><?= $data['recepti'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <?php if ($empty == 'empty') : ?>
            <article class="mt-5 dataNotfound">
                <h1 class=" h1 fw-normal text-center mt-5">Nuk u gjeten te dhena ne databaze.</h1>
            </article>
        <?php endif; ?>

    </main>

</body>

</html>