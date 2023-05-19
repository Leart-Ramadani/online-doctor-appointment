<?php
    include('../config.php');
    
    $id = $_GET['id'];

    $sql = "SELECT * FROM doctor_personal_info WHERE id=:id";
    $prep = $con->prepare($sql);
    $prep->bindParam(':id', $id);
    $prep->execute();
    $data = $prep->fetch();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $data['fullName'] ?></title>
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/responsive.css">
    <link rel="shortcut icon" href="../photos/doctor.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link rel="stylesheet" href="../bootstrap-5.1.3-examples/sidebars/sidebars.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous" defer></script>
    <script src="../bootstrap-5.1.3-examples/sidebars/sidebars.js" defer></script>
    <!-- Swiper library -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css" />
    <!-- Font-awesome script -->
    <script src="https://kit.fontawesome.com/a28016bfcd.js" crossorigin="anonymous"></script>

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
                        <a href="../index.php" class="nav-link active text-white" aria-current="page">Home</a>
                    </li>
                    <li class="nav-item">
                        <a href="../galeria.php" class="nav-link text-white">Gallery</a>
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
                <a href="./admin/login.php" >
                    <img src="../photos/admin-64px.png" alt="Admin" width="40px" title="Admin Side">
                </a>
                <a href="./doctorSide/login.php" >
                    <img class="bg-light rounded-pill ms-2" src="../photos/doctor-64px.png" alt="Doctor Side" width="40px" title="Doctor Side">
                </a>
            </div>
        </div>
    </nav>
    <!-- <a href="../index.php" class="backIndex ms-5 mt-3 text-decoration-none" title="Go back"><i class="fa-solid fa-arrow-left text-secondary fs-1"></i></a> -->
    <main class="main_shiko">
        <article class="profile">
            <img class="shadow" src="../admin/uploads/<?= $data['foto'] ?>" alt="<?= $data['fullName'] ?>">
            <div class="content">
                <h1 class="h2 fw-normal text-center"><?= $data['fullName'] ?></h1>
                <p class="fs-5"><?= $data['biografia'] ?></p>
            </div>
        </article>
    </main>
</body>
</html>