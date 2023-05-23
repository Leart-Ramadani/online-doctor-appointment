<?php
include('../config.php');
$id = $_GET['id'];
$edit_sql = "SELECT * FROM doctor_personal_info WHERE id=:id";
$edit_prep = $con->prepare($edit_sql);
$edit_prep->bindParam(':id', $id);
$edit_prep->execute();
$editData = $edit_prep->fetch();

if ($editData['gjinia'] == 'Mashkull') {
    $maleGender = 'checked';
} else if ($editData['gjinia'] == 'Femer') {
    $femGender = 'checked';
}

$dep_sql = "SELECT * FROM departamentet";
$dep_prep = $con->prepare($dep_sql);
$dep_prep->execute();
$depData = $dep_prep->fetchAll();
?>
<?php include('./header.php'); ?>
<title>Perditeso</title>

</head>

<body style="background: #f5f5f5;">


    <main>

        <?php
        $fullNameErr = $departamentErr = $genderErr = $emailErr = $photoErr = $phoneErr = $bioErr  = $userErr = $passErr = "";

        if (isset($_POST['update'])) {

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
                if ($stm->execute()) {
                    header("Location: doktoret.php");
                    $name = $lastName = $personalNumber = $gender = $userEmail = $biografia = $phone = $user1 = "";
                }
            }
        }

        ?>


        <a href="./doktoret.php" class="backDoc" title="Go back"><i class="fa-solid fa-arrow-left"></i></a>
        <article class="popDocEdit rounded" id="popDocEdit">
            <h3 class="text-center">Perditeso llogarin</h3>
            <div class="editForm_wrapper">
                <form class="editForm" autocomplete="off" method="POST" enctype="multipart/form-data">
                    <div class="d-flex">
                        <div class="form-floating">
                            <input type="text" class="form-control <?= $invalid_name ?? "" ?>" id="floatingInput" name="fullName" placeholder="Emri i plote" value="<?= $editData['fullName']; ?>">
                            <label for="floatingInput">Emri i plote</label>
                            <span class="text-danger fw-normal"><?php echo $fullNameErr; ?></span>
                        </div>
                        <div>
                            <select class="form-select <?= $invalid_dep ?? "" ?> " aria-label="Default select example" name="departament">
                                <option selected value="<?= $editData['departamenti']; ?>"><?= $editData['departamenti']; ?></option>
                                <?php foreach ($depData as $depData) : ?>
                                    <option value="<?= $depData['departamenti']; ?>"><?= $depData['departamenti']; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <span class="text-danger fw-normal"><?php echo $departamentErr; ?></span>
                        </div>
                    </div>
                    <div>
                        <div>
                            <div class="d-flex editGender">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="gender" id="inlineRadio1" value="Mashkull" <?= $maleGender ?? "" ?>>
                                    <label class="form-check-label" for="inlineRadio1">Mashkull</label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="gender" id="inlineRadio2" value="Femer" <?= $femGender ?? "" ?>>
                                    <label class="form-check-label" for="inlineRadio2">Femer</label>
                                </div> <br>
                            </div>
                            <span class="text-danger fw-normal"><?php echo $genderErr; ?></span>
                        </div>

                        <div class="form-floating">
                            <input type="email" class="form-control rounded <?= $invalid_email ?? "" ?>" id="floatingInput" name="email" placeholder="name@example.com" value="<?= $editData['email'] ?>">
                            <label for="floatingInput">Email</label>
                            <span class="text-danger fw-normal"><?php echo $emailErr; ?></span>
                        </div>
                    </div>

                    <div class="d-flex">

                        <div class="mb-3">
                            <label for="formFile" class="form-label">Foto</label>
                            <input class="form-control <?= $invalid_photo ?? "" ?>" type="file" name="my_image" id="formFile">
                            <span class="text-danger fw-normal"><?php echo $photoErr;?></span>
                        </div>

                        <div class="form-floating mt-3">
                            <input type="tel" class="form-control <?= $invalid_phone ?? "" ?>" id="floatingInput" name="phone" placeholder="Numri i telefonit" value="<?= $editData['telefoni']; ?>">
                            <label for="floatingInput">Numri i telefonit</label>
                            <span class="text-danger fw-normal"><?php echo $phoneErr; ?></span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="biografia" class="form-label">Biografia</label>
                        <textarea class="form-control <?= $invalid_bio ?? "" ?>" id="biografia" rows="4" maxlength="250" name="docBio"><?= $editData['biografia']; ?></textarea>
                        <span class="text-danger fw-normal"><?php echo $bioErr; ?></span>
                    </div>

                    <div class="d-flex">

                        <div class="form-floating">
                            <input type="text" class="form-control <?= $invalid_user ?? "" ?>" id="floatingInput" name="username" placeholder="Username" value="<?= $editData['username']; ?>">
                            <label for="floatingInput">Username</label>
                            <span class="text-danger fw-normal"><?php echo $userErr; ?></span>
                        </div>

                        <div class="form-floating">
                            <input type="password" class="form-control <?= $invalid_pass ?? "" ?>" id="floatingPassword" name="password" placeholder="Fjalkalimi">
                            <label for="floatingPassword">Fjalkalimi</label>
                            <span class="text-danger fw-normal"><?php echo $passErr; ?></span>
                        </div>
                    </div>
                    <button class="w-100 btn btn-lg btn-primary mt-1" type="submit" name="update">Perditeso</button>
                </form>
            </div>
        </article>

    </main>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous" defer></script>
    <script src="../bootstrap-5.1.3-examples/sidebars/sidebars.js" defer></script>

</body>

</html>