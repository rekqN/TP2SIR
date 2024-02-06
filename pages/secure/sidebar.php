<?php
    require_once __DIR__ . '/../../middleware/middleware-user.php';
    @require_once __DIR__ . '/../../validations/session.php';
    $user = user();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@300;400;500&display=swap"/>
    <link rel="stylesheet" href="../pageResources/styles/globalStyling.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<style>
@import url('/../pageResources/globalStyling.css');
html,
body {
    height: 100%;
    font-family: 'Ubuntu', sans-serif;
}

.mynav {
    color: var(--very-light-brown);
}

.mynav li a {
    color: var(--very-light-brown);
    text-decoration: none;
    width: 100%;
    display: block;
    border-radius: 5px;
    padding: 8px 5px;
}

.mynav li .btn-side:hover {
    background: rgba(255, 255, 255, 0.2);
}

.mynav li a i {
    width: 25px;
    text-align: center;
}

.bg-color {
    --bs-bg-opacity: 1;
    background-color: #333 !important;
}
</style>

<body>
    <div class="container-fluid p-0 d-flex h-100">
        <div id="bdSidebar"
            class="d-flex flex-column flex-shrink-0 p-3 bg-color text-white offcanvas-md offcanvas-start"
            style="width: 270px;">
            <a href="#" class="navbar-brand">
                <h5 class="h6" style="color: var(--very-light-brown)"><i class="fa-solid fa-wallet me-2" style="font-size: 18px;"></i>SPENDWISE</h5>
            </a>
            <hr>
            <ul class="mynav nav nav-pills flex-column mb-auto">
                <li class="nav-item mb-3">
                    <a href="./userDashboard.php" class="btn-side active">
                        <i class="fa-solid fa-home"></i>
                        DashBoard
                    </a>
                </li>
                <li class="nav-item mb-3">
                    <a href="#" class="btn-side" data-bs-toggle="collapse" data-bs-target="#expensesDropdownMenu">
                        <i class="fa-solid fa-chart-simple"></i>
                        Expenses
                    </a>
                    <div class="collapse" id="expensesDropdownMenu">
                        <ul class="nav flex-column">
                            <li class="nav-item mb-2">
                                <a href="./expensePage.php" class="mx-3">
                                    My Expenses
                                </a>
                            </li>
                            <li class="nav-item mb-2">
                                <a href="./sharedExpensePage.php" class="mx-3">
                                    Shared Expenses
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item mb-3">
                    <a href="./expenseCalendarPage.php" class="btn-side">
                        <i class="fa-solid fa-calendar"></i>
                        Expenses Calendar
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="./profilePage.php" class="btn-side">
                        <i class="fa-solid fa-user"></i>
                        Profile
                    </a>
                </li>
                <li class="nav-item py-1 mask <?= $user['isAdmin'] ? '' : 'd-none'; ?>">
                    <a href="#" class="nav-link text-white btn-side" data-bs-toggle="collapse"
                        data-bs-target="#adminDropdownMenu" aria-expanded="false">
                        <i class="fa-solid fa-users" title="Admin"></i>
                        Admin
                    </a>
                    <div class="collapse" id="adminDropdownMenu">
                        <ul class="nav flex-column">
                            <li class="nav-item mb-2">
                                <a href="./admin-stats.php" class="mx-3">
                                    Dashboard
                                </a>
                            </li>
                            <li class="nav-item mb-2">
                                <a href="./admin-users.php" class="mx-3">
                                    Manage Users
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
            <hr>
            <div class="d-flex align-items-center">
                <div class="d-flex">
                    <?php if (!empty($user['avatar'])): ?>
                    <?php
                            $avatarData = base64_decode($user['avatar']);
                            $avatarSource = 'data:image/jpeg;base64,' . base64_encode($avatarData);
                        ?>
                    <img src="<?= $avatarSource ?>" alt="avatar" class="img-fluid rounded-circle me-2" width="50px">
                    <?php else: ?>
                    <i class="fas fa-user-circle fa-3x me-2 text-secondary"></i>
                    <?php endif;?>

                    <span>
                        <h6 class="mt-2 mb-0" style="color: var(--very-light-brown); font-size: 20px;"><?= mb_strlen($user['firstName'] ?? '') > 16 ? substr($user['firstName'], 0, 16) . '...' : $user['firstName'] ?? 'Guest' ?></h6>
                        <small><?= $user['emailAdrress'] ?? null ?></small>
                    </span>
                </div>

                <div class="ms-auto">
                    <form action="../../controllers/auth/signin.php" method="post">
                        <button class="btn btn-outline-danger btn-sm" type="submit" name="user" value="logout">
                            <i class="fas fa-sign-out-alt" style="color: var(--very-light-brown); font-size: 15px;"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="bg-light flex-fill">
            <div class="p-2 position-fixed d-md-none d-flex text-white bg-black" style="width:100%; z-index:999">
                <a href="#" class="text-white" data-bs-toggle="offcanvas" data-bs-target="#bdSidebar">
                    <i class="fa-solid fa-bars"></i>
                </a>
                <span class="ms-3" style="font-size: 16px; color: blueviolet">EXPENSE FLOW</span>
            </div>