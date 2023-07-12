<?php include('config.php'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ballina</title>
   
    <link rel="stylesheet" href="./css/responsive.css">
    <link rel="shortcut icon" href="./photos/icon-hospital.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    
    <script src="https://kit.fontawesome.com/a28016bfcd.js" crossorigin="anonymous" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous" defer></script>
   
    <script src="./js/index.js" defer></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <!-- Swiper library -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css" />
    <!-- Font-awesome script -->
    <!-- JQuery link -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js" defer></script>
   
    <link rel="stylesheet" href="./css/style1.css">
    <!-- Font-awesome script -->
    <script src="https://kit.fontawesome.com/a28016bfcd.js" crossorigin="anonymous" defer></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <style>
  .custom-dropdown-toggle {
    background-image: none !important;
    padding-right: 0 !important;
  }

  .custom-dropdown-toggle::after {
    display: none !important;
  }

  .custom-button.dropdown-toggle:focus {
    outline: none;
    box-shadow: none;
  }

  .custom-dropdown-toggle:focus {
    border-color: #17a2b8 !important; /* Change the color to your desired value */
    box-shadow: none !important; /* Optional: To remove the shadow effect */
  }
 .resbtn{
    display: none;
 }
@media (max-width: 776px) {
  .dropdown-menu {
    right: 0px !important;
   
  }
  
}
@media (max-width: 770px) {
  .login {
   width: 100px;
   margin-right: 20px!important;
  }
  .resbtn{
    display: block;
 }
 .nonresbtn{
    display: none;
 }
 .hospital{
    display: none;
 }
}
@media (max-width: 450px) {
  .blog_des h1{
font-size: 16px!important;
margin-bottom: 3px;
  }
  .blog_des p{
font-size: 12px!important;
margin-bottom: 5px;
  }
  .blog_des button{
font-size: 12px!important;
padding: 5px !important;
  }
}
@media (max-width: 700px) {
  .docActions{
    display: flex!important;
    justify-content:center!important;
    padding-right: 0!important;
  }
}
</style>
</head>

<body>
    <nav class="t navbar navbar-expand-md navbar-dark" style="background-color:#05204A" aria-label="Fourth navbar example">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsExample04" aria-controls="navbarsExample04" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarsExample04">
                <ul class="navbar-nav me-auto mb-2 mb-md-0">
                    <li class="nav-item">
                        <a href="index.php" class="nav-link active pt-0 pb-0" style="border-right: 1px solid white;color:#ADE8FF" aria-current="page">Ballina</a>
                    </li>
                    <li class="nav-item">
                        <a href="galeria.php" class="nav-link text-white pt-0 pb-0" style="border-right: 1px solid white">Galeria</a>
                    </li>
                    <li class="nav-item">
                        <a href="./patientSide/rezervoTermin.php" class="nav-link text-white pt-0 pb-0" style="border-right: 1px solid white">Terminet</a>
                    </li>
                    <li class="nav-item">
                        <a href="./patientSide/ankesat.php" class="nav-link text-white pt-0 pb-0">Ankesat</a>
                    </li>
                </ul>
            </div>
            <div class="loginBtn d-flex justify-content-center align-items-center">
                <?php if (!isset($_SESSION['emri']) && !isset($_SESSION['mbiemri'])) { ?>
                    <a href="./patientSide/login.php"><button type="button" class="login btn btn-outline-light me-2"><i class="fa-solid fa-arrow-right-to-bracket pe-1"></i> Login</button></a>
                <?php } else { ?>
                    <a href="./patientSide/logout.php"><button type="button" class="btn btn-outline-warning me-2"><i class="fa-solid fa-arrow-right-to-bracket pe-1"></i> Log out</button></a>
                <?php } ?>
                <div class="dropdown pe-2">
                
                <button class="btn nonresbtn custom-btn btn-primary dropdown-toggle custom-dropdown-toggle" style="background-color:#17a2b8;border-color:#17a2b8" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Administrators <i class="fa-solid fa-chevron-down pe-2"></i>
                </button>
                <button class="btn resbtn custom-btn btn-primary dropdown-toggle custom-dropdown-toggle" style="background-color:#17a2b8;border-color:#17a2b8" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                     <i class="fa-solid fa-chevron-down" style="padding-right: 12px"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-left" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item w-75m-auto" style="color:#283B53" href="./admin/login.php">
                    <i class="fa-regular fa-user me-3 mw-3"></i>
               <span> Admin</span>
                </a>
                <a class="dropdown-item" style="color:#283B53" href="./doctorSide/login.php">
                <i class="fa-solid fa-user-doctor me-3 mw-3"></i>Staff
                </a>
                    </div>
                </div>
                
            </div>
        </div>
    </nav>

    <?php
    $sql = "SELECT u.fullName, u.photo, u.departament, d.name AS 'dep_name' FROM users AS u 
        INNER JOIN departamentet AS d ON u.departament=d.id WHERE userType=2";
    $prep = $con->prepare($sql);
    $prep->execute();
    $data = $prep->fetchAll();
    ?>
    <main>
        <article class="blog_container bg-primary-subtle">
            <img class="img" src="photos/Doctors/landing1.jpg">
            <div class="blog_des">
            <i class="fa-solid fa-truck-medical pb-3 hospital" style="font-size:50px;color:#17a2b8;"></i>
                <h1 class="fw-bold">Ne kujdesemi per <span >shendetin</span> tuaj</h1>
                <p class="fs-6">Lorem ipsum dolor sit amet, consectetur</p>
                <a href="./patientSide/rezervoTermin.php">
                    <button style="color:#17a2b8;border-color:#17a2b8" class="btn btn-outline-light btn_doc">Terminet</button>
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

        <section class="test w-100 p-5 overflow-hidden d-flex justify-content-start gap-3">
            <?php foreach ($data as $data) : ?>
                <div class="height-100 position-relative shadow-sm bg-white rounded-2" style="width:270px;height:250px;">
                   <div class="overflow-hidden rounded-2" style="width:270px;height:250px;">
                    <img class="object-fit-cover" style="width: 270px;" src="./admin/uploads/<?= $data['photo'] ?>" alt="">
                </div> 
                    <div class="doc_des p-3 position-absolute bottom-0">
                        <h1 class="fs-5 text-dark fw-medium"><?= $data['fullName'] ?></h1>
                        <p class="text-secondary fw-lighter"><?= $data['dep_name'] ?></p>
                    </div>
                </div>
            <?php endforeach; ?>

        </section>






        <article id="doctors_art">
            <h1 class="personeli_h1">Lokacioni</h1>
            <hr>
        </article>
        <div class="mapWrapper shadow-lg p-3 bg-white rounded-4 w-75" width="900px">
            <div id="my-map-canvas">
                <iframe class="mapFrame rounded-3" frameborder="0" src="https://www.google.com/maps/embed/v1/place?q=QKUK,+Pristina&key=AIzaSyBFw0Qbyq9zTFTd-tUY6dZWTgaQzuU17R8"></iframe>
            </div>
        </div>
    </main>
</body>

<div class="container">
    <footer class="pt-3 my-4">
        <ul class="nav justify-content-center border-bottom pb-3 mb-3">
            <li class="nav-item"><a href="index.php" class="nav-link px-2 text-muted">Ballina</a></li>
            <li class="nav-item"><a href="galeria.php" class="nav-link px-2 text-muted">Galeria</a></li>
            <li class="nav-item"><a href="./patientSide/rezervoTermin.php" class="nav-link px-2 text-muted">Terminet</a></li>
            <li class="nav-item"><a href="./patientSide/ankesat.php" class="nav-link px-2 text-muted">Ankesat </a></li>
        </ul>
        <p class="text-center text-muted"> Copyright ©2023 All rights reserved | This website is made by <a href="https://www.linkedin.com/in/leart-ramadani-47981125a?lipi=urn%3Ali%3Apage%3Ad_flagship3_profile_view_base_contact_details%3BVVrswlowTROepZqoqZDZkw%3D%3D">Leart Ramadani</a>.</p>
    </footer>
</div>


<script src="./js/slider.js"></script>
</body>

</html>