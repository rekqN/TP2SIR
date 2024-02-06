<?php
    require_once __DIR__ . '/../../middleware/middleware-user.php';
    @require_once __DIR__ . '/../../validations/session.php';
    @require_once __DIR__ . '/../../repositories/userDashboardRepository.php';
    $userID = user();

    $countExpenses = countExpensesByUserID($userID['userID']);
    $fullyPaidExpenses = countFullyPaidExpensesByUserID($userID['userID']);
    $sharedExpensesCountByMe = countSharedExpensesByFromUserID($userID['userID']);
    $sharedExpensesCountToMe = countSharedExpensesBySentoToUserID($userID['userID']);
    $sumExpensesAmount = getExpensesAmountByUserID($userID['userID']);
    $sumSharedExpensesAmount = getSharedExpensesAmountByUserID($userID['userID']);
    $futureExpenses = countFutureExpensesByUserID($userID['userID']);
    $futureExpensesDetails = getFutureExpensesDetailsByUserID($userID['userID']);
?>

<?php include __DIR__ . '/sidebar.php'; ?>

<style>
@import url('/../pageResources/globalStyling.css');
.style {
    background-color: white;
    background: var(--very-light-brown);
    border-radius: 50px;
}
</style>
<link rel="icon" href="../../landingPage/assets/images/icon-1.png" type="image/x-icon">
<div class="p-4 overflow-auto h-100">
    <nav style="--bs-breadcrumb-divider:'>';font-size:14px">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><i class="fa-solid fa-house"></i></li>
            <li class="breadcrumb-item">User Dashboard</li>
        </ol>
    </nav>

    <div class="row row-cols-1 row-cols-md-3 g-2">
        <div class="col">
            <div class="card style">
                <div class="card-body">
                    <h6 class="card-title">Number of expenses: <?php echo $countExpenses; ?></h6>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card style">
                <div class="card-body">
                    <?php
                        $displayAmount = isset($sumExpensesAmount) && $sumExpensesAmount !== '' ? $sumExpensesAmount : '0';
                    ?>
                    <h6 class="card-title">Expense's Amount: <?php echo $displayAmount; ?>€</h6>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card style">
                <div class="card-body">
                    <h6 class="card-title">Number of paid expenses: <?php echo $fullyPaidExpenses; ?></h6>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card style">
                <div class="card-body">
                    <h6 class="card-title">Number of shared expenses: <?php echo $sharedExpensesCountByMe; ?></h6>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card style">
                <div class="card-body">
                    <h6 class="card-title">Amount of shared expenses: <?php echo $sumSharedExpensesAmount; ?>€</h6>
                </div>
            </div>
        </div>  

        <div class="col">
            <div class="card style">
                <div class="card-body">
                    <h6 class="card-title">Number of expenses shared with you: <?php echo $sharedExpensesCountToMe; ?>
                    </h6>
                </div>
            </div>
        </div>
    </div>