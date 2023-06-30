<?php
include('../config.php');


if (isset($_POST['popAnulo'])) {
    $_SESSION['idAnulo'] = $_POST['idAnulo'];
    $id = $_SESSION['idAnulo'];

    $sql = "SELECT * FROM terminet_e_mia WHERE id=:id";
    $prep = $con->prepare($sql);
    $prep->bindParam(':id', $id);
    $prep->execute();
    $ter_data = $prep->fetch();




    echo $return = "
    <p>Doctor: <span class='doc_name'>{$ter_data['doktori']}</span></p> 
    <hr>
    <p>Departament: <span class='doc_dep'>{$ter_data['departamenti']}</span></p> 
    <hr>
    <p>Appointment date: <span class='app_date'>{$ter_data['data']}</span></p> 
    <hr>
    <p>Time: <span class='app_time'> {$ter_data['ora']} <span></p>
    <hr>";
}

?>
