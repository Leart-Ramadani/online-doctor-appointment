<?php
include('../config.php');
require_once('../emailData.php');
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
}

?>

<?php include('header.php') ?>
<title>Orari</title>
</head>

<body>
    <div class="sideBlock">
        <button type="button" class="ham" id="ham_menu"><i class="fa-solid fa-bars"></i></button>
    </div>
    <div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark sidebar">
        <button type="button" class="close_side"><i class="fa-solid fa-close"></i></button>
        <p class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
            <span class="fs-4"><?php echo $_SESSION['admin'] ?></span>
        </p>
        <hr>
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item"><a href="doktoret.php" class="nav-link text-white" aria-current="page">Doktoret</a></li>
            <li><a href="departamentet.php" class="nav-link text-white ">Departamentet</a></li>
            <li><a href="orari.php" class="nav-link text-white active">Orari</a></li>
            <li><a href="terminet.php" class="nav-link text-white">Terminet</a></li>
            <li><a href="pacientat.php"" class=" nav-link text-white">Pacientat</a></li>
            <li><a href="historiaTerminit.php" class="nav-link text-white">Historia termineve</a></li>
            <li class="nav-item"><a href="galeria.php" class="nav-link text-white">Galeria</a></li>
            <li><a href="ankesat.php" class="nav-link text-white">Ankesat</a></li>
            <li><a href="kerkesatAnulimit.php" class="nav-link text-white">Kerkesat per anulim</a></li>
        </ul>
        <hr>
        <div class="dropdown">
            <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="https://w7.pngwing.com/pngs/200/420/png-transparent-user-profile-computer-icons-overview-rectangle-black-data-thumbnail.png" alt="" width="32" height="32" class="rounded-circle me-2">
                <strong><?php echo $_SESSION['admin'] ?></strong>
            </a>
            <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
                <li><a class="dropdown-item" href="logout.php">Sign out</a></li>
            </ul>
        </div>
    </div>

    <?php
    $doc_sql = "SELECT fullName FROM users WHERE userType=2";
    $doc_prep = $con->prepare($doc_sql);
    $doc_prep->execute();
    $doc_data = $doc_prep->fetchAll();
    ?>

    <main class="main mainRes">

        <?php
        require("../patientSide/PHPMailer-master/src/Exception.php");
        require("../patientSide/PHPMailer-master/src/PHPMailer.php");
        require("../patientSide/PHPMailer-master/src/SMTP.php");;
        //Import PHPMailer classes into the global namespace
        //These must be at the top of your script, not inside a function

        use PHPMailer\PHPMailer\PHPMailer;
        use PHPMailer\PHPMailer\SMTP;
        use PHPMailer\PHPMailer\Exception;


        $doktorErr = $dateErr = $from_time_err = $to_time_err = $durationErr = "";
        $s_date = $from_time = $to_time = "";
        if (isset($_POST['submit'])) {

            if (empty($_POST['doktori'])) {
                $doktorErr = '*Doktori duhet zgjedhur.';
                $invalid_doc = 'is-invalid';
            } else {
                $doktori = $_POST['doktori'];
                $doktorErr = '';
            }





            if (empty($_POST['data'])) {
                $dateErr = '*Data duhet zgjedhur.';
                $invalid_date = 'is-invalid';
            } else {
                $data = $_POST['data'];
                $date2 = date("Y-m-d");

                $dateTimestamp1 = strtotime($data);
                $dateTimestamp2 = strtotime($date2);

                if ($dateTimestamp1 <= $dateTimestamp2) {
                    $dateErr = "*Data nuk mund te jete me heret apo e njejet me daten e sotme.";
                    $invalid_date = 'is-invalid';
                } else {
                    $data = $_POST['data'];
                    $dateErr = '';
                    $s_date = $data;
                }
            }

            if (empty($_POST['from_time'])) {
                $from_time_err = '*Ju duhet te zgjedhni oren kur mjeku fillon punen.';
                $invalid_from_time = 'is-invalid';
            } else {
                $nga_ora = $_POST['from_time'];
                if ($nga_ora < '08:00') {
                    $from_time_err = "*Ora nuk duhet te jete me heret se 08:00";
                    $invalid_from_time = 'is-invalid';
                } else {
                    if ($nga_ora > '14:00') {
                        $from_time_err = "*Ora nuk mund te jete me vone se 14:00";
                        $invalid_from_time = 'is-invalid';
                    } else {
                        $nga_ora = $_POST['from_time'];
                        $from_time_err = '';
                        $from_time = $nga_ora;
                    }
                }
            }

            if (empty($_POST['to_time'])) {
                $to_time_err = '*Ju duhet te zgjedhni oren kur mjeku perfundon punen.';
                $invalid_to_time = 'is-invalid';
            } else {
                $deri_oren = $_POST['to_time'];
                if ($deri_oren < '14:00') {
                    $to_time_err = "*Ora nuk mund te jete me heret se 14:00";
                    $invalid_to_time = 'is-invalid';
                } else {
                    if ($deri_oren > '18:00') {
                        $to_time_err = "*Ora nuk mund te jete me vone se 18:00";
                        $invalid_to_time = 'is-invalid';
                    } else {
                        $deri_oren = $_POST['to_time'];
                        $to_time_err = '';
                        $to_time = $deri_oren;
                    }
                }
            }

            if (empty($_POST['kohzgjatja'])) {
                $durationErr = '*Ju duhet te zgjedhni kohezgjatjen e terminit';
                $invalid_duration = 'is-invalid';
            } else {
                $kohzgjatja = $_POST['kohzgjatja'];
                $durationErr = '';
            }


            if ($doktorErr == '' && $dateErr == '' && $from_time_err == '' && $to_time_err == '' && $durationErr == '') {
                $dep_sql = "SELECT fullName, departament FROM users WHERE userType=2 AND fullName=:fullName";
                $prep = $con->prepare($dep_sql);
                $prep->bindParam(':fullName', $doktori);
                $prep->execute();
                $dep_fetch = $prep->fetch();

                $departamenti = $dep_fetch['departament'];

                $sql = "INSERT INTO orari(doktori, departamenti, data, nga_ora, deri_oren, kohezgjatja, zene_deri) 
                VALUES(:doktori, :departamenti, :data, :nga_ora, :deri_oren, :kohezgjatja, :zene_deri)";
                $stm = $con->prepare($sql);
                $stm->bindParam(':doktori', $doktori);
                $stm->bindParam(':departamenti', $departamenti);
                $stm->bindParam(':data', $data);
                $stm->bindParam(':nga_ora', $nga_ora);
                $stm->bindParam(':deri_oren', $deri_oren);
                $stm->bindParam(':kohezgjatja', $kohzgjatja);
                $stm->bindParam(':zene_deri', $nga_ora);
                if ($stm->execute()) {
                    $s_date = $from_time = $to_time = "";

                    $check_appointments_sql = "SELECT * FROM terminet_e_dyta WHERE doktori=:doktori AND data=:data";
                    $check_appointments_prep = $con->prepare($check_appointments_sql);
                    $check_appointments_prep->bindParam(':doktori', $doktori);
                    $check_appointments_prep->bindParam(':data', $data);
                    $check_appointments_prep->execute();
                    $check_appointments_data = $check_appointments_prep->fetchAll();


                    if ($check_appointments_data) {

                        foreach ($check_appointments_data as $check_appointments_data) {
                            $data10 = $check_appointments_data['data'];
                            $doktori10 = $check_appointments_data['doktori'];
                            $emriPacientit10 = $check_appointments_data['emri_pacientit'];
                            $mbiemriPacientit10 = $check_appointments_data['mbiemri_pacientit'];
                            $numriPersonal10 = $check_appointments_data['numri_personal'];
                            $emailPacientit10 = $check_appointments_data['email_pacientit'];

                            $orari_sql = "SELECT * FROM orari WHERE doktori=:doktori AND data=:data";
                            $orari_prep = $con->prepare($orari_sql);
                            $orari_prep->bindParam(':doktori', $check_appointments_data['doktori']);
                            $orari_prep->bindParam(':data', $check_appointments_data['data']);
                            $orari_prep->execute();
                            $orari_data = $orari_prep->fetch();
                            $zene_deri = $orari_data['zene_deri'];


                            $terminet_sql = "INSERT INTO terminet(doktori, fullName, numri_personal, email_pacientit, data, ora)
                                            VALUES(:doktori, :fullName :numri_personal, :email_pacientit, :data, :ora)";
                            $terminet_prep = $con->prepare($terminet_sql);
                            $terminet_prep->bindParam(':doktori', $doktori10);
                            $terminet_prep->bindParam(':fullName', $emriPacientit10);
                            $terminet_prep->bindParam(':numri_personal', $numriPersonal10);
                            $terminet_prep->bindParam(':email_pacientit', $emailPacientit10);
                            $terminet_prep->bindParam(':data', $data10);
                            $terminet_prep->bindParam(':ora', $zene_deri);

                            $terminet_prep->execute();
                            $time1 = $orari_data['zene_deri'];
                            $interval2 = $orari_data['kohezgjatja'];
                            $date = new DateTime($time1);
                            $date->add(new DateInterval("PT0H{$interval2}M0S"));
                            $date_format = $date->format("H:i:s");


                            $update_orari = "UPDATE orari SET zene_deri=:zene_deri WHERE doktori=:doktori AND data=:data";
                            $update_prep = $con->prepare($update_orari);
                            $update_prep->bindParam(':doktori', $doktori10);
                            $update_prep->bindParam(':data', $data10);
                            $update_prep->bindParam(':zene_deri', $date_format);
                            $update_prep->execute();


                            $ter_sql = "INSERT INTO terminet_e_mia(emri_pacientit, mbiemri_pacientit, numri_personal, doktori, departamenti, data, ora)
                                            VALUES(:emri_pacientit, :mbiemri_pacientit, :numri_personal, :dok, :dep, :date, :ora)";
                            $ter_prep = $con->prepare($ter_sql);
                            $ter_prep->bindParam(':emri_pacientit', $emriPacientit10);
                            $ter_prep->bindParam(':mbiemri_pacientit', $mbiemriPacientit10);
                            $ter_prep->bindParam(':numri_personal', $numriPersonal10);
                            $ter_prep->bindParam(':dok', $doktori10);
                            $ter_prep->bindParam(':dep', $departamenti);
                            $ter_prep->bindParam(':date', $data10);
                            $ter_prep->bindParam(':ora', $zene_deri);
                            $ter_prep->execute();


                            $del_terminet_dyta = "DELETE FROM terminet_e_dyta WHERE doktori=:doktori AND numri_personal=:numri_personal AND data=:data";
                            $del_terminet_dyta_prep = $con->prepare($del_terminet_dyta);
                            $del_terminet_dyta_prep->bindParam(':doktori', $doktori10);
                            $del_terminet_dyta_prep->bindParam(':numri_personal', $numriPersonal10);
                            $del_terminet_dyta_prep->bindParam(':data', $data10);
                            if ($del_terminet_dyta_prep->execute()) {

                                $gender_sql = "SELECT gjinia FROM users WHERE userType=2 AND personal_id=:personal_id";
                                $gender_prep = $con->prepare($gender_sql);
                                $gender_prep->bindParam(':personal_id', $numriPersonal10);
                                $gender_prep->execute();
                                $gender_data = $gender_prep->fetch();

                                if ($gender_data['gjinia'] == 'Mashkull') {
                                    $gjinia = 'I nderuar z.';
                                } else {
                                    $gjinia = 'E nderuar znj.';
                                }

                                $mail = new PHPMailer(true);


                                try {
                                    //Server settings
                                    $mail->SMTPDebug = 0;                                       //Enable verbose debug output
                                    $mail->isSMTP();                                            //Send using SMTP
                                    $mail->Host       = 'smtp.gmail.com';                       //Set the SMTP server to send through
                                    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                                    $mail->Username   = SITE_EMAIL;            //SMTP username
                                    $mail->Password   = SITE_PASSWORD;                           //SMTP password
                                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            //Enable implicit TLS encryption
                                    $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

                                    //Recipients
                                    $mail->setFrom('terminet.online@gmail.com', 'terminet-online.com');
                                    $mail->addAddress($emailPacientit10, $emriPacientit10 . ' ' . $mbiemriPacientit10);                           //Add a recipient


                                    //Content
                                    $mail->isHTML(true);                                        //Set email format to HTML


                                    $mail->Subject = 'Termini u rezervua';
                                    $mail->Body    =   "<p style='font-size: 16px; color: black;'>
                                                                    $gjinia{$mbiemriPacientit10},
                                                                    <br> <br>
                                                                    Termini juaj i dyte ne daten:$data, ne oren:$zene_deri, 
                                                                    eshte rezervuar me sukses.
                                                                    <br><br>
                                                                    Me respekt, <br>
                                                                    sistemi-termineve-online.com
                                                                    </p>";

                                    $mail->send();

                                    echo "<script>
                                             window.location.replace('orari.php');
                                        </script>";
                                } catch (Exception $e) {
                                    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                                    echo "<script>
                                             window.location.replace('orari.php');
                                        </script>";
                                }
                            }
                        }
                    }
                }
            }
        }
        ?>
        <form class="form-signin" method="POST" enctype="multipart/form-data" autocomplete="off">
            <h1 class="h3 fw-normal text-center">Orari</h1>
            <div>
                <select class="form-select <?= $invalid_doc ?? "" ?> res" aria-label="Default select example" name="doktori">
                    <option value="">Zgjedhni doktorin</option>
                    <?php foreach ($doc_data as $doc_data) : ?>
                        <option value="<?= $doc_data['fullName'] ?>"><?= $doc_data['fullName'] ?></option>
                    <?php endforeach; ?>
                </select>
                <span class="text-danger fw-normal"><?php echo $doktorErr; ?></span>
            </div>

            <div class="d-inline-block">
                <label for="startDate">Data:</label>
                <input id="startDate" class="form-control data  <?= $invalid_date ?? "" ?>" name="data" type="date" value="<?= $s_date ?>" />
                <span class="text-danger fw-normal"><?php echo $dateErr; ?></span>
            </div>


            <div class="text-center mb-0 resOrari">
                <div class="d-inline-block w-50" style="float: left;">
                    <label for="startingHour">Nga ora:</label>
                    <input id="startingHour" class="form-control time  <?= $invalid_from_time ?? "" ?>" name="from_time" type="time" value="<?= $from_time ?>" />
                    <span class="text-danger fw-normal"><?php echo $from_time_err; ?></span>
                </div>

                <div class="d-inline-block w-50" style="float: right;">
                    <label for="leavingHour">Ne oren:</label>
                    <input id="leavingHour" class="form-control  time time1 <?= $invalid_to_time ?? "" ?>" name="to_time" type="time" value="<?= $to_time; ?>" />
                    <span class="text-danger ms-2 fw-normal"><?php echo $to_time_err; ?></span>
                </div>

                <div>
                    <select class="form-select res <?= $invalid_duration ?? "" ?>" aria-label="Default select example" name="kohzgjatja">
                        <option value="" selected>Kohezgjatja</option>
                        <option value="15">15min</option>
                        <option value="20">20min</option>
                        <option value="30">30min</option>
                    </select>
                    <span class="text-danger ms-2 fw-normal"><?php echo $durationErr; ?></span>
                </div>
            </div>




            <button class="w-100 btn btn-lg btn-primary updateRes" type="submit" name="submit">Submit</button>
        </form>
    </main>

    <?php
    $searchedQuery = "";
    $showEntries;
    $entries = isset($_GET['entries']) ? $_GET['entries'] : 25;
    if (isset($_GET['entries'])) {
        $showEntries = $_GET['entries'];
        $searchedQuery = isset($_GET['keyword']) ? $_GET['keyword'] : "";
        if ($showEntries == 25) {
            $entry25 = 'selected';
        } else if ($showEntries == 50) {
            $entry50 = 'selected';
        } else if ($showEntries == 75) {
            $entry75 = 'selected';
        } else if ($showEntries == 100) {
            $entry100 = 'selected';
        }
    }


    $sortDefault = "default";

    $sortBy = isset($_GET['sortBy']) ? $_GET['sortBy'] : $sortDefault;

    $sort = "";


    $countSql = "SELECT COUNT(*) as total FROM orari";
    $countPrep = $con->prepare($countSql);
    $countPrep->execute();
    $totalRows = $countPrep->fetch();

    $totalRows = $totalRows['total'];

    $totalPages = ceil($totalRows / $entries);

    $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;

    $startIndex = ($currentPage - 1) * $entries;


    if ($sortBy == "default") {
        $sort = " ORDER BY DATE(data) LIMIT :startIndex, $entries";
        $sortDate = 'selected';
        $searchedQuery = isset($_GET['keyword']) ? $_GET['keyword'] : "";
    } else if ($sortBy == "ASC") {
        $sort = " ORDER BY doktori ASC LIMIT :startIndex, $entries";
        $sortASC = 'selected';
        $searchedQuery = isset($_GET['keyword']) ? $_GET['keyword'] : "";
    } else if ($sortBy == "DESC") {
        $sort = " ORDER BY doktori DESC LIMIT :startIndex, $entries";
        $sortDESC = 'selected';
        $searchedQuery = isset($_GET['keyword']) ? $_GET['keyword'] : "";
    } else if ($sortBy == "date") {
        $sort = " ORDER BY DATE(data) LIMIT :startIndex, $entries";
        $sortDate = 'selected';
        $searchedQuery = isset($_GET['keyword']) ? $_GET['keyword'] : "";
    }


    $keywordPrep;
    if (isset($_GET['search']) && !empty($_GET['keyword'])) {
        $keyword = $_GET['keyword'];

        $sort = "SELECT o.id, o.doktori, o.departamenti, o.data, o.nga_ora, o.deri_oren, o.kohezgjatja, o.zene_deri, d.name AS
        'dep_name' FROM orari AS o INNER JOIN departamentet AS d ON o.departamenti = d.id WHERE (doktori=:keyword OR departamenti=:keyword) " . $sort;
        $sql = $sort;

        $prep = $con->prepare($sql);
        $prep->bindParam(':keyword', $keyword);
        $prep->bindValue(':startIndex', $startIndex, PDO::PARAM_INT);
        $prep->execute();
        $data = $prep->fetchAll(PDO::FETCH_ASSOC);

        $searchedQuery = $keyword;
    } else {

        $sql = "SELECT o.id, o.doktori, o.departamenti, o.data, o.nga_ora, o.deri_oren, o.kohezgjatja, o.zene_deri, d.name AS
        'dep_name' FROM orari AS o INNER JOIN departamentet AS d ON o.departamenti = d.id " . $sort;
        $prep = $con->prepare($sql);
        $prep->bindValue(':startIndex', $startIndex, PDO::PARAM_INT);
        $prep->execute();
        $data = $prep->fetchAll(PDO::FETCH_ASSOC);
    }



    if (!$data) {
        $empty = 'empty';
    } else {
        $empty = '';
    }


    $current_date = date('Y-m-d');
    foreach ($data as $datasss) {
        if ($current_date > $datasss['data']) {
            $exp_date = "DELETE FROM orari WHERE id=:id";
            $exp_prep = $con->prepare($exp_date);
            $exp_prep->bindParam(':id', $datasss['id']);
            $exp_prep->execute();
        }
    }

    ?>


    <article class="table_wrapper d-flex flex-column p-2">
        <div class="d-flex justify-content-between">
            <div>
                <form id="entriesForm" method="GET" class="d-flex align-items-center w-25" action="">
                    <input type="hidden" name="sortBy" value="<?= $sortBy ?>">
                    <input type="hidden" name="page" value="<?= $currentPage ?>">
                    <label for="entries" class="me-2">Shfaq</label>
                    <select class="form-select" id="entries" aria-label="" name="entries" style="width: 80px; height: 58px" onchange="this.form.submit()">
                        <option value="25" <?= $entry25 ?? '' ?>>25</option>
                        <option value="50" <?= $entry50 ?? '' ?>>50</option>
                        <option value="75" <?= $entry75 ?? '' ?>>75</option>
                        <option value="100" <?= $entry100 ?? '' ?>>100</option>
                    </select>
                    <label for="entries" class="ms-2">rreshta</label>
                </form>
            </div>

            <script>
                $(document).ready(function() {
                    $('#entries').change(function() {
                        $('#entriesForm').submit();
                    });
                });
            </script>


            <div class="d-flex w-75 justify-content-end pe-2">
                <div class="w-25">
                    <form id="sortForm" method="GET" class="d-flex align-items-center" action="">
                        <input type="hidden" name="entries" value="<?= $entries ?>">
                        <input type="hidden" name="page" value="<?= $currentPage ?>">
                        <select class="form-select" id="sortBy" name="sortBy" aria-label="Default select example" style="height: 58px" onchange="this.form.submit()">
                            <option value="ASC" <?= $sortASC ?? "" ?>>Sipas renditjes A-Zh</option>
                            <option value="DESC" <?= $sortDESC ?? "" ?>>Sipas renditjes Zh-A</option>
                            <option value="date" <?= $sortDate ?? "" ?>>Sipas datës</option>
                        </select>
                    </form>
                </div>
                <script>
                    $(document).ready(function() {
                        $('#sortBy').change(function() {
                            $('#sortForm').submit();

                        });
                    });
                </script>
                <div class="w-50 ms-2 me-1">
                    <form method="get" action="">
                        <input type="hidden" name="entries" value="<?= $entries ?>">
                        <input type="hidden" name="sortBy" value="<?= $sortBy ?>">
                        <input type="hidden" name="page" value="<?= $currentPage ?>">
                        <div class="d-flex mb-1">
                            <div class="form-floating w-75">
                                <input type="text" class="form-control lastName" id="floatingInput" name="keyword" placeholder="Kerkro:" value="<?= $searchedQuery ?>">
                                <label for="floatingInput">Kerko:</label>
                            </div>
                            <button class="btn btn-primary w-25 fs-5 ms-2" name="search">Kerko</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php if ($empty == '') : ?>

            <table class="table table-striped text-center table_patinet">
                <thead>
                    <tr>
                        <th scope="col">Doktori</th>
                        <th scope="col">Departamenti</th>
                        <th scope="col">Data</th>
                        <th scope="col">Ne dizpozicion</th>
                        <th scope="col">Kohezgjatja</th>
                        <th scope="col">Aksioni</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data as $data) : ?>
                        <tr>
                            <td><?= $data['doktori'] ?></td>
                            <td><?= $data['dep_name'] ?></td>
                            <td><?= $data['data'] ?></td>
                            <td><?= $data['nga_ora'] . '-' . $data['deri_oren'] ?></td>
                            <td><?= $data['kohezgjatja'] . 'min' ?> </td>
                            <td class="text-center">
                                <a class="text-decoration-none text-white" href="editOrari.php?id=<?= $data['id']  ?>"><button class="btn btn-primary w-25  p-1 text-white">Edit</button></a>
                                <a class="text-decoration-none text-white" href="deleteOrarin.php?id=<?= $data['id']  ?>"><button class="btn btn-danger w-50 p-1 text-white">Delete</button></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <?php if ($empty == 'empty') { ?>
            <article class=" d-flex justify-content-center mt-5">
                <h1 class=" h1 fw-normal text-center mt-5">Te dhenat nuk u gjenden ne databaze.</h1>
            </article>
        <?php } else { ?>
            <nav aria-label="Page navigation example">
                <ul class="pagination">
                 <?php
                    $maxVisibleLinks = 5; // Maximum number of visible page links

                    $startPage = max(1, $currentPage - floor($maxVisibleLinks / 2));
                    $endPage = min($startPage + $maxVisibleLinks - 1, $totalPages);

                    $showEllipsisStart = ($startPage > 1);
                    $showEllipsisEnd = ($endPage < $totalPages);

                    if ($currentPage == 1) {
                        echo '<li class="page-item disabled"><a href="#" class="page-link" tabindex="-1">Previous</a></li>';
                    }

                    if ($currentPage > 1) {
                        $previousPage = $currentPage - 1;
                        echo '<li class"page-item"><a href="?page=' . $previousPage . '" class="page-link">Previous</a></li>';
                    }

                    for ($i = $startPage; $i <= $endPage; $i++) {
                        $activePage = ($i == $currentPage) ? 'active' : '';
                        echo '<li class="page-item"><a class="page-link ' . $activePage . '" href="?page=' . $i . '">' . $i . '</a></li>';
                    }



                    if ($currentPage < $totalPages) {
                        $nextPage = $currentPage + 1;
                        echo '<li class="page-item"><a href="?page=' . $nextPage . '" class="page-link">Next</a></li>';
                    } else{
                        echo '<li class="page-item disabled"><a href="#" class="page-link" abindex="-1">Next</a></li>';
                    }
                    ?>
                </ul>
            </nav>
        <?php } ?>
    </article>





</body>

</html>