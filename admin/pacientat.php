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


    $sortDefault = "default";

    $sortBy = isset($_GET['sortBy']) ? $_GET['sortBy'] : $sortDefault;

    $sort = "";


    $countSql = "SELECT COUNT(*) as total FROM patient_table";
    $countPrep = $con->prepare($countSql);
    $countPrep->execute();
    $totalRows = $countPrep->fetch();

    $totalRows = $totalRows['total'];

    $totalPages = ceil($totalRows / $entries);

    $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;

    $startIndex = ($currentPage - 1) * $entries;


    if ($sortBy == "default") {
        $sort = " ORDER BY emri ASC LIMIT :startIndex, $entries";
        $sortASC = 'selected';
        $searchedQuery = isset($_GET['keyword']) ? $_GET['keyword'] : "";
    } else if ($sortBy == "ASC") {
        $sort = " ORDER BY emri ASC LIMIT :startIndex, $entries";
        $sortASC = 'selected';
        $searchedQuery = isset($_GET['keyword']) ? $_GET['keyword'] : "";
    } else if ($sortBy == "DESC") {
        $sort = " ORDER BY emri DESC LIMIT :startIndex, $entries";
        $sortDESC = 'selected';
        $searchedQuery = isset($_GET['keyword']) ? $_GET['keyword'] : "";
    }


    $keywordPrep;
    if (isset($_GET['search']) && !empty($_GET['keyword'])) {
        $keyword = $_GET['keyword'];

        $sort = "SELECT * FROM patient_table WHERE emri=:keyword OR mbiemri=:keyword OR numri_personal=:keyword OR email=:keyword OR 
        adresa=:keyword OR username=:keyword OR telefoni=:keyword OR gjinia=:keyword" . $sort;
        $sql = $sort;

        $prep = $con->prepare($sql);
        $prep->bindParam(':keyword', $keyword);
        $prep->bindValue(':startIndex', $startIndex, PDO::PARAM_INT);
        $prep->execute();
        $data = $prep->fetchAll(PDO::FETCH_ASSOC);

        $searchedQuery = $keyword;
    } else {

        $sql = "SELECT * FROM patient_table" . $sort;
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
    <main class="text-center main mainRes d-flex flex-column align-items-center">



        <div class="d-flex justify-content-between w-100 p-2 ">
            <div>
                <form id="entriesForm" method="GET" class="d-flex align-items-center w-25" action="">
                    <input type="hidden" name="sortBy" value="<?= $sortBy ?>">
                    <input type="hidden" name="page" value="<?= $currentPage ?>">
                    <label for="entries" class="me-2">Shfaq</label>
                    <select class="form-select" id="entries" aria-label="" name="entries" style="width: 80px; height: 58px" onchange="this.form.submit()">
                        <option value="25" <?= $entry25 ?? '' ?>>25</option>
                        <option value="50" <?= $entry50 ?? '' ?>>50</option>
                        <option value="75" <?= $entry75 ?? '' ?>>75</option>
                        <option value="100" <?= $entry100 ?? '' ?>>100</option>
                    </select>
                    <label for="entries" class="ms-2">rreshta</label>
                </form>
            </div>

            <script>
                $(document).ready(function() {
                    $('#entries').change(function() {
                        $('#entriesForm').submit();
                    });
                });
            </script>


            <div class="d-flex w-75 justify-content-end pe-2">
                <div class="w-25">
                    <form id="sortForm" method="GET" class="d-flex align-items-center" action="">
                        <input type="hidden" name="entries" value="<?= $entries ?>">
                        <input type="hidden" name="page" value="<?= $currentPage ?>">
                        <select class="form-select" id="sortBy" name="sortBy" aria-label="Default select example" style="height: 58px" onchange="this.form.submit()">
                            <option value="ASC" <?= $sortASC ?? "" ?>>Sipas renditjes A-Zh</option>
                            <option value="DESC" <?= $sortDESC ?? "" ?>>Sipas renditjes Zh-A</option>
                        </select>
                    </form>
                </div>
                <script>
                    $(document).ready(function() {
                        $('#sortBy').change(function() {
                            $('#sortForm').submit();

                        });
                    });
                </script>
                <div class="w-50 ms-2 me-1">
                    <form method="get" action="">
                        <input type="hidden" name="entries" value="<?= $entries ?>">
                        <input type="hidden" name="sortBy" value="<?= $sortBy ?>">
                        <input type="hidden" name="page" value="<?= $currentPage ?>">
                        <div class="d-flex mb-1">
                            <div class="form-floating w-75">
                                <input type="text" class="form-control lastName" id="floatingInput" name="keyword" placeholder="Kerkro:" value="<?= $searchedQuery ?>">
                                <label for="floatingInput">Kerko:</label>
                            </div>
                            <button class="btn btn-primary w-25 fs-5 ms-2" name="search">Kerko</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <?php if ($empty == '') : ?>
            <table class="table table-striped mt-2 table_patient ">
                <thead>
                    <tr>
                        <th scope="col">Emri plote</th>
                        <th scope="col">Nr. personal</th>
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
                            <td><?= $data['emri'] . ' ' . $data['mbiemri'] ?></td>
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

        <?php if ($empty == 'empty') { ?>
            <article class=" d-flex justify-content-center mt-5">
                <h1 class=" h1 fw-normal text-center mt-5">Te dhenat nuk u gjenden ne databaze.</h1>
            </article>
        <?php } else { ?>
            <nav aria-label="Page navigation example" class="w-100 ps-2">
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
                    } else{
                        echo '<li class="page-item disabled"><a href="#" class="page-link" abindex="-1">Next</a></li>';
                    }
                    ?>
                </ul>
            </nav>
        <?php } ?>
    </main>


</body>

</html>