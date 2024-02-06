<?php
require_once __DIR__ . '../../database/connection.php';

function createSharedExpense($sharedExpenseData)
{
    try {
        $sqlCreate = "INSERT INTO SHAREDEXPENSES (sentToUserID, fromUserID, expenseID, createdAt, updatedAt ) VALUES (:sentToUserID, :fromUserID, :expenseID, NOW(), NOW())";
        $PDOStatement = $GLOBALS['pdo'] -> prepare($sqlCreate);

        $success = $PDOStatement -> execute([
            ':sentToUserID' => $sharedExpenseData['sentToUserID'],
            ':fromUserID' => $sharedExpenseData['fromUserID'],
            ':expenseID' => $sharedExpenseData['expenseID'],
        ]);

        return $success;
    } catch (PDOException $e) {
        echo 'Error: ' . $e -> getMessage();
        return false;
    }
}

function isExpenseShared($expenseID, $fromUserID, $sentToUserID)
{
    try {
        $sql = 'SELECT COUNT(*) FROM SHAREDEXPENSES WHERE expenseID = :expenseID AND fromUserID = :fromUserID AND sentToUserID = :sentToUserID AND deletedAt IS NULL';

        $PDOStatement = $GLOBALS['pdo'] -> prepare($sql);
        $PDOStatement -> bindParam(':expenseID', $expenseID, PDO::PARAM_INT);
        $PDOStatement -> bindParam(':fromUserID', $fromUserID, PDO::PARAM_INT);
        $PDOStatement -> bindParam(':sentToUserID', $sentToUserID, PDO::PARAM_INT);
        $PDOStatement -> execute();

        return $PDOStatement -> fetchColumn() > 0;
    } catch (PDOException $e) {
        echo 'Error: ' . $e -> getMessage();
        return false;
    }
}

function getSharedExpensesByUserID($userID)
{
    try {
        $query = "SELECT EXPENSES.*, EXPENSECATEGORIES.expenseCategory AS expense_category, PAYMENTMETHODS.paymentMethod AS payment_methods,
                SHAREDEXPENSES.sentToUserID, USERS.firstName AS from_first_name, USERS.lastName AS from_last_name
                FROM EXPENSES
                INNER JOIN SHAREDEXPENSES ON EXPENSES.expenseID = SHAREDEXPENSES.expenseID
                LEFT JOIN EXPENSECATEGORIES ON EXPENSES.expenseCategoryID = EXPENSECATEGORIES.expenseCategoryID
                LEFT JOIN PAYMENTMETHODS ON EXPENSES.paymentMethodID = PAYMENTMETHODS.paymentMethodID
                LEFT JOIN USERS ON SHAREDEXPENSES.fromUserID = USERS.userID
                WHERE SHAREDEXPENSES.sentToUserID = :userID AND EXPENSES.deletedAt IS NULL AND SHAREDEXPENSES.deletedAt IS NULL";

        $PDOStatement = $GLOBALS['pdo'] -> prepare($query);
        $PDOStatement -> bindParam(':userID', $userID, PDO::PARAM_INT);
        $PDOStatement -> execute();

        return $PDOStatement -> fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo 'Error: ' . $e -> getMessage();
        return [];
    }
}

function getSharedExpensesBySenderName($senderName)
{
    try {
        $query = "SELECT SE.*, U.firstName AS sender_first_name, U.lastName AS sender_last_name
                EXPENSES.*, EXPENSECATEGORIES.expenseCategory AS expense_category, PAYMENTMETHODS.paymentMethod AS payment_method
                FROM SHAREDEXPENSES SE
                JOIN USERS U ON SE.fromUserID = U.userID
                JOIN EXPENSES ON SE.expenseID = EXPENSES.expenseID
                LEFT JOIN EXPENSECATEGORIES ON EXPENSES.expenseCategoryID = EXPENSECATEGORIES.expenseCategoryID
                LEFT JOIN PAYMENTMETHODS ON EXPENSES.paymentMethodID = PAYMENTMETHODS.paymentMethodID
                WHERE U.firstName LIKE :senderName OR U.lastName LIKE :senderName  AND EXPENSES.deletedAt IS NULL AND SHAREDEXPENSES.deletedAt IS NULL";

        $PDOStatement = $GLOBALS['pdo'] -> prepare($query);
        $nameParam = "%$senderName%";
        $PDOStatement -> bindParam(':senderName', $nameParam, PDO::PARAM_STR);
        $PDOStatement -> execute();

        $result = $PDOStatement -> fetchAll(PDO::FETCH_ASSOC);

        return $result;
    } catch (PDOException $e) {
        echo 'Error: ' . $e -> getMessage();
        return [];
    }
}

function deleteSharedExpense($expenseID, $userID)
{
    try {
        $stmt = $GLOBALS['pdo'] -> prepare('UPDATE SHAREDEXPENSES SET deletedAt = NOW() WHERE expenseID = :expenseID AND sentToUserID = :sentToUserID AND deletedAt IS NULL');

        $stmt -> bindParam(':expenseID', $expenseID, PDO::PARAM_INT);
        $stmt -> bindParam(':sentToUserID', $UserID, PDO::PARAM_INT);

        return $stmt -> execute();
    } catch (PDOException $e) {
        error_log('Error removing shared expense: ' . $e -> getMessage());
        return false;
    }
}

function deleteSharedExpensesByUserID($userID)
{
    $sqlDeleteSahredExpenses = "UPDATE SHAREDEXPENSES SET deletedAt = NOW() WHERE sentToUserID = :userID OR fromUserID = :userID";
    $deleteStatement = $GLOBALS['pdo'] -> prepare($sqlDeleteSahredExpenses);
    $deleteStatement -> execute([':userID' => $userID]);
}
?>