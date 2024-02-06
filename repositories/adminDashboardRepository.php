<?php
require_once __DIR__ . '../../database/connection.php';

function countDeletedUsers() {
    $stmt = $GLOBALS['pdo'] -> prepare('SELECT COUNT(*) AS count_deleted FROM USERS WHERE deletedAt IS NOT NULL;');
    $stmt -> execute();
    $countDeleted = $stmt -> fetch(PDO::FETCH_ASSOC)['count_deleted'];
    return $countDeleted;
}

function countActiveUsers() {
    $stmt = $GLOBALS['pdo'] -> prepare('SELECT COUNT(*) AS count_active FROM USERS WHERE deletedAt IS NULL;');
    $stmt -> execute();
    $countActive = $stmt -> fetch(PDO::FETCH_ASSOC)['count_active'];
    return $countActive;
}

function countUsersWithSharedExpenses() {
    $stmt = $GLOBALS['pdo'] -> prepare('SELECT COUNT(DISTINCT fromUserID) AS count_users_with_shared_expenses FROM SHAREDEXPENSES WHERE deletedAt IS NULL;');
    $stmt -> execute();
    return $stmt -> fetch(PDO::FETCH_ASSOC)['count_users_with_shared_expenses'];
}

function countUsersWithExpenses() {
    $stmt = $GLOBALS['pdo'] -> prepare('SELECT COUNT(DISTINCT userID) AS count_users_with_expenses FROM EXPENSES WHERE deletedAt IS NULL;');
    $stmt -> execute();
    return $stmt -> fetch(PDO::FETCH_ASSOC)['count_users_with_expenses'];
}

function countExpensesByCategory() {
    $stmt = $GLOBALS['pdo'] -> prepare('SELECT EC.expenseCategory AS expense_category, COUNT(E.expenseID) AS count_expense FROM EXPENSECATEGORIES EC LEFT JOIN EXPENSES E ON EC.expenseCategoryID = E.expenseCategoryID AND E.deletedAt IS NULL GROUP BY EC.expenseCategoryID;');
    $stmt -> execute();
    return $stmt -> fetchAll(PDO::FETCH_ASSOC);
}

function countExpensesByPaymentMethod() {
    $stmt = $GLOBALS['pdo'] -> prepare('SELECT PM.paymentMethod AS payment_method, COUNT(E.expenseID) AS count_expense FROM PAYMENTMETHODS PM LEFT JOIN EXPENSES E ON PM.paymentMethodID = E.paymentMethodID AND E.deletedAt IS NULL GROUP BY PM.paymentMethodID;');
    $stmt -> execute();
    return $stmt -> fetchAll(PDO::FETCH_ASSOC);
}

function countTotalExpenses() {
    $stmt = $GLOBALS['pdo'] -> prepare('SELECT COUNT(*) AS count_total_expenses FROM EXPENSES WHERE deletedAt IS NULL;');
    $stmt -> execute();
    return $stmt -> fetch(PDO::FETCH_ASSOC)['count_total_expenses'];
}

function getTotalExpensesAmount() {
    $stmt = $GLOBALS['pdo'] -> prepare('SELECT SUM(paidAmount) AS total_expenses_amount FROM EXPENSES WHERE deletedAt IS NULL;');
    $stmt -> execute();
    return $stmt -> fetch(PDO::FETCH_ASSOC)['total_expenses_amount'];
}

function doesCategoryExist($expenseCategory) {
    $stmt = $GLOBALS['pdo'] -> prepare('SELECT COUNT(*) AS count_categories FROM EXPENSECATEGORIES WHERE expenseCategory = :expenseCategory AND deletedAt IS NULL;');
    $stmt -> bindParam(':expenseCategory', $expenseCategory, PDO::PARAM_STR);
    $stmt -> execute();
    return $stmt -> fetch(PDO::FETCH_ASSOC)['count_categories'] > 0;
}

function doesPaymentMethodExist($paymentMethod) {
    $stmt = $GLOBALS['pdo'] -> prepare('SELECT COUNT(*) AS count_payment_methods FROM PAYMENTMETHODS WHERE paymentMethod = :paymentMethod AND deletedAt IS NULL;');
    $stmt -> bindParam(':paymentMethod', $paymentMethod, PDO::PARAM_STR);
    $stmt -> execute();
    return $stmt -> fetch(PDO::FETCH_ASSOC)['count_payment_methods'] > 0;
}
?>