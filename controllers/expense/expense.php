<?php
require_once __DIR__ . '/../../repositories/expensesRepository.php';
require_once __DIR__ . '/../../repositories/userRepository.php';
require_once __DIR__ . '/../../repositories/sharedExpensesRepository.php';
@require_once __DIR__ . '/../../validations/session.php';
@require_once __DIR__ . '/../../validations/expenses/expenseValidation.php';

if (isset($_POST['user'])) {
    $action = $_POST['user'];

    if ($action == 'add') {
        eadd($_POST);
    } elseif ($action == 'edit') {
        $expenseID = $_POST['expenseID'];
        eedit($expenseID, $_POST);
    } elseif ($action == 'delete') {
        $expenseID = $_POST['expenseID'];
        edelete($expenseID);
    } elseif ($action == 'share') {
        $expenseID = $_POST['expenseID'];
        $email = $_POST['emailAddress'];
        eshare($expenseID, $email);
    } elseif ($action == 'remove-shared') {
        $expenseID = $_POST['expenseID'];
        seremove($expenseID);
    }
}

function eadd($postData)
{
    if (!isset($_SESSION['userID'])) {
        $_SESSION['errors'][] = '!! User ID not set in the session !!';
        $params = '?' . http_build_query($postData);
        header('location: /projeto_sir/pages/secure/expensePage.php' . $params);
    }

    $resultValidation = isExpenseValid($postData);

    if (isset($resultValidation['invalid'])) {
        $_SESSION['errors'] = $resultValidation['invalid'];
        $params = '?' . http_build_query($postData);
        header('location: /projeto_sir/pages/secure/expensePage.php' . $params);
    }

    if (is_array($resultValidation)) {
        $user = [
            'userID' => $_SESSION['userID'],
        ];

        $expenseData = [
            'expenseCategoryID' => $resultValidation['expenseCategory'],
            'expenseDescription' => $resultValidation['expenseDescription'],
            'paidAmount' => $resultValidation['paidAmount'],
            'paymentDate' => $resultValidation['paymentDate'],
            'expenseNotes' => $resultValidation['expenseNotes'],
            'userID' => $user['userID'],
        ];

        $expenseData['isFullyPaid'] = isset($resultValidation['isFullyPaid']) ? ($resultValidation['isFullyPaid'] ? 1 : 0) : 0;
        $expenseData['paymentMethodID'] = $expenseData['isFullyPaid'] ? $resultValidation['paymentMethod'] : getPaymentMethodByName('Cash')['paymentMethodID'];

        $result = createExpense($expenseData);

        if ($result) {
            $_SESSION['success'] = '!! Expense created SUCCESSFULLY !!';
        } else {
            error_log("!! ERROR creating expense: " . implode(" - ", $result -> errorInfo()));
        }

        $params = '?' . http_build_query($postData);
        header('location: /projeto_sir/pages/secure/expensePage.php' . $params);
    }
}

function edelete($expenseID)
{
    if (!isset($_SESSION['userID'])) {
        $_SESSION['errors'][] = '!! User ID not set in the session !!';
        header('location: /projeto_sir/pages/secure/expensePage.php');
        exit();
    }

    $deleteSuccess = deleteExpense($expenseID);

    if ($deleteSuccess) {
        $_SESSION['success'] = '!! Expense deleted SUCCESSFULLY !!';
    } else {
        $_SESSION['errors'][] = '!! ERROR deleting expense !!';
        error_log("!! ERROR deleting expense with ID $expenseID: " . implode(" - ", $GLOBALS['pdo'] -> errorInfo()));
    }

    header('location: /projeto_sir/pages/secure/expensePage.php');
    exit();
}

function eedit($expenseID, $postData)
{
    if (!isset($_SESSION['userID'])) {
        $_SESSION['errors'][] = '!! User ID not set in the session !!';
        $params = '?' . http_build_query($postData);
        header('location: /projeto_sir/pages/secure/expensePage.php' . $params);
    }

    $resultValidation = isExpenseValid($postData);

    if (isset($resultValidation['invalid'])) {
        $_SESSION['errors'] = $resultValidation['invalid'];
        $params = '?' . http_build_query($postData);
        header('location: /projeto_sir/pages/secure/expensePage.php' . $params);
    }

    if (is_array($resultValidation)) {
        $user = [
            'userID' => $_SESSION['userID'],
        ];

        $expenseData = [
            'expenseCategoryID' => $resultValidation['expenseCategory'],
            'expenseDescription' => $resultValidation['expenseDescription'],
            'paidAmount' => $resultValidation['paidAmount'],
            'paymentDate' => $resultValidation['paymentDate'],
            'expenseNotes' => $resultValidation['expenseNotes'],
            'userID' => $user['userID'],
        ];

        $expenseData['isFullyPaid'] = isset($resultValidation['isFullyPaid']) ? ($resultValidation['isFullyPaid'] ? 1 : 0) : 0;

        if ($expenseData['isFullyPaid']) {
            $expenseData['paymentMethodID'] = $resultValidation['paymentMethod'];
        } else {
            $cashPaymentMethod = getPaymentMethodByName('Cash');
            $expenseData['paymentMethodID'] = ['paymentMethodID'];
        }

        var_dump($expenseData);

        if (empty($_SESSION['errors']) && updateExpense($expenseID, $expenseData)) {
            $_SESSION['success'] = '!! Expense updated SUCCESSFULLY !!';
        } else {
            $_SESSION['errors'][] = '!! ERROR updating expense. Please try again !!';
        }

        $params = '?' . http_build_query($postData);
        header('location: /projeto_sir/pages/secure/expensePage.php' . $params);
    }
}

function eshare($expenseID, $emailAddress)
{
    try {
        $sentToUserID = getUserIDByEmailAddress($emailAddress);

        if (!$sentToUserID) {
            $_SESSION['errors'][] = '!! User with the email "' . $emailAddress . '" NOT found !!';
            header('location: /projeto_sir/pages/secure/expensePage.php');
            exit();
        }

        $fromUserID = $_SESSION['userID'];

        $shareSuccess = shareExpense($expenseID, $fromUserID, $sentToUserID);

        if ($shareSuccess) {
            $_SESSION['success'] = '!! Expense shared SUCCESFULLY !!';
        } else {
            $_SESSION['errors'][] = '!! ERROR sharing expense !!';
            error_log("!! ERROR sharing expense with ID $expenseID: " . implode(" - ", $GLOBALS['pdo'] -> errorInfo()));
        }

        header('location: /projeto_sir/pages/secure/expensePage.php');
        exit();
    } catch (PDOException $e) {
        $_SESSION['errors'][] = 'Error: ' . $e -> getMessage();
        header('location: /projeto_sir/pages/secure/expensePage.php');
        exit();
    }
}

function shareExpense($expenseID, $fromUserID, $sentToUserID) 
{
    try {
        $isAlreadyShared = isExpenseShared($expenseID, $fromUserID, $sentToUserID);

        if ($isAlreadyShared) {
            $_SESSION['errors'][] = '!! Expense is ALREADY BEING SHARED with the specified user !!';
            return false;
        }

        $sharedExpense = [
            'sentToUserID' => $sentToUserID,
            'fromUserID' => $fromUserID,
            'expenseID' => $expenseID,
        ];

        return createSharedExpense($sharedExpense);
    } catch (PDOException $e) {
        echo 'Error: ' . $e -> getMessage();
        return false;
    }
}

function seremove($expenseID)
{
    if (!isset($_SESSION['userID'])) {
        $_SESSION['errors'][] = '!! User ID not set in the session !!';
        header('location: /projeto_sir/pages/secure/expensePage.php');
        exit();
    }

    $userID = $_SESSION['userID'];

    $success = deleteSharedExpense($expenseID, $userID);

    if ($success) {
        $_SESSION['success'] = '!! Shared expense removed SUCCESSFULLY !!';
    } else {
        $_SESSION['errors'][] = '!! ERROR removing shared expense !!';
        error_log("!! ERROR removing shared expense for user ID $userID and expense ID $expenseID !!");
    }

    header('location: /projeto_sir/pages/secure/expensePage.php');
    exit();
}

?>