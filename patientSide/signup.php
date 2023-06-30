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
    require_once('../emailData.php');
    require("./PHPMailer-master/src/Exception.php");
    require("./PHPMailer-master/src/PHPMailer.php");
    require("./PHPMailer-master/src/SMTP.php");
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
            $nameErr = '*Name must be filled!';
            $invalid_name = 'is-invalid';
        } else {
            $emri = testInput($_POST['name']);
            if (!preg_match("/^[a-zA-z]*$/", $emri)) {
                $nameErr = '*Only alphabetical letters are allowed!';
                $invalid_name = 'is-invalid';
            } else {
                $nameErr = '';
                $name = $emri;
            }
        }

        if (empty($_POST['surname'])) {
            $surnameErr = '*Last name must be filled!';
            $invalid_surname = 'is-invalid';
        } else {
            $mbiemri = testInput($_POST['surname']);
            if (!preg_match("/^[a-zA-z]*$/", $mbiemri)) {
                $surnameErr = '*Only alphabetical letters are allowed!';
                $invalid_surname = 'is-invalid';
            } else {
                $surnameErr = '';
                $lastName = $mbiemri;
            }
        }

        if (empty($_POST['personal_id'])) {
            $personalNrErr = '*Personal ID must be filled!';
            $invalid_personal_id = 'is-invalid';
        } else {
            $numri_personal = testInput($_POST['personal_id']);
            if (!preg_match("/^[0-9]*$/", $numri_personal)) {
                $personalNrErr = "*Only numbers are allowed!";
                $invalid_personal_id = 'is-invalid';
            } else {
                if (strlen($numri_personal) != 10) {
                    $personalNrErr = '*Personal ID must be 10 characters!';
                    $invalid_personal_id = 'is-invalid';
                } else {
                    $check_personal_id = "SELECT numri_personal FROM patient_table WHERE numri_personal=:numri_personal";
                    $personal_id_prep = $con->prepare($check_personal_id);
                    $personal_id_prep->bindParam(':numri_personal', $numri_personal);
                    $personal_id_prep->execute();
                    $personal_id_data = $personal_id_prep->fetch();

                    if ($personal_id_data) {
                        $personalNrErr = '*An account already exists using this ID';
                        $invalid_personal_id = 'is-invalid';
                    } else {
                        $personalNrErr = '';
                        $personalNumber = $numri_personal;
                    }
                }
            }
        }

        if (!isset($_POST['gender'])) {
            $genderErr = '*Gender msut be selected!';
        } else {
            $gjinia = testInput($_POST['gender']);
            $genderErr = '';
            $gender = $gjinia;
            if($gender == 'Mashkull'){
                $maleGender = 'checked';
            } else if($gender == 'Femer'){
                $femaleGender = 'checked';
            }
        }

        if (empty($_POST['email'])) {
            $emailErr = '*Email must be filled!';
            $invalid_email = 'is-invalid';
        } else {
            $pattern = "^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^";
            $email = testInput($_POST['email']);
            if (!preg_match($pattern, $email)) {
                $emailErr = '*The given email is invalid!';
                $invalid_email = 'is-invalid';
            } else {
                $check_email = "SELECT email FROM patient_table WHERE email=:email";
                $check_email_prep = $con->prepare($check_email);
                $check_email_prep->bindParam(':email', $email);
                $check_email_prep->execute();
                $check_email_data = $check_email_prep->fetch();

                if ($check_email_data) {
                    $emailErr = '*An account already exists using this email!';
                    $invalid_email = 'is-invalid';
                } else {
                    $userEmail = $email;
                    $emailErr = '';
                }
            }
        }

        if (empty($_POST['birthday'])) {
            $birthdayErr = '*Birthday must be filled.';
            $invalid_birthday = 'is-invalid';
        } else {
            $ditlindja = testInput($_POST['birthday']);
            $birthdayErr = '';
            $birthday = $ditlindja;
        }

        if (empty($_POST['phone'])) {
            $phoneErr = '*Phone number must be filled!';
            $invalid_phone = 'is-invalid';
        } else {
            $telefoni = testInput($_POST['phone']);
            if (!preg_match('/^[0-9]{9}+$/', $telefoni)) {
                $phoneErr = '*The given phone number is invalid!';
                $invalid_phone = 'is-invalid';
            } else {
                $check_phone = "SELECT telefoni FROM patient_table WHERE telefoni=:telefoni";
                $check_phone_prep = $con->prepare($check_phone);
                $check_phone_prep->bindParam(':telefoni', $telefoni);
                $check_phone_prep->execute();
                $check_phone_data = $check_phone_prep->fetch();

                if ($check_phone_data) {
                    $phoneErr = '*An account already exists using this phone number!';
                    $invalid_phone = 'is-invalid';
                } else {
                    $phoneErr = '';
                    $phone = $telefoni;
                }
            }
        }

        if (empty($_POST['adress'])) {
            $adressErr = '*Adress must be filled!';
            $invalid_adress = 'is-invalid';
        } else {
            $adresa = testInput($_POST['adress']);
            $adressErr = '';
            $addres = $adresa;
        }

        if (empty($_POST['username'])) {
            $usernameErr = '*Username must be filled!';
            $invalid_username = 'is-invalid';
        } else {
            $username = testInput($_POST['username']);

            $check_username = "SELECT username FROM patient_table WHERE username=:username";
            $check_username_prep = $con->prepare($check_username);
            $check_username_prep->bindParam(':username', $username);
            $check_username_prep->execute();
            $check_username_data = $check_username_prep->fetch();

            if ($check_username_data) {
                $usernameErr = '*An account already exists using this username!';
                $invalid_username = 'is-invalid';
            } else {
                $usernameErr = '';
                $user1 = $username;
            }
        }

        if (empty($_POST['password'])) {
            $PassErr = '*Password must be filled!';
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
                $mail->Username   = SITE_EMAIL;                            //SMTP username
                $mail->Password   = SITE_PASSWORD;                         //SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            //Enable implicit TLS encryption
                $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

                //Recipients
                $mail->setFrom('no@reply.com', 'terminet-online.com');
                $mail->addAddress($email, $emri.' '.$mbiemri);                           //Add a recipient


                //Content
                $mail->isHTML(true);                                        //Set email format to HTML
                $veri_code = rand(111111, 999999);

                $mail->Subject = 'Email verification';
                $mail->Body    = "<p style='font-size: 16px;'>
                                Verification code: <b>$veri_code</b> <br>
                                This code is available for only 02:30 minutes!
                                </p>";

                $mail->send();

                $veri_date = date('Y-m-d');
                $veri_time = date('H:i:s');
                $verificated = false;

                $sql = "INSERT INTO patient_table(emri, mbiemri, numri_personal, gjinia, email, telefoni, ditlindja, adresa, username, password, veri_code, veri_date, veri_time, verificated)
                VALUES(:emri, :mbiemri, :numri_personal, :gjinia, :email, :telefoni, :ditlindja, :adresa, :username, :password, :veri_code, :veri_date, :veri_time, :verificated)";
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
                $prep->bindParam(':veri_code', $veri_code);
                $prep->bindParam(':veri_date', $veri_date);
                $prep->bindParam(':veri_time', $veri_time);
                $prep->bindParam(':verificated', $verificated);


                if($prep->execute()){
                    $_SESSION['verify'] = $username;
                    echo "<script>
                            alert('Please verify your account! Check your email for the verification code.);
                            window.location.replace('./emailVerification.php');   
                        </script>";
                }
                exit();


            } catch (Exception $e) {
                echo "<script>
                        alert('Sign up failed! Please check your internet connection before trying again!');
                    </script>";
            }
        }
    }

    ?>
    <main class="form-signin">
        <form method="POST" autocomplete="off" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <h1 class="h3 mb-3 fw-normal">Sign up</h1>

            <div class="form-floating mb-1">
                <input type="text" class="form-control <?= $invalid_name ?? "" ?>" id="floatingInput name" name="name" placeholder="Name" value="<?= $name ?>">
                <label for="floatingInput">Name</label>
                <span class="text-danger fw-normal"><?php echo $nameErr; ?></span>
            </div>

            <div class="form-floating mb-1">
                <input type="text" class="form-control <?= $invalid_surname ?? "" ?>" id="floatingInput" name="surname" placeholder="Last name" value="<?= $lastName ?>">
                <label for="floatingInput">Last name</label>
                <span class="text-danger fw-normal"><?php echo $surnameErr; ?></span>
            </div>

            <div class="form-floating mb-1">
                <input type="text" class="form-control <?= $invalid_personal_id ?? "" ?>" id="floatingInput" name="personal_id" placeholder="Personal ID" maxlength="10" value="<?= $personalNumber ?>">
                <label for="floatingInput">Personal ID</label>
                <span class="text-danger fw-normal"><?php echo $personalNrErr; ?></span>
            </div>

            <div class="form-check form-check-inline mb-1">
                <input class="form-check-input" type="radio" <?= $maleGender ?? '' ?> name="gender" id="inlineRadio1" value="Mashkull">
                <label class="form-check-label" for="inlineRadio1">Male</label>
            </div>

            <div class="form-check form-check-inline mb-1">
                <input class="form-check-input" type="radio" <?= $femaleGender ?? '' ?> name="gender" id="inlineRadio2" value="Femer">
                <label class="form-check-label" for="inlineRadio2">Female</label>
            </div> <br>
            <span class="text-danger fw-normal"><?php echo $genderErr; ?></span>

            <div class="form-floating mb-1">
                <input type="email" class="form-control rounded <?= $invalid_email ?? "" ?>" id="floatingInput" name="email" placeholder="name@example.com" value="<?= $userEmail ?>">
                <label for="floatingInput">Email</label>
                <span class="text-danger fw-normal"><?php echo $emailErr; ?></span>
            </div>

            <div class="mb-1">
                <label for="startDate">Birthday:</label>
                <input id="startDate" class="form-control ditlindja <?= $invalid_birthday ?? "" ?>" name="birthday" type="date" value="<?= $birthday ?>" />
                <span class="text-danger fw-normal"><?php echo $birthdayErr; ?></span>
            </div>

            <div class="form-floating mb-1">
                <input type="tel" class="form-control <?= $invalid_phone ?? "" ?>" id="floatingInput" name="phone" placeholder="Phone number" value="<?= $phone ?>">
                <label for="floatingInput">Phone number</label>
                <span class="text-danger fw-normal"><?php echo $phoneErr; ?></span>
            </div>

            <div class="form-floating mb-1">
                <input type="text" class="form-control <?= $invalid_adress ?? "" ?>" id="floatingInput" name="adress" placeholder="Adress" value="<?= $addres ?>">
                <label for="floatingInput">Adress</label>
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

            <button class="w-100 btn btn-lg btn-primary mt-4" type="submit" name="submit">Sign up</button>
            <p>Already have an account?  <a href="login.php">Log in</a></p>
        </form>
    </main>
</body>

</html>