<?php
include('../config.php');
$id = $_GET['id'];
$edit_sql = "SELECT u.id, u.fullName, u.gender, u.email, u.phone, u.photo, u.username, u.password, d.name AS 'dep_name' 
    FROM users AS u INNER JOIN departamentet AS d ON u.departament = d.id WHERE userType=2 AND u.id=:id";
$edit_prep = $con->prepare($edit_sql);
$edit_prep->bindParam(':id', $id);
$edit_prep->execute();
$editData = $edit_prep->fetch();

if ($editData['gender'] == 'Male') {
    $maleGender = 'selected';
} else if ($editData['gender'] == 'Female') {
    $femGender = 'selected';
}

$dep_sql = "SELECT * FROM departamentet";
$dep_prep = $con->prepare($dep_sql);
$dep_prep->execute();
$depData = $dep_prep->fetchAll();
?>
<?php include('./header.php'); ?>
<title>Update</title>

</head>

<body style="background: #f5f5f5;">


    <?php
    $fullNameErr = $departamentErr = $genderErr = $emailErr = $photoErr = $phoneErr = $userErr = $passErr = "";

    if (isset($_POST['update'])) {

        function testInput($data)
        {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }


        if (empty($_POST['fullName'])) {
            $fullNameErr = '*Full name must be filled.';
            $invalid_surname = 'is-invalid';
        } else {
            $fullName = testInput($_POST['fullName']);
            if (!preg_match("/^[a-z A-z]*$/", $fullName)) {
                $fullNameErr = '*Only alphabetical letters are allowed.';
                $invalid_fullName = 'is-invalid';
            } else {
                $fullNameErr = '';
            }
        }

        if (empty($_POST['departament'])) {
            $departamentErr = '*You must select your departament.';
            $invalid_dep = 'is-invalid';
        } else {
            $departamenti = $_POST['departament'];
        }

        if (empty($_POST['gender'])) {
            $genderErr = '*Gender must be selected.';
            $gender_invalid = 'is-invalid';
        } else {
            $gjinia = testInput($_POST['gender']);
            $genderErr = '';
        }

        if (empty($_POST['email'])) {
            $emailErr = '*Email must be filled.';
            $invalid_email = 'is-invalid';
        } else {
            $pattern = "^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^";
            $email = testInput($_POST['email']);
            if (!preg_match($pattern, $email)) {
                $emailErr = '*This emmail is not valid.';
                $invalid_email = 'is-invalid';
            } else {
                $emailErr = '';
            }
        }


        if (isset($_POST['my_image']) && empty($_POST['my_image'])) {
            $photoErr = '*You must select the picture of this doctor.';
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
                    $photo_err = "*This file is to big.";
                    $invalid_photo = 'is-invalid';
                } else {
                    $photoErr = '';
                    $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
                    $img_ex_lc = strtolower($img_ex);

                    $allowed_exs = array("jpg", "jpeg", "png", "gif", "webp", "jfif");

                    if (in_array($img_ex_lc, $allowed_exs)) {
                        $new_img_name = uniqid("IMG-", true) . '.' . $img_ex_lc;
                        $img_upload_path = 'uploads/' . $new_img_name;
                        move_uploaded_file($tmp_name, $img_upload_path);
                    } else {
                        $photoErr = "*This is invalid format. <br> Allowed formats(jpg, jpeg, png, gif, webp).";
                        $invalid_photo = 'is-invalid';
                    }
                }
            } else {
                $photoErr = "*Unkown error!";
                $invalid_photo = 'is-invalid';
            }
        }

        if (empty($_POST['phone'])) {
            $phoneErr = '*Phone must be filled.';
            $invalid_phone = 'is-invalid';
        } else {
            $tel = testInput($_POST['phone']);
            if (!preg_match('/^[0-9]{9}+$/', $tel)) {
                $phoneErr = '*This phone number is invalid.';
                $invalid_phone = 'is-invalid';
            } else {
                $phoneErr = '';
            }
        }



        if (empty($_POST['username'])) {
            $userErr = '*Username must be filled.';
            $invalid_user = 'is-invalid';
        } else {
            $username = testInput($_POST['username']);
            $userErr = '';
        }

        if (empty($_POST['password'])) {
            $passErr = '*Password must be filled.';
            $invalid_pass = 'is-invalid';
        } else {
            $password = testInput($_POST['password']);
            $passErr = '';
            $encPass = password_hash($password, PASSWORD_DEFAULT);
        }

        if (
            $fullNameErr == '' && $departamentErr == '' && $genderErr == '' && $emailErr == '' && $photoErr == ''
            && $phoneErr == '' && $userErr == '' && $passErr == ''
        ) {


            $dep_sql = "SELECT id FROM departamentet WHERE name=:departament";
            $dep_prep = $con->prepare($dep_sql);
            $dep_prep->bindParam(':departament', $departamenti);
            $dep_prep->execute();
            $dep_data = $dep_prep->fetch();

            $sql = "UPDATE users SET fullName=:fullName, departament=:departament, gender=:gender, email=:email, 
                    photo=:photo, phone=:phone, username=:username, password=:password WHERE id=:id AND userType=2";
            $stm = $con->prepare($sql);
            $stm->bindParam(':id', $id);
            $stm->bindParam(':fullName', $fullName);
            $stm->bindParam(':departament', $dep_data['id']);
            $stm->bindParam(':gender', $gjinia);
            $stm->bindParam(':email', $email);
            $stm->bindParam(':photo', $new_img_name);
            $stm->bindParam(':phone', $tel);
            $stm->bindParam(':username', $username);
            $stm->bindParam(':password', $encPass);
            if ($stm->execute()) {
                header("Location: doktoret.php");
                $name = $lastName = $personalNumber = $gender = $userEmail = $phone = $user1 = "";
            }
        }
    }

    ?>

<a href="./doktoret.php" class="backDoc h4 ms-3" title="Go back"><i class="fa-solid fa-arrow-left"></i></a>
    <main class="d-flex justify-content-center">
        <article class="popDocEdit rounded" id="popDocEdit">
            <h3 class="text-center">Update account</h3>
            <div class="editForm_wrapper">
                <form class="editForm" autocomplete="off" method="POST" enctype="multipart/form-data">
                    <div class="d-flex">
                        <div class="form-floating">
                            <input type="text" class="form-control <?= $invalid_name ?? "" ?>" id="floatingInput" name="fullName" placeholder="Full name" value="<?= $editData['fullName']; ?>">
                            <label for="floatingInput">Full name</label>
                            <span class="text-danger fw-normal"><?php echo $fullNameErr; ?></span>
                        </div>
                        <div>
                            <select class="form-select <?= $invalid_dep ?? "" ?> " aria-label="Default select example" name="departament" style="height: 58px;">
                                <option selected value="<?= $editData['dep_name']; ?>"><?= $editData['dep_name']; ?></option>
                                <?php foreach ($depData as $depData) : ?>
                                    <option value="<?= $depData['name']; ?>"><?= $depData['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <span class="text-danger fw-normal"><?php echo $departamentErr; ?></span>
                        </div>
                    </div>
                    <div>
                        <select class="form-select <?= $gender_invalid ?? "" ?>" name="gender" style="height: 58px;">
                            <option value="">Select your gender</option>
                            <option value="Male" <?= $maleGender ?? "" ?>>Male</option>
                            <option value="Female" <?= $femGender ?? "" ?>>Female</option>
                        </select>
                        <span class="text-danger fw-normal"><?php echo $genderErr; ?></span>

                        <div class="form-floating">
                            <input type="email" class="form-control rounded <?= $invalid_email ?? "" ?>" id="floatingInput" name="email" placeholder="name@example.com" value="<?= $editData['email'] ?>">
                            <label for="floatingInput">Email</label>
                            <span class="text-danger fw-normal"><?php echo $emailErr; ?></span>
                        </div>
                    </div>

                    <div class="d-flex">

                        <div class="mb-3 ms-0 me-2">
                            <label for="formFile" class="form-label">Photo</label>
                            <input class="form-control <?= $invalid_photo ?? "" ?>" type="file" name="my_image" id="formFile">
                            <span class="text-danger fw-normal"><?php echo $photoErr; ?></span>
                        </div>

                        <div class="form-floating mt-3">
                            <input type="tel" class="form-control <?= $invalid_phone ?? "" ?>" id="floatingInput" name="phone" placeholder="Phone number" value="<?= $editData['phone']; ?>">
                            <label for="floatingInput">Phone number</label>
                            <span class="text-danger fw-normal"><?php echo $phoneErr; ?></span>
                        </div>
                    </div>

                    <div class="d-flex">
                        <div class="form-floating">
                            <input type="text" class="form-control <?= $invalid_user ?? "" ?>" id="floatingInput" name="username" placeholder="Username" value="<?= $editData['username']; ?>">
                            <label for="floatingInput">Username</label>
                            <span class="text-danger fw-normal"><?php echo $userErr; ?></span>
                        </div>

                        <div class="form-floating">
                            <input type="password" class="form-control <?= $invalid_pass ?? "" ?>" id="floatingPassword" name="password" placeholder="Password">
                            <label for="floatingPassword">Password</label>
                            <span class="text-danger fw-normal"><?php echo $passErr; ?></span>
                        </div>
                    </div>
                    <button class="w-100 btn btn-lg btn-primary mt-1" type="submit" name="update">Update</button>
                </form>
            </div>
        </article>

    </main>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous" defer></script>
    <script src="../bootstrap-5.1.3-examples/sidebars/sidebars.js" defer></script>

</body>

</html>