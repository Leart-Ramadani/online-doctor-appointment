<?php
include('../config.php');
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
}
?>
<?php include('header.php') ?>
<title>Prices</title>
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
            <li class="nav-item"><a href="doktoret.php" class="nav-link text-white">Doctors</a></li>
            <li><a href="departamentet.php" class="nav-link text-white">Departaments</a></li>
            <li><a href="orari.php" class="nav-link text-white">Schedule</a></li>
            <li><a href="terminet.php" class="nav-link text-white">Appointments</a></li>
            <li><a href="pacientat.php"" class=" nav-link text-white">Patients</a></li>
            <li><a href="historiaTerminit.php" class="nav-link text-white">Appointments history</a></li>
            <li class="nav-item"><a href="galeria.php" class="nav-link text-white">Gallery</a></li>
            <li><a href="ankesat.php" class="nav-link text-white">Complaints</a></li>
            <li><a href="kerkesatAnulimit.php" class="nav-link text-white ">Cancelation requests</a></li>
            <li><a href="prices.php" class="nav-link text-white active" aria-current="page">Prices</a></li>
            <li><a href="payments.php" class="nav-link text-white">Payments</a></li>
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



    $countSql = "SELECT COUNT(*) as total FROM prices";
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

        $sort = "SELECT * FROM prices WHERE NOT id=0 AND (name=:keyword OR price=:keyword) LIMIT :startIndex, $entries";
        $sql = $sort;

        $prep = $con->prepare($sql);
        $prep->bindParam(':keyword', $keyword);
        $prep->bindValue(':startIndex', $startIndex, PDO::PARAM_INT);
        $prep->execute();
        $data = $prep->fetchAll(PDO::FETCH_ASSOC);

        $searchedQuery = $keyword;
    } else {

        $sql = "SELECT * FROM prices WHERE NOT id=0  LIMIT :startIndex, $entries";
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

    <main class="main mainRes d-flex flex-column align-items-center p-2">
        <div class="d-flex justify-content-between w-100 pt-2 ">
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
                        <section class="bg-primary ms-2 addDoc" type="button" title="Add Service" data-bs-toggle="modal" data-bs-target="#staticBackdrop">+</section>
                    </div>
                </form>
            </div>

        </div>
        <?php if ($empty == '') : ?>
            <table class="table table-striped text-center mt-2 table_patient">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Service</th>
                        <th scope="col">Price</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data as $data) : ?>
                        <tr>
                            <td class="id"><?= $data['id'] ?></td>
                            <td><?= $data['name'] ?></td>
                            <td><?= $data['price'] ?>&euro;</td>
                            <td>
                                <a class="text-decoration-none text-white editService" title="Edit">
                                    <button class="btn btn-warning p-1 text-white mb-1 rez " type="button" data-bs-toggle="modal" data-bs-target="#editService"><i class="fa-solid fa-user-edit"></i></button>
                                </a>
                                <a class="text-decoration-none text-white" href="deleteService.php?id=<?= $data['id'] ?>" title="Delte">
                                    <button class="btn btn-danger p-1 text-white mb-1 rez"><i class="fa-solid fa-trash"></i></button>
                                </a>
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
            <nav aria-label="Page navigation example" class="w-100 ps-2">
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


        <!-- Modal -->
        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Add new service</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="POST">
                            <div class="mb-3">
                                <label for="recipient-name" class="col-form-label">Service:</label>
                                <input type="text" class="form-control service" id="recipient-name">
                                <span class="serviceError text-danger"></span>
                            </div>
                            <div class="input-group">
                                <label for="price" class="col-form-label me-2">Price:</label>
                                <input type="text" class="form-control price" id="price">
                                <span class="input-group-text">&euro;</span>
                            </div>
                            <span class="priceError text-danger"></span>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary addPrice">Add</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Modal -->
        <div class="modal fade" id="editService" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Edit service</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="POST">
                            <div class="mb-3">
                                <label for="recipient-name" class="col-form-label">Service:</label>
                                <input type="text" class="form-control serviceEdit" id="recipient-name">
                                <span class="editSerErr text-danger"></span>
                            </div>
                            <div class="input-group">
                                <label for="price" class="col-form-label me-2">Price:</label>
                                <input type="text" class="form-control editPrice" id="price">
                                <span class="input-group-text">&euro;</span>
                            </div>
                            <span class="editPriceErr text-danger"></span>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary editServicePrice">Edit</button>
                    </div>
                </div>
            </div>
        </div>


    </main>


    <!-- Add new service script -->
    <script>
        const service = document.querySelector('.service');
        const price = document.querySelector('.price');
        const add = document.querySelector('.addPrice');

        const serviceErr = document.querySelector('.serviceError');
        const priceErr = document.querySelector('.priceError');



        const addPrice = () => {
            $.ajax({
                url: 'addPrice.php',
                type: 'POST',
                data: {
                    addPrice: true,
                    service: service.value,
                    price: price.value
                },
                success: response => {
                    response = JSON.parse(response);
                    let validService = true;
                    let validPrice = true;
                    for (i = 0; i < response.length; i++) {
                        if (response[i].includes('empty service')) {
                            validService = false;
                            service.classList.add('is-invalid');
                            serviceErr.innerHTML = '*Fill service input!';
                        } else if (response[i].includes('Exists')) {
                            validService = false;
                            serviceErr.innerHTML = '*This service exists!';
                            service.classList.add('is-invalid');
                        }

                        if (response[i].includes('empty price')) {
                            validPrice = false;
                            price.classList.add('is-invalid');
                            priceErr.innerHTML = '*Fill price input!';
                        }
                    }
                    if (validService) {
                        service.classList.remove('is-invalid');
                        serviceErr.innerHTML = '';
                    }
                    if (validPrice) {
                        price.classList.remove('is-invalid');
                        priceErr.innerHTML = '';
                    }

                    if (validPrice && validService) {
                        $.ajax({
                            url: 'addPrice.php',
                            type: 'POST',
                            data: {
                                insertPrice: true,
                                service: service.value,
                                price: price.value
                            },
                            success: response => {
                                if (response.includes('Inserted')) {
                                    document.querySelector('.modal-body').innerHTML = 'Inserted';
                                    setTimeout(() => {
                                        window.location.reload();
                                    }, 1000)
                                }
                            }
                        });
                    }
                }

            });
        }
        add.addEventListener('click', addPrice);
    </script>


    <!-- Edit a service script -->
    <script>
        const editPrice = document.querySelector('.editPrice');
        const editService = document.querySelector('.serviceEdit');
        const editErr = document.querySelector('.editSerErr');
        const editPriceErr = document.querySelector('.editPriceErr');

        const editServicePrice = document.querySelector('.editServicePrice');

        document.addEventListener('DOMContentLoaded', () => {

            const buttons = document.querySelectorAll('.editService');


            buttons.forEach((button) => {
                button.addEventListener('click', () => {

                    const closestTr = button.closest('tr');

                    const idElement = closestTr.querySelector('.id');

                    const id = idElement.textContent;

                    $.ajax({
                        url: 'editService.php',
                        type: 'GET',
                        data: {
                        editService: true,
                            idEdit: id
                        },
                        success: response => {
                            response = JSON.parse(response);
                            editService.value = response[1];
                            editPrice.value = response[2];
                        }
                    });
                });
            });
        });

        const editPriceService = () => {
            $.ajax({
                url: 'editService.php',
                type: 'POST',
                data: {
                    checkData: true,
                    editService: editService.value,
                    editPrice: editPrice.value
                },
                success: response => {
                    response = JSON.parse(response);
                    validPrice = true;
                    validService = true;

                    for(i=0; i < response.length; i++){
                        if(response[i].includes('empty service')){
                            validService = false;
                            editService.classList.add('is-invalid');
                            editErr.innerHTML = '*You must fill out this input!';
                        } 

                        if(response[i].includes('empty price')){
                            validPrice = false;
                            editPrice.classList.add('is-invalid');
                            editPriceErr.innerHTML = '*You must fill out this input!';
                        } 
                        
                        if(validService){
                            validService = true;
                            editService.classList.remove('is-invalid');
                            editErr.innerHTML = '';
                        }

                        if(validPrice){
                            validPrice = true;
                            editPrice.classList.remove('is-invalid');
                            editPriceErr.innerHTML = '';
                        }
                        
                        if(response[i].includes('inserted')){
                            window.location.reload();
                        }
                    }
                }
            });
        }
 
        editServicePrice.addEventListener('click', editPriceService);
        
    </script>
</body>

</html>