<?php
require_once __DIR__ . '/../../database/connection.php';
require_once __DIR__ . '/../../repositories/userRepository.php';
@require_once __DIR__ . '/../../validations/session.php';

$user = user();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_avatar'])) {
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
        $data = file_get_contents($_FILES['avatar']['tmp_name']);
        $encodedAvatar = base64_encode($data);

        $success = avatarUpdate($user['userID'], $encodedAvatar);

        if ($success) {
            header('Location: ../../pages/secure/profile.php');
            exit();
        } else {
            $errors[] = '!! FAILED to update profile !!';
        }
    } else {
        $errors[] = '!! FAILED to upload avatar !!';
    }
}

if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    header('Location: ../../pages/secure/profile.php');
    exit();
}
?>