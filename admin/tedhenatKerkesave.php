<?php
include('../config.php');

$id = $_GET['id'];

$sql = "SELECT k.id, k.pacienti, k.numri_personal, k.email, k.telefoni, k.doktori, k.departamenti, k.data, k.ora, k.arsyeja_anulimit, 
    d.name as 'dep_name' FROM kerkesatanulimit AS k INNER JOIN departamentet AS d ON k.departamenti = d.id  WHERE k.id=:id";
$prep = $con->prepare($sql);
$prep->bindParam(':id', $id);
$prep->execute();
$data = $prep->fetch();

$date = date_create($data['data']);
$date = date_format($date, "d/m/Y");

$time = date_create($data['ora']);
$time = date_format($time, "H:i");

?>
<?php include('header.php'); ?>
<title>Kerkesa per anulim</title>

</head>
<body style="background-color: #f5f5f5;">
    <article class="kerkesa_wrapper">
        <section class="kerkesa">
            <div>
                <a href="kerkesatAnulimit.php" class="goBack" title="Go back"><i class="fa-solid fa-arrow-left text-dark"></i></a>
                <div class="h1_flex">
                    <h1 class="h3 mb-4 fw-normal text-center">Cancelation request</h1>
                </div>
            </div>
            <article class=firsecKerkesa" style="  display: flex; gap: 15px;">
                <div class="firstPartKerkesa">
                    <div class="form-floating mb-2">
                        <input type="text" class="form-control" readonly id="floatingInput" placeholder="Emri dhe mbiemri"
                            value="<?= $data['pacienti'] ?>">
                        <label for="floatingInput">Patient</label>
                    </div>
                    <div class="form-floating mb-2">
                        <input type="text" class="form-control" readonly id="floatingInput" placeholder="Numri persoanl"
                            value="<?= $data['numri_personal'] ?>">
                        <label for="floatingInput">Personal ID</label>
                    </div>
                    <div class="form-floating mb-2">
                        <input type="text" class="form-control" readonly id="floatingInput" placeholder="Email"
                            value="<?= $data['email'] ?>">
                        <label for="floatingInput">Email</label>
                    </div>
                    <div class="form-floating mb-2">
                        <input type="text" class="form-control" readonly id="floatingInput" placeholder="Numri i telefonit"
                            value="<?= $data['telefoni'] ?>">
                        <label for="floatingInput">Phone number</label>
                    </div>
                </div>
    
                <div class="secondPartKerkesa">
                    <div class="form-floating mb-2">
                        <input type="text" class="form-control" readonly id="floatingInput" placeholder="Doktori"
                            value="<?= $data['doktori'] ?>">
                        <label for="floatingInput">Doctor</label>
                    </div>
                    <div class="form-floating mb-2">
                        <input type="text" class="form-control" readonly id="floatingInput" placeholder="Departamenti"
                            value="<?= $data['dep_name'] ?>">
                        <label for="floatingInput">Departament</label>
                    </div>
                    <div class="form-floating mb-2">
                        <input type="text" class="form-control" readonly id="floatingInput" placeholder="Data e terminit"
                            value="<?= $date ?>">
                        <label for="floatingInput">Appointment date</label>
                    </div>
                    <div class="form-floating mb-2">
                        <input type="text" class="form-control" readonly id="floatingInput" placeholder="Ora"
                            value="<?= $time ?>">
                        <label for="floatingInput">Time</label>
                    </div>
                </div>
            </article>
            

            <div class="mb-2 d-flex">
                <label for="ankesa" class="form-label">Reason for the request:</label>
                <textarea class="form-control" readonly style="resize:none;" rows="5"><?= $data['arsyeja_anulimit'] ?></textarea>
            </div>

            <div class="kerkesaAction mt-3">
                <a class="text-decoration-none text-white d-inline-block"
                    href="aprovoKerkesen.php?id=<?= $data['id']  ?>">
                    <button class="btn btn-success w-100 p-2 text-white rounded mb-1">Approve</button>
                </a>

                <a class="text-decoration-none text-white d-inline-block"
                    href="deleteKerkesen.php?id=<?= $data['id']  ?>">
                    <button class="btn btn-danger w-100 p-2 text-white mb-1">Reject</button>
                </a>
            </div>


        </section>
    </article>
</body>