<?php
require_once __DIR__ . '/../database/connection.php';

function countExpensesByUserID($userID) {
    global $pdo;

    $stmt = $pdo -> prepare("SELECT COUNT(*) AS countExpenses FROM EXPENSES WHERE userID = :userID AND isFullyPaid = 0 AND deletedAt IS NULL");
    $stmt -> bindParam(':userID', $userID, PDO::PARAM_INT);
    $stmt -> execute();

    $result = $stmt  ->  fetch(PDO::FETCH_ASSOC);
    return $result['countExpenses'];
}

function countFullyPaidExpensesByUserID($userID) {
    global $pdo;

    $stmt = $pdo -> prepare("SELECT COUNT(*) AS fullyPaidExpenses FROM EXPENSES WHERE userID = :userID AND isFullyPaid = 1 AND deletedAt IS NULL");
    $stmt -> bindParam(':userID', $userID, PDO::PARAM_INT);
    $stmt -> execute();

    $result = $stmt -> fetch(PDO::FETCH_ASSOC);
    return $result['fullyPaidExpenses'];
}

function getExpensesAmountByUserID($userID) {
    global $pdo;

    $stmt = $pdo -> prepare("SELECT SUM(paidAmount) AS expenseAmount FROM EXPENSES WHERE userID = :userID AND deletedAt IS NULL AND isFullyPaid = 0");
    $stmt -> bindParam(':userID', $userID, PDO::PARAM_INT);
    $stmt -> execute();

    $result = $stmt -> fetch(PDO::FETCH_ASSOC);
    return $result['expenseAmount'];
}

function countSharedExpensesByFromUserID($userID) {
    global $pdo;

    $stmt = $pdo -> prepare("SELECT COUNT(*) AS countSharedExpenses FROM SHAREDEXPENSES WHERE fromUserID = :userID AND deletedAt IS NULL");
    $stmt -> bindParam(':userID', $userID, PDO::PARAM_INT);
    $stmt -> execute();

    $result = $stmt -> fetch(PDO::FETCH_ASSOC);
    return $result['countSharedExpenses'];
}

function countSharedExpensesBySentoToUserID($userID) {
    global $pdo;

    $stmt = $pdo -> prepare("SELECT COUNT(*) AS countSharedExpenses FROM SHAREDEXPENSES WHERE sentToUserID = :userID AND deletedAt IS NULL");
    $stmt -> bindParam(':userID', $userID, PDO::PARAM_INT);
    $stmt -> execute();

    $result = $stmt -> fetch(PDO::FETCH_ASSOC);
    return $result['countSharedExpenses'];
}

function getSharedExpensesAmountByUserID($userID) {
    global $pdo;

    try {
        $query = "SELECT SUM(EXPENSES.paidAmount) AS sharedExpensesAmount
                FROM SHAREDEXPENSES 
                INNER JOIN EXPENSES ON SHAREDEXPENSES.expenseID = EXPENSES.expenseID
                WHERE SHAREDEXPENSES.sentToUserID = :userID AND SHAREDEXPENSES.deletedAt IS NULL AND EXPENSES.deletedAt IS NULL AND EXPENSES.isFullyPaid = 0";

        $stmt = $pdo -> prepare($query);
        $stmt -> bindParam(':userID', $userID, PDO::PARAM_INT);
        $stmt -> execute();

        $result = $stmt -> fetch(PDO::FETCH_ASSOC);
        return $result['sharedExpenseAmount'] ?? 0;
    } catch (PDOException $e) {
        echo 'Error: ' . $e -> getMessage();
        return 0;
    }
}
?>