<?php
include('../config.php');

if (!isset($_SESSION['fullName']) && !isset($_SESSION['username'])) {
    header("Location: login.php");
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment</title>
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
            <span class=" sess_admin"><?php echo  $_SESSION['fullName'] ?></span>
        </p>
        <hr>
        <ul class="nav nav-pills flex-column mb-auto">
            <li><a href="../index.php" class="nav-link text-white">Homepage</a></li>
            <li class="nav-item"><a href="rezervoTermin.php" class="nav-link active" aria-current="page">Appointments</a></li>
            <li><a href="terminet_e_mia.php" class="nav-link text-white">My appointments</a></li>
            <li><a href="ankesat.php" class="nav-link text-white">Complaints</a></li>
            <li><a href="historiaTermineve(pacientit).php" class="nav-link text-white">Appointments history</a></li>
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



    $countSql = "SELECT COUNT(*) as total FROM orari";
    $countPrep = $con->prepare($countSql);
    $countPrep->execute();
    $totalRows = $countPrep->fetch();

    $totalRows = $totalRows['total'];

    $totalPages = ceil($totalRows / $entries);

    $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;

    $startIndex = ($currentPage - 1) * $entries;


    $keywordPrep;
    if (isset($_GET['search']) && !empty($_GET['keyword'])) {
        $keyword = $_GET['keyword'];

        $depQuery = "SELECT id FROM departamentet WHERE name = :nameDep";
        $depPrep = $con->prepare($depQuery);
        $depPrep->bindParam(':nameDep', $keyword);
        $depPrep->execute();
        $depFetch = $depPrep->fetch();
        if ($depFetch) {
            $dep = $depFetch['id'];
        } else {
            $dep = '';
        }


        $sort = "SELECT o.id, o.doktori, o.departamenti, o.data, o.nga_ora, o.deri_oren, o.kohezgjatja, o.zene_deri, d.name AS
        'dep_name' FROM orari AS o INNER JOIN departamentet AS d ON o.departamenti = d.id  WHERE doktori=:keyword OR d.id='$dep' OR data=:keyword 
        ORDER BY Date(o.data) LIMIT :startIndex, $entries";
        $sql = $sort;

        $prep = $con->prepare($sql);
        $prep->bindParam(':keyword', $keyword);
        $prep->bindValue(':startIndex', $startIndex, PDO::PARAM_INT);
        $prep->execute();
        $data = $prep->fetchAll(PDO::FETCH_ASSOC);

        $searchedQuery = $keyword;
    } else {
        $sql = "SELECT o.id, o.doktori, o.departamenti, o.data, o.nga_ora, o.deri_oren, o.kohezgjatja, o.zene_deri, d.name AS
        'dep_name' FROM orari AS o INNER JOIN departamentet AS d ON o.departamenti = d.id  ORDER BY Date(o.data) LIMIT :startIndex, $entries";
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
    ?>
    <main class="main mainRes d-flex flex-column p-2">
        <div class="d-flex justify-content-between sort">
            <div>
                <form id="entriesForm" method="GET" class="d-flex align-items-center w-25" action="rezervoTermin.php">
                    <input type="hidden" name="page" value="<?= $currentPage ?>">
                    <label for="entries" class="me-2">Show</label>
                    <select class="form-select" id="entries" aria-label="" name="entries" style="width: 80px; height: 38px" onchange="this.form.submit()">
                        <option value="25" <?= $entry25 ?? '' ?>>25</option>
                        <option value="50" <?= $entry50 ?? '' ?>>50</option>
                        <option value="75" <?= $entry75 ?? '' ?>>75</option>
                        <option value="100" <?= $entry100 ?? '' ?>>100</option>
                    </select>
                    <label for="entries" class="ms-2">entries</label>
                </form>
            </div>

            <script>
                $(document).ready(function() {
                    $('#entries').change(function() {
                        $('#entriesForm').submit();
                    });
                });
            </script>



            <div class="me-1 searchInp">
                <form method="get" action="rezervoTermin.php">
                    <input type="hidden" name="entries" value="<?= $entries ?>">
                    <input type="hidden" name="page" value="<?= $currentPage ?>">
                    <div class="d-flex mb-1">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control lastName" placeholder="Search:" aria-label="Search:" aria-describedby="button-addon2" name="keyword" value="<?= $searchedQuery ?>">
                            <button class="btn btn-outline-primary" id="button-addon2" name="search"><i class="fa-solid fa-magnifying-glass"></i></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>


        <?php if ($empty == '') : ?>
            <table class="table table-hover text-center table_patient">
                <thead>
                    <tr class="table-info">
                        <th scope="col" style="display: none;">ID</th>
                        <th scope="col">Doctor</th>
                        <th scope="col" class="departamentRes">Departament</th>
                        <th scope="col">Date</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data as $data) {
                        $date = date_create($data['data']);
                        $date = date_format($date, "d/m/Y, D");
                    ?>
                        <tr>
                            <td class="id" style="display: none;"><?= $data['id'] ?></td>
                            <td><?= $data['doktori'] ?></td>
                            <td class="departamentRes"><?= $data['dep_name'] ?></td>
                            <td><?php echo $date; ?></td>
                            <td class="text-center">
                                <!-- href="rezervo.php?id=<?= $data['id'] ?>" -->
                                <a class="text-decoration-none text-white popUpWindow" title="Book Appointment">
                                    <button class="btn btn-primary text-white rez"><i class="fa-solid fa-calendar-plus"></i></button>
                                </a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php endif; ?>


        <?php if ($empty == 'empty') { ?>
            <article class=" d-flex justify-content-center mt-5">
                <h1 class=" h1 fw-normal text-center mt-5">Data not found in database.</h1>
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
                    } else {
                        echo '<li class="page-item disabled"><a href="#" class="page-link" abindex="-1">Next</a></li>';
                    }
                    ?>
                </ul>
            </nav>
        <?php } ?>
    </main>

    <?php



    $sql = "SELECT  fullName, personal_id, email, phone FROM users WHERE personal_id=:personal_id";
    $prep = $con->prepare($sql);
    $prep->bindParam(':personal_id', $_SESSION['numri_personal']);
    $prep->execute();
    $patient_data = $prep->fetch();
    ?>

    <article id="popWrapper" class="popWrapper">

    </article>

    <div id="popWindow" class="popUp">
        <div class="pac_det">
            <h4>Appointment</h4>
            <button id="close" class="close">
                <i class="fa-solid fa-close rezervoClose"></i>
            </button>
        </div>

        <h4 class="det_pac_h4">Appointment Details</h4>

        <div class="emri_pac doc_pac">

        </div>


        <div class="d-flex justify-content-between align-items-center me-3 mt-1">
            <div class="d-flex flex-column justify-content-center">
                <div class="d-flex align-items-center">
                    <div class="bg-danger ms-1" style="height:15px; width: 15px;"></div> <label class="ms-1" style="font-size: 14px;"> - Booked</label>
                </div>
                <div class="d-flex align-items-center">
                    <div class="bg-primary ms-1" style="height:15px; width: 15px;"></div><label class="ms-1" style="font-size: 14px;"> - Free to book</label>
                </div>
                <div class="d-flex align-items-center">
                    <div class="ms-1" style="height:15px; width: 15px; background: rgb(97,161,254);"></div><label class="ms-1" style="font-size: 14px;"> - Completed</label>
                </div>
            </div>
            <button type="submit" class="btn btn-primary disabled bookApp"><i class="fa-solid fa-calendar-plus"></i> Book</button>
        </div>
    </div>




    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Join waiting list</h5>
                    <button type="button" class="btn-close closeModal" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body waitBody">
                    <p>Unfortunately, this appointment is already booked. However, you have the option to join the waiting list.
                        In the event that this appointment is canceled, it will be automatically booked for you. </p>
                    <p>Note: The waiting list operates on a first-come, first-served basis, meaning that the order of
                        joining determines priority for future available appointments.</p>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <p>Waiting list: <span class="waitingList"></span></p>
                    <div>
                        <button type="button" class="btn btn-secondary closeModal1" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary joinBtn">Join</button>
                    </div>
                </div>
            </div>
        </div>
    </div>





    <script>
        const bookApp = document.querySelector('.bookApp');
        const pop = document.querySelector('.popUp');

        let screenWidth = window.innerWidth;
        const getValue = button => {
            let selectedTime = button;
            document.querySelector('.appTime').innerHTML = selectedTime;
            document.querySelector('.bookApp').classList.remove('disabled');

            const bookAppointment = () => {
                if (screenWidth > 425) {

                    pop.style.width = '400px';
                    pop.style.height = '270px';
                    pop.classList.add('d-flex');
                    pop.classList.add('justify-content-center');
                    pop.classList.add('flex-column');
                } else {
                    pop.style.width = '310px';
                    pop.style.height = '270px';
                    pop.classList.add('d-flex');
                    pop.classList.add('justify-content-center');
                    pop.classList.add('flex-column');
                }

                pop.innerHTML = "<h3 class='text-center'>Please wait...</h3> <div class='loader'></div>";
                $.ajax({
                    url: 'rezervo.php',
                    type: 'POST',
                    data: {
                        rezervo: true,
                        time: selectedTime
                    },
                    success: function(response) {
                        if (response.includes('Appointment exists')) {
                            pop.classList.add('d-flex');
                            pop.classList.add('align-items-center');

                            pop.innerHTML = "<h3 class='text-center'>You have an appointment booked in this date.</h3>";
                            setTimeout(() => {
                                window.location.replace('rezervoTermin.php');
                            }, 1500);
                        } else if (response.includes('Appointment booked')) {
                            pop.innerHTML = "<h3 class='text-center'>Your appointment has been successfully booked!<h3>";
                            setTimeout(() => {
                                window.location.replace('rezervoTermin.php');
                            }, 1500);

                        } else if (response.includes('not paied')) {
                            pop.innerHTML = "<h3 class='text-center'>You can't book another appointment if you don't pay the bill!<h3>";
                            setTimeout(() => {
                                window.location.replace('rezervoTermin.php');
                            }, 3000);
                        } else if (response.includes('Problems with server or internet')) {
                            pop.innerHTML = "<h3 class='text-center'>Appointment booking has failed. Problems with server or internet.</h3>";
                            setTimeout(() => {
                                window.location.replace('rezervoTermin.php');
                            }, 1500);

                        }
                    }
                });
            }
            bookApp.addEventListener('click', bookAppointment);
        }

        const waitList = button => {
            let time = button;
            const joinBtn = document.querySelector('.joinBtn');
            const waitBody = document.querySelector('.waitBody');
            const closeModal = document.querySelector('.closeModal');
            const closeModal1 = document.querySelector('.closeModal1');
            const waitingList = document.querySelector('.waitingList');

            $.ajax({
                url: 'waitingList.php',
                type: 'POST',
                data: {
                    count: true,
                    time: time
                },
                success: response => {
                    waitingList.innerHTML = response;
                }
            });

            const joinWaitList = () => {
                joinBtn.disabled = 'true';
                closeModal.classList.add('disabled');
                closeModal1.classList.add('disabled');
                joinBtn.innerHTML = "<span class='spinner-border spinner-border-sm' role='status' aria-hidden='true'></span> Loading...";

                $.ajax({
                    url: 'waitingList.php',
                    type: 'POST',
                    data: {
                        waitList: true,
                        time: time
                    },
                    success: function(response) {
                        console.log(response);
                        if (response.includes('Success')) {
                            closeModal.classList.remove('disabled');
                            closeModal1.classList.remove('disabled');
                            joinBtn.innerHTML = "Joined";
                            waitBody.innerHTML = "Congratulations! You have successfully joined the waiting list. In the event of a cancellation," +
                                "if luck is on your side, the appointment confirmation will be promptly sent to your email.";
                            closeModal.onclick = () => {
                                window.location.replace('rezervoTermin.php');
                            }
                            setTimeout(() => {
                                window.location.replace('rezervoTermin.php');
                            }, 5000);
                        } else if (response.includes('Exists')) {
                            closeModal.classList.remove('disabled');
                            closeModal1.classList.remove('disabled');
                            joinBtn.innerHTML = "Failed";
                            waitBody.innerHTML = "You have already joined the waiting list.";
                            closeModal.onclick = () => {
                                window.location.replace('rezervoTermin.php');
                            }
                            setTimeout(() => {
                                window.location.replace('rezervoTermin.php');
                            }, 3000);
                        } else if (response.includes('Error')) {
                            closeModal.classList.remove('disabled');
                            closeModal1.classList.remove('disabled');
                            joinBtn.innerHTML = "Failed";
                            waitBody.innerHTML = "Something went wrong.";
                            closeModal.onclick = () => {
                                window.location.replace('rezervoTermin.php');
                            }
                            setTimeout(() => {
                                window.location.replace('rezervoTermin.php');
                            }, 3000);
                        }
                    }
                });
            }

            joinBtn.addEventListener('click', joinWaitList);

        }
    </script>

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
                        $('.doc_pac').html(response);
                    }

                })


            });
        });
    </script>

</body>

</html>