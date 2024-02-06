<?php
require_once __DIR__ . '/../../middleware/middleware-user.php';
require_once __DIR__ . '/../../repositories/expensesRepository.php';
require_once __DIR__ . '/../../repositories/filterExpenses.php';
@require_once __DIR__ . '/../../validations/session.php';
$user = user();

$expenseCategoryFilter = isset($_POST['expenseCategoryFilter']) ? $_POST['expenseCategoryFilter'] : '';
$paymentMethodsFilter = isset($_POST['paymentMethodsFilter']) ? $_POST['paymentMethodsFilter'] : '';
$descriptionFilter = isset($_POST['descriptionFilter']) ? $_POST['descriptionFilter'] : '';
$dateFilter = isset($_POST['dateFilter']) ? $_POST['dateFilter'] : '';
$paidAmountFilter = isset($_POST['paidAmountFilter']) ? $_POST['paidAmountFilter'] : '';
$orderDate = isset($_POST['orderDate']) ? $_POST['orderDate'] : '';
$orderPaidAmount = isset($_POST['orderPaidAmount']) ? $_POST['orderPaidAmount'] : '';
$paymentStatusFilter = isset($_POST['paymentStatusFilter']) ? $_POST['paymentStatusFilter'] : '';

if (!empty($expenseCategoryFilter)) {
    $expenses = getExpensesByExpenseCategoryByUserID($user['userID'], $expenseCategoryFilter);
} elseif (!empty($paymentMethodsFilter)) {
    $expenses = getExpensesByPaymentMethodByUserID($user['userID'], $paymentMethodsFilter);
} elseif (!empty($descriptionFilter)) {
    $expenses = getExpensesByExpenseDescription($user['userID'], $descriptionFilter);
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
        $dateA = new DateTime($a['date']);
        $dateB = new DateTime($b['date']);
        return $dateA <=> $dateB;
    });
} elseif ($orderDate == 'desc') {
    usort($expenses, function ($a, $b) {
        $dateA = new DateTime($a['date']);
        $dateB = new DateTime($b['date']);
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

<link rel="stylesheet" href="../resources/styles/card.css">

<div class="p-4 overflow-auto h-100">
    <nav style="--bs-breadcrumb-divider:'>';font-size:14px">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><i class="fa-solid fa-house"></i></li>
            <li class="breadcrumb-item">Dashboard</li>
            <li class="breadcrumb-item">Expenses</li>
            <li class="breadcrumb-item">Own</li>
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
            <button class="btn btn-blueviolet" data-bs-toggle="modal" data-bs-target="#add-expense">
                <span class="fa fa-plus"></span>
            </button>
        </div>


        <div class="col-12 col-md-10 my-2 mb-2">
            <form id="searchForm" class="d-flex" method="post" action="">
                <div class="form-group me-2 flex-grow-1">
                    <input type="text" class="form-control" id="descriptionFilter" name="descriptionFilter"
                        placeholder="Search by description" value="<?php echo $descriptionFilter; ?>">
                </div>
            </form>
        </div>
        <div class="col-12 col-md-1 my-2 mb-2">
            <div class="dropdown">
                <button class="btn btn btn-blueviolet-reverse dropdown-toggle" type="button" id="filterDropdownButton"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-list"></i>
                </button>
                <ul class="dropdown-menu ps-1" aria-labelledby="filterDropdownButton">
                    <li class="mx-2">
                        <form method="post" action="">
                            <div class="form-group">
                                Categories:
                                <select class="form-select" id="expenseCategoryFilter" name="expenseCategoryFilter"
                                    onchange="this.form.submit()">
                                    <option value="">None</option>
                                    <?php
                            $categories = getAllCategories();
                            foreach ($categories as $category) {
                                $selected = ($expenseCategoryFilter == $category['userID']) ? 'selected' : '';
                                echo "<option value='{$category['userID']}' $selected>{$category['description']} (ID: {$category['userID']})</option>";
                            }
                            ?>
                                </select>
                            </div>
                        </form>
                    </li>
                    <li class="mx-2">
                        <form method="post" action="">
                            <div class="form-group">
                                Methods:
                                <select class="form-select" id="paymentMethodsFilter" name="paymentMethodsFilter"
                                    onchange="this.form.submit()">
                                    <option value="">None</option>
                                    <?php
                            $methods = getAllMethods();
                            foreach ($methods as $method) {
                                $selected = ($paymentMethodsFilter == $method['userID']) ? 'selected' : '';
                                echo "<option value='{$method['userID']}' $selected>{$method['description']} (ID: {$method['userID']})</option>";
                            }
                            ?>
                                </select>
                            </div>
                        </form>
                    </li>
                    <li class="mx-2">
                        <form method="post" action="">
                            <div class="form-group">
                                <input type="hidden" class="form-control" id="dateFilter" name="dateFilter"
                                    value="<?php echo $dateFilter; ?>">
                            </div>
                            <div class="form-group">
                                Date:
                                <select class="form-select" id="orderDate" name="orderDate"
                                    onchange="this.form.submit()">
                                    <option value="asc" <?php echo ($orderDate == 'asc') ? 'selected' : ''; ?>>Most
                                        Recent
                                    </option>
                                    <option value="desc" <?php echo ($orderDate == 'desc') ? 'selected' : ''; ?>>Oldest
                                    </option>
                                </select>
                            </div>
                        </form>
                    </li>
                    <li class="mx-2">
                        <form method="post" action="">
                            <div class="form-group">
                                <input type="hidden" class="form-control" id="paidAmountFilter" name="paidAmountFilter"
                                    value="<?php echo $paidAmountFilter; ?>">
                            </div>
                            <div class="form-group">
                                Amount:
                                <select class="form-select" id="orderPaidAmount" name="orderPaidAmount"
                                    onchange="this.form.submit()">
                                    <option value="asc" <?php echo ($orderPaidAmount == 'asc') ? 'selected' : ''; ?>>ASC
                                    </option>
                                    <option value="desc" <?php echo ($orderPaidAmount == 'desc') ? 'selected' : ''; ?>>DESC
                                    </option>
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
                                    <option value="Paid"
                                        <?php echo ($paymentStatusFilter == 'Paid') ? 'selected' : ''; ?>>Paid</option>
                                    <option value="Unpaid"
                                        <?php echo ($paymentStatusFilter == 'Unpaid') ? 'selected' : ''; ?>>Unpaid
                                    </option>
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
            <div class="card style" id="expense-card-<?php echo $expense['expense_id']; ?>">
                <div class="row">
                    <div class="col m-2">
                        <h5 class="card-title"><?php echo $expense['description']; ?></h5>
                    </div>
                    <div class="col">
                        <div class="justify-content-end align-items-center mt-2 mx-2">
                            <button type="button" class='btn btn-danger btn-sm float-end m-1' data-bs-toggle="modal"
                                data-bs-target="#delete-expense<?= $expense['expense_id']; ?>"><i
                                    class="fas fa-trash-alt"></i></button>

                            <?php if ($expense['payed'] != 1) { ?>
                            <button type="button" class='btn btn-blueviolet btn-sm float-end m-1' data-bs-toggle="modal"
                                data-bs-target="#share-expense<?= $expense['expense_id']; ?>"><i
                                    class="fas fa-share"></i></button>
                            <button type="button" class='btn btn-blueviolet btn-sm float-end m-1' data-bs-toggle="modal"
                                data-bs-target="#edit-expense<?= $expense['expense_id']; ?>"><i
                                    class="fas fa-pencil-alt"></i></button>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col justify-content-center">
                            <p class="card-text"><strong>Category:</strong>
                                <?php echo $expense['category_description']; ?></p>
                            <?php if ($expense['payed'] == 1) : ?>
                            <p class="card-text"><strong>Payment Method:</strong>
                                <?php echo $expense['payment_description']; ?></p>
                            <?php endif; ?>
                            <p class="card-text"><strong>Amount:</strong> <?php echo $expense['amount']; ?></p>
                            <p class="card-text"><strong>Payed:</strong>
                                <?php echo ($expense['payed'] == 1) ? 'Yes' : 'No'; ?></p>
                            <p class="card-text"><strong>Date:</strong> <?php echo $expense['date']; ?></p>
                        </div>
                        <div class="my-3" style="<?php echo empty($expense['receipt_img']) ? 'display: none;' : ''; ?>">
                            <?php if (!empty($expense['receipt_img'])): ?>
                            <?php
                                            $receipt_Data = base64_decode($expense['receipt_img']);
                                            $receipt_Src = 'data:image/jpeg;base64,' . base64_encode($receipt_Data);
                                        ?>
                            <div class="h-auto w-100">
                                <img src="<?= $receipt_Src ?>" alt="receipt_img"
                                    class="object-fit-cover w-100 img-fluid d-block ui-w-80 mx-auto rounded"
                                    style="max-width: 150px;">
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- MODAL EDIT -->
        <div class="modal fade" id="edit-expense<?= $expense['expense_id']; ?>" tabindex="-1"
            aria-labelledby="edit-expense<?= $expense['expense_id']; ?>" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-title"> Edit Expense </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body pt-0">
                        <form action="../../controllers/expenses/expense.php" method="post"
                            enctype="multipart/form-data">
                            <input type="hidden" name="expense_id" id="expense_id"
                                value="<?php echo $expense['expense_id']; ?>">

                            <!-- Description -->
                            <div class="form-group mt-3">
                                <label>Description</label>
                                <input type="text" class="form-control" id="description" name="description"
                                    placeholder="Expense Description"
                                    value="<?= isset($expense['description']) ? $expense['description'] : '' ?>"
                                    required>
                            </div>
                            <!-- Category -->
                            <div class="form-group mt-3">
                                <label>Category</label>
                                <select class="form-control" id="category" name="category">
                                    <?php
                                            $categories = getAllCategories();
                                            foreach ($categories as $category) {
                                                $selected = isset($expense['category_id']) && $expense['category_id'] == $category['id'] ? 'selected' : '';
                                                echo "<option value='{$category['id']}' $selected>{$category['description']}</option>";
                                            }
                                            ?>
                                </select>
                            </div>
                            <!-- Date -->
                            <div class="form-group mt-3">
                                <label>Date</label>
                                <input type="date" class="form-control" id="date" name="date"
                                    value="<?= isset($expense['date']) ? $expense['date'] : '' ?>" required>
                            </div>
                            <!-- Amount -->
                            <div class="form-group mt-3">
                                <label>Amount</label>
                                <input type="text" class="form-control" id="amount" name="amount"
                                    placeholder="Expense Amount"
                                    value="<?= isset($expense['amount']) ? $expense['amount'] : '' ?>" required>
                            </div>
                            <!-- Paid Checkbox -->
                            <div class="form-check mt-3">
                                <input class="form-check-input" type="checkbox" name="payed" id="payed"
                                    <?= isset($expense['payed']) && $expense['payed'] == 1 ? 'checked' : '' ?>>
                                <label class="form-check-label">Paid?</label>
                            </div>
                            <!-- Payment Method -->
                            <div class="form-group mt-3" id="paymentBox">
                                <label>Payment Method</label>
                                <select class="form-control" id="method" name="method">
                                    <?php
                                            $methods = getAllMethods();
                                            foreach ($methods as $method) {
                                                $selectedMethod = isset($expense['payment_id']) && $expense['payment_id'] == $method['id'] ? 'selected' : '';
                                                echo "<option value='{$method['id']}' $selectedMethod>{$method['description']}</option>";
                                            }
                                            ?>
                                </select>
                            </div>
                            <!-- Note -->
                            <div class="form-group mt-3">
                                <label>Note</label>
                                <textarea class="form-control" id="note" name="note"
                                    placeholder="Expense Note"><?= isset($expense['note']) ? $expense['note'] : '' ?></textarea>
                            </div>
                            <!-- Receipt Image -->
                            <div class="form-group mt-3">
                                <label>Receipt Image</label>
                                <?php if (!empty($expense['receipt_img'])): ?>
                                <?php
                                                $receiptData = base64_decode($expense['receipt_img']);
                                                $receiptSrc = 'data:image/jpeg;base64,' . base64_encode($receiptData);
                                            ?>
                                <div class="h-auto w-100">
                                    <img src="<?= $receiptSrc ?>" alt="receipt_img"
                                        class="object-fit-cover w-100 img-fluid d-block ui-w-80 mx-auto rounded my-3"
                                        style="max-width: 150px;">
                                </div>
                                <?php endif; ?>
                                <input type="file" class="form-control" id="receipt_img" name="receipt_img">
                            </div>
                            <!-- Update Button -->
                            <button type="submit" class="btn btn-blueviolet mt-3" name="user"
                                value="edit">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- MODAL SHARE -->
        <div class="modal fade" id="share-expense<?= $expense['expense_id']; ?>" tabindex="-1"
            aria-labelledby="share-expense<?= $expense['expense_id']; ?>" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Share Expense</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="../../controllers/expenses/expense.php" method="post">
                            <input type="hidden" name="expense_id" id="expense_id"
                                value="<?php echo $expense['expense_id']; ?>">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email of the User to Share With:</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <button type="submit" name="user" value="share" class="btn btn-primary">Share</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- MODAL DELETE -->
        <div class="modal fade" id="delete-expense<?= $expense['expense_id']; ?>" tabindex="-1"
            aria-labelledby="delete-expense<?= $expense['expense_id']; ?>" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Delete Expense</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="../../controllers/expenses/expense.php" method="post">
                            <input type="hidden" name="expense_id" id="expense_id"
                                value="<?php echo $expense['expense_id']; ?>">
                            <div class="mb-3">
                                Do you want to proceed deleting the expense?
                            </div>
                            <button type="submit" name="user" value="delete" class="btn btn-danger">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <?php endforeach; ?>
    </div>

    <!-- MODAL ADD -->
    <div class="modal fade" id="add-expense" tabindex="-1" aria-labelledby="modal-title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title"> Add an expense </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-0">
                    <form action="../../controllers/expenses/expense.php" method="post" enctype="multipart/form-data">
                        <div class="form-group mt-3">
                            <label>Description</label>
                            <input type="text" class="form-control" id="description" name="description"
                                placeholder="Expense Description"
                                value="<?= isset($_POST['description']) ? $_POST['description'] : '' ?>" required>
                        </div>
                        <div class="form-group mt-3">
                            <label>Category</label>
                            <select class="form-control" id="category" name="category">
                                <?php
                                    $categories = getAllCategories();
                                    foreach ($categories as $category) {
                                        $selected = isset($_POST['category']) && $_POST['category'] == $category['id'] ? 'selected' : '';
                                        echo "<option value='{$category['id']}' $selected>{$category['description']}</option>";
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="form-group mt-3">
                            <label>Date</label>
                            <input type="date" class="form-control" id="date" name="date"
                                value="<?= isset($_POST['date']) ? $_POST['date'] : '' ?>" required>
                        </div>
                        <div class="form-group mt-3">
                            <label>Amount</label>
                            <input type="text" class="form-control" id="amount" name="amount"
                                placeholder="Expense Amount"
                                value="<?= isset($_POST['amount']) ? $_POST['amount'] : '' ?>" required>
                        </div>
                        <div class="form-check mt-3">
                            <input class="form-check-input" type="checkbox" name="payed" id="payed"
                                <?= isset($_POST['payed']) && $_POST['payed'] == 'on' ? 'checked' : '' ?>>
                            <label class="form-check-label">Paid?</label>
                        </div>
                        <div class="form-group mt-3" id="paymentBox">
                            <label>Payment Method</label>
                            <select class="form-control" id="method" name="method">
                                <?php
                                    $methods = getAllMethods();
                                    foreach ($methods as $method) {
                                        $selectedMethod = isset($_POST['method']) && $_POST['method'] == $method['id'] ? 'selected' : '';
                                        echo "<option value='{$method['id']}' $selectedMethod>{$method['description']}</option>";
                                    }
                                ?>
                            </select>
                        </div>

                        <div class="form-group mt-3">
                            <label>Receipt Image</label>
                            <input type="file" class="form-control" id="receipt_img" name="receipt_img">
                        </div>

                        <div class="form-group mt-3">
                            <label>Note</label>
                            <textarea class="form-control" id="note" name="note"
                                placeholder="Expense Note"><?= isset($_POST['note']) ? $_POST['note'] : '' ?></textarea>
                        </div>
                        <button type="submit" class="btn btn-blueviolet mt-3" name="user" value="add">Create</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const payedCheckbox = document.getElementById('payed');
        const paymentBox = document.getElementById('paymentBox');

        paymentBox.style.display = payedCheckbox.checked ? 'block' : 'none';

        payedCheckbox.addEventListener('change', function() {
            paymentBox.style.display = this.checked ? 'block' : 'none';
        });

        var form = document.getElementById("searchForm");
        var inputElement = document.getElementById("descriptionFilter");

        setupDebouncer(inputElement, form);
    });
</script>