<?php
    include '../config.php';

    if(isset($_POST['action']) && $_POST['action'] == 'getAppointmentsData'){
        $booked_sql = "SELECT COUNT(*) as totalBooked FROM terminet WHERE statusi='Booked'";
        $booked_prep = $con->prepare($booked_sql);
        $booked_prep->execute();
        $booked_data = $booked_prep->fetch(PDO::FETCH_ASSOC);

        $completed_sql = "SELECT COUNT(*) as totalCompleted FROM terminet WHERE statusi='Completed'";
        $completed_prep = $con->prepare($completed_sql);
        $completed_prep->execute();
        $completed_data = $completed_prep->fetch(PDO::FETCH_ASSOC);
        
        $transfered_sql = "SELECT COUNT(*) as totalTransfered FROM terminet WHERE statusi='Transfered'";
        $transfered_prep = $con->prepare($transfered_sql);
        $transfered_prep->execute();
        $transfered_data = $transfered_prep->fetch(PDO::FETCH_ASSOC);

        $progres_sql = "SELECT COUNT(*) as totalInProgres FROM terminet WHERE statusi='In progres'";
        $progres_prep = $con->prepare($progres_sql);
        $progres_prep->execute();
        $progres_data = $progres_prep->fetch(PDO::FETCH_ASSOC);

        $canceled_sql = "SELECT COUNT(*) as totalCanceled FROM terminet WHERE statusi='Canceled'";
        $canceled_prep = $con->prepare($canceled_sql);
        $canceled_prep->execute();
        $canceled_data = $canceled_prep->fetch(PDO::FETCH_ASSOC);

        $response = [
            "Booked" => $booked_data['totalBooked'],
            "Completed" => $completed_data['totalCompleted'],
            "Transfered" => $transfered_data['totalTransfered'],
            "Progres" => $progres_data['totalInProgres'],
            "Canceled" => $canceled_data['totalCanceled']
        ];

        echo json_encode($response);
    }


    if(isset($_POST['action']) && $_POST['action'] == 'getDocWork'){
        $id = $_POST['id'];

        $doc_sql = "SELECT fullName, departament FROM users WHERE userType=2 AND id=:id";
        $doc_prep = $con->prepare($doc_sql);
        $doc_prep->bindParam(':id', $id, PDO::PARAM_INT);
        $doc_prep->execute();

        $doc_data = $doc_prep->fetch(PDO::FETCH_ASSOC);


        $work_sql = "SELECT * FROM terminet WHERE doktori=:doctor AND departamenti=:departament AND ( statusi='Completed' OR statusi='Transfered')";
        $work_prep = $con->prepare($work_sql);
        $work_prep->bindParam(':doctor', $doc_data['fullName']);
        $work_prep->bindParam(':departament', $doc_data['departament']);
        $work_prep->execute();
        
        $doc_work = $work_prep->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($doc_work);
    }


?>