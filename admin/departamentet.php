<?php
include('../config.php');
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
}
?>
<?php include('header.php'); ?>
<title>Departamentet</title>
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
            <li class="nav-item"><a href="doktoret.php" class="nav-link text-white">Doktoret</a></li>
            <li><a href="departamentet.php" class="nav-link text-white active" aria-current="page">Departamentet</a></li>
            <li><a href="orari.php" class="nav-link text-white">Orari</a></li>
            <li><a href="terminet.php" class="nav-link text-white">Terminet</a></li>
            <li><a href="pacientat.php"" class=" nav-link text-white">Pacientat</a></li>
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
            $depErr = '*Departamenti duhet plotesuar.';
            $invalid_dep = 'is-invalid';
        } else {
            $departament = testInput($_POST['departamenti']);
            if (!preg_match("/^[a-z A-z]*$/", $departament)) {
                $depErr = '*Nuk lejohen karaktere tjera perveq shkronjave.';
                $invalid_dep = 'is-invalid';
            } else {
                $sql = "SELECT * FROM departamentet WHERE departamenti=:departamenti";
                $stm = $con->prepare($sql);
                $stm->bindParam(':departamenti', $departament);
                $stm->execute();
                $row = $stm->fetch();

                if ($row) {
                    $depErr = '*Ky departament ekziston ne databaze.';
                    $invalid_dep = 'is-invalid';
                } else {
                    $depErr = '';
                }
            }
        }

        if ($depErr == '') {
            $sql = "INSERT INTO departamentet(departamenti) VALUES(:departamenti)";
            $prep = $con->prepare($sql);
            $prep->bindParam(":departamenti", $departament);
            $prep->execute();
        }
    }
    ?>

    <main class="text-center main mainRes">
        <form method="POST" autocomplete="off" class="form-signin text-center departament">
            <h1 class="h3 mb-3 fw-normal">Shto nje departament</h1>
            <div class="form-floating">
                <input type="text" class="form-control <?= $invalid_dep ?? "" ?>" id="floatingPassword" name="departamenti" placeholder="Departamenti">
                <label for="floatingPassword">Departamenti</label>
                <span class="text-danger fw-normal"><?php echo $depErr; ?></span>
                <button class="w-100 btn btn-lg btn-primary mt-2" type="submit" name="submit">Shto</button>
            </div>
        </form>


    </main>

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


    $countSql = "SELECT COUNT(*) as total FROM departamentet";
    $countPrep = $con->prepare($countSql);
    $countPrep->execute();
    $totalRows = $countPrep->fetch();

    $totalRows = $totalRows['total'];

    $totalPages = ceil($totalRows / $entries);

    $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;

    $startIndex = ($currentPage - 1) * $entries;


    if ($sortBy == "default") {
        $sort = " ORDER BY departamenti ASC LIMIT :startIndex, $entries";
        $sortASC = 'selected';
        $searchedQuery = isset($_GET['keyword']) ? $_GET['keyword'] : "";
    } else if ($sortBy == "ASC") {
        $sort = " ORDER BY departamenti ASC LIMIT :startIndex, $entries";
        $sortASC = 'selected';
        $searchedQuery = isset($_GET['keyword']) ? $_GET['keyword'] : "";
    } else if ($sortBy == "DESC") {
        $sort = " ORDER BY departamenti DESC LIMIT :startIndex, $entries";
        $sortDESC = 'selected';
        $searchedQuery = isset($_GET['keyword']) ? $_GET['keyword'] : "";
    }


    $keywordPrep;
    if (isset($_GET['search']) && !empty($_GET['keyword'])) {
        $keyword = $_GET['keyword'];

        $sort = "SELECT * FROM departamentet WHERE departamenti=:keyword " . $sort;
        $sql = $sort;

        $prep = $con->prepare($sql);
        $prep->bindParam(':keyword', $keyword);
        $prep->bindValue(':startIndex', $startIndex, PDO::PARAM_INT);
        $prep->execute();
        $data = $prep->fetchAll(PDO::FETCH_ASSOC);

        $searchedQuery = $keyword;
    } else {
        $sql = "SELECT * FROM departamentet" . $sort;
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

    <article class="table_wrapper d-flex flex-column align-items-center p-2">
        <div class="d-flex justify-content-between w-100">
             <div>
                 <form id="entriesForm" method="GET" class="d-flex align-items-center w-25" action="departamentet.php">
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
                     <form id="sortForm" method="GET" class="d-flex align-items-center" action="departamentet.php">
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
                     <form method="get" action="departamentet.php">
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
            <table class="table table-striped table-borderd w-50 text-center">
                <thead>
                    <tr>
                        <th scope="col" class="text-center">#</th>
                        <th scope="col">Departamenti</th>
                        <th scope="col">Aksioni</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data as $data) : ?>
                        <tr>
                            <th scope="row" class="text-center"><?= $data['id'] ?></th>
                            <td><?= $data['departamenti'] ?></td>
                            <td>
                                <a class="text-decoration-none text-white" href="deleteDepartament.php?id=<?= $data['id']  ?>">
                                    <button class="btn btn-danger w-100 p-1 text-white">Delete</button>
                                </a>
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
            <nav aria-label="Page navigation example">
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
    </article>

</body>

</html>