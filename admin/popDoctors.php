<?php
include('../config.php');

if (isset($_POST['popDoc'])) {
    header("Location: orari.php");
    $_SESSION['idDoc'] = $_POST['idDoc'];
    // $id_doc = $_SESSION[['idDoc']];

    $sql = "SELECT * FROM doctor_personal_info WHERE id=:id";
    $prep = $con->prepare($sql);
    $prep->bindParam(':id', $_SESSION['idDoc']);
    $prep->execute();
    $doc_data = $prep->fetch();

    



    echo $return = "         
        <div class='first'>
            <div class='doc_img'>
                <img src='../photos/Doctors/Liam Smith.webp' alt=''>
            </div>
            <div class='doc_info'>
                <p>Doktori: <span>{$doc_data['fullName']}</span></p>
                <p>Departamenti: <span>{$doc_data['departamenti']}</span></p>
                <p>Gjinia: <span>{$doc_data['gjinia']}</span></p>
                <p>Email: <span>{$doc_data['email']}</span></p>
                <p>Telefoni: <span>{$doc_data['telefoni']}</span></p>
                <p>Username: <span>{$doc_data['username']}</span></p>
            </div>
        </div>

        <div class='action>
            <button class='btn btn-warning text-white w-50 me-5 fs-5'>Edit</button>
            <button class='btn btn-danger text-white w-50 fs-5'>Delete</button>
        </div>";
}
