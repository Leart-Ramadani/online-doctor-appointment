<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign up</title>
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
    require 'C:\xampp\htdocs\Sistemi-per-rezervimin-e-termineve\patientSide\PHPMailer-master\src\Exception.php';
    require 'C:\xampp\htdocs\Sistemi-per-rezervimin-e-termineve\patientSide\PHPMailer-master\src\PHPMailer.php';
    require 'C:\xampp\htdocs\Sistemi-per-rezervimin-e-termineve\patientSide\PHPMailer-master\src\SMTP.php';
    //Import PHPMailer classes into the global namespace
    //These must be at the top of your script, not inside a function

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;






    $nameErr = $surnameErr = $personalNrErr = $genderErr = $emailErr = $birthdayErr = $phoneErr = $adressErr = $usernameErr = $PassErr = "";
    $name = $lastName = $personalNumber = $gender = $userEmail = $birthday = $phone = $addres = $user1 = "";
    include('../config.php');

    if (isset($_POST['submit'])) {

        function testInput($data)
        {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        if (empty($_POST['name'])) {
            $nameErr = 'Emri duhet plotesuar.';
            $invalid_name = 'is-invalid';
        } else {
            $emri = testInput($_POST['name']);
            if (!preg_match("/^[a-zA-z]*$/", $emri)) {
                $nameErr = 'Nuk lejohen karaktere tjera perveq shkronjave.';
                $invalid_name = 'is-invalid';
            } else {
                $nameErr = '';
                $name = $emri;
            }
        }

        if (empty($_POST['surname'])) {
            $surnameErr = 'Mbiemri duhet plotesuar.';
            $invalid_surname = 'is-invalid';
        } else {
            $mbiemri = testInput($_POST['surname']);
            if (!preg_match("/^[a-zA-z]*$/", $mbiemri)) {
                $surnameErr = 'Nuk lejohen karaktere tjera perveq shkronjave.';
                $invalid_surname = 'is-invalid';
            } else {
                $surnameErr = '';
                $lastName = $mbiemri;
            }
        }

        if (empty($_POST['personal_id'])) {
            $personalNrErr = 'Numri personal duhet plotesuar.';
            $invalid_personal_id = 'is-invalid';
        } else {
            $numri_personal = testInput($_POST['personal_id']);
            if (!preg_match("/^[0-9]*$/", $numri_personal)) {
                $personalNrErr = "Nuk lejohen karaktere tjera perveq numrave.";
                $invalid_personal_id = 'is-invalid';
            } else {
                if (strlen($numri_personal) != 10) {
                    $personalNrErr = '*Numri personal nuk duhet te jete me i shkurter se 10 karaktere';
                    $invalid_personal_id = 'is-invalid';
                } else {
                    $check_personal_id = "SELECT numri_personal FROM patient_table WHERE numri_personal=:numri_personal";
                    $personal_id_prep = $con->prepare($check_personal_id);
                    $personal_id_prep->bindParam(':numri_personal', $numri_personal);
                    $personal_id_prep->execute();
                    $personal_id_data = $personal_id_prep->fetch();

                    if ($personal_id_data) {
                        $personalNrErr = 'Nje llogari eshte e hapur me kete numer personal.';
                        $invalid_personal_id = 'is-invalid';
                    } else {
                        $personalNrErr = '';
                        $personalNumber = $numri_personal;
                    }
                }
            }
        }

        if (!isset($_POST['gender'])) {
            $genderErr = 'Gjinia duhet zgjedhur';
        } else {
            $gjinia = testInput($_POST['gender']);
            $genderErr = '';
            $gender = $gjinia;
        }

        if (empty($_POST['email'])) {
            $emailErr = 'Email duhet plotesuar.';
            $invalid_email = 'is-invalid';
        } else {
            $pattern = "^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^";
            $email = testInput($_POST['email']);
            if (!preg_match($pattern, $email)) {
                $emailErr = 'Email adresa e mesiperme nuk eshte valide.';
                $invalid_email = 'is-invalid';
            } else {
                $check_email = "SELECT email FROM patient_table WHERE email=:email";
                $check_email_prep = $con->prepare($check_email);
                $check_email_prep->bindParam(':email', $email);
                $check_email_prep->execute();
                $check_email_data = $check_email_prep->fetch();

                if ($check_email_data) {
                    $emailErr = 'Nje llogari eshte e hapur me kete email.';
                    $invalid_email = 'is-invalid';
                } else {
                    $userEmail = $email;
                    $emailErr = '';
                }
            }
        }

        if (empty($_POST['birthday'])) {
            $birthdayErr = 'Ditelindja duhet plotesuar.';
            $invalid_birthday = 'is-invalid';
        } else {
            $ditlindja = testInput($_POST['birthday']);
            $birthdayErr = '';
            $birthday = $ditlindja;
        }

        if (empty($_POST['phone'])) {
            $phoneErr = 'Telefoni duhet plotesuar.';
            $invalid_phone = 'is-invalid';
        } else {
            $telefoni = testInput($_POST['phone']);
            if (!preg_match('/^[0-9]{9}+$/', $telefoni)) {
                $phoneErr = 'Numri i telefonit i mesiperm nuk eshte valid.';
                $invalid_phone = 'is-invalid';
            } else {
                $check_phone = "SELECT telefoni FROM patient_table WHERE telefoni=:telefoni";
                $check_phone_prep = $con->prepare($check_phone);
                $check_phone_prep->bindParam(':telefoni', $telefoni);
                $check_phone_prep->execute();
                $check_phone_data = $check_phone_prep->fetch();

                if ($check_phone_data) {
                    $phoneErr = 'Nje llogari eshte hapur me kete numer te telefonit.';
                    $invalid_phone = 'is-invalid';
                } else {
                    $phoneErr = '';
                    $phone = $telefoni;
                }
            }
        }

        if (empty($_POST['adress'])) {
            $adressErr = 'Adresa duhet plotesuar.';
            $invalid_adress = 'is-invalid';
        } else {
            $adresa = testInput($_POST['adress']);
            $adressErr = '';
            $addres = $adresa;
        }

        if (empty($_POST['username'])) {
            $usernameErr = 'Username duhet plotesuar.';
            $invalid_username = 'is-invalid';
        } else {
            $username = testInput($_POST['username']);

            $check_username = "SELECT username FROM patient_table WHERE username=:username";
            $check_username_prep = $con->prepare($check_username);
            $check_username_prep->bindParam(':username', $username);
            $check_username_prep->execute();
            $check_username_data = $check_username_prep->fetch();

            if ($check_username_data) {
                $usernameErr = 'Nje llogari eshte e hapur me kete username';
                $invalid_username = 'is-invalid';
            } else {
                $usernameErr = '';
                $user1 = $username;
            }
        }

        if (empty($_POST['password'])) {
            $PassErr = 'Password duhet plotesuar.';
            $invalid_pass = 'is-invalid';
        } else {
            $password = testInput($_POST['password']);
            $encPass = password_hash($password, PASSWORD_DEFAULT);
            $PassErr = '';
        }





        if (
            $nameErr == '' && $surnameErr == '' && $personalNrErr == '' && $genderErr == '' && $emailErr == '' && $phoneErr == '' &&
            $birthdayErr == '' && $adressErr == '' && $usernameErr == '' && $PassErr == ''
        ) {

            //Create an instance; passing `true` enables exceptions
            $mail = new PHPMailer(true);

            try {
                //Server settings
                $mail->SMTPDebug = 0;                                       //Enable verbose debug output
                $mail->isSMTP();                                            //Send using SMTP
                $mail->Host       = 'smtp.gmail.com';                       //Set the SMTP server to send through
                $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                $mail->Username   = 'terminet.online@gmail.com';            //SMTP username
                $mail->Password   = 'vaiddzxpncfvvksh';                          //SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            //Enable implicit TLS encryption
                $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

                //Recipients
                $mail->setFrom('terminet.online@gmail.com', 'terminet-online.com');
                $mail->addAddress($email, $emri.' '.$mbiemri);                           //Add a recipient


                //Content
                $mail->isHTML(true);                                        //Set email format to HTML


                $mail->Subject = 'Email verification';
                $mail->Body    = "<p style='font-size: 18px;'>Aktivizo llogarin duke klikuar ne kete <a href='localhost/Sistemi-per-rezervimin-e-termineve/patientSide/emailVerification.php?email=$email'>link.</a> </p>";

                $mail->send();

                $defaultVerification = 'false';

                $sql = "INSERT INTO patient_table(emri, mbiemri, numri_personal, gjinia, email, telefoni, ditlindja, adresa, username, password, verification_status)
                VALUES(:emri, :mbiemri, :numri_personal, :gjinia, :email, :telefoni, :ditlindja, :adresa, :username, :password, :verification_status)";
                $prep = $con->prepare($sql);
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
                $prep->bindParam(':verification_status', $defaultVerification);

                if($prep->execute()){
                    echo "<script>
                            alert('Ju lutem verifikojeni llogarin tuaj. Shikoni emailin tuaj per linkun verifikues.');
                            window.location.replace('login.php');   
                        </script>";
                }
                exit();


            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        }
    }

    ?>
    <main class="form-signin">
        <form method="POST" autocomplete="off" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <h1 class="h3 mb-3 fw-normal">Regjistrohuni</h1>

            <div class="form-floating mb-1">
                <input type="text" class="form-control <?= $invalid_name ?? "" ?>" id="floatingInput name" name="name" placeholder="Emri" value="<?= $name ?>">
                <label for="floatingInput">Emri</label>
                <span class="text-danger fw-normal"><?php echo $nameErr; ?></span>
            </div>

            <div class="form-floating mb-1">
                <input type="text" class="form-control <?= $invalid_surname ?? "" ?>" id="floatingInput" name="surname" placeholder="Mbiemri" value="<?= $lastName ?>">
                <label for="floatingInput">Mbiemri</label>
                <span class="text-danger fw-normal"><?php echo $surnameErr; ?></span>
            </div>

            <div class="form-floating mb-1">
                <input type="text" class="form-control <?= $invalid_personal_id ?? "" ?>" id="floatingInput" name="personal_id" placeholder="Numri personal" maxlength="10" value="<?= $personalNumber ?>">
                <label for="floatingInput">Numri personal</label>
                <span class="text-danger fw-normal"><?php echo $personalNrErr; ?></span>
            </div>

            <div class="form-check form-check-inline mb-1">
                <input class="form-check-input" type="radio" name="gender" id="inlineRadio1" value="Mashkull">
                <label class="form-check-label" for="inlineRadio1">Mashkull</label>
            </div>

            <div class="form-check form-check-inline mb-1">
                <input class="form-check-input" type="radio" name="gender" id="inlineRadio2" value="Femer">
                <label class="form-check-label" for="inlineRadio2">Femer</label>
            </div> <br>
            <span class="text-danger fw-normal"><?php echo $genderErr; ?></span>

            <div class="form-floating mb-1">
                <input type="email" class="form-control rounded <?= $invalid_email ?? "" ?>" id="floatingInput" name="email" placeholder="name@example.com" value="<?= $userEmail ?>">
                <label for="floatingInput">Email</label>
                <span class="text-danger fw-normal"><?php echo $emailErr; ?></span>
            </div>

            <div class="mb-1">
                <label for="startDate">Ditelindja:</label>
                <input id="startDate" class="form-control ditlindja <?= $invalid_birthday ?? "" ?>" name="birthday" type="date" value="<?= $birthday ?>" />
                <span class="text-danger fw-normal"><?php echo $birthdayErr; ?></span>
            </div>

            <div class="form-floating mb-1">
                <input type="tel" class="form-control <?= $invalid_phone ?? "" ?>" id="floatingInput" name="phone" placeholder="Telefoni" value="<?= $phone ?>">
                <label for="floatingInput">Telefoni</label>
                <span class="text-danger fw-normal"><?php echo $phoneErr; ?></span>
            </div>

            <div class="form-floating mb-1">
                <input type="text" class="form-control <?= $invalid_adress ?? "" ?>" id="floatingInput" name="adress" placeholder="Adresa" value="<?= $addres ?>">
                <label for="floatingInput">Adresa</label>
                <span class="text-danger fw-normal"><?php echo $adressErr; ?></span>
            </div>

            <div class="form-floating mb-1">
                <input type="text" class="form-control <?= $invalid_username ?? "" ?>" id="floatingInput" name="username" placeholder="Username" value="<?= $user1 ?>">
                <label for="floatingInput">Username</label>
                <span class="text-danger fw-normal"><?php echo $usernameErr; ?></span>
            </div>

            <div class="form-floating">
                <input type="password" class="form-control rounded mb-0 <?= $invalid_pass ?? "" ?>" id="floatingPassword" name="password" placeholder="Password">
                <label for="floatingPassword">Password</label>
                <span class="text-danger fw-normal"><?php echo $PassErr; ?></span>
            </div>

            <button class="w-100 btn btn-lg btn-primary mt-4" type="submit" name="submit">Regjistrohuni</button>
            <p>Keni nje llogari? Klikoni <a href="login.php">ketu.</a></p>
        </form>
    </main>
</body>

</html>