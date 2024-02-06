<?php
require_once __DIR__ . '/../../middleware/middleware-user.php';
require_once __DIR__ . '/../../repositories/expensesRepository.php';
require_once __DIR__ . '/../../repositories/filterExpenses.php';
@require_once __DIR__ . '/../../validations/session.php';
$user = user();

$expenseCategoryFilter = isset($_POST['expenseCategoryFilter']) ? $_POST['expenseCategoryFilter'] : '';
$paymentMethodsFilter = isset($_POST['paymentMethodsFilter']) ? $_POST['paymentMethodsFilter'] : '';
$expenseDescriptionFilter = isset($_POST['expenseDescriptionFilter']) ? $_POST['expenseDescriptionFilter'] : '';
$dateFilter = isset($_POST['dateFilter']) ? $_POST['dateFilter'] : '';
$paidAmountFilter = isset($_POST['paidAmountFilter']) ? $_POST['paidAmountFilter'] : '';
$orderDate = isset($_POST['orderDate']) ? $_POST['orderDate'] : '';
$orderPaidAmount = isset($_POST['orderPaidAmount']) ? $_POST['orderPaidAmount'] : '';
$paymentStatusFilter = isset($_POST['paymentStatusFilter']) ? $_POST['paymentStatusFilter'] : '';

if (!empty($expenseCategoryFilter)) {
    $expenses = getExpensesByExpenseCategoryByUserID($user['userID'], $expenseCategoryFilter);
} elseif (!empty($paymentMethodsFilter)) {
    $expenses = getExpensesByPaymentMethodByUserID($user['userID'], $paymentMethodsFilter);
} elseif (!empty($expenseDescriptionFilter)) {
    $expenses = getExpensesByExpenseDescription($user['userID'], $expenseDescriptionFilter);
} elseif (!empty($dateFilter)) {
    $expenses = getExpensesByPaymentDate($user['userID'], $dateFilter);
} elseif (!empty($paidAmountFilter)) {
    $expenses = getExpensesByPaidAmount($user['userID'], $paidAmountFilter);
} elseif (!empty($paymentStatusFilter)) {
    $expenses = getExpensesByPaymentStatus($user['userID'], $paymentStatusFilter);
} else {
    $expenses = getAllExpensesByUserID($user['userID']);
}

if ($orderDate == 'asc') {
    usort($expenses, function ($a, $b) {
        $dateA = new DateTime($a['paymentDate']);
        $dateB = new DateTime($b['paymentDate']);
        return $dateA <=> $dateB;
    });
} elseif ($orderDate == 'desc') {
    usort($expenses, function ($a, $b) {
        $dateA = new DateTime($a['paymentDate']);
        $dateB = new DateTime($b['paymentDate']);
        return $dateB <=> $dateA;
    });
}

if ($orderPaidAmount == 'asc') {
    array_multisort(array_column($expenses, 'paidAmount'), SORT_ASC, $expenses);
} elseif ($orderPaidAmount == 'desc') {
    array_multisort(array_column($expenses, 'paidAmount'), SORT_DESC, $expenses);
}
?>

<?php include __DIR__ . '/sidebar.php'; ?>
<link rel="icon" href="../../landingPage/assets/images/icon-1.png" type="image/x-icon">
<link rel="stylesheet" href="../resources/styles/card.css">

<div class="p-4 overflow-auto h-100">
    <nav style="--bs-breadcrumb-divider:'>';font-size:14px">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><i class="fa-solid fa-house"></i></li>
            <li class="breadcrumb-item">User Dashboard</li>
            <li class="breadcrumb-item">Expenses</li>
            <li class="breadcrumb-item">My Expenses</li>
        </ol>
    </nav>

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

    <div class="row mb-3">
        <div class="col-12 col-md-1 my-2 mb-2">
            <button class="btn btn-brown" data-bs-toggle="modal" data-bs-target="#add-expense">
                <span class="fa fa-plus"></span>
            </button>
        </div>
        <div class="col-12 col-md-10 my-2 mb-2">
            <form id="searchForm" class="d-flex" method="post" action="">
                <div class="form-group me-2 flex-grow-1">
                    <input type="text" class="form-control" id="expenseDescriptionFilter" name="expenseDescriptionFilter" placeholder="Search by description" value="<?php echo $expenseDescriptionFilter; ?>">
                </div>
            </form>
        </div>
        <div class="col-12 col-md-1 my-2 mb-2">
            <div class="dropdown">
                <button class="btn btn btn-brown dropdown-toggle" type="button" id="filterDropdownButton" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-list"></i></button>
                <ul class="dropdown-menu ps-1" aria-labelledby="filterDropdownButton">
                    <li class="mx-2">
                        <form method="post" action="">
                            <div class="form-group">
                                ExpenseCategories:
                                <select class="form-select" id="expenseCategoryFilter" name="expenseCategoryFilter" onchange="this.form.submit()">
                                    <option value="">No Filter</option>
                                    <?php
                                        $expenseCategories = getAllExpenseCategories();
                                        foreach ($expenseCategories as $expenseCategory) {
                                            $selected = ($expenseCategoryFilter == $expenseCategory['expenseCategoryID']) ? 'selected' : '';
                                            echo "<option value='{$expenseCategory['expenseCategoryID']}' $selected>{$expenseCategory['expenseCategory']}</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                        </form>
                    </li>
                    <li class="mx-2">
                        <form method="post" action="">
                            <div class="form-group">
                                Payment Methods:
                                <select class="form-select" id="paymentMethodsFilter" name="paymentMethodsFilter" onchange="this.form.submit()"> <option value="">No Filter</option>
                                    <?php
                                        $paymentMethods = getAllPaymentMethods();
                                        foreach ($paymentMethods as $paymentMethod) {
                                            $selectedPaymentMethod = ($paymentMethodsFilter == $paymentMethod['paymentMethodID']) ? 'selected' : '';
                                            echo "<option value='{$paymentMethod['paymentMethodID']}' $selectedPaymentMethod>{$paymentMethod['paymentMethod']}</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                        </form>
                    </li>
                    <li class="mx-2">
                        <form method="post" action="">
                            <div class="form-group">
                                <input type="hidden" class="form-control" id="dateFilter" name="dateFilter" value="<?php echo $dateFilter; ?>">
                            </div>
                            <div class="form-group">
                                Payment Date:
                                <select class="form-select" id="orderDate" name="orderDate" onchange="this.form.submit()">
                                    <option value="asc" <?php echo ($orderDate == 'asc') ? 'selected' : ''; ?>>Most Recent</option>
                                    <option value="desc" <?php echo ($orderDate == 'desc') ? 'selected' : ''; ?>>Oldest</option>
                                </select>
                            </div>
                        </form>
                    </li>
                    <li class="mx-2">
                        <form method="post" action="">
                            <div class="form-group">
                                <input type="hidden" class="form-control" id="paidAmountFilter" name="paidAmountFilter" value="<?php echo $paidAmountFilter; ?>">
                            </div>
                            <div class="form-group">
                                Paid Amount:
                                <select class="form-select" id="orderPaidAmount" name="orderPaidAmount" onchange="this.form.submit()">
                                    <option value="asc" <?php echo ($orderPaidAmount == 'asc') ? 'selected' : ''; ?>>ASC</option>
                                    <option value="desc" <?php echo ($orderPaidAmount == 'desc') ? 'selected' : ''; ?>>DESC</option>
                                </select>
                            </div>
                        </form>
                    </li>
                    <li class="mx-2">
                        <form method="post" action="">
                            <div class="form-group">
                                Payment Status:
                                <select class="form-select" id="paymentStatusFilter" name="paymentStatusFilter"
                                    onchange="this.form.submit()">
                                    <option value="">All</option>
                                    <option value="Paid"<?php echo ($paymentStatusFilter == 'Paid') ? 'selected' : ''; ?>>Paid</option>
                                    <option value="Unpaid"<?php echo ($paymentStatusFilter == 'Unpaid') ? 'selected' : ''; ?>>Unpaid</option>
                                </select>
                            </div>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row row-cols-1 row-cols-md-3 g-3">
        <div class="d-flex justify-content-center w-100">
            <?php if (empty($expenses)) : ?>
            <strong>
                <p class="mt-3 justify-content-center text-center" style="color: red">No expenses found.</p>
            </strong>
            <?php endif; ?>
        </div>
        <?php foreach ($expenses as $expense) : ?>
        <div class="col">
            <div class="card style" id="expense-card-<?php echo $expense['expenseID']; ?>">
                <div class="row">
                    <div class="col m-2">
                        <h5 class="card-title"><?php echo $expense['expenseDescription']; ?></h5>
                    </div>
                    <div class="col">
                        <div class="justify-content-end align-items-center mt-2 mx-2">
                            <button type="button" class='btn btn-danger btn-sm float-end m-1' data-bs-toggle="modal" data-bs-target="#delete-expense<?= $expense['expenseID']; ?>"><i class="fas fa-trash-alt"></i></button>
                            <button type="button" class='btn btn-primary btn-sm float-end m-1' data-bs-toggle="modal" data-bs-target="#share-expense<?= $expense['expenseID']; ?>"><i class="fas fa-share"></i></button>
                            <button type="button" class='btn btn-secondary btn-sm float-end m-1' data-bs-toggle="modal" data-bs-target="#edit-expense<?= $expense['expenseID']; ?>"><i class="fas fa-pencil-alt"></i></button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col justify-content-center">
                            <p class="card-text"><strong>Expense Category:</strong>
                                <?php echo $expense['expense_category']; ?></p>
                            <?php if ($expense['isFullyPaid'] == 1) : ?>
                            <p class="card-text"><strong>Payment Method:</strong>
                                <?php echo $expense['payment_method']; ?></p>
                            <?php endif; ?>
                            <p class="card-text"><strong>Paid Amount:</strong> <?php echo $expense['paidAmount']; ?></p>
                            <p class="card-text"><strong>Fully Paid:</strong>
                                <?php echo ($expense['isFullyPaid'] == 1) ? 'Yes' : 'No'; ?></p>
                            <p class="card-text"><strong>Date:</strong> <?php echo $expense['paymentDate']; ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="edit-expense<?= $expense['expenseID']; ?>" tabindex="-1"
            aria-labelledby="edit-expense<?= $expense['expenseID']; ?>" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-title"> Edit Expense </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body pt-0">
                        <form action="../../controllers/expense/expense.php" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="expenseID" id="expenseID" value="<?php echo $expense['expenseID']; ?>">
                            <div class="form-group mt-3">
                                <label>Expense Description</label>
                                <input type="text" class="form-control" id="expenseDescription" name="expenseDescription" placeholder="Expense Description" value="<?= isset($expense['expenseDescription']) ? $expense['expenseDescription'] : '' ?>" required>
                            </div>
                            <div class="form-group mt-3">
                                <label>Expense Category</label>
                                <select class="form-control" id="expenseCategory" name="expenseCategory">
                                    <?php
                                        $expenseCategories = getAllExpenseCategories();
                                        foreach ($expenseCategories as $expenseCategory) {
                                            $selected = isset($expense['expenseCategoryID']) && $expense['expenseCategoryID'] == $expenseCategory['expenseCategoryID'] ? 'selected' : '';
                                            echo "<option value='{$expenseCategory['expenseCategoryID']}' $selected>{$expenseCategory['expenseCategory']}</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group mt-3">
                                <label>Payment Date</label>
                                <input type="date" class="form-control" id="paymentDate" name="paymentDate" value="<?= isset($expense['paymentDate']) ? $expense['paymentDate'] : '' ?>" required>
                            </div>
                            <div class="form-group mt-3">
                                <label>Paid Amount</label>
                                <input type="text" class="form-control" id="paidAmount" name="paidAmount" placeholder="Paid Amount" value="<?= isset($expense['paidAmount']) ? $expense['paidAmount'] : '' ?>" required>
                            </div>
                            <div class="form-check mt-3">
                                <input class="form-check-input" type="checkbox" name="isFullyPaid" id="isFullyPaid" <?= isset($expense['isFullyPaid']) && $expense['isFullyPaid'] == 1 ? 'checked' : '' ?>>
                                <label class="form-check-label">Fully paid for?</label>
                            </div>
                            <div class="form-group mt-3" id="paymentBox">
                                <label>Payment Method</label>
                                <select class="form-control" id="paymentMethod" name="paymentMethod">
                                    <?php
                                        $paymentMethods = getAllPaymentMethods();
                                        foreach ($paymentMethods as $paymentMethod) {
                                            $selectedPaymentMethod = isset($expense['paymentMethodID']) && $expense['paymentMethodID'] == $paymentMethod['paymentMethodID'] ? 'selected' : '';
                                            echo "<option value='{$paymentMethod['paymentMethodID']}' $selectedPaymentMethod>{$paymentMethod['paymentMethod']}</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group mt-3">
                                <label>Note</label>
                                <textarea class="form-control" id="expenseNotes" name="expenseNotes" placeholder="Expense Note"><?= isset($expense['expenseNotes']) ? $expense['expenseNotes'] : '' ?></textarea>
                            </div>
                            <button type="submit" class="btn btn-brown mt-3" name="user" value="edit">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="share-expense<?= $expense['expenseID']; ?>" tabindex="-1"
            aria-labelledby="share-expense<?= $expense['expenseID']; ?>" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Share Expense</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="../../controllers/expense/expense.php" method="post">
                            <input type="hidden" name="expenseID" id="expenseID" value="<?php echo $expense['expenseID']; ?>">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email of the user you want to share the expense with:</label>
                                <input type="email" class="form-control" id="emailAddress" name="emailAddress" required>
                            </div>
                            <button type="submit" name="user" value="share" class="btn btn-primary">Share</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="delete-expense<?= $expense['expenseID']; ?>" tabindex="-1"
            aria-labelledby="delete-expense<?= $expense['expenseID']; ?>" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Delete Expense</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="../../controllers/expense/expense.php" method="post">
                            <input type="hidden" name="expenseID" id="expenseID"
                                value="<?php echo $expense['expenseID']; ?>">
                            <div class="mb-3">
                                Do you want to DELETE this expense?
                            </div>
                            <button type="submit" name="user" value="delete" class="btn btn-danger">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <?php endforeach; ?>
    </div>

    <div class="modal fade" id="add-expense" tabindex="-1" aria-labelledby="modal-title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title"> Add An Expense </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-0">
                    <form action="../../controllers/expense/expense.php" method="post" enctype="multipart/form-data">
                        <div class="form-group mt-3">
                            <label>Expense Description</label>
                            <input type="text" class="form-control" id="expenseDescription" name="expenseDescription" placeholder="Your Expense Description Here" value="<?= isset($_POST['expenseDescription']) ? $_POST['expenseDescription'] : '' ?>" required>
                        </div>
                        <div class="form-group mt-3">
                            <label>Expense Category</label>
                            <select class="form-control" id="expenseCategory" name="expenseCategory">
                                <?php
                                    $expenseCategories = getAllExpenseCategories();
                                    foreach ($expenseCategories as $expenseCategory) {
                                        $selected = isset($_POST['expenseCategory']) && $_POST['expenseCategory'] == $expenseCategory['expenseCategoryID'] ? 'selected' : '';
                                        echo "<option value='{$expenseCategory['expenseCategoryID']}' $selected>{$expenseCategory['expenseCategory']}</option>";
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="form-group mt-3">
                            <label>Date</label>
                            <input type="date" class="form-control" id="paymentDate" name="paymentDate" value="<?= isset($_POST['paymentDate']) ? $_POST['paymentDate'] : '' ?>" required>
                        </div>
                        <div class="form-group mt-3">
                            <label>Expense Amount</label>
                            <input type="text" class="form-control" id="paidAmount" name="paidAmount" placeholder="Expense Amount" value="<?= isset($_POST['paidAmount']) ? $_POST['paidAmount'] : '' ?>" required>
                        </div>
                        <div class="form-check mt-3">
                            <input class="form-check-input" type="checkbox" name="isFullyPaid" id="isFullyPaid" <?= isset($_POST['isFullyPaid']) && $_POST['isFullyPaid'] == 'on' ? 'checked' : '' ?>>
                            <label class="form-check-label">Fully paid for?</label>
                        </div>
                        <div class="form-group mt-3" id="paymentBox">
                            <label>Payment Method</label>
                            <select class="form-control" id="paymentMethod" name="paymentMethod">
                                <?php
                                    $paymentMethods = getAllPaymentMethods();
                                    foreach ($paymentMethods as $paymentMethod) {
                                        $selectedMethod = isset($_POST['paymentMethod']) && $_POST['paymentMethod'] == $paymentMethod['paymentMethodID'] ? 'selected' : '';
                                        echo "<option value='{$paymentMethod['paymentMethodID']}' $selectedMethod>{$paymentMethod['paymentMethod']}</option>";
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="form-group mt-3">
                            <label>Expense Note</label>
                            <textarea class="form-control" id="expenseNotes" name="expenseNotes" placeholder="Expense Notes"><?= isset($_POST['expenseNotes']) ? $_POST['expenseNotes'] : '' ?></textarea>
                        </div>
                        <button type="submit" class="btn btn-brown mt-3" name="user" value="add">Create Expense</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const isFullyPaidCheckbox = document.getElementById('isFullyPaid');
        const paymentBox = document.getElementById('paymentBox');

        paymentBox.style.display = isFullyPaidCheckbox.checked ? 'block' : 'none';

        isFullyPaidCheckbox.addEventListener('change', function() {
            paymentBox.style.display = this.checked ? 'block' : 'none';
        });

        var form = document.getElementById("searchForm");
        var inputElement = document.getElementById("expenseDescriptionFilter");
    });
</script>