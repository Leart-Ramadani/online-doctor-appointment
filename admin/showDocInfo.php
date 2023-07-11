 <?php
include('../config.php');

if(isset($_POST['popShow']) && $_POST['popShow'] == true){
    $id =  $_POST['idShow'];

    $sql = "SELECT u.id, u.fullName, u.gender, u.email, u.photo, u.phone, u.username, u.password, d.name AS
    'dep_name' FROM users AS u INNER JOIN departamentet AS d ON u.departament = d.id 
    WHERE userType=2 AND u.id=:id";
    $prep = $con->prepare($sql);
    $prep->bindParam(':id', $id);
    $prep->execute();
    $data = $prep->fetch();


    echo "<div class='doc_pop_img'>
            <img src='uploads/{$data['photo']}'>
            <div>
                <p class='docPop' style='display: none;'>ID: {$data['id']} </p>
                <h1 class='text-center' style='font-size:28px;'>{$data['fullName']}</h1>
                <p class='ms-2'>Departament: {$data['dep_name']} </p>
                <p class='ms-2'>Email: {$data['email']} </p>
                <p class='ms-2'>Phone: {$data['phone']}</p>
                <p class='ms-2'>Username: {$data['username']}</p>
            </div>

            </div>
            <div class='d-flex justify-content-end'>
                <a href='editUser.php?id=$id' class='text-decoration-none text-white showEditPop'>
                <button class='btn btn-warning  text-white'>Perditeso</button>
                </a>
                <a href='deleteUser.php?id=$id' class='text-decoration-none ms-2 text-white'>
                    <button class='btn btn-danger text-white'>Fshij</button>
                </a>
            </div>
        ";
}