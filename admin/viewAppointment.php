<?php
    include('../config.php');

    if(isset($_GET['App_id'])){
        $id = $_GET['App_id'];

        $sql = "SELECT
        t.id,
        t.doktori,
        t.pacienti,
        t.numri_personal,
        t.email_pacientit,
        t.data,
        t.ora,
        t.statusi,
        t.diagnoza,
        t.recepti,
        t.paied,
        d.name AS 'dep_name',
        p.price AS 'price',
        p2.name AS 'service'
    FROM
        terminet AS t
    INNER JOIN departamentet AS d ON t.departamenti = d.id
    INNER JOIN prices AS p ON t.service = p.id
    INNER JOIN prices AS p2 ON t.service = p2.id
    WHERE
        t.id = :id;";


$prep = $con->prepare($sql);
$prep->bindParam(':id', $id);
$prep->execute();
$data = $prep->fetch();

    // Data found, proceed with processing and returning JSON
    $time = date_create($data['ora']);
    $time = date_format($time, "H:i");

    $date = date_create($data['data']);
    $date = date_format($date, "d/m/Y");

    $response = [
        "ID" => $data['id'],
        "Doctor" => $data['doktori'],
        "Departament" => $data['dep_name'],
        "Patient" => $data['pacienti'],
        "Personal_ID" => $data['numri_personal'],
        "Date" => $date,
        "Time" => $time,
        "Service" => $data['service'],
        "Price" => $data['price'],
        "Diagnose" => $data['diagnoza'],
        "Prescription" => $data['recepti']
    ];

    echo json_encode($response);
    }
