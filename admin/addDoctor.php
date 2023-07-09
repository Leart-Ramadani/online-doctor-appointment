<?php
error_reporting(0);
ini_set('display_errors', 0);


include('../config.php');

$nameErr = $surnameErr = $departamentErr = $genderErr = $emailErr = $photoErr = $phoneErr   = $userErr = $passErr = "";
$name = $lastName = $personalNumber = $genderMale = $genderFem = $userEmail = $phone = $user1 = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['set'])) {
    $name = $_POST['docName'];
    $departament = $_POST['departament'];
    $gender = $_POST['gender'];
    $email = $_POST['email'];
    $image = $_POST['file'];
    $phone = $_POST['phone'];
    $username = $_POST['username'];
    $password = $_POST['password'];



    function testInput($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    if (empty($name)) {
        $nameErr = '*Emri duhet plotesuar.';
    } else {
        $name = testInput($name);
        if (!preg_match("/^[a-z A-z]*$/", $name)) {
            $nameErr = '*Nuk lejohen karaktere tjera perveq shkronjave.';
        } else {
            $nameErr = '';
        }
    }



    if (empty($departament)) {
        $departamentErr = '*Ju duhet te zgjedhni nje departament.';
    } else {
        $departamentErr = '';
    }

    if (empty($gender)) {
        $genderErr = '*Gjinia duhet zgjedhur';
    } else {
        $genderErr = '';
    }



    if (empty($email)) {
        $emailErr = '*Emaili duhet plotesuar.';
    } else {
        $pattern = "^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^";
        $email = testInput($email);
        if (!preg_match($pattern, $email)) {
            $emailErr = '*Ky email nuk eshte valid.';
        } else {
            $emailErr = '';
        }
    }



    if (isset($_POST['file']) && empty($_POST['file'])) {
        $photoErr = '*Duhet te shtoni nje foto te personit ne fjale.';
    } else {
        $photoErr = '';
        $img_name = $_FILES['file']['name'];
        $img_size = $_FILES['file']['size'];
        $tmp_name = $_FILES['file']['tmp_name'];
        $error = $_FILES['file']['error'];

        if ($error === 0) {
            $photoErr = '';
            if ($img_size > 12500000) {
                $photoErr = "*Ky file eshte shume i madh.";
            } else {
                $photoErr = '';
                $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
                $img_ex_lc = strtolower($img_ex);

                $allowed_exs = array("jpg", "jpeg", "png", "gif", "webp");

                if (in_array($img_ex_lc, $allowed_exs)) {
                    $new_img_name = uniqid("IMG-", true) . '.' . $img_ex_lc;
                    $img_upload_path = 'uploads/' . $new_img_name;
                    move_uploaded_file($tmp_name, $img_upload_path);
                } else {
                    $photoErr = "*Ky format nuk eshte valid. Formatet e lejuara(jpg, jpeg, png, gif, webp).";
                }
            }
        } else {
            $photoErr = "*Eshte shfaqur nje gabim i panjohur!";
        }
    }


    if (empty($phone)) {
        $phoneErr = '*Numri i telefonit duhet vendosur.';
    } else {
        $phone = testInput($phone);
        if (!preg_match('/^[0-9]{9}+$/', $phone)) {
            $phoneErr = '*Ky numer i telefonit nuk eshte valid.';
        } else {
            $phoneErr = '';
        }
    }



    if (empty($username)) {
        $userErr = '*Username duhet plotesuar.';
    } else {
        $username = testInput($username);
        $userErr = '';
    }


    if (empty($password)) {
        $passErr = '*Fjalkalimi duhet plotesuar.';
    } else {
        $password = testInput($password);
        $passErr = '';
        $encPass = password_hash($password, PASSWORD_DEFAULT);
    }




    $response = [$nameErr, $departamentErr, $genderErr, $emailErr, $photoErr, $phoneErr, $userErr, $passErr];
    
    if (
        $nameErr == '' && $departamentErr == '' && $genderErr == '' && $emailErr == '' && $photoErr == ''
        && $phoneErr == '' && $userErr == '' && $passErr == ''
    ) {
        try{
            $sql = "INSERT INTO doctor_personal_info(fullName, departamenti, gjinia, email, foto, telefoni, username, password)
            VALUES(:fullName, :departamenti, :gjinia, :email, :foto, :telefoni, :username, :password)";
            $stm = $con->prepare($sql);
            $stm->bindParam(':fullName', $name);
            $stm->bindParam(':departamenti', $departament);
            $stm->bindParam(':gjinia', $gender);
            $stm->bindParam(':email', $email);
            $stm->bindParam(':foto', $new_img_name);
            $stm->bindParam(':telefoni', $phone);
            $stm->bindParam(':username', $username);
            $stm->bindParam(':password', $encPass);

            if($stm->execute()){
            $response[] = "inserted";

            echo json_encode($response);
            }
        } catch(Exception $e){
            echo $e->getMessage();
        }
    } else {
        echo json_encode($response);
    }
}
