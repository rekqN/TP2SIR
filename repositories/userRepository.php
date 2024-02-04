<?php
require_once __DIR__ . '../../database/connection.php';
require_once __DIR__ . '/expensesRepository.php';
require_once __DIR__ . '/sharedExpensesRepository.php';

date_default_timezone_set('Europe/Lisbon');

function getEmailAddresses($emailAddress)
{
    try {
        $sql = 'SELECT * FROM USERS WHERE emailAddress = ? LIMIT 1;';
        $PDOStatement = $GLOBALS['pdo']->prepare($sql);
        $PDOStatement -> bindValue(1, $emailAddress);
        $PDOStatement -> execute();
        return $PDOStatement -> fetch();
    } catch (PDOException $e) {
        echo 'Error: ' . $e -> getMessage();
        return null;
    }
}


function getIDByEmailAddress($emailAddress)
{
    try {
        $sql = 'SELECT userID FROM USERS WHERE emailAddress = :emailAddress AND deletedAt IS NULL';
        $PDOStatement = $GLOBALS['pdo']->prepare($sql);
        $PDOStatement -> bindParam(':emailAddress', $emailAddress, PDO::PARAM_STR);
        $PDOStatement -> execute();

        $result = $PDOStatement -> fetch(PDO::FETCH_ASSOC);

        return ($result) ? $result['userID'] : null;
    } catch (PDOException $e) {
        echo 'Error: ' . $e -> getMessage();
        return null;
    }
}

function registerUser($user)
{
    $user['password'] = password_hash($user['password'], PASSWORD_DEFAULT);
    $user['isAdmin'] = false;

    $sqlCreate = "INSERT INTO USERS (firstName, lastName, emailAddress, password, isAdmin, createdAt, updatedAt) VALUES (:firstName, :lastName, :emailAddress, :password, :isAdmin, NOW(), NOW())";
    $PDOStatement = $GLOBALS['pdo'] -> prepare($sqlCreate);

    $success = $PDOStatement -> execute([
        ':firstName' => $user['firstName'],
        ':lastName' => $user['lastName'],
        ':emailAddress' => $user['emailAddress'],
        ':password' => $user['password'],
        ':isAdmin' => $user['isAdmin'],
    ]);

    if ($success) {
        $user['userID'] = $GLOBALS['pdo'] -> lastInsertId();
        return $user;
    }
    return false;
}

function avatarUpdate($userID, $avatar)
{
    $user['updatedAt'] = date('Y-m-d H:i:s');

    $sqlUpdate = "UPDATE USERS SET avatar = :avatar, updatedAt = :updatedAt WHERE userID = :userID";
    $PDOStatement = $GLOBALS['pdo'] -> prepare($sqlUpdate);

    $bindParams = [
        ':userID' => $userID,
        ':avatar' => $avatar,
        ':updatedAt' => $user['updatedAt'],
    ];

    $success = $PDOStatement -> execute($bindParams);

    return $success;
}

function fullUserUpdate($user)
{
    $passwordUpdate = '';
    $updateFields = [];

    if (isset($user['password']) && !empty($user['password'])) {
        $passwordUpdate = ', password = :password';
        $user['password'] = password_hash($user['password'], PASSWORD_DEFAULT);
    }

    $user['updatedAt'] = date('Y-m-d H:i:s');

    $sqlUpdate = "UPDATE USERS SET updatedAt = :updatedAt";

    $bindParams = [
        ':userID' => $user['userID'],
        ':updatedAt' => $user['updatedAt'],
    ];

    if (isset($user['firstName'])) {
        $sqlUpdate .= ', firstName = :firstName';
        $bindParams[':firstName'] = $user['firstName'];
    }

    if (isset($user['lastName'])) {
        $sqlUpdate .= ', lastName = :lastName';
        $bindParams[':lastName'] = $user['lastName'];
    }

    if (isset($user['emailAddress'])) {
        $sqlUpdate .= ', emailAddress = :emailAddress';
        $bindParams[':emailAddress'] = $user['emailAddress'];
    }

    if (isset($user['country'])) {
        $sqlUpdate .= ', country = :country';
        $bindParams[':country'] = $user['country'];
    }

    if (isset($user['dateOfBirth'])) {
        $sqlUpdate .= ', dateOfBirth = :dateOfBirth';
        $bindParams[':dateOfBirth'] = $user['dateOfBirth'];
    }

    if (isset($user['isAdmin'])) {
        $sqlUpdate .= ', isAdmin = :isAdmin';
        $bindParams[':isAdmin'] = $user['isAdmin'];
    }

    $sqlUpdate .= $passwordUpdate;
    $sqlUpdate .= ' WHERE userID = :userID';

    $PDOStatement = $GLOBALS['pdo'] -> prepare($sqlUpdate);

    $success = $PDOStatement -> execute($bindParams);

    return $success;
}

function getPasswordHash($userID)
{
    $PDOStatement = $GLOBALS['pdo']->prepare('SELECT password FROM USERS WHERE userID = ?'); 
    $PDOStatement -> bindValue(1, $userID, PDO::PARAM_INT);
    $PDOStatement -> execute();
        
    $userData = $PDOStatement -> fetch(PDO::FETCH_ASSOC);
   
    if (!$userData) {
        return false;
    }
    return $userData['password'];
}

function passwordUpdate($userID, $passwordHash)
{
    $sqlUpdatePassword = "UPDATE USERS SET password = :password, updatedAt = :updatedAt WHERE userID = :userID";
    $PDOStatement = $GLOBALS['pdo'] -> prepare($sqlUpdatePassword);

    $bindParams = [
        ':userID' => $userID,
        ':password' => $passwordHash,
        ':updatedAt' => $user['updatedAt'],
    ];

    $success = $PDOStatement -> execute($bindParams);

    return $success;
}

function deleteUser($userID)
{
    $sqlSelectEmail = "SELECT emailAddress FROM USERS WHERE userID = :userID";
    $selectStatement = $GLOBALS['pdo']->prepare($sqlSelectEmail);
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
        deleteExpensesByUserID($useriD);
        deleteSharedExpensesByUserID($useriD);
    }
    return $userEmail;
}
?>