<?php
include('../config.php');
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
}
?>
<?php include('header.php'); ?>
<title>Departamentet</title>
</head>

<body>
    <div class="sideBlock">
        <button type="button" class="ham" id="ham_menu"><i class="fa-solid fa-bars"></i></button>
    </div>
    <div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark sidebar">
        <button type="button" class="close_side"><i class="fa-solid fa-close"></i></button>
        <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
            <span class="fs-4"><?php echo $_SESSION['admin'] ?></span>
        </a>
        <hr>
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item"><a href="doktoret.php" class="nav-link text-white">Doktoret</a></li>
            <li><a href="departamentet.php" class="nav-link text-white active" aria-current="page">Departamentet</a></li>
            <li><a href="orari.php" class="nav-link text-white">Orari</a></li>
            <li><a href="terminet.php" class="nav-link text-white">Terminet</a></li>
            <li><a href="pacientat.php"" class=" nav-link text-white">Pacientat</a></li>
            <li><a href="historiaTerminit.php" class="nav-link text-white">Historia termineve</a></li>
            <li class="nav-item"><a href="galeria.php" class="nav-link text-white">Galeria</a></li>
            <li><a href="ankesat.php" class="nav-link text-white">Ankesat</a></li>
            <li><a href="kerkesatAnulimit.php" class="nav-link text-white">Kerkesat per anulim</a></li>
        </ul>
        <hr>
        <div class="dropdown">
            <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="https://w7.pngwing.com/pngs/200/420/png-transparent-user-profile-computer-icons-overview-rectangle-black-data-thumbnail.png" alt="" width="32" height="32" class="rounded-circle me-2">
                <strong><?php echo $_SESSION['admin'] ?></strong>
            </a>
            <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
                <li><a class="dropdown-item" href="logout.php">Sign out</a></li>
            </ul>
        </div>
    </div>
    <?php
    $depErr = '';
    if (isset($_POST['submit'])) {
        function testInput($data)
        {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        if (empty($_POST['departamenti'])) {
            $depErr = '*Departamenti duhet plotesuar.';
            $invalid_dep = 'is-invalid';
        } else {
            $departament = testInput($_POST['departamenti']);
            if (!preg_match("/^[a-z A-z]*$/", $departament)) {
                $depErr = '*Nuk lejohen karaktere tjera perveq shkronjave.';
                $invalid_dep = 'is-invalid';
            } else {
                $sql = "SELECT * FROM departamentet WHERE departamenti=:departamenti";
                $stm = $con->prepare($sql);
                $stm->bindParam(':departamenti', $departament);
                $stm->execute();
                $row = $stm->fetch();

                if ($row) {
                    $depErr = '*Ky departament ekziston ne databaze.';
                    $invalid_dep = 'is-invalid';
                } else {
                    $depErr = '';
                }
            }
        }

        if ($depErr == '') {
            $sql = "INSERT INTO departamentet(departamenti) VALUES(:departamenti)";
            $prep = $con->prepare($sql);
            $prep->bindParam(":departamenti", $departament);
            $prep->execute();
        }
    }
    ?>

    <main class="text-center main mainRes">
        <form method="POST" autocomplete="off" class="form-signin text-center departament">
            <h1 class="h3 mb-3 fw-normal">Shto nje departament</h1>
            <div class="form-floating">
                <input type="text" class="form-control <?= $invalid_dep ?? "" ?>" id="floatingPassword" name="departamenti" placeholder="Departamenti">
                <label for="floatingPassword">Departamenti</label>
                <span class="text-danger fw-normal"><?php echo $depErr; ?></span>
                <button class="w-100 btn btn-lg btn-primary mt-2" type="submit" name="submit">Shto</button>
            </div>
        </form>


    </main>

    <?php
    $sql = "SELECT * FROM departamentet";
    $stm = $con->prepare($sql);
    $stm->execute();
    $data = $stm->fetchAll();

    if (!$data) {
        $empty = 'empty';
    } else {
        $empty = '';
    }
    ?>

    <article class="table_wrapper">
        <?php if ($empty == '') : ?>
            <table class="table table-striped table-borderd w-50 text-center">
                <thead>
                    <tr>
                        <th scope="col" class="text-center">#</th>
                        <th scope="col">Departamenti</th>
                        <th scope="col">Aksioni</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data as $data) : ?>
                        <tr>
                            <th scope="row" class="text-center"><?= $data['id'] ?></th>
                            <td><?= $data['departamenti'] ?></td>
                            <td>
                                <a class="text-decoration-none text-white" href="deleteDepartament.php?id=<?= $data['id']  ?>">
                                    <button class="btn btn-danger w-100 p-1 text-white">Delete</button>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <?php if ($empty == 'empty') : ?>
            <article class="text-center mt-5">
                <h1 class=" h1 fw-normal text-center mt-5">Nuk u gjeten te dhena ne databaze.</h1>
            </article>
        <?php endif; ?>
    </article>

</body>

</html>