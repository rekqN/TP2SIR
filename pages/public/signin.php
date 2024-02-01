<?php
require_once __DIR__ . '/../../middleware/middleware-not-authenticated.php';
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SpendWise - Sign In</title>
    <link rel="icon" href="../../landingPage/assets/images/icon-1.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../pageResources/styles/globalStyling.css">
    <link rel="stylesheet" href="../pageResources/styles/publicPages.css"/>
  </head>
    
  <body style="background-image: url('../../landingPage/assets/images/hero-2.jpg')" class="py-5">
    <section class="py-4 px-5">
      <?php
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
            <div class="d-flex justify-content-center">
              <a class="text-decoration-none" href="../../landingPage">
                <h1 class="fw-bold" style="color: var(--c-brand)">SPEND WISE</h1>
              </a>
            </div>
          </div>

          <div class="col-lg-6 mb-5 mb-lg-0">
            <div class="card">
              <div class="card-body py-5 px-md-5" >
                <form action="../../controllers/auth/signin.php" method="post">
                  <div class="form-outline mb-4">
                    <label class="mb-2" for="Email">Email Adress</label>
                    <input type="email" class="form-control" id="Email" placeholder="Email" name="email" maxlength="255" value="<?= isset($_REQUEST['emailAdress']) ? $_REQUEST['emailAddress'] : null ?>">
                  </div>
                  <div class="form-outline mb-4">
                    <label class="mb-2" for="password">Password</label>
                    <input type="password" class="form-control" id="password" placeholder="Password" name="password" maxlength="255" value="<?= isset($_REQUEST['password']) ? $_REQUEST['password'] : null ?>">
                  </div>
                  <div class="d-flex justify-content-center">
                    <button class="w-50 btn btn-lg btn-brown mb-4" type="submit" name="user" value="login">Sign In</button>
                  </div>
                  <div class="d-flex justify-content-center mb-2">
                    Don't have an account? Create one <a href="./signup.php" class="hereLink">here</a>.
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
