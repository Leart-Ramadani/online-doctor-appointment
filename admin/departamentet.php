<?php
include('../config.php');
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
}
?>
<?php include('header.php'); ?>
<title>Departaments</title>
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
            <li class="nav-item"><a href="doktoret.php" class="nav-link text-white">Doctors</a></li>
            <li><a href="departamentet.php" class="nav-link text-white active" aria-current="page">Departaments</a></li>
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
    <?php
    $depErr = '';
    if (isset($_POST['submit'])) {
        function testInput($data)
        {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        if (empty($_POST['departamenti'])) {
            $depErr = '*You must fill out this input.';
            $invalid_dep = 'is-invalid';
        } else {
            $departament = testInput($_POST['departamenti']);
            if (!preg_match("/^[a-z A-z]*$/", $departament)) {
                $depErr = '*Only alphabetical characters are allowed.';
                $invalid_dep = 'is-invalid';
            } else {
                $sql = "SELECT * FROM departamentet WHERE name=:departamenti";
                $stm = $con->prepare($sql);
                $stm->bindParam(':departamenti', $departament);
                $stm->execute();
                $row = $stm->fetch();

                if ($row) {
                    $depErr = '*This departament exists.';
                    $invalid_dep = 'is-invalid';
                } else {
                    $depErr = '';
                }
            }
        }

        if ($depErr == '') {
            $sql = "INSERT INTO departamentet(name) VALUES(:departamenti)";
            $prep = $con->prepare($sql);
            $prep->bindParam(":departamenti", $departament);
            $prep->execute();
        }
    }
    ?>

 

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

    $countSql = "SELECT COUNT(*) as total FROM departamentet";
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

        $sort = "SELECT * FROM departamentet WHERE NOT id=0 AND (name=:keyword) LIMIT :startIndex, $entries";
        $sql = $sort;

        $prep = $con->prepare($sql);
        $prep->bindParam(':keyword', $keyword);
        $prep->bindValue(':startIndex', $startIndex, PDO::PARAM_INT);
        $prep->execute();
        $data = $prep->fetchAll(PDO::FETCH_ASSOC);

        $searchedQuery = $keyword;
    } else {
        $sql = "SELECT * FROM departamentet WHERE NOT id=0 LIMIT :startIndex, $entries";
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
   <main class="text-center main mainRes">
        <form method="POST" autocomplete="off" class="form-signin text-center departament d-flex flex-column justify-content-center">
            <h1 class="h3 mb-3 fw-normal">Add a departament</h1>
            <div class="input-group mb-1">
                <input type="text" class="form-control lastName  <?= $invalid_dep ?? "" ?>" placeholder="Add departament:" aria-label="Add departament:" aria-describedby="button-addon2" name="departamenti">
                <button class="btn btn-outline-primary" type="submit" id="button-addon2" name="submit" title="Add departament"><i class="fa-solid fa-circle-plus"></i></button>
            </div>
            <span class="text-danger fw-normal"><?php echo $depErr; ?></span>
        </form>



    <article class="table_wrapper d-flex flex-column align-items-center p-2">
        <div class="d-flex justify-content-between pt-2">
            <div>
                <form id="entriesForm" method="GET" class="d-flex align-items-center" action="departamentet.php">
                    <input type="hidden" name="page" value="<?= $currentPage ?>">
                    <label for="entries" class="me-2">Show</label>
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



            <div class="ms-2 me-1">
                <form method="get" action="departamentet.php">
                    <input type="hidden" name="entries" value="<?= $entries ?>">
                    <input type="hidden" name="sortBy" value="<?= $sortBy ?>">
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
            <table class="table table-striped table-borderd text-center">
                <thead>
                    <tr>
                        <th scope="col" class="text-center">#</th>
                        <th scope="col">Departament</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data as $data) : ?>
                        <tr>
                            <th scope="row" class="text-center"><?= $data['id'] ?></th>
                            <td><?= $data['name'] ?></td>
                            <td>
                                <a class="text-decoration-none text-white" title="Delete" href="deleteDepartament.php?id=<?= $data['id']  ?>">
                                    <button class="btn btn-danger p-1 text-white"><i class="fa-solid fa-trash ps-1 pe-1"></i></button>
                                </a>
                            </td>
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
            <nav aria-label="Page navigation example" class="w-100 ps-1">
                <ul class="pagination">
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
   </main>
</body>

</html>