<?php
require_once __DIR__ . '../../../middleware/middleware-user.php';
require_once __DIR__ . '/../../repositories/expensesRepository.php';
require_once __DIR__ . '/../../repositories/sharedExpensesRepository.php';
@require_once __DIR__ . '/../../validations/session.php';
$user = user();

$filterSenderName = isset($_POST['$filterSenderName']) ? $_POST['$filterSenderName'] : '';

    if (!empty($filterSenderName)) {
        $expenses = getSharedExpensesBySenderName($filterSenderName);
    } else {
        $expenses = getSharedExpensesByUserID($user['userID']);
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
            <li class="breadcrumb-item">Shared Expenses</li>
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

    <div class="col-12 col-md-12 my-2">
        <form id="searchForm" class="d-flex" method="post" action="">
            <div class="form-group me-2 flex-grow-1">
                <input type="text" class="form-control" id="$filterSenderName" name="$filterSenderName" placeholder="Who shared this expense with me?" value="<?php echo $filterSenderName; ?>">
            </div>
        </form>
    </div>

    <div class="row row-cols-1 row-cols-md-3 g-3">
        <div class="d-flex justify-content-center w-100">
            <?php if (empty($expenses)) : ?>
            <strong>
                <p class="mt-3" style="color: red">Lucky for you, nobody is sharing expenses with you.</p>
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
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col justify-content-center">
                            <p class="card-text"><strong>Category:</strong><?php echo $expense['expense_category']; ?></p>
                            <?php if ($expense['isFullyPaid'] == 1) : ?>
                            <p class="card-text"><strong>Payment Method:</strong><?php echo $expense['payment_methods']; ?></p>
                            <?php endif; ?>
                            <p class="card-text"><strong>Amount:</strong> <?php echo $expense['paidAmount']; ?></p>
                            <p class="card-text"><strong>Fully Paid?:</strong><?php echo ($expense['isFullyPaid'] == 1) ? 'Yes' : 'No'; ?></p>
                            <p class="card-text"><strong>Date:</strong> <?php echo $expense['paymentDate']; ?></p>
                        </div>
                    </div>
                    <p class="card-text mt-1"><strong>Expense shared by:</strong>
                        <?php echo $expense['from_first_name'] . ' ' . $expense['from_last_name']; ?></p>
                </div>
            </div>
        </div>

        <div class="modal fade" id="delete-expense<?= $expense['expenseID']; ?>" tabindex="-1"
            aria-labelledby="delete-expense<?= $expense['expenseID']; ?>" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Delete Shared Expense</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="../../controllers/expense/expense.php" method="post">
                            <input type="hidden" name="expenseID" id="expenseID" value="<?php echo $expense['expenseID']; ?>">
                            <div class="mb-3">
                                Do you want to proceed removing this shared expense?
                            </div>
                            <button type="submit" name="user" value="remove-shared" class="btn btn-danger">Remove</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var form = document.getElementById("searchForm");
        var inputElement = document.getElementById("$filterSenderName");

        setupDebouncer(inputElement, form);
    });
</script>