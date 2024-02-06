<?php
require_once __DIR__ . '/../../validations/user/updatePassword.php';
require_once __DIR__ . '/../../repositories/userRepository.php';
@require_once __DIR__ . '/../../validations/session.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['userID'])) {
        $errors[] = '!! ERROR: User ID is not set in the session !!';
    }

    $user = [
        'userID' => $_SESSION['userID'],
        'currentPassword' => $_POST['currentPassword'],
        'newPassword' => $_POST['newPassword'],
        'confirmNewPassword' => $_POST['confirmNewPassword'],
    ];

    $validationResult = updatePasswordValidation($user['userID'], $user['currentPassword'], $user['newPassword'], $user['confirmNewPassword']);

    if ($validationResult !== true) {
        $errors[] = ' !! ERROR VALIDATING: ' . $validationResult . ' !!';
    }

    if (empty($errors)) {
        $hashedPassword = password_hash($user['newPassword'], PASSWORD_DEFAULT);

        $updateSuccess = passwordUpdate($user['userID'], $hashedPassword);

        if ($updateSuccess) {
            $successMessage = '!! Password updated SUCCESSFULLY !!';
        } else {
            $errors[] = '!! FAILED to update password !!';
        }
    }
} else {
    $errors[] = '!! Invalid request !!';
}

if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    header('Location: ../../pages/secure/profile.php');
    exit();
}

if ($successMessage) {
    $_SESSION['success'] = $successMessage;
    header('Location: ../../pages/secure/profile.php');
    exit();
}
?>