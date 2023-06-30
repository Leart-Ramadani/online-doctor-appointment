<?php
include('../config.php');

if (!isset($_SESSION['emri']) && !isset($_SESSION['mbiemri'])) {
    header("Location: login.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appoinment</title>
    <link rel="shortcut icon" href="../photos/icon-hospital.png">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/responsive.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link rel="stylesheet" href="../bootstrap-5.1.3-examples/sidebars/sidebars.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous" defer></script>
    <script src="../bootstrap-5.1.3-examples/sidebars/sidebars.js" defer></script>
    <!-- JQuery link -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <script src="../js/index.js" defer></script>
    <!-- Font-awesome script -->
    <script src="https://kit.fontawesome.com/a28016bfcd.js" crossorigin="anonymous"></script>

</head>

<body>
    <div class="sideBlock">
        <button type="button" class="ham" id="ham_menu"><i class="fa-solid fa-bars"></i></button>
    </div>


    <div class="flex-shrink-0 p-3 text-white bg-dark sidebar">    
        <button type="button" class="close_side"><i class="fa-solid fa-close"></i></button>
        <p class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
            <span class=" sess_admin"><?php echo $_SESSION['emri'] . ' ' . $_SESSION['mbiemri'] ?></span>
        </p>
        <hr>
        <ul class="nav nav-pills flex-column mb-auto">
            <li><a href="../index.php" class="nav-link text-white">Homepage</a></li>
            <li class="nav-item"><a href="rezervoTermin.php" class="nav-link active" aria-current="page">Appoinments</a></li>
            <li><a href="terminet_e_mia.php" class="nav-link text-white">My appoinments</a></li>
            <li><a href="ankesat.php" class="nav-link text-white">Complaints</a></li>
            <li><a href="historiaTermineve(pacientit).php" class="nav-link text-white">Appoinments history</a></li>
        </ul>
        <hr>
        <div class="dropdown">
            <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="https://w7.pngwing.com/pngs/200/420/png-transparent-user-profile-computer-icons-overview-rectangle-black-data-thumbnail.png" alt="" width="32" height="32" class="rounded-circle me-2">
                <strong class="useri"><?php echo $_SESSION['username'] ?></strong>
            </a>
            <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
                <li><a class="dropdown-item" href="profili.php">Profile</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item" href="logout.php">Sign out</a></li>
            </ul>
        </div>
    </div>




    <?php
    $sql = "SELECT * FROM orari";
    $prep = $con->prepare($sql);
    $prep->execute();
    $data = $prep->fetchAll();

    if (!$data) {
        $empty = 'empty';
    } else {
        $empty = '';
    }
    ?>
    <main class="main mainRes">
        <?php if ($empty == '') : ?>
            <table class="table table-striped text-center mt-2 table_patient">
                <thead>
                    <tr>
                        <th scope="col" style="display: none;">ID</th>
                        <th scope="col">Doctor</th>
                        <th scope="col">Departament</th>
                        <th scope="col">Date</th>
                        <th scope="col">Available</th>
                        <th scope="col">Duration</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data as $data) : ?>
                        <tr>
                            <td class="id" style="display: none;"><?= $data['id'] ?></td>
                            <td><?= $data['doktori'] ?></td>
                            <td><?= $data['departamenti'] ?></td>
                            <td><?= $data['data']; ?></td>
                            <td><?= $data['nga_ora'] . '-' . $data['deri_oren'] ?></td>
                            <td><?= $data['kohezgjatja'] . 'min'  ?></td>
                            <td class="text-center">
                                <!-- href="rezervo.php?id=<?= $data['id'] ?>" -->
                                <a class="text-decoration-none text-white popUpWindow">
                                    <button class="btn btn-primary w-100 p-1 text-white rez">Book</button>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <?php if ($empty == 'empty') : ?>
            <article style="margin-left: 200px; width: 100%;" class="mt-5">
                <h1 class=" h1 fw-normal text-center mt-5">Data not found in database.</h1>
            </article>
        <?php endif; ?>
    </main>

    <?php



    $sql = "SELECT emri, mbiemri, numri_personal, email, telefoni FROM patient_table WHERE numri_personal=:numri_personal";
    $prep = $con->prepare($sql);
    $prep->bindParam(':numri_personal', $_SESSION['numri_personal']);
    $prep->execute();
    $patient_data = $prep->fetch();
    ?>

    <article id="popWrapper" class="popWrapper">

    </article>

    <div id="popWindow" class="popUp">
        <div class="pac_det">
            <h4>Termini</h4>
            <button id="close" class="close">
                <i class="fa-solid fa-close rezervoClose"></i>
            </button>
        </div>

        <h4 class="det_pac_h4">Patient details</h4>

        <div class="emri_pac">
            <p>Name: <span><?= $patient_data['emri'] ?></span></p>
            <hr>
            <p>Surname: <span><?= $patient_data['mbiemri'] ?></span></p>
            <hr>
            <p>Email: <span><?= $patient_data['email'] ?></span></p>
            <hr>
            <p>Personal ID: <span><?= $patient_data['numri_personal'] ?></span></p>
            <hr>
            <p>Phone number: <span><?= $patient_data['telefoni'] ?></span></p>
            <hr>
        </div>

        <h4 class="det_pac_h4">Appoinment details</h4>

        <div class="emri_pac doc_pac">

            <p>Doctor: <span class="doc_name"></span></p>
            <hr>
            <p>Departament: <span class="doc_dep"><?= $data['departamenti'] ?> </span></p>
            <hr>
            <p>Appoinment date: <span class="app_date"><?= $row['data'] ?></span></p>
            <hr>
            <p>Schedule: <span class="app_time"><?= $row['nga_ora'] . ' - ' . $row['deri_oren'] ?><span></p>
            <hr>
            <p>Duration: <span class="app_dur"><?= $row['kohezgjatja'] . 'min' ?></span></p>
            <hr>'
        </div>

        <form action="rezervo.php" method="POST" class="submit_rez">
            <button type="submit" name="rezervo" class="btn btn-success">Book Appointment</button>
        </form>


        <script>
            $(document).ready(function() {
                $('.popUpWindow').click(function(e) {
                    e.preventDefault();

                    let id = $(this).closest('tr').find('.id').text();

                    $.ajax({
                        type: "POST",
                        url: "rezervo.php",
                        data: {
                            'checking_viewbtn': true,
                            'id': id,
                        },
                        success: function(response) {
                            console.log(response);
                            $('.doc_pac').html(response);
                        }

                    })


                });
            });
        </script>

</body>

</html>