<?php
include('../config.php');
if (!isset($_SESSION['emri']) && !isset($_SESSION['mbiemri'])) {
    header("Location: login.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
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
            <span class=" sess_admin"><?php echo $_SESSION['emri'] . ' ' . $_SESSION['mbiemri'] ?></span>
        </p>
        <hr>
        <ul class="nav nav-pills flex-column mb-auto">
            <li><a href="../index.php" class="nav-link text-white">Home</a></li>
            <li class="nav-item"><a href="rezervoTermin.php" class="nav-link text-white">Appointments</a></li>
            <li><a href="terminet_e_mia.php" class="nav-link text-white">My appointments</a></li>
            <li><a href="ankesat.php" class="nav-link text-white">Complaints</a></li>
            <li><a href="historiaTermineve(pacientit).php" class="nav-link text-white" >Appointments history</a></li>
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


    <main class=" main mainRes mainProfili">
        <?php
        $nameErr = $surnameErr = $personalNrErr = $genderErr = $emailErr = $birthdayErr = $phoneErr = $adressErr = $usernameErr = $PassErr = $newPass_err = $confirmPass_err = "";

        if (isset($_POST['update'])) {

            function testInput($data)
            {
                $data = trim($data);
                $data = stripslashes($data);
                $data = htmlspecialchars($data);
                return $data;
            }

            if (empty($_POST['name'])) {
                $nameErr = '*Name must be filled.';
                $invalid_name = 'is-invalid';
            } else {
                $emri = testInput($_POST['name']);
                if (!preg_match("/^[a-zA-z]*$/", $emri)) {
                    $nameErr = '*Only letters are allowed.';
                    $invalid_name = 'is-invalid';
                } else {
                    $nameErr = '';
                }
            }

            if (empty($_POST['surname'])) {
                $surnameErr = '*Last name must be filled.';
                $invalid_surname = 'is-invalid';
            } else {
                $mbiemri = testInput($_POST['surname']);
                if (!preg_match("/^[a-zA-z]*$/", $mbiemri)) {
                    $surnameErr = '*Only letters are allowed.';
                    $invalid_surname = 'is-invalid';
                } else {
                    $surnameErr = '';
                }
            }

            if (empty($_POST['personal_id'])) {
                $personalNrErr = '*Personal ID must be filled.';
                $invalid_personal_id = 'is-invalid';
            } else {
                $numri_personal = testInput($_POST['personal_id']);
                if (!preg_match("/^[0-9]*$/", $numri_personal)) {
                    $personalNrErr = "*Only numbers are allowed.";
                    $invalid_personal_id = 'is-invalid';
                } else {
                    if (strlen($numri_personal) != 10) {
                        $personalNrErr = '*Personal ID must be 10 digit number';
                        $invalid_personal_id = 'is-invalid';
                    } else {
                        $personalNrErr = '';
                    }
                }
            }

            if (!isset($_POST['gender'])) {
                $genderErr = '*Gender must be selected';
            } else {
                $gjinia = testInput($_POST['gender']);
                $genderErr = '';
            }

            if (empty($_POST['email'])) {
                $emailErr = '*Email must be filled.';
                $invalid_email = 'is-invalid';
            } else {
                $pattern = "^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^";
                $email = testInput($_POST['email']);
                if (!preg_match($pattern, $email)) {
                    $emailErr = '*Invalid email.';
                    $invalid_email = 'is-invalid';
                } else {

                    $emailErr = '';
                }
            }

            if (empty($_POST['birthday'])) {
                $birthdayErr = '*Birthday must be filled.';
                $invalid_birthday = 'is-invalid';
            } else {
                $ditlindja = testInput($_POST['birthday']);
                $birthdayErr = '';
            }

            if (empty($_POST['phone'])) {
                $phoneErr = '*Phone number must be filled.';
                $invalid_phone = 'is-invalid';
            } else {
                $telefoni = testInput($_POST['phone']);
                if (!preg_match('/^[0-9]{9}+$/', $telefoni)) {
                    $phoneErr = '*Invalid phone number.';
                    $invalid_phone = 'is-invalid';
                } else {
                    $phoneErr = '';
                }
            }

            if (empty($_POST['adress'])) {
                $adressErr = '*Adress must be filled.';
                $invalid_adress = 'is-invalid';
            } else {
                $adresa = testInput($_POST['adress']);
                $adressErr = '';
            }

            if (empty($_POST['username'])) {
                $usernameErr = '*Username must be filled.';
                $invalid_username = 'is-invalid';
            } else {
                $username = testInput($_POST['username']);
                $usernameErr = '';
            }


            if (empty($_POST['newPassword'])) {
                $newPass_err = '*New password must be filled.';
                $invalid_newPass = 'is-invalid';
            } else {
                $newPassword = $_POST['newPassword'];
                $newPass_err = '';
            }

            if (empty($_POST['confirmPassword'])) {
                $confirmPass_err = '*Confirm password must be filled.';
                $invalid_confirmPass = 'is-invalid';
            } else {
                $confirmPassword = $_POST['confirmPassword'];
                $confirmPass_err = '';
            }


            if (empty($_POST['password'])) {
                $PassErr = '*Actual password must be filled.';
                $invalid_pass = 'is-invalid';
            } else {
                $password = testInput($_POST['password']);

                $PassErr = '';

                $check_pass = "SELECT password FROM patient_table WHERE numri_personal=:numri_personal";
                $check_pass_prep = $con->prepare($check_pass);
                $check_pass_prep->bindParam(':numri_personal', $_SESSION['numri_personal']);
                $check_pass_prep->execute();
                $check_data = $check_pass_prep->fetch();

                if (password_verify($password, $check_data['password'])) {
                    $PassErr = '';

                    if ($newPass_err == '' && $confirmPass_err == '') {
                        if ($newPassword !== $confirmPassword) {
                            $confirmPass_err = "Passwords doesn't match.";
                            $invalid_confirmPass = 'is-invalid';
                        } else {
                            $confirmPass_err = '';
                            $encPass = password_hash($confirmPassword, PASSWORD_DEFAULT);
                        }
                    }
                } else {
                    $PassErr = 'Incorrect password. Please try again.';
                    $invalid_pass = 'is-invalid';
                }
            }



            if (
                $nameErr == '' && $surnameErr == '' && $personalNrErr == '' && $genderErr == '' && $emailErr == '' && $phoneErr == '' &&
                $birthdayErr == '' && $adressErr == '' && $usernameErr == '' && $PassErr == '' && $newPass_err == '' && $confirmPass_err == ''
            ) {

                $sql = "UPDATE patient_table  SET emri=:emri, mbiemri=:mbiemri, numri_personal=:numri_personal, gjinia=:gjinia, email=:email, 
                    telefoni=:telefoni, ditlindja=:ditlindja, adresa=:adresa, username=:username, password=:password 
                    WHERE numri_personal=:data_numri_personal";
                $prep = $con->prepare($sql);
                $prep->bindParam(':data_numri_personal', $_SESSION['numri_personal']);
                $prep->bindParam(':emri', $emri);
                $prep->bindParam(':mbiemri', $mbiemri);
                $prep->bindParam(':numri_personal', $numri_personal);
                $prep->bindParam(':gjinia', $gjinia);
                $prep->bindParam(':email', $email);
                $prep->bindParam(':telefoni', $telefoni);
                $prep->bindParam(':ditlindja', $ditlindja);
                $prep->bindParam(':adresa', $adresa);
                $prep->bindParam(':username', $username);
                $prep->bindParam(':password', $encPass);
                if ($prep->execute()) {
                    echo "<script>
                            alert('Your account has been successfully updated.');
                            window.location.replace('../index.php');
                        </script>";
                } else {
                    echo "Fatal error";
                }
            }
        }

        ?>
        <form method="POST" class="form-sigin form-siginPro" autocomplete="off">
            <h1 class="h3 mb-3 fw-normal">Update data</h1>
            <?php

            $sql = "SELECT * FROM patient_table WHERE numri_personal=:numri_personal";
            $prep = $con->prepare($sql);
            $prep->bindParam(':numri_personal', $_SESSION['numri_personal']);
            $prep->execute();
            $data = $prep->fetch();
            ?>

            <div class="form-floating mb-2">
                <input type="text" class="form-control res <?= $invalid_name ?? "" ?>" id="floatingInput" name="name" placeholder="Name" value="<?= $data['emri'] ?>">
                <label for="floatingInput">Name</label>
                <span class="text-danger fw-normal"><?php echo $nameErr; ?></span>
            </div>

            <div class="form-floating mb-2">
                <input type="text" class="form-control res <?= $invalid_surname ?? "" ?>" id="floatingInput" name="surname" placeholder="Last name" value="<?= $data['mbiemri'] ?>">
                <label for="floatingInput">Last Name</label>
                <span class="text-danger fw-normal"><?php echo $surnameErr; ?></span>
            </div>

            <div class="form-floating mb-2">
                <input type="text" class="form-control res <?= $invalid_personal_id ?? "" ?>" id="floatingInput" name="personal_id" placeholder="Personal ID" value="<?= $data['numri_personal'] ?>">
                <label for="floatingInput">Personal ID</label>
                <span class="text-danger fw-normal"><?php echo $personalNrErr; ?></span>
            </div>

            <div class="form-check form-check-inline mb-2">
                <input class="form-check-input" type="radio" name="gender" id="inlineRadio1" value="Mashkull">
                <label class="form-check-label" for="inlineRadio1">Male</label>
            </div>

            <div class="form-check form-check-inline mb-2">
                <input class="form-check-input" type="radio" name="gender" id="inlineRadio2" value="Femer">
                <label class="form-check-label" for="inlineRadio2">Female</label>
            </div> <br>
            <span class="text-danger fw-normal"><?php echo $genderErr; ?></span>
            <div class="form-floating mb-2">
                <input type="email" class="form-control res <?= $invalid_email ?? "" ?>" id="floatingInput" name="email" placeholder="name@example.com" value="<?= $data['email'] ?>">
                <label for="floatingInput">Email </label>
                <span class="text-danger fw-normal"><?php echo $emailErr; ?></span>
            </div>

            <div class="col-lg-3 col-sm-6 mb-2">
                <label for="startDate">Birthday:</label>
                <input id="startDate" class="form-control ditlindja res <?= $invalid_birthday ?? "" ?>" name="birthday" type="date" value="<?= $data['ditlindja'] ?>" />
                <span class="text-danger fw-normal"><?php echo $birthdayErr; ?></span>
            </div>

            <div class="form-floating mb-2">
                <input type="tel" class="form-control  res<?= $invalid_phone ?? "" ?>" id="floatingInput" name="phone" placeholder="Phone number" value="<?= $data['telefoni'] ?>">
                <label for="floatingInput">Phone number</label>
                <span class="text-danger fw-normal"><?php echo $phoneErr; ?></span>
            </div>

            <div class="form-floating mb-2">
                <input type="text" class="form-control res <?= $invalid_adress ?? "" ?>" id="floatingInput" name="adress" placeholder="Adress" value="<?= $data['adresa'] ?>">
                <label for="floatingInput">Adress</label>
                <span class="text-danger fw-normal"><?php echo $adressErr; ?></span>
            </div>

            <div class="form-floating mb-2">
                <input type="text" class="form-control res <?= $invalid_username ?? "" ?>" id="floatingInput" name="username" placeholder="Username" value="<?= $data['username'] ?>">
                <label for="floatingInput">Username</label>
                <span class="text-danger fw-normal"><?php echo $usernameErr; ?></span>
            </div>

            <div class="form-floating">
                <input type="password" class="form-control mb-2 res <?= $invalid_pass ?? "" ?>" id="floatingPassword" name="password" placeholder="Password">
                <label for="floatingPassword">Actual password</label>
                <span class="text-danger fw-normal"><?php echo $PassErr; ?></span>
            </div>

            <div class="form-floating">
                <input type="password" class="form-control res mb-2 <?= $invalid_newPass ?? "" ?>" id="floatingPassword" name="newPassword" placeholder="Password">
                <label for="floatingPassword">New password</label>
                <span class="text-danger fw-normal"><?php echo $newPass_err; ?></span>
            </div>

            <div class="form-floating">
                <input type="password" class="form-control res mb-2  <?= $invalid_confirmPass ?? "" ?>" id="floatingPassword" name="confirmPassword" placeholder="Password">
                <label for="floatingPassword">Confirm password</label>
                <span class="text-danger fw-normal"><?php echo $confirmPass_err; ?></span>
            </div>

            <button class=" btn btn-lg btn-primary updateRes" type="submit" name="update">Update</button>

        </form>

    </main>

</body>

</html>