<?php
require_once __DIR__ . '../../database/connection.php';

function getAllExpensesByUserID($userID)
{
    try {
        $query = "SELECT EXPENSES.*, EXPENSECATEGORIES.expenseCategory AS expense_category, PAYMENTMETHODS.paymentMethod AS payment_method
                FROM EXPENSES
                LEFT JOIN EXPENSECATEGORIES ON EXPENSES.expenseCategoryID = EXPENSECATEGORIES.expenseCategoryID
                LEFT JOIN PAYMENTMETHODS ON EXPENSES.paymentMethodID = PAYMENTMETHODS.paymentMethodID
                WHERE EXPENSES.userID = :userID AND EXPENSES.deletedAt IS NULL";

        $PDOStatement = $GLOBALS['pdo'] -> prepare($query);
        $PDOStatement -> bindParam(':userID', $userID, PDO::PARAM_INT);
        $PDOStatement -> execute();

        $expenses = [];

        while ($expensesList = $PDOStatement -> fetch(PDO::FETCH_ASSOC)) {
            $expenses[] = $expensesList;
        }

        return $expenses;
    } catch (PDOException $e) {
        echo 'Error: ' . $e -> getMessage();
        return false;
    }
}

function getExpensesByPaymentDate($userID, $paymentDate)
{
    try {
        $query = "SELECT EXPENSES.*, EXPENSECATEGORIES.expenseCategory AS expense_category, PAYMENTMETHODS.paymentMethod AS payment_method
                FROM EXPENSES
                LEFT JOIN EXPENSECATEGORIES ON EXPENSES.expenseCategoryID = EXPENSECATEGORIES.expenseCategoryID
                LEFT JOIN PAYMENTMETHODS ON EXPENSES.paymentMethodID = PAYMENTMETHODS.paymentMethodID
                WHERE EXPENSES.userID = :userID AND DATE(EXPENSES.paymenteDate) = DATE(:paymentDate) AND EXPENSES.deletedAt IS NULL";

        $PDOStatement = $GLOBALS['pdo'] -> prepare($query);
        $PDOStatement -> bindParam(':userID', $userID, PDO::PARAM_INT);
        $PDOStatement -> bindParam(':paymentDate', $paymentDate, PDO::PARAM_STR);
        $PDOStatement -> execute();

        $expenses = [];

        while ($expensesList = $PDOStatement -> fetch(PDO::FETCH_ASSOC)) {
            $expenses[] = $expensesList;
        }

        return $expenses;
    } catch (PDOException $e) {
        echo 'Error: ' . $e -> getMessage();
        return false;
    }
}

function getExpensesByPaidAmount($userID, $paidAmount)
{
    try {
        $query = "SELECT * FROM EXPENSES WHERE userID = :userID AND paidAmount = :paidAmount AND deletedAt IS NULL";

        $PDOStatement = $GLOBALS['pdo'] -> prepare($query);
        $PDOStatement -> bindParam(':userID', $userID, PDO::PARAM_INT);
        $PDOStatement -> bindParam(':paidAmount', $paidAmount, PDO::PARAM_STR);
        $PDOStatement -> execute();

        $expenses = $PDOStatement -> fetchAll(PDO::FETCH_ASSOC);

        return $expenses;
    } catch (PDOException $e) {
        echo 'Error: ' . $e -> getMessage();
        return false;
    }
}

function getExpensesByExpenseCategoryByUserID($userID, $categoryID)
{
    try {
        $query = "SELECT EXPENSES.*, EXPENSECATEGORIES.expenseCategory AS expense_category, PAYMENTMETHODS.paymentMethod AS payment_method
                FROM EXPENSES
                LEFT JOIN EXPENSECATEGORIES ON EXPENSES.expenseCategoryID = EXPENSECATEGORIES.expenseCategoryID
                LEFT JOIN PAYMENTMETHODS ON EXPENSES.paymentMethodID = PAYMENTMETHODS.paymentMethodID
                WHERE EXPENSES.userID = :userID AND EXPENSES.expenseCategoryID = :expenseCategoryID AND EXPENSES.deletedAt IS NULL";

        $PDOStatement = $GLOBALS['pdo'] -> prepare($query);
        $PDOStatement -> bindParam(':userID', $userID, PDO::PARAM_INT);
        $PDOStatement -> bindParam(':expenseCategoryID', $expenseCategoryID, PDO::PARAM_INT);
        $PDOStatement -> execute();

        $expenses = [];

        while ($expensesList = $PDOStatement -> fetch(PDO::FETCH_ASSOC)) {
            $expenses[] = $expensesList;
        }

        return $expenses;
    } catch (PDOException $e) {
        echo 'Error: ' . $e -> getMessage();
        return false;
    }
}

function getExpensesByPaymentMethodByUserID($userID, $paymentMethodID)
{
    try {
        $query = "SELECT EXPENSES.*, EXPENSECATEGORIES.expenseCategory AS expense_category, PAYMENTMETHODS.paymentMethod AS payment_method
                FROM EXPENSES
                LEFT JOIN EXPENSECATEGORIES ON EXPENSES.expenseCategoryID = EXPENSECATEGORIES.expenseCategoryID
                LEFT JOIN PAYMENTMETHODS ON EXPENSES.paymentMethodID = PAYMENTMETHODS.paymentMethodID
                WHERE EXPENSES.userID = :userID AND EXPENSES.paymentMethodID = :paymentMethodID AND EXPENSES.deletedAt IS NULL";

        $PDOStatement = $GLOBALS['pdo'] -> prepare($query);
        $PDOStatement -> bindParam(':userID', $userID, PDO::PARAM_INT);
        $PDOStatement -> bindParam(':paymentMethodID', $paymentMethodID, PDO::PARAM_INT);
        $PDOStatement -> execute();

        $expenses = [];

        while ($expensesList = $PDOStatement -> fetch(PDO::FETCH_ASSOC)) {
            $expenses[] = $expensesList;
        }

        return $expenses;
    } catch (PDOException $e) {
        echo 'Error: ' . $e -> getMessage();
        return false;
    }
}

function getExpensesByExpenseDescription($userID, $expenseDescription)
{
    try {
        $query = "SELECT EXPENSES.*, EXPENSECATEGORIES.expenseCategory AS expense_category, PAYMENTMETHODS.paymentMethod AS payment_method
                FROM EXPENSES
                LEFT JOIN EXPENSECATEGORIES ON EXPENSES.expenseCategoryID = EXPENSECATEGORIES.expenseCategoryID
                LEFT JOIN PAYMENTMETHODS ON EXPENSES.paymentMethodID = PAYMENTMETHODS.paymentMethodID
                WHERE EXPENSES.userID = :userID AND EXPENSES.expenseDescription LIKE :expenseDescription AND EXPENSES.deletedAt IS NULL";

        $PDOStatement = $GLOBALS['pdo'] -> prepare($query);
        $descriptionParam = "%{$expenseDescription}%";
        $PDOStatement -> bindParam(':userID', $userID, PDO::PARAM_INT);
        $PDOStatement -> bindParam(':expenseDescription', $descriptionParam, PDO::PARAM_STR);
        $PDOStatement -> execute();

        $expenses = [];

        while ($expensesList = $PDOStatement -> fetch(PDO::FETCH_ASSOC)) {
            $expenses[] = $expensesList;
        }

        return $expenses;
    } catch (PDOException $e) {
        echo 'Error: ' . $e -> getMessage();
        return false;
    }
}

function getExpensesByPaymentStatus($userID, $paymentStatus)
{
    try {
        $query = "SELECT EXPENSES.*, EXPENSECATEGORIES.expenseCategory AS expense_category, PAYMENTMETHODS.paymentMethod AS payment_method
                FROM EXPENSES
                LEFT JOIN EXPENSECATEGORIES ON EXPENSES.expenseCategoryID = EXPENSECATEGORIES.expenseCategoryID
                LEFT JOIN PAYMENTMETHODS ON EXPENSES.paymentMethodID = PAYMENTMETHODS.paymentMethodID";

        if ($paymentStatus === 'Paid' || $paymentStatus === 'Unpaid') {
            $paymentStatusValue = ($paymentStatus === 'Paid') ? 1 : 0;
            $query .= ' WHERE EXPENSES.userID = :userID AND EXPENSES.isFullyPaid = :paymentStatusValue AND EXPENSES.deletedAt IS NULL';
        } else {
            $query .= ' WHERE EXPENSES.userID = :userID AND EXPENSES.deletedAt IS NULL';
        }


        $PDOStatement = $GLOBALS['pdo'] -> prepare($query);
        $PDOStatement -> bindParam(':userID', $userID, PDO::PARAM_INT);

        if ($paymentStatus === 'Paid' || $paymentStatus === 'Unpaid') {
            $PDOStatement -> bindParam(':paymentStatusValue', $paymentStatusValue, PDO::PARAM_INT);
        }

        $PDOStatement -> execute();

        $expenses = [];

        while ($expensesList = $PDOStatement -> fetch(PDO::FETCH_ASSOC)) {
            $expenses[] = $expensesList;
        }

        return $expenses;
    } catch (PDOException $e) {
        echo 'Error: ' . $e -> getMessage();
        return false;
    }
}
?>