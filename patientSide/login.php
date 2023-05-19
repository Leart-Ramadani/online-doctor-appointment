<?php
    include('../config.php');
    if(isset($_SESSION['emri']) && isset($_SESSION['mbiemri'])) {
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

    if(isset($_POST['submit'])){
        $username = $_POST['username'];
        $password = $_POST['password'];

        $sql = "SELECT username, password, emri, mbiemri, numri_personal FROM patient_table where username = :Username";
        $stm = $con->prepare($sql);
        $stm->bindParam(":Username", $username);
        $stm->execute();
        $data = $stm->fetch();

        if($data === false){
            $usernameErr = "This username doesn't exists.";
            $invalid_user = 'is-invalid';
        } else if(password_verify($password, $data['password'])){

            $check_sql = "SELECT verification_status FROM patient_table WHERE username=:username";
            $check_prep = $con->prepare($check_sql);
            $check_prep->bindParam(':username', $username);
            $check_prep->execute();
            $check_data = $check_prep->fetch();

            if($check_data['verification_status'] != 'true'){
                $verificationErr = 'Please verify your account. Check your email for verification link';
                $invalid_verify = 'verificationError';
            } else{
                $_SESSION['username'] = $data['username'];
                $_SESSION['emri'] = $data['emri'];
                $_SESSION['mbiemri'] = $data['mbiemri'];
                $_SESSION['numri_personal'] = $data['numri_personal'];
                header("Location: ./rezervoTermin.php");
            }

        } else{
            $passErr = 'Wrong password. Please try again';
            $invalid_pass = 'is-invalid';
            $user2 = $username;
        }
    }
?>

    <main class="form-signin">
        <form method="POST" autocomplete="off">
            <h1 class="h3 mb-3 fw-normal">Log in</h1>
            <div class="rounded mb-2 <?= $invalid_verify ?? "" ?>">
                <span class="text-white"><?php echo $verificationErr; ?></span>
            </div>

            <div class="form-floating">
                <input type="text" class="form-control <?= $invalid_user ?? "" ?>" id="floatingInput" name="username" placeholder="Username" value="<?= $user2 ?>">
                <label for="floatingInput">Username</label>
                <span class="text-danger fw-normal"><?php echo $usernameErr; ?></span>
            </div>

            <div class="form-floating mt-2">
                <input type="password" class="form-control rounded <?= $invalid_pass ?? "" ?>" id="floatingPassword" name="password" placeholder="Password">
                <label for="floatingPassword">Password</label>
                <span class="text-danger fw-normal"><?php echo $passErr; ?></span>
            </div>

            <button class="w-100 btn btn-lg btn-primary" type="submit" name="submit">Login</button>

            <p class="mb-2">Don't have an account? Click <a href="signup.php">here.</a></p>
            <p class="mb-2">Forgotten your password? Click <a href="forgottenPassword.php">here.</a></p>
            <p>Go to <a href="../index.php">homepage.</a></p>
        </form>
    </main>



</body>

</html>
