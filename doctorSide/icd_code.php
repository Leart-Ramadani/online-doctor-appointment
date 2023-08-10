<?php
    include '../config.php';

    if(isset($_POST['filter'])){
        $filter = $_POST['filter'];

        $sql = "SELECT code FROM icd_code WHERE code LIKE '%$filter%'";
        $prep = $con->prepare($sql);
        $prep->execute();
        $data = $prep->fetchAll();

        $code = [];
        if($data){
            foreach($data as $data){
                $code[] = $data['code'];
            }
            echo json_encode($code);
        } else{
            echo json_encode("not found");
        }
    }
?>