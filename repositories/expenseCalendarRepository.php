<?php
require_once __DIR__ . '../../database/connection.php';

function getExpensesToCalendar($userID) {
    global $pdo;

    $stmt = $pdo -> prepare("SELECT expenseID, expenseDescription, paymentDate FROM EXPENSES WHERE userID = :userID AND deletedAt IS NULL");
    $stmt -> bindParam(':userID', $userID, PDO::PARAM_INT);
    $stmt -> execute();
    
    return $stmt -> fetchAll(PDO::FETCH_ASSOC);
}
?>