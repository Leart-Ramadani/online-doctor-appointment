<?php include('config.php'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ballina</title>
    <link rel="stylesheet" href="./css/main.css">
    <link rel="stylesheet" href="./css/responsive.css">
    <link rel="shortcut icon" href="./photos/icon-hospital.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link rel="stylesheet" href="../bootstrap-5.1.3-examples/sidebars/sidebars.css">
    <script src="https://kit.fontawesome.com/a28016bfcd.js" crossorigin="anonymous" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous" defer></script>
    <script src="../bootstrap-5.1.3-examples/sidebars/sidebars.js" defer></script>
    <script src="./js/index.js" defer></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <!-- Swiper library -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css" />
    <!-- Font-awesome script -->
    <script src="./js/swipper.js"></script>
    <!-- JQuery link -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js" defer></script>
    <script src="../js/index.js" defer></script>
    <script src="../js/anulo.js" defer></script>
    <script src="./js/slider.js" defer></script>
    <!-- Font-awesome script -->
    <script src="https://kit.fontawesome.com/a28016bfcd.js" crossorigin="anonymous" defer></script>
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
                        <a href="index.php" class="nav-link active text-secondary" aria-current="page">Ballina</a>
                    </li>
                    <li class="nav-item">
                        <a href="galeria.php" class="nav-link text-white">Galeria</a>
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

    <?php
    $sql = "SELECT * FROM doctor_personal_info";
    $prep = $con->prepare($sql);
    $prep->execute();
    $data = $prep->fetchAll();
    ?>
    <main>
        <article class="blog_container">
            <img class="img" src="photos/blog.jpg">
            <div class="blog_des">
                <h1 class="fw-bold">Ne kujdesemi per shendetin tuaj</h1>
                <p class="fs-6">Lorem ipsum dolor sit amet, consectetur</p>
                <a href="./patientSide/rezervoTermin.php">
                    <button class="btn btn-outline-light btn_doc">Terminet</button>
                </a>
            </div>
        </article>

        <article id="doctors_art">
            <h1 class="personeli_h1">Doktoret tane ekspert</h1>
            <hr>
        </article>

        <div class="docActions">
            <button class="prevDoc"><div class="leftArrow"></div></button>
            <button class="nextBtn"><div class="rightArrow"></div></button>
        </div>

        <section class="docInfoWrapper">
            <?php foreach ($data as $data) : ?>
                <div class="doc_info">
                    <img src="./admin/uploads/<?= $data['foto'] ?>" alt="">
                    <div class="doc_des">
                        <h1><?= $data['fullName'] ?></h1>
                        <p><?= $data['departamenti'] ?></p>
                    </div>
                </div>
            <?php endforeach; ?>

        </section>






        <article id="doctors_art">
            <h1 class="personeli_h1">Lokacioni</h1>
            <hr>
        </article>
        <div class="mapWrapper">
            <div id="my-map-canvas">
                <iframe class="mapFrame" frameborder="0" src="https://www.google.com/maps/embed/v1/place?q=QKUK,+Pristina&key=AIzaSyBFw0Qbyq9zTFTd-tUY6dZWTgaQzuU17R8"></iframe>
            </div>
        </div>
    </main>
</body>

<div class="container">
    <footer class="py-3 my-4">
        <ul class="nav justify-content-center border-bottom pb-3 mb-3">
            <li class="nav-item"><a href="index.php" class="nav-link px-2 text-muted">Ballina</a></li>
            <li class="nav-item"><a href="galeria.php" class="nav-link px-2 text-muted">Galeria</a></li>
            <li class="nav-item"><a href="./patientSide/rezervoTermin.php" class="nav-link px-2 text-muted">Terminet</a></li>
            <li class="nav-item"><a href="./patientSide/ankesat.php" class="nav-link px-2 text-muted">Ankesat </a></li>
        </ul>
        <p class="text-center text-muted"> Copyright ©2023 All rights reserved | This website is made by <a href="https://www.linkedin.com/in/leart-ramadani-47981125a?lipi=urn%3Ali%3Apage%3Ad_flagship3_profile_view_base_contact_details%3BVVrswlowTROepZqoqZDZkw%3D%3D">Leart Ramadani</a>.</p>
    </footer>
</div>



</body>

</html>