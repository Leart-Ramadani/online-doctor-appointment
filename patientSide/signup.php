<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign up</title>
    <link rel="shortcut icon" href="../photos/icon-hospital.png">
    <link rel="stylesheet" href="../css/main.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous" defer></script>
    <!-- JQuery link -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js" defer></script>
    <script src="../js/signUp.js" defer></script>

    <style>
        html,
        body {
            height: 100%;
        }

        body {
            display: flex;
            align-items: center;
            padding-top: 40px;
            padding-bottom: 40px;
            background-color: #f5f5f5;
        }

        .form-signin {
            width: 100%;
            max-width: 330px;
            padding: 15px;
            margin: auto;
        }

        .form-signin .checkbox {
            font-weight: 400;
        }

        .form-signin .form-floating:focus-within {
            z-index: 2;
        }

        .form-signin input[type="email"] {
            margin-bottom: -1px;
            border-bottom-right-radius: 0;
            border-bottom-left-radius: 0;
        }

        .form-signin input[type="password"] {
            margin-bottom: 10px;
            border-top-left-radius: 0;
            border-top-right-radius: 0;
        }

        .form-check {
            margin-top: 10px;
            margin-bottom: 10px;
        }

        .ditlindja {
            width: 300px;
            height: 50px;
        }

        .tab {
            display: none;
        }
    </style>
</head>

<body class="text-center">

    <main class="form-signin">
        <form method="POST" autocomplete="off" action="validateForm.php" id="registerForm">
            <h1 class="h3 mb-3 fw-normal register">Sign up</h1>

            <!-- First tab of register inputs (name, lastName, personal ID, gender) -->
            <div class="tab">
                <div class="form-floating mb-1">
                    <input type="text" class="form-control name" id="floatingInput name" name="name" placeholder="Name">
                    <label for="floatingInput">Name</label>
                    <span class="text-danger fw-normal nameErrorr"></span>
                </div>

                <div class="form-floating mb-1">
                    <input type="text" class="form-control lastName" id="floatingInput" name="surname" placeholder="Last name">
                    <label for="floatingInput">Last name</label>
                    <span class="text-danger fw-normal lastNameErr"></span>
                </div>

                <div class="form-floating mb-1">
                    <input type="text" class="form-control personal_ID" id="floatingInput" name="personal_id" placeholder="Personal ID" maxlength="10">
                    <label for="floatingInput">Personal ID</label>
                    <span class="text-danger fw-normal personal_id_err"></span>
                </div>

                <div class="mb-1">
                    <select class="form-select gender " aria-label="Default select example" name="gender">
                        <option value="">Select gender</option>
                        <option value="Mashkull">Mashkull</option>
                        <option value="Femer">Femer</option>
                    </select>
                    <span class="text-danger fw-normal genderErr"></span>
                </div>

            </div>

            <!-- Second tab of register inputs (email, birthday, phone number, adress) -->
            <div class="tab">
                <div class="form-floating mb-1">
                    <input type="email" class="form-control rounded email" id="floatingInput" name="email" placeholder="name@example.com">
                    <label for="floatingInput">Email</label>
                    <span class="text-danger fw-normal emailErr"></span>
                </div>

                <div class="mb-1">
                    <label for="startDate">Birthday:</label>
                    <input id="startDate" class="form-control birthday ditlindja" name="birthday" type="date" />
                    <span class="text-danger fw-normal birthdayErr"></span>
                </div>

                <div class="form-floating mb-1">
                    <input type="tel" class="form-control phone" id="floatingInput" name="phone" placeholder="Phone number">
                    <label for="floatingInput">Phone number</label>
                    <span class="text-danger fw-normal phoneErr"></span>
                </div>

                <div class="form-floating mb-1">
                    <input type="text" class="form-control adress" id="floatingInput" name="adress" placeholder="Adress">
                    <label for="floatingInput">Adress</label>
                    <span class="text-danger fw-normal adressErr"></span>
                </div>
            </div>


            <!-- Third and the last tab of register inputs (username, password, confirm password) -->
            <div class="tab">
                <div class="form-floating mb-1">
                    <input type="text" class="form-control username" id="floatingInput" name="username" placeholder="Username">
                    <label for="floatingInput">Username</label>
                    <span class="text-danger fw-normal usernameErr"></span>
                </div>

                <div class="form-floating mb-1">
                    <input type="password" class="form-control password rounded mb-0" id="floatingPassword" name="password" placeholder="Password">
                    <label for="floatingPassword">Password</label>
                    <span class="text-danger fw-normal passwordErr"></span>
                </div>

                <div class="form-floating">
                    <input type="password" class="form-control confirmPass rounded mb-0" id="floatingPassword" name="confirmPass" placeholder="Confirm password">
                    <label for="floatingPassword">Confirm password</label>
                    <span class="text-danger fw-normal confirmPassErr"></span>
                </div>

            </div>

            <div class="tab loader"></div>

            <!-- Register form action buttons -->
            <div class="d-flex justify-content-end mt-2 ">
                <button class="w-25 btn btn-primary me-2 prev" type="button">Prev</button>
                <button class="w-25 btn btn-primary next" type="button">Next</button>
            </div>
            <p class="mt-2 loginLink">Already have an account? <a href="login.php">Log in</a></p>
        </form>
    </main>
</body>

</html>