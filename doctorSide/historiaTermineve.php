<?php
include('../config.php');
if (!isset($_SESSION['doctor'])) {
    header("Location: login.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $_SESSION['doctor'] ?> | Appointments history</title>
    <link rel="icon" href="../photos/doctor.png">
    <link rel="stylesheet" href="../css/main.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link rel="stylesheet" href="../bootstrap-5.1.3-examples/sidebars/sidebars.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous" defer></script>
    <script src="../bootstrap-5.1.3-examples/sidebars/sidebars.js" defer></script>
    <!-- Font-awesome script -->
    <script src="https://kit.fontawesome.com/a28016bfcd.js" crossorigin="anonymous" defer></script>

    <style>
        .form-control {
            width: 280px !important;
        }
    </style>
</head>

<body>
    <div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark sidebar">
        <p class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
            <span class="fs-4"><?php echo $_SESSION['doctor'] ?></span>
        </p>
        <hr>
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item"><a href="terminet.php" class="nav-link text-white">Appointments</a></li>
            <li class="nav-item"><a href="historiaTermineve.php" class="nav-link active" aria-current="page">Appointments history</a></li>
        </ul>
        <hr>
        <div class="dropdown">
            <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="https://w7.pngwing.com/pngs/200/420/png-transparent-user-profile-computer-icons-overview-rectangle-black-data-thumbnail.png" alt="" width="32" height="32" class="rounded-circle me-2">
                <strong><?php echo $_SESSION['doc_username'] ?></strong>
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





    $countSql = "SELECT COUNT(*) as total FROM terminet WHERE doktori=:doktori AND statusi='Completed' OR statusi='Transfered'";
    $countPrep = $con->prepare($countSql);
    $countPrep->bindParam(':doktori', $_SESSION['doctor']);
    $countPrep->execute();
    $totalRows = $countPrep->fetch();

    $totalRows = $totalRows['total'];

    $totalPages = ceil($totalRows / $entries);

    $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;

    $startIndex = ($currentPage - 1) * $entries;


    $keywordPrep;
    if (isset($_GET['search']) && !empty($_GET['keyword'])) {
        $keyword = $_GET['keyword'];

        $sort = "SELECT t.id, t.pacienti, t.numri_personal, t.data, t.ora, t.diagnoza, c.code AS 'diagnose', t.recepti 
        FROM terminet AS t INNER JOIN icd_code AS c ON t.diagnoza=c.id 
        WHERE doktori=:doktori AND (statusi='Completed' OR statusi='Transfered') AND (pacienti=:keyword  OR
        data=:keyword OR ora=:keyword OR numri_personal=:keyword OR diagnoza=:keyword OR recepti=:keyword)
        LIMIT :startIndex, $entries";
        $sql = $sort;

        $prep = $con->prepare($sql);
        $prep->bindParam(':keyword', $keyword);
        $prep->bindParam(':doktori', $_SESSION['doctor']);
        $prep->bindValue(':startIndex', $startIndex, PDO::PARAM_INT);
        $prep->execute();
        $data = $prep->fetchAll(PDO::FETCH_ASSOC);

        $searchedQuery = $keyword;
    } else {

        $sql = "SELECT t.id, t.pacienti, t.numri_personal, t.data, t.ora, t.diagnoza, t.recepti, c.code AS 'diagnose'
        FROM terminet AS t INNER JOIN icd_code AS c ON t.diagnoza=c.id
        WHERE doktori=:doktori AND (statusi='Completed' OR statusi='Transfered') LIMIT :startIndex, $entries";
        $prep = $con->prepare($sql);
        $prep->bindParam(':doktori', $_SESSION['doctor']);
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
    <main class="main d-flex flex-column align-items-center p-2">
        <div class="d-flex justify-content-between w-100  pt-2">
            <div>
                <form id="entriesForm" method="GET" class="d-flex align-items-center w-25" action="">
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

            <div class="w-50 ms-2 me-1">
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
        </div>
        <?php if ($empty == '') : ?>
            <table class="table table-striped text-center">
                <thead>
                    <tr>
                        <th scope="col">Patient</th>
                        <th scope="col">Personal ID</th>
                        <th scope="col">Date</th>
                        <th scope="col">Time</th>
                        <th scope="col">Diagnose</th>
                        <th scope="col">Prescription</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        foreach ($data as $data) { 
                            $date = date_create($data['data']);
                            $date = date_format($date, "d/m/Y");
    
                            $time = date_create($data['ora']);
                            $time = date_format($time, "H:i");
                            if($data['diagnose'] == ""){
                                $diagnose = "Transfered";
                            } else{
                                $diagnose = $data['diagnose'];
                            }
                    ?>
                        <tr>
                            <td><?= $data['pacienti'] ?></td>
                            <td><?= $data['numri_personal'] ?></td>
                            <td><?= $date    ?></td>
                            <td><?= $time ?> </td>
                            <td><?= $diagnose ?></td>
                            <td><?= $data['recepti'] ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php endif; ?>

        <?php if ($empty == 'empty') { ?>
            <article class=" d-flex justify-content-center mt-5">
                <h1 class=" h1 fw-normal text-center mt-5">Data not found.</h1>
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
                        echo '<li class="page-item disabled"><a href="#" class="page-link">Previous</a></li>';
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
                        echo '<li class="page-item disabled"><a href="#" class="page-link" tabindex="-1">Next</a></li>';
                    }
                    ?>
                </ul>
            </nav>
        <?php } ?>

    </main>

</body>

</html>