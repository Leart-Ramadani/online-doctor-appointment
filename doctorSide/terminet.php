<?php
include('../config.php');
if (!isset($_SESSION['doctor'])) {
    header("Location: login.php");
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $_SESSION['doctor'] ?></title>
    <link rel="stylesheet" href="../css/main.css">
    <link rel="icon" href="../photos/doctor.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link rel="stylesheet" href="../bootstrap-5.1.3-examples/sidebars/sidebars.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous" defer></script>
    <script src="../bootstrap-5.1.3-examples/sidebars/sidebars.js" defer></script>
</head>

<body>

    <div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark sidebar">
        <p class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
            <span class="fs-4"><?php echo $_SESSION['doctor'] ?></span>
        </p>
        <hr>
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item"><a href="terminet.php" class="nav-link active" aria-current="page">Terminet</a></li>
            <li class="nav-item"><a href="historiaTermineve.php" class="nav-link text-white">Historia e termineve</a></li>
        </ul>
        <hr>
        <div class="dropdown">
            <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="https://w7.pngwing.com/pngs/200/420/png-transparent-user-profile-computer-icons-overview-rectangle-black-data-thumbnail.png" alt="" width="32" height="32" class="rounded-circle me-2">
                <strong><?php echo $_SESSION['doc_username'] ?></strong>
            </a>
            <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">

                <li><a class="dropdown-item" href="logout.php">Sign out</a></li>
            </ul>
        </div>
    </div>


    <?php
    $sql = "SELECT * FROM terminet WHERE doktori=:doktori";
    $stm = $con->prepare($sql);
    $stm->bindParam(':doktori', $_SESSION['doctor']);
    $stm->execute();
    $data = $stm->fetchAll();

    if (!$data) {
        $empty = 'empty';
    } else {
        $empty = '';
    }

    ?>
    <main class="main">
    <?php if ($empty == '') : ?>
        <table class="table table-striped mt-2">
            <thead>
                <tr>
                    <th scope="col">Pacienti</th>
                    <th scope="col">Numri Personal</th>
                    <th scope="col">Email i pacientit</th>
                    <th scope="col">Data</th>
                    <th scope="col">Ora</th>
                    <th scope="col">Aksioni</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data as $data) : ?>
                    <tr>
                        <td><?= $data['emri_pacientit']. ' ' .$data['mbiemri_pacientit']  ?></td>
                        <td><?= $data['numri_personal'] ?></td>
                        <td><?= $data['email_pacientit'] ?></td>
                        <td><?= $data['data'] ?></td>
                        <td><?= $data['ora'] ?> </td>
                        <td class="text-center">
                            <a class="text-decoration-none text-white" href="perfundoTakimin.php?id=<?= $data['id']  ?>"><button class="btn btn-success  w-100 p-1 text-white">Perfundo takimin</button></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>

<?php if ($empty == 'empty') : ?>
    <article style="margin-left: 200px;" class="text-center">
        <h1 class=" h1 fw-normal text-center">Nuk ka asnje termin te rezervuar deri me tani.</h1>
    </article>
<?php endif; ?>
    </main>

</body>