<?php
include('../config.php');

$id = $_GET['id'];
$sql = "SELECT * FROM ORARI WHERE id=:id";
$prep = $con->prepare($sql);
$prep->bindParam(':id', $id);
$prep->execute();
$datas = $prep->fetch();

?>

<?php require('header.php'); ?>
<title>Orari</title>
</head>


<?php
$sql = "SELECT fullName FROM doctor_personal_info";
$prep = $con->prepare($sql);
$prep->execute();
$doctors = $prep->fetchAll();
?>

<body>
    <form class="form-signin" method="POST" enctype="multipart/form-data" autocomplete="off">
        <h1 class="h3 mb-3 fw-normal text-center">Ndrysho orarin</h1>

        <select class="form-select" aria-label="Default select example" name="doktori">
            <option selected class="fst-italic"><?= $datas['doktori'] ?></option>
            <?php foreach ($doctors as $doctors) : ?>
                <option value="<?= $doctors['fullName'] ?>"><?= $doctors['fullName'] ?></option>
            <?php endforeach; ?>
        </select>

        <div class="col-lg-3 col-sm-6">
            <label for="startDate">Data:</label>
            <input id="startDate" class="form-control ditlindja" name="data" type="date" value="<?= $datas['data'] ?>" />
        </div>

        <div class="text-center">
            <div class="d-inline-block me-1" style="float: left;">
                <label for="startingHour">Nga ora:</label>
                <input id="startingHour" class="form-control time" name="from_time" type="time" value="<?= $datas['nga_ora'] ?>" />
            </div>

            <div class="d-inline-block mb-2" style="float: right;">
                    <label for=" leavingHour">Deri oren:</label>
                <input id="leavingHour" class="form-control time" name="to_time" type="time" value="<?= $datas['deri_oren'] ?>" />
            </div>

        </div>

        <select class="form-select mt-2" aria-label="Default select example" name="kohzgjatja">
            <option selected>Kohezgjatja</option>
            <option value="15">15min</option>
            <option value="20">20min</option>
            <option value="30">30min</option>
            <option value="45">45min</option>
        </select>

        <button class="w-100 btn btn-lg btn-primary mt-3" type="submit" name="submit">Ndryshoni</button>
    </form>

    <?php
    if (isset($_POST['submit'])) {
        $doktori = $_POST['doktori'];
        $data = $_POST['data'];
        $nga_ora = $_POST['from_time'];
        $deri_oren = $_POST['to_time'];
        $kohzgjatja = $_POST['kohzgjatja'];

        $dep_sql = "SELECT departamenti FROM doctor_personal_info WHERE fullName=:fullName";
        $dep_prep = $con->prepare($dep_sql);
        $dep_prep->bindParam(':fullName', $doktori);
        $dep_prep->execute();
        $dep_fetch = $dep_prep->fetch();
        $departamenti = $dep_fetch['departamenti'];

        $sql = "UPDATE orari SET doktori=:doktori, departamenti=:departamenti, 
                    data=:data, nga_ora=:nga_ora, deri_oren=:deri_oren, kohezgjatja=:kohezgjatja WHERE id=:id";
        $stm = $con->prepare($sql);
        $stm->bindParam(':id', $id);
        $stm->bindParam(':doktori', $doktori);
        $stm->bindParam(':departamenti', $departamenti);
        $stm->bindParam(':data', $data);
        $stm->bindParam(':nga_ora', $nga_ora);
        $stm->bindParam(':deri_oren', $deri_oren);
        $stm->bindParam(':kohezgjatja', $kohzgjatja);
        $stm->execute();

        header("Location: orari.php");
    }
    ?>


</body>

</html>