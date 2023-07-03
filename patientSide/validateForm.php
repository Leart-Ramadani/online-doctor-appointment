<?php
// Config file
include('../config.php');

// File that has the email and the password to send emails
require_once('../emailData.php');

// PHP Mailer files
require("./PHPMailer-master/src/Exception.php");
require("./PHPMailer-master/src/PHPMailer.php");
require("./PHPMailer-master/src/SMTP.php");
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;






$nameErr = $surnameErr = $personalNrErr = $genderErr = $emailErr = $birthdayErr = $phoneErr = $adressErr = $usernameErr = $PassErr = $confirmPassErr = "";



function testInput($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}


// First tab that validates the inputs
if (isset($_POST['set']) && $_POST['id'] == 1) {
    $name = $_POST['name'];
    $lastName = $_POST['lastName'];
    $perosnal_ID = $_POST['personal_ID'];
    $gender = $_POST['gender'];

    if (empty($name)) {
        $nameErr = '*Name must be filled!';
    } else {
        $name = testInput($name);
        if (!preg_match("/^[a-zA-z]*$/", $name)) {
            $nameErr = '*Only alphabetical letters are allowed!';
        } else {
            $nameErr = '';
        }
    }

    if (empty($lastName)) {
        $surnameErr = '*Last name must be filled!';
    } else {
        $lastName = testInput($lastName);
        if (!preg_match("/^[a-zA-z]*$/", $lastName)) {
            $surnameErr = '*Only alphabetical letters are allowed!LastName';
        } else {
            $surnameErr = '';
        }
    }

    if (empty($perosnal_ID)) {
        $personalNrErr = '*Personal ID must be filled!';
    } else {
        $perosnal_ID = testInput($perosnal_ID);
        if (!preg_match("/^[0-9]*$/", $perosnal_ID)) {
            $personalNrErr = "*Only numbers are allowed!Personal_id";
        } else {
            if (strlen($perosnal_ID) != 10) {
                $personalNrErr = '*Personal ID must be 10 characters!';
            } else {
                $check_personal_id = "SELECT numri_personal FROM patient_table WHERE numri_personal=:numri_personal";
                $personal_id_prep = $con->prepare($check_personal_id);
                $personal_id_prep->bindParam(':numri_personal', $perosnal_ID);
                $personal_id_prep->execute();
                $personal_id_data = $personal_id_prep->fetch();

                if ($personal_id_data) {
                    $personalNrErr = '*An account already exists using this ID';
                } else {
                    $personalNrErr = '';
                }
            }
        }
    }

    if (empty($gender)) {
        $genderErr = '*You must select the gender.';
    } else {
        $genderErr = '';
    }

    if ($nameErr == "" && $surnameErr == "" && $personalNrErr == "" && $genderErr == "") {
        $response = [$nameErr, $surnameErr, $personalNrErr, $genderErr];
        $response = json_encode($response);
        echo $response;
    } else {
        $response = [$nameErr, $surnameErr, $personalNrErr, $genderErr];
        $response = json_encode($response);
        echo $response;
    }
}


// Second tab that validates the inputs
if (isset($_POST['set']) && $_POST['id'] == 2) {
    $email = $_POST['email'];
    $birthday = $_POST['birthday'];
    $phone = $_POST['phone'];
    $adress = $_POST['adress'];

    if (empty($email)) {
        $emailErr = '*Email must be filled!';
    } else {
        $pattern = "^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^";
        $email = testInput($email);
        if (!preg_match($pattern, $email)) {
            $emailErr = '*The given email is invalid!';
        } else {
            $check_email = "SELECT email FROM patient_table WHERE email=:email";
            $check_email_prep = $con->prepare($check_email);
            $check_email_prep->bindParam(':email', $email);
            $check_email_prep->execute();
            $check_email_data = $check_email_prep->fetch();

            if ($check_email_data) {
                $emailErr = '*An account already exists using this email!';
            } else {
                $emailErr = '';
            }
        }
    }

    if (empty($birthday)) {
        $birthdayErr = '*Birthday must be filled.';
    } else {
        $birthday = testInput($birthday);

        $birthdayDate = new DateTime($birthday);
        $currentDate = new DateTime();

        $age = $currentDate->diff($birthdayDate)->y;

        if ($age <= 18) {
            $birthdayErr = '*You must be 18 years or older.';
        } else {
            $birthdayErr = '';
        }
    }


    if (empty($phone)) {
        $phoneErr = '*Phone number must be filled!';
    } else {
        $phone = testInput($phone);
        if (!preg_match('/^[0-9]{9}+$/', $phone)) {
            $phoneErr = '*The given phone number is invalid!';
        } else {
            $check_phone = "SELECT telefoni FROM patient_table WHERE telefoni=:telefoni";
            $check_phone_prep = $con->prepare($check_phone);
            $check_phone_prep->bindParam(':telefoni', $phone);
            $check_phone_prep->execute();
            $check_phone_data = $check_phone_prep->fetch();

            if ($check_phone_data) {
                $phoneErr = '*An account already exists using this phone number!';
            } else {
                $phoneErr = '';
            }
        }
    }

    if (empty($adress)) {
        $adressErr = '*Adress must be filled!';
    } else {
        $adresa = testInput($adress);
        $adressErr = '';
    }

    if ($emailErr == "" && $birthdayErr == "" && $phoneErr == "" && $adressErr == "") {
        $response = [$emailErr, $birthdayErr, $phoneErr, $adressErr];
        $response = json_encode($response);
        echo $response;
    } else {
        $response = [$emailErr, $birthdayErr, $phoneErr, $adressErr];
        $response = json_encode($response);
        echo $response;
    }
}



// Second tab that validates the inputs
if (isset($_POST['set']) && $_POST['id'] == 3) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirmPass = $_POST['confirmPass'];

    if (empty($username)) {
        $usernameErr = '*Username must be filled!';
    } else {
        $username = testInput($username);

        $check_username = "SELECT username FROM patient_table WHERE username=:username";
        $check_username_prep = $con->prepare($check_username);
        $check_username_prep->bindParam(':username', $username);
        $check_username_prep->execute();
        $check_username_data = $check_username_prep->fetch();

        if ($check_username_data) {
            $usernameErr = '*An account already exists using this username!';
        } else {
            $usernameErr = '';
        }
    }

    if (empty($password)) {
        $PassErr = '*Password must be filled!';
    } else {
        $PassErr = '';
    }

    if (empty($confirmPass)) {
        $confirmPassErr = '*You must confirm your password!';
    } else if ($password !== $confirmPass) {
        $confirmPassErr = "*Password doesn't match!";
    } else {
        $confirmPassErr = '';
    }




    if ($usernameErr == "" && $PassErr == "" && $confirmPassErr == "") {
        $response = [$usernameErr, $PassErr, $confirmPassErr];
        $response = json_encode($response);
        echo $response;
    } else {
        $response = [$usernameErr, $PassErr, $confirmPassErr];
        $response = json_encode($response);
        echo $response;
    }
}


if (isset($_POST['set']) && $_POST['id'] == 4) {
    $name = $_POST['name'];
    $lastName = $_POST['lastName'];
    $perosnal_ID = $_POST['personal_ID'];
    $gender = $_POST['gender'];
    $email = $_POST['email'];
    $birthday = $_POST['birthday'];
    $phone = $_POST['phone'];
    $adress = $_POST['adress'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirmPass = $_POST['confirmPass'];
    $encPass = password_hash($confirmPass, PASSWORD_DEFAULT);
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
        $mail->addAddress($email, $name . ' ' . $lastName);                           //Add a recipient


        //Content
        $mail->isHTML(true);                                        //Set email format to HTML
        $veri_code = rand(111111, 999999);

        $mail->Subject = 'Email verification';
        $mail->Body    = "<p style='font-size: 16px;'>
                        Kodi i verifikimit: <b>$veri_code</b> <br>
                        Ky kod eshte i vlefshem per vetem 02:30 minuta!
                        </p>";

        $mail->send();

        $veri_date = date('Y-m-d');
        $veri_time = date('H:i:s');
        $verificated = false;

        $sql = "INSERT INTO patient_table(emri, mbiemri, numri_personal, gjinia, email, telefoni, ditlindja, adresa, username, password, veri_code, veri_date, veri_time, verificated)
                            VALUES(:emri, :mbiemri, :numri_personal, :gjinia, :email, :telefoni, :ditlindja, :adresa, :username, :password, :veri_code, :veri_date, :veri_time, :verificated)";
        $prep = $con->prepare($sql);
        $prep->bindParam(':emri', $name);
        $prep->bindParam(':mbiemri', $lastName);
        $prep->bindParam(':numri_personal', $perosnal_ID);
        $prep->bindParam(':gjinia', $gender);
        $prep->bindParam(':email', $email);
        $prep->bindParam(':telefoni', $phone);
        $prep->bindParam(':ditlindja', $birthday);
        $prep->bindParam(':adresa', $adress);
        $prep->bindParam(':username', $username);
        $prep->bindParam(':password', $encPass);
        $prep->bindParam(':veri_code', $veri_code);
        $prep->bindParam(':veri_date', $veri_date);
        $prep->bindParam(':veri_time', $veri_time);
        $prep->bindParam(':verificated', $verificated);


        if ($prep->execute()) {
            $_SESSION['verify'] = $username;
            echo "Registerd";
        } 

    } catch (Exception $e) {
        echo "Something went wrong";
    }
}
