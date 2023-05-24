<?php include('../config.php'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rivendos fjalekalimin</title>
    <link rel="shortcut icon" href="../photos/icon-hospital.png">
    <link rel="stylesheet" href="../css/main.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link rel="stylesheet" href="../bootstrap-5.1.3-examples/sidebars/sidebars.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous" defer></script>
    <script src="../bootstrap-5.1.3-examples/sidebars/sidebars.js" defer></script>
</head>

<body>
    <?php
    $error = '';

    require 'C:\xampp\htdocs\Sistemi-per-rezervimin-e-termineve\patientSide\PHPMailer-master\src\Exception.php';
    require 'C:\xampp\htdocs\Sistemi-per-rezervimin-e-termineve\patientSide\PHPMailer-master\src\PHPMailer.php';
    require 'C:\xampp\htdocs\Sistemi-per-rezervimin-e-termineve\patientSide\PHPMailer-master\src\SMTP.php';
    //Import PHPMailer classes into the global namespace
    //These must be at the top of your script, not inside a function

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    if (isset($_POST['submit'])) {
        $email = $_POST['email'];

        $sql = "SELECT * FROM patient_table WHERE email=:email";
        $prep = $con->prepare($sql);
        $prep->bindParam(':email', $email);
        $prep->execute();
        $data = $prep->fetch();

        if (!$data) {
            $error = "*Ky username nuk eksizton.";
            $invalid_error = 'is-invalid';
        } else{
            $mail = new PHPMailer(true);

            try {
                //Server settings
                $mail->SMTPDebug = 0;                                       //Enable verbose debug output
                $mail->isSMTP();                                            //Send using SMTP
                $mail->Host       = 'smtp.gmail.com';                       //Set the SMTP server to send through
                $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                $mail->Username   = 'terminetonline193@gmail.com';            //SMTP username
                $mail->Password   = 'feqjqxuujfpugmls';                     //SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         //Enable implicit TLS encryption
                $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

                //Recipients
                $mail->setFrom('terminet.online@gmail.com', 'terminet-online.com');
                $mail->addAddress($email, $data['emri'].' '.$data['mbiemri']);                           //Add a recipient


                //Content
                $mail->isHTML(true);                                        //Set email format to HTML


                $mail->Subject = 'Rivendos fjalkalimin';
                $mail->Body    = "<p style='font-size: 18px;'>Kliko <a href='localhost/online-doctor-appointment-master/patientSide/resetPassword.php?email=$email'>ketu</a> 
                per te rivendosur fjalkalimin.</p>";

                $mail->send();

                echo "<script>
                        alert('Ne ju derguam linkun per te rivendosur fjalkalimin ne emailin: $email.');
                        window.location.replace('login.php');
                    </script>";

            } catch(Exception $e){
                echo "Fatal error {$mail->ErrorInfo}";
            }
        }
    }
    ?>
    <form method="post" class="form-signin" autocomplete="off">
        <h1 class="h3 mb-4 fw-normal">Rivendos fjalekalimin</h1>
        <div class="form-floating">
            <input type="email" class="form-control rounded <?= $invalid_error ?? '' ?>" id="floatingInput" name="email" placeholder="Email">
            <label for="floatingInput">Email</label>
            <span class="text-danger fw-normal"><?php echo $error; ?></span>
        </div>

        <button class="w-100 btn btn-lg btn-primary mt-3" type="submit" name="submit">Submit</button>
    </form>

</body>

</html>