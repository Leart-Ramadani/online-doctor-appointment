<?php include('config.php'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galeria</title>
    <link rel="stylesheet" href="./css/main.css">
    <link rel="stylesheet" href="./css/responsive.css">
    <link rel="shortcut icon" href="./photos/icon-hospital.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link rel="stylesheet" href="../bootstrap-5.1.3-examples/sidebars/sidebars.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous" defer></script>
    <script src="../bootstrap-5.1.3-examples/sidebars/sidebars.js" defer></script>
    <!-- Swiper library -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css" />
    <!-- Lightbox library -->
    <link rel='stylesheet' type='text/css' media='screen' href='./css/lightbox.min.css'>
    <script src="./js/lightbox-plus-jquery.min.js"></script>
</head>

<body>
    <nav class="navbar navbar-expand-md navbar-dark bg-dark" aria-label="Fourth navbar example">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsExample04" aria-controls="navbarsExample04" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarsExample04">
                <ul class="navbar-nav me-auto mb-2 mb-md-0">
                    <li class="nav-item">
                        <a href="index.php" class="nav-link active text-white" aria-current="page">Ballina</a>
                    </li>
                    <li class="nav-item">
                        <a href="galeria.php" class="nav-link text-secondary">Galeria</a>
                    </li>
                    <li class="nav-item">
                        <a href="./patientSide/rezervoTermin.php" class="nav-link  text-white">Terminet</a>
                    </li>
                    <li class="nav-item">
                        <a href="./patientSide/ankesat.php" class="nav-link text-white">Ankesat</a>
                    </li>
                </ul>
            </div>
            <div class="loginBtn">
                <?php if (!isset($_SESSION['emri']) && !isset($_SESSION['mbiemri'])) { ?>
                    <a href="./patientSide/login.php"><button type="button" class="btn btn-outline-light me-2">Login</button></a>
                <?php } else { ?>
                    <a href="./patientSide/logout.php"><button type="button" class="btn btn-outline-warning me-2">Log out</button></a>
                <?php } ?>
                <a href="./admin/login.php">
                    <img src="./photos/admin-64px.png" alt="Admin" width="40px" title="Admin Side">
                </a>
                <a href="./doctorSide/login.php">
                    <img class="bg-light rounded-pill ms-2" src="./photos/doctor-64px.png" alt="Doctor Side" width="40px" title="Doctor Side">
                </a>
            </div>
        </div>
    </nav>

    <main>
        <?php
        $sql = "SELECT COUNT(*) as total FROM galeria";
        $prep = $con->prepare($sql);
        $prep->execute();
        $data = $prep->fetch();
        $totalImages = $data['total'];

        $totalPages = ceil($totalImages / 6);

        $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;

        $startIndex = ($currentPage - 1) * 6;

        $query = "SELECT * FROM galeria LIMIT :startIndex, 6";
        $stmt = $con->prepare($query);
        $stmt->bindValue(':startIndex', $startIndex, PDO::PARAM_INT);
        $stmt->execute();
        $images = $stmt->fetchAll(PDO::FETCH_ASSOC);

        ?>
        <article id="doctors_art">
            <h1>Galeria</h1>
            <hr>

            <section id="gallery">
                <?php foreach ($images as $image) : ?>
                    <a href="./admin/uploads_gallery/<?= $image['foto_src'] ?>" data-lightbox="mygallery">
                        <img src="./admin/uploads_gallery/<?= $image['foto_src'] ?>">
                    </a>
                <?php endforeach; ?>
                <article id="biggerImage">
                    <img src="">
                </article>
            </section>
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

    </main>

</body>

<div class="container">
    <footer class="py-3 my-4">
        <ul class="nav justify-content-center border-bottom pb-3 mb-3">
            <li class="nav-item"><a href="index.php" class="nav-link px-2 text-muted">Ballina</a></li>
            <li class="nav-item"><a href="galeria.php" class="nav-link px-2 text-muted">Galeria</a></li>
            <li class="nav-item"><a href="./patientSide/rezervoTermin.php" class="nav-link px-2 text-muted">Terminet</a></li>
            <li class="nav-item"><a href="./patientSide/ankesat.php" class="nav-link px-2 text-muted">Ankesat</a></li>
        </ul>
        <p class="text-center text-muted"> Copyright ©2023 All rights reserved | This website is made by <a href="https://www.linkedin.com/in/leart-ramadani-47981125a?lipi=urn%3Ali%3Apage%3Ad_flagship3_profile_view_base_contact_details%3BVVrswlowTROepZqoqZDZkw%3D%3D">Leart Ramadani</a>.</p>
    </footer>
</div>

</html>