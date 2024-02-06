<?php
require_once __DIR__ . '../../../middleware/middleware-user.php';
@require_once __DIR__ . '/../../validations/session.php';
@require_once __DIR__ . '/../../repositories/adminDashboardRepository.php';
$userID = user();


$countDeletedUsers = countDeletedUsers();
$countActiveUsers = countActiveUsers();
$countUsersWithExpenses = countUsersWithExpenses();
$countUsersWithSharedExpenses = countUsersWithSharedExpenses();
$countExpensesByCategory = countExpensesByCategory();
$countExpensesByPaymentMethod = countExpensesByPaymentMethod();
$countTotalExpenses = countTotalExpenses();
$getTotalExpensesAmount = getTotalExpensesAmount();
?>

<?php include __DIR__ . '/sidebar.php'; ?>
<link rel="icon" href="../../landingPage/assets/images/icon-1.png" type="image/x-icon">
<div class="p-4 overflow-auto h-100">
    <nav style="--bs-breadcrumb-divider:'>';font-size:14px">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><i class="fa-solid fa-house"></i></li>
            <li class="breadcrumb-item">Admin</li>
            <li class="breadcrumb-item">Admin Dashboard</li>
        </ol>
    </nav>

    <section class="py-3 px-5">
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
        <hr class="w-100">
        <div class="col-md-6 col-lg-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Deleted Users</h5>
                    <p class="card-text"><?php echo $countDeletedUsers; ?></p>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-6 mb-4">
            <a class="text-decoration-none" href="./userManagementPage.php">
                <div class="card" style="cursor:pointer">
                    <div class="card-body">
                        <h5 class="card-title">Active Users</h5>
                        <p class="card-text"><?php echo $countActiveUsers; ?></p>
                    </div>
                </div>
            </a>
            
        </div>

        <div class="col-md-6 col-lg-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Users with expenses</h5>
                    <p class="card-text"><?php echo $countUsersWithExpenses; ?></p>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Users with shared expenses</h5>
                    <p class="card-text"><?php echo $countUsersWithSharedExpenses; ?></p>
                </div>
            </div>
        </div>
     
        <hr class="w-100">

        <div class="col-md-6 col-lg-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Count Expenses by Category</h5>
                    <ul class="list-group">
                        <?php foreach ($countExpensesByCategory as $countCategory) : ?>
                            <?php if ($countCategory['count_expense'] > 0) : ?>
                                <li class="list-group-item"><?php echo $countCategory['expense_category'] . ': ' . $countCategory['count_expense']; ?></li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Count Expenses by Payment Method</h5>
                    <ul class="list-group">
                        <?php foreach ($countExpensesByPaymentMethod as $countPaymentMethod) : ?>
                            <?php if ($countPaymentMethod['count_expense'] > 0) : ?>
                                <li class="list-group-item"><?php echo $countPaymentMethod['payment_method'] . ': ' . $countPaymentMethod['count_expense']; ?></li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Active Expenses Count</h5>
                    <p class="card-text"><?php echo $countTotalExpenses; ?></p>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Expenses Amount</h5>
                    <p class="card-text"><?php echo $getTotalExpensesAmount; ?></p>
                </div>
            </div>
        </div>
    </div>
</div>