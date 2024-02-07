<?php

require_once __DIR__ . '/../../repositories/adminRepository.php';
require_once __DIR__ . '/../../repositories/userRepository.php';
require_once __DIR__ . '/../../validations/admin/validate-user.php';
require_once __DIR__ . '/../../validations/admin/validate-update.php';
require_once __DIR__ . '/../../validations/session.php';

if (isset($_POST['user'])) {
    if ($_POST['user'] == 'create') {
        create($_POST);
    }

    if ($_POST['user'] == 'update') {
        $userToEdit = $_POST['user'];
        update($userToEdit, $_POST);
    }

    if ($_POST['user'] == 'delete') {
        $userToDelete = $_POST['user'];
        softDelete($userToDelete);
    }
}

function create($postData)
{
    $resultValidation = validatedUser($postData);

    if (isset($resultValidation['invalid'])) {
        $_SESSION['errors'] = $resultValidation['invalid'];
        header('location: /projeto_sir/pages/secure/userManagementPage.php');
        exit;
    }

    $user = [
        'firstName' => $resultValidation['firstName'],
        'lastName' => $resultValidation['lastName'],
        'dateOfBirth' => $resultValidation['dateOfBirth'],
        'password' => $resultValidation['password'],
        'emailAddress' => $resultValidation['emailAddress'],
        'isAdmin' => isset($resultValidation['isAdmin']) && $resultValidation['isAdmin'] ? 1 : 0,
    ];

    $result = createUser($user);

    if ($result) {
        $_SESSION['success'] = '!! User created SUCCESSFULLY !!';
    } else {
        error_log("!! Error creating user: " . implode(" - ", $GLOBALS['pdo']->errorInfo()));
    }

    header('location: /projeto_sir/pages/secure/userManagementPage.php');
    exit;
}

function update($userID, $postData)
{
    if (!isset($_SESSION['userID'])) {
        $_SESSION['errors'][] = 'User ID not set in the session.';
        $params = '?' . http_build_query($postData);
        header('location: /projeto_sir/pages/secure/userManagementPage.php' . $params);
        return;
    }

    $userData = validatedUpdate($postData);

    if (isset($userData['invalid'])) {
        $_SESSION['errors'] = $userData['invalid'];
        $_SESSION['action'] = 'update';
        $params = '?' . http_build_query($postData);
        header('location: /projeto_sir/pages/secure/userManagementPage.php' . $params);
        return;
    }

    $userID = $postData['user'];

    $success = updateAdminUser($userID, $userData);

    if ($success) {
        $_SESSION['success'] = '!! User SUCCESSFULLY updated !!';
        $data['action'] = 'update';
        $params = '?' . http_build_query($data);
        header('location: /projeto_sir/pages/secure/userManagementPage.php');
    } else {
        $_SESSION['errors'][] = '!! FAILED to update user information !!';
        header('location: /projeto_sir/pages/secure/userManagementPage.php');
    }
}

function softDelete($userID)
{
    $deleteSuccess = deleteUser($userID);

    if ($deleteSuccess) {
        if ($_SESSION['userID'] == $userID) {
            session_unset();
            session_destroy();
    
            setcookie(session_name(), '', time() - 3600);
            setcookie('userID', '', time() - 3600, "/");
            setcookie('firstName', '', time() - 3600, "/");
        }

        $_SESSION['success'] = '!! User deleted SUCCESSFULLY !!';
        $home_url = 'http://' . $_SERVER['HTTP_HOST'] . '/projeto_sir/pages/secure/userManagementPage.php';
        header('Location: ' . $home_url);
        exit();
    } else {
        $_SESSION['errors'][] = '!! ERROR deleting user !!';
    }
}