 <?php
    include('../config.php');
    if (!isset($_SESSION['admin'])) {
        header("Location: login.php");
    }
    ?>
 <?php include('header.php') ?>
 <title>Doktoret</title>
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
             <li class="nav-item"><a href="doktoret.php" class="nav-link active" aria-current="page">Doktoret</a></li>
             <li><a href="departamentet.php" class="nav-link text-white">Departamentet</a></li>
             <li><a href="orari.php" class="nav-link text-white">Orari</a></li>
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
        $sql = "SELECT * FROM departamentet";
        $stm = $con->prepare($sql);
        $stm->execute();
        $data = $stm->fetchAll();
        ?>

     <main class="text-center main mainRes">
         <div class="back_wrapper"></div>
         <article class="popDocAdd rounded" id="popDocAdd">
             <div class="d-flex justify-content-between">
                 <h3 class="text-center text-uppercase ms-3">Shto nje doktor</h3>
                 <button type="button" class="closePopAdd"><i class="fa-regular fa-circle-xmark"></i></button>
             </div>
             <div class="editForm_wrapper">
                 <form class="editForm addForm" autocomplete="off" method="POST" enctype="multipart/form-data">
                     <div class="d-flex">
                         <div class="form-floating">
                             <input type="text" class="form-control name" id="floatingInput" name="fullName" placeholder="Emri i plote" value="">
                             <label for="floatingInput">Emri i plote</label>
                             <span class="text-danger fw-normal nameError"></span>
                         </div>
                         <div>
                             <select class="form-select departament" aria-label="Default select example" name="departament">
                                 <option selected value="">Zgjidhni departamentin</option>
                                 <?php foreach ($data as $data) : ?>
                                     <option value="<?= $data['departamenti']; ?>"><?= $data['departamenti']; ?></option>
                                 <?php endforeach; ?>
                             </select>
                             <span class="text-danger fw-normal departamentError"></span>
                         </div>
                     </div>
                     <div>
                         <div class="mb-1">
                             <select class="form-select gender" aria-label="Default select example" name="gender">
                                 <option value="">Zgjedhni gjinin tuaj</option>
                                 <option value="Mashkull">Mashkull</option>
                                 <option value="Femer">Femer</option>
                             </select>
                             <span class="text-danger fw-normal genderError"></span>
                         </div>

                         <div class="form-floating">
                             <input type="email" class="form-control rounded email " id="floatingInput" name="email" placeholder="name@example.com" value="">
                             <label for="floatingInput">Email</label>
                             <span class="text-danger fw-normal emailError"></span>
                         </div>
                     </div>

                     <div class="d-flex">

                         <div class="mb-3">
                             <label for="formFile" class="form-label ">Foto</label>
                             <input class="form-control photo" type="file" name="my_image" id="formFile">
                             <span class="text-danger fw-normal photoError"></span>
                         </div>

                         <div class="form-floating mt-3">
                             <input type="tel" class="form-control phone" id="floatingInput" name="phone" placeholder="Numri i telefonit" value="">
                             <label for="floatingInput">Numri i telefonit</label>
                             <span class="text-danger fw-normal phoneError"></span>
                         </div>
                     </div>

                     <div class="d-flex">

                         <div class="form-floating">
                             <input type="text" class="form-control username" id="floatingInput" name="username" placeholder="Username" value="">
                             <label for="floatingInput">Username</label>
                             <span class="text-danger fw-normal usernameError"></span>
                         </div>

                         <div class="form-floating">
                             <input type="password" class="form-control password" id="floatingPassword" name="password" placeholder="Fjalkalimi">
                             <label for="floatingPassword">Fjalkalimi</label>
                             <span class="text-danger fw-normal passwordError"></span>
                         </div>
                     </div>
                     <button class="w-100 btn btn-lg btn-primary mt-1 register" type="button" name="register">Shto</button>
                 </form>
             </div>
         </article>
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


        $countSql = "SELECT COUNT(*) as total FROM doctor_personal_info";
        $countPrep = $con->prepare($countSql);
        $countPrep->execute();
        $totalRows = $countPrep->fetch();

        $totalRows = $totalRows['total'];

        $totalPages = ceil($totalRows / $entries);

        $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;

        $startIndex = ($currentPage - 1) * $entries;


        if ($sortBy == "default") {
            $sort = " ORDER BY fullName ASC LIMIT :startIndex, $entries";
            $sortASC = 'selected';
            $searchedQuery = isset($_GET['keyword']) ? $_GET['keyword'] : "";
        } else if ($sortBy == "ASC") {
            $sort = " ORDER BY fullName ASC LIMIT :startIndex, $entries";
            $sortASC = 'selected';
            $searchedQuery = isset($_GET['keyword']) ? $_GET['keyword'] : "";
        } else if ($sortBy == "DESC") {
            $sort = " ORDER BY fullName DESC LIMIT :startIndex, $entries";
            $sortDESC = 'selected';
            $searchedQuery = isset($_GET['keyword']) ? $_GET['keyword'] : "";
        }


        $keywordPrep;
        if (isset($_GET['search']) && !empty($_GET['keyword'])) {
            $keyword = $_GET['keyword'];

            $sort = "SELECT * FROM doctor_personal_info WHERE fullName=:keyword OR departamenti=:keyword OR 
                username=:keyword OR email=:keyword OR telefoni=:keyword " . $sort;
            $sql = $sort;

            $prep = $con->prepare($sql);
            $prep->bindParam(':keyword', $keyword);
            $prep->bindValue(':startIndex', $startIndex, PDO::PARAM_INT);
            $prep->execute();
            $data = $prep->fetchAll(PDO::FETCH_ASSOC);

            $searchedQuery = $keyword;
        } else {
            $sql = "SELECT * FROM doctor_personal_info" . $sort;
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



     <article class="main d-flex flex-column p-2">
         <div class="d-flex justify-content-between">
             <div>
                 <form id="entriesForm" method="GET" class="d-flex align-items-center w-25" action="doktoret.php">
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
                     <form id="sortForm" method="GET" class="d-flex align-items-center" action="doktoret.php">
                         <input type="hidden" name="entries" value="<?= $entries ?>">
                         <input type="hidden" name="page" value="<?= $currentPage ?>">
                         <select class="form-select" id="sortBy" name="sortBy" aria-label="Default select example" style="height: 58px" onchange="this.form.submit()">
                             <option value="ASC" <?= $sortASC ?? "" ?>>Sipas renditjes A-Zh</option>
                             <option value="DESC" <?= $sortDESC ?? "" ?>>Sipas renditjes Zh-A</option>
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
                     <form method="get" action="doktoret.php">
                         <input type="hidden" name="entries" value="<?= $entries ?>">
                         <input type="hidden" name="sortBy" value="<?= $sortBy ?>">
                         <input type="hidden" name="page" value="<?= $currentPage ?>">
                         <div class="d-flex mb-1">
                             <div class="form-floating w-75">
                                 <input type="text" class="form-control lastName" id="floatingInput" name="keyword" placeholder="Kerkro:" value="<?= $searchedQuery ?>">
                                 <label for="floatingInput">Kerko:</label>
                             </div>
                             <button class="btn btn-primary fs-5 ms-2" name="search">Kerko</button>
                             <button class="btn btn-primary ms-2 addDoc" type="button" title="Add Doctor">+</button>
                         </div>
                     </form>
                 </div>
             </div>
         </div>
         <?php if ($empty == '') : ?>
             <table class="table table-striped table_doc table_patient text-center table_doctors">
                 <thead>
                     <tr>
                         <th scope="col" style="display: none;">ID</th>
                         <th scope="col">Doktori</th>
                         <th scope="col">Departamenti</th>
                         <th scope="col">Username</th>
                         <th scope="col">Aksioni</th>
                     </tr>
                 </thead>
                 <tbody>
                     <?php foreach ($data as $data) : ?>
                         <tr>
                             <td class="idShow" style="display: none;"><?= $data['id']; ?></td>
                             <td><?= $data['fullName'] ?></td>
                             <td><?= $data['departamenti'] ?></td>
                             <td><?= $data['username'] ?></td>
                             <td>
                                 <a class="text-decoration-none  text-white showPop">
                                     <button class="btn btn-primary  text-white " id="showPop">Shiko</button>
                                 </a>
                                 <a href="editUser.php?id=<?= $data['id']  ?>" class="text-decoration-none  text-white showEditPop">
                                     <button class="btn btn-warning  text-white">Ndrysho</button>
                                 </a>
                                 <a href="deleteUser.php?id=<?= $data['id']  ?>" class="text-decoration-none  text-white">
                                     <button class="btn btn-danger text-white">Fshij</button>
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
             <div class="imagePagination justify-content-start ms-2">
                 <?php
                    $maxVisibleLinks = 5; // Maximum number of visible page links

                    $startPage = max(1, $currentPage - floor($maxVisibleLinks / 2));
                    $endPage = min($startPage + $maxVisibleLinks - 1, $totalPages);

                    $showEllipsisStart = ($startPage > 1);
                    $showEllipsisEnd = ($endPage < $totalPages);

                    if ($showEllipsisStart) {
                        echo '<a href="?page=1" class="paginationLink">1</a>';
                        echo '<span class="ellipsis">...</span>';
                    }

                    if ($currentPage > 1) {
                        $previousPage = $currentPage - 1;
                        echo '<a href="?page=' . $previousPage . '" class="paginationLink"><</a>';
                    }

                    for ($i = $startPage; $i <= $endPage; $i++) {
                        $activePage = ($i == $currentPage) ? 'activePage' : '';
                        echo '<a class="paginationLink ' . $activePage . '" href="?page=' . $i . '">' . $i . '</a> ';
                    }

                    if ($showEllipsisEnd) {
                        echo '<span class="ellipsis">...</span>';
                        echo '<a href="?page=' . $totalPages . '" class="paginationLink">' . $totalPages . '</a>';
                    }

                    if ($currentPage < $totalPages) {
                        $nextPage = $currentPage + 1;
                        echo '<a href="?page=' . $nextPage . '" class="paginationLink">></a>';
                    }
                    ?>
             </div>
         <?php } ?>
     </article>



     <article class="popDoc" id="popDoc">

     </article>


     <article class="popDoc_info" id="popDoc_info">
         <div class="pac_h5">
             <h5>Doctor information</h5>
             <button id="close" class="close">
                 <i class="fa-solid fa-close rezervoClose"></i>
             </button>
         </div>

         <div class='doc_wrapper'>

     </article>




     <!-- External scripts
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
     <script src="../bootstrap-5.1.3-examples/sidebars/sidebars.js"></script> -->
     <!-- Font-awesome script -->
     <script src="https://kit.fontawesome.com/a28016bfcd.js" crossorigin="anonymous"></script>
     <!-- JQuery link -->
     <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
     <script src="../js/showDocInfo.js"></script>
     <script src="../js/DoctorProfile.js"></script>
     <script src="../js/addDoctor.js"></script>
 </body>

 </html>