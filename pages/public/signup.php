<?php
require_once __DIR__ . '/../../middleware/middleware-not-authenticated.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SpendWise - Sign up</title>
  <link rel="icon" href="../../landingPage/assets/images/icon-1.png" type="image/x-icon">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="../pageResources/styles/globalStyling.css"/>
  <link rel="stylesheet" href="../pageResources/styles/publicPages.css"/>
</head>

<body class="py-5">
  <section class="py-4 px-5">
    <?php
    if (isset($_SESSION['success'])) {
      echo '<div class="alert alert-success alert-dismissible fade show" role="alert">';
      echo $_SESSION['success'] . '<br>';
      echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
      unset($_SESSION['success']);
    }
    if (isset($_SESSION['errors'])) {
      echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
      foreach ($_SESSION['errors'] as $error) {
        echo $error . '<br>';
      }
      echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
      unset($_SESSION['errors']);
    }
    ?>
  </section>
  
  <div class="px-4 py-5 px-md-5 text-center text-lg-start">
    <div class="container">
      <div class="row gx-lg-5 align-items-center">
        <div class="col-lg-6 mb-5 mb-lg-0">
          <div class="d-flex justify-content-center rounded spendWiseLink">
            <a class="text-decoration-none spendWiseLink" href="../../landingPage"><h1 class="fw-bold">SPENDWISE</h1></a>
          </div>
        </div>
        <div class="col-lg-6 mb-5 mb-lg-0">
          <div class="card">
            <div class="card-body py-5 px-md-5">
              <form action="../../controllers/auth/signup.php" method="post">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-outline mb-3">
                        <label class="mb-2" for="firstName">First Name</label>
                        <input type="text" class="form-control" name="firstName" placeholder="First Name" maxlength="100" size="100" value="<?= isset($_REQUEST['firstName']) ? $_REQUEST['firstName'] : null ?>" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-outline mb-3">
                        <label class="mb-2" for="lastName">Last Name</label>
                        <input type="text" class="form-control" name="lastName" placeholder="Last Name" maxlength="100" size="100" value="<?= isset($_REQUEST['lastName']) ? $_REQUEST['lastName'] : null ?>" required>
                    </div>
                  </div>
                </div>
                <div class="form-outline mb-3">
                  <label class="mb-2" for="floatingInput">Email Address</label>
                  <input type="email" class="form-control" id="floatingInput" name="emailAddress" placeholder="spend@wise.com" value="<?= isset($_REQUEST['emailAddress']) ? $_REQUEST['emailAddress'] : null ?>">
                </div>
                <div class="form-outline mb-3">
                  <label class="mb-2" for="password">Password</label>
                  <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                </div>
                <div class="form-outline mb-3">
                  <label class="mb-2" for="confirmPassword">Confirm Password</label>
                  <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" placeholder="Confirm password">
                </div>
                <div class="d-flex justify-content-center mb-3">
                  <button class="w-50 btn btn-lg btn-brown mb-2 signInUpText" type="submit" name="user" value="signUp">Sign Up</button>
                </div>
                <div class="d-flex justify-content-center mb-2">
                  <label class="d-flex">Already have an account? Log in <a class="hereLink" href="./signin.php"> here</a>.</label>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>