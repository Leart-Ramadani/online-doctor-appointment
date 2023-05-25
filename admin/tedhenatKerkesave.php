<?php
include('../config.php');

$id = $_GET['id'];

$sql = "SELECT * FROM kerkesatanulimit WHERE id=:id";
$prep = $con->prepare($sql);
$prep->bindParam(':id', $id);
$prep->execute();
$data = $prep->fetch();



?>
<?php include('header.php'); ?>
<title>Kerkesa per anulim</title>

</head>
<body style="background-color: #f5f5f5;">
    <article class="kerkesa_wrapper">
        <section class="kerkesa">
            <div>
                <a href="kerkesatAnulimit.php" class="goBack" title="Go back"><i class="fa-solid fa-arrow-left"></i></a>
                <div class="h1_flex">
                    <h1 class="appointment_h1 kerkesa_h1 ">Kerkesa e anulimit</h1>
                </div>
            </div>
            <div>
                <label>Emri dhe mbiemri:</label>
                <p class="kerkesa_p"><?= $data['emri_pacientit']. ' ' . $data['mbiemri_pacientit'] ?></p>
            </div>
            <div>
                <label>Numri personal:</label>
                <p class="kerkesa_p"><?= $data['numri_personal'] ?></p>
            </div>
            <div>
                <label>Email:</label>
                <p class="kerkesa_p"><?= $data['email'] ?></p>
            </div>
            <div>
                <label>Numri i telefonit:</label>
                <p class="kerkesa_p"><?= $data['telefoni'] ?></p>
            </div>
    
            <div>
                <label>Emri i doktorit:</label>
                <p class="kerkesa_p"><?= $data['doktori'] ?></p>
            </div>
            <div>
                <label>Departamenti:</label>
                <p class="kerkesa_p"><?= $data['departamenti'] ?></p>
            </div>
            <div>
                <label>Data e terminit:</label>
                <p class="kerkesa_p"><?= $data['data'] ?></p>
            </div>
            <div>
                <label>Ora:</label>
                <p class="kerkesa_p"><?= $data['ora'] ?></p>
            </div>
    
            <div >
                <label style="width: 30%;">Arsyeja e kerkeses:</label> <br>
                <p class="kerkesa_p"><?= $data['arsyeja_anulimit'] ?></p>
            </div>
    
            <div class="mt-5">
                <a class="text-decoration-none text-white d-inline-block kerkesa" href="aprovoKerkesen.php?id=<?= $data['id']  ?>">
                    <button class="btn btn-warning w-100 p-2 text-white rounded mb-1">Aprovo</button>
                </a>
    
                <a class="text-decoration-none text-white d-inline-block kerkesa" href="deleteKerkesen.php?id=<?= $data['id']  ?>">
                    <button class="btn btn-danger w-100 p-2 text-white mb-1 ms-3">Fshij </button>
                </a>
            </div>
    
    
        </section>
    </article>
</body>