 <?php
include('../config.php');

if(isset($_POST['popShow']) && $_POST['popShow'] == true){
    $_SESSION['idShow'] = $_POST['idShow'];
    $id = $_SESSION['idShow'];

    $sql = "SELECT * FROM doctor_personal_info WHERE id=:id";
    $prep = $con->prepare($sql);
    $prep->bindParam(':id', $id);
    $prep->execute();
    $data = $prep->fetch();


    echo "<div class='doc_pop_img'>
            <img src='uploads/{$data['foto']}'>
            <div>
                <h1 class='text-center' style='font-size:28px;'>{$data['fullName']}</h1>
                <p class='ms-2'>Departament: {$data['departamenti']} </p>
                <p class='ms-2'>Email: {$data['email']} </p>
                <p class='ms-2'>Phone: {$data['telefoni']}</p>
                <p class='ms-2'>Username: {$data['username']}</p>
            </div>
        </div>
        ";
}