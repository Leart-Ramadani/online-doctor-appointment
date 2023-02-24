<?php
include('../config.php');
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
}
?>
<?php include('header.php') ?>
<title>Galeria</title>
</head>

<body>

    <?php
    if (isset($_POST['shto']) && isset($_FILES['galeria'])) {



        $img_name = $_FILES['galeria']['name'];
        $img_size = $_FILES['galeria']['size'];
        $tmp_name = $_FILES['galeria']['tmp_name'];
        $error = $_FILES['galeria']['error'];

        if ($error === 0) {
            if ($img_size > 12500000) {
                $em = "Sorry, your file is to large.";
                header("Location: galeria.php?$em");
            } else {
                $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
                $img_ex_lc = strtolower($img_ex);

                $allowed_exs = array("jpg", "jpeg", "png", "gif", "webp");

                if (in_array($img_ex_lc, $allowed_exs)) {
                    $new_img_name = uniqid("IMG-", true) . '.' . $img_ex_lc;
                    $img_upload_path = 'uploads_gallery/' . $new_img_name;
                    move_uploaded_file($tmp_name, $img_upload_path);

                    $sql = "INSERT INTO galeria(foto_src) VALUES(:foto_src)";
                    $stm = $con->prepare($sql);
                    $stm->bindParam(':foto_src', $new_img_name);
                    if ($stm->execute()) {
                        header('Location: galeria.php');
                    } else {
                        echo 'Fatal error';
                    }
                } else {
                    $em = "Format not supported";
                    header("Location: doktoret.php?$em");
                }
            }
        }
    }


    ?>
    <div class="sideBlock">
        <button type="button" class="ham" id="ham_menu"><i class="fa-solid fa-bars"></i></button>
    </div>
    <div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark sidebar">
    <button type="button" class="close_side"><i class="fa-solid fa-close"></i></button>
        <p class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
            <span class="fs-4"><?php echo $_SESSION['admin'] ?></span>
        </p>
        <hr>
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item"><a href="doktoret.php" class="nav-link text-white">Doktoret</a></li>
            <li><a href="departamentet.php" class="nav-link text-white">Departamentet</a></li>
            <li><a href="orari.php" class="nav-link text-white">Orari</a></li>
            <li><a href="terminet.php" class="nav-link text-white">Terminet</a></li>
            <li><a href="pacientat.php"" class=" nav-link text-white">Pacientat</a></li>
            <li><a href="historiaTerminit.php" class="nav-link text-white">Historia e termineve</a></li>
            <li class="nav-item"><a href="galeria.php" class="nav-link active" aria-current="page">Galeria</a></li>
            <li><a href="ankesat.php" class="nav-link text-white">Ankesat</a></li>
            <li><a href="kerkesatAnulimit.php" class="nav-link text-white">Kerkesat e anulimit te termineve</a></li>
        </ul>
        <hr>
        <div class="dropdown">
            <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="https://w7.pngwing.com/pngs/200/420/png-transparent-user-profile-computer-icons-overview-rectangle-black-data-thumbnail.png" alt="" width="32" height="32" class="rounded-circle me-2">
                <strong><?php echo $_SESSION['admin'] ?></strong>
            </a>
            <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
                <li><a class="dropdown-item" href="profili.php">Profile</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item" href="logout.php">Sign out</a></li>
            </ul>
        </div>
    </div>

    <main class=" text-center main_galeria mainRes">
        <h1 class="h3 text-center fw-normal mt-3">Shto nje foto ne galleri</h1>
        <form method="POST" class="form-sigin" enctype="multipart/form-data" autocomplete="off">
            <div class="mb-3">
                <input class="form-control" type="file" name="galeria" id="formFile">
            </div>
            <button class="w-100 btn btn-lg btn-primary" type="submit" name="shto">Shtoni</button>
        </form>


    </main>
    <?php
    $gallery_sql = "SELECT * FROM galeria";
    $gallery_prep = $con->prepare($gallery_sql);
    $gallery_prep->execute();
    $data = $gallery_prep->fetchAll();
    ?>


    <article class="galeria_wrapper mt-5">
        <h3 class="h3 text-center fw-normal mt-5 mb-3">Galeria</h3> 
        <table class="table table-border w-50 table_patient">
            <thead>
                <tr>
                    <th scope="col">Foto</th>
                    <th scope="col">Aksioni</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data as $data) : ?>
                    <tr>
                        <td class="p-0 w-50 text-center"><img class="gallery_img" src="uploads_gallery/<?= $data['foto_src'] ?>"></td>
                        <td class="w-25">
                            <a class="text-decoration-none text-white" href="deletePhoto.php?id=<?= $data['id']  ?>">
                                <button class="btn btn-danger w-100 fs-5 mt-4 text-white">Delete</button>
                            </a>
                            <a class="text-decoration-none text-white" href="changePhoto.php?id=<?= $data['id']  ?>">
                                <button class="btn btn-success w-100 fs-5  mt-2 mb-0 text-white">Change</button>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </article>





</body>

</html>