<?php
include('../config.php');
if (!isset($_SESSION['fullName'])) {
    header("Location: login.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complaints</title>
    <link rel="shortcut icon" href="../photos/icon-hospital.png">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/responsive.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link rel="stylesheet" href="../bootstrap-5.1.3-examples/sidebars/sidebars.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous" defer></script>
    <script src="../bootstrap-5.1.3-examples/sidebars/sidebars.js" defer></script>
    <!-- JQuery link -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js" defer></script>
    <script src="../js/index.js" defer></script>
    <script src="../js/anulo.js" defer></script>
    <!-- Font-awesome script -->
    <script src="https://kit.fontawesome.com/a28016bfcd.js" crossorigin="anonymous" defer></script>

</head>

<body>
    <div class="sideBlock">
        <button type="button" class="ham" id="ham_menu"><i class="fa-solid fa-bars"></i></button>
    </div>


    <div class="flex-shrink-0 p-3 text-white bg-dark sidebar">
        <button type="button" class="close_side"><i class="fa-solid fa-close"></i></button>
        <p class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
            <span class=" sess_admin"><?php echo $_SESSION['fullName']  ?></span>
        </p>
        <hr>
        <ul class="nav nav-pills flex-column mb-auto">
            <li><a href="../index.php" class="nav-link text-white">Homepage</a></li>
            <li class="nav-item"><a href="rezervoTermin.php" class="nav-link text-white">Appointments</a></li>
            <li><a href="terminet_e_mia.php" class="nav-link text-white">My appointments</a></li>
            <li><a href="ankesat.php" class="nav-link text-white active" aria-current="page">Complaints</a></li>
            <li><a href="historiaTermineve(pacientit).php" class="nav-link text-white">Appointments history</a></li>
        </ul>
        <hr>
        <div class="dropdown">
            <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="https://w7.pngwing.com/pngs/200/420/png-transparent-user-profile-computer-icons-overview-rectangle-black-data-thumbnail.png" alt="" width="32" height="32" class="rounded-circle me-2">
                <strong class="useri"><?php echo $_SESSION['username'] ?></strong>
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


    <?php
    $sql = "SELECT email FROM users WHERE personal_id=:personal_id";
    $prep = $con->prepare($sql);
    $prep->bindParam(':personal_id', $_SESSION['numri_personal']);
    $prep->execute();
    $data = $prep->fetch();
    ?>
    <main class="main mainRes mainProfili">
        <?php
        $error_msg = '';
        if (isset($_POST['ankohu'])) {
            $pacienti = $_SESSION['fullName'];
            $personal_id = $_SESSION['numri_personal'];
            $email = $data['email'];
            $ankesa = $_POST['ankesa'];

            $paid = "SELECT * FROM terminet WHERE numri_personal=:personal_id AND paied=false";
            $prep = $con->prepare($paid);
            $prep->bindParam(':personal_id', $_SESSION['numri_personal']);
            $prep->execute();
            $data_paid = $prep->fetch();

            if($data_paid){
                $error_msg = "You can't complain until you pay the bill!";
                $invalid_msg = 'is-invalid';
            } else{
                if ($ankesa == '') {
                    $error_msg = '*You must fill out this field!';
                    $invalid_msg = 'is-invalid';
                } else {
                    $error_msg = '';
                    $ankesa_sql = "INSERT INTO ankesat(pacienti, numri_personal, email, ankesa)
                        VALUES(:pacienti, :numri_personal, :email, :ankesa)";
                    $ankesa_prep = $con->prepare($ankesa_sql);
                    $ankesa_prep->bindParam(':pacienti', $pacienti);
                    $ankesa_prep->bindParam(':numri_personal', $personal_id);
                    $ankesa_prep->bindParam(':email', $email);
                    $ankesa_prep->bindParam(':ankesa', $ankesa);
                    $ankesa_prep->execute();
                }
            }
        }
        ?>
        <form class="form-signin" method="POST" autocomplete="off">
            <h1 class="h3 mb-3 fw-normal text-center">Complaint</h1>

            <div class="form-floating">
                <input type="text" class="form-control mb-2" readonly id="floatingInput" name="name" placeholder="Name" value="<?= $_SESSION['fullName'] ?>">
                <label for="floatingInput">Name</label>
            </div>


            <div class="form-floating">
                <input type="text" class="form-control mb-2" readonly id="floatingInput" name="personal_id" placeholder="Personal ID" value="<?= $_SESSION['numri_personal'] ?>">
                <label for="floatingInput">Personal ID</label>
            </div>

            <div class="form-floating">
                <input type="email" class="form-control mb-2 rounded" readonly id="floatingInput" name="email" placeholder="name@example.com" value="<?= $data['email'] ?>">
                <label for="floatingInput">Email</label>
            </div>

            <div class="mb-2">
                <label for="ankesa" class="form-label">Your complaint:</label>
                <textarea class="form-control <?= $invalid_msg ?? '' ?>" style="resize:none;" id="ankesa" rows="5" maxlength="350" name="ankesa"></textarea>
                <span class="text-danger fw-normal"><?php echo $error_msg; ?></span>
            </div>



            <button class=" btn btn-lg btn-primary ankohu updateRes" type="submit" name="ankohu">Send</button>

        </form>
    </main>

</body>

</html>