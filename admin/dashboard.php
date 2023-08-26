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

    <main class="main p-4 flex-column ">
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
                        <tr class="table-info">
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
                                $statusColor = 'btn btn-info  text-white rounded p-1';
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

        <?php
        $searchedQuery = "";
        $showEntries;
        $entries = isset($_GET['entries']) ? $_GET['entries'] : 25;
        if (isset($_GET['entries'])) {
            $showEntries = $_GET['entries'];
            $searchedQuery = isset($_GET['keyword']) ? $_GET['keyword'] : "";
            if ($showEntries == 25) {
                $entry25 = 'selected';
            } else if ($showEntries == 50) {
                $entry50 = 'selected';
            } else if ($showEntries == 75) {
                $entry75 = 'selected';
            } else if ($showEntries == 100) {
                $entry100 = 'selected';
            }
        }


        $countSql = "SELECT COUNT(*) as total FROM users WHERE userType=2";
        $countPrep = $con->prepare($countSql);
        $countPrep->execute();
        $totalRows = $countPrep->fetch();

        $totalRows = $totalRows['total'];

        $totalPages = ceil($totalRows / $entries);

        $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;

        $startIndex = ($currentPage - 1) * $entries;


        $keywordPrep;
        if (isset($_GET['search']) && !empty($_GET['keyword'])) {
            $keyword = $_GET['keyword'];

            $depQuery = "SELECT id FROM departamentet WHERE name = :nameDep";
            $depPrep = $con->prepare($depQuery);
            $depPrep->bindParam(':nameDep', $keyword);
            $depPrep->execute();
            $depFetch = $depPrep->fetch();
            if ($depFetch) {
                $dep = $depFetch['id'];
            } else {
                $dep = '';
            }

            $sort = "SELECT u.id, u.fullName, d.name AS
                'dep_name' FROM users AS u INNER JOIN departamentet AS d ON u.departament = d.id 
                WHERE userType=2 AND (fullName=:keyword OR d.id='$dep' OR 
                username=:keyword OR email=:keyword OR phone=:keyword) LIMIT :startIndex, $entries";
            $sql = $sort;

            $prep = $con->prepare($sql);
            $prep->bindParam(':keyword', $keyword);
            $prep->bindParam(':keyword', $keyword);
            $prep->bindValue(':startIndex', $startIndex, PDO::PARAM_INT);
            $prep->execute();
            $data = $prep->fetchAll(PDO::FETCH_ASSOC);

            $searchedQuery = $keyword;
        } else {
            $sql = "SELECT u.id, u.fullName, d.name AS
            'dep_name' FROM users AS u INNER JOIN departamentet AS d ON u.departament = d.id 
            WHERE userType=2 LIMIT :startIndex, $entries";
            $prep = $con->prepare($sql);
            $prep->bindValue(':startIndex', $startIndex, PDO::PARAM_INT);
            $prep->execute();
            $data = $prep->fetchAll(PDO::FETCH_ASSOC);
        }

        if (!$data) {
            $empty = 'empty';
        } else {
            $empty = '';
        }
        ?>


        <article class="d-flex justify-content-between mt-5 gap-4">
            <section class="d-flex">
                <article class="appointmentsDashboard">
                    <h5 class="h5">Doctors</h5>
                    <hr>
                    <article class="d-flex flex-column">
                        <div class="d-flex justify-content-between pt-2">
                            <div>
                                <form id="entriesForm" method="GET" class="d-flex align-items-center w-25" action="">
                                    <input type="hidden" name="page" value="<?= $currentPage ?>">
                                    <select class="form-select" id="entries" aria-label="" name="entries" style="width: 80px; height: 38px" onchange="this.form.submit()">
                                        <option value="25" <?= $entry25 ?? '' ?>>25</option>
                                        <option value="50" <?= $entry50 ?? '' ?>>50</option>
                                        <option value="75" <?= $entry75 ?? '' ?>>75</option>
                                        <option value="100" <?= $entry100 ?? '' ?>>100</option>
                                    </select>
                                    <label for="entries" class="ms-2">entries</label>
                                </form>
                            </div>

                            <script>
                                $(document).ready(function() {
                                    $('#entries').change(function() {
                                        $('#entriesForm').submit();
                                    });
                                });
                            </script>



                            <div class="w-75 ms-2 me-1">
                                <form method="get" action="">
                                    <input type="hidden" name="entries" value="<?= $entries ?>">
                                    <input type="hidden" name="page" value="<?= $currentPage ?>">
                                    <div class="d-flex mb-1">
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control lastName" placeholder="Search:" aria-label="Search:" aria-describedby="button-addon2" name="keyword" value="<?= $searchedQuery ?>">
                                            <button class="btn btn-outline-primary" id="button-addon2" name="search"><i class="fa-solid fa-magnifying-glass"></i></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <?php if ($empty == '') : ?>
                            <table class="table table-hover">
                                <thead>
                                    <tr class="table-info">
                                        <th scope="col">Doctor</th>
                                        <th scope="col">Departament</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($data as $data) : ?>
                                        <tr class="selectDoctor" onclick="getId(this.id)" id="<?= $data['id'] ?>">
                                            <td><?= $data['fullName'] ?></td>
                                            <td><?= $data['dep_name'] ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>

                        <?php endif; ?>
                        <?php if ($empty == 'empty') { ?>
                            <article class=" d-flex justify-content-center mt-5">
                                <h1 class=" h1 fw-normal text-center mt-5">Data not found in database.</h1>
                            </article>
                        <?php } else { ?>
                            <nav aria-label="Page navigation example">
                                <ul class="pagination m-0 p-0">
                                    <?php
                                    $maxVisibleLinks = 5; // Maximum number of visible page links

                                    $startPage = max(1, $currentPage - floor($maxVisibleLinks / 2));
                                    $endPage = min($startPage + $maxVisibleLinks - 1, $totalPages);

                                    $showEllipsisStart = ($startPage > 1);
                                    $showEllipsisEnd = ($endPage < $totalPages);

                                    if ($currentPage == 1) {
                                        echo '<li class="page-item disabled"><a href="#" class="page-link" tabindex="-1">Previous</a></li>';
                                    }

                                    if ($currentPage > 1) {
                                        $previousPage = $currentPage - 1;
                                        echo '<li class"page-item"><a href="?page=' . $previousPage . '" class="page-link">Previous</a></li>';
                                    }

                                    for ($i = $startPage; $i <= $endPage; $i++) {
                                        $activePage = ($i == $currentPage) ? 'active' : '';
                                        echo '<li class="page-item"><a class="page-link ' . $activePage . '" href="?page=' . $i . '">' . $i . '</a></li>';
                                    }



                                    if ($currentPage < $totalPages) {
                                        $nextPage = $currentPage + 1;
                                        echo '<li class="page-item"><a href="?page=' . $nextPage . '" class="page-link">Next</a></li>';
                                    } else {
                                        echo '<li class="page-item disabled"><a href="#" class="page-link" abindex="-1">Next</a></li>';
                                    }
                                    ?>
                                </ul>
                            </nav>
                        <?php } ?>
                    </article>
            </section>
            <article class="appointmentsDashboard w-75 d-flex justify-content-center">
                <article class="w-100 doctorWork d-none">
                    <h5 class="h5">Doctors work</h5>
                    <hr>
                    <article class="d-flex flex-column">
                        <div class="d-flex justify-content-between pt-2">
                            <div class="w-100">
                                <form method="get" action="">
                                    <input type="hidden" name="entries" value="<?= $entries ?>">
                                    <input type="hidden" name="page" value="<?= $currentPage ?>">
                                    <div class="d-flex mb-1">
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control lastName" placeholder="Search:" aria-label="Search:" aria-describedby="button-addon2" name="keyword" value="<?= $searchedQuery ?>">
                                            <button class="btn btn-outline-primary" id="button-addon2" name="search"><i class="fa-solid fa-magnifying-glass"></i></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <?php if ($empty == '') : ?>
                            <table class="table table-hover">
                                <thead>
                                    <tr class="table-info">
                                        <th scope="col">Patient</th>
                                        <th scope="col">Personal ID</th>
                                        <th scope="col">Date</th>
                                        <th scope="col">Time</th>
                                    </tr>
                                </thead>
                                <tbody class="docTableBody">

                                </tbody>
                            </table>

                        <?php endif; ?>
                        <?php if ($empty == 'empty') { ?>
                            <article class=" d-flex justify-content-center mt-5">
                                <h1 class=" h1 fw-normal text-center mt-5">Data not found in database.</h1>
                            </article>
                        <?php } ?>
                    </article>
                </article>
                <article class="workNotFound d-flex justify-content-center align-items-center">
                    <h3 class="h3">Please select a doctor to see their work!</h3>
                </article>
                <article class="loaderWrapper d-flex d-none flex-column justify-content-center">
                    <article class="loader">
    
                    </article>
                </article>
            </article>

    </main>




</body>

</html>