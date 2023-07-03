<?php
include('../config.php');
if (isset($_SESSION['emri']) && isset($_SESSION['mbiemri'])) {
    header("Location: ../index.php");
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log in</title>
    <link rel="shortcut icon" href="../photos/icon-hospital.png">
    <link rel="stylesheet" href="../css/main.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous" defer></script>

    <style>
        html,
        body {
            height: 100%;
        }

        body {
            display: flex;
            align-items: center;
            padding-top: 40px;
            padding-bottom: 40px;
            background-color: #f5f5f5;
        }

        .form-signin {
            width: 100%;
            max-width: 330px;
            padding: 15px;
            margin: auto;
        }

        .form-signin .checkbox {
            font-weight: 400;
        }

        .form-signin .form-floating:focus-within {
            z-index: 2;
        }

        .form-signin input[type="email"] {
            margin-bottom: -1px;
            border-bottom-right-radius: 0;
            border-bottom-left-radius: 0;
        }

        .form-signin input[type="password"] {
            margin-bottom: 10px;
            border-top-left-radius: 0;
            border-top-right-radius: 0;
        }
    </style>
</head>

<body class="text-center">
    <?php

    $usernameErr = $passErr = $verificationErr = '';
    $user2 = '';

    if (isset($_POST['submit'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $sql = "SELECT username, password, emri, mbiemri, numri_personal, email FROM patient_table where username = :Username or email=:Username";
        $stm = $con->prepare($sql);
        $stm->bindParam(":Username", $username);
        $stm->execute();
        $data = $stm->fetch();

        if ($data === false) {
            $usernameErr = "*Ky username ose email nuk eksizton!";
            $invalid_user = 'is-invalid';
        } else if (password_verify($password, $data['password'])) {

            $check_sql = "SELECT verificated FROM patient_table WHERE username=:username or email=:username";
            $check_prep = $con->prepare($check_sql);
            $check_prep->bindParam(':username', $username);
            $check_prep->execute();
            $check_data = $check_prep->fetch();

            if ($check_data['verificated'] != true) {
                $_SESSION['verify'] = $username;
                echo "<script>
                    alert('Kjo llogari nuk eshte verifikuar ende. Kontrolloje emailin tuaj per kodin verifikues!')
                    window.location.replace('./emailVerification.php')
                    </script>";
            } else {
                $_SESSION['username'] = $data['username'];
                $_SESSION['emri'] = $data['emri'];
                $_SESSION['mbiemri'] = $data['mbiemri'];
                $_SESSION['numri_personal'] = $data['numri_personal'];
                header("Location: ./rezervoTermin.php");
            }
        } else {
            $passErr = '*Fjalekalimi i pasakt!';
            $invalid_pass = 'is-invalid';
            $user2 = $username;
        }
    }
    ?>

    <main class="form-signin">
        <form method="POST" autocomplete="off">
            <h1 class="h3 mb-3 fw-normal">Kyqu ne llogarin tuaj</h1>
            <div class="rounded mb-2 <?= $invalid_verify ?? "" ?>">
                <span class="text-white"><?php echo $verificationErr; ?></span>
            </div>

            <div class="form-floating">
                <input type="text" class="form-control <?= $invalid_user ?? "" ?>" id="floatingInput" name="username" placeholder="Username ose email" value="<?= $user2 ?>">
                <label for="floatingInput">Username ose email</label>
                <span class="text-danger fw-normal"><?php echo $usernameErr; ?></span>
            </div>

            <div class="form-floating mt-2">
                <input type="password" class="form-control rounded <?= $invalid_pass ?? "" ?>" id="floatingPassword" name="password" placeholder="Password">
                <label for="floatingPassword">Fjalekalimi</label>
                <span class="text-danger fw-normal"><?php echo $passErr; ?></span>
            </div>

            <div class="form-check d-flex mb-2 justify-content-end">
                <label class="form-check-label" for="flexCheckDefault">Shfaq passwordin</label>
                <input class="form-check-input ms-2" type="checkbox" value="" id="flexCheckDefault">
            </div>

            <script>
                const passInput = document.getElementById('floatingPassword');
                const showPassCheckBox = document.getElementById('flexCheckDefault');

                const showPass = () => {
                    if (showPassCheckBox.checked) {
                        passInput.type = 'text';
                    } else {
                        passInput.type = 'password';
                    }
                }

                showPassCheckBox.addEventListener('change', showPass);
            </script>


            <button class="w-100 btn btn-lg btn-primary mb-3" type="submit" name="submit">Kyqu</button>

            <p class="mb-1">Nuk ke nje llogari? <a href="signup.php">Regjistrohu</a></p>
            <p class="mb-1">Keni harruar fjalkalimin? Kliko <a href="forgottenPassword.php">ketu.</a></p>
            <p>Kthehuni te <a href="../index.php">ballina.</a></p>
            </div>
        </form>
    </main>



</body>

</html>