<?php
include('../config.php');
if (isset($_SESSION['admin'])) {
    header("Location: doktoret.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log in</title>
    <link rel="stylesheet" href="../css/main.css">
    <link rel="icon" href="../photos/icon-admin.png">
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

        .form-check {
            margin-top: 10px;
            margin-bottom: 10px;
        }

        .ditlindja {
            width: 300px;
            height: 50px;
        }
    </style>
</head>

<body class="text-center">
    <?php
    $usernameErr = $passErr = $user = '';

    if (isset($_POST['submit'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $sql = "SELECT username, password from admin_table WHERE username=:username";
        $stm = $con->prepare($sql);
        $stm->bindParam(':username', $username);
        $stm->execute();
        $data = $stm->fetch();

        if ($data === false) {
            $usernameErr = "*This username doesn't exists!";
            $invalidUser = 'is-invalid';
        } elseif (password_verify($password, $data['password'])) {
            $_SESSION['admin'] = $data['username'];
            header("Location: doktoret.php");
            $usernameErr = $passErr = '';
        } else {
            $passErr = '*Incorrect password!';
            $invalidPass = 'is-invalid';
            $user = $username;
        }
    }
    ?>
    <main class="form-signin">
        <form method="POST" autocomplete="off">
            <h1 class="h3 mb-4 fw-normal">Login in admin side</h1>

            <div class="form-floating mb-2">
                <input type="text" class="form-control <?= $invalidUser ?? '' ?>" id="floatingInput" name="username" placeholder="Username" value="<?= $user; ?>">
                <label for="floatingInput">Username</label>
                <span class="text-danger fw-normal"><?php echo $usernameErr; ?></span>
            </div>

            <div class="form-floating">
                <input type="password" class="form-control rounded <?= $invalidPass ?? '' ?>" id="floatingPassword" name="password" placeholder="Password">
                <label for="floatingPassword">Password</label>
                <span class="text-danger fw-normal"><?php echo $passErr; ?></span>
            </div>

            <div class="form-check d-flex mb-2 justify-content-end">
                <label class="form-check-label" for="flexCheckDefault">Show password</label>
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

            <button class="w-100 btn btn-lg btn-primary" type="submit" name="submit">Login</button>
        </form>
    </main>
</body>

</html>