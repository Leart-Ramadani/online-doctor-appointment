<?php
include('../config.php');


$id = $_GET['id'];

$sql = "SELECT * FROM doctor_personal_info WHERE id=:id";

$prep = $con->prepare($sql);
$prep->bindParam(':id', $id);
$prep->execute();

$row = $prep->fetch();


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orari</title>
    <link rel="stylesheet" href="../css/main.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link rel="stylesheet" href="../bootstrap-5.1.3-examples/sidebars/sidebars.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous" defer></script>
    <script src="../bootstrap-5.1.3-examples/sidebars/sidebars.js" defer></script>

</head>

<body>
    <?php
    $sql = "SELECT * FROM departamentet";
    $stm = $con->prepare($sql);
    $stm->execute();
    $data = $stm->fetchAll();
    ?>

    <main class="form-signin text-center main_side ">

        <?php
        $fullNameErr = $departamentErr = $genderErr = $emailErr = $photoErr = $phoneErr = $bioErr  = $userErr = $passErr = "";

        if (isset($_POST['edito'])) {

            function testInput($data)
            {
                $data = trim($data);
                $data = stripslashes($data);
                $data = htmlspecialchars($data);
                return $data;
            }


            if (empty($_POST['fullName'])) {
                $fullNameErr = '*Emri i plote duhet plotesuar.';
                $invalid_surname = 'is-invalid';
            } else {
                $fullName = testInput($_POST['fullName']);
                if (!preg_match("/^[a-z A-z]*$/", $fullName)) {
                    $fullNameErr = '*Nuk lejohen karaktere tjera perveq shkronjave.';
                    $invalid_fullName = 'is-invalid';
                } else {
                    $fullNameErr = '';
                }
            }

            if (empty($_POST['departament'])) {
                $departamentErr = '*Duhet te zgjidhni nje departament.';
                $invalid_dep = 'is-invalid';
            } else {
                $departamenti = $_POST['departament'];
            }

            if (!isset($_POST['gender'])) {
                $genderErr = '*Gjinia duhet zgjedhur';
            } else {
                $gjinia = testInput($_POST['gender']);
                $genderErr = '';
            }

            if (empty($_POST['email'])) {
                $emailErr = '*Email duhet plotesuar.';
                $invalid_email = 'is-invalid';
            } else {
                $pattern = "^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^";
                $email = testInput($_POST['email']);
                if (!preg_match($pattern, $email)) {
                    $emailErr = '*Email adresa e mesiperme nuk eshte valide.';
                    $invalid_email = 'is-invalid';
                } else {
                    $emailErr = '';
                }
            }


            if (isset($_POST['my_image']) && empty($_POST['my_image'])) {
                $photoErr = '*Duhet te shtoni nje foto te personit ne fjale.';
                $invalid_photo = 'is-invalid';
            } else {
                $photoErr = '';
                $img_name = $_FILES['my_image']['name'];
                $img_size = $_FILES['my_image']['size'];
                $tmp_name = $_FILES['my_image']['tmp_name'];
                $error = $_FILES['my_image']['error'];

                if ($error === 0) {
                    $photoErr = '';
                    if ($img_size > 12500000) {
                        $photo_err = "*Ky file eshte shume i madh.";
                        $invalid_photo = 'is-invalid';
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
                            $photoErr = "*Ky format nuk eshte valid. <br> Formatet e lejuara(jpg, jpeg, png, gif, webp).";
                            $invalid_photo = 'is-invalid';
                        }
                    }
                } else {
                    $photoErr = "*Eshte shfaqur nje gabim i panjohur!";
                    $invalid_photo = 'is-invalid';
                }
            }

            if (empty($_POST['phone'])) {
                $phoneErr = '*Telefoni duhet plotesuar.';
                $invalid_phone = 'is-invalid';
            } else {
                $tel = testInput($_POST['phone']);
                if (!preg_match('/^[0-9]{9}+$/', $tel)) {
                    $phoneErr = '*Numri i telefonit i mesiperm nuk eshte valid.';
                    $invalid_phone = 'is-invalid';
                } else {
                    $phoneErr = '';
                }
            }


            if (empty($_POST['docBio'])) {
                $bioErr = '*Biografia duhet plotesuar.';
                $invalid_bio = 'is-invalid';
            } else {
                $bio = testInput($_POST['docBio']);
                $bioErr = '';
            }

            if (empty($_POST['username'])) {
                $userErr = '*Username duhet plotesuar.';
                $invalid_user = 'is-invalid';
            } else {
                $username = testInput($_POST['username']);
                $userErr = '';
            }

            if (empty($_POST['password'])) {
                $passErr = '*Password duhet plotesuar.';
                $invalid_pass = 'is-invalid';
            } else {
                $password = testInput($_POST['password']);
                $passErr = '';
                $encPass = password_hash($password, PASSWORD_DEFAULT);
            }

            if (
                $fullNameErr == '' && $departamentErr == '' && $genderErr == '' && $emailErr == '' && $photoErr == ''
                && $phoneErr == '' && $bioErr == '' && $userErr == '' && $passErr == ''
            ) {



                $sql = "UPDATE doctor_personal_info SET fullName=:fullName, departamenti=:departamenti, gjinia=:gjinia, email=:email, 
                    biografia=:biografia, foto=:foto, telefoni=:telefoni, username=:username, password=:password WHERE id=:id";
                $stm = $con->prepare($sql);
                $stm->bindParam(':id', $id);
                $stm->bindParam(':fullName', $fullName);
                $stm->bindParam(':departamenti', $departamenti);
                $stm->bindParam(':gjinia', $gjinia);
                $stm->bindParam(':email', $email);
                $stm->bindParam(':biografia', $bio);
                $stm->bindParam(':foto', $new_img_name);
                $stm->bindParam(':telefoni', $tel);
                $stm->bindParam(':username', $username);
                $stm->bindParam(':password', $encPass);
                if($stm->execute()){
                    header("Location: doktoret.php");
                    $name = $lastName = $personalNumber = $gender = $userEmail = $biografia = $phone = $user1 = "";
                }
                

            }
        }

        ?>
        <form method="POST" enctype="multipart/form-data" autocomplete="off">
            <h1 class="h3 mb-3 fw-normal">Editoni nje doktor</h1>

            <div class="form-floating">
                <input type="text" class="form-control <?= $invalid_name ?? "" ?>" id="floatingInput" name="fullName" value="<?= $row['fullName'] ?>" placeholder="Emri i plote">
                <label for="floatingInput">Emri i plote</label>
                <span class="text-danger fw-normal"><?php echo $fullNameErr; ?></span>
            </div>

            <div>
                <select class="form-select mt-2 <?= $invalid_dep ?? "" ?>" aria-label="Default select example" name="departament">
                    <option class="fst-italic fw-bold" selected value="<?= $row['departamenti'] ?>"><?= $row['departamenti'] ?></option>
                    <?php foreach ($data as $data) : ?>
                        <option value="<?= $data['departamenti'] ?>"><?= $data['departamenti'] ?></option>
                    <?php endforeach; ?>
                </select>
                <span class="text-danger fw-normal"><?php echo $departamentErr; ?></span>
            </div>


            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="gender" id="inlineRadio1" value="Mashkull">
                <label class="form-check-label" for="inlineRadio1">Mashkull</label>
            </div>

            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="gender" id="inlineRadio2" value="Femer">
                <label class="form-check-label" for="inlineRadio2">Femer</label>
            </div> <br>
            <span class="text-danger fw-normal"><?php echo $genderErr; ?></span>

            <div class="form-floating">
                <input type="email" class="form-control mt-2 <?= $invalid_email ?? "" ?>" id="floatingInput" name="email" placeholder="name@example.com" value="<?= $row['email'] ?>">
                <label for="floatingInput">Email adresa</label>
                <span class="text-danger fw-normal"><?php echo $emailErr; ?></span>
            </div>

            <div class="mb-3">
                <label for="formFile" class="form-label <?= $invalid_photo ?? "" ?>">Foto</label>
                <input class="form-control" type="file" name="my_image" id="formFile">
                <span class="text-danger fw-normal"><?php echo $photoErr; ?></span>
            </div>

            <div class="form-floating ">
                <input type="tel" class="form-control mt-2 <?= $invalid_phone ?? "" ?>" id="floatingInput" name="phone" placeholder="Telefoni" value="<?= $row['telefoni'] ?>">
                <label for="floatingInput">Telefoni</label>
                <span class="text-danger fw-normal"><?php echo $phoneErr; ?></span>
            </div>

            <div class="mb-3">
                <label for="biografia" class="form-label <?= $invalid_bio ?? "" ?>">Biografia personale</label>
                <textarea class="form-control" id="biografia" rows="4" maxlength="250" name="docBio"><?= $row['biografia'] ?></textarea>
                <span class="text-danger fw-normal"><?php echo $bioErr; ?></span>
            </div>

            <div class="form-floating">
                <input type="text" class="form-control mt-2 <?= $invalid_user ?? "" ?>" id="floatingInput" name="username" placeholder="Username" value="<?= $row['username'] ?>">
                <label for="floatingInput">Username</label>
                <span class="text-danger fw-normal"><?php echo $userErr; ?></span>
            </div>

            <div class="form-floating">
                <input type="password" class="form-control mt-2 <?= $invalid_pass ?? "" ?>" id="floatingPassword" name="password" placeholder="Password">
                <label for="floatingPassword">Password</label>
                <span class="text-danger fw-normal"><?php echo $passErr; ?></span>
            </div>

            <button class="w-100 btn btn-lg btn-primary" type="submit" name="edito">Editoni</button>
        </form>

    </main>




</body>

</html>