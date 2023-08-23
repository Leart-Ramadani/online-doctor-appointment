<?php
include('../config.php');
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
}

$countDoc_sql = "SELECT COUNT(*) AS totalDoctors FROM users WHERE userType=2";
$countDoc_prep = $con->prepare($countDoc_sql);
$countDoc_prep->execute();
$countDoc = $countDoc_prep->fetch(PDO::FETCH_ASSOC);

$countPatients_sql = "SELECT COUNT(*) AS totalPatients FROM users WHERE userType=1";
$countPatients_prep = $con->prepare($countPatients_sql);
$countPatients_prep->execute();
$countPatients = $countPatients_prep->fetch(PDO::FETCH_ASSOC);

$countAppointments_sql = "SELECT COUNT(*) AS totalAppointments FROM terminet";
$countAppointments_prep = $con->prepare($countAppointments_sql);
$countAppointments_prep->execute();
$countAppointments = $countAppointments_prep->fetch(PDO::FETCH_ASSOC);

?>
<?php include('header.php') ?>
<title>Dashboard</title>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="../js/dashboard.js"></script>
</head>

<body style="background-color: #F1F5FC;">
    <div class="sideBlock">
        <button type="button" class="ham" id="ham_menu"><i class="fa-solid fa-bars"></i></button>
    </div>

    <div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark sidebar">
        <button type="button" class="close_side"><i class="fa-solid fa-close"></i></button>
        <p class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
            <span class="fs-5"><?php echo $_SESSION['admin'] ?></span>
        </p>
        <hr style="margin: 10px 0 !important;">
        <ul class="nav nav-pills mb-auto">
            <li class="nav-item"><a href="dashboard.php" class="nav-link text-white active" aria-current="page">Dashboard</a></li>
            <li class="nav-item"><a href="doktoret.php" class="nav-link text-white">Doctors</a></li>
            <li><a href="departamentet.php" class="nav-link text-white">Departaments</a></li>
            <li><a href="orari.php" class="nav-link text-white">Schedule</a></li>
            <li><a href="terminet.php" class="nav-link text-white">Appointments</a></li>
            <li><a href="pacientat.php"" class=" nav-link text-white">Patients</a></li>
            <li><a href="historiaTerminit.php" class="nav-link text-white">Appointments history</a></li>
            <li class="nav-item"><a href="galeria.php" class="nav-link text-white">Gallery</a></li>
            <li><a href="ankesat.php" class="nav-link text-white">Complaints</a></li>
            <li><a href="kerkesatAnulimit.php" class="nav-link text-white">Cancelation requests</a></li>
            <li><a href="prices.php" class="nav-link text-white">Prices</a></li>
            <li><a href="payments.php" class="nav-link text-white">Payments</a></li>
            <li><a href="references.php" class="nav-link text-white">References</a></li>
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

    <main class="main p-4 flex-column align-items-center">
        <div class="d-flex justify-content-center">
            <div class="d-flex flex-wrap gap-3">
                <div class="dashboardCard-1">
                    <div>
                        <p class="fs-4">Doctors</p>
                        <div>
                            <?= $countDoc['totalDoctors'] ?>
                            <div class="dashboardImage">
                                <img src="../photos/whitedoctor.png">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="dashboardCard-1">
                    <div>
                        <p class="fs-4">Patients</p>
                        <div>
                            <?= $countPatients['totalPatients'] ?>
                            <div class="dashboardImage">
                                <img src="../photos/patient.png">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="dashboardCard-1">
                    <div>
                        <p class="fs-4">Appointments</p>
                        <div>
                            <?= $countAppointments['totalAppointments'] ?>
                            <div class="dashboardImage">
                                <img src="../photos/appointment.png">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php
        $sql = "SELECT * FROM terminet LIMIT 1,5";
        $prep = $con->prepare($sql);
        $prep->execute();
        $data = $prep->fetchAll(PDO::FETCH_ASSOC);
        ?>

        <section class="d-flex justify-content-between w-100 gap-5 mt-5">
            <article class="appointmentsDashboard w-100">
                <h5 class="h5">Appointments</h5>
                <hr>
                <table class="table table-hover mt-2">
                    <thead>
                        <tr class="table-primary">
                            <th scope="col">Doctor</th>
                            <th scope="col">Patient</th>
                            <th scope="col">Date</th>
                            <th scope="col">Time</th>
                            <th scope="col" class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data as $data) {
                            if ($data['statusi'] == 'Booked') {
                                $statusColor = 'btn btn-warning  text-white rounded p-1';
                            } else if ($data['statusi'] == 'Canceled') {
                                $statusColor = 'btn btn-danger rounded p-1';
                            } else if ($data['statusi'] == 'In progres') {
                                $statusColor = 'btn btn-warning text-white rounded p-1';
                            } else if ($data['statusi'] == 'Transfered') {
                                $statusColor = 'btn btn-primary text-white rounded p-1';
                            } else if ($data['statusi'] == 'Completed') {
                                $statusColor = 'btn btn-success text-white rounded p-1';
                            }
                            $date = date_create($data['data']);
                            $date = date_format($date, "d/m/Y");

                            $time = date_create($data['ora']);
                            $time = date_format($time, "H:i");
                        ?>
                            <tr>
                                <td>Dr.<?= $data['doktori'] ?></td>
                                <td><?= $data['pacienti'] ?></td>
                                <td><?= $date ?></td>
                                <td><?= $time ?> </td>
                                <td class="text-center"><span class="<?= $statusColor ?>"><?= $data['statusi'] ?></span></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <a href="./terminet.php" class="">
                    <button class="btn btn-dark">View all</button>
                </a>
            </article>
            <div class="appointmentsDashboard">
                <h5 class="h5">Appointments chart</h5>
                <hr>
                <canvas id="myChart"></canvas>
            </div>
        </section>

    </main>




</body>

</html>