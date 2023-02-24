<?php
    include('../config.php');

    $id = $_GET['id'];
    $sql = "SELECT * FROM galeria WHERE id=:id";
    $prep = $con->prepare($sql);
    $prep->bindParam(':id', $id);
    $prep->execute();
    $datas = $prep->fetch();

?>

<?php require('header.php'); ?>
<title>Change Photo</title>
</head>

<body>

<?php
    if (isset($_POST['ndrro']) && isset($_FILES['galeria'])) {



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

                    $sql = "UPDATE galeria SET foto_src=:foto_src WHERE id=:id";
                    $stm = $con->prepare($sql);
                    $stm->bindParam(':id', $id);
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

    <main class="form-signin text-center main_side">    
        <h1 class="h3 text-center fw-normal mb-5">Shto nje foto ne galleri</h1>
        <form method="POST" enctype="multipart/form-data" autocomplete="off">
            <div class="mb-3">
                <label for="formFile" class="form-label">Foto</label>
                <input class="form-control" type="file" name="galeria" id="formFile">
            </div>
            <button class="w-100 btn btn-lg btn-primary" type="submit" name="ndrro">Regjistrohuni</button>
        </form>


    </main>

</body>
