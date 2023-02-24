<?php
include('../config.php');
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
}
?>
<?php include('header.php') ?>
<title>Pacientat</title>
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
            <li><a href="terminet.php" class="nav-link text-white">Terminet</a></li>
            <li><a href="pacientat.php" class="nav-link text-white active" aria-current="page">Pacientat</a></li>
            <li><a href="historiaTerminit.php" class="nav-link text-white">Historia e termineve</a></li>
            <li class="nav-item"><a href="galeria.php" class="nav-link text-white">Galeria</a></li>
            <li><a href="ankesat.php" class="nav-link text-white">Ankesat</a></li>
            <li><a href="kerkesatAnulimit.php" class="nav-link text-white">Kerkesat e anulimit te termineve</a></li>
        </ul>
        <hr>
        <div class="dropdown">
            <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="https://w7.pngwing.com/pngs/200/420/png-transparent-user-profile-computer-icons-overview-rectangle-black-data-thumbnail.png" alt="" width="32" height="32" class="rounded-circle me-2">
                <strong><?php echo $_SESSION['admin'] ?></strong>
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

    <main class="text-center main mainRes">


        <?php
        $sql = "SELECT * FROM patient_table";
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


            <table class="table table-striped mt-2 table_patient ">
                <thead>
                    <tr>
                        <th scope="col">Emri</th>
                        <th scope="col">Mbiemri</th>
                        <th scope="col">Numri personal</th>
                        <th scope="col">Gjinia</th>
                        <th scope="col">Email</th>
                        <th scope="col">Telefoni</th>
                        <th scope="col">Ditelindja</th>
                        <th scope="col">Adresa</th>
                        <th scope="col">Username</th>
                        <th scope="col">Aksioni</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data as $data) : ?>
                        <tr>
                            <td><?= $data['emri'] ?></td>
                            <td><?= $data['mbiemri'] ?></td>
                            <td><?= $data['numri_personal'] ?></td>
                            <td><?= $data['gjinia'] ?></td>
                            <td><?= $data['email'] ?></td>
                            <td class="p-2"><?= $data['telefoni'] ?></td>
                            <td class="p-2"><?= $data['ditlindja'] ?></td>
                            <td><?= $data['adresa'] ?></td>
                            <td><?= $data['username'] ?></td>
                            <td>
                                <a class="text-decoration-none text-white" href="deletePatient.php?id=<?= $data['id']  ?>"><button class="btn btn-danger w-100 p-0 text-white">Delete</button></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <?php if ($empty == 'empty') : ?>
            <article style="margin-left: 200px;" class="text-center">
                <h1 class=" h1 fw-normal text-center">Nuk ka asnje pacient te regjistruar.</h1>
            </article>
        <?php endif; ?>
    </main>

    </main>



</body>

</html>