<?php
include '../config.php';
if (isset($_POST['diagnose'])) {

    $id = $_POST['id'];
    $service = $_POST['service'];
    $diagnose = $_POST['diagnose'];
    $prescription = $_POST['prescription'];

    if (empty($service)) {
        $serviceErr = '*Service must be selected';
    } else {
        $serviceErr = '';

        $service_sql = "SELECT * FROM prices WHERE name=:service";
        $service_stmt = $con->prepare($service_sql);
        $service_stmt->bindParam(':service', $service);
        $service_stmt->execute();
        $service_row = $service_stmt->fetch();
        $invalid_service = '';
    }

    if (empty($diagnose)) {
        $diagnoseErr = '*Diagnose must be filled.';
    } else {
        $diagnoseErr = '';

        $diag_sql = "SELECT id FROM icd_code WHERE code='$diagnose'";
        $diag_prep = $con->prepare($diag_sql);
        $diag_prep->execute();
        $diag_data = $diag_prep->fetch();
        $diag_id = $diag_data['id'];
    }

    if (empty($prescription)) {
        $prescriptionErr = '*Prescription must be filled.';
    } else {
        $prescriptionErr = '';
    }

    $errors = [$serviceErr, $prescriptionErr, $diagnoseErr];


    if ($diagnoseErr == '' && $prescriptionErr == '' && $serviceErr == '') {

        $ins_sql = "UPDATE terminet SET statusi='Completed', diagnoza=:diagnoza, recepti=:recepti, service=:service WHERE id='$id'";
        $ins_prep = $con->prepare($ins_sql);
        $ins_prep->bindParam(':diagnoza', $diag_id);
        $ins_prep->bindParam(':recepti', $prescription);
        $ins_prep->bindParam(':service', $service_row['id']);
        $ins_prep->execute();


        $delWait = "DELETE FROM waiting_list WHERE apointment_id='$id'";
        $del_prep = $con->prepare($delWait);
        $del_prep->execute();

        echo json_encode($errors);
    } else{
        echo json_encode($errors);
    }
}



if(isset($_POST['departament'])){
    $id = $_POST['id'];
    $service = $_POST['service'];
    $departament = $_POST['departament'];
    $prescription = $_POST['prescription'];

    if (empty($service)) {
        $serviceErr = '*Service must be selected';
    } else {
        $serviceErr = '';

        $service_sql = "SELECT * FROM prices WHERE name=:service";
        $service_stmt = $con->prepare($service_sql);
        $service_stmt->bindParam(':service', $service);
        $service_stmt->execute();
        $service_row = $service_stmt->fetch();
        $invalid_service = '';
    }

    if(empty($departament)){
        $departamentErr = '*Departament must be selected';
    } else{
        $depSql = "SELECT id FROM departamentet WHERE name=:departament";
        $depPrep = $con->prepare($depSql);
        $depPrep->bindParam(':departament', $departament);
        $depPrep->execute();
        $depData = $depPrep->fetch();

        $depId = $depData['id'];

        $departamentErr = '';
    }

    if (empty($prescription)) {
        $prescriptionErr = '*Prescription must be filled.';
    } else {
        $prescriptionErr = '';
    } 

    $diag_id = 0;
    $errors = [$serviceErr, $prescriptionErr, $departamentErr];

    if ($departamentErr == '' && $prescriptionErr == '' && $serviceErr == '') {
        $sql = "INSERT INTO reference(appointment_id, to_departament, status) VALUES(:appointment_id, :to_departament, 'Not transfered')";
        $prep = $con->prepare($sql);
        $prep->bindParam(':appointment_id', $id);
        $prep->bindParam(':to_departament', $depId);
        $prep->execute();

        $update_sql = "UPDATE terminet SET statusi='Transfered', diagnoza=:diagnoza, recepti=:prescription, service=:service WHERE id='$id'";
        $update_prep = $con->prepare($update_sql);
        $update_prep->bindParam(':diagnoza', $diag_id);
        $update_prep->bindParam(':prescription', $prescription);
        $update_prep->bindParam(':service', $service_row['id']);
        $update_prep->execute();

        $delWait = "DELETE FROM waiting_list WHERE apointment_id='$id'";
        $del_prep = $con->prepare($delWait);
        $del_prep->execute();

        echo json_encode($errors);
    } else{
        echo json_encode($errors);
    }

}