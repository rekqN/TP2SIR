<?php
require_once __DIR__ . '../../database/connection.php';

function createUser($userID)
{
    $userID['password'] = password_hash($userID['password'], PASSWORD_DEFAULT);

    $sqlCreate = "INSERT INTO USERS (firstName, lastName, dateOfBirth, password, emailAddress, isAdmin, createdAt,  updatedAt) VALUES ( :firstName, :lastName, :dateOfBirth, :password, :emailAddress, :isAdmin, NOW(), NOW())";
    $PDOStatement = $GLOBALS['pdo'] -> prepare($sqlCreate);

    $success = $PDOStatement -> execute([
        ':firstName' => $userID['firstName'],
        ':lastName' => $userID['lastName'],
        ':dateOfBirth' => $userID['dateOfBirth'],
        ':password' => $userID['password'],
        ':emailAddress' => $userID['emailAddress'],
        ':isAdmin' => $userID['isAdmin'],
    ]);

    if ($success) {
        $userID['userID'] = $GLOBALS['pdo'] -> lastInsertId();
    }

    return $success;
}

function updateAdminUser($userID, $userData)
{
    try {
        $sqlUpdate = "UPDATE users SET firstName = :firstName, lastName = :lastName, country = :country,  dateOfBirth = :dateOfBirth, isAdmin = :isAdmin, updatedAt = CURRENT_TIMESTAMP";

        if (!empty($userData['password'])) {
            $sqlUpdate .= ', password = :password';
            $userData['password'] = password_hash($userData['password'], PASSWORD_DEFAULT);
        }

        $sqlUpdate .= " WHERE userID = :userID";

        $PDOStatement = $GLOBALS['pdo'] -> prepare($sqlUpdate);

        if (empty($userData['dateOfBirth'])) {
            $userData['dateOfBirth'] = null;
        }

        $params = [
            ':firstName' => $userData['firstName'],
            ':lastName' => $userData['lastName'],
            ':country' => $userData['country'],
            ':dateOfBirth' => $userData['dateOfBirth'],
            ':isAdmin' => $userData['isAdmin'],
            ':userID' => $userID,
        ];

        if (!empty($userData['password'])) {
            $params[':password'] = $userData['password'];
        }

        $params = array_filter($params, function ($value) {
            return $value !== '';
        });

        return $PDOStatement -> execute($params);
    } catch (PDOException $e) {
        echo 'Error: ' . $e -> getMessage();
        return false;
    }
}

function getAllUsers()
{
    $stmt = $GLOBALS['pdo'] -> prepare('SELECT * FROM USERS WHERE deletedAt IS NULL;');
    $stmt -> execute();

    return $stmt -> fetchAll(PDO::FETCH_ASSOC);
}

function getUsersByName($userName)
{
    try {
        $query = 'SELECT * FROM USERS WHERE deletedAt IS NULL AND (firstName LIKE :userName OR lastName LIKE :userName)';
        $nameParam = "%{$userName}%";

        $PDOStatement = $GLOBALS['pdo'] -> prepare($query);
        $PDOStatement -> bindParam(':userName', $nameParam, PDO::PARAM_STR);
        $PDOStatement -> execute();

        $users = [];

        while ($userID = $PDOStatement -> fetch(PDO::FETCH_ASSOC)) {
            $users[] = $userID;
        }

        return $users;
    } catch (PDOException $e) {
        echo 'Error: ' . $e -> getMessage();
        return false;
    }
}

function getAllAdmins()
{
    try {
        $query = 'SELECT * FROM USERS WHERE isAdmin = 1 AND deletedAt IS NULL';

        $PDOStatement = $GLOBALS['pdo'] -> prepare($query);
        $PDOStatement -> execute();

        $users = [];

        while ($userID = $PDOStatement -> fetch(PDO::FETCH_ASSOC)) {
            $users[] = $userID;
        }

        return $users;
    } catch (PDOException $e) {
        echo 'Error: ' . $e -> getMessage();
        return false;
    }
}

function getUserByUserID($userID)
{
    try {
        $PDOStatement = $GLOBALS['pdo'] -> prepare('SELECT * FROM USERS WHERE userID = :userID');
        $PDOStatement -> bindParam(':id', $id, PDO::PARAM_INT);
        $PDOStatement -> execute();

        return $PDOStatement -> fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo 'Error: ' . $e -> getMessage();
        return false;
    }
}
?>