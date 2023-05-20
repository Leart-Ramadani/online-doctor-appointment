<?php
include('../config.php');
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
}
?>
<?php include('header.php'); ?>
<title>Complaints</title>
</head>

<body>
    <div class="sideBlock">
        <button type="button" class="ham" id="ham_menu"><i class="fa-solid fa-bars"></i></button>
    </div>
    <div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark sidebar">
    <button type="button" class="close_side"><i class="fa-solid fa-close"></i></button>
        <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
            <span class="fs-4"><?php echo $_SESSION['admin'] ?></span>
        </a>
        <hr>
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item"><a href="doctors.php" class="nav-link text-white">Doctors</a></li>
            <li><a href="departamentet.php" class="nav-link text-white">Departaments</a></li>
            <li><a href="orari.php" class="nav-link text-white">Schedule</a></li>
            <li><a href="terminet.php" class="nav-link text-white">Appointments</a></li>
            <li><a href="pacientat.php"" class=" nav-link text-white">Patients</a></li>
            <li><a href="historiaTerminit.php" class="nav-link text-white">Appointments history</a></li>
            <li class="nav-item"><a href="galeria.php" class="nav-link text-white">Gallery</a></li>
            <li><a href="ankesat" class="nav-link text-white active" aria-current="page">Complaints</a></li>
            <li><a href="kerkesatAnulimit.php" class="nav-link text-white">Cancellation requests</a></li>
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


    <?php
    $sql = "SELECT * FROM ankesat";
    $stm = $con->prepare($sql);
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
            <table class="table table-striped table-borderd mt-2 table_patient">
                <thead>
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Last name</th>
                        <th scope="col">Personal ID</th>
                        <th scope="col">Email</th>
                        <th scope="col">Complaint</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data as $data) : ?>
                        <tr>
                            <td scope="row"><?= $data['emri'] ?></td>
                            <td scope="row"><?= $data['mbiemri'] ?></td>
                            <td scope="row"><?= $data['numri_personal'] ?></td>
                            <td scope="row"><?= $data['email'] ?></td>
                            <td scope="row"><?= $data['ankesa'] ?></td>
                            <td scope="row"><?= $data['permisimi'] ?></td>
                            <td>
                                <a class="text-decoration-none text-white" href="deleteAnkesen.php?id=<?= $data['id']  ?>">
                                    <button class="btn btn-danger w-100 p-1 text-white">Delete</button>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <?php if ($empty == 'empty') : ?>
            <article style="margin-left: 200px;" class="text-center">
                <h1 class=" h1 fw-normal text-center">Data not found.</h1>
            </article>
        <?php endif; ?>

    </main>



</body>

</html>