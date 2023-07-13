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
                                     <option value="<?= $data['name']; ?>"><?= $data['name']; ?></option>
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


        $countSql = "SELECT COUNT(*) as total FROM users WHERE userType=2";
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

            $sort = "SELECT u.id, u.fullName, u.gender, u.email, u.photo, u.username, u.password, d.name AS
                'dep_name' FROM users AS u INNER JOIN departamentet AS d ON u.departament = d.id 
                WHERE userType=2 AND (fullName=:keyword OR d.id='$dep' OR 
                username=:keyword OR email=:keyword OR phone=:keyword) LIMIT :startIndex, $entries";
            $sql = $sort;

            $prep = $con->prepare($sql);
            $prep->bindParam(':keyword', $keyword);
            $prep->bindParam(':keyword', $keyword);
            $prep->bindValue(':startIndex', $startIndex, PDO::PARAM_INT);
            $prep->execute();
            $data = $prep->fetchAll(PDO::FETCH_ASSOC);

            $searchedQuery = $keyword;
        } else {
            $sql = "SELECT u.id, u.fullName, u.gender, u.email, u.photo, u.username, u.password, d.name AS
            'dep_name' FROM users AS u INNER JOIN departamentet AS d ON u.departament = d.id 
            WHERE userType=2 LIMIT :startIndex, $entries";
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
         <div class="d-flex justify-content-between pt-2">
             <div>
                 <form id="entriesForm" method="GET" class="d-flex align-items-center w-25" action="doktoret.php">
                     <input type="hidden" name="page" value="<?= $currentPage ?>">
                     <label for="entries" class="me-2">Shfaq</label>
                     <select class="form-select" id="entries" aria-label="" name="entries" style="width: 80px; height: 38px" onchange="this.form.submit()">
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



             <div class="w-50 ms-2 me-1">
                 <form method="get" action="doktoret.php">
                     <input type="hidden" name="entries" value="<?= $entries ?>">
                     <input type="hidden" name="page" value="<?= $currentPage ?>">
                     <div class="d-flex mb-1">
                         <div class="input-group mb-3">
                             <input type="text" class="form-control lastName" placeholder="Kerkro:" aria-label="Kerkro:" aria-describedby="button-addon2" name="keyword" value="<?= $searchedQuery ?>">
                             <button class="btn btn-outline-primary" id="button-addon2" name="search"><i class="fa-solid fa-magnifying-glass"></i></button>
                         </div>
                         <section class="bg-primary ms-2 addDoc" type="button" title="Add Doctor">+</section>
                     </div>
                 </form>
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
                             <td><?= $data['dep_name'] ?></td>
                             <td><?= $data['username'] ?></td>
                             <td>
                                 <a class="text-decoration-none text-white showPop">
                                     <button class="btn btn-primary text-white" id="showPop">Shiko</button>
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