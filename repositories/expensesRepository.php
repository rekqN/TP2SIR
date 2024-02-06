<?php
require_once __DIR__ . '../../database/connection.php';

function getAllExpenseCategories()
{
    $stmt = $GLOBALS['pdo'] -> prepare('SELECT * FROM EXPENSECATEGORIES WHERE expenseCategory IS NOT NULL;');
    $stmt -> execute();

    return $stmt -> fetchAll(PDO::FETCH_ASSOC);
}

function getAllPaymentMethods()
{
    $stmt = $GLOBALS['pdo'] -> prepare('SELECT * FROM PAYMENTMETHODS WHERE paymentMethod IS NOT NULL;');
    $stmt -> execute();

    return $stmt -> fetchAll(PDO::FETCH_ASSOC);
}

function getPaymentMethodByName($paymentMethodName)
{
    global $pdo;

    $query = "SELECT * FROM PAYMENTMETHODS WHERE paymentMethod = :paymentMethod";
    $statement = $pdo -> prepare($query);
    $statement -> execute([':paymentMethod' => $paymentMethodName]);

    $result = $statement -> fetch(PDO::FETCH_ASSOC);

    return $result;
}

function createExpense($expense)
{
    try {
        $sqlCreate = "INSERT INTO EXPENSES ( expenseCategoryID, expenseDescription, paymentMethodID, paidAmount, paymentDate, isFullyPaid, expenseNotes, userID, createdAt, updatedAt) VALUES (:expenseCategoryID, :expenseDescription, :paymentMethodID, :paidAmount, :paymentDate, :isFullyPaid, :expenseNotes, :userID, NOW(), NOW())";
        $PDOStatement = $GLOBALS['pdo'] -> prepare($sqlCreate);

        $success = $PDOStatement -> execute([
            ':expenseCategoryID' => $expense['expenseCategoryID'],
            ':paymentMethodID' => $expense['paymentMethodID'],
            ':expenseDescription' => $expense['expenseDescription'],
            ':paidAmount' => $expense['paidAmount'],
            ':paymentDate' => $expense['paymentDate'],
            ':isFullyPaid' => $expense['isFullyPaid'],
            ':expenseNotes' => $expense['expenseNotes'],
            ':userID' => $expense['userID'],
        ]);

        if ($success) {
            $expense['expenseID'] = $GLOBALS['pdo'] -> lastInsertId();
        }

        return $success;
    } catch (PDOException $e) {
        echo 'Error: ' . $e -> getMessage();
        return false;
    }
}

function getExpenseByExpenseID($expenseID)
{
    global $pdo;
    try {
        $query = "SELECT EXPENSES.*, EXPENSECATEGORIES.expenseCategory AS expense_category, PAYMENTMETHODS.paymentMethod AS payment_method
                FROM EXPENSES
                LEFT JOIN EXPENSECATEGORIES ON EXPENSES.expenseCategoryID = EXPENSECATEGORIES.expenseCategoryID
                LEFT JOIN PAYMENTMETHODS ON EXPENSES.paymentMethodID = PAYMENTMETHODS.paymentMethodID
                WHERE EXPENSES.expenseID = :expenseID";

        $PDOStatement = $pdo -> prepare($query);
        $PDOStatement -> bindParam(':expenseID', $expenseID, PDO::PARAM_INT);
        $PDOStatement -> execute();

        return $PDOStatement -> fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        throw new Exception('Error fetching expense details: ' . $e -> getMessage());
    }
}

function updateExpense($expenseID, $expenseData)
{
    try {
        $sqlUpdate = "UPDATE EXPENSES SET expenseCategoryID = :expenseCategoryID, paymentMethodID = :paymentMethodID, expenseDescription = :expenseDescription, paidAmount = :paidAmount, paymentDate = :paymentDate, isFullyPaid = :isFullyPaid, expenseNotes = :expenseNotes, userID = :userID, updatedAt = CURRENT_TIMESTAMP WHERE expenseID = :expenseID";
        $PDOStatement = $GLOBALS['pdo'] -> prepare($sqlUpdate);

        $params = [
            ':expenseCategoryID' => $expenseData['expenseCategoryID'],
            ':paymentMethodID' => $expenseData['paymentMethodID'],
            ':expenseDescription' => $expenseData['expenseDescription'],
            ':paidAmount' => $expenseData['paidAmount'],
            ':paymentDate' => $expenseData['paymentDate'],
            ':isFullyPaid' => $expenseData['isFullyPaid'],
            ':expenseNotes' => $expenseData['expenseNotes'],
            ':userID' => $expenseData['userID'],
            ':expenseID' => $expenseID,
        ];

        $params = array_filter($params, function ($value) {
            return $value !== '';
        });

        return $PDOStatement -> execute($params);
    } catch (PDOException $e) {
        echo 'Error: ' . $e -> getMessage();
        return false;
    }
}

function deleteExpense($expenseID)
{
    $pdo = $GLOBALS['pdo'];
    $pdo -> beginTransaction();

    try {
        $sqlUpdate = "UPDATE EXPENSES SET deletedAt = NOW() WHERE expenseID = :expenseID";
        $updateStatement = $pdo -> prepare($sqlUpdate);
        $updateStatement -> execute([
            ':expenseID' => $expenseID,
        ]);

        $updateSuccess = $updateStatement -> rowCount() > 0;

        if ($updateSuccess) {
            $sqlSharedUpdate = "UPDATE SHAREDEXPENSES SET deletedAt = NOW() WHERE expenseID = :expenseID AND deletedAt IS NULL AND EXISTS (SELECT 1 FROM EXPENSES WHERE expenseID = :expenseID)";
            $sharedUpdateStatement = $pdo -> prepare($sqlSharedUpdate);
            $sharedUpdateStatement -> execute([
                ':expenseID' => $expenseID,
            ]);
            $pdo -> commit();

            return true;
        } else {
            $pdo -> rollBack();
            return false;
        }
    } catch (PDOException $e) {
        error_log('Error deleting expense: ' . $e -> getMessage());
        $pdo -> rollBack();
        return false;
    }
}

function deleteExpensesByUserID($userID)
{
    $sqlDeleteExpenses = "UPDATE EXPENSES SET deletedAt = NOW() WHERE userID = :userID";
    $deleteStatement = $GLOBALS['pdo'] -> prepare($sqlDeleteExpenses);
    $deleteStatement -> execute([':userID' => $userID]);
}
?>