<?php
    include('../config.php');

    $id = $_GET['id'];

    $sel_sql = "SELECT * FROM terminet WHERE id=:id";
    $sel_prep = $con->prepare($sel_sql);
    $sel_prep->bindParam(':id', $id);
    $sel_prep->execute();
    $sel_data = $sel_prep->fetch();

    $sql = "DELETE FROM terminet WHERE id=:id";
    $prep = $con->prepare($sql);
    $prep->bindParam(':id', $id);

    if($prep->execute()){
        $del_sql = "DELETE FROM terminet_e_mia WHERE numri_personal=:numri_personal AND doktori=:doktori AND data=:data AND ora=:ora";
        $del_prep = $con->prepare($del_sql);
        $del_prep->bindParam(':numri_personal', $sel_data['numri_personal']);
        $del_prep->bindParam(':doktori', $sel_data['doktori']);
        $del_prep->bindParam(':data', $sel_data['data']);
        $del_prep->bindParam(':ora', $sel_data['ora']);
        $del_prep->execute();
        echo "<script>
                alert('Termini eshte fshire me sukses.');
                window.location.replace('terminet.php');
            </script>";
    }

?>