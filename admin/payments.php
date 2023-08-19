<?php
include('../config.php');
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
}
?>
<?php include('header.php') ?>
<title>Doctors</title>
</head>

<body>
    <div class="sideBlock">
        <button type="button" class="ham" id="ham_menu"><i class="fa-solid fa-bars"></i></button>
    </div>

    <div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark sidebar">
        <button type="button" class="close_side"><i class="fa-solid fa-close"></i></button>
        <p class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
            <span class="fs-5"><?php echo $_SESSION['admin'] ?></span>
        </p>
        <hr style="margin: 10px 0 !important;">
        <ul class="nav nav-pills mb-auto">
            <li class="nav-item"><a href="dashboard.php" class="nav-link text-white">Dashboard</a></li>
            <li class="nav-item"><a href="doktoret.php" class="nav-link text-white">Doctors</a></li>
            <li><a href="departamentet.php" class="nav-link text-white">Departaments</a></li>
            <li><a href="orari.php" class="nav-link text-white">Schedule</a></li>
            <li><a href="terminet.php" class="nav-link text-white">Appointments</a></li>
            <li><a href="pacientat.php"" class=" nav-link text-white">Patients</a></li>
            <li><a href="historiaTerminit.php" class="nav-link text-white">Appointments history</a></li>
            <li class="nav-item"><a href="galeria.php" class="nav-link text-white">Gallery</a></li>
            <li><a href="ankesat.php" class="nav-link text-white">Complaints</a></li>
            <li><a href="kerkesatAnulimit.php" class="nav-link text-white">Cancelation requests</a></li>
            <li><a href="prices.php" class="nav-link text-white">Prices</a></li>
            <li><a href="payments.php" class="nav-link text-white active" aria-current="page">Payments</a></li>
            <li><a href="references.php" class="nav-link text-white">References</a></li>
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


    $countSql = "SELECT COUNT(*) as total FROM terminet WHERE statusi='Completed' AND paied=false";
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

        $sort = "SELECT u.id, u.doktori, u.pacienti, u.numri_personal, u.data, u.ora, d.name AS
                'dep_name' FROM terminet AS u INNER JOIN departamentet AS d ON u.departamenti = d.id 
                WHERE statusi='Completed' AND paied=false AND (doktori=:keyword OR d.id='$dep' OR 
                pacienti=:keyword OR numri_personal=:keyword OR data=:keyword OR ora=:keyword) LIMIT :startIndex, $entries";
        $sql = $sort;

        $prep = $con->prepare($sql);
        $prep->bindParam(':keyword', $keyword);
        $prep->bindParam(':keyword', $keyword);
        $prep->bindValue(':startIndex', $startIndex, PDO::PARAM_INT);
        $prep->execute();
        $data = $prep->fetchAll(PDO::FETCH_ASSOC);

        $searchedQuery = $keyword;
    } else {
        $sql = "SELECT u.id, u.doktori, u.pacienti, u.numri_personal, u.data, u.ora, d.name AS
        'dep_name' FROM terminet AS u INNER JOIN departamentet AS d ON u.departamenti = d.id 
        WHERE statusi='Completed' AND paied=false LIMIT :startIndex, $entries";
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


    <main class="">
        <article class="main d-flex flex-column p-2">
            <div class="d-flex justify-content-between pt-2">
                <div>
                    <form id="entriesForm" method="GET" class="d-flex align-items-center w-25" action="">
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



                <div class="w-50 ms-2 me-1">
                    <form method="get" action="">
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
                <table class="table table-striped table_doc table_patient text-center table_doctors">
                    <thead>
                        <tr>
                            <th scope="col" style="display: none;">ID</th>
                            <th scope="col">Doctor</th>
                            <th scope="col">Departament</th>
                            <th scope="col">Patient</th>
                            <th scope="col">Personal ID</th>
                            <th scope="col">Date</th>
                            <th scope="col">Time</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            foreach ($data as $data) { 
                                $date = date_create($data['data']);
                                $date = date_format($date, "d/m/Y");

                                $time = date_create($data['ora']);
                                $time = date_format($time, "H:i");  
                        ?>
                            <tr>
                                <td class="id" style="display: none;"><?= $data['id']; ?></td>
                                <td><?= $data['doktori'] ?></td>
                                <td><?= $data['dep_name'] ?></td>
                                <td><?= $data['pacienti'] ?></td>
                                <td><?= $data['numri_personal'] ?></td>
                                <td><?= $date ?></td>
                                <td><?= $time ?></td>
                                <td>
                                    <a class="text-decoration-none text-white billBtn" title="Bill">
                                        <button class="btn btn-primary text-white" type="button" data-bs-toggle='modal' data-bs-target='#staticBackdrop' id="showPop">
                                            <i class="fa-solid fa-receipt"></i>
                                        </button>
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
        </article>
    </main>

    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Bill</h5>
                    <button type="button" class="btn-close closeModal" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body billBody">
                    <div class="input-group">
                        <label for="Doctor" class="col-form-label me-2">Doctor:</label>
                        <input type="text" class="form-control doc" readonly id="Doctor">
                    </div>
                    <div class="input-group mt-3">
                        <label for="Departament" class="col-form-label me-2">Departament:</label>
                        <input type="text" class="form-control dep" readonly id="Departament">
                    </div>
                    <div class="input-group mt-3">
                        <label for="patient" class="col-form-label me-2">Patient:</label>
                        <input type="text" class="form-control patient" readonly id="patient">
                    </div>
                    <div class="input-group mt-3">
                        <label for="Personal ID" class="col-form-label me-2">Personal ID:</label>
                        <input type="text" class="form-control personal_id" readonly id="Personal ID">
                    </div>
                    <div class="input-group mt-3 align-items-center">
                        <label for="Date" class="col-form-label me-2">Date:</label>
                        <input type="text" class="form-control dateInp" readonly id="Date" value="01/01/2023">
                        <label for="Time" class="col-form-label ms-2 me-2">Time:</label>
                        <input type="text" class="form-control timeInp" readonly id="Time" value="08:00">
                    </div>
                    <div class="input-group mt-3">
                        <label for="Service" class="col-form-label me-2">Service:</label>
                        <input type="text" class="form-control service" readonly id="Service">
                    </div>
                    <div class="input-group mt-3">
                        <label for="Service" class="col-form-label me-2">Price:</label>
                        <input type="text" class="form-control price" readonly id="Service">
                        <span class="input-group-text">&euro;</span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary closeModal1" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary payBill"><i class="fa-solid fa-money-bill-1-wave"></i> Pay</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const docInp = document.querySelector('.doc');
        const depInp = document.querySelector('.dep');
        const patientInp = document.querySelector('.patient');
        const personal_id = document.querySelector('.personal_id');
        const date = document.querySelector('.dateInp');
        const time = document.querySelector('.timeInp');
        const service = document.querySelector('.service');
        const price = document.querySelector('.price');

        const payBtn = document.querySelector('.payBill');


        document.addEventListener('DOMContentLoaded', () => {

            const buttons = document.querySelectorAll('.billBtn');

            buttons.forEach((button) => {
                button.addEventListener('click', () => {

                    const closestTr = button.closest('tr');

                    const idElement = closestTr.querySelector('.id');

                    const id = idElement.textContent;

                    $.ajax({
                        url: 'bill.php',
                        type: 'GET',
                        data: {
                            billSet: true,
                            id: id
                        },
                        success: response => {
                            response = JSON.parse(response);
                            docInp.value = response.Doctor;
                            depInp.value = response.Departament;
                            patientInp.value = response.Patient;
                            personal_id.value = response.Personal_ID;
                            date.value = response.Date;
                            time.value = response.Time;
                            service.value = response.Service;
                            price.value = response.Price;
                        }
                    });

                    const payBill = () => {
                        $.ajax({
                            url: 'bill.php',
                            type: 'GET',
                            data: {
                                payBill: true,
                                id: id
                            },
                            success: response => {
                                if(response.includes('okay')){
                                    document.querySelector('.billBody').innerHTML = 'Paied';
                                    setTimeout(() => {
                                        window.location.replace('./prescription.php?id=' + id);
                                    }, 1000)
                                }
                            }
                        });
                    }
                    payBtn.addEventListener('click', payBill);
                });
            });
        });
    </script>
</body>

</html>