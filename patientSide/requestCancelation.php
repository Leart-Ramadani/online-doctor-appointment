<?php
include('../config.php');


if (isset($_POST['popAnulo'])) {
    $_SESSION['idAnulo'] = $_POST['idAnulo'];
    $id = $_SESSION['idAnulo'];

    $sql = "SELECT t.id, t.doktori, t.departamenti, t.pacienti, t.numri_personal, t.email_pacientit, t.data, t.ora, t.statusi, d.name AS 'dep_name' FROM terminet AS t 
        INNER JOIN departamentet AS d ON t.departamenti=d.id WHERE t.id=:id AND statusi='Booked'";
    $prep = $con->prepare($sql);
    $prep->bindParam(':id', $id);
    $prep->execute();
    $ter_data = $prep->fetch();




    echo $return = "
    <p>Doctor: <span class='doc_name'>{$ter_data['doktori']}</span></p> 
    <hr>
    <p>Departament: <span class='doc_dep'>{$ter_data['dep_name']}</span></p> 
    <hr>
    <p>Appointment date: <span class='app_date'>{$ter_data['data']}</span></p> 
    <hr>
    <p>Time: <span class='app_time'> {$ter_data['ora']} <span></p>
    <hr>";
}

?>
