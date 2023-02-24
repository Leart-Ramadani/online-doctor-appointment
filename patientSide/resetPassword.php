<?php
include('../config.php');

$email = $_GET['email'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rivendos fjalkalimin</title>
    <link rel="shortcut icon" href="../photos/icon-hospital.png">
    <link rel="stylesheet" href="../css/main.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link rel="stylesheet" href="../bootstrap-5.1.3-examples/sidebars/sidebars.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous" defer></script>
    <script src="../bootstrap-5.1.3-examples/sidebars/sidebars.js" defer></script>
</head>

<body>
    <?php
    $newPass_err = $confirmPass_err = '';
    if (isset($_POST['submit'])) {
        if (empty($_POST['newPass'])) {
            $newPass_err = 'Ju duhet qe te plotesoni fjalekalimin e ri.';
            $invalid_newPass = 'is-invalid';
        } else {
            $newPass_err = '';
            $newPass = $_POST['newPass'];
        }

        if (empty($_POST['confirmPass'])) {
            $confirmPass_err = 'Ju duhet qe te konfirmoni fjalekalimin.';
            $invalid_confirmPass = 'is-invalid';
        } else {
            $confirmPass_err = '';
            $confirmPass = $_POST['confirmPass'];
        }

        if ($newPass_err == '' && $confirmPass_err == '') {
            if ($newPass !== $confirmPass) {
                $confirmPass_err = 'Fjalkalimat nuk perputhen.';
                $invalid_confirmPass = 'is-invalid';
            } else {
                $encPass = password_hash($confirmPass, PASSWORD_DEFAULT);
                $sql = "UPDATE patient_table SET password=:password WHERE email=:email";
                $prep = $con->prepare($sql);
                $prep->bindParam(':email', $email);
                $prep->bindParam(':password', $encPass);
                if ($prep->execute()) {
                    echo "<script>
                                    alert('Fjalekalimi u ndryshua me sukses.');
                                    window.location.replace('login.php');
                                </script>";
                }
            }
        }
    }
    ?>
    <main class="main">
        <form method="post" class="form-signin" autocomplete="off">
            <h1 class="h3 mb-4 fw-normal">Rivendosni fjalekalimin tuaj</h1>
            <div class="form-floating mb-2">
                <input type="password" class="form-control rounded <?= $invalid_newPass ?? '' ?>" id="floatingInput" name="newPass" placeholder="Fjalekalimi i ri">
                <label for="floatingInput">Fjalekalimi i ri</label>
                <span class="text-danger fw-normal"><?php echo $newPass_err; ?></span>
            </div>

            <div class="form-floating">
                <input type="password" class="form-control rounded <?= $invalid_confirmPass ?? '' ?>" id="floatingInput" name="confirmPass" placeholder="Konfirmo fjalekalimin">
                <label for="floatingInput">Konfirmo fjalekalimin</label>
                <span class="text-danger fw-normal"><?php echo $confirmPass_err; ?></span>
            </div>

            <button class="w-100 btn btn-lg btn-primary mt-3" type="submit" name="submit">Submit</button>
        </form>
    </main>
</body>

</html>