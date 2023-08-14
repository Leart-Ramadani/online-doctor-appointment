<?php
include('../config.php');
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
}
?>
<?php include('header.php') ?>
<title>Dashboard</title>
</head>

<body>
    <div class="sideBlock">
        <button type="button" class="ham" id="ham_menu"><i class="fa-solid fa-bars"></i></button>
    </div>

    <div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark sidebar" style="overflow-y: scroll;">
        <button type="button" class="close_side"><i class="fa-solid fa-close"></i></button>
        <p class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
            <span class="fs-5"><?php echo $_SESSION['admin'] ?></span>
        </p>
        <hr style="margin: 10px 0 !important;">
        <ul class="nav nav-pills flex-column mb-auto">
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

    <main class="main p-4 flex-column">
        <div class="d-flex justify-content-center">
            <div class="d-flex flex-wrap gap-3">
                <div class="dashboardCard-1">
                    <div>
                        <p>Todays Patients</p>
                        <div>
                            <img src="../photos/dashboardPatient.png">
                            231
                        </div>
                        <p>18% Higher Then Last Month</p>
                    </div>
                </div>
                <div class="dashboardCard-2">
                    <div>
                        <p>Appointments</p>
                        <div>
                            <img src="../photos/appointment.png">
                            122
                        </div>
                        <p>21% Higher Then Last Month</p>
                    </div>
                </div>
                <div class="dashboardCard-3">
                    <div>
                        <p>Registerd Patients</p>
                        <div>
                            <img src="../photos/dashboardPatient.png">
                            10
                        </div>
                        <p>2% Higher Then Last Month</p>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>

</html>