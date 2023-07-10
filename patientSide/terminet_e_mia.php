<?php
include('../config.php');
if (!isset($_SESSION['fullName'])) {
    header("Location: login.php");
}
?>

<?php


$d_sql = "SELECT * FROM users WHERE personal_id=:personal_id";
$d_prep = $con->prepare($d_sql);
$d_prep->bindParam(':personal_id', $_SESSION['numri_personal']);
$d_prep->execute();
$row = $d_prep->fetch();

$ter_sql = "SELECT * FROM terminet_e_mia WHERE id=:id";
$ter_prep = $con->prepare($ter_sql);
$ter_prep->bindParam(':id', $_SESSION['idAnulo']);
$ter_prep->execute();
$stm = $ter_prep->fetch();



$msg = '';
if (isset($_POST['anulo'])) {
    $pacienti = $stm['pacienti'];
    $numri_personal = $row['personal_id'];
    $email = $row['email'];
    $telefoni = $row['phone'];
    $doktori = $stm['doktori'];
    $departamenti = $stm['departamenti'];
    $data = $stm['data'];
    $ora = $stm['ora'];
    $arsyejaAnulimit = $_POST['arsyejaAnulimit'];

    if (empty($_POST['arsyejaAnulimit'])) {
        $msg = '*Shkruani arsyen se pse deshironi te anuloni terminin!';
        $njoftim = 'njoftim';
    } else {
        $req_sql = "INSERT INTO kerkesatanulimit(pacienti, numri_personal, email, telefoni, doktori, departamenti, data, ora, arsyeja_anulimit)
            VALUES(:pacienti, :numri_personal, :email, :telefoni, :doktori, :departamenti, :data, :ora, :arysejaAnulimit)";
        $res_prep = $con->prepare($req_sql);
        $res_prep->bindParam(':pacienti', $pacienti);
        $res_prep->bindParam(':numri_personal', $numri_personal);
        $res_prep->bindParam(':email', $email);
        $res_prep->bindParam(':telefoni', $telefoni);
        $res_prep->bindParam(':doktori', $doktori);
        $res_prep->bindParam(':departamenti', $departamenti);
        $res_prep->bindParam(':data', $data);
        $res_prep->bindParam(':ora', $ora);
        $res_prep->bindParam(':arysejaAnulimit', $arsyejaAnulimit);
        if ($res_prep->execute()) {
            $msg = "Kerkesa per te anuluar terminin eshte parashtruar me sukses. <br>
                    Ju do te njoftoheni permes email-it nese ju eshte aprovuar kerkesa!";
            $njoftim = 'njoftim';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terminet e mia</title>
    <link rel="shortcut icon" href="../photos/icon-hospital.png">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/responsive.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link rel="stylesheet" href="../bootstrap-5.1.3-examples/sidebars/sidebars.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous" defer></script>
    <script src="../bootstrap-5.1.3-examples/sidebars/sidebars.js" defer></script>
    <!-- JQuery link -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js" defer></script>
    <script src="../js/index.js" defer></script>
    <script src="../js/anulo.js" defer></script>
    <!-- Font-awesome script -->
    <script src="https://kit.fontawesome.com/a28016bfcd.js" crossorigin="anonymous" defer></script>

</head>

<body>
    <div class="sideBlock">
        <button type="button" class="ham" id="ham_menu"><i class="fa-solid fa-bars"></i></button>
    </div>


    <div class="flex-shrink-0 p-3 text-white bg-dark sidebar">
        <button type="button" class="close_side"><i class="fa-solid fa-close"></i></button>
        <p class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
            <span class=" sess_admin"><?php echo $_SESSION['fullName'] ?></span>
        </p>
        <hr>
        <ul class="nav nav-pills flex-column mb-auto">
            <li><a href="../index.php" class="nav-link text-white">Ballina</a></li>
            <li class="nav-item"><a href="rezervoTermin.php" class="nav-link text-white">Terminet</a></li>
            <li><a href="terminet_e_mia.php" class="nav-link text-white active" aria-current="page">Terminet e mia</a></li>
            <li><a href="ankesat.php" class="nav-link text-white">Ankesat</a></li>
            <li><a href="historiaTermineve(pacientit).php" class="nav-link text-white">Historia e termineve</a></li>
        </ul>
        <hr>
        <div class="dropdown">
            <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="https://w7.pngwing.com/pngs/200/420/png-transparent-user-profile-computer-icons-overview-rectangle-black-data-thumbnail.png" alt="" width="32" height="32" class="rounded-circle me-2">
                <strong class="useri"><?php echo $_SESSION['username'] ?></strong>
            </a>
            <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
                <li><a class="dropdown-item" href="profili.php">Profili</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
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


    $countSql = "SELECT COUNT(*) as total FROM terminet_e_mia WHERE numri_personal=:numri_personal";
    $countPrep = $con->prepare($countSql);
    $countPrep->bindParam(':numri_personal', $_SESSION['numri_personal']);
    $countPrep->execute();
    $totalRows = $countPrep->fetch();

    $totalRows = $totalRows['total'];

    $totalPages = ceil($totalRows / $entries);

    $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;

    $startIndex = ($currentPage - 1) * $entries;


    if ($sortBy == "default") {
        $sort = " ORDER BY DATE(data) LIMIT :startIndex, $entries";
        $sortDate = 'selected';
        $searchedQuery = isset($_GET['keyword']) ? $_GET['keyword'] : "";
    } else if ($sortBy == "ASC") {
        $sort = " ORDER BY doktori ASC LIMIT :startIndex, $entries";
        $sortASC = 'selected';
        $searchedQuery = isset($_GET['keyword']) ? $_GET['keyword'] : "";
    } else if ($sortBy == "DESC") {
        $sort = " ORDER BY doktori DESC LIMIT :startIndex, $entries";
        $sortDESC = 'selected';
        $searchedQuery = isset($_GET['keyword']) ? $_GET['keyword'] : "";
    } else if ($sortBy == "date") {
        $sort = " ORDER BY DATE(data) LIMIT :startIndex, $entries";
        $sortDate = 'selected';
        $searchedQuery = isset($_GET['keyword']) ? $_GET['keyword'] : "";
    }


    $keywordPrep;
    if (isset($_GET['search']) && !empty($_GET['keyword'])) {
        $keyword = $_GET['keyword'];

        $pacienti_sql = "SELECT * FROM users WHERE personal_id=:personal_id";
        $pacienti_prep = $con->prepare($pacienti_sql);
        $pacienti_prep->bindParam(':personal_id', $_SESSION['numri_personal']);
        $pacienti_prep->execute();
        $pacienti_fetch = $pacienti_prep->fetch();
        $pacienti = $pacienti_fetch['fullName'];


        $sort = "SELECT * FROM terminet_e_mia WHERE numri_personal=:numri_personal AND (doktori=:keyword OR departamenti=:keyword) " . $sort;
        $sql = $sort;

        $prep = $con->prepare($sql);
        $prep->bindParam(':keyword', $keyword);
        $prep->bindParam(':numri_personal', $_SESSION['numri_personal']);
        $prep->bindValue(':startIndex', $startIndex, PDO::PARAM_INT);
        $prep->execute();
        $data = $prep->fetchAll(PDO::FETCH_ASSOC);

        $searchedQuery = $keyword;
    } else {
        $pacienti_sql = "SELECT * FROM users WHERE personal_id=:personal_id";
        $pacienti_prep = $con->prepare($pacienti_sql);
        $pacienti_prep->bindParam(':personal_id', $_SESSION['numri_personal']);
        $pacienti_prep->execute();
        $pacienti_fetch = $pacienti_prep->fetch();
        $pacienti = $pacienti_fetch['fullName'];

        $sql = "SELECT * FROM terminet_e_mia WHERE numri_personal=:numri_personal" . $sort;
        $prep = $con->prepare($sql);
        $prep->bindParam(':numri_personal', $_SESSION['numri_personal']);
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
    <main class="main mainRes d-flex flex-column p-2">
        <div class="d-flex justify-content-between">
            <div>
                <form id="entriesForm" method="GET" class="d-flex align-items-center w-25" action="terminet_e_mia.php">
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
                    <form id="sortForm" method="GET" class="d-flex align-items-center" action="terminet_e_mia.php">
                        <input type="hidden" name="entries" value="<?= $entries ?>">
                        <input type="hidden" name="page" value="<?= $currentPage ?>">
                        <select class="form-select" id="sortBy" name="sortBy" aria-label="Default select example" style="height: 58px" onchange="this.form.submit()">
                            <option value="ASC" <?= $sortASC ?? "" ?>>Sipas renditjes A-Zh</option>
                            <option value="DESC" <?= $sortDESC ?? "" ?>>Sipas renditjes Zh-A</option>
                            <option value="date" <?= $sortDate ?? "" ?>>Sipas datës</option>
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
                    <form method="get" action="terminet_e_mia.php">
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
            <table class="table table-striped text-center mt-2 table_patient">
                <thead>
                    <tr>
                        <th scope="col" style="display: none;">ID</th>
                        <th scope="col">Doktori</th>
                        <th scope="col">Departamenti</th>
                        <th scope="col">Data</th>
                        <th scope="col">Ora</th>
                        <th scope="col">Aksioni</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data as $data) : ?>
                        <tr>
                            <td class="idAnulo" style="display: none;"><?= $data['id'] ?></td>
                            <td><?= $data['doktori'] ?></td>
                            <td><?= $data['departamenti'] ?></td>
                            <td><?= $data['data'] ?></td>
                            <td><?= $data['ora'] ?></td>
                            <td class="text-center">
                                <a class="text-decoration-none text-white anuloPop">
                                    <button class="btn btn-warning w-100 p-1 text-white rez">Anulo</button>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>



        <?php if ($empty == 'empty') { ?>
            <article class=" d-flex justify-content-center mt-5">
                <h1 class=" h1 fw-normal text-center mt-5">Nuk keni ndonje termin te rezervuar.</h1>
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
    </main>

    <article id="popWrapper" class="popWrapper <?= $njoftim ?? "" ?>">

    </article>


    <div id="popWindow" class="popAnulo">
        <div class="pac_h5">
            <h5>Anulo terminin</h5>
            <button id="close" class="close">
                <i class="fa-solid fa-close rezervoClose"></i>
            </button>
        </div>

        <h5 class="det_pac_h4">Detajet e pacientit</h5>

        <div class="emri_pac">
            <p>Pacienti: <span><?= $pacienti_fetch['fullName'] ?></span></p>
            <hr>
            <p>Email: <span><?= $pacienti_fetch['email'] ?></span></p>
            <hr>
            <p>Nr. personal: <span><?= $pacienti_fetch['personal_id'] ?></span></p>
            <hr>
            <p>Nr. telefonit: <span><?= $pacienti_fetch['phone'] ?></span></p>
            <hr>
        </div>

        <h5 class="det_pac_h4">Detajet e terminit</h5>

        <div class="emri_pac doc_pac">

        </div>

        <form action="terminet_e_mia.php" method="POST" class="submit_anu">
            <div class="mb-3">
                <label for="arsyeja" class="form-label">Arsyeja e anulimmit:</label>
                <textarea class="form-control text" id="arsyeja" rows="3" maxlength="250" name="arsyejaAnulimit"></textarea>
            </div>

            <button type="submit" name="anulo" class="anulo btn btn-primary w-25">Dergo</button>
        </form>
    </div>

    <div class="popMsg <?= $njoftim ?? '' ?>">
        <div class="pac_h5">
            <h5>Njoftim</h5>
            <button id="close" class="close">
                <i class="fa-solid fa-close rezervoClose"></i>
            </button>
        </div>
        <p class="mt-2 ps-1"><?php echo $msg; ?></p>
    </div>
</body>

</html>