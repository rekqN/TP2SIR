<?php
require_once __DIR__ . '/../database/connection.php';
require_once __DIR__ . '/expensesRepository.php';
require_once __DIR__ . '/sharedExpensesRepository.php';

date_default_timezone_set('Europe/Lisbon');

function getById($userID) {
    $PDOStatement = $GLOBALS['pdo'] -> prepare('SELECT * FROM USERS WHERE userID = ?;');
    $PDOStatement -> bindValue(1, $userID, PDO::PARAM_INT);
    $PDOStatement -> execute();
    return $PDOStatement -> fetch();
}

function getEmailAddresses($emailAddress) {
    try {
        $sql = 'SELECT * FROM USERS WHERE emailAddress = ? LIMIT 1;';
        $PDOStatement = $GLOBALS['pdo'] -> prepare($sql);
        $PDOStatement -> bindValue(1, $emailAddress);
        $PDOStatement -> execute();
        return $PDOStatement -> fetch();
    } catch (PDOException $e) {
        echo 'Error: ' . $e -> getMessage();
        return null;
    }
}


function getUserIDByEmailAddress($emailAddress) {
    try {
        $sql = 'SELECT userID FROM USERS WHERE emailAddress = :emailAddress AND deletedAt IS NULL';
        $PDOStatement = $GLOBALS['pdo'] -> prepare($sql);
        $PDOStatement -> bindParam(':emailAddress', $emailAddress, PDO::PARAM_STR);
        $PDOStatement -> execute();

        $result = $PDOStatement -> fetch(PDO::FETCH_ASSOC);

        return ($result) ? $result['userID'] : null;
    } catch (PDOException $e) {
        echo 'Error: ' . $e -> getMessage();
        return null;
    }
}

function registerUser($userID) {
    $userID['password'] = password_hash($userID['password'], PASSWORD_DEFAULT);
    $userID['isAdmin'] = false;

    $sqlCreate = "INSERT INTO USERS (firstName, lastName, emailAddress, password, isAdmin, createdAt, updatedAt) VALUES (:firstName, :lastName, :emailAddress, :password, :isAdmin, NOW(), NOW())";
    $PDOStatement = $GLOBALS['pdo'] -> prepare($sqlCreate);

    $success = $PDOStatement -> execute([
        ':firstName' => $userID['firstName'],
        ':lastName' => $userID['lastName'],
        ':emailAddress' => $userID['emailAddress'],
        ':password' => $userID['password'],
        ':isAdmin' => $userID['isAdmin'],
    ]);

    if ($success) {
        $userID['userID'] = $GLOBALS['pdo'] -> lastInsertId();
        return $userID;
    }
    return false;
}

function avatarUpdate($userID, $avatar) {
    $updatedAt = date('Y-m-d H:i:s');

    $sqlUpdate = "UPDATE USERS SET avatar = :avatar, updatedAt = :updatedAt WHERE userID = :userID";
    $PDOStatement = $GLOBALS['pdo'] -> prepare($sqlUpdate);

    $bindParams = [
        ':userID' => $userID,
        ':avatar' => $avatar,
        ':updatedAt' => $updatedAt,
    ];

    $success = $PDOStatement->execute($bindParams);

    return $success;
}


function fullUserUpdate($userID) {
    $passwordUpdate = '';
    $updateFields = [];

    if (isset($userID['password']) && !empty($userID['password'])) {
        $passwordUpdate = ', password = :password';
        $userID['password'] = password_hash($userID['password'], PASSWORD_DEFAULT);
    }

    $userID['updatedAt'] = date('Y-m-d H:i:s');

    $sqlUpdate = "UPDATE USERS SET updatedAt = :updatedAt";

    $bindParams = [
        ':userID' => $userID['userID'],
        ':updatedAt' => $userID['updatedAt'],
    ];

    if (isset($userID['firstName'])) {
        $sqlUpdate .= ', firstName = :firstName';
        $bindParams[':firstName'] = $userID['firstName'];
    }

    if (isset($userID['lastName'])) {
        $sqlUpdate .= ', lastName = :lastName';
        $bindParams[':lastName'] = $userID['lastName'];
    }

    if (isset($userID['emailAddress'])) {
        $sqlUpdate .= ', emailAddress = :emailAddress';
        $bindParams[':emailAddress'] = $userID['emailAddress'];
    }

    if (isset($userID['country'])) {
        $sqlUpdate .= ', country = :country';
        $bindParams[':country'] = $userID['country'];
    }

    if (isset($userID['dateOfBirth'])) {
        $sqlUpdate .= ', dateOfBirth = :dateOfBirth';
        $bindParams[':dateOfBirth'] = $userID['dateOfBirth'];
    }

    if (isset($userID['isAdmin'])) {
        $sqlUpdate .= ', isAdmin = :isAdmin';
        $bindParams[':isAdmin'] = $userID['isAdmin'];
    }

    $sqlUpdate .= $passwordUpdate;
    $sqlUpdate .= ' WHERE userID = :userID';

    $PDOStatement = $GLOBALS['pdo'] -> prepare($sqlUpdate);

    $success = $PDOStatement -> execute($bindParams);

    return $success;
}

function getPasswordHash($userID) {
    $PDOStatement = $GLOBALS['pdo'] -> prepare('SELECT password FROM USERS WHERE userID = ?'); 
    $PDOStatement -> bindValue(1, $userID, PDO::PARAM_INT);
    $PDOStatement -> execute();
        
    $userData = $PDOStatement -> fetch(PDO::FETCH_ASSOC);
   
    if (!$userData) {
        return false;
    }
    return $userData['password'];
}

function passwordUpdate($userID, $passwordHash) {
    $sqlUpdatePassword = "UPDATE USERS SET password = :password, updatedAt = :updatedAt WHERE userID = :userID";
    $PDOStatement = $GLOBALS['pdo'] -> prepare($sqlUpdatePassword);

    $bindParams = [
        ':userID' => $userID,
        ':password' => $passwordHash,
        ':updatedAt' => $userID['updatedAt'],
    ];

    $success = $PDOStatement -> execute($bindParams);

    return $success;
}

function deleteUser($userID) {
    $sqlSelectEmail = "SELECT emailAddress FROM USERS WHERE userID = :userID";
    $selectStatement = $GLOBALS['pdo'] -> prepare($sqlSelectEmail);
    $selectStatement -> execute([':userID' => $userID]);
    $userEmail = $selectStatement -> fetchColumn();

    $newEmail = 'deleted_' . $userEmail;

    $sqlUpdate = "UPDATE USERS SET emailAddress = :newEmailAddress, deletedAt = NOW() WHERE userID = :userID";
    $updateStatement = $GLOBALS['pdo'] -> prepare($sqlUpdate);

    $updateSuccess = $updateStatement -> execute([
        ':userID' => $userID,
        ':newEmail' => $newEmail,
    ]);

    if ($updateSuccess) {
        deleteExpensesByUserID($userID);
        deleteSharedExpensesByUserID($userID);
    }
    return $userEmail;
}
?>