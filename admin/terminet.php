<?php
include('../config.php');
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
}
?>

<?php include('header.php') ?>
<title>Terminet</title>
</head>

<body>
    <div class="sideBlock">
        <button type="button" class="ham" id="ham_menu"><i class="fa-solid fa-bars"></i></button>
    </div>
    <div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark sidebar">
        <button type="button" class="close_side"><i class="fa-solid fa-close"></i></button>
        <p class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white ">
            <span class="fs-4"><?php echo $_SESSION['admin'] ?></span>
        </p>
        <hr>
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item"><a href="doktoret.php" class="nav-link text-white">Doktoret</a></li>
            <li><a href="departamentet.php" class="nav-link text-white">Departamentet</a></li>
            <li><a href="orari.php" class="nav-link text-white">Orari</a></li>
            <li><a href="terminet.php" class="nav-link text-white active" aria-current="page">Terminet</a></li>
            <li><a href="pacientat.php" class="nav-link text-white">Pacientat</a></li>
            <li><a href="historiaTerminit.php" class="nav-link text-white">Historia termineve</a></li>
            <li class="nav-item"><a href="galeria.php" class="nav-link text-white">Galeria</a></li>
            <li><a href="ankesat.php" class="nav-link text-white">Ankesat</a></li>
            <li><a href="kerkesatAnulimit.php" class="nav-link text-white">Kerkesat per anulim</a></li>
        </ul>
        <hr>
        <div class="dropdown">
            <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="https://w7.pngwing.com/pngs/200/420/png-transparent-user-profile-computer-icons-overview-rectangle-black-data-thumbnail.png" alt="" width="32" height="32" class="rounded-circle me-2">
                <strong><?php echo $_SESSION['admin'] ?></strong>
            </a>
            <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">

                <li><a class="dropdown-item" href="logout.php">Sign out</a></li>
            </ul>
        </div>
    </div>

    <?php
    $sql = "SELECT * FROM terminet";
    $stm = $con->prepare($sql);
    $stm->execute();
    $data = $stm->fetchAll();

    if (!$data) {
        $empty = 'empty';
    } else {
        $empty = '';
    }
    ?>

    <?php if ($empty == '') : ?>

        <main class="main mainRes">
            <table class="table table-striped text-center mt-2 table_patient">
                <thead>
                    <tr>
                        <th scope="col">Doktori</th>
                        <th scope="col">Pacienti</th>
                        <th scope="col">Numri personal</th>
                        <th scope="col">Emaili</th>
                        <th scope="col">Data</th>
                        <th scope="col">Ora</th>
                        <th scope="col">Aksioni</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data as $data) : ?>
                        <tr>
                            <td><?= $data['doktori'] ?></td>
                            <td><?= $data['emri_pacientit'] . ' ' . $data['mbiemri_pacientit'] ?></td>
                            <td><?= $data['numri_personal'] ?></td>
                            <td><?= $data['email_pacientit'] ?></td>
                            <td><?= $data['data'] ?></td>
                            <td><?= $data['ora'] ?> </td>
                            <td class="text-center">
                                <a class="text-decoration-none text-white" href="deleteTerminin.php?id=<?= $data['id'] ?>">
                                    <button class="btn btn-danger w-100 p-1 text-white rez">Delete</button>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        <?php endif; ?>

        <?php if ($empty == 'empty') : ?>
            <article class="text-center">
                <h1 class=" h1 fw-normal text-center">Nuk u gjeten te dhena ne databaze.</h1>
            </article>
        <?php endif; ?>
        </main>




</body>

</html>